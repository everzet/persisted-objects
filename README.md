## Persisted Objects

This repository is a collection of repositories (pun intended) that somebody might find useful
in training or testing exercises. They provide an easy way to create Fakes for your repositories
in the test infrastructure.

### Why?

As stated in the header - for testing and demo purposes. These repos are optimised for cases where
you have less than 20 records in your repository and there's always only one user accessing it at
a time. In these particular cases these repositories are faster. But in every other instance
they're exponentially not.

### Usage

Install with:

```
$> composer require everzet/persisted-objects
```

Use like this:

```php
use Everzet\PersistedObjects\AccessorObjectIdentifier;
use Everzet\PersistedObjects\FileRepository;

$repo = new FileRepository(TEMP_FILE, new AccessorObjectIdentifier('getId'));
$repo->save($user);

$user === $repo->findById($user->getId());

$repo->clear();
```

or like this:


```php
use Everzet\PersistedObjects\CallbackObjectIdentifier;
use Everzet\PersistedObjects\InMemoryRepository;

$repo = new InMemoryRepository(new CallbackObjectIdentifier(
    function($obj) { return $obj->getFirstname() . $obj->getLastname(); }
);
$repo->save($user);

$user === $repo->findById($user->getFirstname() . $user->getLastname());

$repo->clear();
```
