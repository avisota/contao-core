<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['email']       = array('E-Mail', 'Hier können Sie die E-Mail Adresse des Abonnenten angeben.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['firstname']   = array('Vorname', 'Hier können Sie den Vornamen eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['lastname']    = array('Nachname', 'Hier können Sie den Nachnamen eingeben.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['gender']      = array('Geschlecht', 'Bitte wählen Sie das Geschlecht.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['confirmed']   = array('Bestätigt', 'Hier können Sie die E-Mail Adresse als bestätigt markieren.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['token']       = array('Token', 'Der Auth-Token wird für das Double-Opt-In Verfahren genutzt.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['addedOn']     = array('Registrierungsdatum', 'Das Datum des Abonnements.');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['source']  	   = array('Quelldateien', 'Bitte wählen Sie die zu importierenden CSV-Dateien aus der Dateiübersicht.');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['recipient_legend'] = 'Abonnent';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['subscribed'] = 'registriert am %s';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['manually']   = 'manuell hinzugefügt';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['confirm']    = '%s neue Abonnenten wurden importiert.';
$GLOBALS['TL_LANG']['tl_avisota_recipient']['invalid']    = '%s ungültige Einträge wurden übersprungen.';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_recipient']['new']         = array('Neuer Abonnent', 'Einen neuen Abonnent erstellen');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['show']        = array('Abonnentendetails', 'Details des Abonnenten ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['copy']        = array('Abonnent duplizieren', 'Abonnent ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['delete']      = array('Abonnent löschen', 'Abonnent ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['edit']        = array('Abonnent bearbeiten', 'Abonnent ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_recipient']['import']	   = array('CSV-Import','Import von Abbonements aus einer CSV-Datei.')
?>