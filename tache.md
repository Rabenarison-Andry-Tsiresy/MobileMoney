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
- [ ]  Config `.env` (DB, base_url)
- [ ]  `app/Config/Database.php` vérifié
- [ ]  `app/Config/Routes.php` : structure de base + groupes de routes
- [ ]  `BaseController` commun (helpers, réponses JSON si API)
- [ ]  Migrations générées pour **toutes** les tables (une par table, dans l'ordre des FK) :
  - [ ]  `CreateClient`
  - [ ]  `CreateOperateur`
  - [ ]  `CreatePrefixe`
  - [ ]  `CreateNumero`
  - [ ]  `CreateOperation`
  - [ ]  `CreateTarif`
  - [ ]  `CreateMouvement`
- [ ]  `php spark migrate` testé sur environnement local
- [ ]  Vérifier index / contraintes (FK, CHECK) dans les migrations (`$this->forge->addForeignKey(...)`)

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

- [x]  `operateur/index.php`, `operateur/form.php`
- [x]  `prefixe/index.php`, `prefixe/form.php`
- [x]  `operation/index.php`
- [x]  `tarif/index.php` (tableau des tranches), `tarif/form.php`
- [x]  `rapport/gains.php`
- [x]  `rapport/comptes.php`

### Routes

- [ ]  `resource('operateur', ['controller' => 'OperateurController'])`
- [ ]  `resource('prefixe', ['controller' => 'PrefixeController'])`
- [ ]  `resource('operation', ['controller' => 'OperationController'])`
- [ ]  `resource('tarif', ['controller' => 'TarifController'])`
- [ ]  `GET rapport/gains` → `RapportController::gains`
- [ ]  `GET rapport/comptes` → `RapportController::comptesClients`

---
**ESPACE ADMIN & CLIENT**
### Sécurité & Authentification (Nouveau)
- [x] Créer la migration `Admin` (id, username, password)
- [x] Créer `AdminSeeder` (pour insérer un admin par défaut ex: admin / admin123)
- [x] Créer `AdminModel`
- [x] Créer `AdminController` (login, authentification, logout)
- [x] Créer la vue `admin/login.php` (sans navbar)
- [x] Créer un Filtre `AdminFilter` pour protéger les routes.
- [x] Appliquer le Filtre dans `app/Config/Filters.php` sur toutes les routes de ton module.

### UI & Navigation
- [ ] Créer `app/Views/layouts/admin.php` (Template Bootstrap 5 Thème sombre + Navbar)
- [ ] Nettoyer toutes les vues (Operateur, Prefixe, Tarif, Rapport) pour utiliser `<?= $this->extend('layouts/admin') ?>`

### Routes (Checklist finale)
- [x] Routes Opérateur
- [x] Routes Préfixe
- [x] Routes Opération
- [x] Routes Tarif
- [x] Routes Rapports
- [x] Routes Admin (GET `/admin/login`, POST `/admin/auth`, GET `/admin/logout`)


## 2. Module Client / Numero / Mouvement @Tsiresy

### Database

- [X]  Migration `Client` (id, nom, created_at)
- [X]  Migration `Numero` (id, id_client FK, id_operateur FK, numero UNIQUE, solde, created_at)
- [X]  Migration `Mouvement` (id, id_operation FK, id_numero_source FK nullable, id_numero_destination FK nullable, montant, montant_frais, id_tarif FK, date_transaction)

### Modèle

- [X]  `ClientModel` (CRUD standard)
- [X]  `NumeroModel`
  - [X]  `findByNumero($numero)`
  - [X]  `updateSolde($idNumero, $nouveauSolde)`
- [ ]  `MouvementModel`
  - [ ]  `getHistorique($idNumero)`
  - [ ]  `create(...)`

### Controller

- [X]  `AuthController`
  - [X]  `login()` : saisie numéro
- [ ]  `CompteController`
  - [X]  `solde()` : afficher `Numero.solde`
  - [ ]  `historique()` : liste des `Mouvement` filtrés par numéro
- [ ]  `TransactionController`
  - [ ]  `depot()` (form + traitement)
  - [ ]  `retrait()` (form + traitement, vérifier solde)
  - [ ]  `transfert()` (form + traitement, vérifier solde)

### Views

- [X]  `auth/login.php`
- [X]  `compte/solde.php`
- [ ]  `compte/historique.php`
- [ ]  `transaction/depot.php`
- [ ]  `transaction/retrait.php`
- [ ]  `transaction/transfert.php` (champ numéro destination libre, sans restriction d'opérateur)

### Routes

- [X]  `GET|POST login` → `AuthController::login`
- [X]  `GET compte/solde` → `CompteController::solde`
- [ ]  `GET compte/historique` → `CompteController::historique`
- [ ]  `GET|POST transaction/depot` → `TransactionController::depot`
- [ ]  `GET|POST transaction/retrait` → `TransactionController::retrait`
- [ ]  `GET|POST transaction/transfert` → `TransactionController::transfert`
- [ ]  Filtre/middleware `auth` sur toutes les routes `compte/*` et `transaction/*`

---

## 3. Logique métier / Services @Both

*(indépendant des controllers, à mettre dans `app/Libraries/` ou `app/Services/`)*

- [ ]  `TarifService::calculerFrais($idOperateur, $idOperation, $montant)` → lookup `TarifModel`
- [ ]  `MouvementService::executer($idOperation, $source, $destination, $montant)`
  - [ ]  Gérer le cas transfert **inter-opérateurs** (source et destination peuvent avoir un `id_operateur` différent)
  - [ ]  Calcul du frais via `TarifService` (basé sur l'opérateur source, à valider)
  - [ ]  Transaction DB (`$db->transStart()` / `transComplete()`)
  - [ ]  Mise à jour atomique du/des solde(s) — débit sur `Numero` source, crédit sur `Numero` destination (peu importe l'opérateur)
  - [ ]  Insertion dans `Mouvement`
- [ ]  Exceptions custom :
  - [ ]  `SoldeInsuffisantException`
  - [ ]  `TarifIntrouvableException`
  - [ ]  `NumeroInconnuException`
- [ ]  Tests unitaires (`tests/unit/`) :
  - [ ]  `TarifServiceTest` (calcul par tranche, bornes min/max)
  - [ ]  `MouvementServiceTest` :
  - [ ]  dépôt
  - [ ]  retrait
  - [ ]  transfert même opérateur
  - [ ]  transfert inter-opérateurs
  - [ ]  solde insuffisant

---

## Décisions prises

- [X]  **Un transfert peut se faire entre deux opérateurs différents** (pas de restriction "même opérateur"). Impact :
  - `TransactionController::transfert` : suppression de la vérification d'opérateur identique
  - `MouvementService::executer` : gérer explicitement le cas source/destination sur des opérateurs différents
  - `TarifService` : clarifier sur quel opérateur se base le calcul du frais (source, destination, ou règle spécifique) → **à préciser techniquement avant implémentation**

## Questions encore à trancher avant dev @Both

- [ ]  Le dépôt "vient d'où" ? (agent, guichet, source externe) — à modéliser en V2 si besoin
- [ ]  Faut-il un statut sur `Mouvement` (réussi/échoué) ou tout est synchrone en V1 ?
- [ ]  Pour un transfert inter-opérateurs, le tarif appliqué dépend de quel opérateur (source, destination, ou table de correspondance dédiée) ?
