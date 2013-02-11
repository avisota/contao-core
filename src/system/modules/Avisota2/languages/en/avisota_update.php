<?php

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
$GLOBALS['TL_LANG']['avisota_update']['previousVersion'] = 'previous version';
$GLOBALS['TL_LANG']['avisota_update']['unknownVersion']  = 'unknown';
$GLOBALS['TL_LANG']['avisota_update']['installedVersion'] = 'installed version';
$GLOBALS['TL_LANG']['avisota_update']['moreVersionUpdates'] = 'More information about the update';
$GLOBALS['TL_LANG']['avisota_update']['doUpdate'] = 'do update';
$GLOBALS['TL_LANG']['avisota_update']['updateSuccess'] = 'Update successfull';
$GLOBALS['TL_LANG']['avisota_update']['updateFailed'] = 'Update failed, please check system log for more details';

/**
 * Update 1.6.0
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['1.6.0'] = array
(
	'Update version 1.6.0',
	'<h3>Greatest news</h3>
<ul>
	<li class="feature">New module to show a list of sended newsletter (Modul: Newsletter-List), like the News-List.</li>
	<li class="feature">New module to show a newsletter in the website (Modul: Newsletter-Reader), like the News-Reader.</li>
</ul>'
);

/**
 * Update 1.5.1
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['1.5.1'] = array
(
	'Update version 1.5.1',
	'<h3>Update notes</h3>
<ul>
	<li class="update">Clean Statistics-Database, combine links with email address.</li>
	<li class="update">Clean Statistics-Database, clean html encoded urls.</li>
</ul>'
);

/**
 * Update 1.5.0
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['1.5.0'] = array
(
	'Update version 1.5.0',
	'<h3>Update notes</h3>
<ul>
	<li class="update">Update database, improve performance of the outbox.</li>
</ul>'
);

/**
 * Update 0.4.5
 */
$GLOBALS['TL_LANG']['avisota_update']['update']['0.4.5'] = array
(
	'Update version 0.4.5',
	'<h3>Update notes</h3>
<ul>
	<li class="update">Update database for newsletter areas.</li>
</ul>'
);
