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
$GLOBALS['TL_LANG']['tl_module']['avisota_show_lists']            = array(
	'Show mailing lists',
	'Show the mailing lists and make possible it possible for the recipient to choose the mailing lists to subscribe.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_lists']                 = array(
	'Mailing lists',
	'Please choose the mailing lists, that will be subscribed or shown.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_recipient_fields']      = array(
	'Personal data',
	'Pleas choose additional personal data fields to ask for.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscribe']            = array(
	'Formular template',
	'Please choose a custom formular template.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_form_target']   = array(
	'Formular target page (not confirmation page!)',
	'Please choose a page, the submitted form data will be posted.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_subscribe_confirmation_page']   = array(
	'Subscription confirmation page',
	'Please choose the page, that will be shown for successful subscription.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_unsubscribe_confirmation_page'] = array(
	'Unsubscribe confirmation page',
	'Please choose the page, that will be show for successful unsubscribe.'
);



$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscription'] = array(
	'Formular template',
	'Please choose a custom formular template.'
);




$GLOBALS['TL_LANG']['tl_module']['avisota_categories']                    = array(
	'Kategorien',
	'Wählen Sie die Kategorien aus, aus denen die Newsletter angezeigt werden sollen.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_reader_template']               = array(
	'Leser-Template',
	'Wählen Sie hier das Template für den Newsletter-Leser aus.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_list_template']                 = array(
	'Listen-Template',
	'Wählen Sie hier das Template für die Newsletter-Liste aus.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_view_page']                     = array(
	'Ansichtsseite',
	'Wählen Sie hier eine Seite aus, auf der die Newsletter angezeigt werden soll. Wird keine Seite ausgewählt, wird die in der Kategorie hinterlegte Seite zur Online-Ansicht verwendet.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe']          = array(
	'Form-Template',
	'Wählen Sie hier das Template für das Abmelden-Formular aus.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_module']['avisota_subscription_legend'] = 'Abonnement';
$GLOBALS['TL_LANG']['tl_module']['avisota_mail_legend']         = 'Mail Einstellungen';
$GLOBALS['TL_LANG']['tl_module']['avisota_notification_legend'] = 'Erinnerung';
$GLOBALS['TL_LANG']['tl_module']['avisota_cleanup_legend']      = 'Aufräumen';
$GLOBALS['TL_LANG']['tl_module']['avisota_reader_legend']       = 'Newsletter-Leser';
$GLOBALS['TL_LANG']['tl_module']['avisota_list_legend']         = 'Newsletter-Liste';
