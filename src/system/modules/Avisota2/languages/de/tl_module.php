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
 * @author     Oliver Hoff <oliver@hofff.com>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['avisota_show_lists']            = array(
	'Verteilerauswahl anzeigen',
	'Zeigt eine Auswahl der Verteiler im Frontend an.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_lists']                 = array(
	'Verteiler',
	'Wählen Sie hier die Verteiler aus, zu denen man sich anmelden kann.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_selectable_lists']      = array(
	'Avisota Verteiler',
	'Wählen Sie hier die Verteiler aus, die zur Auswahl stehen bzw. abonniert werden (beim Feld Newsletter abonnieren).'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_recipient_fields']      = array(
	'Persönliche Daten',
	'Wählen Sie hier die persönlichen Felder aus, die ein Abonnent zusätzlich angeben kann.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscription'] = array(
	'Formulartemplate',
	'Hier können Sie das Formulartemplate auswählen. Das Template muss den Prefix <strong>subscription_</strong> haben.'
);

$GLOBALS['TL_LANG']['tl_module']['avisota_send_notification']                = array(
	'Erinnerung senden',
	'Sendet eine Erinnerung, wenn das Abonnement nach einigen Tagen noch nicht aktiviert wurde.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_notification_time']                = array(
	'Tage bis zur Erinnerung',
	'Anzahl Tage nach denen die Erinnerung verschickt werden soll.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_notification_mail_plain'] = array(
	'Erinnerung Plain Text E-Mail-Template',
	''
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_notification_mail_html']  = array(
	'Erinnerung HTML E-Mail-Template',
	''
);

$GLOBALS['TL_LANG']['tl_module']['avisota_do_cleanup']   = array(
	'Unbestätige Abonnements löschen',
	'Löscht unbestätigte Abonnements nach einigen Tagen.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_cleanup_time'] = array(
	'Tage bis zur Löschung',
	'Anzahl Tage nach denen das Abonnement wieder gelöscht wird.'
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
$GLOBALS['TL_LANG']['tl_module']['avisota_form_target']                   = array(
	'Formular Zielseite',
	'Wählen Sie hier eine Seite aus, an die das Formular die Daten schickt (action-Attribut).'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_subscribe']            = array(
	'Form-Template',
	'Wählen Sie hier das Template für das Anmelden-Formular aus.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_template_unsubscribe']          = array(
	'Form-Template',
	'Wählen Sie hier das Template für das Abmelden-Formular aus.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_subscribe_confirmation_page']   = array(
	'Weiterleitungsseite nach Anmeldung',
	'Bitte wählen Sie die Seite aus, zu der Besucher nach erfolgreicher Anmeldung weitergeleitet wird.'
);
$GLOBALS['TL_LANG']['tl_module']['avisota_unsubscribe_confirmation_page'] = array(
	'Weiterleitungsseite nach Abmeldung',
	'Bitte wählen Sie die Seite aus, zu der Besucher nach erfolgreicher Abmeldung weitergeleitet wird.'
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
