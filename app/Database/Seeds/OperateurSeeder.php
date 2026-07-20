<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateurSeeder extends Seeder
{
    public function run()
    {
        //
        $this->db->table('operateur')->truncate();

        $data = [
            ['libelle' => 'Telma Money'],
            ['libelle' => 'Orange Money'],
            ['libelle' => 'Airtel Money'],
        ];

        $this->db->table('operateur')->insertBatch($data);
    }
}
