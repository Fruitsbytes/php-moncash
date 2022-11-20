<?php
require_once '../../vendor/autoload.php';


use Fruitsbytes\PHP\MonCash\API\Client;
use Fruitsbytes\PHP\MonCash\API\Order;

$price = 50;
$client = new Client();
$order  = new Order($price);
try {
    $url = $client->getRedirectUrlForOrder($order);
} catch (Exception $e) {
    $error = "Oooops!, ".$e->getMessage();
}

$seconds = 1 * 60;

?>
<!DOCTYPE>
<html lang="en">

<head>
    <title>MonCash Pay with QR-Code</title>

    <link rel="stylesheet" href="./qr-pay-example.css">

    <script>
        const url = "<?php echo $url ?? 'https://fruitsbytes.com/error' ?>";
        const seconds = <?php echo $seconds ?>;
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" defer></script>
    <script src="./qr-pay-example.js" defer></script>
</head>


<body>
<main style="max-width: 800px; margin: 12px auto">
    <h1 style="font-weight: 100; color: #737373;">MonCash Example</h1>
    <small style="color: #4b4f52">Redirection URL with Product QRcode</small>

    <a href="<?php echo $url ?? 'https://fruitsbytes.com/error' ?>" class="qr-holder">
        <div class="product-img"></div>
        <div id="qr"></div>

        <div class="description">
            <b>Fruit Mix - Smoothie</b>
            <span class="price"><?php echo $price ?>HTG</span>
        </div>
    </a>
    <p style="color: #adadad">Scan Code to get redirected to payment checkout. Exp:<span id="timer">10:00</span> mns</p>

    <div class="url">
            <?php echo $url ?? 'https://fruitsbytes.com/error' ?>
    </div>


    <?php if ( ! empty($error)) { ?>
        <div class="error">
            <svg style="width: 28px; height: 28px" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <strong style="margin: 0 6px">Error</strong> <?php echo $error ?>
        </div>

    <?php } ?>
    <div class="menu">
        <svg id="settings" fill="none" stroke="currentColor" viewBox="0 0 24 24"
             xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>


    </div>
</main>
<footer>
    <img src="../../assets/images/fruitsbytes-watermark.png" style="width: 48px; height: 48px;display: block" alt="">
    <span style="margin: 0 12px">Fresh from FruitsBytes!</span>
    <small>Photo by <a
                href="https://unsplash.com/@tangerinenewt?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Tangerine
            Newt</a> on <a
                href="https://unsplash.com/@tangerinenewt?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Unsplash</a>
    </small>
</footer>


</body>
</html>
