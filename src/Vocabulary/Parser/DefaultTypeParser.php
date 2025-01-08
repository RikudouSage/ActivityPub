<?php

namespace Rikudou\ActivityPub\Vocabulary\Parser;

use Error;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use Rikudou\ActivityPub\Dto\Endpoints;
use Rikudou\ActivityPub\Dto\NullID;
use Rikudou\ActivityPub\Dto\OmittedID;
use Rikudou\ActivityPub\Dto\PublicKey;
use Rikudou\ActivityPub\Dto\Source\Source;
use Rikudou\ActivityPub\Exception\InvalidValueException;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use SplFileInfo;
use function Rikudou\ActivityPub\runInNoValidationContext;

final class DefaultTypeParser implements TypeParser
{
    /**
     * @var array<string, class-string<ActivityPubObject>>
     */
    private array $typeMap;

    /**
     * @param array<string, class-string<ActivityPubObject>>|null $typeMap
     */
    public function __construct(
        ?array $typeMap = null,
    ) {
        if ($typeMap === null) {
            $typeMap = $this->createDefaultTypes();
        }

        $this->typeMap = $typeMap;
    }

    public function parse(array $data, bool $allowCustomProperties = false): ActivityPubObject
    {
        $type = $data['type'] ?? null;
        if ($type === null) {
            throw new InvalidValueException('Invalid activity object received, no type provided');
        }

        $className = $this->typeMap[$type] ?? null;
        if ($className === null) {
            throw new InvalidValueException("Invalid activity object received, the type {$type} is not registered");
        }

        try {
            $instance = new $className();
        } catch (Error $e) {
            throw new InvalidValueException('Failed instantiating activity object: ' . $e->getMessage(), previous: $e);
        }

        foreach ($data as $key => $value) {
            if ($key === 'type') {
                continue;
            }
            if ($key === 'id' && $value === null) {
                $value = new NullID();
            } else if ($key === '@context') {
                $key = 'context';
            } else if (is_array($value) && isset($value['type'])) {
                $value = $this->parse($value, $allowCustomProperties);
            } else if (is_array($value) && $key === 'endpoints' && $instance instanceof ActivityPubActor) {
                $value = new Endpoints(...$value);
            } else if (is_array($value) && $key === 'publicKey' && $instance instanceof ActivityPubActor) {
                $value = new PublicKey(...$value);
            } else if (is_array($value) && $key === 'source') {
                $value = new Source(...$value);
            } else if (!property_exists($instance, $key)) {
                if ($allowCustomProperties) {
                    runInNoValidationContext(fn () => $instance->set($key, $value));
                } else {
                    $instance->set($key, $value);
                }
            }

            $instance->$key = $value;
        }
        if (!$instance->id) {
            $instance->id = new OmittedID();
        }

        return $instance;
    }

    public function parseJson(string $data, bool $allowCustomProperties = false): ActivityPubObject
    {
        return $this->parse(json_decode($data, true, flags: JSON_THROW_ON_ERROR), $allowCustomProperties);
    }

    public function registerType(string $typeName, string $class): void
    {
        $this->typeMap[$typeName] = $class;
    }

    /**
     * @return array<class-string<ActivityPubObject>>
     */
    private function createDefaultTypes(): array
    {
        $dirToScan = realpath(__DIR__ . '/..');
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $dirToScan,
            ),
        );

        $namespaceParts = explode('\\', $this::class);
        $namespaceParts = array_slice($namespaceParts, 0, count($namespaceParts) - 2);
        $namespace = implode('\\', $namespaceParts);

        $result = [];

        foreach ($iterator as $file) {
            assert($file instanceof SplFileInfo);
            if (!$file->isFile()) {
                continue;
            }
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $relative = substr($file->getRealPath(), strlen($dirToScan) + 1, -strlen('.php'));
            $className = $namespace . '\\' . str_replace('/', '\\', $relative);
            if (!class_exists($className)) {
                continue;
            }

            if (!is_a($className, ActivityPubObject::class, true)) {
                continue;
            }

            $instance = new ReflectionClass($className)->newInstanceWithoutConstructor();
            assert($instance instanceof ActivityPubObject);

            $result[$instance->type] = $className;
        }

        return $result;
    }
}
