# MobileMoney

Examen S4 mobile money

# TODO - Version 1

## 0. Setup commun @Both

- [ ] Config `.env` (DB, base_url)
- [ ] `app/Config/Database.php` vérifié
- [ ] `app/Config/Routes.php` : structure de base + groupes de routes
- [ ] `BaseController` commun (helpers, réponses JSON si API)
- [ ] Migrations générées pour **toutes** les tables (une par table, dans l'ordre des FK) :
      - [ ] `CreateClient`
      - [ ] `CreateOperateur`
      - [ ] `CreatePrefixe`
      - [ ] `CreateNumero`
      - [ ] `CreateOperation`
      - [ ] `CreateTarif`
      - [ ] `CreateMouvement`
- [ ] `php spark migrate` testé sur environnement local
- [ ] Vérifier index / contraintes (FK, CHECK) dans les migrations (`$this->forge->addForeignKey(...)`)

---

## 1. Module Opérateur / Préfixe / Opération / Tarif @Manoina

### Database
- [ ] Migration `Operateur` (id, libelle)
- [ ] Migration `Prefixe` (id, id_operateur FK, prefixe UNIQUE)
- [ ] Migration `Operation` (id, libelle)
- [ ] Migration `Tarif` (id, id_operateur FK, id_operation FK, montant_min, montant_max, montant_frais)
- [ ] Seeder `OperationSeeder` (depot, retrait, transfert)
- [ ] Seeder `OperateurSeeder` + `PrefixeSeeder` (opérateurs + préfixes de test)
- [ ] Seeder `TarifSeeder` (barème de test par tranche)

### Modèle
- [ ] `OperateurModel` (CRUD standard)
- [ ] `PrefixeModel` (méthode `findOperateurByPrefixe($prefixe)`)
- [ ] `OperationModel` (CRUD standard, lecture seule côté front normalement)
- [ ] `TarifModel`
      - [ ] `getTranchesByOperateurOperation($idOperateur, $idOperation)`
      - [ ] `findTarifApplicable($idOperateur, $idOperation, $montant)`
      - [ ] Validation métier : détection de chevauchement de tranches avant insert/update

### Controller
- [ ] `OperateurController` : `index`, `new`, `create`, `edit`, `update`, `delete`
- [ ] `PrefixeController` : CRUD (lié à un opérateur)
- [ ] `OperationController` : `index` (lecture), gestion libellés si besoin
- [ ] `TarifController` :
      - [ ] `index` (liste des tranches par opérateur/opération)
      - [ ] `create` / `store` (avec validation anti-chevauchement)
      - [ ] `edit` / `update`
      - [ ] `delete`
- [ ] `RapportController` :
      - [ ] `gains()` → somme `montant_frais` groupée par opérateur/opération/période
      - [ ] `comptesClients()` → liste `Numero` + solde, recherche

### Views
- [ ] `operateur/index.php`, `operateur/form.php`
- [ ] `prefixe/index.php`, `prefixe/form.php`
- [ ] `operation/index.php`
- [ ] `tarif/index.php` (tableau des tranches), `tarif/form.php`
- [ ] `rapport/gains.php`
- [ ] `rapport/comptes.php`

### Routes
- [ ] `resource('operateur', ['controller' => 'OperateurController'])`
- [ ] `resource('prefixe', ['controller' => 'PrefixeController'])`
- [ ] `resource('operation', ['controller' => 'OperationController'])`
- [ ] `resource('tarif', ['controller' => 'TarifController'])`
- [ ] `GET rapport/gains` → `RapportController::gains`
- [ ] `GET rapport/comptes` → `RapportController::comptesClients`

---

## 2. Module Client / Numero / Mouvement @Tsiresy

### Database
- [ ] Migration `Client` (id, nom, created_at)
- [ ] Migration `Numero` (id, id_client FK, id_operateur FK, numero UNIQUE, solde, created_at)
- [ ] Migration `Mouvement` (id, id_operation FK, id_numero_source FK nullable, id_numero_destination FK nullable, montant, montant_frais, id_tarif FK, date_transaction)

### Modèle
- [ ] `ClientModel` (CRUD standard)
- [ ] `NumeroModel`
      - [ ] `findByNumero($numero)`
      - [ ] `creerAvecClient($numero, $idOperateur, $nomClient)` (login auto)
      - [ ] `updateSolde($idNumero, $nouveauSolde)`
- [ ] `MouvementModel`
      - [ ] `getHistorique($idNumero)`
      - [ ] `create(...)`

### Controller
- [ ] `AuthController`
      - [ ] `login()` : saisie numéro
      - [ ] détection opérateur via `PrefixeModel`
      - [ ] création automatique Client + Numero si première connexion
      - [ ] gestion session
- [ ] `CompteController`
      - [ ] `solde()` : afficher `Numero.solde`
      - [ ] `historique()` : liste des `Mouvement` filtrés par numéro
- [ ] `TransactionController`
      - [ ] `depot()` (form + traitement)
      - [ ] `retrait()` (form + traitement, vérifier solde)
      - [ ] `transfert()` (form + traitement, vérifier solde)
            - [ ] Autoriser la sélection d'un numéro destination **quel que soit son opérateur** (inter-opérateurs autorisé)
            - [ ] Déterminer le tarif applicable en fonction de l'opérateur **source** (à confirmer, cf. section Décisions)

### Views
- [ ] `auth/login.php`
- [ ] `compte/solde.php`
- [ ] `compte/historique.php`
- [ ] `transaction/depot.php`
- [ ] `transaction/retrait.php`
- [ ] `transaction/transfert.php` (champ numéro destination libre, sans restriction d'opérateur)

### Routes
- [ ] `GET|POST login` → `AuthController::login`
- [ ] `GET compte/solde` → `CompteController::solde`
- [ ] `GET compte/historique` → `CompteController::historique`
- [ ] `GET|POST transaction/depot` → `TransactionController::depot`
- [ ] `GET|POST transaction/retrait` → `TransactionController::retrait`
- [ ] `GET|POST transaction/transfert` → `TransactionController::transfert`
- [ ] Filtre/middleware `auth` sur toutes les routes `compte/*` et `transaction/*`

---

## 3. Logique métier / Services @Both

*(indépendant des controllers, à mettre dans `app/Libraries/` ou `app/Services/`)*

- [ ] `TarifService::calculerFrais($idOperateur, $idOperation, $montant)` → lookup `TarifModel`
- [ ] `MouvementService::executer($idOperation, $source, $destination, $montant)`
      - [ ] Gérer le cas transfert **inter-opérateurs** (source et destination peuvent avoir un `id_operateur` différent)
      - [ ] Calcul du frais via `TarifService` (basé sur l'opérateur source, à valider)
      - [ ] Transaction DB (`$db->transStart()` / `transComplete()`)
      - [ ] Mise à jour atomique du/des solde(s) — débit sur `Numero` source, crédit sur `Numero` destination (peu importe l'opérateur)
      - [ ] Insertion dans `Mouvement`
- [ ] Exceptions custom :
      - [ ] `SoldeInsuffisantException`
      - [ ] `TarifIntrouvableException`
      - [ ] `NumeroInconnuException`
- [ ] Tests unitaires (`tests/unit/`) :
      - [ ] `TarifServiceTest` (calcul par tranche, bornes min/max)
      - [ ] `MouvementServiceTest` :
            - [ ] dépôt
            - [ ] retrait
            - [ ] transfert même opérateur
            - [ ] transfert inter-opérateurs
            - [ ] solde insuffisant

---

## Décisions prises

- [x] **Un transfert peut se faire entre deux opérateurs différents** (pas de restriction "même opérateur"). Impact :
      - `TransactionController::transfert` : suppression de la vérification d'opérateur identique
      - `MouvementService::executer` : gérer explicitement le cas source/destination sur des opérateurs différents
      - `TarifService` : clarifier sur quel opérateur se base le calcul du frais (source, destination, ou règle spécifique) → **à préciser techniquement avant implémentation**

## Questions encore à trancher avant dev @Both

- [ ] Le dépôt "vient d'où" ? (agent, guichet, source externe) — à modéliser en V2 si besoin
- [ ] Faut-il un statut sur `Mouvement` (réussi/échoué) ou tout est synchrone en V1 ?
- [ ] Pour un transfert inter-opérateurs, le tarif appliqué dépend de quel opérateur (source, destination, ou table de correspondance dédiée) ?