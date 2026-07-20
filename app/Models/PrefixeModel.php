<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixe';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = ['libelle', 'id_operateur'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'libelle'      => 'required|min_length[1]|max_length[10]|is_unique[prefixe.libelle,id,{id}]',
        'id_operateur' => 'required'
    ];

    protected $validationMessages   = [
        'libelle' => [
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
            ->where('prefixe.libelle', $prefixe) 
            ->first();
    }
}
