<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * @copyright  InfinitySoft 2010,2011,2012
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_transport']['type']       = array('Transportmodul', 'Wählen Sie hier das Transportmodul aus.');
$GLOBALS['TL_LANG']['tl_avisota_transport']['title']      = array('Titel', 'Hier können Sie einen Titel für das Transportmodul angeben.');
$GLOBALS['TL_LANG']['tl_avisota_transport']['senderName'] = array('Absendername', 'Hier können Sie den Namen des Absenders eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_transport']['sender']     = array('Absenderadresse', 'Hier können Sie eine individuelle Absenderadresse eingeben.');
// swift transport
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftUseSmtp']  = array('Eigener SMTP-Server', 'Einen eigenen SMTP-Server für den Newsletter-Versand verwenden.');
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpHost'] = array('SMTP-Hostname', 'Bitte geben Sie den Hostnamen des SMTP-Servers ein.');
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpUser'] = array('SMTP-Benutzername', 'Hier können Sie den SMTP-Benutzernamen eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpPass'] = array('SMTP-Passwort', 'Hier können Sie das SMTP-Passwort eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpEnc']  = array('SMTP-Verschlüsselung', 'Hier können Sie eine Verschlüsselungsmethode auswählen (SSL oder TLS).');
$GLOBALS['TL_LANG']['tl_avisota_transport']['swiftSmtpPort'] = array('SMTP-Portnummer', 'Bitte geben Sie die Portnummer des SMTP-Servers ein.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_transport']['transport_legend'] = 'Transportmodul';
$GLOBALS['TL_LANG']['tl_avisota_transport']['sender_legend']    = 'Absender';
$GLOBALS['TL_LANG']['tl_avisota_transport']['swift_legend']     = 'Swift PHP Mailer';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_transport']['swift'] = 'Swift PHP Mailer';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_transport']['new']    = array('Neues Transportmodul', 'Eine neues Transportmodul erstellen');
$GLOBALS['TL_LANG']['tl_avisota_transport']['show']   = array('Transportmoduldetails', 'Details des Transportmoduls ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_transport']['delete'] = array('Transportmodul löschen', 'Transportmodul ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_transport']['edit']   = array('Transportmodul bearbeiten', 'Transportmodul ID %s bearbeiten');
