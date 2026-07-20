<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Updaterajout extends Seeder
{
    public function run()
    {
        echo "🔄 Insertion des données de base...\n";

        // ============================================
        // 1. OPÉRATIONS
        // ============================================
        $this->db->table('operation')->insertBatch([
            ['libelle' => 'depot'],
            ['libelle' => 'retrait'],
            ['libelle' => 'transfert'],
        ]);
        echo "✓ Opérations insérées\n";

        // ============================================
        // 2. OPÉRATEURS (3 opérateurs)
        // ============================================
        $this->db->table('operateur')->insertBatch([
            ['libelle' => 'Orange'],
            ['libelle' => 'Airtel'],
            ['libelle' => 'Telma'],
        ]);
        echo "✓ Opérateurs insérés (Orange:1, Airtel:2, Telma:3)\n";

        // ============================================
        // 3. PRÉFIXES
        // ============================================
        $this->db->table('prefixe')->insertBatch([
            // Orange (id_operateur = 1)
            ['id_operateur' => 1, 'prefixe' => '032'],
            ['id_operateur' => 1, 'prefixe' => '037'],
            // Airtel (id_operateur = 2)
            ['id_operateur' => 2, 'prefixe' => '033'],
            // Telma (id_operateur = 3)
            ['id_operateur' => 3, 'prefixe' => '034'],
            ['id_operateur' => 3, 'prefixe' => '038'],
        ]);
        echo "✓ Préfixes insérés\n";

        // ============================================
        // 4. TARIFS
        // ============================================
        $this->db->table('tarif')->insertBatch([
            // ===== Orange (id_operateur = 1) =====
            ['id_operateur' => 1, 'id_operation' => 1, 'montant_min' => 0, 'montant_max' => 1000000, 'montant_frais' => 0],
            ['id_operateur' => 1, 'id_operation' => 2, 'montant_min' => 0, 'montant_max' => 5000, 'montant_frais' => 50],
            ['id_operateur' => 1, 'id_operation' => 2, 'montant_min' => 5000, 'montant_max' => 50000, 'montant_frais' => 200],
            ['id_operateur' => 1, 'id_operation' => 2, 'montant_min' => 50000, 'montant_max' => 200000, 'montant_frais' => 500],
            ['id_operateur' => 1, 'id_operation' => 3, 'montant_min' => 0, 'montant_max' => 10000, 'montant_frais' => 50],
            ['id_operateur' => 1, 'id_operation' => 3, 'montant_min' => 10000, 'montant_max' => 100000, 'montant_frais' => 200],
            ['id_operateur' => 1, 'id_operation' => 3, 'montant_min' => 100000, 'montant_max' => 1000000, 'montant_frais' => 500],

            // ===== Airtel (id_operateur = 2) =====
            ['id_operateur' => 2, 'id_operation' => 1, 'montant_min' => 0, 'montant_max' => 1000000, 'montant_frais' => 0],
            ['id_operateur' => 2, 'id_operation' => 2, 'montant_min' => 0, 'montant_max' => 5000, 'montant_frais' => 50],
            ['id_operateur' => 2, 'id_operation' => 2, 'montant_min' => 5000, 'montant_max' => 50000, 'montant_frais' => 200],
            ['id_operateur' => 2, 'id_operation' => 2, 'montant_min' => 50000, 'montant_max' => 200000, 'montant_frais' => 500],
            ['id_operateur' => 2, 'id_operation' => 3, 'montant_min' => 0, 'montant_max' => 10000, 'montant_frais' => 50],
            ['id_operateur' => 2, 'id_operation' => 3, 'montant_min' => 10000, 'montant_max' => 100000, 'montant_frais' => 200],
            ['id_operateur' => 2, 'id_operation' => 3, 'montant_min' => 100000, 'montant_max' => 1000000, 'montant_frais' => 500],

            // ===== Telma (id_operateur = 3) =====
            ['id_operateur' => 3, 'id_operation' => 1, 'montant_min' => 0, 'montant_max' => 1000000, 'montant_frais' => 0],
            ['id_operateur' => 3, 'id_operation' => 2, 'montant_min' => 0, 'montant_max' => 5000, 'montant_frais' => 50],
            ['id_operateur' => 3, 'id_operation' => 2, 'montant_min' => 5000, 'montant_max' => 50000, 'montant_frais' => 200],
            ['id_operateur' => 3, 'id_operation' => 2, 'montant_min' => 50000, 'montant_max' => 200000, 'montant_frais' => 500],
            ['id_operateur' => 3, 'id_operation' => 3, 'montant_min' => 0, 'montant_max' => 10000, 'montant_frais' => 50],
            ['id_operateur' => 3, 'id_operation' => 3, 'montant_min' => 10000, 'montant_max' => 100000, 'montant_frais' => 200],
            ['id_operateur' => 3, 'id_operation' => 3, 'montant_min' => 100000, 'montant_max' => 1000000, 'montant_frais' => 500],
        ]);
        echo "✓ Tarifs insérés\n";

        // ============================================
        // 4.5. COMMISSIONS (transferts inter-opérateurs)
        // ============================================
        $this->db->table('commission')->insertBatch([
            // Orange (1) -> Airtel (2)
            ['id_operateur_depart' => 1, 'id_operateur_arrivee' => 2, 'pourcentage' => 2.5],
            // Orange (1) -> Telma (3)
            ['id_operateur_depart' => 1, 'id_operateur_arrivee' => 3, 'pourcentage' => 3],
            // Airtel (2) -> Orange (1)
            ['id_operateur_depart' => 2, 'id_operateur_arrivee' => 1, 'pourcentage' => 2],
            // Airtel (2) -> Telma (3)
            ['id_operateur_depart' => 2, 'id_operateur_arrivee' => 3, 'pourcentage' => 2.5],
            // Telma (3) -> Orange (1)
            ['id_operateur_depart' => 3, 'id_operateur_arrivee' => 1, 'pourcentage' => 3],
            // Telma (3) -> Airtel (2)
            ['id_operateur_depart' => 3, 'id_operateur_arrivee' => 2, 'pourcentage' => 2.5],
        ]);
        echo "✓ Commissions insérées\n";

        // ============================================
        // 5. CLIENTS
        // ============================================
        $this->db->table('client')->insertBatch([
            ['nom' => 'Jean Dupont'],
            ['nom' => 'Marie Martin'],
            ['nom' => 'Paul Kouassi'],
            ['nom' => 'Fatou Diop'],
            ['nom' => 'Ali Touré'],
            ['nom' => 'Rakoto Jean'],
            ['nom' => 'Rabe Marie'],
            ['nom' => 'Randria Paul'],
            ['nom' => 'Rajoelina Fara'],
            ['nom' => 'Rakotomalala Tiana'],
            ['nom' => 'Rasoa Lanto'],
            ['nom' => 'Ravelo Heri'],
            ['nom' => 'Rakotondrabe Mamy'],
            ['nom' => 'Razafindrakoto Jules'],
            ['nom' => 'Ranaivo Mirana'],
        ]);
        echo "✓ Clients insérés (IDs: 1 à 15)\n";

        // ============================================
        // 6. NUMÉROS AVEC SOLDE (UNIQUES)
        // ============================================
        $this->db->table('numero')->insertBatch([
            // ===== Orange (id_operateur = 1) =====
            ['numero' => '0321234567', 'id_client' => 1, 'id_operateur' => 1, 'solde' => 150000],
            ['numero' => '0322345678', 'id_client' => 2, 'id_operateur' => 1, 'solde' => 25000],
            ['numero' => '0323456789', 'id_client' => 3, 'id_operateur' => 1, 'solde' => 75000],
            ['numero' => '0324567890', 'id_client' => 4, 'id_operateur' => 1, 'solde' => 120000],
            ['numero' => '0371234567', 'id_client' => 5, 'id_operateur' => 1, 'solde' => 45000],
            ['numero' => '0372345678', 'id_client' => 6, 'id_operateur' => 1, 'solde' => 180000],

            // ===== Airtel (id_operateur = 2) =====
            ['numero' => '0331234567', 'id_client' => 7, 'id_operateur' => 2, 'solde' => 50000],
            ['numero' => '0332345678', 'id_client' => 8, 'id_operateur' => 2, 'solde' => 95000],
            ['numero' => '0333456789', 'id_client' => 9, 'id_operateur' => 2, 'solde' => 60000],
            ['numero' => '0334567890', 'id_client' => 10, 'id_operateur' => 2, 'solde' => 30000],

            // ===== Telma (id_operateur = 3) =====
            ['numero' => '0341234567', 'id_client' => 11, 'id_operateur' => 3, 'solde' => 125000],
            ['numero' => '0342345678', 'id_client' => 12, 'id_operateur' => 3, 'solde' => 55000],
            ['numero' => '0381234567', 'id_client' => 13, 'id_operateur' => 3, 'solde' => 85000],
            ['numero' => '0382345678', 'id_client' => 14, 'id_operateur' => 3, 'solde' => 200000],
            ['numero' => '0383456789', 'id_client' => 15, 'id_operateur' => 3, 'solde' => 32000],
        ]);
        echo "✓ Numéros insérés\n";

        // ============================================
        // 7. MOUVEMENTS (Historique)
        // ============================================
        $this->db->table('mouvement')->insertBatch([
            // Dépôts
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
                'id_operation' => 1,
                'id_numero_source' => null,
                'id_numero_destination' => 7,
                'montant' => 25000,
                'montant_frais' => 0,
                'id_tarif' => null,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            // Retraits
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
                'id_operation' => 2,
                'id_numero_source' => 11,
                'id_numero_destination' => null,
                'montant' => 15000,
                'montant_frais' => 200,
                'id_tarif' => 4,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            // Transferts
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
            [
                'id_operation' => 3,
                'id_numero_source' => 10,
                'id_numero_destination' => 14,
                'montant' => 10000,
                'montant_frais' => 200,
                'id_tarif' => 17,
                'date_transaction' => date('Y-m-d H:i:s', strtotime('-6 hours'))
            ],
        ]);
        echo "✓ Mouvements insérés\n";

        echo "✅ Toutes les données ont été insérées avec succès !\n";
        echo "\n📊 Récapitulatif des opérateurs :\n";
        echo "   - Orange (id=1) : préfixes 032, 037\n";
        echo "   - Airtel (id=2) : préfixe 033\n";
        echo "   - Telma (id=3) : préfixes 034, 038\n";
    }
}