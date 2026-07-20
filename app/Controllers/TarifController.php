<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TarifModel;
use App\Models\OperateurModel;
use App\Models\OperationModel;

class TarifController extends BaseController
{
    protected $tarifModel;
    protected $operateurModel;
    protected $operationModel;

    public function __construct()
    {
        $this->tarifModel = new TarifModel();
        $this->operateurModel = new OperateurModel();
        $this->operationModel = new OperationModel();
    }

    public function index()
    {
        $data['tarifs'] = $this->tarifModel
            ->select('tarif.*, operateur.libelle as operateur_libelle, operation.libelle as operation_libelle')
            ->join('operateur', 'operateur.id = tarif.id_operateur')
            ->join('operation', 'operation.id = tarif.id_operation')
            ->orderBy('operateur.libelle', 'ASC')
            ->orderBy('operation.libelle', 'ASC')
            ->orderBy('montant_min', 'ASC') // On trie les tranches dans le bon ordre
            ->findAll();

        return view('tarif/index', $data);
    }

    public function new()
    {
        $data['operateurs'] = $this->operateurModel->findAll();
        $data['operations'] = $this->operationModel->findAll();
        return view('tarif/form', $data);
    }

    public function create()
    {
        $data = $this->request->getPost();
        
        // 1. Vérification logique : Min doit être inférieur à Max
        if ($data['montant_min'] >= $data['montant_max']) {
            return redirect()->back()->withInput()->with('error', 'Le montant maximum doit être strictement supérieur au montant minimum.');
        }

        // 2. Vérification métier : Chevauchement de tranches
        $chevauchement = $this->tarifModel->aChevauchement(
            $data['id_operateur'], 
            $data['id_operation'], 
            $data['montant_min'], 
            $data['montant_max']
        );

        if ($chevauchement) {
            return redirect()->back()->withInput()->with('error', 'Ces montants chevauchent une tranche tarifaire déjà existante pour cette opération !');
        }

        // 3. Insertion
        if ($this->tarifModel->insert($data)) {
            return redirect()->to('/tarif')->with('success', 'Tranche tarifaire ajoutée.');
        }

        return redirect()->back()->withInput()->with('errors', $this->tarifModel->errors());
    }

    public function edit($id)
    {
        $data['tarif'] = $this->tarifModel->find($id);
        $data['operateurs'] = $this->operateurModel->findAll();
        $data['operations'] = $this->operationModel->findAll();

        if (!$data['tarif']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('tarif/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();

        // 1. Min vs Max
        if ($data['montant_min'] >= $data['montant_max']) {
            return redirect()->back()->withInput()->with('error', 'Le montant maximum doit être strictement supérieur au montant minimum.');
        }

        // 2. Anti-chevauchement (on passe l'ID actuel pour ne pas qu'il se compare avec lui-même)
        $chevauchement = $this->tarifModel->aChevauchement(
            $data['id_operateur'], 
            $data['id_operation'], 
            $data['montant_min'], 
            $data['montant_max'],
            $id
        );

        if ($chevauchement) {
            return redirect()->back()->withInput()->with('error', 'Ces montants chevauchent une autre tranche tarifaire existante !');
        }

        // 3. Update
        if ($this->tarifModel->update($id, $data)) {
            return redirect()->to('/tarif')->with('success', 'Tranche tarifaire modifiée.');
        }

        return redirect()->back()->withInput()->with('errors', $this->tarifModel->errors());
    }

    public function delete($id)
    {
        $this->tarifModel->delete($id);
        return redirect()->to('/tarif')->with('success', 'Tranche tarifaire supprimée.');
    }
}