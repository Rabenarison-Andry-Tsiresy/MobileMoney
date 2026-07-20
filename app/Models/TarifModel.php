<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table            = 'Tarif';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = ['id_operation', 'id_operateur', 'montant_min', 'montant_max', 'montant_frais'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_operateur' => 'required',
        'id_operation' => 'required',
        'montant_min' => 'required|numeric',
        'montant_max' => 'required|numeric',
        'montant_frais' => 'required|numeric|min_value[0]'
    ];

    protected $validationMessages   = [
        'id_operateur' => [
            'required'   => 'L\'opérateur est obligatoire.',
        ],
        'id_operation' => [
            'required'   => 'L\'opération est obligatoire.',
        ],
        'montant_min' => [
            'required'   => 'Le montant minimum est obligatoire.',
            'numeric'    => 'Le montant minimum doit être un nombre.',
        ],
        'montant_max' => [
            'required'   => 'Le montant maximum est obligatoire.',
            'numeric'    => 'Le montant maximum doit être un nombre.',
        ],
        'montant_frais' => [
            'required'   => 'Le montant des frais est obligatoire.',
            'numeric'    => 'Le montant des frais doit être un nombre.',
            'min_value[0]' => 'Le montant des frais doit être un nombre positif.',
        ]
    ];

    public function getTranchesByOperateurOperation(int $idOperateur, int $idOperation): array
    {
        return $this->where('id_operateur', $idOperateur)
            ->where('id_operation', $idOperation)
            ->orderBy('montant_min', 'ASC') 
            ->findAll();
    }


    public function findTarifApplicable(int $idOperateur, int $idOperation, float $montant): ?array
    {
        return $this->where('id_operateur', $idOperateur)
            ->where('id_operation', $idOperation)
            ->where('montant_min <=', $montant) 
            ->where('montant_max >=', $montant) 
            ->first();
    }

    public function aChevauchement(int $idOperateur, int $idOperation, float $nouveauMin, float $nouveauMax, ?int $idTarifExclu = null): bool
    {
        $builder = $this->where('id_operateur', $idOperateur)
            ->where('id_operation', $idOperation)
            // Formule mathématique pour vérifier si deux intervalles se croisent :
            ->where('montant_min <=', $nouveauMax)
            ->where('montant_max >=', $nouveauMin);

        // Si on est en train de faire un UPDATE, on exclut la ligne actuelle de la vérification
        if ($idTarifExclu) {
            $builder->where('id !=', $idTarifExclu);
        }

        // S'il trouve au moins 1 résultat, c'est qu'il y a un chevauchement !
        return $builder->countAllResults() > 0;
    }
}
