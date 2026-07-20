<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Numero extends Migration
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
            'numero' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true,
                'null'       => false,
            ],
            'id_client' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_operateur' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'solde' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'null'       => false,
            ],
            'date_creation' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => date('Y-m-d H:i:s'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_client', 'client', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_operateur', 'operateur', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('numero');
        
        // Ajouter des index pour les recherches
        $this->db->query('CREATE INDEX idx_numero_client ON numero (id_client)');
        $this->db->query('CREATE INDEX idx_numero_operateur ON numero (id_operateur)');
        $this->db->query('CREATE INDEX idx_numero_numero ON numero (numero)');
    }

    public function down()
    {
        $this->forge->dropTable('numero');
    }
}