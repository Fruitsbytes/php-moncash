<p align="center">

<img  src="../../FruitsBytes-moncash-php.png?v=2" alt="FruitsBytes-Moncash-PHP">

# Introduction

[fr]: ../fr/0_ABOUT.md "Traduction française"

[ht]: ../ht/0_ABOUT.md "TRadiksyon kreyòl"


🌎 i18n:  🇺🇸 • [🇫🇷][fr] • [🇭🇹][ht]

> Since 2010, MonCash has been closing the distance between people in Haiti by providing a way to make fast, reliable, safe, and convenient daily financial transactions on any Digicel mobile. As MonCash serves its 1.5 million users, it has also looked for ways to expand the way they access its services.
> 
> @[Moncash Official Webiste](https://www.digicelgroup.com/ht/en/moncash/customer.html)


There are several ways to introduce MonCash into your business. The wallet app is directly available for [Android](https://go.onelink.me/0oln/372417d0) and [IOS](https://go.onelink.me/0oln/372417d0) but if you want a more streamlined experience  

1) [Set environment](#env)
    - [Use .env file](#env-file)
    - [Override global environment variables during instanciation](#env-override)
    - [$_ENV vs putenv](#env-putenv)
    - [`Advanced`] [Use third party Secret manager](#env-secret)
1) [Client Application](#client)
   - [Authenticattion](#authentication)
      + [`Advanced`] [Traffic optimisation](#traffic-optmization)
   - [Payment](#payment)
   - [Transfer](#transfer)
1) [Merchant Application](#client)
1) [Button](#button)
1) [Localization](#localization)
1) [Order ID](#idempotance)
   + [`Advanced`] [Use anoter idempotence strategy](#idempotance-stategy)
1) [Manage secret](#manage-secret)
1) [Reduce server calls](#server-calls)
1) [Phone Validation & formating](#phone-validation-formating)
