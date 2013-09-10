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
 * Avisota defaults
 */
$GLOBALS['TL_LANG']['avisota']['latest_link'] = '<a href="%s" target="_blank">Unser aktueller Newsletter</a>';

/**
 * Subscription
 */
$GLOBALS['TL_LANG']['avisota']['subscription']['preamble'] = 'Melden Sie sich zu unserem Newsletter an.';
$GLOBALS['TL_LANG']['avisota']['subscription']['empty']    = 'Sie sind bereits zu unserem Newsletter angemeldet.';


/**
 * Subscribe
 */
$GLOBALS['TL_LANG']['avisota']['subscribe']['submit']           = 'Abonnieren';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['subject']  = 'Newsletter Abonnement bestätigen';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['send']     = 'Sie wurden erfolgreich zu unserem Newsletter angemeldet, Sie erhalten in Kürze eine Aktivierungsmail um Ihr Abonnent zu bestätigen.';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['confirm']  = 'Ihr Abonnent für %s wurde erfolgreich aktiviert.';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['rejected'] = 'Die E-Mail Adresse %s scheint ungültig und wurde abgewiesen.';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['html']     = '<p>Sehr geehrter Interessent, wir freuen uns Sie als Abonnenten unseres Newsletters %1$s begrüßen zu dürfen.</p>
<p>Bitte öffnen Sie die folgende Adresse in Ihrem Browser, um das Abonnement zu bestätigen.<br/>
<a href="%2$s">%2$s</a></p>
<p>Vielen Dank</p>';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['plain']    = 'Sehr geehrter Interessent, wir freuen uns Sie als Abonnenten unseres Newsletters %s begrüßen zu dürfen.

Bitte öffnen Sie die folgende Adresse in Ihrem Browser, um das Abonnement zu bestätigen.
%s

Vielen Dank';

/**
 * Unsubscribe
 */
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['empty'] = 'Sie sind nicht an unserem Newsletter angemeldet.';

$GLOBALS['TL_LANG']['avisota']['unsubscribe']['submit']           = 'Kündigen';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['subject']  = 'Newsletter Abonnement gekündigt';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['confirm']  = 'Sie wurden erfolgreich aus unserem Newsletter ausgetragen.';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['rejected'] = 'Die E-Mail Adresse %s scheint ungültig und wurde abgewiesen.';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['html']     = '<p>Sehr geehrter Abonnent, Sie wurden aus unserem Newsletter %1$s ausgetragen.</p>
<p>Wir bedauern Ihre Entscheidung und würden uns freuen, Sie in Zukunft wieder als Abonnenten begrüßen zu dürfen.</p>
<p>Sie können sich jederzeit wieder an unserem Newsletter anmelden.<br/>
<a href="%2$s">%2$s</a></p>
<p>Vielen Dank</p>';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['plain']    = 'Sehr geehrter Abonnent, Sie wurden aus unserem Newsletter %1$s ausgetragen.

Wir bedauern Ihre Entscheidung und würden uns freuen, Sie in Zukunft wieder als Abonnenten begrüßen zu dürfen.

Sie können sich jederzeit wieder an unserem Newsletter anmelden.
%s

Vielen Dank';

/**
 * Notification
 */
$GLOBALS['TL_LANG']['avisota']['notification']['mail']['subject'] = 'Erinnerung - Newsletter Abonnement bestätigen';
$GLOBALS['TL_LANG']['avisota']['notification']['mail']['html']    = '<p>Sehr geehrter Interessent,<br>
wir möchten Sie daran Erinnern, dass Sie Ihr Abonnent unseres Newsletters %s noch nicht bestätigt haben. Wir können Ihnen leider erst unseren Newsletter zukommen lassen, wenn Sie Ihr Abonnement bestätigt haben.</p>
<p>Bitte öffnen Sie die folgende Adresse in Ihrem Browser, um das Abonnement zu bestätigen.<br>
<a href="%2$s">%2$s</a></p>
<p>Vielen Dank</p>';
$GLOBALS['TL_LANG']['avisota']['notification']['mail']['plain']   = 'Sehr geehrter Interessent,
wir möchten Sie daran Erinnern, dass Sie Ihr Abonnent unseres Newsletters %s noch nicht bestätigt haben. Wir können Ihnen leider erst unseren Newsletter zukommen lassen, wenn Sie Ihr Abonnement bestätigt haben.

Bitte öffnen Sie die folgende Adresse in Ihrem Browser, um das Abonnement zu bestätigen.
%s

Vielen Dank';

/**
 * Reader
 */
$GLOBALS['TL_LANG']['avisota']['reader']['notFound'] = 'Der gewünschte Newsletter konnte nicht gefunden werden!';
