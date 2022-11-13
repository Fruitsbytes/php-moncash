<p align="center">
<a href="https://www.digicelgroup.com/ht/en/moncash/business.html" target="_blank">
<img style="box-shadow: 2px 2px 1px #000000" src="https://www.digicelgroup.com/etc/designs/haiti-en-moncash/_jcr_content/global/headerLogo.asset.spool/MonCash_Logo-180-90-white.png" width="200"></a></p>


🚧 Work in Progress - Do not use 🚧

[![Latest Stable Version](http://poser.pugx.org/fruitsbytes/php-moncash/v)](https://packagist.org/packages/fruitsbytes/php-moncash) [![Total Downloads](http://poser.pugx.org/fruitsbytes/php-moncash/downloads)](https://packagist.org/packages/fruitsbytes/php-moncash) [![Latest Unstable Version](http://poser.pugx.org/fruitsbytes/php-moncash/v/unstable)](https://packagist.org/packages/fruitsbytes/php-moncash) [![License](http://poser.pugx.org/fruitsbytes/php-moncash/license)](https://packagist.org/packages/fruitsbytes/php-moncash) [![PHP Version Require](http://poser.pugx.org/fruitsbytes/php-moncash/require/php)](https://packagist.org/packages/fruitsbytes/php-moncash)
[![Version](http://poser.pugx.org/fruitsbytes/php-moncash/version)](https://packagist.org/packages/fruitsbytes/php-moncash)


Digicel Moncash PHP library
=============
<p align="center">
    <a href="/README.md">EN</a> • <a href="/README.fr.md">FR</a> • <a href="/README.ht.md">HT</a>
</p>

PHP Library to interact with Digicel MonCash mobile money API based on the
official [documentation](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/resources/doc/RestAPI_MonCash_doc.pdf)
.


Features
------------

- Authenticate
- Create Payment
- Create Transfer `(Beta)`
- Get Payment by transactionID
- Get Payment by orderID

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```php
php composer.phar require --prefer-dist fruitsbytes/php-moncash "*"
```

or

```php
composer require fruitsbytes/php-moncash
```

or add in `composer.json` 

```json
{
  ...
  "require": {
    "fruitsbytes/php-moncash": "*"
    ...
  }


```

to the require section of your `composer.json` file.


Prerequiste
-----

<h3>Create an account</h3>

1) Go to the [Moncash Sanbox portal](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/New)
2) Add a new Buisiness
3) Retrieve the <b>clientID</b> and <b>clientSecret</b>

Online videos:
- [Kijan pou mete Moncash sou sit ou pou w vann](https://youtu.be/lE3ejFT11_w)
- [Comment Intégrer l'onglet Moncash Pay à votre commerce online - Technopro Web](https://youtu.be/NiWYrO_E5ik)  (🕊 Osirus)

<p align="center">
<a href="https://www.digicelgroup.com/ht/en/moncash/business.html" target="_blank">
<img style="box-shadow: 2px 2px 1px #000000" 
src="/demo_1.png" width="700"></a></p>



Usage
--------
<h3>Examples</h3>

```php
use Fruitsbytes\PHP\Moncash\Moncash;
use Fruitsbytes\PHP\Moncash\MonCashException;
use Fruitsbytes\PHP\Moncash\PaymentRequestResult;

/**
* @var string $client_id
 */
$client_id ='<your-client-id>';
/**
* @var string $client_secret
 */
$client_secret='<your-client-secret>';

// Initialize a sandbox instance
$monCash = new Moncash( $client_id, $client_secret);

// Initialize a production instance
$monCash = new Moncash( $client_id, $client_secret, 'production');

// Get authentication
$token = $monCash->get_access_token();

// Get redirection url to start the payement in the frontend
try{
    /**
    * @var $result PaymentRequestResult
    */
    $result = $monCash->create_payment( 1000.50, <your-uniq-reference-id>);
    $redirection_url = $result->redirect;
    $token = $result->token;
    
     response(["redirection_url"=>$redirection_url])->json();
    
}catch (MonCashException $e){
 //...
}
```

<b>Note:</b> You can use  [UUID](https://github.com/ramsey/uuid) to generate
semi-random-idempotent ` <uniq-reference-id>`

```php
// Get Payment by your internal reference ID
use Fruitsbytes\PHP\Moncash\PaymentRequestResult;
/**
* @var $paymentResult PaymentRequestResult
 */
$paymentResult = $monCash->get_payment_by_order( '5392b804-4c15-40b2-9049-f7a471df15fd');

// Get Payment by TransactionID from the front-end after the user successfully went through the payment process
$paymentResult = $monCash->get_payment_by_transaction( '1559796839');

//Make a transfer
use Fruitsbytes\PHP\Moncash\TransfertRequestResult;
/**
* @var $transactionResult TransfertRequestResult
*/
$transactionResult = $monCash->transfer(525.00 , '50937007294', 'My description');
 
```

<b>Note:</b> You can
use [PhoneNumberUtil](https://github.com/giggsey/libphonenumber-for-php/blob/master/docs/PhoneNumberUtil.md) to properly
format the phone numbers into the INTERNATIONAL format or validate befor submitting to Digicel.

Other phone number Libraries

- [Google\libphonenumber](https://github.com/google/libphonenumber)
- [libphonenumber-js](https://gitlab.com/catamphetamine/libphonenumber-js#readme)

Online videos :
- [Kijan pou mete Moncash sou sit ou pou w vann](https://youtu.be/lE3ejFT11_w)
- [Comment Intégrer l'onglet Moncash Pay à votre commerce online - Technopro Web](https://youtu.be/NiWYrO_E5ik)
