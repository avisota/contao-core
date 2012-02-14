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
$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['source']    = array('Newsletter', 'Wählen Sie hier die den Newsletter aus, aus dem Sie Abonnenten migrieren möchten.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['personals'] = array('Persönliche Daten übernehmen', 'Übernimmt vorhandene persönliche Daten aus der Mitgliedertabelle.');
$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['force']     = array('Migration erzwingen', 'Warnung: Sie sollten den Wunsch eines Abonnenten Ihren Newsletter nicht mehr erhalten zu wollen respektieren, es besteht die Möglichkeit dass Sie rechtliche Konsequenzen zu befürchten haben, lassen Sie sich diesbezüglich von einem Anwalt beraten! Nutzen Sie diese Option mit Bedacht und nur dann, wenn Sie wissen was Sie tun! Wählen Sie diese Option, wird die interne Blacklist ignoriert. Die Blacklist sorgt dafür, dass Abonnenten die sich abgemeldet haben nicht durch den Import wieder hinzugefügt werden.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['migrated'] = '%s Abonnenten wurden migriert.';
$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['edit']     = 'Abonnenten migrieren';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['migrate_legend'] = 'Abonnenten migrieren';

?>