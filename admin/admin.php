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
if (! defined ( 'ABSPATH' )) {
    exit ();
}

// add_action(string $tag, callable $function_to_add, int $priority=10, int $accepted_args=1) : true
add_action('admin_menu', 'banned_ips_admin_menu' );

function banned_ips_admin_menu() {
    
    global $Bips;
    // add_options_page(string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function='', int $position=null) : string|false
    add_options_page('Banned IPs', 'Banned IPs', 'administrator', $Bips->PATH . '/admin/options.php');
    
}