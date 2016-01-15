<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Avisota compatibility controller
 */
$GLOBALS['TL_LANG']['avisotaCompatibilityController']['title']     = 'Inkompatible Systemvoraussetzungen';
$GLOBALS['TL_LANG']['avisotaCompatibilityController']['message']   = 'Diese Version von Avisota benötigt <strong>MySQL 5+</strong>,<br>
sie verwenden zur Zeit <strong>MySQL %s</strong>.<br>
<br>
Wenn Sie Avisota verwenden möchten, aktualisieren Sie den MySQL Server oder wenden Sie sich an Ihren Provider!';
$GLOBALS['TL_LANG']['avisotaCompatibilityController']['disable']   = 'Avisota deaktivieren';
$GLOBALS['TL_LANG']['avisotaCompatibilityController']['uninstall'] = 'Avisota deinstallieren';
$GLOBALS['TL_LANG']['avisotaCompatibilityController']['disabled']  = 'Avisota wurde deaktiviert';
