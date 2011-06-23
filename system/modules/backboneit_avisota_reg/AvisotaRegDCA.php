<?php

class AvisotaRegDCA extends Controller {
	
	private $blnMemberActivation;

	public function setMemberActivation($blnMemberActivation) {
		$this->blnMemberActivation = $blnMemberActivation;
	}
	
	public function createNewUser($intMemberID, $arrMemberData) {
		if(strlen($arrMemberData['email']) < 5)
			return;
		if(!$this->blnMemberActivation)
			$this->subscribe($arrMemberData['email'], deserialize($arrMemberData['backboneit_avisota_reg_lists'], true));
	}
	
	public function activateAccount($objMember) {
		if(strlen($objMember->email) < 5)
			return;
		$this->subscribe($objMember->email, deserialize($objMember->backboneit_avisota_reg_lists, true));
	}
	
	protected function subscribe($strEmail, $arrLists) {
		if(!$arrLists)
			return;
			
		$objPrepared = $this->Database->prepare('
			SELECT	r.confirmed, r.id AS rid
			FROM	tl_avisota_recipient_list AS l
			LEFT JOIN (
				SELECT	r1.confirmed, r1.id, r1.pid
				FROM	tl_avisota_recipient AS r1
				WHERE	r1.email = ?
			) AS r ON l.id = r.pid
			WHERE l.id = ?
		');
		$intTime = time();
		
		foreach($arrLists as $intListID) {
			$objAlreadySubscribed = $objPrepared->execute($strEmail, $intListID);
			
			if(!$objAlreadySubscribed->numRows) // list doesnt exist
				continue;
				
			$arrData = array(
				'email' => $strEmail,
				'confirmed' => 1,
				'tstamp' => $intTime
			);
				
			if(!$objAlreadySubscribed->rid) { // no existing subscription
				$arrData['pid'] = $intListID;
				$arrData['addedOn'] = $intTime;
				$this->Database->prepare(
					'INSERT INTO tl_avisota_recipient %s'
				)->set($arrData)->execute();
				
			} elseif(!$objAlreadySubscribed->confirmed) { // unconfirmed subscription found
				$arrData['token'] = '';
				$this->Database->prepare(
					'UPDATE tl_avisota_recipient %s WHERE id = ?'
				)->set($arrData)->execute($objAlreadySubscribed->rid);
			}
		}
	}
	
	private $arrSelectableLists;
	
	public function setSelectableLists($arrSelectableLists) {
		$this->arrSelectableLists = deserialize($arrSelectableLists);
	}
	
	public function getSelectableLists() {
		if(!$this->arrSelectableLists)
			return;
		
		$objLists = $this->Database->execute('
			SELECT		id, title
			FROM		tl_avisota_recipient_list
			WHERE		id IN (' . implode(',', array_map('intval', $this->arrSelectableLists)) . ')
			ORDER BY	title
		');
		
		$arrOptions = array();
		while($objLists->next())
			$arrOptions[$objLists->id] = $objLists->title;
			
		return $arrOptions;
	}
	
	public function getLists() {
		$objLists = $this->Database->execute(
			'SELECT id, title FROM tl_avisota_recipient_list ORDER BY title'
		);
		
		$arrOptions = array();
		while($objLists->next())
			$arrOptions[$objLists->id] = $objLists->title;
			
		return $arrOptions;
	}
	
	public function loadDataContainer($strTable) {
		if(!$strTable == 'tl_module') return;
		$GLOBALS['TL_DCA']['tl_module']['palettes']['backboneit_avisota_reg']
			= $GLOBALS['TL_DCA']['tl_module']['palettes']['registration'] . $GLOBALS['TL_DCA']['tl_module']['palettes']['backboneit_avisota_reg'];
	}
	
	protected function __construct() {
		$this->import('Database');
	}
	
	private static $objInstance;
	
	public static function getInstance() {
		if(isset(self::$objInstance))
			return self::$objInstance;
			
		return self::$objInstance = new self();
	}

}
