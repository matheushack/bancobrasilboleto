<?php
/**
 * Created by PhpStorm.
 * User: matheus
 * Date: 23/04/18
 * Time: 17:37
 */

namespace MatheusHack\BancoBrasilBoleto\Transformers;

use League\Fractal;

class BoletoTransformer extends Fractal\TransformerAbstract
{
    public function transform(array $boleto)
    {
        return $boleto;
    }
}