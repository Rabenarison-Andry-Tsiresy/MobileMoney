<?php
namespace App\Controllers;
use App\Models\AdminModel;

class AdminController extends BaseController
{
    public function login()
    {
        // Si déjà connecté, on le renvoie direct vers le tableau de bord
        if (session()->get('is_admin_logged_in')) {
            return redirect()->to('/operateur');
        }
        return view('admin/login');
    }

    public function authenticate()
    {
        $model = new AdminModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $model->where('username', $username)->first();

        if ($admin && password_verify($password, $admin['password'])) {
            // Succès : on crée la session
            session()->set([
                'admin_id' => $admin['id'],
                'admin_user' => $admin['username'],
                'is_admin_logged_in' => true
            ]);
            return redirect()->to('/operateur');
        }

        // Echec : retour à la page login avec erreur
        return redirect()->back()->with('error', 'Identifiants incorrects.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }
}