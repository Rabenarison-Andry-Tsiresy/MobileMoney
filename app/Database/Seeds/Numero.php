<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Numero extends Seeder
{
    public function run()
    {
        $this->db->table('numero')->insertBatch([
            [
                'numero' => '0331234567',
                'id_client' => 1,
                'id_operateur' => NULL,
                'solde' => 150000
            ],
            [
                'numero' => '0332345678',
                'id_client' => 2,
                'id_operateur' => NULL,
                'solde' => 25000
            ],
            [
                'numero' => '0373456789',
                'id_client' => 3,
                'id_operateur' => NULL,
                'solde' => 50000
            ],
            [
                'numero' => '0374567890',
                'id_client' => 4,
                'id_operateur' => NULL,
                'solde' => 125000
            ],
            [
                'numero' => '0355678901',
                'id_client' => 5,
                'id_operateur' => NULL,
                'solde' => 8000
            ],
            [
                'numero' => '0336789012',
                'id_client' => 6,
                'id_operateur' => NULL,
                'solde' => 75000
            ],
            [
                'numero' => '0387890123',
                'id_client' => 7,
                'id_operateur' => NULL,
                'solde' => 300000
            ],
            [
                'numero' => '0368901234',
                'id_client' => 8,
                'id_operateur' => NULL,
                'solde' => 42000
            ],
            [
                'numero' => '0339012345',
                'id_client' => 9,
                'id_operateur' => NULL,
                'solde' => 100000
            ],
            [
                'numero' => '0370123456',
                'id_client' => 10,
                'id_operateur' => NULL,
                'solde' => 60000
            ],
        ]);
    }
}