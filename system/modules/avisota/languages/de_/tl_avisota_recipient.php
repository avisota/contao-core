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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient']['confirmed']               = array(
	'Bestätigt',
	'Hier können Sie die E-Mail Adresse als bestätigt markieren.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['email']                   = array(
	'E-Mail',
	'Hier können Sie die E-Mail Adresse des Abonnenten angeben.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['lists']                   = array(
	'Verteiler',
	'Wählen Sie hier die zu abonnierenden Verteiler aus.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscriptionAction']      = array(
	'Abonnementaktivierung',
	'Wählen Sie hier wie die Aktivierung des Abonnements für neu ausgewählte Verteiler durchgeführt werden soll.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['salutation']              = array(
	'Anrede',
	'Hier können Sie die Anrede auswählen.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['title']                   = array(
	'Titel',
	'Hier können Sie den Titel eingeben.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['forename']               = array(
	'Vorname',
	'Hier können Sie den Vornamen eingeben.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['surname']                = array(
	'Nachname',
	'Hier können Sie den Nachnamen eingeben.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['gender']                  = array(
	'Geschlecht',
	'Bitte wählen Sie das Geschlecht.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['permitPersonalTracing']   = array(
	'Personenbezogene Profilbildung',
	'Der Abonnent hat seine Erlaubnis zur Erfassung eines personenbezogenen Profils erteilt.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['permitPersonalTracingFE'] = array(
	'Datenschutz',
	'Ja, ich willige der Erhebung, Verarbeitung und Nutzung meiner personenbezogenen Daten gemäß der <a href="%s" onclick="window.open(this.href); return false;">Datenschutzrichtlinie</a> ein.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['token']                   = array(
	'Token',
	'Der Auth-Token wird für das Double-Opt-In Verfahren genutzt.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedOn']                 = array(
	'Eintragungszeitpunkt',
	'Der Zeitpunkt, an dem das Abonnement hinzugefügt wurde.',
	'hinzugefügt am %s'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['addedBy']                 = array(
	'Hinzugefügt von',
	'Der Benutzer, der den Abonnenten hinzugefügt hat, falls dieser sich nicht selbst eingetragen hat.',
	' von %s',
	'einen gelöschten Benutzer'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient']['recipient_legend']    = 'Abonnent';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscription_legend'] = 'Abonnement';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['personals_legend']    = 'Persönliche Daten';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['tracing_legend']      = 'Nachverfolgung';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient']['confirm']                 = '%s neue Abonnenten wurden importiert.';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['invalid']                 = '%s ungültige Einträge wurden übersprungen.';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['subscribed']              = 'registriert am %s';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['manually']                = 'manuell hinzugefügt';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['confirmManualActivation'] = 'Sind Sie sicher, dass Sie dieses Abonnement manuell aktivieren möchten?';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['confirmationSent']        = 'Bestätigungsmail gesendet am %s';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['reminderSent']            = 'Erinnerungsmail gesendet am %s';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['remindersSent']           = '%d. Erinnerungsmail gesendet am %s';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['sendConfirmation']        = 'Bestätigungsmail senden';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['activateSubscription']    = 'Abonnement direkt aktivieren';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['doNothink']               = 'Abonnement unbestätigt eintragen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient']['new']                 = array(
	'Neuer Abonnent',
	'Einen neuen Abonnent erstellen'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['show']                = array(
	'Abonnentendetails',
	'Details des Abonnenten ID %s anzeigen'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['copy']                = array(
	'Abonnent duplizieren',
	'Abonnent ID %s duplizieren'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['delete']              = array(
	'Abonnent löschen',
	'Abonnent ID %s löschen'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['delete_no_blacklist'] = array(
	'Abonnent löschen ohne Blacklist Eintrag',
	'Abonnent ID %s löschen ohne Ihn in die Blacklist einzutragen'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['edit']                = array(
	'Abonnent bearbeiten',
	'Abonnent ID %s bearbeiten'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['notify']              = array(
	'Abonnent benachrichtigen',
	'Abonnent Benachrichtigungen und Erinnerungen senden'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['tools']               = array(
	'Tools',
	'Abonnenten in Massen verarbeiten.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['migrate']             = array(
	'Migrieren',
	'Abonnenten aus dem Contao Newslettersystem migrieren.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['import']              = array(
	'CSV-Import',
	'Import von Abbonements aus einer CSV-Datei.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['export']              = array(
	'CSV-Export',
	'Export von Abbonements in eine CSV-Datei.'
);
$GLOBALS['TL_LANG']['orm_avisota_recipient']['remove']              = array(
	'CSV-Löschen',
	'Löschen von Abbonements aus einer CSV-Datei.'
);


/**
 * Exceptions
 */
$GLOBALS['TL_LANG']['orm_avisota_recipient']['blacklist']  = 'Der Verteiler <strong>%s</strong> befindet sich in der Blacklist, wenn Sie die Blacklist ignorieren möchten, speichern Sie erneut!';
$GLOBALS['TL_LANG']['orm_avisota_recipient']['blacklists'] = 'Die Verteiler <strong>%s</strong> befinden sich in der Blacklist, wenn Sie die Blacklist ignorieren möchten, speichern Sie erneut!';
