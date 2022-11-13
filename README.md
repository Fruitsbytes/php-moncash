<p align="center">

<img  src="./FruitsBytes-moncash-php.png?v=2" alt="FruitsBytes-Moncash-PHP">

🚧 Work in Progress - Do not use 🚧

[![Latest Stable Version](http://poser.pugx.org/fruitsbytes/php-moncash/v?style=for-the-badge)](https://packagist.org/packages/fruitsbytes/php-moncash)
[![Total Downloads](http://poser.pugx.org/fruitsbytes/php-moncash/downloads?style=for-the-badge)](https://packagist.org/packages/fruitsbytes/php-moncash)
[![Latest Unstable Version](http://poser.pugx.org/fruitsbytes/php-moncash/v/unstable?style=for-the-badge)](https://packagist.org/packages/fruitsbytes/php-moncash)
[![License](http://poser.pugx.org/fruitsbytes/php-moncash/license?style=for-the-badge)](https://packagist.org/packages/fruitsbytes/php-moncash)
[![PHP Version Require](http://poser.pugx.org/fruitsbytes/php-moncash/require/php?style=for-the-badge)](https://packagist.org/packages/fruitsbytes/php-moncash)
[![Version](http://poser.pugx.org/fruitsbytes/php-moncash/version?style=for-the-badge)](https://packagist.org/packages/fruitsbytes/php-moncash)

<p>
<small> <b>*</b> The Digicel&trade;, MonCash&trade;, Sogebank&trade; and all other trademarks, logos and brand names are the property
of their respective owners. All company, product and service names used in this documentation are for identification purposes
only. Use of these names,trademarks and brands does not imply endorsement. </small>
</p>

<p>
<small>
<a href="https://www.digicelgroup.com/ht/en/moncash/business.html" target="_blank">MonCash&trade;</a>
 is a mobile money service provided by 
<a href="https://www.digicelgroup.com/" target="_blank">Digicel&trade;</a> 
that allows daily transactions between MonCash users, regardless of their location in Haiti. 
Digicel is a pioneer in mobile money. Their financial services  are currently expanding into other markets, specifically in the pacific island with MyCash&trade;
[<a target="_blank" href="https://mycash.com.fj/" >1</a>] [<a target="_blank" href="https://mycash.ws/" >2</a>] 
</small>
</p>

Digicel Moncash PHP library
=============

[en]: ./README.md "English translation"

[fr]: ./README.fr.md "Traduction française"

[ht]: ./README.ht.md "TRadiksyon kreyòl"


🌎 i18n:  [🇺🇸][en] • [🇫🇷][fr] • [🇭🇹][ht]

A library to facilitate Digicel MonCash mobile money integration on your PHP projects via
their [API](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/resources/doc/RestAPI_MonCash_doc.pdf). It
handles both the <u>base</u> (client) and <u>merchant</u> use cases. It
is part of the MonCash SDK provided by FruitsBytes.

Other libraries for this SDK:

+ 🚧  [Laravel]() - Package
+ 🚧  [Wordpress](#wordpress) - Plugin with WooCommerce integration
+ 🚧  [Shopify](#shopify) - App
+ 🚧  [JavaScript](#javaScript) - Support for NodesJs servers and Web clients
+ 🚧  [Angular](#angular) - Configurable Button
+ 🚧  [ReactJS](#reactJS) - Configurable Button
+ 🚧  [VueJS](#vueJS) - Configurable Button
+ 🚧  [Capacitor](#capacitor) - IonicFramework Plugin for Android and IOS with deepLink integration support

<div id="features"></div> 

------------

## Features

- Authentication
- Traffic optimisation (`Advanced`)
- Security: Secret Management (`Advanced`)
- Payment
- Transfer
- 🚧 Idempotence (`Advanced`) 🚧
- Unique orderID generator
- HTML button
- 🚧 Localization (`Advanced`) 🚧
- Retry (`Advanced`)
- Phone Validation (`Advanced`)

<p>Check the  <a href="/CHANGELOG.md">CHANGELOG</a> for additional information on breaking changes and new features.</p>


<div id="installation"></div> 

------------

## Installation

The preferred way to install this extension with all it's dependencies, is
through [composer](http://getcomposer.org/download/).

### Terminal

You can run the installation composer command from the root of your project:

```shell
composer require fruitsbytes/php-moncash
```

alternatively :

```shell
php composer.phar require --prefer-dist fruitsbytes/php-moncash "*"
```

### Config file

or update your `composer.json` and add the package in the `require` section:

```json
{
  "require": {
    "fruitsbytes/php-moncash": "*"
  }
}

```

Run the installation command

```shell
composer install
```

------------

## Prerequiste

In order to interact with the Digicel's API, you need to the credentials for your buisiness application. You can scope
your business operations with multiple sets of credentials. For example you can have on set for websites and another one
for mobile apps with different redirection urls. This can be very useful to manage `deep links`,

To create and manage your credentials:

1) Go to the Moncah portal [test](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/)
   or [live](https://moncashbutton.digicelgroup.com/Moncash-business/)
2) Select your business or add a new one (Note the congratulation and return URL for `deep links` and web integration)
3) Retrieve the `clientID` and `clientSecret` to interact with the API
4) Retrieve the `BusinessKey`

> 💥 <small>IMPORTANT security note:</small>
> <p><small>Save your business secret in a secure place. Do not share the file containing the secret. Change the secret from time to time.:  </small></p>
> <ul>
> <li><small>exclude `.env` from Git commits  </small></li>
> <li><small>Use a third party Secret manager /Vault to store the secret, example: GCP Secret Manager</small></li>
> </ul>
>

---

## Quick Start

After retrieving the credentials ab installing the package you are ready to start using it.

Make sure the environment variables are set:

```shell
MONCASH_CLIENT_ID="<!your-client-id/>"
MONCASH_CLIENT_SECRET="<!your-client-secret/>"
MONCASH_BUSINESS_KEY="<!/>"
MONCASH_MODE="sandbox"
MONCASH_LANG="env"
MONCASH_RSA_KEY_PATH="/secure-path-to-rsa-dir/rsa.txt"
```

### Usage

```php

use Fruitsbytes\PHP\MonCash\API;
use Fruitsbytes\PHP\MonCash\Configuration;

// Create a new instance
$mc = new API();

// Override global Configuration
$config =  new Configuration([ 'lang'=>'ht', 'mode'=>'production']);
$api = new API($config);

```

#### Client

For client facing websites and mobile app, where the client iniates the payment.

```php
// Create a payment
try{
    $payment = $api->client->createPayment($oderId, $amount);
}catch( MonCash\APIException $e){ 
   $message = $e->getMessage();
}


# Re-Authenticate
$response = $api->auth();

# Generate Button form html code
$button = $api->button( $oderId, $amount);
$htmlButton = $button->html();
print($htmlButton);

# Use the Stringable interface
print($button);

# Output Button form in the current page context
$buttonEN = $api->button( $oderId, $amount, 'en');
?>

<div>
    <h4>Payment methods:</h4>
</div>

<?php $buttonEN->render(); ?>

```

```php
# catch server response
$response  = API::intercept();


```

#### Merchant

For application aimed at store clerks or business owners where the client is present at the time of the transaction.

```php

use Fruitsbytes\PHP\MonCash\API\CashIO;
use Fruitsbytes\PHP\MonCash\API\CashIOException;

# Send payment request (cashIn) to client
$sellArabicaCoffee = $api->merchant->cashIn(
                                         'rebo-5ff92ef8-5d56-11ed-9b6a-0242ac120002', // OrderID, reference number
                                         '50934524301' , //  wallet phone number
                                           50.0 // price in gourdes
                                             );
# or
try {
 $cashIn = new CashIO($orderID, $phoneNumber , $amount);
 $sellPrestigeBeer = $api->merchant->cashIn($cashIn, true); // uses $api Configuration
 
 # or 
 $sellPrestigeBeer = $cashIn->process(true); // will use default global config
 
}catch( CashIOException $e){ 
   $message = $e->getMessage();
}


# Cash out - Give money + throw error on failure
$payrollTiJean = $api->merchant->cashOut($orderID, $phoneNumber , $amount, true);

# Bulk async 
/**
* @var CashIO[]
 */
$payrolls = $api->merchant->cashOutAsyncBulk(
  list : [
            [$orderID1, $phoneNumber1 , $amount1],
            [$orderID2, $phoneNumber2 , $amount2],
            [$orderID3, $phoneNumber3 , $amount3],
            ...,
            [$orderID12, $phoneNumber12 , $amount12]
        ]
    );



# Check cashIn/Out status
$sellArabicaCoffee->status; // successful - cached
$sellPrestigeBeer->status; // failed - cached
$api->merchant->io(['transactionIr' => '12323232'])->get()->status;//failed - fresh. use $sellPrestigeBeer->statusVersion  to get the last time this information was checkedn

# Get transaction ID
$api->merchant->io(['oderId' => 'rebo-5ff92ef8-5d56-11ed-9b6a-0242ac120002'])->get()->transacinId; // 12323232




```

For a complete guide please check the [documentation](./docs/en/0_ABOUT.md) or the [code examples](./demo).



-----------


## Playground
You can check the Postman API [online](#todo) or [import](#todo) the .json from this repository.

You can also test your credentials on the OpenAPI 3.1 [documentation](#).

## TODO

- [ ] Version 2.0 migrate to php 8.1 + PhpUnit10 (2023-02-03)
- [ ] Add additional tests
- [ ] Add Additional PhpDoc & [PhpStorm Attributes](https://github.com/JetBrains/phpstorm-attributes)
- [ ] Make a video guide on how to use the library
- [ ] Implement additional Secret Manager
- [ ] Implement additional Token Machine
    - [ ] APC
    - [ ] Flysystem
    - [ ] Memcache
    - [ ] Memcached
    - [ ] MySQL
    - [ ] Redis
- [ ] Implement additional idempotence key maker
    - [ ] MySQL
    - [ ] UUID
- [ ] Add translations
- [ ] Add demos
- [ ] Provide Postman collection
- [ ] Provide OpenApi3.1 Documentation
- [ ] Upgrade to Async support with PHP Fibers 
- [ ] Make a [Github Page](https://pages.github.com/)

---

## Contributing

### Test

When modifyiing
To run the test use the following shell command from this directory.

Set the values of `.env.testing` environment file.

```shell
composer phpunit
```

or

```shell
vendor\bin\phpunit
```

⚠ Reduce credentials being leaked by not using production credentials for testing.

---

## Other

#### MonCash Documentation

- [Client Rest API](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/resources/doc/RestAPI_MonCash_doc.pdf) (
  client facing user interface)
    - [Button](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/resources/doc/MC_Button_Doc.pdf)
- [Merchant Rest API](https://documenter.getpostman.com/view/1199944/UVeJKju3) (admin/merchant facing UI)
- Dashboard: [sandbox](https://sandbox.moncashbutton.digicelgroup.com/) (for test)
  | [production](https://moncashbutton.digicelgroup.com/Moncash-business/Login) (live)

#### Offical Repo

- [ecelestin/ ecelestin-Moncash-sdk-php](https://github.com/ecelestin/ecelestin-Moncash-sdk-php/blob/master/src/PaymentMaker.php)
  ✍ ing. Enadyre celeste

#### Online videos :

| Title                                                                                                           | Link                                                                                                   |
|-----------------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------|
| Kijan pou mete Moncash sou sit ou pou w vann (🇭🇹)  <br/> ✍ Certil Rémy                                                   | [![Video1](https://img.youtube.com/vi/lE3ejFT11_w/1.jpg)](https://www.youtube.com/watch?v=lE3ejFT11_w) |
| Comment Intégrer l'onglet Moncash Pay à votre commerce online - Technopro Web (🇫🇷) <br/> ✍  Osirus Kurt, RIP 🕊 | [![Video2](https://img.youtube.com/vi/NiWYrO_E5ik/1.jpg)](https://www.youtube.com/watch?v=NiWYrO_E5ik) |

## 🔐 Security

If you discover a security vulnerability within this package, please send an email
to [security@anbapyezanman.com](mailto:security@anbapyezanman.com). All security vulnerabilities will be addressed as
soon as possible. You may view our full security policy [here](./SECURITY.md).

## ⚖ License

this library is licensed under [The MIT License](LICENSE).

## 🏢 For Enterprise

[Fruitsbytes](fruitsbytes.com) can deliver commercial support and maintenance for your applications. Save time, reduce
risk, and improve code health, while paying the maintainers of the exact dependencies you use.

contact us at [inquery@anbapyezanman.com](mailto:inquery@anbapyezanman.com)
