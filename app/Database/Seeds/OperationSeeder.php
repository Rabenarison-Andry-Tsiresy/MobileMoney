<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperationSeeder extends Seeder
{
    public function run()
    {
        //
         $this->db->table('operation')->truncate();

        $data = [
            ['libelle' => 'depot'],
            ['libelle' => 'retrait'],
            ['libelle' => 'transfert'],
        ];

        $this->db->table('operation')->insertBatch($data);
    }
}
