<?php

use Faker\Extension\GeneratorAwareExtensionTrait;
use Faker\Factory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    /**
     * @var \Faker\Generator
     */
    protected $faker;


    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        $this->faker = Factory::create();
        return require __DIR__ . '/../bootstrap/app.php';
    }
}
