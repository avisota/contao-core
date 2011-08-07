-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_avisota_recipient_list`
-- 

CREATE TABLE `tl_avisota_recipient_list` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `viewOnlinePage` int(10) unsigned NOT NULL default '0',
  `subscriptionPage` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_avisota_recipient`
-- 

CREATE TABLE `tl_avisota_recipient` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  `salutation` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `firstname` varchar(255) NOT NULL default '',
  `lastname` varchar(255) NOT NULL default '',
  `gender` varchar(32) NOT NULL default '',
  `confirmed` char(1) NOT NULL default '',
  `token` char(32) NOT NULL default '',
  `addedOn` int(10) NOT NULL default '0',
  `addedBy` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_avisota_recipient_blacklist`
-- 

CREATE TABLE `tl_avisota_recipient_blacklist` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `email` char(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_avisota_newsletter_category`
-- 

CREATE TABLE `tl_avisota_newsletter_category` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `viewOnlinePage` int(10) unsigned NOT NULL default '0',
  `subscriptionPage` int(10) unsigned NOT NULL default '0',
  `useSMTP` char(1) NOT NULL default '',
  `smtpHost` varchar(64) NOT NULL default '',
  `smtpUser` varchar(128) NOT NULL default '',
  `smtpPass` varchar(32) NOT NULL default '',
  `smtpPort` smallint(5) unsigned NOT NULL default '0',
  `smtpEnc` varchar(3) NOT NULL default '',
  `sender` varchar(128) NOT NULL default '',
  `senderName` varchar(128) NOT NULL default '',
  `areas` varchar(255) NOT NULL default '',
  `template_html` varchar(32) NOT NULL default '',
  `template_plain` varchar(32) NOT NULL default '',
  `stylesheets` blob NULL,
  PRIMARY KEY  (`id`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_avisota_newsletter`
-- 

CREATE TABLE `tl_avisota_newsletter` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `recipients` blob NULL,
  `addFile` char(1) NOT NULL default '',
  `files` blob NULL,
  `template_html` varchar(32) NOT NULL default '',
  `template_plain` varchar(32) NOT NULL default '',
  `sendOn` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_avisota_newsletter_content`
-- 

CREATE TABLE `tl_avisota_newsletter_content` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `invisible` char(1) NOT NULL default '',
  `type` varchar(32) NOT NULL default '',
  `area` varchar(32) NOT NULL default '',
  `headline` varchar(255) NOT NULL default '',
  `text` mediumtext NULL,
  `definePlain` char(1) NOT NULL default '',
  `plain` mediumtext NULL,
  `personalize` varchar(32) NOT NULL default '',
  `addImage` char(1) NOT NULL default '',
  `singleSRC` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `size` varchar(64) NOT NULL default '',
  `imagemargin` varchar(128) NOT NULL default '',
  `imageUrl` varchar(255) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `floating` varchar(32) NOT NULL default '',
  `html` mediumtext NULL,
  `listtype` varchar(32) NOT NULL default '',
  `listitems` blob NULL,
  `tableitems` mediumblob NULL,
  `summary` varchar(255) NOT NULL default '',
  `thead` char(1) NOT NULL default '',
  `tfoot` char(1) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `linkTitle` varchar(255) NOT NULL default '',
  `events` blob NULL,
  `news` blob NULL,
  `articleAlias` int(10) unsigned NOT NULL default '0',
  `embed` varchar(255) NOT NULL default '',
  `multiSRC` blob NULL,
  `perRow` smallint(5) unsigned NOT NULL default '0',
  `sortBy` varchar(32) NOT NULL default '',
  `galleryHtmlTpl` varchar(64) NOT NULL default '',
  `galleryPlainTpl` varchar(64) NOT NULL default '',
  `protected` char(1) NOT NULL default '',
  `groups` blob NULL,
  `guests` char(1) NOT NULL default '',
  `cssID` varchar(255) NOT NULL default '',
  `space` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_avisota_newsletter_outbox`
-- 

CREATE TABLE `tl_avisota_newsletter_outbox` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_avisota_newsletter_outbox_recipient`
-- 

CREATE TABLE `tl_avisota_newsletter_outbox_recipient` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  `domain` varchar(255) NOT NULL default '',
  `recipientID` int(10) unsigned NOT NULL default '0',
  `source` varchar(255) NOT NULL default '',
  `sourceID` int(10) unsigned NOT NULL default '0',
  `send` int(10) unsigned NOT NULL default '0',
  `failed` char(1) NOT NULL default '',
  `error` blob NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `email` (`email`),
  KEY `domain` (`domain`),
  KEY `send` (`send`),
  KEY `source` (`source`),
  KEY `sourceID` (`sourceID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_avisota_newsletter_read`
-- 

CREATE TABLE `tl_avisota_newsletter_read` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `recipient` varchar(255) NOT NULL default '',
  `readed` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_avisota_newsletter_link`
-- 

CREATE TABLE `tl_avisota_newsletter_link` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `url` blob NULL,
  `recipient` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_avisota_newsletter_link_hit`
-- 

CREATE TABLE `tl_avisota_newsletter_link_hit` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0'
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `avisota_recipient_fields` blob NULL,
  `avisota_subscription_sender_name` varchar(255) NOT NULL default '',
  `avisota_subscription_sender` varchar(255) NOT NULL default '',
  `avisota_show_lists` char(1) NOT NULL default '',
  `avisota_lists` blob NULL,
  `avisota_template_subscribe_mail_plain` varchar(64) NOT NULL default '',
  `avisota_template_subscribe_mail_html` varchar(64) NOT NULL default '',
  `avisota_template_unsubscribe_mail_plain` varchar(64) NOT NULL default '',
  `avisota_template_unsubscribe_mail_html` varchar(64) NOT NULL default '',
  `avisota_registration_lists` blob NULL,
  `subscriptionTpl` varchar(64) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_page`
-- 

CREATE TABLE `tl_page` (
  `jumpBack` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_user`
-- 

CREATE TABLE `tl_user` (
  `avisota_recipient_lists` blob NULL,
  `avisota_recipient_list_permissions` blob NULL,
  `avisota_recipient_permissions` blob NULL,
  `avisota_newsletter_categories` blob NULL,
  `avisota_newsletter_category_permissions` blob NULL,
  `avisota_newsletter_permissions` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_user_group`
-- 

CREATE TABLE `tl_user_group` (
  `avisota_recipient_lists` blob NULL,
  `avisota_recipient_list_permissions` blob NULL,
  `avisota_recipient_permissions` blob NULL,
  `avisota_newsletter_categories` blob NULL,
  `avisota_newsletter_category_permissions` blob NULL,
  `avisota_newsletter_permissions` blob NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_member`
-- 

CREATE TABLE `tl_member` (
  `avisota_registration_lists` blob NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
