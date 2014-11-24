<?php

namespace Spin\Tests;

use StdClass;
use Spin\Container;

class ContainerTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function itBindsFactories()
    {
        $container = $this->getContainerWithBoundMode("bind");

        $first = $container->resolve("foo");

        $this->assertInstanceOf(StdClass::class, $first);
        $this->assertNotSame($first, $container->resolve("foo"));
    }

    /**
     * @param string $mode
     *
     * @return Container
     */
    protected function getContainerWithBoundMode($mode)
    {
        $container = new Container();

        $container->$mode("foo", function() {
            return new StdClass();
        });

        return $container;
    }

    /**
     * @test
     *
     * @return void
     */
    public function itExtendsBoundFactories()
    {
        $container = $this->getContainerWithBoundMode("bind");

        $container->extend("foo", function($foo) {
            $foo->bar = "bar";

            return $foo;
        });

        $first = $container->resolve("foo");

        $this->assertInstanceOf(StdClass::class, $first);
        $this->assertNotSame($first, $container->resolve("foo"));

        $this->assertEquals("bar", $first->bar);
    }

    /**
     * @test
     *
     * @return void
     */
    public function itSharesFactories()
    {
        $container = $this->getContainerWithBoundMode("bindShared");

        $first = $container->resolve("foo");

        $this->assertInstanceOf(StdClass::class, $first);
        $this->assertSame($first, $container->resolve("foo"));
    }

    /**
     * @test
     *
     * @return void
     */
    public function itUnbindsFactories()
    {
        foreach (["bind", "bindShared"] as $mode) {
            $container = $this->getContainerWithBoundMode($mode);

            $this->assertNotNull($container->resolve("foo"));

            $container->unbind("foo");

            $this->assertNull($container->resolve("foo"));
        }
    }

    /**
     * @test
     *
     * @return void
     */
    public function itHasArrayAccess()
    {
        $container = new Container();

        $container["foo"] = function() {
            return new StdClass();
        };

        $this->assertArrayHasKey("foo", $container);
        $this->assertNotNull($container["foo"]);

        unset($container["foo"]);

        $this->assertNull($container["foo"]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function itSharesItself()
    {
        $first = Container::shared();

        $this->assertInstanceOf(Container::class, $first);
        $this->assertSame($first, Container::shared());
    }
}
