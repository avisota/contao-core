Avisota 3
=========

[![Build Status](https://travis-ci.org/avisota/contao-core.png)](https://travis-ci.org/avisota/contao-core)
[![Latest Version tagged](http://img.shields.io/github/tag/avisota/contao-core.svg)](https://github.com/avisota/contao-core/tags)
[![Latest Version on Packagist](http://img.shields.io/packagist/v/avisota/contao-core.svg)](https://packagist.org/packages/avisota/contao-core)
[![Installations via composer per month](http://img.shields.io/packagist/dm/avisota/contao-core.svg)](https://packagist.org/packages/avisota/contao-core)
[![Reference Status](https://www.versioneye.com/php/avisota:contao-core/rbadge.svg?style=flat)](https://www.versioneye.com/php/avisota:contao-core)

Avisota is a high definition newsletter and mailing system for the Contao CMS <www.contao.org>.

## Events

### avisota-recipient-subscribe (SubscribeEvent)

Triggered if a recipient starts the subscription process (double-opt-in).

### avisota-recipient-confirm-subscription (ConfirmSubscriptionEvent)

Triggered if a recipient confirms his subscription.

### avisota-recipient-unsubscribe (UnsubscribeEvent)

Triggered if a recipient cancels his subscription.

### avisota-recipient-remove (RecipientEvent)

Triggered if a recipient gets removed, because he has no more subscriptions.
