<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTarifTable extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_operation' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'   => false,
            ],
            'id_operateur' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'   => false,

            ],
            'montant_min' => [
                'type'       => 'DECIMAL',
                'constraint' => [10, 2],
            ],
            'montant_max' => [
                'type'       => 'DECIMAL',
                'constraint' => [10, 2],
            ],
            'montant_frais' => [
                'type'       => 'DECIMAL',
                'constraint' => [10, 2],
                'null'   => false,
            ],
            'created_at datetime default current_timestamp',
            'updated_at datetime default current_timestamp on update current_timestamp',
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_operation', 'operation', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_operateur', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tarif');
    }

    public function down()
    {
        //
        $this->forge->dropTable('tarif');
    }
}
