# Avisota

Avisota is a newsletter and mailing system for the Contao CMS <www.contao.org>

## Branches

**master** This is the current stable upstream development branch.

**version1.5** This is the stable bugfix branch.

## Hooks

These hooks are added in the **master** branch.

### avisotaIntegratedRecipientSubscribe

Called when an integrated recipient is added to some mailing lists (unconfirmed).

Synopsis: `function avisotaIntegratedRecipientSubscribe(AvisotaRecipient $objRecipient, array $arrLists)`

### avisotaIntegratedRecipientSendSubscriptionConfirmation

Called when the subscription confirmation email is send to an integrated recipient.

Synopsis: `function avisotaIntegratedRecipientSendSubscriptionConfirmation(AvisotaRecipient $objRecipient, array $arrListsByPage)`

### avisotaIntegratedRecipientSendSubscriptionReminder

Called when the subscription reminder email is send to an integrated recipient.

Synopsis: `function avisotaIntegratedRecipientSendSubscriptionReminder(AvisotaRecipient $objRecipient, array $arrListsByPage)`

### avisotaIntegratedRecipientUnsubscribe

Called when an integrated recipient unsubscribe.

Synopsis: `function avisotaIntegratedRecipientUnsubscribe(AvisotaRecipient $objRecipient, array $arrListsByPage, bool $blnDeleted)`

### avisotaIntegratedRecipientConfirmSubscription

Called when an integrated recipient confirm his subscription.

Synopsis: `function avisotaIntegratedRecipientConfirmSubscription(AvisotaRecipient $objRecipient, array $arrLists)`

### avisotaCollectThemeCss

Called in DCA, when collection css files of a theme, allow other modules (like external_stylesheets or theme_plus) to add selectable css files.

Synopsis: `function avisotaCollectThemeCss($arrStylesheets, $arrTheme)`
The function must return $arrStylesheets!

### avisotaCollectCss

Called in DCA, when collection theme independend css files, allow other modules (like external_stylesheets or theme_plus) to add selectable css files.

Synopsis: `function avisotaCollectThemeCss($arrStylesheets)`
The function must return $arrStylesheets!


