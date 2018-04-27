<?php
/**
 * Created by PhpStorm.
 * User: matheus
 * Date: 24/04/18
 * Time: 09:58
 */

namespace MatheusHack\BancoBrasilBoleto\Services;


abstract class ServiceSoapClient extends \SoapClient
{
    protected $defaultSocketTimeout = null; // if null then copy connection_timeout

    public $wsdl;

    public $options;

    public function __construct($wsdl = null, $options = [])
    {
        $this->wsdl = $wsdl;
        $this->options = $options;

        if ($timeoutSeconds = array_get($options, 'connection_timeout')) {
            $this->defaultSocketTimeout = $timeoutSeconds;
        }

        parent::__construct($wsdl, $options);
    }

    /**
     * @return ServiceSoapClient
     */
    public static function createWithSingleElementArrays($wsdl, array $moreOptions = [])
    {
        $sslOptions = array_get($moreOptions, 'stream_context_ssl', []);
        $timeout = array_get($moreOptions, 'connection_timeout', 90);

        $options = self::getSoapOptionsWithSingleElementArrays($wsdl, $timeout, $sslOptions);

        $options = array_merge($options, $moreOptions);

        return new static($wsdl, $options);
    }

    /**
     * Pass the request and response do service soap log
     *
     * @param string $request The XML SOAP request.
     * @param string $location The URL to request.
     * @param string $action The SOAP action.
     * @param int $version The SOAP version.
     * @param int $one_way = 0 If one_way is set to 1, this method returns nothing. Use this where a response is not expected.
     *
     * @return string
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $originalSocketTimeout = ini_get('default_socket_timeout');


        if ($this->defaultSocketTimeout > 0) {
            ini_set('default_socket_timeout', $this->defaultSocketTimeout);
        }

        $exception = null;
        $response = null;

        try {
            $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        } catch (\SoapFault $e) {
            $exception = $e;
        } catch (\Exception $e) {
            $exception = $e;
        } finally {
            if ($this->defaultSocketTimeout > 0 && $originalSocketTimeout > 0) {
                ini_set('default_socket_timeout', $originalSocketTimeout);
            }
        }

        if ($exception) {
            throw $exception;
        }

        return $response;
    }

    private static function getSoapOptionsWithSingleElementArrays($wsdlUrl, $timeoutSeconds = 90, array $sslOptions = []) {
        $options = [
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'cache_wsdl' => WSDL_CACHE_DISK,
            'trace' => 1,
            'exceptions' => true,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'connection_timeout' => $timeoutSeconds,
            'stream_context' => self::getStreamContext($sslOptions, $timeoutSeconds),
        ];

        $isUrl = (strpos($wsdlUrl, '?wsdl') !== false)
            || (strpos($wsdlUrl, '?WSDL') !== false);

        if ($isUrl) {
            $options['location'] = str_replace(['?wsdl', '?WSDL'], '', $wsdlUrl);
        }

        //dd($options);

        return $options;
    }

    public static function getStreamContext(array $sslOptions = [], $timeoutSeconds = 90)
    {
        $context = stream_context_create([
            'ssl' => $sslOptions + [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'ssl_method' => SOAP_SSL_METHOD_SSLv3,
                    'connection_timeout' => $timeoutSeconds,
                ],
        ]);

        return $context;
    }

    public function __getLastResponseBodySimpleXml()
    {
        $lastResponse = $this->__getLastResponse();

        if (!$lastResponse) {
            return;
        }

        $endBodyTag = '</SOAP-ENV:Body>';
        $strPosStart = strpos($lastResponse, '<SOAP-ENV:Body>');
        $strPosEnd = strpos($lastResponse, $endBodyTag);

        if ($strPosStart === false || $strPosEnd === false) {
            return;
        }

        $rawBody = substr(
            $lastResponse,
            $strPosStart,
            $strPosEnd - $strPosStart + strlen($endBodyTag)
        );

        if (!$rawBody) {
            return;
        }

        return simplexml_load_string($rawBody, null, LIBXML_NOERROR);
    }
}