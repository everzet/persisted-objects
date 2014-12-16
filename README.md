## Persisted Objects

This repository is a collection of repositories (pun intended) that somebody might find useful
in training or testing exercises. They provide an easy way to create Fakes for your repositories
in the test infrastructure.

### Usage

Install with:

```
$> composer require everzet/persisted-objects
```

Use like this:

```
$repo = new FileRepository(TEMP_FILE, new ReflectionMethod(YourEntity::class, 'getId'));
$repo->save($user);

$user === $repo->findById($user->getId());

$repo->clear();
```
