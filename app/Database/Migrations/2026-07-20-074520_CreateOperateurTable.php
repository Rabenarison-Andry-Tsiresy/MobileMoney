<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOperateurTable extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
    
            'libelle' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('libelle');
        $this->forge->createTable('operateur');
    }

    public function down()
    {
        //
        $this->forge->dropTable('operateur');
    }
}
