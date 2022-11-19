
<div id="usage"></div>

## 4. Usage


<div id="env"></div> 

### 4.1. Set environmental values

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

#### 4.1.1 Environment file `.env`

This package is bunndled with `vlucas/phpdotenv` and can read the .env file in the root of the application host. Add
these variables to the <a href="https://docs.docker.com/compose/env-file/" target="_blank"><b>.env</b>
file</a> in the root of your project.

```shell
# /root/.env

# Replace <your-client-id> accordingly
MONCASH_CLIENT_ID="<your-client-id>"
# Replace <your-client-secret> accordingly
MONCASH_CLIENT_SECRET="<your-client-secret>"
# Replace <yourbuisiness-key> acordingly
MONCASH_BUSINESS_KEY="<yourbuisiness-key>"
# Enum:sandbox, production. Default: 'sandbox'
MONCASH_MODE="sandbox"
# Enum: en, fr, ht. Defualt 'en' 
MONCASH_LANG="en"

```

<div id="env-setEnv"></div> 

#### 4.1.2 Static helper method

<div id="env-overridev"></div> 

#### 4.1.3 Override global environment

```php
use Fruitsbytes\PHP\MonCash\API\API;use Fruitsbytes\PHP\MonCash\API\APIException;use Fruitsbytes\PHP\MonCash\PaymentResponse;

/**
* @var string $client_id
 */
$client_id ='<your-client-id>';
/**
* @var string $client_secret
 */
$client_secret='<your-client-secret>';

// Switch credentials
$monCash = new API([$client_id, $client_secret,]);

// Override instance set mode to production
$monCash = new API("mode" => 'production');

// Authenticate
$token = $monCash->getAccessToken();

// Get redirection url to start the payement in the frontend
try{
    /**
    * Create Payment 
    * @var $result PaymentResponse
    */
    $result = $monCash->CreatePayment( 1000.50, <your-uniq-reference-id>);
    $redirection_url = $result->redirect;
    $token = $result->token;
    
     response(["redirectionUrl"=>$redirection_url])->json();
    
}catch (APIException $e){
 //...
}
```

<div id="env-putenv"></div> 

#### 4.1.4  $_ENV vs putenv()

<div id="env-secret"></div> 

#### 4.1.5 Use third party Secret manager (ADVANCED)

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

| Name                                                                           | Provider                                                                                                                        | Status |
|--------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------|:------:|
| [Default](./src/PHP/Moncash/Strategy/SecretManager/DefaultSecretManager.php)   | uses `.env file`                                                                                                                |   ✅    |
| [GCP](./src/PHP/Moncash/Strategy/SecretManager/GCPSecretManager.php)           | <a href="https://cloud.google.com/functions/docs/configuring/secrets" target="_blank" >Google Cloud Platform Secret Manager</a> |   ❌    |
| [AWS](./src/PHP/Moncash/Strategy/SecretManager/AWSSecretManager.php)           | <a href="https://aws.amazon.com/secrets-manager/" target="_blank">AWS Secret Manager</a>                                        |   ❌    |
| [Azure](./src/PHP/Moncash/Strategy/SecretManager/AzureSecretManager.php)       | <a href="https://azure.microsoft.com/en-us/products/key-vault/" target="_blank">Azure Key Vault</a>                             |   ❌    |
| [KeyCloak](./src/PHP/Moncash/Strategy/SecretManager/KeyCloakSecretManager.php) | <a href="https://www.keycloak.org/server/vault" target="_blank">Keycloak - Kubernetes/OpenShift secrets</a>                     |   ❌    |
| Vault                                                                          |  <a href="https://www.vaultproject.io/" target="_blank">HashiCorp Vault</a>                                                     |   ❌    |

To add new stategies implement
the [SecretManager interface](./src/PHP/Moncash/Strategy/SecretManager/SecretManagerInterface.php) as explained in the
section [Manage secret](#manage-secret).

In the `environment variables` you can specify a Stategy to retrieve the secret :

```shell
# /root/.env

# Name of implementation `\Fruitsbytes\PHP\Moncash\Strategy\TokenMachine\TokenMachineInterface`. Default: `DefaultTokenMachine`
MONCASH_TOKEN_MACHINE=""
# Name of `\Fruitsbytes\PHP\Moncash\Strategy\SecretManager\SecretManagerInterface` implementation. Default Default: `DefaultSecretManager`
MONCASH_SECRET_MANAGER=""

```

<b>Note:</b> You can use  [UUID](https://github.com/ramsey/uuid) to generate
semi-random-idempotent ` <uniq-reference-id>`

```php
// Get Payment by your internal reference ID
use Fruitsbytes\PHP\MonCash\PaymentResponse;
/**
* @var $paymentResult PaymentResponse
 */
$paymentResult = $monCash->getPaymentByOrder( '5392b804-4c15-40b2-9049-f7a471df15fd');

// Get Payment by TransactionID from the front-end after the user successfully went through the payment process
$paymentResult = $monCash->getPaymentByTransaction( '1559796839');

//Make a transfer
use Fruitsbytes\PHP\MonCash\TransfertRequestResult;
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
