<?php

namespace App\Controllers;

use App\Models\NumeroModel;
use App\Models\MouvementModel;
use App\Models\TarifModel;

class TransactionController extends BaseController
{
   
    public function depot()
    {
        $numero = session()->get('numero');

        if (!$numero) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        return view('Transaction/depot');
    }

    public function faireDepot()
    {
        $numero = session()->get('numero');
        $montant = $this->request->getPost('montant');

        if (!$numero) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        if (!$montant || $montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }

        $numeroModel = new NumeroModel();
        $userdata = $numeroModel->findByNumero($numero);

        if (!$userdata) {
            return redirect()->to('/Authentification/login')->with('error', 'Numéro introuvable');
        }

        // Mettre à jour le solde
        $nouveauSolde = $userdata['solde'] + $montant;
        $numeroModel->updateSolde($numero, $nouveauSolde);

        // Créer le mouvement
        $mouvementModel = new MouvementModel();
        $mouvementModel->createDepot($userdata['id'], $montant);

        return redirect()->to('/solde')->with('success', 'Dépôt de ' . number_format($montant, 0, ',', ' ') . ' FCFA effectué');
    }

    // ============================================
    // RETRAIT
    // ============================================
    public function retrait()
    {
        $numero = session()->get('numero');

        if (!$numero) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        return view('Transaction/retrait');
    }

    public function faireRetrait()
    {
        $numero = session()->get('numero');
        $montant = $this->request->getPost('montant');

        if (!$numero) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        if (!$montant || $montant <= 0) {
            return redirect()->back()->with('error', 'Montant invalide');
        }

        $numeroModel = new NumeroModel();
        $userdata = $numeroModel->findByNumero($numero);

        if (!$userdata) {
            return redirect()->to('/Authentification/login')->with('error', 'Numéro introuvable');
        }

        // Calculer les frais
        $tarifModel = new TarifModel();
        $tarif = $tarifModel->findTarif($userdata['id_operateur'], 2, $montant);
        
        $frais = $tarif ? $tarif['montant_frais'] : 0;
        $montantTotal = $montant + $frais;

        // Vérifier le solde
        if ($userdata['solde'] < $montantTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Solde disponible : ' . number_format($userdata['solde'], 0, ',', ' ') . ' FCFA');
        }

        // Mettre à jour le solde
        $nouveauSolde = $userdata['solde'] - $montantTotal;
        $numeroModel->updateSolde($numero, $nouveauSolde);

        // Créer le mouvement
        $mouvementModel = new MouvementModel();
        $mouvementModel->createRetrait(
            $userdata['id'], 
            $montant, 
            $frais, 
            $tarif ? $tarif['id'] : null
        );

        return redirect()->to('/solde')->with('success', 'Retrait de ' . number_format($montant, 0, ',', ' ') . ' FCFA effectué. Frais : ' . number_format($frais, 0, ',', ' ') . ' FCFA');
    }

   
    public function transfert()
    {
        $numero = session()->get('numero');

        if (!$numero) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        return view('Transaction/transfert');
    }

    public function faireTransfert()
    {
        $numeroSource = session()->get('numero');
        $numeroDestinataire = $this->request->getPost('numero_destinataire');
        $montant = $this->request->getPost('montant');

        if (!$numeroSource) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        if (!$numeroDestinataire || !$montant || $montant <= 0) {
            return redirect()->back()->with('error', 'Tous les champs sont obligatoires');
        }

        if ($numeroSource == $numeroDestinataire) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous transférer à vous-même');
        }

        $numeroModel = new NumeroModel();
        
        // Récupérer les infos de l'émetteur
        $sourceData = $numeroModel->findByNumero($numeroSource);
        if (!$sourceData) {
            return redirect()->to('/Authentification/login')->with('error', 'Numéro source introuvable');
        }

        // Récupérer les infos du destinataire
        $destData = $numeroModel->findByNumero($numeroDestinataire);
        if (!$destData) {
            return redirect()->back()->with('error', 'Numéro destinataire introuvable');
        }

        // Calculer les frais
        $tarifModel = new TarifModel();
        $tarif = $tarifModel->findTarif($sourceData['id_operateur'], 3, $montant);
        
        $frais = $tarif ? $tarif['montant_frais'] : 0;
        $montantTotal = $montant + $frais;

        // Vérifier le solde de l'émetteur
        if ($sourceData['solde'] < $montantTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Solde disponible : ' . number_format($sourceData['solde'], 0, ',', ' ') . ' FCFA');
        }

        // Mettre à jour les soldes
        $nouveauSoldeSource = $sourceData['solde'] - $montantTotal;
        $nouveauSoldeDest = $destData['solde'] + $montant;

        $numeroModel->updateSolde($numeroSource, $nouveauSoldeSource);
        $numeroModel->updateSolde($numeroDestinataire, $nouveauSoldeDest);

        // Créer le mouvement
        $mouvementModel = new MouvementModel();
        $mouvementModel->createTransfert(
            $sourceData['id'],
            $destData['id'],
            $montant,
            $frais,
            $tarif ? $tarif['id'] : null
        );

        return redirect()->to('/solde')->with('success', 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' FCFA vers ' . $numeroDestinataire . ' effectué. Frais : ' . number_format($frais, 0, ',', ' ') . ' FCFA');
    }
}