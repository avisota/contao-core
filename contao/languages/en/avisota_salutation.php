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
	'salutation'                 => 'Dear Sir ##title## ##forename## ##surname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'male',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('title', 'forename', 'surname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Madam ##title## ##forename## ##surname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'female',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('title', 'forename', 'surname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Sir ##forename## ##surname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'male',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('forename', 'surname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Madam ##forename## ##surname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'female',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('forename', 'surname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Sir ##surname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'male',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('surname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Madam ##surname##',
	'enableGenderFilter'         => true,
	'genderFilter'               => 'female',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('surname'),
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Sir or Madam ##title## ##forename## ##surname##',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('title', 'forename', 'surname')
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Sir or Madam ##forename## ##surname##',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('forename', 'surname')
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation'                 => 'Dear Sir or Madam ##surname##',
	'enableRequiredFieldsFilter' => true,
	'requiredFieldsFilter'       => array('surname')
);
$GLOBALS['TL_LANG']['avisota_salutation'][] = array(
	'salutation' => 'Dear subscriber'
);
