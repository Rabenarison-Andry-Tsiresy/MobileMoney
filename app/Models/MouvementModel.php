<?php 

namespace App\Models;
use CodeIgniter\Model;

class MouvementModel extends Model
{
    protected $table = 'mouvement';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_numero', 'type', 'montant', 'date_mouvement'];
    protected $useTimestamps = true;
    protected $createdField = 'date_mouvement';
    protected $updatedField = null;

    public function getHistorique($numero)
    {
        return $this->where('id_numero', $numero)->findAll();
    }

    public function createMouvement($data)
    {
        return $this->insert($data);
    }
}

?>