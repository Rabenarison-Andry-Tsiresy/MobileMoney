<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionModel extends Model
{
    protected $table = 'commission';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_operateur_depart', 'id_operateur_arrivee', 'pourcentage'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'id_operateur_depart' => 'required',
        'id_operateur_arrivee' => 'required',
        'pourcentage' => 'required|numeric|greater_than[0]|less_than_equal_to[100]'
    ];

    protected $validationMessages = [
        'id_operateur_depart' => ['required' => 'L\'opérateur de départ est obligatoire.'],
        'id_operateur_arrivee' => ['required' => 'L\'opérateur d\'arrivée est obligatoire.'],
        'pourcentage' => [
            'required' => 'Le pourcentage est obligatoire.',
            'numeric' => 'Le pourcentage doit être un nombre.',
            'greater_than' => 'Le pourcentage doit être supérieur à 0.',
            'less_than_equal_to' => 'Le pourcentage ne peut pas dépasser 100.'
        ]
    ];

    public function findByOperateurs(int $idDepart, int $idArrivee): ?array
    {
        return $this->where('id_operateur_depart', $idDepart)
            ->where('id_operateur_arrivee', $idArrivee)
            ->first();
    }

    public function getCommissionsByOperateurDepart(int $idOperateur): array
    {
        return $this->where('id_operateur_depart', $idOperateur)
            ->findAll();
    }
}
