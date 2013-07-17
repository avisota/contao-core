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
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_salutation']['salutation']           = array(
	'Salutation',
	'Please enter the salutation.'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['genderFilter']         = array(
	'Gender',
	'Please select the gender that is required for this salutation.'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['requiredFieldsFilter'] = array(
	'Required fields',
	'Please select the fields that are required for this salutation.'
);
$GLOBALS['TL_LANG']['orm_avisota_salutation']['fieldValuesFilter']    = array(
	'Field value',
	'Please select and type in value filters. If you select REGEXP the value is interpreted as regular expression. For non regexp values, you can use * as wildcard.'
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
$GLOBALS['TL_LANG']['orm_avisota_salutation']['fieldValuesFilter_field'] = 'Field';
$GLOBALS['TL_LANG']['orm_avisota_salutation']['fieldValuesFilter_value'] = 'Value';
$GLOBALS['TL_LANG']['orm_avisota_salutation']['fieldValuesFilter_rgxp']  = 'REGEXP';


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
