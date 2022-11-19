<?php

namespace Fruitsbytes\PHP\MonCash\Button;

$action   = '';
$redirect = '' ; // https://sandbox.moncashbutton.digicelgroup.com/Moncash-middleware/Checkout/{BusinessKey}
$amount   = ''; // base64(encrypt(Amount for this order))
$order    = ''; // base64(encrypt(Order Id))
$image    = ''; // https://sandbox.moncashbutton.digicelgroup.com/Moncash-middleware/resources/assets/images/MC_button.png

class Button
{

    public string $action;
}

?>


<form method="post" action="<?php echo $action ?>">
    <input type="hidden" name="amount" value="<?php echo $amount ?>"/>
    <input type="hidden" name="orderId" value="<?php echo $order ?>"/>
    <input type="image" name="ap_image" src="<?php echo $image ?>" alt="moncash_button"/>
</form>
