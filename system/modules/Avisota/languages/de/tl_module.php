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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['avisota_show_lists']                      = array('Listenauswahl anzeigen', 'Zeigt eine Auswahl der Listen im Frontend an.');
$GLOBALS['TL_LANG']['tl_module']['avisota_lists']                           = array('Listen', 'Wählen Sie hier die Listen aus, zu denen man sich anmelden kann.');
$GLOBALS['TL_LANG']['tl_module']['avisota_recipient_fields']                = array('Persönliche Daten', 'Wählen Sie hier die persönlichen Felder aus, die ein Abonnent zusätzlich angeben kann.');
$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_sender_name']        = array('Absendername', 'Hier können Sie den Namen des Absenders eingeben.');
$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_sender']             = array('Absenderadresse', 'Hier können Sie eine individuelle Absenderadresse eingeben.');
$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscribe_mail_plain']   = array('Anmelden Plain Text E-Mail-Template', '');
$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscribe_mail_html']    = array('Anmelden HTML E-Mail-Template', '');
$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe_mail_plain'] = array('Abmelden Plain Text E-Mail-Template', '');
$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe_mail_html']  = array('Abmelden HTML E-Mail-Template', '');
$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscription']           = array('Formulartemplate', 'Hier können Sie das Formulartemplate auswählen.');
$GLOBALS['TL_LANG']['tl_module']['avisota_registration_lists']              = array('Auswählbare Verteiler', 'Die Verteiler die der Benutzer auswählen können soll.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_legend'] = 'Abonnement';
$GLOBALS['TL_LANG']['tl_module']['avisota_mail_legend']         = 'Mail Einstellungen';
$GLOBALS['TL_LANG']['tl_module']['avisota_registration_legend'] = 'Avisota Verteiler';

?>