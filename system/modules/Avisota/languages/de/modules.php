<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @author     Oliver Hoff <oliver@hofff.com>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['avisota']              = 'Newslettersystem';
$GLOBALS['TL_LANG']['MOD']['avisota_recipients']   = array('Abonnenten', 'Newsletter Abonnenten verwalten.');
$GLOBALS['TL_LANG']['MOD']['avisota_newsletter']   = array('Newsletter', 'Newsletter verwalten und versenden.');
$GLOBALS['TL_LANG']['MOD']['avisota_tracking']     = array('Analytics', 'Lese und Reaktionsverhalten analysieren.');
$GLOBALS['TL_LANG']['MOD']['avisota_outbox']       = array('Postausgang', 'Postausgang einsehen und Newsletter versenden.');
$GLOBALS['TL_LANG']['MOD']['avisota_translation']  = array('Sprachvariablen', 'Verändern Sie die Sprachvariablen für das Newslettersystem.');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['avisota']               = 'Newslettersystem';
$GLOBALS['TL_LANG']['FMD']['avisota_subscription']  = array('Abonnement verwalten', 'An- und Abmeldung zum Avisota Newslettersystem.');
$GLOBALS['TL_LANG']['FMD']['avisota_registration']  = array('Registrierung (Avisota)', 'Registrierungsmodul mit Avisota Verteilern.');

?>