<?php

declare(strict_types=1);

namespace Nusje2000\DependencyGraph\Tests\Builder;

use Nusje2000\DependencyGraph\Builder\Builder;
use Nusje2000\DependencyGraph\Dependency\DependencyTypeEnum;
use Nusje2000\DependencyGraph\Package\PackageInterface;
use PHPUnit\Framework\TestCase;

final class BuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $exampleProjectDir = (string)realpath(__DIR__ . '/../../example-structure');

        $builder = new Builder();
        $graph = $builder->build($exampleProjectDir);
        $packages = $graph->getPackages();

        self::assertCount(8, $packages);

        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-1'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-2'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-3'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-internal-4'));
        self::assertTrue($packages->hasPackageByName('nusje2000/dependency-graph-example-project'));
        self::assertTrue($packages->hasPackageByName('bar/bar-package'));
        self::assertTrue($packages->hasPackageByName('foo/foo-package'));

        $package1 = $packages->getPackageByName('nusje2000/dependency-graph-internal-1');
        self::assertCount(1, $package1->getDependencies());
        self::assertFalse($package1->isFromVendor());
        self::assertTrue($package1->hasDependency('foo/foo-package'));
        self::assertFalse($package1->hasDependency('bar/bar-package'));
        self::assertSame(realpath($exampleProjectDir . '/src/Package1'), $package1->getPackageLocation());
        self::assertCount(2, $package1->getAuthors());

        $author1 = $package1->getAuthors()->getAuthorByName('Maarten Nusteling');
        self::assertSame('Maarten Nusteling', $author1->getName());
        self::assertSame('maarten.nusteling@gmail.com', $author1->getEmail());
        self::assertNull($author1->getRole());
        self::assertNull($author1->getHomepage());

        $author2 = $package1->getAuthors()->getAuthorByName('Other Nusteling');
        self::assertSame('Other Nusteling', $author2->getName());
        self::assertSame('other.nusteling@gmail.com', $author2->getEmail());
        self::assertNull($author2->getRole());
        self::assertNull($author2->getHomepage());

        $package2 = $packages->getPackageByName('nusje2000/dependency-graph-internal-2');
        $this->assertDependency($package2, 'bar/bar-package', '1.0.0', false, DependencyTypeEnum::PACKAGE);
        self::assertCount(1, $package2->getDependencies());
        self::assertFalse($package2->isFromVendor());
        self::assertTrue($package2->hasDependency('bar/bar-package'));
        self::assertSame(realpath($exampleProjectDir . '/src/Package2'), $package2->getPackageLocation());
        self::assertCount(1, $package2->getAuthors());

        $author = $package2->getAuthors()->getAuthorByName('Maarten Nusteling');
        self::assertSame('Maarten Nusteling', $author->getName());
        self::assertNull($author->getEmail());
        self::assertNull($author->getRole());
        self::assertSame('https://github.com/nusje2000', $author->getHomepage());

        $package3 = $packages->getPackageByName('nusje2000/dependency-graph-internal-3');
        self::assertCount(0, $package3->getDependencies());
        self::assertFalse($package3->isFromVendor());
        self::assertSame(realpath($exampleProjectDir . '/src/Package3'), $package3->getPackageLocation());
        self::assertCount(1, $package3->getAuthors());

        $author = $package3->getAuthors()->getAuthorByName('Maarten Nusteling');
        self::assertSame('Maarten Nusteling', $author->getName());
        self::assertNull($author->getEmail());
        self::assertNull($author->getRole());
        self::assertNull($author->getHomepage());

        $package4 = $packages->getPackageByName('nusje2000/dependency-graph-internal-4');
        self::assertCount(2, $package4->getDependencies());
        $this->assertDependency($package4, 'foo/foo-package', '^2.0', false, DependencyTypeEnum::PACKAGE);
        $this->assertDependency($package4, 'bar/bar-package', '^1.0', true, DependencyTypeEnum::PACKAGE);
        self::assertFalse($package4->isFromVendor());
        self::assertSame(realpath($exampleProjectDir . '/src/Package4'), $package4->getPackageLocation());
        self::assertTrue($package4->getAuthors()->isEmpty());
        self::assertCount(0, $package4->getAuthors());

        $projectPackage = $packages->getPackageByName('nusje2000/dependency-graph-example-project');
        self::assertCount(3, $projectPackage->getDependencies());
        $this->assertDependency($projectPackage, 'foo/foo-package', '2.0.0', false, DependencyTypeEnum::PACKAGE);
        $this->assertDependency($projectPackage, 'bar/bar-package', '1.0.0', false, DependencyTypeEnum::PACKAGE);
        $this->assertDependency($projectPackage, 'phpunit/phpunit', '^8.5', true, DependencyTypeEnum::PACKAGE);
        self::assertFalse($projectPackage->isFromVendor());
        self::assertSame($exampleProjectDir, $projectPackage->getPackageLocation());
        self::assertCount(5, $projectPackage->getReplaces());
        self::assertCount(1, $projectPackage->getAuthors());

        $replaces = $projectPackage->getReplaces();

        self::assertTrue($replaces->hasReplaceByName('nusje2000/dependency-graph-internal-1'));
        $replace = $replaces->getReplaceByName('nusje2000/dependency-graph-internal-1');
        self::assertSame('nusje2000/dependency-graph-internal-1', $replace->getPackageName());
        self::assertSame('self.version', $replace->getVersion());

        self::assertTrue($replaces->hasReplaceByName('nusje2000/dependency-graph-internal-2'));
        $replace = $replaces->getReplaceByName('nusje2000/dependency-graph-internal-2');
        self::assertSame('nusje2000/dependency-graph-internal-2', $replace->getPackageName());
        self::assertSame('self.version', $replace->getVersion());

        self::assertTrue($replaces->hasReplaceByName('nusje2000/dependency-graph-internal-3'));
        $replace = $replaces->getReplaceByName('nusje2000/dependency-graph-internal-3');
        self::assertSame('nusje2000/dependency-graph-internal-3', $replace->getPackageName());
        self::assertSame('self.version', $replace->getVersion());

        self::assertTrue($replaces->hasReplaceByName('nusje2000/dependency-graph-internal-4'));
        $replace = $replaces->getReplaceByName('nusje2000/dependency-graph-internal-4');
        self::assertSame('nusje2000/dependency-graph-internal-4', $replace->getPackageName());
        self::assertSame('self.version', $replace->getVersion());

        self::assertTrue($replaces->hasReplaceByName('nusje2000/dependency-graph-internal-5'));
        $replace = $replaces->getReplaceByName('nusje2000/dependency-graph-internal-5');
        self::assertSame('nusje2000/dependency-graph-internal-5', $replace->getPackageName());
        self::assertSame('self.version', $replace->getVersion());

        $author = $projectPackage->getAuthors()->getAuthorByName('Maarten Nusteling');
        self::assertSame('Maarten Nusteling', $author->getName());
        self::assertSame('maarten.nusteling@gmail.com', $author->getEmail());
        self::assertSame('maintainer', $author->getRole());
        self::assertSame('https://github.com/nusje2000', $author->getHomepage());

        $fooPackage = $packages->getPackageByName('foo/foo-package');
        self::assertCount(1, $fooPackage->getDependencies());
        self::assertTrue($fooPackage->hasDependency('bar/bar-package'));
        self::assertTrue($fooPackage->isFromVendor());
        self::assertSame(realpath($exampleProjectDir . '/vendor/foo/package'), $fooPackage->getPackageLocation());

        $barPackage = $packages->getPackageByName('bar/bar-package');
        self::assertCount(0, $barPackage->getDependencies());
        self::assertTrue($barPackage->isFromVendor());
        self::assertSame(realpath($exampleProjectDir . '/vendor/bar/package'), $barPackage->getPackageLocation());
    }

    private function assertDependency(PackageInterface $package, string $name, string $version, bool $isDev, string $type): void
    {
        self::assertTrue($package->hasDependency($name));

        $dependency = $package->getDependency($name);
        self::assertSame($name, $dependency->getName());
        self::assertSame($version, $dependency->getVersionConstraint());
        self::assertSame($isDev, $dependency->isDev());
        self::assertTrue($dependency->getType()->equals(new DependencyTypeEnum($type)));
    }
}
