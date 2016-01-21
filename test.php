<?php
/**
 * FRAMEWORK
 *
 * Copyright (C) FRAMEWORK
 *
 * @package   contao-core
 * @file      test.php
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2016 owner
 */



include "contao/languages/en/avisota_promotion.php";

foreach ($TL_LANG['avisota_promotion'] as &$lang) {
    $lang = htmlspecialchars($lang);
}

echo "";

