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
    date_creation   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------
-- Table Prefixe (1 opérateur -> plusieurs préfixes : ex 033, 037)
-- ---------------------------------------------
CREATE TABLE Prefixe (
    id              INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operateur    INTEGER NOT NULL,
    prefixe         TEXT NOT NULL UNIQUE,
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

-- ---------------------------------------------
-- Données de base
-- ---------------------------------------------
INSERT INTO Operation (libelle) VALUES
    ('depot'),
    ('retrait'),
    ('transfert');