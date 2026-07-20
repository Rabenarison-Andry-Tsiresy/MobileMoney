<?php

namespace App\Models;

use CodeIgniter\Model;

class TarifModel extends Model
{
    protected $table = 'tarif';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_operateur', 'id_operation', 'montant_min', 'montant_max', 'montant_frais'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'id_operateur' => 'required',
        'id_operation' => 'required',
        'montant_min' => 'required|numeric',
        'montant_max' => 'required|numeric',
        'montant_frais' => 'required|numeric|greater_than_equal_to[0]'
    ];

    protected $validationMessages = [
        'id_operateur' => ['required' => 'L\'opérateur est obligatoire.'],
        'id_operation' => ['required' => 'L\'opération est obligatoire.'],
        'montant_min' => [
            'required' => 'Le montant minimum est obligatoire.',
            'numeric' => 'Le montant minimum doit être un nombre.',
        ],
        'montant_max' => [
            'required' => 'Le montant maximum est obligatoire.',
            'numeric' => 'Le montant maximum doit être un nombre.',
        ],
        'montant_frais' => [
            'required' => 'Le montant des frais est obligatoire.',
            'numeric' => 'Le montant des frais doit être un nombre.',
            'greater_than_equal_to' => 'Le montant des frais doit être un nombre positif.',
        ]
    ];

    public function getTranchesByOperateurOperation(int $idOperateur, int $idOperation): array
    {
        return $this->where('id_operateur', $idOperateur)
            ->where('id_operation', $idOperation)
            ->orderBy('montant_min', 'ASC')
            ->findAll();
    }

    public function findTarifApplicable($idOperateur, $idOperation, $montant): ?array
    {
    
        if (!is_numeric($idOperateur) || !is_numeric($idOperation) || !is_numeric($montant)) {
            return null;
        }

        return $this->where('id_operateur', (int) $idOperateur)
            ->where('id_operation', (int) $idOperation)
            ->where('montant_min <=', (float) $montant)
            ->where('montant_max >=', (float) $montant)
            ->first();
    }

    /**
     * Vérifier si une nouvelle tranche chevauche une tranche existante
     */
    public function aChevauchement(int $idOperateur, int $idOperation, float $nouveauMin, float $nouveauMax, ?int $idTarifExclu = null): bool
    {
    
        $tranches = $this->where('id_operateur', $idOperateur)
            ->where('id_operation', $idOperation)
            ->findAll();

    
        if ($idTarifExclu) {
            $tranches = array_filter($tranches, function($t) use ($idTarifExclu) {
                return $t['id'] != $idTarifExclu;
            });
        }

    
        foreach ($tranches as $t) {
    
            if ($nouveauMin <= $t['montant_max'] && $nouveauMax >= $t['montant_min']) {
                return true;
            }
        }

        return false;
    }
}
