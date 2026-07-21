<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Epargne extends Migration
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
            'id_numero' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'solde' => [
                'type'       => 'FLOAT',
                'constraint' => 11,
                'unsigned'   => true,
            ]
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_numero', 'numero', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('epargne');
        
        // Index pour SQLite (if using SQLite)
        // Note: These indexes are for a 'mouvement' table that doesn't exist in this migration
        // You should either remove these or ensure the 'mouvement' table exists
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_mouvement_source ON mouvement (id_numero_source)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_mouvement_destination ON mouvement (id_numero_destination)');
        $this->db->query('CREATE INDEX IF NOT EXISTS idx_mouvement_date ON mouvement (date_transaction)');
    }

    public function down()
    {
        $this->forge->dropTable('epargne');
    }
}