<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PrefixeModel;
use App\Models\OperateurModel;

class PrefixeController extends BaseController
{
    protected $prefixeModel;
    protected $operateurModel;

    public function __construct()
    {
        $this->prefixeModel = new PrefixeModel();
        $this->operateurModel = new OperateurModel();
    }

    public function index()
    {
        // On récupère les préfixes avec le nom de l'opérateur (Jointure basique CI4)
        $data['prefixes'] = $this->prefixeModel
            ->select('prefixe.*, operateur.libelle as operateur_libelle')
            ->join('operateur', 'operateur.id = prefixe.id_operateur')
            ->findAll();

        return view('prefixe/index', $data);
    }

    public function new()
    {
        // On envoie la liste des opérateurs pour le menu déroulant (<select>)
        $data['operateurs'] = $this->operateurModel->findAll();
        return view('prefixe/form', $data);
    }

    public function create()
    {
        if ($this->prefixeModel->insert($this->request->getPost())) {
            return redirect()->to('/prefixe')->with('success', 'Préfixe ajouté.');
        }
        return redirect()->back()->withInput()->with('errors', $this->prefixeModel->errors());
    }

    public function edit($id)
    {
        $data['prefixe'] = $this->prefixeModel->find($id);
        $data['operateurs'] = $this->operateurModel->findAll();

        if (!$data['prefixe']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('prefixe/form', $data);
    }

    public function update($id)
    {
        if ($this->prefixeModel->update($id, $this->request->getPost())) {
            return redirect()->to('/prefixe')->with('success', 'Préfixe modifié.');
        }
        return redirect()->back()->withInput()->with('errors', $this->prefixeModel->errors());
    }

    public function delete($id)
    {
        $this->prefixeModel->delete($id);
        return redirect()->to('/prefixe')->with('success', 'Préfixe supprimé.');
    }
}