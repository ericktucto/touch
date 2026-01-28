<?php

namespace Touch\Tests\Integration\Fixtures\Definitions;

use Override;
use Touch\Tests\Integration\Fixtures\Definitions\Objects\Foo;

class Bar
{
    public static function create(): Foo
    {
        return new class implements Foo {
            #[Override]
            public function isFoo(): bool
            {
                return true;
            }
        };
    }
}
