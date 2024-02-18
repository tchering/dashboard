-- Active: 1706778247599@@127.0.0.1@3306@dashboard


insert into article(num_article,designation,price) VALUES
('AA001','T-shirt',10),
('AA002','Pantalon',20),
('AA003','Chaussure',30),
('AA004','Chapeau',40),
('AA005','Veste',50),
('AA006','Pull',60),
('AA007','Chemise',70),
('AA008','Short',80),
('AA009','Jupe',90),
('AA010','Robe',100),
('AA011','Chaussette',110),
('AA012','Culotte',120),
('AA013','Soutien-gorge',130),
('AA014','Pyjama',140),
('AA015','Maillot de bain',150),
('AA016','Costume',160),
('AA017','Cravate',170),
('AA018','Chausson',180),
('AA019','Manteau',190),
('AA020','Chapeau',200);

insert into client(num_client,name_client,adresse_client) VALUES
('CC001','Jean','Paris'),
('CC002','Paul','Lyon'),
('CC003','Jacques','Marseille'),
('CC004','Pierre','Lille'),
('CC005','Marie','Toulouse'),
('CC006','Julie','Bordeaux'),
('CC007','Sophie','Nantes'),
('CC008','Lucie','Strasbourg'),
('CC009','Alice','Nice'),
('CC010','Julien','Rennes'),
('CC011','Thomas','Montpellier'),
('CC012','Nicolas','Toulon'),
('CC013','Laurent','Grenoble'),
('CC014','François','Dijon'),
('CC015','Eric','Angers'),
('CC016','Sylvain','Nancy'),
('CC017','Olivier','Metz'),
('CC018','Alexandre','Rouen'),
('CC019','Antoine','Avignon'),
('CC020','Benoit','Saint-Etienne');

insert into commande(client_id,date_commande,num_commande) values 
(2,'2022-01-02','CMD002'),
(3,'2022-01-03','CMD003');

insert into ligne_commande(commande_id,article_id,quantity,price) values 
(4,3,2,30),
(6,4,3,40);
```