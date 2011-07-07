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
		'default'                     => '{migrate_legend},source,personals,force'
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
		'personals' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['personals'],
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'m12')
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
		$blnPersonals = $dc->getData('personals') ? true : false;
		$blnForce = $dc->getData('force') ? true : false;
		
		if (count($arrSource))
		{
			$strSource = implode(',', $arrSource);
			
			$strInsertPersonals = '';
			$strSelectPersonals = '';
			if ($blnPersonals)
			{
				$this->loadDataContainer('tl_avisota_recipient');
				$this->loadDataContainer('tl_member');
				foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $strField => $arrField)
				{
					if (	// do not add default fields
							!in_array($strField, array('pid', 'tstamp', 'email', 'confirmed', 'addedOn', 'addedBy', 'token'))
							// only add importable fields
						&&	isset($arrField['eval']['importable'])
						&&	$arrField['eval']['importable']
							// only add fields, that are exists in tl_member table
						&&	isset($GLOBALS['TL_DCA']['tl_member']['fields'][$strField]))
					{
						$strInsertPersonals .= ',' . $strField;
						$strSelectPersonals .= ',IFNULL(m.' . $strField . ', "")';
					}
				}
			}
			
			$objStmt = $this->Database->prepare("INSERT INTO
						tl_avisota_recipient (pid,tstamp,email" . $strInsertPersonals . ",confirmed,addedOn,addedBy,token)
					SELECT
						?,r.tstamp,r.email" . $strSelectPersonals . ",r.active,?,?,r.token
					FROM
						tl_newsletter_recipients r
					" . ($blnPersonals ? "
					LEFT JOIN
						tl_member m
					ON
						r.email = m.email
					" : "") . "
					WHERE
						r.pid IN ($strSource)
					AND
						r.email NOT IN (SELECT email FROM tl_avisota_recipient WHERE pid=?)
					" . ($blnForce ? "" : "
					AND
						MD5(r.email) NOT IN (SELECT email FROM tl_avisota_recipient_blacklist WHERE pid=?)
					"))
				->execute($this->Input->get('id'), time(), $this->User->id, $this->Input->get('id'), $this->Input->get('id'));
			
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