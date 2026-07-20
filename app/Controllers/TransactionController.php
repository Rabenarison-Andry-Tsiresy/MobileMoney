<?php

namespace App\Controllers;

use App\Models\NumeroModel;
use App\Models\MouvementModel;
use App\Models\TarifModel;

class TransactionController extends BaseController
{
   
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
        $mode = $this->request->getPost('mode');

        if (!$numeroSource) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        if ($mode === 'simple') {
            return $this->transfertSimple($numeroSource);
        } elseif ($mode === 'multiple') {
            return $this->transfertMultiple($numeroSource);
        }

        return redirect()->back()->with('error', 'Mode de transfert invalide');
    }

    // ============================================
    // TRANSFERT SIMPLE
    // ============================================
    private function transfertSimple($numeroSource)
    {
        $numeroDestinataire = $this->request->getPost('numero_destinataire');
        $montant = $this->request->getPost('montant');

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
        $tarif = $tarifModel->findTarifApplicable($sourceData['id_operateur'], 3, $montant);
        
        $frais = $tarif ? $tarif['montant_frais'] : 0;
        $montantTotal = $montant + $frais;

        // Vérifier le solde de l'émetteur
        if ($sourceData['solde'] < $montantTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Solde disponible : ' . number_format($sourceData['solde'], 0, ',', ' ') . ' Ar');
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

        return redirect()->to('/Compte/solde')->with('success', 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . $numeroDestinataire . ' effectué. Frais : ' . number_format($frais, 0, ',', ' ') . ' Ar');
    }

    // ============================================
    // TRANSFERT MULTIPLE
    // ============================================
    private function transfertMultiple($numeroSource)
    {
        $destinataires = $this->request->getPost('destinataires');

        if (!$destinataires || !is_array($destinataires)) {
            return redirect()->back()->with('error', 'Aucun destinataire');
        }

        $numeroModel = new NumeroModel();
        $sourceData = $numeroModel->findByNumero($numeroSource);
        if (!$sourceData) {
            return redirect()->to('/Authentification/login')->with('error', 'Numéro source introuvable');
        }

        $montantTotal = 0;
        $destinatairesValides = [];

        foreach ($destinataires as $dest) {
            if (empty($dest['numero']) || empty($dest['montant']) || $dest['montant'] <= 0) {
                return redirect()->back()->with('error', 'Tous les champs sont obligatoires');
            }

            if ($numeroSource == $dest['numero']) {
                return redirect()->back()->with('error', 'Vous ne pouvez pas vous transférer à vous-même');
            }

            $destData = $numeroModel->findByNumero($dest['numero']);
            if (!$destData) {
                return redirect()->back()->with('error', 'Numéro destinataire introuvable : ' . $dest['numero']);
            }

            if ((int)$destData['id_operateur'] !== (int)$sourceData['id_operateur']) {
                return redirect()->back()->with('error', 'Le destinataire ' . $dest['numero'] . ' n\'est pas du même opérateur que vous. Le transfert multiple est autorisé uniquement vers le même opérateur.');
            }

            $montantTotal += $dest['montant'];
            $destinatairesValides[] = [
                'numero' => $dest['numero'],
                'montant' => $dest['montant'],
                'id' => $destData['id'],
                'solde' => $destData['solde']
            ];
        }

        $tarifModel = new TarifModel();
        $tarif = $tarifModel->findTarifApplicable($sourceData['id_operateur'], 3, $montantTotal);
        $frais = $tarif ? $tarif['montant_frais'] : 0;
        $montantTotalAvecFrais = $montantTotal + $frais;

        if ($sourceData['solde'] < $montantTotalAvecFrais) {
            return redirect()->back()->with('error', 'Solde insuffisant. Solde disponible : ' . number_format($sourceData['solde'], 0, ',', ' ') . ' Ar. Total requis : ' . number_format($montantTotalAvecFrais, 0, ',', ' ') . ' Ar');
        }

        $mouvementModel = new MouvementModel();
        $successCount = 0;
        $montantTotalEnvoye = 0;

        foreach ($destinatairesValides as $dest) {
            $numeroModel->updateSolde($dest['numero'], $dest['solde'] + $dest['montant']);
            $montantTotalEnvoye += $dest['montant'];

            $mouvementModel->createTransfert(
                $sourceData['id'],
                $dest['id'],
                $dest['montant'],
                0,
                null
            );

            $successCount++;
        }

        $soldeFinal = $sourceData['solde'] - $montantTotalEnvoye - $frais;
        $numeroModel->updateSolde($numeroSource, $soldeFinal);

        if ($successCount > 0) {
            return redirect()->to('/Compte/solde')->with('success', 'Transferts multiples effectués avec succès ! ' . $successCount . ' destinataires servis. Total : ' . number_format($montantTotalEnvoye, 0, ',', ' ') . ' Ar. Frais : ' . number_format($frais, 0, ',', ' ') . ' Ar');
        }

        return redirect()->back()->with('error', 'Aucun transfert n\'a pu être effectué');
    }
}