<?php
/**
 * Created by PhpStorm.
 * User: matheus
 * Date: 19/04/18
 * Time: 15:46
 */

require '../vendor/autoload.php';

use MatheusHack\BancoBrasilBoleto\BancoBrasil;

$boletos[] = [
    'tipo_carteira_titulo' => 109,
    'nosso_numero' => 1,
    'digito_verificador_nosso_numero' => 2,
    'data_vencimento' => \Carbon\Carbon::now()->addDays(15)->format('Y-m-d'),
    'valor_cobrado' => 100,
    'data_emissao' => \Carbon\Carbon::now()->format('Y-m-d'),
    'beneficiario' => [
        'documento_identificacao' => '',
        'agencia' => '1',
        'conta' => '2',
        'digito_conta' => '0'
    ],
    'pagador' => [
        'documento_identificacao' => '',
        'nome'=> 'Teste',
        'logradouro' => 'Rua teste',
        'cidade' => 'São Paulo',
        'uf' => 'SP',
        'cep' => '99999999'
    ],
    'moeda' => [
        'quantidade' => 100
    ]
];


try {
    $bancoBrasil = new BancoBrasil([
        'production' => false
    ]);

    $boletosRegistrados = $bancoBrasil->registrar($boletos);
    echo $boletosRegistrados;

}catch(\Exception $e){
    dd($e->getMessage());
}