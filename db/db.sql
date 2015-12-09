/*----------------------------------------------------------------------------*/
CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `a_username` varchar(250) NOT NULL default '',
  `a_password` varchar(250) NOT NULL default '',
  `a_email` varchar(250) NOT NULL default '',
  `a_title` varchar(250) NOT NULL default ''
) TYPE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `settings` VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'alnik@rambler.ru', 'DogBet');


/*----------------------------------------------------------------------------*/
CREATE TABLE `main_pages` (
    `page_id`       INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `destination`   INT NOT NULL default '0',
    `order_index`   INT NOT NULL default '0',
    `title`         varchar(120) NOT NULL default '',
    `keywords`      varchar(250) NOT NULL default '',
    `description`   varchar(250) NOT NULL default '',
    `menu_title`    varchar(120) NOT NULL default '',
    `content`       text NOT NULL default '',
    `is_active`     tinyint(1) NOT NULL default '0',
    KEY `order_index_idx` (`order_index`),
    KEY `destination_idx` (`destination`)
) TYPE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `main_pages` VALUES (1, '', 1, 'Главная', '', '', 'Главная', '', 1);
INSERT INTO `main_pages` VALUES (2, '', 2, 'Контакты', '', '', 'Контакты', '', 1);

/*----------------------------------------------------------------------------*/
DROP TABLE IF EXISTS news;
CREATE TABLE `news` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(120) NOT NULL default '',
  `article` text NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(30) NOT NULL default '',
  `photo2` varchar(30) NOT NULL default '',
  `z_date` int(11) NOT NULL default '0',
  `is_active` tinyint(1) NOT NULL default '0',
  KEY `z_date_idx` (`z_date`)
) TYPE=MyISAM DEFAULT CHARSET=utf8;


/*----------------------------------------------------------------------------*/
DROP TABLE IF EXISTS pgalleries;
CREATE TABLE `pgalleries` (
  `gallery_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(120) NOT NULL default '',
  `description` text NOT NULL,
  `pdescription` longtext NOT NULL,
  `z_date` int(11) NOT NULL default '0',
  `is_active` tinyint(1) NOT NULL default '0',
  KEY `z_date_idx` (`z_date`)
) TYPE=MyISAM DEFAULT CHARSET=utf8;


/*----------------------------------------------------------------------------*/
DROP TABLE IF EXISTS photos;
CREATE TABLE `photos` (
  `photo_id`        int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `gallery_id`      int(11) NOT NULL default '0',
  `photo`           varchar(50) NOT NULL default '',
  `content`         text NOT NULL,
  `z_date`          int(11) NOT NULL default '0',
  `is_active`       tinyint(1) NOT NULL default '0',
  KEY `z_date_idx` (`z_date`)
) TYPE=MyISAM DEFAULT CHARSET=utf8;


/*----------------------------------------------------------------------------*/
DROP TABLE IF EXISTS offers;
CREATE TABLE `offers` (
  `offer_id`    int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title`       varchar(120) NOT NULL default '',
  `description` text NOT NULL,
  `pdescription` longtext NOT NULL,
  `photo`       varchar(50) NOT NULL default '',
  `z_date`      int(11) NOT NULL default '0',
  `is_spec`     tinyint(1) NOT NULL default '0',
  `is_active`   tinyint(1) NOT NULL default '0',
  KEY `z_date_idx` (`z_date`)
) TYPE=MyISAM DEFAULT CHARSET=utf8;


/*----------------------------------------------------------------------------*/
DROP TABLE IF EXISTS testimonials;
CREATE TABLE `testimonials` (
  `testimonial_id` 	int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` 			varchar(120) NOT NULL default '',
  `email` 			varchar(120) NOT NULL default '',
  `city` 			varchar(120) NOT NULL default '',
  `description`     text NOT NULL,
  `z_date`          int(11) NOT NULL default '0',
  `is_active`       tinyint(1) NOT NULL default '0',
  KEY `z_date_idx` (`z_date`)
) TYPE=MyISAM DEFAULT CHARSET=utf8;


/*----------------------------------------------------------------------------*/
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
    `report_id`     INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title`         varchar(120) NOT NULL default '',
    `description`   text NOT NULL,
    `document`      varchar(50) NOT NULL default '',
    `z_date`        INT NOT NULL default '0',
    `is_active`     tinyint(1) NOT NULL default '0',
    KEY `z_date_idx` (`z_date`)
) TYPE=MyISAM DEFAULT CHARSET=utf8;


/*----------------------------------------------------------------------------*/
DROP TABLE IF EXISTS `report_files`;
CREATE TABLE `report_files` (
    `file_id`       INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `report_id`     INT NOT NULL default '0',
    `photo`         varchar(50) NOT NULL default '',
    `content`       text NOT NULL,
    `z_date`        INT NOT NULL default '0',
    `is_active`     tinyint(1) NOT NULL default '0',
    KEY `z_date_idx` (`z_date`)
) TYPE=MyISAM DEFAULT CHARSET=utf8;

