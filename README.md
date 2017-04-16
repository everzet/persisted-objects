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
$> composer require --dev everzet/persisted-objects
```

Use like this:

```php
$repo = new FileRepository(TEMP_FILE, new AccessorObjectIdentifier('getId'));
$repo->save($user);

$user === $repo->findById($user->getId());

$repo->clear();
```

or like this:

```php
$repo = new InMemoryRepository(new AccessorObjectIdentifier('getId'));
$repo->save($user);

$user === $repo->findById($user->getId());

$repo->clear();
```


#### Custom callback for identifiers

Should you need a custom way to create an identifier — you may have a compound key, universally unique identifiers, or any more complex id generation strategy you can use the `CallbackObjectIdentifier`:

```php
$repo = new InMemoryRepository(new CallbackObjectIdentifier(
    function($obj) { return $obj->getFirstname() . $obj->getLastname(); }
);
$repo->save($user);

$user === $repo->findById($user->getFirstname() . $user->getLastname());

$repo->clear();
```
