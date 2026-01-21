<?php

namespace Touch\Tests\Integration;

use Helmich\JsonAssert\JsonAssertions;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use JsonAssertions;
}
