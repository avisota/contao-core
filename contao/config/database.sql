-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

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
  `avisota_template_subscription` varchar(64) NOT NULL default '',
  `avisota_selectable_lists` blob NULL,
  `subscriptionTpl` varchar(64) NOT NULL default '',
  `avisota_send_notification` char(1) NOT NULL default '',
  `avisota_notification_time` int(10) NOT NULL default '3',
  `avisota_template_notification_mail_plain` varchar(64) NOT NULL default '',
  `avisota_template_notification_mail_html` varchar(64) NOT NULL default '',
  `avisota_subscribe_confirmation_page` int(10) NOT NULL default '0',
  `avisota_unsubscribe_confirmation_page` int(10) NOT NULL default '0',
  `avisota_do_cleanup` char(1) NOT NULL default '',
  `avisota_cleanup_time` int(10) NOT NULL default '7',
  `avisota_categories` blob NULL,
  `avisota_reader_template` varchar(64) NOT NULL default '',
  `avisota_list_template` varchar(64) NOT NULL default '',
  `avisota_view_page` int(10) NOT NULL default '0',
  `avisota_form_target` int(10) NOT NULL default '0',
  `avisota_template_subscribe` varchar(64) NOT NULL default '',
  `avisota_template_unsubscribe` varchar(64) NOT NULL default '',
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
-- Table `tl_member_to_mailing_list`
--

CREATE TABLE `tl_member_to_mailing_list` (
  `member` int(10) unsigned NOT NULL default '0',
  `list` int(10) unsigned NOT NULL default '0',
  `confirmed` char(1) NOT NULL default '',
  PRIMARY KEY  (`member`, `list`),
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table `tl_member`
-- 

CREATE TABLE `tl_member` (
  `avisota_lists` blob NULL,
  `avisota_subscribe` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
