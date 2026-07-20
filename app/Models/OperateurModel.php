<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table            = 'Operateur';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = ['libelle'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'libelle' => 'required|min_length[2]|max_length[100]|is_unique[operateur.libelle,id,{id}]'
    ];

    protected $validationMessages   = [
        'libelle' => [
            'required'   => 'Le libellé est obligatoire.',
            'is_unique'  => 'Cet opérateur existe déjà.',
        ]
    ];

}
