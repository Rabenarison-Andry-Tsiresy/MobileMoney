<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class RapportController extends BaseController
{
    /**
     * Rapport 1 : Situation des gains (Somme des frais)
     *
     * Deux sources de gains existent :
     *  - les frais de barème (dépôt/retrait/transfert même opérateur), liés
     *    à un id_tarif -> l'opérateur est tarif.id_operateur
     *  - les commissions sur transfert inter-opérateurs, qui n'ont PAS de
     *    id_tarif (voir TransactionController::transfertSimple) -> l'opérateur
     *    "gagnant" est celui de l'émetteur (numero source)
     * L'ancienne requête faisait un INNER JOIN sur tarif, ce qui excluait
     * complètement les commissions inter-opérateurs du rapport.
     */
    public function gains()
    {
        $db = \Config\Database::connect();

        // 1) Gains issus des tarifs (dépôt / retrait / transfert même opérateur)
        $builderTarif = $db->table('mouvement');
        $builderTarif->select('
            operateur.libelle as operateur_nom,
            operation.libelle as operation_nom,
            SUM(mouvement.montant_frais) as total_gains
        ');
        $builderTarif->join('tarif', 'tarif.id = mouvement.id_tarif', 'inner');
        $builderTarif->join('operateur', 'operateur.id = tarif.id_operateur', 'inner');
        $builderTarif->join('operation', 'operation.id = mouvement.id_operation', 'inner');
        $builderTarif->groupBy('operateur.id');
        $builderTarif->groupBy('operation.id');
        $gainsTarif = $builderTarif->get()->getResultArray();

        // 2) Gains issus des commissions sur transfert inter-opérateurs
        //    (mouvement.id_tarif est NULL dans ce cas)
        $builderCommission = $db->table('mouvement');
        $builderCommission->select('
            operateur.libelle as operateur_nom,
            "Commission transfert inter-opérateurs" as operation_nom,
            SUM(mouvement.montant_frais) as total_gains
        ');
        $builderCommission->join('numero', 'numero.id = mouvement.id_numero_source', 'inner');
        $builderCommission->join('operateur', 'operateur.id = numero.id_operateur', 'inner');
        $builderCommission->where('mouvement.id_operation', 3); // transfert
        $builderCommission->where('mouvement.id_tarif', null);
        $builderCommission->where('mouvement.montant_frais >', 0);
        $builderCommission->groupBy('operateur.id');
        $gainsCommission = $builderCommission->get()->getResultArray();

        $data['gains'] = array_merge($gainsTarif, $gainsCommission);

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
            // On regroupe les deux conditions ensemble pour ne pas casser
            // d'autres filtres qui pourraient être ajoutés plus tard
            // (numéro OU nom du client contient le mot recherché)
            $builder->groupStart();
            $builder->like('numero.numero', $recherche);
            $builder->orLike('client.nom', $recherche);
            $builder->groupEnd();
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
