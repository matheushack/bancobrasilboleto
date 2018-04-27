<?php
/**
 * Created by PhpStorm.
 * User: matheus
 * Date: 24/04/18
 * Time: 10:12
 */

namespace MatheusHack\BancoBrasilBoleto;


class Config
{
    private $production = true;

    private $clientId;

    private $clientSecret;

    private $oAuthUrl;

    private $boletoUrl;

    private $wsdl;

    private $accessToken;

    function __construct(array $options)
    {
        if(is_bool(data_get($options, 'production', null)))
            $this->setProduction(data_get($options, 'production'));

        $this->setBoletoUrl(($this->isProduction() ? '' : 'https://cobranca.homologa.bb.com.br:7101/registrarBoleto'))
            ->setOAuthUrl(($this->isProduction() ? '' : 'https://oauth.hm.bb.com.br:43000/oauth/token'))
            ->setWsdl(($this->isProduction() ? '' : 'https://cobranca.homologa.bb.com.br:7101/Processos/Ws/RegistroCobrancaService.serviceagent?wsdl'));
    }

    /**
     * @return bool
     */
    public function isProduction()
    {
        return $this->production;
    }

    /**
     * @param bool $production
     * @return Config
     */
    public function setProduction($production)
    {
        $this->production = $production;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     * @return Config
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param mixed $clientSecret
     * @return Config
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOAuthUrl()
    {
        return $this->oAuthUrl;
    }

    /**
     * @param mixed $oAuthUrl
     * @return Config
     */
    public function setOAuthUrl($oAuthUrl)
    {
        $this->oAuthUrl = $oAuthUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBoletoUrl()
    {
        return $this->boletoUrl;
    }

    /**
     * @param mixed $boletoUrl
     * @return Config
     */
    public function setBoletoUrl($boletoUrl)
    {
        $this->boletoUrl = $boletoUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWsdl()
    {
        return $this->wsdl;
    }

    /**
     * @param mixed $wsdl
     * @return Config
     */
    public function setWsdl($wsdl)
    {
        $this->wsdl = $wsdl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param mixed $accessToken
     * @return Config
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

}