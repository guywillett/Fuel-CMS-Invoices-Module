CREATE TABLE if not exists `invoices` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `description` varchar(255) collate utf8_unicode_ci NOT NULL,
`content` text collate utf8_unicode_ci ,
  `published` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'yes',
`date` date,
`due_date` date,
`unix` int(11) unsigned,
`date_added` datetime default NULL,
`last_modified` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
`file` varchar(255)collate utf8_unicode_ci,
`user_id` int(10) unsigned,
`status` varchar(255)collate utf8_unicode_ci,
`paid` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
`recur` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
`recur_days` int(10) unsigned,
`send_note` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
`note_sent` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
`total` decimal(13,2),
`tax_total` decimal(13,2),
`tax` decimal(13,2),
`amount` decimal(13,2),
`date_last_sent` date,
`recur_sent` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE if not exists `email_templates` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `from` varchar(255) collate utf8_unicode_ci NOT NULL,
  `copied_to` varchar(255) collate utf8_unicode_ci,
  `subject` varchar(255) collate utf8_unicode_ci NOT NULL,
  content text collate utf8_unicode_ci NOT NULL,
  `active` enum('yes','no') collate utf8_unicode_ci NOT NULL default 'yes',
  `date_added` datetime default NULL,
  `last_modified` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `email_templates` (`name`,`from`,`subject`,`content`) VALUES ('invoice','your@emailaddress.com','Invoice from Your Company','Do not forget to use the placeholders like {first_name} {link} etc');
  INSERT INTO `email_templates` (`name`,`from`,`subject`,`content`) VALUES ('reminder','your@emailaddress.com','Invoice from Your Company is Over Due!','Do not forget to use the placeholders like {first_name} {link} etc');