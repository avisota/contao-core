<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
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
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @filesource
 */


/**
 * Table tl_page
 */
$GLOBALS['TL_DCA']['tl_page']['palettes']['avisota'] = '{title_legend},title,alias,type;{redirect_legend},jumpBack;{protected_legend:hide},protected;{cache_legend:hide},includeCache;{publish_legend},published,start,stop';

$GLOBALS['TL_DCA']['tl_page']['fields']['jumpBack'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['jumpBack'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'eval'                    => array('fieldType'=>'radio')
);

?>