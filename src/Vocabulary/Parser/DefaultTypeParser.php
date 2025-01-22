<?php

namespace Rikudou\ActivityPub\Vocabulary\Parser;

use Error;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use Rikudou\ActivityPub\Attribute\ObjectArray;
use Rikudou\ActivityPub\Dto\Endpoints;
use Rikudou\ActivityPub\Dto\NullID;
use Rikudou\ActivityPub\Dto\OmittedID;
use Rikudou\ActivityPub\Dto\PublicKey;
use Rikudou\ActivityPub\Dto\Source\Source;
use Rikudou\ActivityPub\Exception\InvalidValueException;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;
use Rikudou\ActivityPub\Vocabulary\Extensions\CustomObject;
use SplFileInfo;
use function Rikudou\ActivityPub\runInNoValidationContext;

final class DefaultTypeParser implements TypeParser
{
    /**
     * @var array<string, class-string<ActivityPubObject | Link>>
     */
    private array $typeMap;

    /**
     * @param array<string, class-string<ActivityPubObject | Link>>|null $typeMap
     */
    public function __construct(
        ?array $typeMap = null,
    ) {
        if ($typeMap === null) {
            $typeMap = $this->createDefaultTypes();
        }

        $this->typeMap = $typeMap;
    }

    public function parse(array $data, bool $allowCustomProperties = false): ActivityPubObject|Link
    {
        $type = $data['type'] ?? null;
        if ($type === null) {
            throw new InvalidValueException('Invalid activity object received, no type provided');
        }

        $className = $this->typeMap[$type] ?? null;
        if ($className === null) {
            $instance = new CustomObject($type);
        }

        try {
            $instance ??= new $className();
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
            } else if (is_array($value) && $this->acceptsTypedObjectsArray($key, $className)) {
                $result = [];
                foreach ($value as $innerKey => $innerValue) {
                    if (is_array($innerValue) && isset($innerValue['type'])) {
                        $result[$innerKey] = $this->parse($innerValue, $allowCustomProperties);
                    } else {
                        $result[$innerKey] = $innerValue;
                    }
                }

                $value = $result;
            }

            if (property_exists($instance, $key)) {
                $instance->$key = $value;
            } else {
                if ($allowCustomProperties) {
                    runInNoValidationContext(fn () => $instance->set($key, $value));
                } else {
                    $instance->set($key, $value);
                }
            }
        }
        if ($instance instanceof ActivityPubObject && !$instance->id) {
            $instance->id = new OmittedID();
        }

        return $instance;
    }

    public function parseJson(string $data, bool $allowCustomProperties = false): ActivityPubObject|Link
    {
        return $this->parse(json_decode($data, true, flags: JSON_THROW_ON_ERROR), $allowCustomProperties);
    }

    public function registerType(string $typeName, string $class): void
    {
        $this->typeMap[$typeName] = $class;
    }

    /**
     * @return array<class-string<ActivityPubObject|Link>>
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

            if (
                !is_a($className, ActivityPubObject::class, true)
                && !is_a($className, Link::class, true)
            ) {
                continue;
            }

            try {
                $instance = new $className();
            } catch (Error) {
                continue;
            }
            assert($instance instanceof ActivityPubObject || $instance instanceof Link);

            $result[$instance->type] = $className;
        }

        return $result;
    }

    private function acceptsTypedObjectsArray(string $property, string $className): bool
    {
        $reflection = new ReflectionClass($className);
        if (!$reflection->hasProperty($property)) {
            return false;
        }

        $property = $reflection->getProperty($property);
        return count($property->getAttributes(ObjectArray::class)) > 0;
    }
}
