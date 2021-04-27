=== Banned IPs ===
Tags: fail2ban, php, WordPress, Linux, FreeBSD, blocklist, abuseipdb
Contributors: emha.koeln
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires php: 7.2+
Requires php-sqlite3
Requires php-curl
Requires php-gd
Requires fail2ban: 0.11+

Shortcode [bannedips] and Widget for showing the current blocked IPs by fail2ban and/or statistics
 from your blocklist and abuseipdb account

== Description ==

Shortcode [bannedips] and Widget for showing the current blocked IPs by fail2ban and/or statistics
 from your blocklist and abuseipdb account

== Installation ==

    1. Make your fail2ban.sqlite3 db readable to the web server
    (e.g. # chown root:www-data /var/lib/fail2ban/fail2ban.sqlite3
          # chmod 0640 /var/lib/fail2ban/fail2ban.sqlite3)
    2. Use it with fail2ban's banntime.incremt

    WordPress:
    1. Put the plugin folder into [wordpress_dir]/wp-content/plugins/
    	or install via the WordPress Plugin interface 
    2. Go to the WordPress admin interface and activate the plugin
    3. Set defaults in Banned IPs' configuration interface
    
== Homepage ==
   
   https://sourceforge.net/projects/banned-ips/
   https://emha.koeln/banned-ips-plugin/
   
== Frequently Asked Questions ==

    No questions have been asked.

== Screenshots ==

    Screenshots available on https://sourceforge.net/projects/banned-ips/

== Options/Attributes ==

    db	                 Fail2Ban DB                  text                default: autodetect
    lang                 Language                     boolean, True=de    default: False
    
    ab_links	         show links to abuseipdb.com  boolean             default: False
    ab_stats             show AbuseIPDB stats         boolean             default: False
    ab_account_id        AbuseIPDB Number             text                default: ""
    
    bl_links	         show links to blocklist.de   boolean             default: False
    bl_stats             show BlockList stats         boolean             default: False
    bl_account_serverid  Blocklist ServerID           text                default: ""
    bl_account_apikey    Blocklist APIKey             text                default: ""

    You may change the defaults in the Banned IP's configuration interface
    or, for standalone use, inside the banned.php file

== Use ==

    As WordPress Plugin:

    Sample:     [bannedips]
    or:         [bannedips db='/path/to/fail2banDB' lang='de' bl_links=false]

    Attributes given overwrite defaults.

    Standalone:

    The file 'banned.php' maybe used 'as is' on your site without WordPress.
    For configuration see '$my*'-vars inside banned.php.

== Changelog ==

= 0.1.5.beta =
    (Not started)
    TODO:       Use more OOP
	
= 0.1.5.alpha =
    Bugs:       Installation in 'banned-ips' plugin folder only works with 'banned-ips.zip'
<<<<<<< Upstream, based on origin/main
 
    TODO:       Cron, Use statistics from own DB, Graph Colors, blockist per user stats
=======
                Translation in Widget
 
    TODO:       Cron, Use statistics from own DB, Graph Colors, blocklist per user stats,
                Widget
>>>>>>> b9a6b74 v 0.1.5-alpha
    NEW:        Options: i18n, Use Cron, Show Stats Graph
                With Cron we use our own stats-db for abuseipdb and blocklist  
    
    Options, Options-Page, WP-Cronjobs, Graph

= 0.1.4 =
    Bugs:       Installation in 'banned-ips' plugin folder only works with 'banned-ips.zip'
   
    Test WP deactivation hook, WP cron, Blocklist and AbuseIPDB Statistics
    
    New Options: ab_account_id,
                 ab_stats, 
                 bl_account_serverid, 
                 bl_account_apikey,
                 bl_stats
    
    Changed Options: link_ab => ab_links
                     link_bl => bl_links

= 0.1.3 =
    Bugs:       Links don't work in page-preview (?)
                Installation in 'banned-ips' plugin folder only works with 'banned-ips.zip'
   
    Test WP activation hook, test own DB table

= 0.1.2 =
    Bugs:       Links don't work in page-preview (?)
                Installation only works with 'banned-ips.zip'
   
    fail2ban DB autodetect, delete options on deinstallation/uninstall.php
   
= 0.1.1 =
    Bugs:       Links don't work in page-preview (?)
		
    German translation, preview, standalone php warnings

= 0.1 =
    Bugs:       Links don't work in page-preview


