<?php 
namespace App\Models;

use CodeIgniter\Model;

class EpargneModel extends Model
{
    protected $table = '';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_numero','solde'];
    protected $updatedField = '';
    
    // id numero
    public function creerEpargne($id){
           return $this->create(['id_numero'=> $id],['solde'=> 0]);
    }
    public function getEpargne($id){
        $eparne = $this->find($id);
        if($eparne){
            return $eparne;
        }
        else{
             $this->creerEpargne($id);
        }
    }

    public function updateSoldeEpargne($id,$montantajout){
       $epargne = $this->where('id',$id);
       $montantfinal = $epargne['solde'] + $montantajout;

        return $this->where('id', $id)->update(null, [
            'solde' => (float) $montantfinal
        ]); 
    } 
    
}