<?php
/**
 * Created by PhpStorm.
 * User: matheus
 * Date: 23/04/18
 * Time: 17:34
 */

namespace MatheusHack\BancoBrasilBoleto\Soap;

use MatheusHack\BancoBrasilBoleto\Config;
use MatheusHack\BancoBrasilBoleto\Services\ServiceSoapClient;

class SoapClient extends ServiceSoapClient
{
    public $xmlns = '';

    public function __construct(Config $config)
    {
        parent::__construct($config->getWsdl(), [
            'exceptions' => 0,
            'trace' => 1,
            'connection_timeout' => 1800,
            'compression' =>  SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'encoding' => 'UTF-8',
            'cache_wsdl' => WSDL_CACHE_BOTH,
        ]);
        $this->__setSoapHeaders($this->header($config));
    }

    private function header(Config $config){
        return new \SoapHeader($this->xmlns, 'Authorization', 'Bearer '.$config->getAccessToken());
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        dd($request);
    }
}
