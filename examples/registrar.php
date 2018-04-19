<?php
/**
 * Created by PhpStorm.
 * User: matheus
 * Date: 19/04/18
 * Time: 15:46
 */

require '../vendor/autoload.php';

use MatheusHack\BancoBrasilBoleto\BancoBrasil;

try {
    $bancoBrasil = new BancoBrasil();

}catch(\Exception $e){
    dd($e->getMessage());
}