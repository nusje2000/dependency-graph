# CHANGELOG

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
