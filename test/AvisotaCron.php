<?php

define('TL_MODE', 'FE');
include('../system/initialize.php');

header('Content-Type: text/plain');

$objAvisotaBackend = new AvisotaBackend();
$objAvisotaBackend->cronCleanupRecipientList();
$objAvisotaBackend->cronNotifyRecipients();
