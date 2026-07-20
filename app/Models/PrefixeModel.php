<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixe';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = ['prefixe', 'id_operateur'];

    protected $useTimestamps = false;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = '';

    // Validation
    protected $validationRules      = [
        'prefixe'      => 'required|min_length[1]|max_length[10]|is_unique[prefixe.prefixe,id,{id}]',
        'id_operateur' => 'required'
    ];

    protected $validationMessages   = [
        'prefixe' => [
            'required'   => 'Le préfixe est obligatoire.',
            'is_unique'  => 'Ce préfixe existe déjà.',
        ],
        'id_operateur' => [
            'required' => 'L\'opérateur est obligatoire.',
        ]
    ];

    public function findOperateurByPrefixe(string $prefixe): ?array
    {
        return $this->select('operateur.*') 
            ->join('operateur', 'operateur.id = prefixe.id_operateur') 
            ->where('prefixe.prefixe', $prefixe) 
            ->first();
    }
}
