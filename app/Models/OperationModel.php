<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table = 'operation';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'libelle' => 'required|min_length[2]|max_length[100]|is_unique[operation.libelle,id,{id}]'
    ];

    protected $validationMessages = [
        'libelle' => [
            'required' => 'Le libellé est obligatoire.',
            'is_unique' => 'Cette opération existe déjà.',
        ]
    ];
}