<?php
/**
 * Created by PhpStorm.
 * User: matheus
 * Date: 20/04/18
 * Time: 10:22
 */

namespace MatheusHack\BancoBrasilBoleto\Services;

use Illuminate\Support\Collection;
use MatheusHack\BancoBrasilBoleto\BancoBrasilClient;
use MatheusHack\BancoBrasilBoleto\Config;
use MatheusHack\BancoBrasilBoleto\Exceptions\BoletoException;
use MatheusHack\BancoBrasilBoleto\Factories\BoletoFactory;
use MatheusHack\BancoBrasilBoleto\Soap\SoapClient;

class ServiceBoleto
{
    private $config;

    function __construct(Config $options)
    {
        $this->config = $options;
    }

    public function registrar(array $boletos)
    {
        $bbClient = new BancoBrasilClient($this->config);
        $authorize = $bbClient->authorize();

        if(!$authorize instanceof Config)
            throw new BoletoException('AccessToken - Token não autorizado pelo banco');

        $boletosCollection = new Collection();
        $boletoFactory = new BoletoFactory();

        foreach($boletos as $boleto)
            $boletosCollection[] = $boletoFactory->make($boleto);

        $bbSoapClient = new SoapClient($authorize);
        $boletosResponse = [];

        $boletosCollection->each(function($boleto) use(&$boletosResponse, $bbSoapClient){
            try {
                $response = $bbSoapClient->__soapCall('registrarBoleto', [$boleto]);
                $boletosResponse[] = $response;

            } catch (\SoapFault $SoapFault) {
                $boletosResponse[] = $boleto;
            }
        });

        return $boletosResponse;
    }
}