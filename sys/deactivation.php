<?php

/**
 * deactivation.php
 * Part of banned-ips
 * v 0.1.5.alpha
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */
/**
 *
 * @package banned-ips
 * @author emha.koeln
 */

if (! defined ( 'ABSPATH' )) {
    exit ();
}

function banned_ips_deactivate_cronjobs()
{
    // WP Cron
    
    // everyminute
    $timestamp = wp_next_scheduled('bannedips_hook_everyminute_cronjob');
    wp_unschedule_event($timestamp, 'bannedips_hook_everyminute_cronjob');
    // tenminutes
    $timestamp = wp_next_scheduled('bannedips_hook_tenminutes_cronjob');
    wp_unschedule_event($timestamp, 'bannedips_hook_tenminutes_cronjob');
    // Hourly
    $timestamp = wp_next_scheduled('bannedips_hook_hourly_cronjob');
    wp_unschedule_event($timestamp, 'bannedips_hook_hourly_cronjob');
    // Daily
    $timestamp = wp_next_scheduled('bannedips_hook_daily_cronjob');
    wp_unschedule_event($timestamp, 'bannedips_hook_daily_cronjob');
    
    // $recepients = 'root@localhost';
    // $subject = 'Hello from your Bannedips Sys Cron Job: deactivated';
    // $message = 'This is a test mail sent by bannedips automatically as per your schedule.';
    // let's send it
    // mail ( $recepients, $subject, $message );
}

?>