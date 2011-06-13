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
 * Table tl_avisota_recipient_migrate
 */
$GLOBALS['TL_DCA']['tl_avisota_recipient_migrate'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Memory',
		'closed'                      => true,
		'onsubmit_callback'           => array
		(
			array('tl_avisota_recipient_migrate', 'onsubmit_callback'),
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{migrate_legend},source,force'
	),
	
	// Fields
	'fields' => array
	(
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['source'],
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_newsletter_channel.title',
			'eval'                    => array('mandatory'=>true, 'multiple'=>true)
		),
		'force' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['force'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'m12')
		)
	)
);

class tl_avisota_recipient_migrate extends Backend
{
	/**
	 * migrate the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
		
	
	/**
	 * Do the migration.
	 * 
	 * @param DataContainer $dc
	 */
	public function onsubmit_callback(DataContainer $dc)
	{
		$arrSource = array_filter(array_map('intval', $dc->getData('source')));
		$blnForce = $dc->getData('force') ? true : false;
		
		if (count($arrSource))
		{
			$strSource = implode(',', $arrSource);
			
			$objStmt = $this->Database->prepare("INSERT INTO
						tl_avisota_recipient (pid,tstamp,email,confirmed,addedOn,token)
					SELECT
						?,tstamp,email,active,addedOn,token
					FROM
						tl_newsletter_recipients
					WHERE
						tl_newsletter_recipients.pid IN ($strSource)
					AND
						tl_newsletter_recipients.email NOT IN (SELECT email FROM tl_avisota_recipient WHERE pid=?)" . ($blnForce ? '' : "
					AND
						MD5(tl_newsletter_recipients.email) NOT IN (SELECT email FROM tl_avisota_recipient_blacklist WHERE pid=?)"))
				->execute($this->Input->get('id'), $this->Input->get('id'), $this->Input->get('id'));
			
			$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['migrated'], $objStmt->affectedRows);
			
			if ($blnForce)
			{
				$this->Database->prepare("DELETE FROM
							tl_avisota_recipient_blacklist
						WHERE
							pid=?
						AND
							email IN (SELECT MD5(email) FROM tl_avisota_recipient WHERE pid=?)")
					->execute($this->Input->get('id'), $this->Input->get('id'));
			}
		}
		
		setcookie('BE_PAGE_OFFSET', 0, 0, '/');
		$this->reload();
	}
}

?>