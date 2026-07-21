<?php

namespace App\Controllers;

use App\Models\NumeroModel;
use App\Models\ClientModel;
use App\Models\PrefixeModel;

class AuthController extends BaseController
{
    public function login()
    {
        return view('Auth/Login');
    }

    public function loginAuth()
    {
        $numero = trim((string) $this->request->getPost('numero'));

        if ($numero === '') {
            return redirect()->to('/Authentification/login')->with('error', 'Veuillez saisir un numéro');
        }

        $numeroModel = new NumeroModel();
        $userdata = $numeroModel->findByNumero($numero);

        // Cas 1 : le numéro existe déjà -> connexion classique
        if ($userdata) {
            session()->set('user_id', $userdata['id']);
            session()->set('numero', $userdata['numero']);
            session()->set('id_client', $userdata['id_client']);

            return redirect()->to('/dashboard');
        }

        // Cas 2 : le numéro est inconnu -> "login automatique", pas d'inscription
        // préalable. On déduit l'opérateur à partir du préfixe et on crée le
        // compte à la volée.
        $prefixe = substr($numero, 0, 3);
        $prefixeModel = new PrefixeModel();
        $operateur = $prefixeModel->findOperateurByPrefixe($prefixe);

        if (!$operateur) {
            return redirect()->to('/Authentification/login')
                ->with('error', 'Numéro invalide : préfixe opérateur non reconnu.');
        }

        $clientModel = new ClientModel();
        $idClient = $clientModel->insert(['nom' => 'Client ' . $numero]);

        if (!$idClient) {
            return redirect()->to('/Authentification/login')
                ->with('error', 'Impossible de créer le compte, veuillez réessayer.');
        }

        $idNumero = $numeroModel->creerAvecClient($numero, $idClient, $operateur['id']);

        if (!$idNumero) {
            return redirect()->to('/Authentification/login')
                ->with('error', 'Impossible de créer le compte, veuillez réessayer.');
        }

        session()->set('user_id', $idNumero);
        session()->set('numero', $numero);
        session()->set('id_client', $idClient);

        return redirect()->to('/dashboard')->with('success', 'Bienvenue ! Votre compte a été créé automatiquement.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/Authentification/login')->with('success', 'Déconnecté avec succès');
    }
}
