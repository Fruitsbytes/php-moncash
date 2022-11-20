<?php
require_once '../../vendor/autoload.php';

use Fruitsbytes\PHP\MonCash\API\Order;
use Fruitsbytes\PHP\MonCash\Button\ButtonStyleRedResponsive;

$lang         = $_GET['lang'] ?? '';
$border       = $_GET['border'] ?? '';
$animateHover = $_GET['animateHover'] ?? '';
$height       = (int) ($_GET['height'] ?? 253);

$isLangSelected = function (string $value) use ($lang) {
    return $lang === $value;
};

$order = new Order(100);

try {
    $button = new ButtonStyleRedResponsive(
        $order,
        [],
        $border === 'on',
        $lang,
        $animateHover === 'on',
        $height
    );
} catch (Exception $e) {
    $error = $e->getMessage();
}

?>
<!DOCTYPE>
<html lang="en">

<head>
    <style>
        html, body {
            background: #171717;
            color: #dedede;
            font-family: sans-serif;
        }

        label {
            font-weight: 600;
            color: #ffa2a2;
            display: block;
            margin: 24px 0 6px;
        }

        input:not([type=checkbox]), select {
            width: 300px;
            padding: 6px;
            border-radius: 3px;
            border: 2px solid #ffa2a2;
            font-size: 1.1rem;
        }

        input[type=checkbox] + label {
            display: inline-block;
        }

        p {
            text-align: center;
            color: #383838;
        }
    </style>
    <title>MonCash button - styles</title>
</head>


<body>
<div style="max-width: 800px; margin: 12px auto">

    <h1 style="font-weight: 100; color: #737373;">MonCash Button</h1>
    <form action="">
        <div>
            <label for="height">Height</label>
            <input type="number" id="height" name="height" value="<?php echo $height ?>"> <br>

            <label for="lang">Langue</label>
            <select name="lang" id="lang">
                <option value="" <?php echo $isLangSelected('') ? "selected='selected'" : "" ?> >
                    English
                </option>
                <option value="fr" <?php echo $isLangSelected('fr') ? "selected='selected'" : "" ?> >
                    Français
                </option>
                <option value="ht" <?php echo $isLangSelected('ht') ? "selected='selected'" : "" ?> >
                    Kreyòl Ayisyen
                </option>
            </select>
            <br>

            <input type="checkbox" id="border" name="border" <?php echo $border === 'on' ? 'checked="checked"' : '' ?> >
            <label for="border"> Add border</label><br>

            <input type="checkbox" id="animateHover"
                   name="animateHover" <?php echo $animateHover === 'on' ? 'checked="checked"' : '' ?> >
            <label for="animateHover"> Animate on hover</label><br>

        </div>

        <div style="margin-top: 48px"></div>

        <?php
        if (isset($button)) {
            $button->render();
        } else {
            echo <<<ERROR
                <div class="error">$error</div>
               ERROR;
        }
        ?>
    </form>

    <p>Press button to update</p>
</div>
</body>

</html>

