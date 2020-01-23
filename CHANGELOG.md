# CHANGELOG

## 2.3.0
 - Added support for replaces on packages using PackageInterface::getReplaces
 - Added support for authors on packages using PackageInterface::getAuthors
 - Deprecated the cache component
 - Deprecated mutating functions on PackageDefinition

## 2.2.1
 - Added node_modules as vendor directory

## 2.2.0
 - added getReplaces function
 - added setReplace function
 - added removeReplace function
 - added JSON_UNESCAPED_UNICODE flag to json_encode for saving composer.json file

## 2.1.1
 - Changed PackageDefinition::addDevDependency to PackageDefinition::setDevDependency

## 2.1.0
 - Removed ValidateCommand

## 2.0.0
 - Moved validate command and validator to https://github.com/nusje2000/composer-monolith

## 1.0.2
 - added `PackageDefinition::createFromDirectory` function
 - added `PackageDefinition::hasDependency` function
 - added `PackageDefinition::setDependency` function
 - added `PackageDefinition::removeDependency` function
 - added `PackageDefinition::getDependencyVersionConstraint` function
 - added `PackageDefinition::hasDevDependency` function
 - added `PackageDefinition::addDevDependency` function
 - added `PackageDefinition::removeDevDependency` function
 - added `PackageDefinition::getDevDependencyVersionConstraint` function
 - added `PackageDefinition::save` function

## 1.0.1
 - Fixed compatibility with the highest phpstan level
