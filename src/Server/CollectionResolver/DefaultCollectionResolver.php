<?php

namespace Rikudou\ActivityPub\Server\CollectionResolver;

use Rikudou\ActivityPub\Exception\InvalidValueException;
use Rikudou\ActivityPub\Server\ObjectFetcher\ObjectFetcher;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubCollection;
use Rikudou\ActivityPub\Vocabulary\Core\CollectionPage;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

final readonly class DefaultCollectionResolver implements CollectionResolver
{
    public function __construct(
        private ObjectFetcher $objectFetcher,
    ) {
    }

    public function resolve(ActivityPubCollection $collection): iterable
    {
        if ($collection->items) {
            return $collection->items;
        }

        if (!$collection->first) {
            return [];
        }

        $pageLink = $collection->first;
        do {
            $page = $this->fetchPage($pageLink);
            assert($page instanceof CollectionPage);

            if ($page->items) {
                yield from $page->items;
            }

            $pageLink = $page->next;
        } while ($pageLink);
    }

    private function fetchPage(CollectionPage|Link $collectionPage): CollectionPage
    {
        if ($collectionPage instanceof CollectionPage) {
            return $collectionPage;
        }

        $collectionPage = $this->objectFetcher->fetch($collectionPage);
        if (!$collectionPage instanceof CollectionPage) {
            throw new InvalidValueException("The resource must be a collection page");
        }

        return $collectionPage;
    }
}
