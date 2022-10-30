<p align="center">

<img  src="./FruitsBytes-moncash-php.png?v=2" alt="FruitsBytes-Moncash-PHP">

[![Latest Stable Version](http://poser.pugx.org/fruitsbytes/php-moncash/v)](https://packagist.org/packages/fruitsbytes/php-moncash) [![Total Downloads](http://poser.pugx.org/fruitsbytes/php-moncash/downloads)](https://packagist.org/packages/fruitsbytes/php-moncash) [![Latest Unstable Version](http://poser.pugx.org/fruitsbytes/php-moncash/v/unstable)](https://packagist.org/packages/fruitsbytes/php-moncash) [![License](http://poser.pugx.org/fruitsbytes/php-moncash/license)](https://packagist.org/packages/fruitsbytes/php-moncash) [![PHP Version Require](http://poser.pugx.org/fruitsbytes/php-moncash/require/php)](https://packagist.org/packages/fruitsbytes/php-moncash)
[![Version](http://poser.pugx.org/fruitsbytes/php-moncash/version)](https://packagist.org/packages/fruitsbytes/php-moncash)

<p>
<small> <b style="color: red">*</b> The Digicel&trade;, MonCash&trade;, Sogebank&trade; and all other trademarks, logos and brand names are the property
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

PHP Library to interact with Digicel MonCash mobile money API based on the
official [documentation](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/resources/doc/RestAPI_MonCash_doc.pdf)
.

1) [Features](#features)
2) [Installation](#installation)
3) [Prerequisite](#prerequisite)
    1) [Security](#security)
4) [Usage](#usage)
    1) [Set environment](#env)
        1) [Use .env file](#env-file)
        2) [Use static method /Moncash/Moncash::setEnv](#env-setEnv)
        3) [Override global environment variables during instanciation](#env-override)
        4) [$_ENV vs putenv](#env-putenv)
        5) [`Advanced`] [Use third party Secret manager](#env-secret)
    2) [Authenticattion](#authentication)
        1) [Get Token](#authentication-token)
        2) [`Advanced`] [Traffic optimisation](#traffic-optmization)
    3) [Localization](#localization)
    4) [Payment](#payment)
        1) [Create](#creat-payment)
        2) [Get](#get-payment)
    5) [Transfer](#transfer)
    6) [Client side Button](#button)
    7) [Manage secret](#manage-secret)
    8) [Reduce server calls](#server-calls)
    9) [Phone Validation & formating](#phone-validation-formating)
5) [Test](#test)
6) [Other Libraries & complementary documentation](#complementary)
7) [Todo](#todo)

<div id="features"></div> 1. Features
------------

- Authenticattion
- Traffic optimisation (`Advanced`)
- Security: Secret Management (`Advanced`)
- Payment
- Transfer
- HTML button
- Localization (`Advanced`)
- Phone Validation (`Advanced`)

<p>Check the  <a href="/CHANGELOG.md">CHANGELOG</a> for additional information on breaking changes and new features.</p>


<div id="installation"></div> 2. Installation
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


<div id="prerequisite">3. Prerequiste</div>
-----

Get the credentials for your buisiness

1) Go to the [Moncash Sanbox portal](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/New)
2) Select your buisiness or add a new one (Note the congratulation and return URL for DeepLinks and web integration)
3) Retrieve the `clientID` and `clientSecret` to interact with the API
4) Retrieve the `BusinessKey` if you plan on using the Button

<div id="security"></div>💥IMPORT SECURITY NOTE:
Save your buisiness secret in a secure place. Do not share the file containing the secret. Change the secret

example:

- exclude `.env` from Git commits
- Use a third party Secret manager /Vault to store the secret, example: GCP Secret Manager

<div id="usage"></div>4. Usage
--------

<div id="env"></div> 

### 4.i. Set environmental values

<table>
    <tr>
        <td>💣</td>
        <td>
            <small>Check <u>[security notice](#security)</u> for important informmation</small>
        </td>
    </tr>
</table>


This library expects the configuration (secret, mode, clientID....) to be set in the environment value of the host.
Assign the proper value from the Moncash Buisiness dashboard as indicated in the [prerequisite section](#prerequisite).

Check the [.env.example](./.env.example) file for a non-exhaustive list of variables.

On the test server, set the mode (`MONCASH_MODE`) to `sandbox`. It will only work with the snadbox credentials.

Once completed testing contact the Digicel Moncash Business services team via email at `MFS_B.Services@digicelgroup.com`
in order to move to production/live and get the production credentials. ⚠ These credentials will interact with the world
real wallets (yours and your clients).

If you have the permission you can set the host environment depending on the server distribution and service running (
Windows, Ubuntu, Apache, Nginx,...). You can also set them several other ways:


<div id="env-file"></div> 

#### 4.i.a Environment file `.env`

This package is bunndled with `vlucas/phpdotenv` and can read the .env file in the root of the application host. Add
these variables to the <a href="https://docs.docker.com/compose/env-file/" target="_blank"><b>.env</b>
file</a> in the root of your project.

```dotenv
# /root/.env

# Replace <your-client-id> accordingly
MONCASH_CLIENT_ID=<your-client-id>
# Replace <your-client-secret> accordingly
MONCASH_CLIENT_SECRET=<your-client-secret>
# Replace <yourbuisiness-key> acordingly
MONCASH_BUSINESS_KEY=<yourbuisiness-key>
# Enum:sandbox, production. Default: 'sandbox'
MONCASH_MODE=sandbox
# Enum: en, fr, ht. Defualt 'en' 
MONCASH_LANG=en

```

<div id="env-setEnv"></div> 

#### 4.i.b Static helper method

<div id="env-overridev"></div> 

#### 4.i.c Override global environment

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

// Switch credentials
$monCash = new Moncash([$client_id, $client_secret,]);

// Override instance set mode to production
$monCash = new Moncash("mode" => 'production');

// Authenticate
$token = $monCash->getAccessToken();

// Get redirection url to start the payement in the frontend
try{
    /**
    * Create Payment 
    * @var $result PaymentRequestResult
    */
    $result = $monCash->CreatePayment( 1000.50, <your-uniq-reference-id>);
    $redirection_url = $result->redirect;
    $token = $result->token;
    
     response(["redirectionUrl"=>$redirection_url])->json();
    
}catch (MonCashException $e){
 //...
}
```




<div id="env-putenv"></div> 

#### 4.i.d $_ENV vs putenv()

<div id="env-secret"></div> 

#### 4.i.e Use third party Secret manager (ADVANCED)

<table>
    <tr>
        <td>🧙</td>
        <td>
        <small> <u>This configuration is optional and requieres a level of mastery of external library/SDK, but can greatly
improve the Application security if implemented
correctly.</u></small>
        </td>
    </tr>
</table>


It is not recomanded to keep application API keys, passwords, certificates, and other sensitive data in the repository.
For this reason Many cloud providers offer secret manager to help mitigate secret exposition.

The general idea is to set the `host` environment variables via a secure and authorized access to the a `vault`. Based
on
the various implementations from the providers, we can use a uniform approch. Some strategy class are available:

| Name                                                                           | Provider                                                                                                                        | State |
|--------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------|:-----:|
| [Default](./src/PHP/Moncash/Strategy/SecretManager/DefaultSecretManager.php)   | uses `.env file`                                                                                                                |   ✅   |
| [GCP](./src/PHP/Moncash/Strategy/SecretManager/GCPSecretManager.php)           | <a href="https://cloud.google.com/functions/docs/configuring/secrets" target="_blank" >Google Cloud Platform Secret Manager</a> |   ❌   |
| [AWS](./src/PHP/Moncash/Strategy/SecretManager/AWSSecretManager.php)           | <a href="https://aws.amazon.com/secrets-manager/" target="_blank">AWS Secret Manager</a>                                        |   ❌   |
| [Azure](./src/PHP/Moncash/Strategy/SecretManager/AzureSecretManager.php)       | <a href="https://azure.microsoft.com/en-us/products/key-vault/" target="_blank">Azure Key Vault</a>                             |   ❌   |
| [KeyCloak](./src/PHP/Moncash/Strategy/SecretManager/KeyCloakSecretManager.php) | <a href="https://www.keycloak.org/server/vault" target="_blank">Keycloak - Kubernetes/OpenShift secrets</a>                     |   ❌   |
| Vault                                                                          |  <a href="https://www.vaultproject.io/" target="_blank">HashiCorp Vault</a>                                                     |   ❌   |

To add new stategies implement the [SecretManager interface](./src/PHP/Moncash/Strategy/SecretManager/SecretManagerInterface.php) as explained in the section [Manage secret](#manage-secret). 

In the `environment variables` you can specify a Stategy to retrieve the secret :

```dotenv
# /root/.env

```



<b>Note:</b> You can use  [UUID](https://github.com/ramsey/uuid) to generate
semi-random-idempotent ` <uniq-reference-id>`

```php
// Get Payment by your internal reference ID
use Fruitsbytes\PHP\Moncash\PaymentRequestResult;
/**
* @var $paymentResult PaymentRequestResult
 */
$paymentResult = $monCash->getPaymentByOrder( '5392b804-4c15-40b2-9049-f7a471df15fd');

// Get Payment by TransactionID from the front-end after the user successfully went through the payment process
$paymentResult = $monCash->getPaymentByTransaction( '1559796839');

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




- [Google\libphonenumber](https://github.com/google/libphonenumber)
- [libphonenumber-js](https://gitlab.com/catamphetamine/libphonenumber-js#readme)

<div id="test"></div>5. Test
------------


<div id="complementary"></div>6. Other libraries & complementary documentation
------------

Online videos :

- 🇭🇹 [Kijan pou mete Moncash sou sit ou pou w vann](https://youtu.be/lE3ejFT11_w)
- 🇫🇷 [Comment Intégrer l'onglet Moncash Pay à votre commerce online - Technopro Web](https://youtu.be/NiWYrO_E5ik)  (🕊
  Osirus)

<p align="center">
<a href="https://www.digicelgroup.com/ht/en/moncash/business.html" target="_blank">
<img style="box-shadow: 2px 2px 1px #000000"  alt="" src="./demo_1.png"></a>
</p>


<div id="todo"></div>7. TODO
------------

- [ ] migrate to php 8.1 + PhpUnit10 (2023-02-03)
- [ ] Add additional tests
- [ ] Make a video guide on how to use the library
- [ ] Implement additional Secret Manager
- [ ] Implement addiotional Token Machine
- [ ] Add translations

