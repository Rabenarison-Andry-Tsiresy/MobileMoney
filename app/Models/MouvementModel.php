<?php

namespace App\Models;

use CodeIgniter\Model;

class MouvementModel extends Model
{
    protected $table = 'mouvement';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_operation',
        'id_numero_source',
        'id_numero_destination',
        'montant',
        'montant_frais',
        'id_tarif'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'date_transaction';
    protected $updatedField = '';

    // CREATE DEPOT
    public function createDepot($idNumeroDestination, $montant, $frais = 0, $idTarif = null)
    {
        $data = [
            'id_operation' => 1,
            'id_numero_source' => null,
            'id_numero_destination' => $idNumeroDestination,
            'montant' => $montant,
            'montant_frais' => $frais,
            'id_tarif' => $idTarif
        ];
        return $this->insert($data);
    }

    // CREATE RETRAIT
    public function createRetrait($idNumeroSource, $montant, $frais, $idTarif = null)
    {
        $data = [
            'id_operation' => 2,
            'id_numero_source' => $idNumeroSource,
            'id_numero_destination' => null,
            'montant' => $montant,
            'montant_frais' => $frais,
            'id_tarif' => $idTarif
        ];
        return $this->insert($data);
    }

    // CREATE TRANSFERT
    public function createTransfert($idNumeroSource, $idNumeroDestination, $montant, $frais, $idTarif = null)
    {
        $data = [
            'id_operation' => 3,
            'id_numero_source' => $idNumeroSource,
            'id_numero_destination' => $idNumeroDestination,
            'montant' => $montant,
            'montant_frais' => $frais,
            'id_tarif' => $idTarif
        ];
        return $this->insert($data);
    }

    public function getHistorique($idNumero)
    {
        return $this->where('id_numero_source', $idNumero)
            ->orWhere('id_numero_destination', $idNumero)
            ->orderBy('date_transaction', 'DESC')
            ->findAll();
    }
}