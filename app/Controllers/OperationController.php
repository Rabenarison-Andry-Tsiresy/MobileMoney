<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OperationModel;

class OperationController extends BaseController
{
    public function index()
    {
        $operationModel = new OperationModel();
        $data['operations'] = $operationModel->findAll();
        
        return view('operation/index', $data);
    }
}