<?php 
namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'client';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom'];
    protected $useTimestamps = true;
    protected $createdField = 'date_creation';
    protected $updatedField = null;
    

    public function getClientById($id)
    {
        return $this->find($id);
    }

    public function getListClients()
    {
        return $this->findAll();
    }

    public function createClient($data)
    {
        return $this->insert($data);
    }

    public function updateClient($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deleteClient($id)
    {
        return $this->delete($id);
    }
}