<?php

use Touch\Tests\Integration\Fixtures\Definitions\Bar;
use Touch\Tests\Integration\Fixtures\Definitions\Objects\Foo;

use function DI\factory;

return [
    Foo::class => factory([Bar::class, "create"]),
];
