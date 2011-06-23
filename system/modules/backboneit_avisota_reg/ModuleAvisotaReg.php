<?php

class ModuleAvisotaReg extends ModuleRegistration {
	
	protected function loadDataContainer($strTable) {
		parent::loadDataContainer($strTable);
		$this->import('AvisotaRegDCA');
		$this->AvisotaRegDCA->setSelectableLists($this->backboneit_avisota_reg_lists);
		$this->AvisotaRegDCA->setMemberActivation($this->reg_activate);
	}
	
}
