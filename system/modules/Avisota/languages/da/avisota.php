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
 * Avisota defaults
 */
$GLOBALS['TL_LANG']['avisota']['latest_link'] = '<a href="%s" target="_blank">Se tidligere nyhedsbreve</a>';

$GLOBALS['TL_LANG']['avisota']['subscription']['preamble'] = 'Tilmeld dig vores nyhedsbrev.';
$GLOBALS['TL_LANG']['avisota']['subscription']['lists']    = 'Distribution';
$GLOBALS['TL_LANG']['avisota']['subscription']['email']    = 'E-Mail Adresse';
$GLOBALS['TL_LANG']['avisota']['subscription']['empty']    = 'Er du allerede tilmeldt vores nyhedsbrev?';

$GLOBALS['TL_LANG']['avisota']['subscribe']['submit']              = 'Tilmeld';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['subject']     = 'Bekræft tilmelding af nyhedsbrev';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['send']        = 'Du er nu tilmeldt vores nyhedsbrev og vil modtage en aktiverings e-mail for at bekræfte dit abonnement.';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['confirm']     = 'Dit abonnement på %s er aktiveret.';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['rejected']    = 'Denne E-Mail adresse %s er ugyldig, og er blevet afvist.';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['html']        = '<p>Kære læser, vi er glade for at byde dig velkommen til nyhedsbrevet,  %1$s</p>
<p>Klik venligst på følgende link for at bekræfte abonnementet.<br/>
<a href="%2$s">%2$s</a></p>
<p>Mange tak!</p>';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['plain']       = 'Kære læser, vil vi gerne byde dig velkommen til vores nyhedsbrev %s.

Klik venligst på følgende link for at bekræfte abonnementet.
%s

Mange tak!';

$GLOBALS['TL_LANG']['avisota']['unsubscribe']['empty'] = 'Du er ikke logget ind i vores nyhedsbrev.';

$GLOBALS['TL_LANG']['avisota']['unsubscribe']['submit']            = 'Fortryd';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['subject']   = 'Du vil fremover ikke modtage nyhedsbrevet';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['confirm']   = 'Du er nu oprettet som modtager af nyhedsbrevet.';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['rejected']  = 'E-Mail adressen %s er ikke gyldig og er derfor afvist.';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['html']      = '<p>Kære læser, du er nu afmeldt fra nyhedsbrevet  %1$s.</p>
<p>Vi er kede af din beslutning om at afmelde dig fra vores nyhedsbrev %1$s.  Skulle der opstå problemer fremover med vores nyhedsbrev, eller ønsker du at give os forslag til, hvordan vi kan forbedre det, er du velkommen til at kontakte os. Tak for interessen.</p>
<p>Du kan altid tilmelde dig vores nyhedsbrev igen på:<br/>
<a href="%2$s">%2$s</a></p>
<p>Tak!</p>';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['plain']     = 'Kære læser, du er nu afmeldt vores nyhedsbrev %1$s.

Vi er kede af din beslutning om at afmelde dig fra vores nyhedsbrev %1$s.  Skulle der opstå problemer fremover med vores nyhedsbrev, eller ønsker du at give os forslag til, hvordan vi kan forbedre det, er du velkommen til at kontakte os. Tak for interessen.

Du kan altid tilmelde dig vores nyhedsbrev igen på:
%s

Tak!';
