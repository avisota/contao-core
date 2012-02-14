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
 * Avisota update
 */
$GLOBALS['TL_LANG']['avisota_update']['headline'] = 'Avisota Update';
$GLOBALS['TL_LANG']['avisota_update']['previousVersion'] = 'vorherige Version';
$GLOBALS['TL_LANG']['avisota_update']['unknownVersion']  = 'unbekannt';
$GLOBALS['TL_LANG']['avisota_update']['installedVersion'] = 'installierte Version';
$GLOBALS['TL_LANG']['avisota_update']['moreVersionUpdates'] = 'Alle Informationen zum Update';
$GLOBALS['TL_LANG']['avisota_update']['doUpdate'] = 'Aktualisierung durchführen';
$GLOBALS['TL_LANG']['avisota_update']['updateSuccess'] = 'Aktualisierung erfolgreich';
$GLOBALS['TL_LANG']['avisota_update']['updateFailed'] = 'Aktualisierung nicht erfolgreich, prüfen Sie das Systemlog für weitere Details';

/**
 * Update 1.6.0
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['1.6.0'] = array
(
	'Update Version 1.6.0',
	'<h3>Die wichtigsten Neuerungen</h3>
<ul>
	<li class="feature">Neues Modul zur Auflistung der Newsletter (Modul: Newsletter-Liste), ähnlich einer News-Liste.</li>
	<li class="feature">Neues Modul zum Anzeigen eines Newlsetters in der Website (Modul: Newsletter-Reader), ähnlich einem News-Reader.</li>
</ul>'
);

/**
 * Update 1.5.1
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['1.5.1'] = array
(
	'Update Version 1.5.1',
	'<h3>Update Hinweis</h3>
<ul>
	<li class="update">Statistik-Datenbank aufräumen und Links mit E-Mail Adresse zusammen fassen.</li>
	<li class="update">Statistik-Datenbank aufräumen und HTML kodierte URLs säubern.</li>
</ul>'
);

/**
 * Update 1.5.0
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['1.5.0'] = array
(
	'Update Version 1.5.0',
	'<h3>Update Hinweis</h3>
<ul>
	<li class="update">Datenbank aktualisieren, um Leistung des Postausgangs zu verbessern.</li>
</ul>'
);

/**
 * Update 0.4.5
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['0.4.5'] = array
(
	'Update Version 0.4.5',
	'<h3>Update Hinweis</h3>
<ul>
	<li class="update">Datenbank für Newsletter Bereiche aktualisieren.</li>
</ul>'
);
