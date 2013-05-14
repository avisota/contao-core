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
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['title']            = array(
	'Titel',
	'Hier können Sie den Titel des Verteilers angeben.'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['alias']            = array(
	'Alias',
	'Der Alias ist eine eindeutige Referenz, die anstelle der numerischen Id aufgerufen werden kann.'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['viewOnlinePage']   = array(
	'Online-Ansehen Seite',
	'Bitte wählen Sie die Newsletterleser-Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Newsletter online ansehen wollen.'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['subscriptionPage'] = array(
	'Abonnement-Verwalten Seite',
	'Bitte wählen Sie die Abonnement-Verwalten Seite aus, zu der Besucher weitergeleitet werden, wenn Sie einen Newsletter kündigen wollen.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['list_legend']   = 'Verteiler';
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['expert_legend'] = 'Experten-Einstellungen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['new']    = array('Neuer Verteiler', 'Einen neuen Verteiler erstellen');
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['show']   = array(
	'Verteilerdetails',
	'Details der Verteilers ID %s anzeigen'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['copy']   = array(
	'Verteiler duplizieren',
	'Verteiler ID %s duplizieren'
);
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['delete'] = array('Verteiler löschen', 'Verteiler ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['edit']   = array(
	'Verteilereinstellungen bearbeiten',
	'Einstellungen der Verteilers ID %s bearbeiten'
);


/**
 * Label
 */
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['label_recipients'] = '%1$d Abonnenten (<span title="%2$d aktive Abonnenten">%2$d</span> / <span title="%3$d inaktive Abonnenten">%3$d</span>)';
$GLOBALS['TL_LANG']['tl_avisota_mailing_list']['label_members']    = '%1$d Mitglieder (<span title="%2$d aktive Mitglieder">%2$d</span> / <span title="%3$d inaktive Mitglieder">%3$d</span>)';