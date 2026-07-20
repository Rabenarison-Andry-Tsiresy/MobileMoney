<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        $this->call('OperationSeeder');
        $this->call('OperateurSeeder');
        $this->call('PrefixeSeeder');
        $this->call('TarifSeeder');
    }
}