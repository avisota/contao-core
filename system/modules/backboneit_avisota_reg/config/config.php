<?php

$GLOBALS['FE_MOD']['avisota']['backboneit_avisota_reg']
	= 'ModuleAvisotaReg';

$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('AvisotaRegDCA', 'loadDataContainer');
$GLOBALS['TL_HOOKS']['createNewUser'][] = array('AvisotaRegDCA', 'createNewUser');
$GLOBALS['TL_HOOKS']['activateAccount'][] = array('AvisotaRegDCA', 'activateAccount');
