-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `navigation_article` varchar(10) NOT NULL default '',
  `navigation_min_level` varchar(10) NOT NULL default '1',
  `navigation_max_level` varchar(10) NOT NULL default '6',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
