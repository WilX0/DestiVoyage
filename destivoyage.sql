creat database destivoyage;

CREATE TYPE typesexe AS ENUM ('homme', 'femme');

CREATE TABLE utilisateur (
    email VARCHAR(50) PRIMARY KEY NOT NULL,
    login VARCHAR(50) PRIMARY KEY NOT NULL,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(20),
    prenom VARCHAR(20),
    date_naissance DATE,
    sexe typesexe, 
    codemdpoublie INT,
    expire_mdpo DATE,
    email_verifie BOOLEAN 
);
CREATE TABLE verify (
    id_verif VARCHAR(50) PRIMARY KEY NOT NULL,
    code_verif INT,
    expiration_code DATE,
    email VARCHAR(50) NOT NULL,
    login VARCHAR(50) NOT NULL,
    FOREIGN KEY (email) REFERENCES utilisateur (email) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (login) REFERENCES utilisateur (login) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Hotel_fav (
    id_hotel VARCHAR(50) PRIMARY KEY NOT NULL,
    nom VARCHAR(50),
    ville VARCHAR(50),
    pays VARCHAR(50)
);

CREATE TABLE Vols_fav (
    id_vols VARCHAR(50) PRIMARY KEY NOT NULL,
    depart VARCHAR(50),
    destination VARCHAR(50),
    prix DECIMAL(10, 2), 
);
CREATE TABLE avoir (
    id_hotel VARCHAR(50),
    email VARCHAR(50),
    login VARCHAR(50),
    PRIMARY KEY (id_hotel, email, login),
    FOREIGN KEY (id_hotel) REFERENCES Hotel_fav (id_hotel) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (email, login) REFERENCES utilisateur (email, login) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE detenir (
    id_vols VARCHAR(50),
    email VARCHAR(50),
    login VARCHAR(50),
    PRIMARY KEY (id_vols, email, login),
    FOREIGN KEY (id_vols) REFERENCES Vols_fav (id_vols) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (email, login) REFERENCES utilisateur (email, login) ON UPDATE CASCADE ON DELETE CASCADE
);

