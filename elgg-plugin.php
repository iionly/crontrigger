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

require_once(dirname(__FILE__) . '/lib/events.php');

return [
'plugin' => [
	'name' => 'Crontrigger',
	'version' => '4.0.0',
],
'bootstrap' => \CrontriggerBootstrap::class,
	'settings' => [
		'crontrigger_minute' => 0,
		'crontrigger_fiveminute' => 0,
		'crontrigger_fifteenmin' => 0,
		'crontrigger_halfhour' => 0,
		'crontrigger_hour' => 0,
		'crontrigger_day' => 0,
		'crontrigger_week' => 0,
		'crontrigger_month' => 0,
		'crontrigger_year' => 0,
	],
];
