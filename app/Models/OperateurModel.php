<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table = 'operateur';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle'];
    protected $useTimestamps = true;
    protected $createdField = 'date_creation';
    protected $updatedField = '';

    protected $validationRules = [
        'libelle' => 'required|min_length[2]|max_length[100]|is_unique[operateur.libelle,id,{id}]'
    ];

    protected $validationMessages = [
        'libelle' => [
            'required' => 'Le libellé est obligatoire.',
            'is_unique' => 'Cet opérateur existe déjà.',
        ]
    ];
}