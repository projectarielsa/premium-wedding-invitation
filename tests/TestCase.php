<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create a fake Vite manifest to avoid manifest not found errors during testing
        $this->withoutVite();
    }
}
