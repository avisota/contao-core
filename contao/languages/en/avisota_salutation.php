<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL-3.0+
 * @filesource
 */

$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Sir ##title## ##firstname## ##lastname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'male',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('title', 'firstname', 'lastname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Madam ##title## ##firstname## ##lastname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'female',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('title', 'firstname', 'lastname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Sir ##firstname## ##lastname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'male',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('firstname', 'lastname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Madam ##firstname## ##lastname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'female',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('firstname', 'lastname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Sir ##lastname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'male',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('lastname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Madam ##lastname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'female',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('lastname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Sir or Madam ##title## ##firstname## ##lastname##',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('title', 'firstname', 'lastname')
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Sir or Madam ##firstname## ##lastname##',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('firstname', 'lastname')
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Sir or Madam ##lastname##',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('lastname')
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation' => 'Dear subscriber'
);
