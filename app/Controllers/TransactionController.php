<?php

namespace App\Controllers;

use App\Models\NumeroModel;
use App\Models\MouvementModel;
use App\Models\TarifModel;
use App\Models\CommissionModel;
use App\Models\PrefixeModel;

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

        $tarifModel = new TarifModel();
        $tarif = $tarifModel->findTarifApplicable($userdata['id_operateur'], 1, $montant);
        $frais = $tarif ? $tarif['montant_frais'] : 0;

        $nouveauSolde = $userdata['solde'] + $montant;
        $numeroModel->updateSolde($numero, $nouveauSolde);

        $mouvementModel = new MouvementModel();
        $mouvementModel->createDepot(
            $userdata['id'],
            $montant,
            $frais,
            $tarif ? $tarif['id'] : null
        );

        return redirect()->to('/Compte/solde')->with('success', 'Dépôt de ' . number_format($montant, 0, ',', ' ') . ' Ar effectué');
    }

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

        $tarifModel = new TarifModel();
        $tarif = $tarifModel->findTarifApplicable($userdata['id_operateur'], 2, $montant);
        $frais = $tarif ? $tarif['montant_frais'] : 0;
        $montantTotal = $montant + $frais;

        if ($userdata['solde'] < $montantTotal) {
            return redirect()->back()->with('error', 'Solde insuffisant. Solde disponible : ' . number_format($userdata['solde'], 0, ',', ' ') . ' Ar. Frais : ' . number_format($frais, 0, ',', ' ') . ' Ar');
        }

        $nouveauSolde = $userdata['solde'] - $montantTotal;
        $numeroModel->updateSolde($numero, $nouveauSolde);

        $mouvementModel = new MouvementModel();
        $mouvementModel->createRetrait(
            $userdata['id'],
            $montant,
            $frais,
            $tarif ? $tarif['id'] : null
        );

        return redirect()->to('/Compte/solde')->with('success', 'Retrait de ' . number_format($montant, 0, ',', ' ') . ' Ar effectué. Frais : ' . number_format($frais, 0, ',', ' ') . ' Ar');
    }
    
    public function transfert()
    {
        $numero = session()->get('numero');

        if (!$numero) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        $numeroModel = new NumeroModel();
        $sourceData = $numeroModel->findByNumero($numero);
        $tarifsTransfert = [];
        $tarifsRetrait = [];
        $commissions = [];
        $idOperateurSource = $sourceData['id_operateur'] ?? null;

        if ($sourceData) {
            $tarifModel = new TarifModel();
            $tarifsTransfert = $tarifModel->getTranchesByOperateurOperation($sourceData['id_operateur'], 3);
            $tarifsRetrait = $tarifModel->getTranchesByOperateurOperation($sourceData['id_operateur'], 2);
            
            $commissionModel = new CommissionModel();
            $commissions = $commissionModel->getCommissionsByOperateurDepart($sourceData['id_operateur']);
        }

  
        $prefixeModel = new PrefixeModel();
        $prefixeToOperateur = [];
        foreach ($prefixeModel->findAll() as $p) {
            $prefixeToOperateur[$p['prefixe']] = (int) $p['id_operateur'];
        }

        return view('Transaction/transfert', [
            'tarifsTransfert' => $tarifsTransfert,
            'tarifsRetrait' => $tarifsRetrait,
            'commissions' => $commissions,
            'idOperateurSource' => $idOperateurSource,
            'prefixeToOperateur' => $prefixeToOperateur
        ]);
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
        $inclureFrais = $this->request->getPost('inclure_frais') === '1' || $this->request->getPost('inclure_frais') === 'on';

        if (!$numeroDestinataire || !$montant || $montant <= 0) {
            return redirect()->back()->with('error', 'Tous les champs sont obligatoires');
        }

        if ($numeroSource == $numeroDestinataire) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous transférer à vous-même');
        }

        $numeroModel = new NumeroModel();
        
        $sourceData = $numeroModel->findByNumero($numeroSource);
        if (!$sourceData) {
            return redirect()->to('/Authentification/login')->with('error', 'Numéro source introuvable');
        }

        $destData = $numeroModel->findByNumero($numeroDestinataire);
        if (!$destData) {
            return redirect()->back()->with('error', 'Numéro destinataire introuvable');
        }

        $tarifModel = new TarifModel();
        $commissionModel = new CommissionModel();
        $memeOperateur = (int)$sourceData['id_operateur'] === (int)$destData['id_operateur'];
        
        $fraisTransfert = 0;
        $commission = 0;
        $fraisRetrait = 0;
        
        if ($memeOperateur) {
            $tarifTransfert = $tarifModel->findTarifApplicable($sourceData['id_operateur'], 3, $montant);
            $fraisTransfert = $tarifTransfert ? $tarifTransfert['montant_frais'] : 0;
        } else {
            $commissionData = $commissionModel->findByOperateurs($sourceData['id_operateur'], $destData['id_operateur']);
            if ($commissionData) {
                $commission = $montant * ($commissionData['pourcentage'] / 100);
            }
        }
        
        if ($inclureFrais && $memeOperateur) {
            $tarifRetrait = $tarifModel->findTarifApplicable($destData['id_operateur'], 2, $montant);
            $fraisRetrait = $tarifRetrait ? $tarifRetrait['montant_frais'] : 0;
        }

        $montantADebiterEmetteur = $montant + $fraisTransfert + $commission;
        $montantRecuDestinataire = $montant - $fraisRetrait;

        if ($sourceData['solde'] < $montantADebiterEmetteur) {
            return redirect()->back()->with('error', 'Solde insuffisant. Solde disponible : ' . number_format($sourceData['solde'], 0, ',', ' ') . ' Ar. Montant requis : ' . number_format($montantADebiterEmetteur, 0, ',', ' ') . ' Ar');
        }

        if ($inclureFrais && $fraisRetrait > 0 && $montant <= $fraisRetrait) {
            return redirect()->back()->with('error', 'Le montant doit être supérieur aux frais de retrait (' . number_format($fraisRetrait, 0, ',', ' ') . ' Ar)');
        }

        $nouveauSoldeSource = $sourceData['solde'] - $montantADebiterEmetteur;
        $numeroModel->updateSolde($numeroSource, $nouveauSoldeSource);

        $mouvementModel = new MouvementModel();
        
        $mouvementModel->createTransfert(
            $sourceData['id'],
            $destData['id'],
            $montant,
            $fraisTransfert + $commission,
            $memeOperateur && $fraisTransfert > 0 ? ($tarifTransfert ? $tarifTransfert['id'] : null) : null
        );
        
        if ($inclureFrais && $fraisRetrait > 0) {
            $mouvementModel->createRetrait(
                $destData['id'],
                $fraisRetrait,
                0,
                $tarifRetrait ? $tarifRetrait['id'] : null
            );
            $numeroModel->updateSolde($numeroDestinataire, $destData['solde'] + $montantRecuDestinataire);
        } else {
            $numeroModel->updateSolde($numeroDestinataire, $destData['solde'] + $montant);
        }

        if (!$memeOperateur && $commission > 0) {
            return redirect()->to('/Compte/solde')->with('success', 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . $numeroDestinataire . ' effectué. Commission : ' . number_format($commission, 0, ',', ' ') . ' Ar. Total débité : ' . number_format($montantADebiterEmetteur, 0, ',', ' ') . ' Ar');
        }

        if ($inclureFrais && $memeOperateur && $fraisRetrait > 0) {
            return redirect()->to('/Compte/solde')->with('success', 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . $numeroDestinataire . ' effectué. Le destinataire reçoit ' . number_format($montantRecuDestinataire, 0, ',', ' ') . ' Ar. Frais transfert : ' . number_format($fraisTransfert, 0, ',', ' ') . ' Ar. Frais retrait : ' . number_format($fraisRetrait, 0, ',', ' ') . ' Ar');
        }

        if ($memeOperateur && $fraisTransfert > 0) {
            return redirect()->to('/Compte/solde')->with('success', 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . $numeroDestinataire . ' effectué. Frais : ' . number_format($fraisTransfert, 0, ',', ' ') . ' Ar');
        }

        return redirect()->to('/Compte/solde')->with('success', 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . $numeroDestinataire . ' effectué.');
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