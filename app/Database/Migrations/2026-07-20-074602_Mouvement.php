<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Mouvement extends Migration
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
            'id_operation' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_numero_source' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_numero_destination' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'montant' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => false,
            ],
            'montant_frais' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => false,
                'default'    => 0,
            ],
            'id_tarif' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'date_transaction' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => date('Y-m-d H:i:s'),
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_operation', 'operation', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_numero_source', 'numero', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('id_numero_destination', 'numero', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('id_tarif', 'tarif', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('mouvement');
        
        // Index pour SQLite
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_mouvement_source ON mouvement (id_numero_source)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_mouvement_destination ON mouvement (id_numero_destination)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_mouvement_date ON mouvement (date_transaction)');
    }

    public function down()
    {
        $this->forge->dropTable('mouvement');
    }
}