<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_salutation']['salutation']           = array(
	'Salutation',
	'Please enter the salutation.'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['enableGenderFilter']         = array(
	'Filter by Gender',
	'Choose to enable this salutation only for a specific gender.'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['genderFilter']         = array(
	'Gender',
	'Please select the gender that is required for this salutation.'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['enableRequiredFieldsFilter']         = array(
	'Filter by required fields',
	'Choose to enable this salutation only if selected fields are filled.'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['requiredFieldsFilter'] = array(
	'Required fields',
	'Please select the fields that are required for this salutation.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_salutation']['salutation_legend'] = 'Salutation';
$GLOBALS['TL_LANG']['orm_avisota_salutation']['filter_legend']     = 'Filter settings';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_salutation']['gender']['male']          = 'Male';
$GLOBALS['TL_LANG']['orm_avisota_salutation']['gender']['female']        = 'Female';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_salutation']['new']        = array(
	'New salutation',
	'Create a new salutation'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['show']       = array(
	'Salutation details',
	'Show the details of salutation ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['edit']       = array(
	'Edit salutation',
	'Edit salutation ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['editheader'] = array(
	'Edit salutation settings',
	'Edit salutation settings ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['copy']       = array(
	'Duplicate salutation',
	'Duplicate salutation ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['delete']     = array(
	'Delete salutation',
	'Delete salutation ID %s'
);
