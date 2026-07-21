<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateAdmin extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255], // 255 car le mot de passe sera crypté (hash)
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('admin');
    }

    public function down() { $this->forge->dropTable('admin'); }
}