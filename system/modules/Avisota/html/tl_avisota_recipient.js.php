<?php
chdir('../../../../');
define('TL_MODE', 'FE');
require('system/initialize.php');

class JavaScript extends System {
	public function __construct() {
		parent::__construct();
		$this->loadLanguageFile('tl_avisota_recipient');
	}
}

header('Content-Type: text/javascript');
new JavaScript();
?>
window.addEvent('domready', function() {
	var contextmenu = new Element('div', { id: 'contextmenu' });
	var tools = $('header_recipient_tools');
	if (tools) {
		var elements = tools.getAllNext('a.recipient_tool');
		
		elements.each(function(e) {
			e.parentNode.removeChild(e.previousSibling);
			e.inject(contextmenu);
		});
		
		tools.addEvent('click', function() {
			ContextMenu.hide();
			
			contextmenu
				.clone(true, true)
				.inject(document.body)
				.position({
					relativeTo: tools,
					position: 'rightTop',
					edge: 'rightTop',
					ignoreMargins: true
				});
			
			return false;
		});
	}
	
	var formSelect = $('tl_select');
	if (formSelect) {
		var inputDelete = formSelect.getElement('input#delete');
		var inputDeleteNoBlacklist = inputDelete.clone();
		inputDeleteNoBlacklist.set('id', 'deleteNoBlacklist');
		inputDeleteNoBlacklist.set('value', <?php echo json_encode($GLOBALS['TL_LANG']['tl_avisota_recipient']['delete_no_blacklist'][0]); ?>);
		var onclickFunction = inputDeleteNoBlacklist.onclick;
		inputDeleteNoBlacklist.onclick = function() {
			if (onclickFunction()) {
				this.form.action += '&blacklist=false';
				return true;
			} else {
				return false;
			}
		};
		inputDeleteNoBlacklist.inject(inputDelete, 'after');
	}
});
