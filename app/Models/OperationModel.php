<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table            = 'Operation';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = ['libelle'];

    protected $useTimestamps = false;

    // Validation
    protected $validationRules      = [
        'libelle' => 'required|min_length[2]|max_length[100]|is_unique[operation.libelle,id,{id}]'
    ];

    protected $validationMessages   = [
        'libelle' => [
            'required'   => 'Le libellé est obligatoire.',
            'is_unique'  => 'Cet opérateur existe déjà.',
        ]
    ];

}
