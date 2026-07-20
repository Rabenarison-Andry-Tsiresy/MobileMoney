<?php

namespace App\Controllers;

use App\Models\NumeroModel;
use App\Models\MouvementModel;

class CompteController extends BaseController
{
    public function solde()
    {
        $numero = session()->get('numero');

        if (!$numero) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        $numeroModel = new NumeroModel();
        $userdata = $numeroModel
            ->select('numero.*, client.nom, operateur.libelle')
            ->join('client', 'client.id = numero.id_client')
            ->join('operateur', 'operateur.id = numero.id_operateur')
            ->where('numero.numero', $numero)
            ->first();

        if (!$userdata) {
            return redirect()->to('/Authentification/login')->with('error', 'Numéro introuvable');
        }

        $data = [
            'solde' => $userdata['solde'],
            'numero' => $userdata['numero'],
            'nom' => $userdata['nom'],
            'operateur' => $userdata['libelle']
        ];

        return view('Compte/solde', $data);
    }

    public function historique()
    {
        $numero = session()->get('numero');

        if (!$numero) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        $numeroModel = new NumeroModel();
        $numeroData = $numeroModel->where('numero', $numero)->first();
        
        if (!$numeroData) {
            return redirect()->to('/Authentification/login')->with('error', 'Numéro introuvable');
        }

        $idNumero = $numeroData['id'];
        $mouvementModel = new MouvementModel();
        $historique = $mouvementModel
            ->select('
                mouvement.*,
                operation.libelle as type_operation,
                source.numero as numero_source,
                dest.numero as numero_destination
            ')
            ->join('operation', 'operation.id = mouvement.id_operation')
            ->join('numero as source', 'source.id = mouvement.id_numero_source', 'left')
            ->join('numero as dest', 'dest.id = mouvement.id_numero_destination', 'left')
            ->where('mouvement.id_numero_source', $idNumero)
            ->orWhere('mouvement.id_numero_destination', $idNumero)
            ->orderBy('mouvement.date_transaction', 'DESC')
            ->findAll();

        $data = [
            'historique' => $historique,
            'numero' => $numero,
            'numero_id' => $idNumero
        ];

        return view('Compte/historique', $data);
    }
}