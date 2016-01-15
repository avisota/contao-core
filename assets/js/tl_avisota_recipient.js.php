<?php
chdir('../../../../');
define('TL_MODE', 'FE');
/** @noinspection PhpIncludeInspection */
require('system/initialize.php');

use Symfony\Component\EventDispatcher\EventDispatcher;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;

/** @var EventDispatcher $eventDispatcher */
$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

$eventDispatcher->dispatch(
    ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
    new LoadLanguageFileEvent('orm_avisota_recipient')
);

header('Content-Type: text/javascript');
?>
window.addEvent('domready', function () {
    var contextmenu = new Element('div', {id: 'contextmenu'});
    var tools = $('header_recipient_tools');
    if (tools) {
        var elements = tools.getAllNext('a.recipient_tool');

        elements.each(function (e) {
            e.parentNode.removeChild(e.previousSibling);
            e.inject(contextmenu);
        });

        tools.addEvent('click', function () {
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
        inputDeleteNoBlacklist.set('value', <?php echo json_encode($GLOBALS['TL_LANG']['orm_avisota_recipient']['delete_no_blacklist'][0]); ?>);
        var onclickFunction = inputDeleteNoBlacklist.onclick;
        inputDeleteNoBlacklist.onclick = function () {
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
