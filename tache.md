# MobileMoney

Examen S4 mobile money

# TODO - Version 1

## 0. Setup commun @Both

- [x] Config `.env` (DB, base_url)
- [x] `app/Config/Database.php` vérifié
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
- [x] Migration `Operateur` (id, libelle) (@started)
- [x] Migration `Prefixe` (id, id_operateur FK, prefixe UNIQUE)
- [x] Migration `Operation` (id, libelle)
- [x] Migration `Tarif` (id, id_operateur FK, id_operation FK, montant_min, montant_max, montant_frais)
- [x] Seeder `OperationSeeder` (depot, retrait, transfert) @IA
- [x] Seeder `OperateurSeeder` + `PrefixeSeeder` (opérateurs + préfixes de test) @IA
- [x] Seeder `TarifSeeder` (barème de test par tranche) @IA

### Modèle
- [x] `OperateurModel` (CRUD standard)(@Started)
- [x] `PrefixeModel` (méthode `findOperateurByPrefixe($prefixe)`)
- [x] `OperationModel` (CRUD standard, lecture seule côté front normalement)
- [x] `TarifModel`
      - [x] `getTranchesByOperateurOperation($idOperateur, $idOperation)`
      - [x] `findTarifApplicable($idOperateur, $idOperation, $montant)`
      - [x] Validation métier : détection de chevauchement de tranches avant insert/update

### Controller
- [x] `OperateurController` : `index`, `new`, `create`, `edit`, `update`, `delete`
- [x] `PrefixeController` : CRUD (lié à un opérateur)
- [x] `OperationController` : `index` (lecture), gestion libellés si besoin
- [x] `TarifController` :
      - [x] `index` (liste des tranches par opérateur/opération)
      - [x] `create` / `store` (avec validation anti-chevauchement)
      - [x] `edit` / `update`
      - [x] `delete`
- [x] `RapportController` :
      - [x] `gains()` → somme `montant_frais` groupée par opérateur/opération/période
      - [x] `comptesClients()` → liste `Numero` + solde, recherche

### Views
- [x] `operateur/index.php`, `operateur/form.php`
- [x] `prefixe/index.php`, `prefixe/form.php`
- [x] `operation/index.php`
- [x] `tarif/index.php` (tableau des tranches), `tarif/form.php`
- [x] `rapport/gains.php`
- [x] `rapport/comptes.php`

### Routes
- [ ] `resource('operateur', ['controller' => 'OperateurController'])`
- [ ] `resource('prefixe', ['controller' => 'PrefixeController'])`
- [ ] `resource('operation', ['controller' => 'OperationController'])`
- [ ] `resource('tarif', ['controller' => 'TarifController'])`
- [ ] `GET rapport/gains` → `RapportController::gains`
- [ ] `GET rapport/comptes` → `RapportController::comptesClients`
[ ] Routes Admin (GET `/admin/login`, POST `/admin/auth`, GET `/admin/logout`)

### Sécurité & Authentification (Nouveau)
- [ ] Créer la migration `Admin` (id, username, password)
- [ ] Créer `AdminSeeder` (pour insérer un admin par défaut ex: admin / admin123)
- [ ] Créer `AdminModel`
- [ ] Créer `AdminController` (login, authentification, logout)
- [ ] Créer la vue `admin/login.php` (sans navbar)
- [ ] Créer un Filtre `AdminFilter` pour protéger les routes.
- [ ] Appliquer le Filtre dans `app/Config/Filters.php` sur toutes les routes de ton module.


### UI & Navigation (Nouveau)
- [ ] Créer `app/Views/layouts/admin.php` (Template Bootstrap 5 Thème sombre + Navbar)
- [ ] Nettoyer toutes les vues (Operateur, Prefixe, Tarif, Rapport) pour utiliser `<?= $this->extend('layouts/admin') ?>`
---

## 2. Module Client / Numero / Mouvement @Tsiresy

### Database
- [x] Migration `Client` (id, nom, created_at)
- [x] Migration `Numero` (id, id_client FK, id_operateur FK, numero UNIQUE, solde, created_at)
- [x] Migration `Mouvement` (id, id_operation FK, id_numero_source FK nullable, id_numero_destination FK nullable, montant, montant_frais, id_tarif FK, date_transaction)

### Modèle
- [x] `ClientModel` (CRUD standard)
- [x] `NumeroModel`
      - [x] `findByNumero($numero)`
      - [x] `updateSolde($idNumero, $nouveauSolde)`
- [x] `MouvementModel`
      - [x] `getHistorique($idNumero)`
      - [x] `create(...)`

### Controller
- [x] `AuthController`
      - [x] `login()` : saisie numéro
      - [x] gestion session
- [x] `CompteController`
      - [x] `solde()` : afficher `Numero.solde`
      - [x] `historique()` : liste des -  `Mouvement` filtrés par numéro
- [x] `TransactionController`
      - [x] `depot()` (form + traitement)
      - [x] `retrait()` (form + traitement, vérifier solde)
      - [x] `transfert()` (form + traitement, vérifier solde)
            - [x] Autoriser la sélection d'un numéro destination **quel que soit son opérateur** (inter-opérateurs autorisé)
            - [x] Déterminer le tarif applicable en fonction de l'opérateur **source** (à confirmer, cf. section Décisions)

### Views
- [x] `auth/login.php`
- [x] `compte/solde.php`
- [x] `compte/historique.php`
- [x] `transaction/depot.php`
- [x] `transaction/retrait.php`
- [x] `transaction/transfert.php` (champ numéro destination libre, sans restriction d'opérateur)

### Routes
- [x] `GET|POST login` → `AuthController::login`
- [x] `GET compte/solde` → `CompteController::solde`
- [x] `GET compte/historique` → `CompteController::historique`
- [x] `GET|POST transaction/depot` → `TransactionController::depot`
- [x] `GET|POST transaction/retrait` → `TransactionController::retrait`
- [x] `GET|POST transaction/transfert` → `TransactionController::transfert`
- [x] Filtre/middleware `auth` sur toutes les routes `compte/*` et `transaction/*`

---

## 3. Logique métier / Services @Both

*(indépendant des controllers, à mettre dans `app/Libraries/` ou `app/Services/`)*

- [x] `TarifService::calculerFrais($idOperateur, $idOperation, $montant)` → lookup `TarifModel`
- [x] `MouvementService::executer($idOperation, $source, $destination, $montant)`
      - [x] Gérer le cas transfert **inter-opérateurs** (source et destination peuvent avoir un `id_operateur` différent)
      - [x] Calcul du frais via `TarifService` (basé sur l'opérateur source, à valider)
      - [x] Transaction DB (`$db->transStart()` / `transComplete()`)
      - [x] Mise à jour atomique du/des solde(s) — débit sur `Numero` source, crédit sur `Numero` destination (peu importe l'opérateur)
      - [x] Insertion dans `Mouvement`
- [ ] Exceptions custom :
      - [x] `NumeroInconnuException`
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
