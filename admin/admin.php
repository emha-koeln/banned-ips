<?php
/**
 * admin.php
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
// add_action(string $tag, callable $function_to_add, int $priority=10, int $accepted_args=1) : true 
add_action ( 'admin_menu', function () {
	// add_options_page(string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function='', int $position=null) : string|false 
	add_options_page ( 'Banned IPs', 'Banned IPs', 'administrator', BIPS_PATH . '/admin/options.php' );
} );