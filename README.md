# Dependency graph
This package can be used to create a list of used packages and their
dependencies.

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
$packages->hasPackageByName('foo/foo-pacakge');

// getting a specific package
$fooPackage = $packages->getPackageByName('foo/foo-pacakge');
$fooPackage->getName(); // return the name of the package (foo/foo-pacakge)
$fooPackage->getDependencies(); // returns a list of dependencies
```
