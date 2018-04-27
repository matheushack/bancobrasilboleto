<?php
/**
 * Created by PhpStorm.
 * User: matheus
 * Date: 24/04/18
 * Time: 10:11
 */

namespace MatheusHack\BancoBrasilBoleto;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use MatheusHack\BancoBrasilBoleto\Exceptions\BoletoException;

class BancoBrasilClient
{
    private $config;

    private $httpClient;

    function __construct(Config $options)
    {
        $this->config = $options;
        $this->httpClient = new Client();
    }

    public function authorize()
    {
        try {
            $response = $this->httpClient->post($this->config->getOAuthUrl(), [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization:' => 'Basic '.base64_encode($this->config->getClientId().':'.$this->config->getClientSecret()),
                    'Cache-Control' => 'no-cache'
                ],
                'form_params' => [
                    'scope' => 'cobranca.registro-boletos',
                    'grant_type' => base64_encode($this->config->getClientId().':'.$this->config->getClientSecret())
                ]
            ]);

        } catch (RequestException $e) {
            throw new BoletoException('AccessToken - '.$e->getMessage());
        }

        $jsonResponse = json_decode($response->getBody());

        if(data_get($jsonResponse, 'codigo', null) == 400)
            throw new BoletoException(data_get($jsonResponse, 'mensagem', null));

        $this->config->setAccessToken($jsonResponse->access_token);

        return $this->config;
    }
}