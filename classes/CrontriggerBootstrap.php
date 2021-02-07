<?php

/**
 * Elgg Cron trigger.
 * When enabled this plugin provides "poor man's cron" functionality to trigger elgg cron scripts without the need
 * to set up cronjobs on the server.
 *
 * Note, this is a substitute and not a replacement for proper cronjobs. It is recommended that you use cronjobs
 * where possible.
 *
 */

use Elgg\DefaultPluginBootstrap;

class CrontriggerBootstrap extends DefaultPluginBootstrap {

	public function init() {
		elgg_register_event_handler('shutdown', 'system', 'crontrigger_shutdownhook');
	}
}
