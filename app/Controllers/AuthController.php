<?php

namespace App\Controllers;

use App\Models\NumeroModel;

class AuthController extends BaseController
{   
    public function login()
    {
        return view('Auth/Login');
    }
    
    public function loginAuth()
    {
        $numero = $this->request->getPost('numero');
        
        if ($numero != NULL) {
            $numeroModel = new NumeroModel();
            $userdata = $numeroModel->findByNumero($numero);
            
            if ($userdata) {
                session()->set('user_id', $userdata['id']);
                session()->set('numero', $userdata['numero']);
                session()->set('id_client', $userdata['id_client']);
     
                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/Authentification/login')->with('error', 'Numéro invalide.');
            }
        }
        
        return redirect()->to('/Authentification/login')->with('error', 'Veuillez saisir un numéro');
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/Authentification/login')->with('success', 'Déconnecté avec succès');
    }
}