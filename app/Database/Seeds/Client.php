<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Client extends Seeder
{
    public function run()
    {
       
        $this->db->table('client')->insertBatch([
            ['nom' => 'Jean Dupont'],
            ['nom' => 'Marie Martin'],
            ['nom' => 'Paul Kouassi'],
            ['nom' => 'Fatou Diop'],
            ['nom' => 'Ali Touré'],
            ['nom' => 'Sophie Koffi'],
            ['nom' => 'David Yao'],
            ['nom' => 'Claire Bamba'],
            ['nom' => 'Marc Ouattara'],
            ['nom' => 'Amina Diallo'],
        ]);

    }        
}