-- ============================================
-- Base de données Mobile Money - Version 1
-- SQLite3
-- ============================================

PRAGMA foreign_keys = ON;

-- ---------------------------------------------
-- Table Client
-- ---------------------------------------------
CREATE TABLE Client (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    nom             TEXT NOT NULL,
    date_creation   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------
-- Table Operateur
-- ---------------------------------------------
CREATE TABLE Operateur (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle         TEXT NOT NULL UNIQUE,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
);

-- ---------------------------------------------
-- Table Prefixe (1 opérateur -> plusieurs préfixes : ex 033, 037)
-- ---------------------------------------------
CREATE TABLE Prefixe (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operateur    INTEGER NOT NULL,
    libelle         TEXT NOT NULL UNIQUE,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_operateur) REFERENCES Operateur(id) ON DELETE CASCADE
);

-- ---------------------------------------------
-- Table Numero (= le compte du client chez un opérateur)
-- ---------------------------------------------
CREATE TABLE Numero (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    numero          TEXT NOT NULL UNIQUE,
    id_client       INTEGER NOT NULL,
    id_operateur    INTEGER NOT NULL,
    solde           REAL NOT NULL DEFAULT 0,
    date_creation   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_client)    REFERENCES Client(id)    ON DELETE CASCADE,
    FOREIGN KEY (id_operateur) REFERENCES Operateur(id) ON DELETE RESTRICT
);

-- ---------------------------------------------
-- Table Operation (dépôt, retrait, transfert)
-- ---------------------------------------------
CREATE TABLE Operation (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle         TEXT NOT NULL UNIQUE
);

-- ---------------------------------------------
-- Table Tarif (barème de frais par tranche de montant, modifiable)
-- ---------------------------------------------
CREATE TABLE Tarif (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operateur    INTEGER NOT NULL,
    id_operation    INTEGER NOT NULL,
    montant_min     REAL NOT NULL,
    montant_max     REAL NOT NULL,
    montant_frais   REAL NOT NULL,
    FOREIGN KEY (id_operateur) REFERENCES Operateur(id) ON DELETE CASCADE,
    FOREIGN KEY (id_operation) REFERENCES Operation(id) ON DELETE CASCADE,
    CHECK (montant_max > montant_min)
);

CREATE INDEX idx_tarif_lookup
    ON Tarif (id_operateur, id_operation, montant_min, montant_max);

-- ---------------------------------------------
-- Table Mouvement (dépôt / retrait / transfert)
-- ---------------------------------------------
CREATE TABLE Mouvement (
    id                      INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operation            INTEGER NOT NULL,
    id_numero_source        INTEGER,       -- NULL si dépôt
    id_numero_destination   INTEGER,       -- NULL si retrait
    montant                 REAL NOT NULL,
    montant_frais           REAL NOT NULL DEFAULT 0,
    id_tarif                INTEGER,       -- tarif appliqué (traçabilité)
    date_transaction        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_operation)          REFERENCES Operation(id),
    FOREIGN KEY (id_numero_source)      REFERENCES Numero(id),
    FOREIGN KEY (id_numero_destination) REFERENCES Numero(id),
    FOREIGN KEY (id_tarif)              REFERENCES Tarif(id)
);

CREATE INDEX idx_mouvement_source      ON Mouvement (id_numero_source);
CREATE INDEX idx_mouvement_destination ON Mouvement (id_numero_destination);
CREATE INDEX idx_mouvement_date        ON Mouvement (date_transaction);

-- ============================================
-- DONNÉES DE BASE
-- ============================================

-- ---------------------------------------------
-- 1. Opérations
-- ---------------------------------------------
INSERT INTO Operation (libelle) VALUES
    ('depot'),
    ('retrait'),
    ('transfert');

-- ---------------------------------------------
-- 2. Opérateurs
-- ---------------------------------------------
INSERT INTO Operateur (libelle) VALUES
    ('Orange'),
    ('Airtel'),
    ('Telma');

-- ---------------------------------------------
-- 3. Préfixes
-- ---------------------------------------------
INSERT INTO Prefixe (id_operateur, prefixe) VALUES
    -- Orange (id=1)
    (1, '032'),
    (1, '037'),
    -- Airtel (id=2)
    (2, '033'),
    -- Telma (id=3)
    (3, '034'),
    (3, '038');

-- ---------------------------------------------
-- 4. Tarifs
-- ---------------------------------------------
INSERT INTO Tarif (id_operateur, id_operation, montant_min, montant_max, montant_frais) VALUES
    -- ===== Orange (id=1) =====
    -- Dépôt (id_operation=1)
    (1, 1, 0, 1000000, 0),
    -- Retrait (id_operation=2)
    (1, 2, 0, 5000, 50),
    (1, 2, 5000, 50000, 200),
    (1, 2, 50000, 200000, 500),
    -- Transfert (id_operation=3)
    (1, 3, 0, 10000, 50),
    (1, 3, 10000, 100000, 200),
    (1, 3, 100000, 1000000, 500),

    -- ===== Airtel (id=2) =====
    -- Dépôt
    (2, 1, 0, 1000000, 0),
    -- Retrait
    (2, 2, 0, 5000, 50),
    (2, 2, 5000, 50000, 200),
    (2, 2, 50000, 200000, 500),
    -- Transfert
    (2, 3, 0, 10000, 50),
    (2, 3, 10000, 100000, 200),
    (2, 3, 100000, 1000000, 500),

    -- ===== Telma (id=3) =====
    -- Dépôt
    (3, 1, 0, 1000000, 0),
    -- Retrait
    (3, 2, 0, 5000, 50),
    (3, 2, 5000, 50000, 200),
    (3, 2, 50000, 200000, 500),
    -- Transfert
    (3, 3, 0, 10000, 50),
    (3, 3, 10000, 100000, 200),
    (3, 3, 100000, 1000000, 500);

-- ---------------------------------------------
-- 5. Clients (15 clients)
-- ---------------------------------------------
INSERT INTO Client (nom) VALUES
    ('Jean Dupont'),
    ('Marie Martin'),
    ('Paul Kouassi'),
    ('Fatou Diop'),
    ('Ali Touré'),
    ('Rakoto Jean'),
    ('Rabe Marie'),
    ('Randria Paul'),
    ('Rajoelina Fara'),
    ('Rakotomalala Tiana'),
    ('Rasoa Lanto'),
    ('Ravelo Heri'),
    ('Rakotondrabe Mamy'),
    ('Razafindrakoto Jules'),
    ('Ranaivo Mirana');

-- ---------------------------------------------
-- 6. Numéros avec soldes (15 numéros)
-- ---------------------------------------------
INSERT INTO Numero (numero, id_client, id_operateur, solde) VALUES
    -- Orange (id_operateur=1)
    ('0321234567', 1, 1, 150000),
    ('0322345678', 2, 1, 25000),
    ('0323456789', 3, 1, 75000),
    ('0324567890', 4, 1, 120000),
    ('0321234567', 5, 1, 45000),
    ('0322345678', 6, 1, 180000),
    ('0321234567', 7, 1, 60000),
    
    -- Airtel (id_operateur=2)
    ('0331234567', 8, 2, 50000),
    ('0332345678', 9, 2, 95000),
    ('0331234567', 10, 2, 30000),
    ('0332345678', 11, 2, 200000),
    
    -- Telma (id_operateur=3)
    ('0341234567', 12, 3, 125000),
    ('0342345678', 13, 3, 55000),
    ('0381234567', 14, 3, 85000),
    ('0382345678', 15, 3, 32000);

-- ---------------------------------------------
-- 7. Mouvements (Historique des transactions)
-- ---------------------------------------------
INSERT INTO Mouvement (id_operation, id_numero_source, id_numero_destination, montant, montant_frais, id_tarif, date_transaction) VALUES
    -- Dépôts (id_operation=1)
    (1, NULL, 1, 50000, 0, NULL, datetime('now', '-5 days')),
    (1, NULL, 2, 10000, 0, NULL, datetime('now', '-4 days')),
    (1, NULL, 3, 25000, 0, NULL, datetime('now', '-3 days')),
    (1, NULL, 8, 15000, 0, NULL, datetime('now', '-2 days')),
    
    -- Retraits (id_operation=2)
    (2, 1, NULL, 20000, 200, 3, datetime('now', '-4 days')),
    (2, 3, NULL, 5000, 50, 2, datetime('now', '-3 days')),
    (2, 5, NULL, 10000, 200, 3, datetime('now', '-2 days')),
    (2, 12, NULL, 15000, 200, 19, datetime('now', '-1 day')),
    
    -- Transferts (id_operation=3)
    (3, 2, 5, 3000, 50, 7, datetime('now', '-3 days')),
    (3, 4, 3, 25000, 200, 8, datetime('now', '-2 days')),
    (3, 6, 8, 10000, 200, 8, datetime('now', '-1 day')),
    (3, 10, 14, 5000, 50, 14, datetime('now', '-6 hours'));



CREATE TABLE Commission (
    id   INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operateur_depart int,
    id_operateur_arrivee int,
    montant_min FLOAT ,
    FOREIGN KEY (id_operateur_depart) REFERENCES Operateur(id),
    FOREIGN KEY (id_operateur_arrivee) REFERENCES Operateur(id)
);