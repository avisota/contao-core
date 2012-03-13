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
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['newsletter']  = 'Newsletter';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['date']        = 'Aufgegeben';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['count']       = 'Empfänger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['state']       = 'Status';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['outstanding'] = 'ausstehend';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['total']       = 'total';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['failed']      = 'fehlgeschlagen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['sended']      = 'versendet';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['sended_on']   = 'Versendet';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['recipient']   = 'Empfänger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['source']      = 'Quelle';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['headline']       = 'Newsletter versenden';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['open']           = 'Versand offen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['incomplete']     = 'Versand unvollständig';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['complete']       = 'Versand vollständig';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['recipient_list'] = 'Empfängerliste';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['mgroup']         = 'Mitgliedergruppe';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['empty']         = 'Es sind keine Aufträge im Postausgang.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['rejected']      = 'Die E-Mail-Adresse %s wurde deaktiviert.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['error']         = 'In diesem Verteiler sind keine aktiven Abonnenten vorhanden.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['removed']       = 'Auftrag wurde gelöscht.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['dateimsFormat'] = 'd.m.Y h:i:s';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['send']    = array('Versenden', 'Newsletter an ausstehende Empfänger versenden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['remove']  = array('Auftrag löschen', 'Den Auftrag löschen.', 'Soll der Auftrag wirklich gelöscht werden?');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['details'] = array('Details', 'Details über den Auftrag anzeigen.');


/**
 * Personalisation
 */

?>