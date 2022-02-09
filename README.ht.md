<p align="center">
<a href="https://www.digicelgroup.com/ht/en/moncash/business.html" target="_blank">
<img style="box-shadow: 2px 2px 1px #000000" src="https://www.digicelgroup.com/etc/designs/haiti-en-moncash/_jcr_content/global/headerLogo.asset.spool/MonCash_Logo-180-90-white.png" width="200"></a></p>

[![Latest Stable Version](http://poser.pugx.org/fruitsbytes/php-moncash/v)](https://packagist.org/packages/fruitsbytes/php-moncash) [![Total Downloads](http://poser.pugx.org/fruitsbytes/php-moncash/downloads)](https://packagist.org/packages/fruitsbytes/php-moncash) [![Latest Unstable Version](http://poser.pugx.org/fruitsbytes/php-moncash/v/unstable)](https://packagist.org/packages/fruitsbytes/php-moncash) [![License](http://poser.pugx.org/fruitsbytes/php-moncash/license)](https://packagist.org/packages/fruitsbytes/php-moncash) [![PHP Version Require](http://poser.pugx.org/fruitsbytes/php-moncash/require/php)](https://packagist.org/packages/fruitsbytes/php-moncash)
[![Version](http://poser.pugx.org/fruitsbytes/php-moncash/version)](https://packagist.org/packages/fruitsbytes/php-moncash)


Libreri PHP pou Digicel Moncash
=============
<p align="center">
    <a href="/README.md">EN</a> • <a href="/README.fr.md">FR</a> • <a href="/README.ht.md">HT</a>
</p>

Yon libreri PHP pou ou ka pale ak API Digicel Moncash la. Li baze sou vèsyon `1`
[dokimantasyon](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/resources/doc/RestAPI_MonCash_doc.pdf)
ofisyèl la.


Features
------------

- Otorizasyon
- Kreye yon peman
- Fè ton transfè `(Beta)`
- Telechaje enfòmasyon sou yon peman avèk nimewo tranzaksyon an
- Telechaje enfòmasyon sou yon peman avèk nimewo referans biznis ou a bay tranzakyon an

Installation
------------

Fason ki pi fasil pou sèvi ak libreri sila a se sèvi ak [composer](http://getcomposer.org/download/).

Ou ka lanse kòmand sa a:

```php
php composer.phar require --prefer-dist fruitsbytes/php-moncash "*"
```

oswa

```php
composer require fruitsbytes/php-moncash
```

osinon ajoute li nan fichye  `composer.json` la

```json
{
  ...
  "require": {
    "fruitsbytes/php-moncash": "*"
    ...
  }


```

Prereki
-----

<h3>Ou bezwen yon kont biznis sou Moncash</h3>

1) Ale nan page sa a: [Moncash Sanbox portal](https://sandbox.moncashbutton.digicelgroup.com/Moncash-business/New)
2) Ajoute yon nouvo Biznis
3) Rékipere valè   `clientID` ak `clientSecret`

Video pou ede ou:

- [Kijan pou mete Moncash sou sit ou pou w vann](https://youtu.be/lE3ejFT11_w)
- [Comment Intégrer l'onglet Moncash Pay à votre commerce online - Technopro Web](https://youtu.be/NiWYrO_E5ik)

<p align="center">
<a href="https://www.digicelgroup.com/ht/en/moncash/business.html" target="_blank">
<img style="box-shadow: 2px 2px 1px #000000" 
src="/demo_1.png" width="700"></a></p>




Itilizasyon
--------
<h3>Egzanp</h3>

```php
use Fruitsbytes\PHP\Moncash\Moncash;use Fruitsbytes\PHP\Moncash\MonCashException;use Fruitsbytes\PHP\Moncash\PaymentRequestResult;

/**
* @var string $client_id
 */
$client_id ='<idantifyan-ou>';
/**
* @var string $client_id
 */
$client_secret='<mo-secrè>';

// Inisyalize on instana pour fè test(sandbox)
$monCash = new Moncash( $client_id, $client_secret);

// Inisyalize yon enstans pou fè test
$monCash = new Moncash( $client_id, $client_secret, 'production');

// Jenere yon nouvo token
$token = $monCash->get_access_token();

// Jenere yon nouvo lyen pou redirije kliyan an sou page Digicel la pou li ka kontinye operasyon an
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

<b>Note:</b> Ou ka sèvi ak  [UUID](https://github.com/ramsey/uuid) jenere yon idantifyan referans inik ki pli ou mwens
aleatwa ` <uniq-reference-id>`

```php
// Telechaje enfòmasyon sou yon peman avèk nimewo referans biznis ou a bay tranzakyon an
use Fruitsbytes\PHP\Moncash\PaymentRequestResult;
/**
* @var $resultatPaiement PaymentRequestResult
 */
$resultatPaiement = $monCash->get_payment_by_order( '5392b804-4c15-40b2-9049-f7a471df15fd');

// Telechaje enfòmasyon sou yon peman avèk nimewo tranzaksyon an
$resultatPaiement= $monCash->get_payment_by_transaction( '1559796839');

// Fè yon transfè
use Fruitsbytes\PHP\Moncash\TransfertRequestResult;

/**
* @var $transactionResult TransfertRequestResult
*/
$transactionResult = $monCash->transfer(525.00 , '50937007294', 'Ma description');
 
```

<b>Note:</b>
Ou ka sèvi ak  [PhoneNumberUtil](https://github.com/giggsey/libphonenumber-for-php/blob/master/docs/PhoneNumberUtil.md)
pou ou ka fòmate nimewo telefon nan fòm entènasyonal yo INTERNATIONALoswa valide li avan ou voye li bayDigicel.

Men on lis lòt libreri ki fè sa:

- [Google\libphonenumber](https://github.com/google/libphonenumber)
- [libphonenumber-js](https://gitlab.com/catamphetamine/libphonenumber-js#readme)

