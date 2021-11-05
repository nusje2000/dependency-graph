# Dependency graph

[![Build](https://github.com/nusje2000/dependency-graph/actions/workflows/build.yml/badge.svg)](https://github.com/nusje2000/dependency-graph/actions/workflows/build.yml)

This package can be used to create a list of used packages and their
dependencies.

### Installation
Using composer:
```
composer require nusje2000/dependency-graph --dev
```

### Usage
```php
use Nusje2000\DependencyGraph\Cache\FileCache;
use Nusje2000\DependencyGraph\DependencyGraph;

// By default the DependencyGraph won't cache the result
$graph = DependencyGraph::build('/path/to/project/root');

// Using the FileCache to cache the dependency graph
$graph = DependencyGraph::build('/path/to/project/root', null, new FileCache());
```

The build method uses 3 parameters:
1. The root path, this is the path to the root of your project.
2. The builder, this class is used to build the dependency graph (leave
null for default implementation).
3. The cache class, this class will be used to cache the result or to
load the graph from if a cache exists.

### Visualizing dependencies
You can visualize the dependency graph by using `vendor/bin/dependency-graph search`.
This console command can be used to search through the graph and look at
information about certain packages. This command is still being worked on.

There is also a command for only visualizing a package (without the
search dialog). i.e. you can use `vendor/bin/dependency-graph info nusje2000/dependency-graph`
to see the dependencies of this package.

### Validating the dependency graph in monolithic repositories
This package can be used to fetch all the packages defined in monolithic
repositories. This has been implemented into a command in the
`nusje2000/composer-monolith` package, which can be used to validate
internal and external dependencies in a monolithic repository.

### Accessing the dependency graph
```php
use Nusje2000\DependencyGraph\DependencyGraph;

// building the dependency graph
$graph = DependencyGraph::build('/path/to/project/root');

// get the root path
$graph->getRootPath();

// get all packages
$packages = $graph->getPackages();

// checking if a package exists
$graph->hasPackage('foo/foo-pacakge');

// getting a specific package
$fooPackage = $graph->getPackage('foo/foo-pacakge');
$fooPackage->getName(); // return the name of the package (foo/foo-pacakge)
$fooPackage->getDependencies(); // returns a list of dependencies
$fooPackage->getPackageLocation(); // returns the location of the package
```
