

 


/*  create table */

CREATE DATABASE learnefrei;
CREATE USER learnefreiuser@'localhost' IDENTIFIED BY 'eisohx7b';
GRANT ALL PRIVILEGES ON learnefrei.* TO learnefreiuser;





 CREATE TABLE learnefrei.MEMBRE (
	membre_id int(11) NOT NULL auto_increment,
	
 	nom VARCHAR(20)  NOT NULL,
 	prenom VARCHAR(20)  NOT NULL,

	email CHAR(50) NOT NULL,
	
	password CHAR(255) NOT NULL,




	PRIMARY KEY  (membre_id )



) ;


CREATE TABLE learnefrei.FORMATIONS (
 formation_id int(20) NOT NULL auto_increment,
 matiere VARCHAR( 200) NOT NULL,
 contenu TEXT NOT NULL,
 membre_id int (11) NOT NULL,
 note FLOAT,
 note_coeff INT,
 nb_notes int(10), /*  utilisé pour stocker le nombre de vues   */
 nb_com INT,
 PRIMARY KEY (formation_id),
 FOREIGN KEY (membre_id) REFERENCES MEMBRE(membre_id)

);

CREATE TABLE learnefrei.NOTATIONS (
	notation_id INT NOT NULL auto_increment,
	membre_id int(11) NOT NULL,
	formation_id int(20) NOT NULL,
	note INT NOT NULL,
	PRIMARY KEY (notation_id),
	FOREIGN KEY (membre_id) REFERENCES MEMBRE(membre_id) ON DELETE CASCADE,
	FOREIGN KEY (formation_id) REFERENCES FORMATIONS(formation_id) ON DELETE CASCADE
);

CREATE TABLE learnefrei.COMMENTAIRE (
	commentaire_id int(20)  NOT NULL auto_increment,
	contenu TEXT NOT NULL,
	formation_id INT NOT NULL,
	membre_id INT NOT NULL,
	PRIMARY KEY (commentaire_id),
	FOREIGN KEY (formation_id) REFERENCES FORMATIONS(formation_id) ON DELETE CASCADE,
	FOREIGN KEY (membre_id) REFERENCES MEMBRE(membre_id) ON DELETE CASCADE
);
