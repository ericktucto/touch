<?php

namespace Touch\Tests\Integration;

use PHPUnit\Framework\Attributes\Test;
use Touch\Application;
use Touch\Core\Kernel;
use Touch\Tests\Integration\Fixtures\Definitions\Bar;
use Touch\Tests\Integration\Fixtures\Definitions\Objects\Foo;

class DefinitionTest extends TestCase
{
    #[Test]
    public function is_file_or_directory_config(): void
    {
        $kernel1 = new Kernel('./tests/Integration/Fixtures/Definitions');
        $kernel1->build();
        $this->assertEquals(
            'local',
            $kernel1->getContainer()->get('config')->get('env'),
        );
        $kernel2 = new Kernel('./tests/Integration/Fixtures/config.yml');
        $kernel2->build();
        $this->assertEquals(
            'production',
            $kernel2->getContainer()->get('config')->get('env'),
        );
    }

    #[Test]
    public function can_register_definition(): void
    {
        $kernel = new Kernel('./tests/Integration/Fixtures/config.yml');
        $kernel->add(Foo::class, Bar::class);

        $this->isFooAddedOnDefinition($kernel);
    }

    #[Test]
    public function can_register_definition_file_config(): void
    {
        $kernel = new Kernel('./tests/Integration/Fixtures/Definitions');

        $this->isFooAddedOnDefinition($kernel);
    }

    protected function isFooAddedOnDefinition(Kernel $kernel): void
    {
        $app = Application::create($kernel);
        $foo = $app->getContainer()->get(Foo::class);
        $this->assertInstanceOf(Foo::class, $foo);
        $this->assertTrue($foo->isFoo());
    }
}
