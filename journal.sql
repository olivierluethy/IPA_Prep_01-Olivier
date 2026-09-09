DROP DATABASE IF EXISTS journal;
CREATE DATABASE journal;
USE journal;

--
-- Tabelle 'Benutzer'
--

CREATE TABLE benutzer (
  `benutzerId` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(100) NOT NULL DEFAULT '',
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `gender` varchar(50) NOT NULL DEFAULT '',
  `full_name` varchar(100) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `verifiedEmail` int(11) NOT NULL DEFAULT 0,
  `token` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '', /* bcrypt-Hash für die normale E-Mail/Passwort-Anmeldung (leer bei Google-Konten) */
  `role` TINYINT(2) NOT NULL, /* Lernender: 0, Fachkraft: 1, Administrator: 2 */
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

--
-- Tabelle 'Journaleintrag'
--

CREATE TABLE journal (
  journalId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  text TEXT NOT NULL,
  status TINYINT(1), /* Offen: 0, Freigegeben: 1 */
  datum DATETIME DEFAULT CURRENT_TIMESTAMP, /* Date of creation */
  released DATETIME,
  fk_benutzerId INT NOT NULL,
  FOREIGN KEY (fk_benutzerId) REFERENCES benutzer(benutzerId)
);

--
-- Tabelle 'Wocheneintrag'
--

CREATE TABLE wochenreport (
  wochenreportId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  kalenderwoche INT NOT NULL,
  erledigte_arbeiten TEXT NOT NULL,
  laufende_arbeiten TEXT NOT NULL,
  reflexion TEXT NOT NULL,
  aufgetretene_probleme TEXT NOT NULL,
  status TINYINT(1), /* Offen: 0, Freigegeben: 1 */
  datum DATETIME DEFAULT CURRENT_TIMESTAMP,
  fk_benutzerId INT NOT NULL,
  FOREIGN KEY (fk_benutzerId) REFERENCES benutzer(benutzerId)
);

--
-- Tabelle 'Themen'
--

CREATE TABLE thema (
  themaId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  thema VARCHAR(255) NOT NULL,
  fk_benutzerId INT NOT NULL,
  FOREIGN KEY (fk_benutzerId) REFERENCES benutzer(benutzerId)
);

--
-- Tabelle 'Ausgewählte Themen'
--

CREATE TABLE ausgewaehlte_themen (
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	fk_themaId INT NULL,
  fk_journalId INT NULL,
	FOREIGN KEY (fk_themaId) REFERENCES thema(themaId),
  FOREIGN KEY (fk_journalId) REFERENCES journal(journalId)
);

/* Lernender */
-- 'olivier.luethy@kauz.ch'

/* Fachkraft */
-- 'aurel.wicki@kauz.ch'

/* Administrator */
-- 'janik.lüthi@kauz.ch'