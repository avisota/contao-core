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
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['title']              = array('Titel ','Her kan du ændre titlen på kategorien');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['alias']              = array('Kategoriens alias','Kategoriens alias er en unique reference der kan kaldes istedet for den numeriske kategori alias ID.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['viewOnlinePage']     = array('Nyhedsbrevs læserside','Vælg venligst den side hvor tilmeldte vil blive omdirigeret til for at læses nyhedsbreve, hvis de vælger at læse nyhedsbrevet online.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['subscriptionPage']   = array('Håndter medlems abonnementer','Vælg venligst den side som abonnenterne vil blive sendt til, med henblik på at administrere deres abonnement.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['senderName']         = array('Afsenders navn','Her kan der indtastes navn på afsender.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['sender']             = array('Afsenders adresse','Her kan der indtastes en tilpasset returadresse.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['useSMTP']            = array('Personlig SMTP Server','Brug din egen SMTP server til at sende nyhedsbreve med.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtpHost']           = array('SMTP host navn ','Indtast host navn på din SMTP server.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtpUser']           = array('SMTP Brugernavn ','Her kan du indtaste SMTP-brugernavn.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtpPass']           = array('SMTP Kodeord','Her kan du indtaste SMTP-adgangskode.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtpEnc']            = array('SMTP kryptering ','Her kan du vælge en krypteringsmetode (SSL eller TLS).');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtpPort']           = array('SMTP port nummer ',' Indtast venligst portnummeret på SMTP-server.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['tstamp']             = array('Ændre Dato ', 'Dato og tidspunkt for sidste ændring.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['areas']              = array('Områder', 'Kommasepareret liste med definerede elementer/kolonner i nyhedsbrevet (f.eks. header,left,right,footer).');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['template_html']      = array('HTML Email Skabelon ', 'Her kan du vælge HTML e-mail-skabelonen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['template_plain']     = array('Ren tekst e-mail-skabelon ','Her kan du vælge ren tekst e-mail-skabelon.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['stylesheets']        = array('Style Sheets ',' Style sheets, som skal inkluderes i nyhedsbrevet.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['category_legend'] = 'Kategori';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['smtp_legend']     = 'SMTP-instillinger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['expert_legend']   = 'Ekspert instillinger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['template_legend'] = 'Skabelon instillinger';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['new']         = array('Ny kategori ',' Opret en ny kategori');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['show']        = array('Kategori detaljer','Kategoriens detaljer ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['copy']        = array('Kopier kategori ', ' Kopier kategoriens ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['delete']      = array('Slet kategori', 'Slet kategoriens ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['edit']        = array('Rediger kategori', 'Rediger kategoriens ID %s');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['editheader']  = array('Rediger Kategoriens Header', 'Rediger header på kategorien ID %s');

?>