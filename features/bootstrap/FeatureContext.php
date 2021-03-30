<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert as PHPUNIT;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $shelf;
    private $basket;

    public function __construct()
    {
        $this->shelf = [];
        $this->basket = [
            'products' => []
        ];
    }

    /**
     * @Given there is a :product, which costs £:price
     */
    public function thereIsAWhichCostsPs($product, $price)
    {
        $this->shelf['products'][$product] = $price;
    }

    /**
     * @When I add the :product to the basket
     */
    public function iAddTheToTheBasket($product)
    {
        $this->basket['shelf']['products'][] = $this->shelf['products'][$product];
    }



}
