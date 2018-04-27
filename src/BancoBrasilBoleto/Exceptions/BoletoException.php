<?php
/**
 * Created by PhpStorm.
 * User: matheus
 * Date: 23/04/18
 * Time: 17:35
 */

namespace MatheusHack\BancoBrasilBoleto\Exceptions;

use Throwable;

class BoletoException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if(empty($message))
            $message = 'Houve problema na comunicação';

        parent::__construct($message, $code, $previous);
    }
}