<?php

namespace Fruitsbytes\PHP\MonCash\Button;


use Fruitsbytes\PHP\MonCash\API\Order;
use Fruitsbytes\PHP\MonCash\Configuration\Configuration;

interface ButtonInterface extends \Stringable
{

    public function __construct(Order $order, Configuration|array $configuration = []);

    public function render(): void;

    public function html(): string;

}



