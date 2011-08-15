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
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['subject']              = array('Emne', 'Indtast emne for nyhedsbrevet.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['alias']                = array('Nyhedsbrevets Alias', 'Nyhedsbrevets Alias er en unique reference som kan blive kaldt istedet for Nyhedsbrevets I.D.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['addFile']              = array('Vedhæft Fil', 'Vedhæft en eller flere filer i Nyhedsbrevet');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['files']                = array('Vedhæftninger', 'Vælg venligst den fil(er), der skal vedhæftes fra Filhåndteringen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_html']        = array('HTML e-mail skabelon','Her kan du vælge HTML E-mail-skabelon.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_plain']       = array('Almindelig tekst E-Mail-skabelon', 'Her kan du vælge Almindelig tekst E-mail-skabelon.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['recipients']           = array('Modtagere', 'Vælg modtagerne.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['tstamp']               = array('Ændre Dato', 'Dato og tid for sidste ændring.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sendPreviewTo']        = array('Send Test', 'Test fremsendelse af nyhedsbrevet til denne e-mail-adresse.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_mode']         = array('Preview Mode', 'Type a Preview Mode.','HTML Preview','Almindelig Tekst Preview.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview_personalized'] = array('Tilpas','Type a tilpasning','Ingen','Anonym','Personlig');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['newsletter_legend']  = 'Nyhedsbrev';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['recipient_legend']   = 'Modtagere';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['attachment_legend']  = 'Vedhæftninger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['template_legend']    = 'Skabelon indstillinger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['headline']           = 'Se og send nyhedsbrev';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['from']               = 'Afsender';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['live']               = 'Updater Preview';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['preview']            = 'Preview';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sendConfirm']        = 'Bekræftelse af afsendt Nyhedsbrev';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['unsubscribe']        = 'Afmeld nyhedsbrev';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation']         = 'Kære/-r';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_male']    = 'Kære Hr';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['salutation_femaile'] = 'Kære Frøken';

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['view']     = array('Se og send','Se nyhedsbrevet og send det.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['send']     = array('Send','Send Nyhedsbrevet.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['sended']   = 'Sendt %s';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['confirm']  = 'Nyhedsbrevet blev sendt til alle modtagere.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['online']   = 'Problemer med visning? Se nyhedsbrevet online.';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['list']     = 'Distribution Liste';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['member']   = 'Medlemmer';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['mgroup']   = 'Medlemsgruppe';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['new']         = array('Nyt Nyhedsbrev','Create a new Newsletter.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['show']        = array('Nyhedsbrevsdetaljer','Nærmere oplysninger om nyhedsbrevets I.D. %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['copy']        = array('Dupliker Nyhedsbrevet','Dupliker Nyhedsbrevet ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['delete']      = array('Slet Nyhedsbrev','Slet Nyhedsbrev ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['edit']        = array('Rediger Nyhedsbrev','Rediger Nyhedsbrev ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['editheader']  = array('Rediger Nyhedsbrevets instillinger','Rediger Nyhedsbrevets instillinger ID %s');


/**
 * Personalisation
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['salutation'] = 'Kære/-r';
$GLOBALS['TL_LANG']['tl_avisota_newsletter']['anonymous']['name']       = 'Modtager/-in';

?>