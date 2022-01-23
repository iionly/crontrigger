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

namespace Crontrigger;

class CrontriggerFunctions {

	static function crontrigger_once($period, $timelastupdatedcheck = 0) {
		$plugin = elgg_get_plugin_from_id('crontrigger');
		if (!$plugin) {
			return false;
		}

		$lastupdated = (int) $plugin->getSetting($period, 0);
		if ($lastupdated <= $timelastupdatedcheck) {
			$interval = substr($period, 12);
			elgg_call(ELGG_IGNORE_ACCESS, function() use($interval) {
				$time = new \DateTime('now');
				_elgg_services()->cron->setCurrentTime($time);
				$jobs = _elgg_services()->cron->run([$interval], true);
			});

			$plugin->setSetting((string)$period, time());
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
	public static function crontrigger_shutdownhook(\Elgg\Event $event): void {
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
		self::crontrigger_once('crontrigger_minute', $now - $minute);
		self::crontrigger_once('crontrigger_fiveminute', $now - $fiveminute);
		self::crontrigger_once('crontrigger_fifteenmin', $now - $fifteenmin);
		self::crontrigger_once('crontrigger_halfhour', $now - $halfhour);
		self::crontrigger_once('crontrigger_hourly', $now - $hour);
		self::crontrigger_once('crontrigger_daily', $now - $day);
		self::crontrigger_once('crontrigger_weekly', $now - $week);
		self::crontrigger_once('crontrigger_monthly', $now - $month);
		self::crontrigger_once('crontrigger_yearly', $now - $year);
		ob_clean();
	}
}
