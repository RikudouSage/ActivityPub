<!-- TOC -->
* [ActivityPub for PHP](#activitypub-for-php)
  * [Naming](#naming)
  * [Objects and activities](#objects-and-activities)
  * [Validations](#validations)
  * [Creating your own types](#creating-your-own-types)
<!-- TOC -->

# ActivityPub for PHP

A strongly typed and developer friendly ActivityPub implementation. All Core and Extended types are implemented.
Also some widely used unofficial extensions.

## Naming

All object names are the same as in the ActivityPub/ActivityStreams specifications, with the sole exception of the
base `Object` which is called `BaseObject` because PHP disallows having a class called `Object`.

## Objects and activities

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

## Validations

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

## Creating your own types

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
