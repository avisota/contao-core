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
$GLOBALS['TL_LANG']['avisota']['latest_link'] = '<a href="%s" target="_blank">Our Current Newsletter</a>';

$GLOBALS['TL_LANG']['avisota']['subscription']['preamble'] = 'Sign Up For Our Newsletter.';
$GLOBALS['TL_LANG']['avisota']['subscription']['lists']    = 'Distribution';
$GLOBALS['TL_LANG']['avisota']['subscription']['email']    = 'E-Mail Address';
$GLOBALS['TL_LANG']['avisota']['subscription']['empty']    = 'Are you already registered for our Newsletter?';

$GLOBALS['TL_LANG']['avisota']['subscribe']['submit']           = 'Subscribe';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['subject']  = 'Newsletter Subscription Confirmation';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['send']     = 'You are successfully logged into our mailing list and you will receive an activation email to confirm your subscription.';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['confirm']  = 'Your subscription for %s was successfully activated.';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['rejected'] = 'This email address %s is invalid and has been dismissed.';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['html']     = '<p>Dear Subscriber, we are pleased to welcome you to our Newsletter,  %1$s may be welcome.</p>
<p>Please open the following link in your browser to confirm the subscription.<br/>
<a href="%2$s">%2$s</a></p>
<p>Thank You!</p>';
$GLOBALS['TL_LANG']['avisota']['subscribe']['mail']['plain']    = 'Dear member, we would like to welcome you to our Newsletter %s.

Please open the following link in your browser to confirm the subscription.
%s

Thank You!';

$GLOBALS['TL_LANG']['avisota']['unsubscribe']['empty'] = 'You are not logged into our Newsletter.';

$GLOBALS['TL_LANG']['avisota']['unsubscribe']['submit']           = 'Cancel';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['subject']  = 'Newsletter Subscription Cancelled';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['confirm']  = 'They were held successfully in our newsletter.';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['rejected'] = 'The email address %s is invalid and has been dismissed.';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['html']     = '<p>Dear subscriber, you have been removed from the Newsletter %1$s.</p>
<p>We regret your decision for removing yourself from our Newsletter %1$s.  If there are any problems with our Newsletter, or you wish to give us some suggestions on how we can improve upon it, please feel free to contact the web master from our "Contact Us" page linked on our home page.  We look forward to serving you in the future.</p>
<p>You may always sign back up for our Newsletter at:<br/>
<a href="%2$s">%2$s</a></p>
<p>Thank You!</p>';
$GLOBALS['TL_LANG']['avisota']['unsubscribe']['mail']['plain']    = 'Dear subscriber, you have been removed from our Newsletter %1$s.

We regret your decision for removing yourself from our Newsletter %1$s.  If there are any problems with our Newsletter, or you wish to give us some suggestions on how we can improve upon it, please feel free to contact the web master from our "Contact Us" page linked on our home page.  We look forward to serving you in the future.

You may always sign back up for our Newsletter at:
%s

Thank You!';
