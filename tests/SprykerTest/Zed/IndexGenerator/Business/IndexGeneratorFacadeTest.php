<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\IndexGenerator\Business;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Spryker\Zed\IndexGenerator\Dependency\Facade\IndexGeneratorToPropelFacadeBridge;
use Spryker\Zed\IndexGenerator\Dependency\Facade\IndexGeneratorToPropelFacadeInterface;
use Spryker\Zed\IndexGenerator\IndexGeneratorDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group IndexGenerator
 * @group Business
 * @group Facade
 * @group IndexGeneratorFacadeTest
 * Add your own group annotations below this line
 */
class IndexGeneratorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\IndexGenerator\IndexGeneratorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGeneratesSchemaFileWithIndexWhenIndexIsMissing(): void
    {
        $propelFacadeBridge = $this->mockPropelFacadeBridge('SchemaWithMissingIndex');
        $this->tester->setDependency(IndexGeneratorDependencyProvider::FACADE_PROPEL, $propelFacadeBridge);
        $indexGeneratorFacade = $this->tester->getFacadeWithMockedConfig('SchemaWithMissingIndex');
        $indexGeneratorFacade->removeIndexSchemaFiles();
        $indexGeneratorFacade->generateIndexSchemaFiles();

        $this->tester->assertSchemaHasIndex();
    }

    /**
     * @return void
     */
    public function testDoesNotGenerateSchemaFileWhenIndexIsDefined(): void
    {
        $propelFacadeBridge = $this->mockPropelFacadeBridge('SchemaWithIndex');
        $this->tester->setDependency(IndexGeneratorDependencyProvider::FACADE_PROPEL, $propelFacadeBridge);
        $indexGeneratorFacade = $this->tester->getFacadeWithMockedConfig('SchemaWithIndex');
        $indexGeneratorFacade->removeIndexSchemaFiles();
        $indexGeneratorFacade->generateIndexSchemaFiles();

        $this->assertFileDoesNotExist($this->tester->getPathToGeneratedSchema());
    }

    /**
     * @return void
     */
    public function testDoesNotGenerateSchemaFileWhenTableNotIndexable(): void
    {
        $propelFacadeBridge = $this->mockPropelFacadeBridge('SchemaWithArchivableBehavior');
        $this->tester->setDependency(IndexGeneratorDependencyProvider::FACADE_PROPEL, $propelFacadeBridge);
        $indexGeneratorFacade = $this->tester->getFacadeWithMockedConfig('SchemaWithArchivableBehavior');
        $indexGeneratorFacade->removeIndexSchemaFiles();
        $indexGeneratorFacade->generateIndexSchemaFiles();

        $this->assertFileDoesNotExist($this->tester->getPathToGeneratedSchema());
    }

    /**
     * @return void
     */
    public function testDoesNotGenerateSchemaFileWhenTableIsExcluded(): void
    {
        $propelFacadeBridge = $this->mockPropelFacadeBridge('SchemaWithMissingIndex');
        $this->tester->setDependency(IndexGeneratorDependencyProvider::FACADE_PROPEL, $propelFacadeBridge);
        $indexGeneratorFacade = $this->tester->getFacadeWithMockedConfig('SchemaWithMissingIndex', ['spy_foo_bar']);
        $indexGeneratorFacade->removeIndexSchemaFiles();
        $indexGeneratorFacade->generateIndexSchemaFiles();

        $this->assertFileDoesNotExist($this->tester->getPathToGeneratedSchema());
    }

    /**
     * @param string $directory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\IndexGenerator\Dependency\Facade\IndexGeneratorToPropelFacadeInterface
     */
    protected function mockPropelFacadeBridge(string $directory): IndexGeneratorToPropelFacadeInterface
    {
        $propelFacadeBridge = $this->getMockBuilder(IndexGeneratorToPropelFacadeBridge::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getSchemaDirectory'])
            ->getMock();
        $propelFacadeBridge->method('getSchemaDirectory')
            ->willReturn(Configuration::dataDir() . $directory);

        return $propelFacadeBridge;
    }
}
