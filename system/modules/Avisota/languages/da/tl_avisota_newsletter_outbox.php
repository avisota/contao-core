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
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['newsletter']  = 'Nyhedsbrev';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['date']        = 'Sendt';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['count']       = 'Modtager';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['outstanding'] = 'Pending';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['total']       = 'Total';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['failed']      = 'Mislykket';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['sended']      = 'Sendt';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['sended_on']   = 'Afsender';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['recipient']   = 'Modtager';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['source']      = 'Kilde';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['headline']       = 'Send Nyhedsbrev';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['open']           = 'Åbne afsending';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['incomplete']     = 'Ufærdig afsending';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['complete']       = 'Færdig med at sende';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['recipient_list'] = 'Modtager liste';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['mgroup']         = 'Medlemsgruppe';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['empty']         = 'Der er ingen opgaver i udboksen.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['rejected']      = 'Email adressen %s blev deaktiveret.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['error']         = 'I denne distribution, er der ingen aktive abonnenter.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['removed']       = 'Fjernet';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['dateimsFormat'] = 'l -- F d, Y h:i:s';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['send']    = array('Send ',' Send nyhedsbrevet.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['remove']  = array('Slet Opgave ',' Slet denne opgave.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_outbox']['details'] = array('Se Detaljer ',' Detaljer omkring opgaven.');


/**
 * Personalisation
 */

?>