
CREATE TABLE `avisota_newsletter_outbox_{{alias}}_open` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  `domain` varchar(255) NOT NULL default '',
  `personals` text NULL,
  `newsletter` text NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `domain` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
