<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table            = 'Operation';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = ['libelle', 'id_operateur', 'libelle'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'libelle' => 'required|min_length[2]|max_length[100]|is_unique[operation.libelle,id,{id}]'
    ];

    protected $validationMessages   = [
        'libelle' => [
            'required'   => 'Le libellé est obligatoire.',
            'is_unique'  => 'Cette opération existe déjà.',
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
