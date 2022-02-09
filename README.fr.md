<p align="center">
<a href="https://www.digicelgroup.com/ht/en/moncash/business.html" target="_blank">
<img style="box-shadow: 2px 2px 1px #000000" src="https://www.digicelgroup.com/etc/designs/haiti-en-moncash/_jcr_content/global/headerLogo.asset.spool/MonCash_Logo-180-90-white.png" width="200"></a></p>

[![Latest Stable Version](http://poser.pugx.org/fruitsbytes/php-moncash/v)](https://packagist.org/packages/fruitsbytes/php-moncash) [![Total Downloads](http://poser.pugx.org/fruitsbytes/php-moncash/downloads)](https://packagist.org/packages/fruitsbytes/php-moncash) [![Latest Unstable Version](http://poser.pugx.org/fruitsbytes/php-moncash/v/unstable)](https://packagist.org/packages/fruitsbytes/php-moncash) [![License](http://poser.pugx.org/fruitsbytes/php-moncash/license)](https://packagist.org/packages/fruitsbytes/php-moncash) [![PHP Version Require](http://poser.pugx.org/fruitsbytes/php-moncash/require/php)](https://packagist.org/packages/fruitsbytes/php-moncash)
[![Version](http://poser.pugx.org/fruitsbytes/php-moncash/version)](https://packagist.org/packages/fruitsbytes/php-moncash)


Librairie PHP pour Digicel Moncash
=============
<p align="center">
    <a href="/README.md">EN</a> • <a href="/README.fr.md">FR</a> • <a href="/README.ht.md">HT</a>
</p>

Une librairie PHP pour interagir avec l' API de Digicel MonCash (mobile money) basée sur la version `1` de
la [documentation](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/resources/doc/RestAPI_MonCash_doc.pdf)
officielle.


Features
------------

- Authorisation
- Créer un Paiement (requête)
- Faire un Transfert `(Beta)`
- Récupérer un paiement avec le numero de transaction
- Récupérer un paiement avec le numero de référence interne

Installation
------------

La meilleure façon d'installer cette extension consiste à utiliser [composer](http://getcomposer.org/download/).

Soit, vous lancez cette commande :

```php
php composer.phar require --prefer-dist fruitsbytes/php-moncash "*"
```

ou

```php
composer require fruitsbytes/php-moncash
```

ou ajouter dans `composer.json`

```json
{
  ...
  "require": {
    "fruitsbytes/php-moncash": "*"
    ...
  }


```


Prérequis
-----

<h3>Créer un compte marchand</h3>

1) Rendez-vous sur [Moncash Sanbox portal](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/New)
2) Ajouter un nouveau Buisiness
3) Récupérer les valeurs de  `clientID` et `clientSecret`

Video en ligne:
- [Kijan pou mete Moncash sou sit ou pou w vann](https://youtu.be/lE3ejFT11_w)
- [Comment Intégrer l'onglet Moncash Pay à votre commerce online - Technopro Web](https://youtu.be/NiWYrO_E5ik)

<p align="center">
<a href="https://www.digicelgroup.com/ht/en/moncash/business.html" target="_blank">
<img style="box-shadow: 2px 2px 1px #000000" 
src="/demo_1.png" width="700"></a></p>


Utilisation
-------

```php
use Fruitsbytes\PHP\Moncash\Moncash;use Fruitsbytes\PHP\Moncash\MonCashException;use Fruitsbytes\PHP\Moncash\PaymentRequestResult;

// Initialiser une instance pour les tests(sandbox)
$monCash = new Moncash( $client_id, $client_secret);

/**
* @var string $client_id
 */
$client_id ='<votre-identifiant-client>';
/**
* @var string $client_id
 */
$client_secret='<votre-clé-secrète>';

// Initialiser une instance pour la platform de production
$monCash = new Moncash( $client_id, $client_secret, 'production');

// Récupérer un nouveau token d'accès
$token = $monCash->get_access_token();

// Récupérer le token de redirection pour l'interface client
try{
    /**
    * @var $resultat PaymentRequestResult
    */
    $resultat = $monCash->create_payment( 1000.50, <your-uniq-reference-id>);
    $url_de_redirection = $result->redirect;
    $token = $result->token;
    
    response(["redirection_url"=>$redirection_url])->json();
    
}catch (MonCashException $e){
 //...
}
```

<b>Note:</b> Vous pouvez utiliser  [UUID](https://github.com/ramsey/uuid) pour générer un identifiant unique semi
aléatoire ` <uniq-reference-id>`

```php
// Récupérer les informations sur un paiement à partir de votre identifiant intern
use Fruitsbytes\PHP\Moncash\PaymentRequestResult;
/**
* @var $resultatPaiement PaymentRequestResult
 */
$resultatPaiement = $monCash->get_payment_by_order( '5392b804-4c15-40b2-9049-f7a471df15fd');

// Récupérer les informations sur un paiement à partir du numéro de transaction fourni au niveau de la transaction réussie dans l'interface client
$resultatPaiement= $monCash->get_payment_by_transaction( '1559796839');

// Faire un transfert
use Fruitsbytes\PHP\Moncash\TransfertRequestResult;
/**
* @var $transactionResult TransfertRequestResult
*/
$transactionResult = $monCash->transfer(525.00 , '50937007294', 'Ma description');
 
```

<b>Note:</b>
Vous pouvez
utiliser [PhoneNumberUtil](https://github.com/giggsey/libphonenumber-for-php/blob/master/docs/PhoneNumberUtil.md) pour
formater le numéro de téléphone suivant la forme INTERNATIONAL ou encore valider avant de soummettre à Digicel.

D'autres librairies disponibles :

- [Google\libphonenumber](https://github.com/google/libphonenumber)
- [libphonenumber-js](https://gitlab.com/catamphetamine/libphonenumber-js#readme)

