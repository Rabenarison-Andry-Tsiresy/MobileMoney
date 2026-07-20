<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommissionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_operateur_depart' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_operateur_arrivee' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'pourcentage' => [
                'type'       => 'FLOAT',
                'null'       => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_operateur_depart', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_operateur_arrivee', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('commission');
        
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_commission_lookup ON commission (id_operateur_depart, id_operateur_arrivee)');
    }

    public function down()
    {
        $this->forge->dropTable('commission');
    }
}
