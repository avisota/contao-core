<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011,2012 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['title']            = array(
	'Title ',
	'Here you can enter the title of this distribution group.'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['alias']            = array(
	'Alias',
	' The alias is a unique reference that can be called instead of the numeric ID.'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['viewOnlinePage']   = array(
	'View Online Page ',
	'Please select the page that the subscriber will be redirected to if they wish to view the Newsletter online.'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['subscriptionPage'] = array(
	'Manage Subscription Page ',
	'Please select a page that the subscriber will be redirected to if they wish to change their Newsletter preferences.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['list_legend']   = 'Distribution Group';
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['expert_legend'] = 'Redirect Settings';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['new']        = array(
	'New Distribution List',
	'Add a new distributor list'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['show']       = array(
	'Distribution Details',
	'Details of the distribution ID %s'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['copy']       = array('Copy Distribution', 'Copy distribution ID %s');
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['delete']     = array(
	'Delete Distribution',
	'Delete distribution ID %s'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['edit']       = array(
	'Edit Distribution',
	'Edit details of distribution ID %s'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['editheader'] = array(
	'Edit Header',
	'Edit the header of distribution ID %s'
);
