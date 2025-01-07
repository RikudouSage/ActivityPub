# ActivityPub for PHP

A strongly typed and developer friendly ActivityPub implementation. All Core and Extended types are implemented.
Also some widely used unofficial extensions.

## Table of contents

<!-- TOC -->
* [ActivityPub for PHP](#activitypub-for-php)
  * [Table of contents](#table-of-contents)
  * [Objects](#objects)
    * [Naming](#naming)
    * [Objects and activities](#objects-and-activities)
    * [Validations](#validations)
    * [Creating your own types](#creating-your-own-types)
  * [Server](#server)
    * [Request signing](#request-signing)
    * [Request validating](#request-validating)
<!-- TOC -->

## Objects

### Naming

All object names are the same as in the ActivityPub/ActivityStreams specifications, with the sole exception of the
base `Object` which is called `BaseObject` because PHP disallows having a class called `Object`.

### Objects and activities

To construct an object, simply create it as you normally would, for example, let's construct a note:

```php
<?php

use Rikudou\ActivityPub\Vocabulary\Extended\Object\Note;
use Rikudou\ActivityPub\Dto\Source\MarkdownSource;

$note = new Note();
$note->id = 'https://example.com/notes/123';
$note->content = 'Hello <strong>there</strong>!';
$note->attributedTo = 'https://example.com/user/some-actor';
$note->to = 'https://example.com/user/some-other-actor';
$note->inReplyTo = 'https://example.com/notes/120';
$note->published = new DateTimeImmutable();
$note->source = new MarkdownSource('Hello **there**');

echo json_encode($note, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

This prints:

```json
{
    "type": "Note",
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "https://example.com/notes/123",
    "attributedTo": "https://example.com/user/some-actor",
    "content": "Hello <strong>there</strong>!",
    "inReplyTo": "https://example.com/notes/120",
    "published": "2025-01-06T22:52:11+01:00",
    "to": [
        "https://example.com/user/some-other-actor"
    ],
    "source": {
        "content": "Hello **there**",
        "mediaType": "text/markdown"
    }
}
```

### Validations

All property assignments are validated using various set of rules depending on the type of the property and object.
There are multiple modes of validation:

- **none** - no validation takes place
- **lax** - not as strict as the **strict** mode, leaves out some stuff that is required by the specification
  but isn't used in real-world scenarios
- **strict** - strict adherence to the ActivityPub/ActivityStreams specifications
- **recommended** - a custom opinionated set of rules, stricter than **strict**, but should prevent you making
  some mistakes which are technically correct but make no real sense. Some bugs are possible for edge cases.

Validations can be changed for each object individually.

For example:

```php
<?php

use Rikudou\ActivityPub\Vocabulary\Extended\Object\Note;

$note = new Note();
$note->id = '123';
```

When running the snippet above, you get this exception:

`Uncaught Rikudou\ActivityPub\Exception\InvalidPropertyValueException: The value for property 'id' is not valid: string(123): the value must be a valid uri`

Now let's do the same with changing the validation level to none:

```php
<?php

use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Extended\Object\Note;

$note = new Note();
$note->validatorMode = ValidatorMode::None;
$note->id = '123';

echo json_encode($note, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

This prints the following JSON:

```json
{
    "type": "Note",
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "123"
}
```

If you don't want to change the validator mode for every object individually, you can also use the `GlobalSettings` class:

```php
<?php

use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\GlobalSettings;
use Rikudou\ActivityPub\Vocabulary\Extended\Object\Note;

GlobalSettings::$validatorMode = ValidatorMode::None;

$note = new Note();
$note->id = '123';

echo json_encode($note, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

The above code prints the same JSON.

### Parsing JSON into types

While exporting ActivityPub objects to JSON is great, you'll need the exact opposite if you want to handle incoming
activities!

Luckily for us, there's a `TypeParser` (more specifically, a class implementing the interface, `DefaultTypeParser`).
Let's take the previous example as our input:

```php
<?php

use Rikudou\ActivityPub\Vocabulary\Extended\Object\Note;
use Rikudou\ActivityPub\Vocabulary\Parser\DefaultTypeParser;

$parser = new DefaultTypeParser();

$json = <<<'JSON'
{
    "type": "Note",
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "https://example.com/notes/123",
    "attributedTo": "https://example.com/user/some-actor",
    "content": "Hello <strong>there</strong>!",
    "inReplyTo": "https://example.com/notes/120",
    "published": "2025-01-06T22:52:11+01:00",
    "to": [
        "https://example.com/user/some-other-actor"
    ],
    "source": {
        "content": "Hello **there**",
        "mediaType": "text/markdown"
    }
}
JSON;

$note = $parser->parseJson($json);

// all the following assertions are true
assert($note instanceof Note);
assert($note->context === "https://www.w3.org/ns/activitystreams");
assert($note->id === "https://example.com/notes/123");
assert((string) $note->attributedTo === "https://example.com/user/some-actor");
assert($note->content === "Hello <strong>there</strong>!");
assert((string) $note->inReplyTo === "https://example.com/notes/120");
assert($note->published->format('c') === "2025-01-06T22:52:11+01:00");
assert(count($note->to) === 1);
assert((string) $note->to[0] === "https://example.com/user/some-other-actor");
assert($note->source->content === "Hello **there**");
assert($note->source->mediaType === "text/markdown");
```

### Creating your own types

All the ActivityPub objects can be extended by your own classes. The built-in ones use property hooks to automatically 
validate the values, but you can do it any other way, just make sure the properties are publicly readable.

Let's create a custom type:

```php
<?php

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

final class Cat extends BaseObject
{
    public string $type {
        get => 'Cat';
    }
}

```

Adding a property is easy:

```php
<?php

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

final class Cat extends BaseObject
{
    public string $type {
        get => 'Cat';
    }

    public ?int $lives = null;
}
```

Now, if you create your cat, you can check out the response JSON:

```php
<?php

$cat = new Cat();
$cat->id = 'https://example.com/meow';
$cat->lives = 9;

echo json_encode($cat, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), PHP_EOL;
```

```json
{
    "type": "Cat",
    "lives": 9,
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "https://example.com/meow"
}
```

Now, if you want to make sure your can always has some lives, you can mark the property as required:

```php
<?php

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

final class Cat extends BaseObject
{
    public string $type {
        get => 'Cat';
    }

    #[RequiredProperty(ValidatorMode::Lax)]
    public ?int $lives = null;
}
```

You also need to specify the minimum validator mode that it's required on. If you set it to `Lax`, it will be required
on `Lax`, `Strict` and `Recommended`. If you set it to `Strict`, it will be required on `Strict` and `Recommended`.

And now creating our cat throws this exception:

```php
<?php

$cat = new Cat();
$cat->id = 'https://example.com/meow';

echo json_encode($cat, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), PHP_EOL;

// Uncaught Rikudou\ActivityPub\Exception\MissingRequiredPropertyException: The property "Cat:lives" is required when running in "Strict" validator mode.
```

Now, let's get fancy and create our cat! And announce it to the world!

```php
<?php

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\GlobalSettings;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;
use Rikudou\ActivityPub\Vocabulary\Extended\Activity\Announce;
use Rikudou\ActivityPub\Vocabulary\Extended\Activity\Create;
use Rikudou\ActivityPub\Vocabulary\Extended\Actor\Person;

final class Cat extends BaseObject
{
    public string $type {
        get => 'Cat';
    }

    #[RequiredProperty(ValidatorMode::Lax)]
    public ?int $lives = null;
}

$cat = new Cat();
$cat->id = 'https://example.com/meow';
$cat->lives = 9;
$cat->name = 'Meowth';

$me = new Person();
$me->id = 'https://example.com/me';
$me->name = 'James';
$me->inbox = 'https://example.com/inbox';
$me->outbox = 'https://example.com/outbox';
$me->following = 'https://example.com/following';
$me->followers = 'https://example.com/following';

$create = new Create();
$create->id = 'https://example.com/create/meow';
$create->actor = $me;
$create->object = $cat;
$create->to = Link::publicAudienceLink(); // a special link that indicates that the target is public

$announcer = new Person();
$announcer->id = 'https://example.com/not-me';
$announcer->name = 'Jessie';
$announcer->inbox = 'https://example.com/inbox-jessie';
$announcer->outbox = 'https://example.com/outbox-jessie';
$announcer->following = 'https://example.com/following-jessie';
$announcer->followers = 'https://example.com/following-jessie';

$announce = new Announce();
$announce->id = 'https://example.com/announce/create/meow';
$announce->to = $create->to;
$announce->actor = $announcer;
$announce->object = $create;

echo json_encode($announce, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT), PHP_EOL;
```

All this prints this complicated-looking ActivityPub activity which can be sent to every ActivityPub server in the whole world!

```json
{
  "type": "Announce",
  "actor": {
    "type": "Person",
    "inbox": "https://example.com/inbox-jessie",
    "outbox": "https://example.com/outbox-jessie",
    "following": "https://example.com/following-jessie",
    "followers": "https://example.com/following-jessie",
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "https://example.com/not-me",
    "name": "Jessie"
  },
  "object": {
    "type": "Create",
    "actor": {
      "type": "Person",
      "inbox": "https://example.com/inbox",
      "outbox": "https://example.com/outbox",
      "following": "https://example.com/following",
      "followers": "https://example.com/following",
      "@context": "https://www.w3.org/ns/activitystreams",
      "id": "https://example.com/me",
      "name": "James"
    },
    "object": {
      "type": "Cat",
      "lives": 9,
      "@context": "https://www.w3.org/ns/activitystreams",
      "id": "https://example.com/meow",
      "name": "Meowth"
    },
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "https://example.com/create/meow",
    "to": [
      "https://www.w3.org/ns/activitystreams#Public"
    ]
  },
  "@context": "https://www.w3.org/ns/activitystreams",
  "id": "https://example.com/announce/create/meow",
  "to": [
    "https://www.w3.org/ns/activitystreams#Public"
  ]
}
```

Now, if your Cat object ever becomes so popular that everything using ActivityPub sends them back and forth,
you might want to register the type in the parser, otherwise it would just throw an exception saying that it
doesn't know about the Cat object.

```php
<?php

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;
use Rikudou\ActivityPub\Vocabulary\Parser\DefaultTypeParser;

final class Cat extends BaseObject
{
    public string $type {
        get => 'Cat';
    }

    #[RequiredProperty(ValidatorMode::Lax)]
    public ?int $lives = null;
}

$parser = new DefaultTypeParser();

$parser->registerType('Cat', Cat::class);

$catJson = <<<'JSON'
{
    "type": "Cat",
    "lives": 9,
    "@context": "https://www.w3.org/ns/activitystreams",
    "id": "https://example.com/meow",
    "name": "Meowth"
}
JSON;

$reconstructedCat = $parser->parseJson($catJson);

// all the following are true
assert($reconstructedCat instanceof Cat);
assert($reconstructedCat->lives === 9);
assert($reconstructedCat->name === 'Meowth');
assert($reconstructedCat->id === 'https://example.com/meow');

```

## Server

In addition to the ActivityPub object, there are also various helpers for implementing ActivityPub in your server.
All of them rely on the PSR abstractions, so it should be easy to use them with your favourite http client or
a framework of choice.

### Request signing

While not part of the ActivityPub protocol itself, you won't get far in the Fediverse without signing your request - almost no
mainstream software accepts activities that are unsigned. For signing to work, each actor must be publicly accessible at the URL
pointed to in its ID and have a `publicKey` property with the public key defined.

For this reason, this package includes two things:

1. A non-standard `publicKey` property available for all actors
   - If you use the `Recommended` validator mode, this property is required for all actors
2. An `ActorKeyGenerator` service which generates a private and public key-pair that should be stored in a database
  for all actors.

Example:

```php
<?php

use Rikudou\ActivityPub\Dto\KeyPair;
use Rikudou\ActivityPub\Dto\PublicKey;
use Rikudou\ActivityPub\Server\OpenSslActorKeyGenerator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Extended\Actor\Person;

function storePrivateKeyInDatabase(ActivityPubActor $actor, KeyPair $keyPair): void
{
    $privateKey = $keyPair->privateKey;
    // todo store it somewhere securely
}

// create a minimal valid Actor object
$me = new Person();
$me->id = 'https://example.com/person/1';
$me->inbox = 'https://example.com/person/1/inbox';
$me->outbox = 'https://example.com/person/1/outbox';
$me->following = 'https://example.com/person/1/following';
$me->followers = 'https://example.com/person/1/followers';

// instantiate a specific implementation of the KeyGenerator interface, there's currently only this one
$keyGenerator = new OpenSslActorKeyGenerator();

// generate the private and public key-pair
// if you provide an instance of actor as the first parameter, it will automatically create
$keyPair = $keyGenerator->generate($me);

// alternatively, you can assign the public key manually
$keyPair = $keyGenerator->generate();
$me->publicKey = new PublicKey(
    // adding #main-key is a convention, and it's not really important what exactly is there, important is that you can fetch
    // the public key at that URL and that it's unique (thus it cannot be the same as the owner id)
    id: $me->id . '#main-key',
    owner: $me->id,
    publicKeyPem: $keyPair->publicKey,
);
```

Now you have an actor who can send signed requests!

Now let's take a look at a hypothetical service that sends your requests:

```php
<?php

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Rikudou\ActivityPub\Server\RequestSigner;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActivity;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;

require_once __DIR__ . '/vendor/autoload.php';

class ActivitySender
{
    public function __construct(
        private RequestFactoryInterface $requestFactory,
        // this is the service used for signing requests, more specifically this is an interface implemented by RequestSignerAndValidator
        private RequestSigner $requestSigner,
        private ClientInterface $httpClient,
    ) {
    }

    public function sendOutgoingActivity(
        ActivityPubActor $actor,
        #[SensitiveParameter]
        string $actorPrivateKey,
        ActivityPubActivity $activity,
    ): void {
        // you should send the activity to other fields as well, this is just for illustration
        $recipients = $activity->to;

        foreach ($recipients as $recipient) {
            // for simplicity, let's assume this is always an actor, but it can also be a link
            assert($recipient instanceof ActivityPubActor);

            // you need to specify at least the request method,
            $request = $this->requestFactory
                ->createRequest('POST', $recipient->inbox)
                ->withBody(
                    Utils::streamFor(
                        json_encode($activity),
                    ),
                )
            ;

            // now let's sign it!
            $request = $this->requestSigner->signRequest(
                $request,
                $actor->publicKey->id,
                $actorPrivateKey,
            );

            $response = $this->httpClient->sendRequest($request);
            if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
                // todo handle bad responses in some way
            }
        }
    }
}
```

### Request validating

Of course the reverse, validating an incoming request, is also possible!

```php
<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rikudou\ActivityPub\Server\RequestValidator;

class IncomingActivityHandler
{
    public function __construct(
        // Just like before, an interface that's implemented by RequestSignerAndValidator
        private RequestValidator $requestValidator,
    ) {
    }

    public function handle(
        ServerRequestInterface $incomingRequest,
    ): ResponseInterface {
        if ($incomingRequest->getMethod() !== 'POST') {
            // todo return 405
        }

        if (!$this->requestValidator->isRequestValid($incomingRequest)) {
            // todo return 403 or something
        }

        // todo handle the request
    }
}

```
