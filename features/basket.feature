Feature: Product basket
    In order to buy products
    As a customer
    I need to be able to put interesting products into a basket

    Rules:
    - VAT is 20%
    - Delivery for basket under £10 is £3
    - Delivery for basket over £10 is £2

    Scenario: Buying a single product under £10
        Given there is a "Sith Lord Lightsaber", which costs £5
        When I add the "Sith Lord Lightsaber" to the basket
        When I add the "Sith Lord Lightsaber" to the basket
