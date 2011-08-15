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
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['title']              = array('Titel ','Her kan du indtaste titlen på distributions gruppen.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['alias']              = array('Alias',' Aliaset er en unik reference, der kan kaldes i stedet for det numeriske ID.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['viewOnlinePage']     = array('Se Online Side ','Vælg venligst den side, som abonnenten vil blive omdirigeret til, hvis de ønsker at se nyhedsbrevet online.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['subscriptionPage']   = array('Administrer tilmeldingssiden ','Vælg en side, som abonnenten vil blive omdirigeret til, hvis de ønsker at ændre deres nyhedsbrevspræferencer.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['list_legend'] = 'Distributiongruppe';
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['expert_legend']   = 'Redirect Indstillinger';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['new']         = array('Ny distributionsliste','Tilføj en ny distributørliste');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['show']        = array('Distributions Detaljer','Nærmere oplysninger om distribution ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['copy']        = array('Kopier Distribution', 'Kopier distribution ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['delete']      = array('Slet Distribution', 'Slet distribution ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['edit']        = array('Rediger Distribution', 'Rediger detaljer for distribution ID %s');
$GLOBALS['TL_LANG']['tl_avisota_recipient_list']['editheader']  = array('Rediger Header', 'Edit headeren for distribution ID %s');

?>