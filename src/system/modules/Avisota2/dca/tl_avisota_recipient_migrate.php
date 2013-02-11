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
 *
 * @copyright  InfinitySoft 2010,2011,2012
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
	'config'       => array
	(
		'dataContainer'     => 'Memory',
		'closed'            => true,
		'onsubmit_callback' => array
		(
			array('tl_avisota_recipient_migrate', 'onsubmit_callback'),
		)
	),
	// Palettes
	'metapalettes' => array
	(
		'default' => array
		(
			'migrate' => array('source', 'personals', 'force')
		)
	),
	// Fields
	'fields'       => array
	(
		'source'    => array
		(
			'label'      => &$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['source'],
			'inputType'  => 'checkbox',
			'foreignKey' => 'tl_newsletter_channel.title',
			'eval'       => array('mandatory' => true, 'multiple' => true)
		),
		'personals' => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['personals'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12')
		),
		'force'     => array
		(
			'label'     => &$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['force'],
			'inputType' => 'checkbox',
			'eval'      => array('tl_class' => 'm12')
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
		$sourceIds    = array_filter(array_map('intval', $dc->getData('source')));
		$usePersonals = $dc->getData('personals') ? true : false;
		$force     = $dc->getData('force') ? true : false;

		if (count($sourceIds)) {
			$source = implode(',', $sourceIds);

			$insertPersonals = '';
			$selectPersonals = '';
			if ($usePersonals) {
				$this->loadDataContainer('tl_avisota_recipient');
				$this->loadDataContainer('tl_member');
				foreach ($GLOBALS['TL_DCA']['tl_avisota_recipient']['fields'] as $fieldName => $fieldConfig) {
					if ( // do not add default fields
						!in_array(
							$fieldName,
							array('pid', 'tstamp', 'email', 'confirmed', 'addedOn', 'addedBy', 'token')
						)
						// only add importable fields
						&& isset($fieldConfig['eval']['importable'])
						&& $fieldConfig['eval']['importable']
						// only add fields, that are exists in tl_member table
						&& isset($GLOBALS['TL_DCA']['tl_member']['fields'][$fieldName])
					) {
						$insertPersonals .= ',' . $fieldName;
						$selectPersonals .= ',IFNULL(m.' . $fieldName . ', "")';
					}
				}
			}

			$stmt = $this->Database
				->prepare(
				"INSERT INTO
						tl_avisota_recipient (pid,tstamp,email" . $insertPersonals . ",confirmed,addedOn,addedBy,token)
					SELECT
						?,r.tstamp,r.email" . $selectPersonals . ",r.active,?,?,r.token
					FROM
						tl_newsletter_recipients r
					" . ($usePersonals ? "
					LEFT JOIN
						tl_member m
					ON
						r.email = m.email
					" : "") . "
					WHERE
						r.pid IN ($source)
					AND
						r.email NOT IN (SELECT email FROM tl_avisota_recipient WHERE pid=?)
					" . ($force
					? ""
					: "
					AND
						MD5(r.email) NOT IN (SELECT email FROM tl_avisota_recipient_blacklist WHERE pid=?)
					")
			)
				->execute(
				$this->Input->get('id'),
				time(),
				$this->User->id,
				$this->Input->get('id'),
				$this->Input->get('id')
			);

			$_SESSION['TL_CONFIRM'][] = sprintf(
				$GLOBALS['TL_LANG']['tl_avisota_recipient_migrate']['migrated'],
				$stmt->affectedRows
			);

			if ($force) {
				$this->Database
					->prepare(
					"DELETE FROM
							tl_avisota_recipient_blacklist
						WHERE
							pid=?
						AND
							email IN (SELECT MD5(email) FROM tl_avisota_recipient WHERE pid=?)"
				)
					->execute($this->Input->get('id'), $this->Input->get('id'));
			}
		}

		setcookie('BE_PAGE_OFFSET', 0, 0, '/');
		$this->reload();
	}
}
