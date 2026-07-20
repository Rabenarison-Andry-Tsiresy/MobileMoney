<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OperateurModel;

class OperateurController extends BaseController
{
    protected $operateurModel;

    public function __construct()
    {
        $this->operateurModel = new OperateurModel();
    }

    public function index()
    {
        $data['operateurs'] = $this->operateurModel->findAll();
        return view('operateur/index', $data);
    }

    public function new()
    {
        return view('operateur/form');
    }

    public function create()
    {
        $data = $this->request->getPost();

        if ($this->operateurModel->insert($data)) {
            return redirect()->to('/operateur')->with('success', 'Opérateur créé avec succès.');
        }

        return redirect()->back()->withInput()->with('errors', $this->operateurModel->errors());
    }

    public function edit($id)
    {
        $data['operateur'] = $this->operateurModel->find($id);
        if (!$data['operateur']) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('operateur/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();

        if ($this->operateurModel->update($id, $data)) {
            return redirect()->to('/operateur')->with('success', 'Opérateur modifié avec succès.');
        }

        return redirect()->back()->withInput()->with('errors', $this->operateurModel->errors());
    }

    public function delete($id)
    {
        $this->operateurModel->delete($id);
        return redirect()->to('/operateur')->with('success', 'Opérateur supprimé.');
    }
}