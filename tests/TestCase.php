<?php

namespace Tests;

use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Tests\Feature\Concerns\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations,
        WithFaker;

    public function createApplication(): Application
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * Boot the testing helper traits.
     *
     * @return void
     */
    protected function setUpTraits()
    {
        parent::setUpTraits();

        $this->setUpFaker();
    }
}
