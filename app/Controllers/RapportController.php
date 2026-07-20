<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class RapportController extends BaseController
{
    /**
     * Rapport 1 : Situation des gains (Somme des frais)
     */
    public function gains()
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('mouvement');
        
        // On sélectionne le nom de l'opérateur, de l'opération, et la somme des frais
        $builder->select('
            operateur.libelle as operateur_nom, 
            operation.libelle as operation_nom, 
            SUM(mouvement.montant_frais) as total_gains
        ');
        
        // Jointures pour retrouver à qui appartient le mouvement
        $builder->join('tarif', 'tarif.id = mouvement.id_tarif', 'inner'); 
        $builder->join('operateur', 'operateur.id = tarif.id_operateur', 'inner');
        $builder->join('operation', 'operation.id = mouvement.id_operation', 'inner');
        
        // On regroupe les calculs par opérateur et par type d'opération
        $builder->groupBy('operateur.id');
        $builder->groupBy('operation.id');
        
        // Exécution de la requête
        $data['gains'] = $builder->get()->getResultArray();

        return view('rapport/gains', $data);
    }

    /**
     * Rapport 2 : Liste des comptes clients avec recherche
     */
    public function comptesClients()
    {
        $db = \Config\Database::connect();
        
        // On récupère le mot tapé dans la barre de recherche (ex: ?search=Tsiresy ou ?search=034)
        $recherche = $this->request->getGet('search');

        $builder = $db->table('numero');
        
        // On sélectionne ce qu'on veut afficher
        $builder->select('
            client.nom as client_nom, 
            numero.numero, 
            numero.solde, 
            operateur.libelle as operateur_nom, 
            numero.date_creation
        ');
        
        $builder->join('client', 'client.id = numero.id_client', 'inner');
        $builder->join('operateur', 'operateur.id = numero.id_operateur', 'inner');

        // Si l'utilisateur a fait une recherche
        if (!empty($recherche)) {
            // On cherche si le numéro ou le nom contient le mot tapé
            $builder->like('numero.numero', $recherche);
            $builder->orLike('client.nom', $recherche);
        }

        // On trie par date (les comptes les plus récents en premier)
        $builder->orderBy('numero.date_creation', 'DESC');

        // Exécution de la requête
        $data['comptes'] = $builder->get()->getResultArray();
        
        // On renvoie aussi le mot cherché pour le remettre dans l'input HTML de la vue
        $data['recherche'] = $recherche; 

        return view('rapport/compte', $data);
    }
}