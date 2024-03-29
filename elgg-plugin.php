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

return [
	'plugin' => [
		'name' => 'Crontrigger',
		'version' => '4.0.0',
	],
	'settings' => [
		'crontrigger_minute' => 0,
		'crontrigger_fiveminute' => 0,
		'crontrigger_fifteenmin' => 0,
		'crontrigger_halfhour' => 0,
		'crontrigger_hourly' => 0,
		'crontrigger_dayly' => 0,
		'crontrigger_weekly' => 0,
		'crontrigger_monthly' => 0,
		'crontrigger_yearly' => 0,
	],
	'events' => [
		'shutdown' => [
			'system' => [
				'Crontrigger\CrontriggerFunctions::crontrigger_shutdownhook' => [],
			],
		],
	],
];
