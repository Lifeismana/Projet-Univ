DROP TABLE IF EXISTS Quartier ;
CREATE TABLE Quartier (idQ INT(4) AUTO_INCREMENT NOT NULL,
nomQ VARCHAR(30),
codeInsee INT(9),
typeQ CHAR(1),
latitude FLOAT,
longitude FLOAT,
estDepart BOOL DEFAULT 0,
codePostal INT(5) NOT NULL ,
PRIMARY KEY (idQ)) ENGINE=InnoDB;

DROP TABLE IF EXISTS Route ;
CREATE TABLE Route (idR INT(4) AUTO_INCREMENT NOT NULL,
idQDepart INT(4),
idQArrive INT(4),
typeT VARCHAR(20),
INDEX idrI(typeT), --   TODO:WTF PQ ÇA MARCHE
PRIMARY KEY (idR)) ENGINE=InnoDB;

DROP TABLE IF EXISTS Joueuse ;
CREATE TABLE Joueuse (idJ INT(3) AUTO_INCREMENT NOT NULL,
nomJ VARCHAR(20),
emailJ VARCHAR(30),
PRIMARY KEY (idJ)) ENGINE=InnoDB;

DROP TABLE IF EXISTS Commune ;
CREATE TABLE Commune (codePostal INT(5) AUTO_INCREMENT NOT NULL,
nomC VARCHAR(12),
codeDep INT(5),
PRIMARY KEY (codePostal)) ENGINE=InnoDB;

DROP TABLE IF EXISTS TourMisterX ;
CREATE TABLE TourMisterX (idP INT(3),
numTour INT(2) NOT NULL,
idR INT(2),
idQDepart INT(4),
idQArrive INT(4),
typeT VARCHAR(13),
PRIMARY KEY (idP, numTour)) ENGINE=InnoDB;


DROP TABLE IF EXISTS Partie ;
CREATE TABLE Partie (idP INT(3) AUTO_INCREMENT NOT NULL,
dateD DATETIME,
nbDetective INT(2),
idJVictoire INT(3),
idJ INT(3),
nomC VARCHAR(20),
PRIMARY KEY (idP)) ENGINE=InnoDB;

DROP TABLE IF EXISTS Configuration ;
CREATE TABLE Configuration (nomC VARCHAR(20) NOT NULL,
dateC DATETIME,
strategie VARCHAR(20),
PRIMARY KEY (nomC)) ENGINE=InnoDB;

DROP TABLE IF EXISTS Departement ;
CREATE TABLE Departement (codeDep INT(2) AUTO_INCREMENT NOT NULL,
PRIMARY KEY (codeDep)) ENGINE=InnoDB;

DROP TABLE IF EXISTS Image ;
CREATE TABLE Image (nomI VARCHAR(20) NOT NULL,
pathToFile VARCHAR(80),
PRIMARY KEY (nomI)) ENGINE=InnoDB;

DROP TABLE IF EXISTS Participe ;
CREATE TABLE Participe (idJ INT(3) AUTO_INCREMENT NOT NULL,
idP INT(3) NOT NULL,
numTour INT(3) NOT NULL,
PRIMARY KEY (idJ,
idP,
numTour)) ENGINE=InnoDB;

DROP TABLE IF EXISTS Inclus ;
CREATE TABLE Inclus (nomI VARCHAR(20) NOT NULL,
nomC VARCHAR(20) NOT NULL,
PRIMARY KEY (nomI,
 nomC)) ENGINE=InnoDB;

ALTER TABLE Quartier ADD CONSTRAINT FK_Quartier_codePostal FOREIGN KEY (codePostal) REFERENCES Commune (codePostal);
ALTER TABLE Route ADD CONSTRAINT FK_Route_idQ_Quartier_Depart FOREIGN KEY (idQDepart) REFERENCES Quartier (idQ);
ALTER TABLE Route ADD CONSTRAINT FK_Route_idQ_Quartier_Arrive FOREIGN KEY (idQArrive) REFERENCES Quartier (idQ);

ALTER TABLE Commune ADD CONSTRAINT FK_Commune_codeDep_Department FOREIGN KEY (codeDep) REFERENCES Departement (codeDep);
ALTER TABLE TourMisterX ADD CONSTRAINT FK_TourMisterX_route__idQ_Depart FOREIGN KEY (idQDepart) REFERENCES Route (idQDepart);
ALTER TABLE TourMisterX ADD CONSTRAINT FK_TourMisterX_route__idQ_Arrive FOREIGN KEY (idQArrive) REFERENCES Route (idQArrive);
ALTER TABLE TourMisterX ADD CONSTRAINT FK_TourMisterX_route_typet FOREIGN KEY (typeT) REFERENCES Route (typeT);
ALTER TABLE TourMisterX ADD CONSTRAINT FK_TourMisterX_route_idR FOREIGN KEY (idR) REFERENCES Route (idR);
ALTER TABLE TourMisterX ADD CONSTRAINT FK_TourMisterX_partie_idP FOREIGN KEY (idP) REFERENCES Partie (idP);
ALTER TABLE Partie ADD CONSTRAINT FK_Partie_joueuse_idj FOREIGN KEY (idJ) REFERENCES Joueuse (idJ);
ALTER TABLE Partie ADD CONSTRAINT FK_Partie_joueuse_idjvictoire FOREIGN KEY (idJVictoire) REFERENCES Joueuse (idJ);
ALTER TABLE Partie ADD CONSTRAINT FK_Partie_configuration_nomc FOREIGN KEY (nomC) REFERENCES Configuration (nomC);
ALTER TABLE Participe ADD CONSTRAINT FK_Participe_idJ_Joueuse FOREIGN KEY (idJ) REFERENCES Joueuse (idJ);
ALTER TABLE Participe ADD CONSTRAINT FK_Participe_idP_Partie FOREIGN KEY (idP) REFERENCES Partie (idP);
ALTER TABLE Participe ADD CONSTRAINT FK_Participe_numTour_TourMisterX FOREIGN KEY (numTour) REFERENCES TourMisterX (numTour);
ALTER TABLE Inclus ADD CONSTRAINT FK_Inclus_nomI FOREIGN KEY (nomI) REFERENCES Image (nomI);
ALTER TABLE Inclus ADD CONSTRAINT FK_Inclus_nomC FOREIGN KEY (nomC) REFERENCES Configuration (nomC);

# TODO faire ça cote php :(
/*DELIMITER $$
CREATE TRIGGER `composite_auto_increment` BEFORE INSERT ON TourMisterX
    FOR EACH ROW
BEGIN
    DECLARE max_tour INT(2); --
    SELECT MAX(numTour) INTO max_tour FROM TourMisterX WHERE idP = NEW.idP ;
    SET NEW.numTour = IF(ISNULL(max_tour), 1, max_tour + 1);
END $$*/