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

elgg_register_event_handler('init', 'system', 'crontrigger_init');

function crontrigger_init() {
	elgg_register_event_handler('shutdown', 'system', 'crontrigger_shutdownhook');
}

/**
 * Triggers cron hooks after a page has been displayed (so user won't notice any slowdown).
 * This is basically the code of the private Elgg core _elgg_cron_run() function.
 * It needs someone to view a page to trigger the hooks. If necessary it triggers all
 * cron hooks that are overdue since the last page view.
 *
 */
function crontrigger_shutdownhook() {
	$now = time();
	$params = [];
	$params['time'] = $now;

	$periods = [
		'minute' => 60,
		'fiveminute' => 300,
		'fifteenmin' => 900,
		'halfhour' => 1800,
		'hourly' => 3600,
		'daily' => 86400,
		'weekly' => 604800,
		'monthly' => 2628000,
		'yearly' => 31536000,
		'reboot' => 31536000,
	];

	$access = elgg_set_ignore_access(true);

	foreach ($periods as $period => $interval) {
		$key = "cron_latest:$period:ts";
		$ts = elgg_get_site_entity()->getPrivateSetting($key);
		$deadline = $ts + $interval;

		if ($now > $deadline) {
			$msg_key = "cron_latest:$period:msg";
			$msg = elgg_echo('admin:cron:started', [$period, date('r', time())]);
			elgg_get_site_entity()->setPrivateSetting($msg_key, $msg);

			ob_start();
			
			$old_stdout = elgg_trigger_plugin_hook('cron', $period, $params, '');
			$std_out = ob_get_clean();

			$period_std_out = $std_out .  $old_stdout;

			elgg_get_site_entity()->setPrivateSetting($msg_key, $period_std_out);
		}
	}

	elgg_set_ignore_access($access);
}
