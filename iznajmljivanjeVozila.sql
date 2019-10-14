/*
SQLyog Community v13.1.1 (64 bit)
MySQL - 5.7.23 : Database - iznajmljivanje_vozila
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`iznajmljivanje_vozila` /*!40100 DEFAULT CHARACTER SET cp1250 COLLATE cp1250_croatian_ci */;

USE `iznajmljivanje_vozila`;

/*Table structure for table `korisnici` */

DROP TABLE IF EXISTS `korisnici`;

CREATE TABLE `korisnici` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `korisnickoIme` varchar(25) COLLATE cp1250_croatian_ci NOT NULL,
  `ime` varchar(35) COLLATE cp1250_croatian_ci NOT NULL,
  `prezime` varchar(35) COLLATE cp1250_croatian_ci NOT NULL,
  `email` varchar(120) COLLATE cp1250_croatian_ci NOT NULL,
  `lozinka` text COLLATE cp1250_croatian_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `vrijemeRegistracije` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `korisnickoIme` (`korisnickoIme`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=cp1250 COLLATE=cp1250_croatian_ci;

/*Data for the table `korisnici` */

insert  into `korisnici`(`id`,`korisnickoIme`,`ime`,`prezime`,`email`,`lozinka`,`admin`,`vrijemeRegistracije`) values 
(32,'asd2','Yvydvyegagaa','Aeeeer','asd2@gmail.com','$2y$10$DQbMOgCMc4a23fUxivp2rOEV3eXAvH/S89ik3GlJUi7gcVJb0y1RK',0,'2019-08-31 16:01:47'),
(35,'bbek123','Bruno','Bek','bbek@gmail.com','$2y$10$jDxavjfFySQ9mEFuWIqRPO6F7.EP2hj8ybkV/z9HUNWikxFR5Hl2m',0,'2019-09-11 12:39:46'),
(26,'admin','Admin','Prezime','admin@gmail.com','$2y$10$4FmJ9etS0Rl/gAgBbyaQheurE81FCOFRr/rLJ3FCebbQrJSSooLEW',1,'2019-08-29 22:19:35'),
(33,'qwe','Adgeag','Ageada','qwe@gmail.com','$2y$10$SHXB9AAJmzPe4fm0mh.x8OtJI7dRFgb9v76XsMVL8jTpNuMIgZkeu',0,'2019-09-01 15:19:06');

/*Table structure for table `marke` */

DROP TABLE IF EXISTS `marke`;

CREATE TABLE `marke` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marka` varchar(50) COLLATE cp1250_croatian_ci NOT NULL,
  `vrijemeDodavanja` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marka` (`marka`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=cp1250 COLLATE=cp1250_croatian_ci;

/*Data for the table `marke` */

insert  into `marke`(`id`,`marka`,`vrijemeDodavanja`) values 
(1,'Mazda','2019-07-14 23:28:57'),
(2,'Nissan','2019-07-14 23:28:58'),
(3,'BMW','2019-07-14 23:28:58'),
(4,'Mercedes-Benz','2019-07-14 23:28:58'),
(5,'Opel','2019-07-14 23:28:58'),
(6,'Audi','2019-07-14 23:28:58'),
(7,'Honda','2019-07-14 23:28:58'),
(56,'Testno Vozilo','2019-09-09 22:24:51');

/*Table structure for table `rezervacije` */

DROP TABLE IF EXISTS `rezervacije`;

CREATE TABLE `rezervacije` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `korisnikId` int(11) NOT NULL,
  `voziloId` int(11) NOT NULL,
  `vrijemeOd` timestamp NOT NULL,
  `vrijemeDo` timestamp NOT NULL,
  `sveukupnoZaPlacanje` decimal(11,2) NOT NULL,
  `naplatniKod` text COLLATE cp1250_croatian_ci NOT NULL,
  `vremenskoStanje` varchar(15) COLLATE cp1250_croatian_ci DEFAULT NULL,
  `odsutno` tinyint(1) NOT NULL DEFAULT '0',
  `vrijemeRezervacije` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `korisnikId` (`korisnikId`),
  KEY `voziloId` (`voziloId`)
) ENGINE=MyISAM AUTO_INCREMENT=286 DEFAULT CHARSET=cp1250 COLLATE=cp1250_croatian_ci;

/*Data for the table `rezervacije` */

insert  into `rezervacije`(`id`,`korisnikId`,`voziloId`,`vrijemeOd`,`vrijemeDo`,`sveukupnoZaPlacanje`,`naplatniKod`,`vremenskoStanje`,`odsutno`,`vrijemeRezervacije`) values 
(285,33,139,'2019-09-26 10:00:00','2019-09-29 10:00:00',1359.00,'ch_1FHbttJ8MkxnNupMI7ljOH0O',NULL,0,'2019-09-11 21:54:48'),
(280,35,139,'2019-09-15 09:00:00','2019-09-17 09:00:00',906.00,'ch_1FHTFHJ8MkxnNupMl64ZJxo2','nadolazece',1,'2019-09-11 12:40:19'),
(281,35,146,'2019-09-11 13:40:00','2019-09-13 13:00:00',800.00,'ch_1FHU2QJ8MkxnNupMuYvOZyq5','aktivne',0,'2019-09-11 12:43:21');

/*Table structure for table `vozila` */

DROP TABLE IF EXISTS `vozila`;

CREATE TABLE `vozila` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `markaId` int(11) NOT NULL,
  `model` varchar(50) COLLATE cp1250_croatian_ci NOT NULL,
  `opis` text COLLATE cp1250_croatian_ci,
  `cijenaPoDanu` decimal(11,2) NOT NULL,
  `godinaProizvodnje` int(11) NOT NULL,
  `prijedeniKilometri` int(11) NOT NULL,
  `motor` varchar(50) COLLATE cp1250_croatian_ci NOT NULL,
  `brojSjedala` int(2) NOT NULL,
  `klimaUredaj` tinyint(1) DEFAULT '0',
  `usb` tinyint(1) DEFAULT '0',
  `radio` tinyint(1) DEFAULT '0',
  `navigacija` tinyint(1) DEFAULT '0',
  `vrijemeDodavanja` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `markaId` (`markaId`)
) ENGINE=MyISAM AUTO_INCREMENT=504 DEFAULT CHARSET=cp1250 COLLATE=cp1250_croatian_ci;

/*Data for the table `vozila` */

insert  into `vozila`(`id`,`markaId`,`model`,`opis`,`cijenaPoDanu`,`godinaProizvodnje`,`prijedeniKilometri`,`motor`,`brojSjedala`,`klimaUredaj`,`usb`,`radio`,`navigacija`,`vrijemeDodavanja`) values 
(146,56,'1','Lorem ipsum dolor sit amet consectetur adipisicing elit. Earum, quis consequuntur? Nisi, explicabo voluptas necessitatibus ipsa adipisci dolorem alias recusandae cum repudiandae libero dignissimos nihil, aperiam excepturi laudantium dolores impedit?',400.00,2014,100000,'dizel',4,0,0,1,1,'2019-08-18 14:35:23'),
(139,56,'2','Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus, magnam molestiae est laudantium in quos fugit cum harum asperiores accusantium ad alias, corporis deserunt totam dolore placeat quibusdam ea! Adipisci, incidunt. Impedit in ratione at facere, magnam amet inventore accusantium similique quidem aliquid, suscipit rerum odio voluptate deleniti iure autem repellendus quam magni, eveniet dolor. Earum unde eaque nam, ipsum eos, cumque in fuga odio distinctio enim sed nemo molestias architecto voluptate consequuntur qui? Dolorem.',453.00,2015,70000,'benzin',4,1,1,0,0,'2019-07-27 20:53:36'),
(148,6,'a5','Lorem ipsum dolor sit amet consectetur, adipisicing elit. Accusamus, ad recusandae placeat vitae praesentium quaerat corrupti quae cupiditate dolorem mollitia impedit reprehenderit modi officia optio saepe facere ducimus temporibus harum, in eum, explicabo reiciendis. Neque blanditiis laudantium sit consectetur dolor quam enim dicta odio sapiente libero eos assumenda, iste',800.00,2016,50000,'benzin',4,0,0,1,0,'2019-08-18 14:37:20'),
(154,1,'cx 9','Lorem ipsum dolor sit amet consectetur adipisicing elit. Facere commodi possimus consequatur eveniet reprehenderit dicta dolores voluptatibus quo sapiente, excepturi sint obcaecati eaque blanditiis, fuga soluta aut amet quibusdam aliquid! Soluta ullam architecto consequuntur suscipit, molestias vel magnam temporibus delectus',300.00,2017,10000,'benzin',4,0,0,0,0,'2019-08-19 19:37:43');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
