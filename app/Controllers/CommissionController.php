<?php

namespace App\Controllers;

use App\Models\OperateurModel;
use App\Models\CommissionModel;

class CommissionController extends BaseController
{
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        $commissionModel = new CommissionModel();
        $commissions = $commissionModel
            ->select('commission.*, op1.libelle as operateur_depart, op2.libelle as operateur_arrivee')
            ->join('operateur as op1', 'op1.id = commission.id_operateur_depart')
            ->join('operateur as op2', 'op2.id = commission.id_operateur_arrivee')
            ->findAll();

        return view('Commission/index', ['commissions' => $commissions]);
    }

    public function create()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        $operateurModel = new OperateurModel();
        $operateurs = $operateurModel->findAll();

        return view('Commission/create', ['operateurs' => $operateurs]);
    }

    public function store()
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        $idOperateurDepart = $this->request->getPost('id_operateur_depart');
        $idOperateurArrivee = $this->request->getPost('id_operateur_arrivee');
        $pourcentage = $this->request->getPost('pourcentage');

        if (!$idOperateurDepart || !$idOperateurArrivee || !$pourcentage || $pourcentage <= 0) {
            return redirect()->back()->with('error', 'Tous les champs sont obligatoires');
        }

        if ((int)$idOperateurDepart === (int)$idOperateurArrivee) {
            return redirect()->back()->with('error', 'Les opérateurs de départ et d\'arrivée doivent être différents');
        }

        $commissionModel = new CommissionModel();
        $data = [
            'id_operateur_depart' => $idOperateurDepart,
            'id_operateur_arrivee' => $idOperateurArrivee,
            'pourcentage' => $pourcentage
        ];

        if (!$commissionModel->save($data)) {
            return redirect()->back()->with('error', 'Erreur lors de la création de la commission');
        }

        return redirect()->to('/Commission')->with('success', 'Commission créée avec succès');
    }

    public function edit($id)
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        $commissionModel = new CommissionModel();
        $commission = $commissionModel->find($id);

        if (!$commission) {
            return redirect()->to('/Commission')->with('error', 'Commission introuvable');
        }

        $operateurModel = new OperateurModel();
        $operateurs = $operateurModel->findAll();

        return view('Commission/edit', [
            'commission' => $commission,
            'operateurs' => $operateurs
        ]);
    }

    public function update($id)
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        $commissionModel = new CommissionModel();
        $commission = $commissionModel->find($id);

        if (!$commission) {
            return redirect()->to('/Commission')->with('error', 'Commission introuvable');
        }

        $idOperateurDepart = $this->request->getPost('id_operateur_depart');
        $idOperateurArrivee = $this->request->getPost('id_operateur_arrivee');
        $pourcentage = $this->request->getPost('pourcentage');

        if (!$idOperateurDepart || !$idOperateurArrivee || !$pourcentage || $pourcentage <= 0) {
            return redirect()->back()->with('error', 'Tous les champs sont obligatoires');
        }

        if ((int)$idOperateurDepart === (int)$idOperateurArrivee) {
            return redirect()->back()->with('error', 'Les opérateurs de départ et d\'arrivée doivent être différents');
        }

        $data = [
            'id_operateur_depart' => $idOperateurDepart,
            'id_operateur_arrivee' => $idOperateurArrivee,
            'pourcentage' => $pourcentage
        ];

        if (!$commissionModel->update($id, $data)) {
            return redirect()->back()->with('error', 'Erreur lors de la modification de la commission');
        }

        return redirect()->to('/Commission')->with('success', 'Commission modifiée avec succès');
    }

    public function delete($id)
    {
        if (!session()->has('user_id')) {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez vous connecter');
        }

        $commissionModel = new CommissionModel();
        $commission = $commissionModel->find($id);

        if (!$commission) {
            return redirect()->to('/Commission')->with('error', 'Commission introuvable');
        }

        $commissionModel->delete($id);

        return redirect()->to('/Commission')->with('success', 'Commission supprimée avec succès');
    }
}
