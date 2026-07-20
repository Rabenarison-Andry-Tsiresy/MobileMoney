<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixeSeeder extends Seeder
{
    public function run()
    {
        //
        $this->db->table('prefixe')->truncate();

        // Récupère les opérateurs déjà seedés (libelle => id)
        $operateurs = $this->db->table('operateur')
            ->select('id, libelle')
            ->get()
            ->getResultArray();

        $map = [];
        foreach ($operateurs as $op) {
            $map[$op['libelle']] = $op['id'];
        }

        $prefixesParOperateur = [
            'Orange Money' => ['032','037'],
            'Airtel Money' => ['033'],
            'Telma Money'  => ['034', '038'],
        ];

        $data = [];
        foreach ($prefixesParOperateur as $libelle => $prefixes) {
            if (! isset($map[$libelle])) {
                continue; 
            }

            foreach ($prefixes as $prefixe) {
                $data[] = [
                    'id_operateur' => $map[$libelle],
                    'prefixe'      => $prefixe,
                ];
            }
        }

        $this->db->table('prefixe')->insertBatch($data);
    }
}
