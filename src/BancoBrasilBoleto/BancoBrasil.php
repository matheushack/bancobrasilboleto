<?php
/**
 * Created by PhpStorm.
 * User: matheus
 * Date: 19/04/18
 * Time: 15:45
 */

namespace MatheusHack\BancoBrasilBoleto;

use MatheusHack\BancoBrasilBoleto\Helpers\Fractal;
use MatheusHack\BancoBrasilBoleto\Services\ServiceBoleto;
use MatheusHack\BancoBrasilBoleto\Exceptions\BoletoException;
use MatheusHack\BancoBrasilBoleto\Transformers\BoletoTransformer;

class BancoBrasil
{
    private $serviceBoleto;

    function __construct($options  = [])
    {
        $config = new Config($options);
        $this->serviceBoleto = new ServiceBoleto($config);
    }


    public function registrar(array $boletos)
    {
        if(empty($boletos))
            throw new BoletoException('Requisição inválida');

        $boletos = $this->serviceBoleto->registrar($boletos);

        if($boletos->count() > 0)
            return Fractal::collection($boletos, new BoletoTransformer)->toJson();

        throw new BoletoException('Nenhuma boleto registrado');
    }


}