<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['title']              = array('Titel', 'Hier können Sie den Titel der Kategorie angeben.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['alias']              = array('Kategoriealias', 'Der Kategoriealias ist eine eindeutige Referenz, die anstelle der numerischen Kategoriealias-Id aufgerufen werden kann.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['tstamp']             = array('Änderungsdatum', 'Datum und Uhrzeit der letzten Änderung');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['areas']              = array('Bereiche', 'Komma-getrennte Liste von zusätzlichen Newsletterbereichen (z.B. header,left,right,footer).');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipients']         = array('Empfänger', 'Wählen Sie hier die Empfänger aus.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['force_recipients']   = array('Empfänger vorgeben', 'Wählen Sie diese Option können in einem Newsletter keine individuellen Empfänger ausgewählt werden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['template_html']      = array('HTML E-Mail-Template', 'Hier können Sie das HTML E-Mail-Template auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['template_plain']     = array('Plain Text E-Mail-Template', 'Hier können Sie das Plain Text E-Mail-Template auswählen.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['force_template']     = array('Template vorgeben', 'Wählen Sie diese Option können in einem Newsletter keine individuellen Templates ausgewählt werden.');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['stylesheets']        = array('Stylesheets', 'Stylesheets, die in den Newsletter eingebunden werden sollen.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['category_legend'] = 'Kategorie';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['expert_legend']   = 'Experten-Einstellungen';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['recipient_legend']   = 'Empfänger';
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['template_legend'] = 'Template-Einstellungen';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['new']         = array('Neue Kategorie', 'Eine neue Liste erstellen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['show']        = array('Kategoriedetails', 'Details der Kategorie ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['copy']        = array('Kategorie duplizieren', 'Kategorie ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['delete']      = array('Kategorie löschen', 'Kategorie ID %s löschen');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['edit']        = array('Kategorie bearbeiten', 'Kategorie ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_avisota_newsletter_category']['editheader']  = array('Kategorieeinstellungen bearbeiten', 'Einstellungen der Kategorie ID %s bearbeiten');

?>