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
 * Avisota compatibility controller
 */
$GLOBALS['TL_LANG']['avisotaCompatibilityController']['title']     = 'Inkompatible Systemvoraussetzungen';
$GLOBALS['TL_LANG']['avisotaCompatibilityController']['message']   = 'Diese Version von Avisota benötigt <strong>MySQL 5+</strong>,<br>
sie verwenden zur Zeit <strong>MySQL %s</strong>.<br>
<br>
Wenn Sie Avisota verwenden möchten, aktualisieren Sie den MySQL Server oder wenden Sie sich an Ihren Provider!';
$GLOBALS['TL_LANG']['avisotaCompatibilityController']['disable']   = 'Avisota deaktivieren';
$GLOBALS['TL_LANG']['avisotaCompatibilityController']['uninstall'] = 'Avisota deinstallieren';
$GLOBALS['TL_LANG']['avisotaCompatibilityController']['disabled']  = 'Avisota wurde deaktiviert';
