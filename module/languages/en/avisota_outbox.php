<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

$GLOBALS['TL_LANG']['avisota_outbox']['headline']       = 'Outbox';
$GLOBALS['TL_LANG']['avisota_outbox']['col_name']       = 'Queue name';
$GLOBALS['TL_LANG']['avisota_outbox']['col_length']     = 'Length';
$GLOBALS['TL_LANG']['avisota_outbox']['col_action']     = '';
$GLOBALS['TL_LANG']['avisota_outbox']['action_execute'] = 'Execute queue now';

$GLOBALS['TL_LANG']['avisota_outbox']['execute']      = 'Execute queue %s';
$GLOBALS['TL_LANG']['avisota_outbox']['col_failed']   = 'failed';
$GLOBALS['TL_LANG']['avisota_outbox']['col_success']  = 'success';
$GLOBALS['TL_LANG']['avisota_outbox']['col_open']     = 'open';
$GLOBALS['TL_LANG']['avisota_outbox']['col_timeout']  = 'timeout';
$GLOBALS['TL_LANG']['avisota_outbox']['col_duration'] = 'duration';

$GLOBALS['TL_LANG']['avisota_outbox']['progress_initializing'] = 'Initializing, please stand by and do not close the window.';
$GLOBALS['TL_LANG']['avisota_outbox']['progress_running']      = 'Sending messages, please stand by and do not close the window.';
$GLOBALS['TL_LANG']['avisota_outbox']['progress_pause']        = 'Waiting for next cycle, do not close the window.';
$GLOBALS['TL_LANG']['avisota_outbox']['progress_finish']       = 'Finished send, you can now close the window.';
$GLOBALS['TL_LANG']['avisota_outbox']['progress_error']        = 'An error occurred, execution stopped!';
