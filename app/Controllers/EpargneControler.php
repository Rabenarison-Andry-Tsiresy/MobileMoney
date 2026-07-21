<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EpargneModel;
use App\Models\ClientModel;
use App\Models\NumeroModel;

class EpargneController extends BaseController
{
    protected $EpargneModel;
    public function __construct(){
        $this->EpargneModel =new EpargneModel();
    }

    public function pageepargne(){
        $numero = session()->get('numero');
        $client = new ClientModel();
        $numero_po = new NumeroModel();
        $data = $numero_po->findByNumero($numero);
        $client_data = $client->findByClientById($data);
        
    }


}