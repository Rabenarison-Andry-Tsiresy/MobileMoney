<?php
namespace App\Models;

use CodeIgniter\Model;

class NumeroModel extends Model
{
    protected $table = 'numero';
    protected $primaryKey = 'id';
    protected $allowedFields = ['numero', 'id_client', 'id_operateur', 'solde'];
    protected $useTimestamps = true;
    protected $createdField = 'date_creation';
    protected $updatedField = '';

    public function findByNumero($numero)
    {
        return $this->where('numero', $numero)->first();
    }

    public function creerAvecClient($numero, $clientId, $idOperateur)
    {
        $data = [
            'numero' => $numero,
            'id_client' => $clientId,
            'id_operateur' => $idOperateur,
            'solde' => 0
        ];
        return $this->insert($data);
    }

    public function updateSolde($numero, $nouveauSolde)
    {

        return $this->where('numero', $numero)->update(null, [
            'solde' => (float) $nouveauSolde
        ]);
    }

}
?>