<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrefixeTable extends Migration
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
            'id_operateur' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'prefixe' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => false,
                'unique'     => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_operateur', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('prefixe');
        
        // Index pour SQLite
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_prefixe_operateur ON prefixe (id_operateur)');
    }

    public function down()
    {
        $this->forge->dropTable('prefixe');
    }
}
