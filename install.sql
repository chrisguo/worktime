set names utf8;
CREATE DATABASE IF NOT EXISTS common_db_name DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS common_db_name.`sys` (
`id` int(11) NOT NULL ,
`list` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS common_db_name.`ticket` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`acter_id` varchar(200) NOT NULL,
`reporter_id` varchar(200) NOT NULL,

`caty` int(11) NOT NULL,
`priority` int(11) NOT NULL,
`department` varchar(200) NOT NULL,

`title` varchar(200) NOT NULL,
`content` text NOT NULL,
`log` blob NOT NULL,

`t` int(11) NOT NULL,
`last_t` int(11) NOT NULL,
`status` TINYINT(4) NOT NULL,
`test_status` TINYINT(4) NOT NULL,
`test_report` blob NOT NULL,
`version` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  AUTO_INCREMENT=10000 ;
