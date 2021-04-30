<?php
/**
 * cron_init.php (not done)
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

// Cronjobs
echo "bannedips_cron called <br>";

ob_start();

// Generage HTML page here
// generate_full_html_page();
echo "<!DOCTYPE html>";
echo "<html>";
echo "<body>";
echo "<p>Cron_init Page</p>";
// echo ABSPATH . '/wp-admin/admin.php';
echo "</body>";
echo "</html> ";

// All magic goes here
$output = ob_get_clean();
ignore_user_abort(true);
set_time_limit(0);
header("Connection: close");
header("Content-Length: " . strlen($output));
header("Content-Encoding: none");
echo $output . str_repeat(' ', 10000) . "\n\n\n";
flush();

// Now page is sent and it safe to do all needed stuff here
// cron_task1();
// cron_task2();

$recepients = 'root@localhost';
$subject = 'Hello from your Banned IPs External Cron Job';
$message = 'This is a test mail sent by bannedips automatically as per your schedule.';
// let's send it
mail($recepients, $subject, $message);

?>