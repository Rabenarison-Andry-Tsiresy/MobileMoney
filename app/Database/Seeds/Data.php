<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Data extends Seeder
{
    public function run()
    {
        // Opérations
        $this->db->table('operation')->insertBatch([
            ['libelle' => 'depot'],
            ['libelle' => 'retrait'],
            ['libelle' => 'transfert'],
        ]);

        // Opérateurs
        $this->db->table('operateur')->insertBatch([
            ['libelle' => 'Orange'],
            ['libelle' => 'MTN'],
            ['libelle' => 'Moov'],
        ]);

        // Préfixes
        $this->db->table('prefixe')->insertBatch([
            ['id_operateur' => 1, 'prefixe' => '033'],
            ['id_operateur' => 1, 'prefixe' => '034'],
            ['id_operateur' => 2, 'prefixe' => '037'],
            ['id_operateur' => 2, 'prefixe' => '038'],
            ['id_operateur' => 3, 'prefixe' => '035'],
            ['id_operateur' => 3, 'prefixe' => '036'],
        ]);

        // Tarifs
        $this->db->table('tarif')->insertBatch([
            // Dépôt
            ['id_operateur' => 1, 'id_operation' => 1, 'montant_min' => 0, 'montant_max' => 1000000, 'montant_frais' => 0],
            ['id_operateur' => 2, 'id_operation' => 1, 'montant_min' => 0, 'montant_max' => 1000000, 'montant_frais' => 0],
            ['id_operateur' => 3, 'id_operation' => 1, 'montant_min' => 0, 'montant_max' => 1000000, 'montant_frais' => 0],

            // Retrait
            ['id_operateur' => 1, 'id_operation' => 2, 'montant_min' => 0, 'montant_max' => 5000, 'montant_frais' => 50],
            ['id_operateur' => 1, 'id_operation' => 2, 'montant_min' => 5000, 'montant_max' => 50000, 'montant_frais' => 200],
            ['id_operateur' => 1, 'id_operation' => 2, 'montant_min' => 50000, 'montant_max' => 200000, 'montant_frais' => 500],

            ['id_operateur' => 2, 'id_operation' => 2, 'montant_min' => 0, 'montant_max' => 5000, 'montant_frais' => 50],
            ['id_operateur' => 2, 'id_operation' => 2, 'montant_min' => 5000, 'montant_max' => 50000, 'montant_frais' => 200],
            ['id_operateur' => 2, 'id_operation' => 2, 'montant_min' => 50000, 'montant_max' => 200000, 'montant_frais' => 500],

            ['id_operateur' => 3, 'id_operation' => 2, 'montant_min' => 0, 'montant_max' => 5000, 'montant_frais' => 50],
            ['id_operateur' => 3, 'id_operation' => 2, 'montant_min' => 5000, 'montant_max' => 50000, 'montant_frais' => 200],
            ['id_operateur' => 3, 'id_operation' => 2, 'montant_min' => 50000, 'montant_max' => 200000, 'montant_frais' => 500],

            // Transfert
            ['id_operateur' => 1, 'id_operation' => 3, 'montant_min' => 0, 'montant_max' => 10000, 'montant_frais' => 50],
            ['id_operateur' => 1, 'id_operation' => 3, 'montant_min' => 10000, 'montant_max' => 100000, 'montant_frais' => 200],
            ['id_operateur' => 1, 'id_operation' => 3, 'montant_min' => 100000, 'montant_max' => 1000000, 'montant_frais' => 500],

            ['id_operateur' => 2, 'id_operation' => 3, 'montant_min' => 0, 'montant_max' => 10000, 'montant_frais' => 50],
            ['id_operateur' => 2, 'id_operation' => 3, 'montant_min' => 10000, 'montant_max' => 100000, 'montant_frais' => 200],
            ['id_operateur' => 2, 'id_operation' => 3, 'montant_min' => 100000, 'montant_max' => 1000000, 'montant_frais' => 500],

            ['id_operateur' => 3, 'id_operation' => 3, 'montant_min' => 0, 'montant_max' => 10000, 'montant_frais' => 50],
            ['id_operateur' => 3, 'id_operation' => 3, 'montant_min' => 10000, 'montant_max' => 100000, 'montant_frais' => 200],
            ['id_operateur' => 3, 'id_operation' => 3, 'montant_min' => 100000, 'montant_max' => 1000000, 'montant_frais' => 500],
        ]);

        // Clients
        $this->db->table('client')->insertBatch([
            ['nom' => 'Jean Dupont'],
            ['nom' => 'Marie Martin'],
            ['nom' => 'Paul Kouassi'],
            ['nom' => 'Fatou Diop'],
            ['nom' => 'Ali Touré'],
        ]);

        // Numéros
        $this->db->table('numero')->insertBatch([
            ['numero' => '0331234567', 'id_client' => 1, 'id_operateur' => 1, 'solde' => 150000],
            ['numero' => '0332345678', 'id_client' => 2, 'id_operateur' => 1, 'solde' => 25000],
            ['numero' => '0373456789', 'id_client' => 3, 'id_operateur' => 2, 'solde' => 50000],
            ['numero' => '0374567890', 'id_client' => 4, 'id_operateur' => 2, 'solde' => 125000],
            ['numero' => '0355678901', 'id_client' => 5, 'id_operateur' => 3, 'solde' => 8000],
        ]);

        // Historique des mouvements
        $this->db->table('mouvement')->insertBatch([
            [
                'id_operation' => 1,
                'id_numero_source' => null,
                'id_numero_destination' => 1,
                'montant' => 50000,
                'montant_frais' => 0,
                'id_tarif' => null,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ],
            [
                'id_operation' => 1,
                'id_numero_source' => null,
                'id_numero_destination' => 2,
                'montant' => 10000,
                'montant_frais' => 0,
                'id_tarif' => null,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ],
            [
                'id_operation' => 2,
                'id_numero_source' => 1,
                'id_numero_destination' => null,
                'montant' => 20000,
                'montant_frais' => 200,
                'id_tarif' => 4,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-4 days'))
            ],
            [
                'id_operation' => 2,
                'id_numero_source' => 3,
                'id_numero_destination' => null,
                'montant' => 5000,
                'montant_frais' => 50,
                'id_tarif' => 7,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'id_operation' => 3,
                'id_numero_source' => 2,
                'id_numero_destination' => 5,
                'montant' => 3000,
                'montant_frais' => 50,
                'id_tarif' => 16,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ],
            [
                'id_operation' => 3,
                'id_numero_source' => 4,
                'id_numero_destination' => 3,
                'montant' => 25000,
                'montant_frais' => 200,
                'id_tarif' => 17,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
        ]);
    }
}