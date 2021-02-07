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

function crontrigger_trigger($period) {
	elgg_call(ELGG_IGNORE_ACCESS, function() use($period) {
		$time = new \DateTime('now');
		_elgg_services()->cron->setCurrentTime($time);
		$jobs = _elgg_services()->cron->run([$period], true);
	});
}

function crontrigger_minute() {
	crontrigger_trigger('minute');
}

function crontrigger_fiveminute() {
	crontrigger_trigger('fiveminute');
}

function crontrigger_fifteenmin() {
	crontrigger_trigger('fifteenmin');
}

function crontrigger_halfhour() {
	crontrigger_trigger('halfhour');
}

function crontrigger_hourly() {
	crontrigger_trigger('hourly');
}

function crontrigger_daily() {
	crontrigger_trigger('daily');
}

function crontrigger_weekly() {
	crontrigger_trigger('weekly');
}

function crontrigger_monthly() {
	crontrigger_trigger('monthly');
}

function crontrigger_yearly() {
	crontrigger_trigger('yearly');
}

function crontrigger_once($functionname, $timelastupdatedcheck = 0) {
	$lastupdated = (int) elgg_get_plugin_setting($functionname, 'crontrigger', 0);
	if (is_callable($functionname) && $lastupdated <= $timelastupdatedcheck) {
		$functionname();
		elgg_set_plugin_setting($functionname, time(), 'crontrigger');
		return true;
	} else {
		return false;
	}
}

/**
 * Call cron hooks after a page has been displayed (so user won't notice any slowdown).
 *
 * It uses a mod of now and needs someone to view the page within a certain time period
 *
 */
function crontrigger_shutdownhook(\Elgg\Event $event) {
	$minute = 60;
	$fiveminute = 300;
	$fifteenmin = 900;
	$halfhour = 1800;
	$hour = 3600;
	$day = 86400;
	$week = 604800;
	$month = 2628000;
	$year = 31536000;

	$now = time();

	ob_start();
	crontrigger_once('crontrigger_minute', $now - $minute);
	crontrigger_once('crontrigger_fiveminute', $now - $fiveminute);
	crontrigger_once('crontrigger_fifteenmin', $now - $fifteenmin);
	crontrigger_once('crontrigger_halfhour', $now - $halfhour);
	crontrigger_once('crontrigger_hourly', $now - $hour);
	crontrigger_once('crontrigger_daily', $now - $day);
	crontrigger_once('crontrigger_weekly', $now - $week);
	crontrigger_once('crontrigger_monthly', $now - $month);
	crontrigger_once('crontrigger_yearly', $now - $year);
	ob_clean();
}
