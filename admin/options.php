<?php
/**
 * options.php
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
if (function_exists ( 'load_plugin_textdomain' )) {
	load_plugin_textdomain ( 'banned-ips', false, BIPS_PATH . '/languages' );
}

// Save options
if (isset ( $_POST ['_wpnonce'] ) && wp_verify_nonce ( $_POST ['_wpnonce'], 'save' )) {
	if (isset ( $_POST ['save'] )) {
		if (isset ( $_POST ['options'] )) {
			$options = stripslashes_deep ( $_POST ['options'] );
			update_option ( 'bannedips', $options );
		} else {
			update_option ( 'bannedips', array () );
		}
	}
} else {
	$options = get_option ( 'bannedips', array () );
}

// Cron
if (isset ( $options ['sys_cron'] )) {
	bips_activate_cronjobs ();
} else {
	bips_deactivate_cronjobs ();
}

if (! isset ( $options ['sys_cron_methode'] )) {
	$options ['sys_cron_methode'] = "WordPress Cron";
}

// Graph Colors Size
if (! isset ( $options ['graph_color_bg'] )) {
	$options ['graph_color_bg'] = "Grey";
}
if (! isset ( $options ['graph_color_graph'] )) {
	$options ['graph_color_graph'] = "Red";
}
if (! isset ( $options ['graph_width'] )) {
	$options ['graph_width'] = "400";
}
if (! isset ( $options ['graph_height'] )) {
	$options ['graph_height'] = "200";
}
if (! isset ( $options ['graph_time'] )) {
	$options ['graph_time'] = "last seven days";
}

// Accounts
if (! isset ( $options ['ab_account_id'] )) {
	$options ['ab_account_id'] = "";
}
if (! isset ( $options ['bl_account_serverid'] )) {
	$options ['bl_account_serverid'] = "";
}
if (! isset ( $options ['bl_account_apikey'] )) {
	$options ['bl_account_apikey'] = "";
}

// default fail2ban DB, 'autodetect'/select DB
$myDB = "";
if (! isset ( $options ['db'] ) || $options ['db'] == "") {
	if (PHP_OS == "Linux") {
		$myDB = "/var/lib/fail2ban/fail2ban.sqlite3";
		if (! file_exists ( $myDB )) {
			$myDB = "Error: autodetect failed; Fail2Ban DB is not set!";
		}
	} elseif (PHP_OS == "FreeBSD") {
		$myDB = "/var/db/fail2ban/fail2ban.sqlite3";
		if (! file_exists ( $myDB )) {
			$myDB = "Error: autodetect failed; Fail2Ban DB is not set!";
		}
	} else {
		$myDB = "Error: autodetect failed; Fail2Ban DB is not set!";
	}
	$options ['db'] = $myDB;
}

// css
echo "<style>";
echo "include BIPS_PATH.'/admin/admin.css'";
echo "</style>";


echo '<div class="wrap">';

	echo '<h2>Banned IPs</h2>';
	echo '<h3>';
	_e ( 'Configuration', 'banned-ips' );
	echo '</h3>';

	echo '<form action="" method="post">';
        wp_nonce_field('save');
        echo '<table class="form-table">';


			echo '<!--  DB, DB verify -->';
			echo '<tr>';
			echo '	<th colspan=2 style="text-decoration: underline"><h2>';
			echo '	<b>';
				_e ('Fail2Ban Database', 'banned-ips');
			echo '	</b>';
			echo '	</h2></th>';
			echo '</tr>';
			
			echo '<tr style="background-color:#FFFFFF;">';
			echo '	<th>';
			_e('Fail2Ban DB', 'banned-ips');
			echo '	</th>';
			echo '	<td>';
			echo '	<input type="text" name="options[db]"';
			echo '		value="';
			if(!$options['db']==""){
			 	echo $options['db'];
			}else{
				echo '';
			};
			echo '">';
			echo '<p class="description">';
            _e('Select Fail2Ban DB', 'banned-ips');
            echo "<br>" . (isset($options['db']) ? ('' . $options['db']) : ('autodetect: <br>' . $myDB)) . "<br>";
            echo '</p></td>';

            echo '	<th>';
			_e('To change group and get read access run:' , 'banned-ips');
			echo '</th>';
			echo '	<td colspan=2>';
			echo '		<p class="description">';
            echo ((PHP_OS == "Linux") ? ('$ sudo chown :www-data ' . $options['db'] .'<br> $ sudo chmod g=+r ' . $options['db']) : ('$ sudo chown :www ' . $options['db'] .'<br> $ sudo chmod g=+r ' . $options['db'])) ."<br>";
 
            echo '        </p>';
			echo '	</td>';

			echo '</tr>';
?>
			<!--  Language TODO:change to WP language-->
			<tr>
				<th colspan=2 style="text-decoration: underline"><h2>
						<b>Language</b>
					</h2></th>
			</tr>
			<tr style="background-color:#FFFFFF;">
				<th><?php _e('Select Output Language', 'banned-ips') ?></th>
				<td><input type="checkbox" name="options[lang]" value="1"
					<?php echo isset($options['lang']) ? 'checked' : ''; ?>>
					<p class="description">
                        <?php _e('Select German', 'banned-ips') ?>
                    </p></td>
			</tr>

			<!--  Links to AbuseIPDB and Blocklist -->
			<tr>
				<th colspan=2 style="text-decoration: underline"><h2>
						<b>Links to AbuseIPDB and Blocklist</b>
					</h2></th>
			</tr>
			<tr style="background-color:#FFFFFF;">
				<th><?php _e('Show Links to AbuseIPDB.com', 'banned-ips') ?></th>
				<td><input type="checkbox" name="options[ab_links]" value="1"
					<?php echo isset($options['ab_links']) ? 'checked' : ''; ?>>
					<p class="description">
                        <?php _e('AbuseIPDB.com', 'banned-ips') ?>
                    </p></td>

				<th><?php _e('Show Links to BlockList.de', 'banned-ips') ?></th>
				<td valign="top"><input type="checkbox" name="options[bl_links]"
					value="1"
					<?php echo isset($options['bl_links']) ? 'checked' : ''; ?>>
					<p class="description">
                        <?php _e('BlockList.de', 'banned-ips') ?>
                    </p></td>
			</tr>

			<!--  Stats from AbuseIPDB and Bloclist -->
			<tr>
				<th colspan=2 style="text-decoration: underline"><h2>
						<b>Stats from AbuseIPDB and Blocklist (testing)</b>
					</h2></th>
			</tr>
			<tr style="background-color:#FFFFFF;">
				<th><?php _e('Show AbuseIPDB.com Stats', 'banned-ips') ?></th>
				<td><input type="checkbox" name="options[ab_stats]" value="1"
					<?php echo isset($options['ab_stats']) ? 'checked' : ''; ?>>
					<p class="description">
                        <?php _e('AbuseIPDB.com Stats', 'banned-ips') ?>
                    </p></td>

				<th><?php _e('Show BlockList.de Stats', 'banned-ips') ?></th>
				<td><input type="checkbox" name="options[bl_stats]" value="1"
					<?php echo isset($options['bl_stats']) ? 'checked' : ''; ?>>
					<p class="description">
                        <?php _e('BlockList.de Stats', 'banned-ips'); ?>
                    </p></td>
			</tr>

			<!--  Accounts to AbuseIPDB and Blocklist -->
<?php
			// AbuseIPDB Account
			if (isset ( $options ['ab_stats'] )) {
				echo '<tr style="background-color:#FFFFFF;">';
				echo '	<th>';
				_e ( 'AbuseIPDB Account', 'banned-ips' );
				echo '	</th>';
				echo '	<td><input type="text" size="12" name="options[ab_account_id]"';
				echo '		value="';
				if (isset ( $options ['ab_account_id'] )) {
					echo $options ['ab_account_id'];
				} else {
					echo '';
				}
				;
				echo '">';
				echo '			<p class="description">';
				_e ( 'AbuseIPDB Account ID', 'banned-ips' );
				echo ' 			</p>';
				echo '	</td>';
			} elseif (isset ( $options ['bl_stats'] )) {
				echo '<tr style="background-color:#FFFFFF;">';
				echo '<th><br></th>';
				echo '<td>';
				echo '<input type="hidden" name="options[ab_account_id]" value="' . $options ['ab_account_id'] . '">';
				echo '		<p class="description"></p>';
				echo '</td>';
			} else {
				echo '<input type="hidden" name="options[ab_account_id]" value="' . $options ['ab_account_id'] . '">';
			}
			
			// Blocklist Account
			if (isset ( $options ['bl_stats'] )) {
				echo '<th>';
				_e ( 'Blocklist Account', 'banned-ips' );
				echo '</th>';
				echo '<td><input type="text" size="12" name="options[bl_account_serverid]"';
				echo '	value="';
				if (isset ( $options ['bl_account_serverid'] )) {
					echo $options ['bl_account_serverid'];
				} else {
					echo '';
				}
				;
				echo '">';
				echo '	<p class="description">';
				_e ( 'Blocklist Account ServerID', 'banned-ips' );
				echo '  </p>';
				echo '</td>';
				
				echo '<td><input type="text" size="12" name="options[bl_account_apikey]"';
				echo '	value="';
				if (isset ( $options ['bl_account_apikey'] )) {
					echo $options ['bl_account_apikey'];
				} else {
					echo '';
				}
				;
				echo '">';
				echo '	<p class="description">';
				_e ( 'Blocklist Account APIKEY', 'banned-ips' );
				echo '	</p>';
				echo '</td>';
			} elseif (isset ( $options ['ab_stats'] )) {
				echo '<input type="hidden" name="options[bl_account_serverid]" value="' . $options ['bl_account_serverid'] . '">';
				echo '<input type="hidden" name="options[bl_account_apikey]" value="' . $options ['bl_account_apikey'] . '">';
				echo '</tr>';
			} else {
				echo '<input type="hidden" name="options[bl_account_serverid]" value="' . $options ['bl_account_serverid'] . '">';
				echo '<input type="hidden" name="options[bl_account_apikey]" value="' . $options ['bl_account_apikey'] . '">';
			}
			
?>

			<!--  Testing Cron -->
			<tr>
				<th colspan=2 style="text-decoration: underline"><h2>
						<b>Cronjobs (testing)</b>
					</h2></th>
			</tr>
			<tr style="background-color:#FFFFFF;">
				<th><?php _e('Activate Banned IPs Cron', 'banned-ips') ?></th>
				<td><input type="checkbox" name="options[sys_cron]" value="1"
					<?php echo isset($options['sys_cron']) ? 'checked' : ''; ?>>
					<p class="description">
                        <?php _e('Activate Banned IPs Cron Jobs', 'banned-ips') ?>
                    </p></td>

				<th><?php _e('Cron Method', 'banned-ips') ?></th>
				<td><select name="options[sys_cron_methode]">
						<option value="OS Cronjob"
							<?php if($options['sys_cron_methode']=="OS Cronjob (not done)"){echo " selected";}?>>OS
							Cronjob (not done)</option>
						<option value="External Call"
							<?php if($options['sys_cron_methode']=="External Call"){echo " selected";}?>>External
							Call (not done)</option>
						<option value="WordPress Cron"
							<?php if($options['sys_cron_methode']=="WordPress Cron"){echo " selected";}?>>WordPress
							Cron</option>
				</select>
					<p class="description">
                        <?php _e('Select Cron Method', 'banned-ips') ?>
                    </p></td>
			</tr>
<?php
			
			// Cron Method
			if (isset ( $options ['sys_cron'] )) {
				echo '<tr style="background-color:#FFFFFF;">';
				echo '<th><br></th>';
				echo '<td>';
				echo '		<p class="description"></p>';
				echo '</td>';
				echo '<th>';
				_e ( 'Cron Method Activation', 'banned-ips' );
				echo '</th>';
				echo '<td>';
				_e ('To activate do the follwing...', 'banned-ips');
				echo '<p class="description">';
				echo '$ sudo crontab...';
				echo '</p></td>';
				echo '</tr>';
			}
			
			// Stats Grap
			if (isset ( $options ['sys_cron'] )) {
				echo '<!--  Graph -->';
				echo '<tr >';
				echo '<th colspan=2 style="text-decoration: underline"><h2>';
				echo '<b>';
				_e ('Show Stats Graph', 'banned-ips');
				echo '</b>';
				echo '</h2></th>';
				echo '</tr>';
			}
			
			// Show Graph
			if (isset ( $options ['sys_cron'] )) {
				echo '<tr style="background-color:#FFFFFF;">';
				echo '<th>';
				_e ( 'Show Graph', 'banned-ips' );
				echo '</th>';
				echo '<td>';
				echo '<input type="checkbox" name="options[show_graph]" value="1"';
				echo isset ( $options ['show_graph'] ) ? 'checked' : '';
				echo '>';
				echo '<p class="description">';
				echo 'Show Graph';
				echo '</p></td>';
			} else {
				echo '<input type="hidden" name="options[show_graph]" value="' . $options ['show_graph'] . '">';
			}
			
			// Graph Colors
			if (isset ( $options ['sys_cron'] )) {
				
				echo '<th>';
				_e ( 'Select Colors', 'banned-ips' );
				echo '</th>';
				echo '<td>';
				echo '<select name="options[graph_color_bg]">';
				echo '<option value="White"';
				if ($options ['graph_color_bg'] == "White") {
					echo ' selected';
				}
				;
				echo '>White</option>';
				echo '<option value="Grey"';
				if ($options ['graph_color_bg'] == "Grey") {
					echo " selected";
				}
				echo '>Grey</option>';
				echo '<option value="Black"';
				if ($options ['graph_color_bg'] == "Black") {
					echo " selected";
				}
				echo '>Black</option>';
				echo '</select>';
				echo '<p class="description">';
				_e ('Background Color', 'banned-ips');
				echo '</p></td>';
				
				echo '<td>';
				echo '<select name="options[graph_color_graph]">';
				echo '<option value="Blue"';
				if ($options ['graph_color_graph'] == "Blue") {
					echo ' selected';
				}
				;
				echo '>Blue</option>';
				echo '<option value="Red"';
				if ($options ['graph_color_graph'] == "Red") {
					echo " selected";
				}
				echo '>Red</option>';
				echo '<option value="Green"';
				if ($options ['graph_color_graph'] == "Green") {
					echo " selected";
				}
				echo '>Green</option>';
				echo '</select>';
				echo '<p class="description">';
				_e ('Graph Color', 'banned-ips');
				echo '</p></td>';
				echo '</tr>';
			} else {
				echo '<input type="hidden" name="options[graph_color_bg]" value="' . $options ['graph_color_bg'] . '">';
				echo '<input type="hidden" name="options[graph_color_graph]" value="' . $options ['graph_color_graph'] . '">';
			}
			
			// Graph Period
			if (isset ( $options ['sys_cron'] )) {
				echo '<tr style="background-color:#FFFFFF;">';
				
				echo '<th>';
				_e ( 'Graph Time Period', 'banned-ips' );
				echo '</th>';
				
				echo '<td>';
				echo '<select name="options[graph_time]">';
				echo '<option value="last 24 hours"';
				if ($options ['graph_time'] == "last 24 hours") {
					echo ' selected';
				}
				;
				echo '>last 24 hours</option>';
				echo '<option value="last seven days"';
				if ($options ['graph_time'] == "last seven days") {
					echo ' selected';
				}
				;
				echo '>last seven days</option>';
				echo '<option value="last thirty days"';
				if ($options ['graph_time'] == "last thirty days") {
					echo " selected";
				}
				echo '>last thirty days</option>';
				echo '<option value="all"';
				if ($options ['graph_time'] == "all") {
					echo " selected";
				}
				echo '>all</option>';
				echo '</select>';
				echo '<p class="description">';
				_e ('Time period', 'banned-ips');
				echo '</p></td>';
			} else {
				echo '<input type="hidden" name="options[graph_time]" value="' . $options ['graph_time'] . '">';
			}
			
			// Graph Size
			if (isset ( $options ['sys_cron'] )) {
				echo '<th>';
				_e ( 'Graph Size', 'banned-ips' );
				echo '</th>';
				echo '<td><input type="text" size="5" name="options[graph_width]"';
				echo '	value="';
				if (isset ( $options ['graph_width'] )) {
					echo $options ['graph_width'];
				} else {
					echo '';
				}
				;
				echo '">';
				echo '	<p class="description">';
				_e ( 'Width', 'banned-ips' );
				echo '  </p>';
				echo '</td>';
				
				echo '<td><input type="text" size="5" name="options[graph_height]"';
				echo '	value="';
				if (isset ( $options ['graph_height'] )) {
					echo $options ['graph_height'];
				} else {
					echo '';
				}
				;
				echo '">';
				echo '	<p class="description">';
				_e ( 'Hight', 'banned-ips' );
				echo '	</p>';
				echo '</td>';
				echo '</tr>';
			} else {
				echo '<input type="hidden" name="options[graph_width]" value="' . $options ['graph_width'] . '">';
				echo '<input type="hidden" name="options[graph_height]" value="' . $options ['graph_height'] . '">';
			}
			
			echo '</table>';
			echo '<p class="submit">';
			echo '<input class="button button-primary" type="submit" name="save" value="';
			_e ( "Save", "banned-ips" );
			echo '">';
			echo '</p>';
			
		echo '	</form>';
	echo '</div>';
?>
