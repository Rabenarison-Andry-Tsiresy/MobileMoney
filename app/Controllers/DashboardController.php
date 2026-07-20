<?php

namespace App\Controllers;

use App\Models\NumeroModel;
use App\Models\MouvementModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        $numero = session()->get('numero');
        $userId = session()->get('user_id');
        
        $numeroModel = new NumeroModel();
        $mouvementModel = new MouvementModel();
        
        // Récupérer les infos du client
        $userdata = $numeroModel
            ->select('numero.*, client.nom, operateur.libelle')
            ->join('client', 'client.id = numero.id_client')
            ->join('operateur', 'operateur.id = numero.id_operateur')
            ->where('numero.numero', $numero)
            ->first();
        
        // Récupérer les dernières transactions
        $dernieresTransactions = $mouvementModel
            ->select('
                mouvement.*,
                operation.libelle as type_operation,
                source.numero as numero_source,
                dest.numero as numero_destination
            ')
            ->join('operation', 'operation.id = mouvement.id_operation')
            ->join('numero as source', 'source.id = mouvement.id_numero_source', 'left')
            ->join('numero as dest', 'dest.id = mouvement.id_numero_destination', 'left')
            ->where('mouvement.id_numero_source', $userId)
            ->orWhere('mouvement.id_numero_destination', $userId)
            ->orderBy('mouvement.date_transaction', 'DESC')
            ->findAll(5);
        
        // Statistiques
        $statistiques = $this->getStatistiques($userId);
        
        $data = [
            'nom' => $userdata['nom'] ?? 'Client',
            'numero' => $userdata['numero'] ?? '',
            'solde' => $userdata['solde'] ?? 0,
            'operateur' => $userdata['libelle'] ?? '',
            'dernieres_transactions' => $dernieresTransactions,
            'statistiques' => $statistiques
        ];

        return view('Dashboard', $data);
    }
    
    private function getStatistiques($userId)
    {
        $mouvementModel = new MouvementModel();
        
        // Nombre total de transactions
        $totalTransactions = $mouvementModel
            ->where('id_numero_source', $userId)
            ->orWhere('id_numero_destination', $userId)
            ->countAllResults();
        
        // Total des dépôts
        $totalDepots = $mouvementModel
            ->where('id_numero_destination', $userId)
            ->where('id_operation', 1)
            ->selectSum('montant')
            ->first();
        
        // Total des retraits
        $totalRetraits = $mouvementModel
            ->where('id_numero_source', $userId)
            ->where('id_operation', 2)
            ->selectSum('montant')
            ->first();
        
        // Total des transferts envoyés
        $totalTransfertsEnvoyes = $mouvementModel
            ->where('id_numero_source', $userId)
            ->where('id_operation', 3)
            ->selectSum('montant')
            ->first();
        
        // Total des transferts reçus
        $totalTransfertsRecus = $mouvementModel
            ->where('id_numero_destination', $userId)
            ->where('id_operation', 3)
            ->selectSum('montant')
            ->first();
        
        return [
            'total_transactions' => $totalTransactions,
            'total_depots' => $totalDepots['montant'] ?? 0,
            'total_retraits' => $totalRetraits['montant'] ?? 0,
            'total_transferts_envoyes' => $totalTransfertsEnvoyes['montant'] ?? 0,
            'total_transferts_recus' => $totalTransfertsRecus['montant'] ?? 0
        ];
    }
}