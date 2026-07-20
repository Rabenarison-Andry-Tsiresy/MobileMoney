<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TarifSeeder extends Seeder
{
    public function run()
    {
        //
        $this->db->table('tarif')->truncate();

        // Récupère les opérateurs (libelle => id)
        $operateurs = $this->db->table('operateur')
            ->select('id, libelle')
            ->get()
            ->getResultArray();

        // Récupère les opérations (libelle => id)
        $operations = $this->db->table('operation')
            ->select('id, libelle')
            ->get()
            ->getResultArray();

        $mapOperateur = [];
        foreach ($operateurs as $op) {
            $mapOperateur[$op['libelle']] = $op['id'];
        }

        $mapOperation = [];
        foreach ($operations as $op) {
            $mapOperation[$op['libelle']] = $op['id'];
        }

        // Barème de test (identique pour retrait et transfert)
        $tranches = [
            [100,      1000,      50],
            [1001,     5000,      50],
            [5001,     10000,     100],
            [10001,    25000,     200],
            [25001,    50000,     400],
            [50001,    100000,    800],
            [100001,   250000,    1500],
            [250001,   500000,    1500],
            [500001,   1000000,   2500],
            [1000001,  2000000,   3000],
        ];

        $data = [];

        foreach ($mapOperateur as $idOperateur) {
            // Dépôt : gratuit par défaut (à ajuster selon règle métier)
            $data[] = [
                'id_operateur'  => $idOperateur,
                'id_operation'  => $mapOperation['depot'],
                'montant_min'   => 0,
                'montant_max'   => 999999999,
                'montant_frais' => 0,
            ];

            // Retrait et transfert : même barème pour le seed de test
            foreach (['retrait', 'transfert'] as $operationLibelle) {
                foreach ($tranches as [$min, $max, $frais]) {
                    $data[] = [
                        'id_operateur'  => $idOperateur,
                        'id_operation'  => $mapOperation[$operationLibelle],
                        'montant_min'   => $min,
                        'montant_max'   => $max,
                        'montant_frais' => $frais,
                    ];
                }
            }
        }

        $this->db->table('tarif')->insertBatch($data);
    }
}
