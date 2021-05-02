<?php

/**
 * options_functions.php
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



function bannedips_options_stats()
{
    $options = get_option('bannedips', array());
    ?>
<!--  Stats from AbuseIPDB and Bloclist -->
<tr>
	<th colspan=2 style="text-decoration: underline"><h2>
			<b>Stats from AbuseIPDB and Blocklist</b>
		</h2></th>
</tr>
<tr style="background-color: #FFFFFF;">
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
	<th colspan=3></th>
</tr>

<!--  Accounts to AbuseIPDB and Blocklist -->
<?php
    
    // bannedips_options_savebutton();
}

function bannedips_options_accounts()
{
    $options = get_option('bannedips', array());
    
    // AbuseIPDB Account
    if (isset($options['ab_stats'])) {
        echo '<tr style="background-color:#FFFFFF;">';
        echo '	<th>';
        _e('AbuseIPDB Account', 'banned-ips');
        echo '	</th>';
        echo '	<td><input type="text" size="12" name="options[ab_account_id]"';
        echo '		value="';
        if (isset($options['ab_account_id'])) {
            echo $options['ab_account_id'];
        } else {
            echo '';
        }
        ;
        echo '">';
        echo '			<p class="description">';
        _e('AbuseIPDB Account ID', 'banned-ips');
        echo ' 			</p>';
        echo '	</td>';
    } elseif (isset($options['bl_stats'])) {
        echo '<tr style="background-color:#FFFFFF;">';
        echo '<th><br></th>';
        echo '<td>';
        echo '<input type="hidden" name="options[ab_account_id]" value="' . $options['ab_account_id'] . '">';
        echo '		<p class="description"></p>';
        echo '</td>';
        echo '<th>';
        echo '</th>';
    } else {
        echo '<input type="hidden" name="options[ab_account_id]" value="' . $options['ab_account_id'] . '">';
    }
    
    // Blocklist Account
    if (isset($options['bl_stats'])) {
        echo '<th>';
        _e('Blocklist Account', 'banned-ips');
        echo '</th>';
        echo '<td><input type="text" size="12" name="options[bl_account_serverid]"';
        echo '	value="';
        if (isset($options['bl_account_serverid'])) {
            echo $options['bl_account_serverid'];
        } else {
            echo '';
        }
        ;
        echo '">';
        echo '	<p class="description">';
        _e('Blocklist Account ServerID', 'banned-ips');
        echo '  </p>';
        echo '</td>';
        
        echo '<td><input type="text" size="12" name="options[bl_account_apikey]"';
        echo '	value="';
        if (isset($options['bl_account_apikey'])) {
            echo $options['bl_account_apikey'];
        } else {
            echo '';
        }
        ;
        echo '">';
        echo '	<p class="description">';
        _e('Blocklist Account APIKEY', 'banned-ips');
        echo '	</p>';
        echo '</td>';
        echo '<th>';
        echo '</th>';
        echo '</tr>';
    } elseif (isset($options['ab_stats'])) {
        echo '<input type="hidden" name="options[bl_account_serverid]" value="' . $options['bl_account_serverid'] . '">';
        echo '<input type="hidden" name="options[bl_account_apikey]" value="' . $options['bl_account_apikey'] . '">';
        echo '</tr>';
    } else {
        echo '<input type="hidden" name="options[bl_account_serverid]" value="' . $options['bl_account_serverid'] . '">';
        echo '<input type="hidden" name="options[bl_account_apikey]" value="' . $options['bl_account_apikey'] . '">';
    }
    bannedips_options_savebutton_tr();
}

function bannedips_options_graph()
{
    $options = get_option('bannedips', array());
    // Stats Grap
    if (isset($options['sys_cron'])) {
        echo '<!--  Graph -->';
        echo '<tr >';
        echo '<th colspan=2 style="text-decoration: underline"><h2>';
        echo '<b>';
        _e('Show Stats Graph', 'banned-ips');
        // bannedips_options_savebutton();
        echo '</b>';
        echo '</h2></th>';
        // bannedips_options_savebutton_td();
        echo '</tr>';
    }
    
    // Show Graph
    if (isset($options['sys_cron'])) {
        echo '<tr style="background-color:#FFFFFF;">';
        echo '<th>';
        _e('Show Graph', 'banned-ips');
        echo '</th>';
        echo '<td>';
        echo '<input type="checkbox" name="options[show_graph]" value="1"';
        echo isset($options['show_graph']) ? 'checked' : '';
        echo '>';
        echo '<p class="description">';
        _e('Show Graph', 'banned-ips');
        echo '</p></td>';
    } else {
        echo '<input type="hidden" name="options[show_graph]" value="' . $options['show_graph'] . '">';
    }
    
    // Graph Colors
    if (isset($options['sys_cron'])) {
        
        echo '<th>';
        _e('Select Colors', 'banned-ips');
        echo '</th>';
        echo '<td>';
        echo '<select name="options[graph_color_bg]">';
        
        echo '<option value="White"';
        if ($options['graph_color_bg'] == "White") {
            echo ' selected';
        }
        ;
        echo '>';
        _e('White', 'banned-ips');
        echo '</option>';
        
        echo '<option value="Grey"';
        if ($options['graph_color_bg'] == "Grey") {
            echo " selected";
        }
        echo '>';
        _e('Grey', 'banned-ips');
        echo '</option>';
        
        echo '<option value="Black"';
        if ($options['graph_color_bg'] == "Black") {
            echo " selected";
        }
        echo '>';
        _e('Black', 'banned-ips');
        echo '</option>';
        
        echo '<option value="#Hex"';
        if ($options['graph_color_bg'] == "#Hex") {
            echo " selected";
        }
        echo '>';
        _e('#Hex', 'banned-ips');
        echo '</option>';
        
        echo '</select>';
        
        echo '<p class="description">';
        _e('Background Color', 'banned-ips');
        echo '</p></td>';
        
        echo '<td>';
        echo '<select name="options[graph_color_graph]">';
        
        echo '<option value="Black"';
        if ($options['graph_color_graph'] == "Black") {
            echo " selected";
        }
        echo '>';
        _e('Black', 'banned-ips');
        echo '</option>';
        
        echo '<option value="Blue"';
        if ($options['graph_color_graph'] == "Blue") {
            echo ' selected';
        }
        echo '>';
        _e('Blue', 'banned-ips');
        echo '</option>';
        
        echo '<option value="Red"';
        if ($options['graph_color_graph'] == "Red") {
            echo " selected";
        }
        echo '>';
        _e('Red', 'banned-ips');
        echo '</option>';
        
        echo '<option value="Green"';
        if ($options['graph_color_graph'] == "Green") {
            echo " selected";
        }
        echo '>';
        _e('Green', 'banned-ips');
        echo '</option>';
        
        echo '<option value="White"';
        if ($options['graph_color_graph'] == "White") {
            echo " selected";
        }
        echo '>';
        _e('White', 'banned-ips');
        echo '</option>';
        
        echo '<option value="#Hex"';
        if ($options['graph_color_graph'] == "#Hex") {
            echo " selected";
        }
        echo '>';
        _e('#Hex', 'banned-ips');
        echo '</option>';
        
        echo '</select>';
        echo '<p class="description">';
        _e('Graph Color', 'banned-ips');
        echo '</p></td>';
        echo '<th>';
        echo '</th>';
        echo '</tr>';
    } else {
        echo '<input type="hidden" name="options[graph_color_bg]" value="' . $options['graph_color_bg'] . '">';
        echo '<input type="hidden" name="options[graph_color_graph]" value="' . $options['graph_color_graph'] . '">';
    }
    // Graph Colors Hex
    
    if (isset($options['sys_cron'])) {
        echo '<tr style="background-color:#FFFFFF;">';
        echo '<th>';
        echo '</th>';
        echo '<td>';
        echo '</td>';
        
        echo '<th>';
        echo '</th>';
        echo '<td><input type="text" size="5" name="options[graph_color_bg_hex]"';
        echo '	value="';
        if (isset($options['graph_color_bg_hex'])) {
            echo $options['graph_color_bg_hex'];
        } else {
            echo '';
        }
        ;
        echo '">';
        echo '	<p class="description">';
        _e('Hex Value', 'banned-ips');
        echo '  </p>';
        echo '</td>';
        
        echo '<td><input type="text" size="5" name="options[graph_color_graph_hex]"';
        echo '	value="';
        if (isset($options['graph_color_graph_hex'])) {
            echo $options['graph_color_graph_hex'];
        } else {
            echo '';
        }
        ;
        echo '">';
        echo '	<p class="description">';
        _e('Hex Value', 'banned-ips');
        echo '	</p>';
        echo '</td>';
        echo '<th>';
        echo '</th>';
        echo '</tr>';
    } else {
        echo '<input type="hidden" name="options[graph_color_bg_hex]" value="' . $options['graph_color_bg_hex'] . '">';
        echo '<input type="hidden" name="options[graph_color_graph_hex]" value="' . $options['graph_color_graph_hex'] . '">';
    }
    
    // Graph Period
    if (isset($options['sys_cron'])) {
        echo '<tr style="background-color:#FFFFFF;">';
        
        echo '<th>';
        _e('Graph Time Period', 'banned-ips');
        echo '</th>';
        
        echo '<td>';
        echo '<select name="options[graph_time]">';
        
        echo '<option value="last hour"';
        if ($options['graph_time'] == "last hour") {
            echo ' selected';
        }
        echo '>';
        _e('last hour', 'banned-ips');
        echo '</option>';
        
        echo '<option value="last 24 hours"';
        if ($options['graph_time'] == "last 24 hours") {
            echo ' selected';
        }
        echo '>';
        _e('last 24 hours', 'banned-ips');
        echo '</option>';
        
        echo '<option value="last seven days"';
        if ($options['graph_time'] == "last seven days") {
            echo ' selected';
        }
        echo '>';
        _e('last seven days', 'banned-ips');
        echo '</option>';
        
        echo '<option value="last thirty days"';
        if ($options['graph_time'] == "last thirty days") {
            echo " selected";
        }
        echo '>';
        _e('last thirty days', 'banned-ips');
        echo '</option>';
        
        echo '<option value="all"';
        if ($options['graph_time'] == "all") {
            echo " selected";
        }
        echo '>';
        _e('all', 'banned-ips');
        echo '</option>';
        
        echo '</select>';
        echo '<p class="description">';
        _e('Time period', 'banned-ips');
        echo '</p></td>';
    } else {
        echo '<input type="hidden" name="options[graph_time]" value="' . $options['graph_time'] . '">';
    }
    
    // Graph Size
    if (isset($options['sys_cron'])) {
        echo '<th>';
        _e('Graph Size', 'banned-ips');
        echo '</th>';
        echo '<td><input type="text" size="5" name="options[graph_width]"';
        echo '	value="';
        if (isset($options['graph_width'])) {
            echo $options['graph_width'];
        } else {
            echo '';
        }
        ;
        echo '">';
        echo '	<p class="description">';
        _e('Width', 'banned-ips');
        echo '  </p>';
        echo '</td>';
        
        echo '<td><input type="text" size="5" name="options[graph_height]"';
        echo '	value="';
        if (isset($options['graph_height'])) {
            echo $options['graph_height'];
        } else {
            echo '';
        }
        ;
        echo '">';
        echo '	<p class="description">';
        _e('Hight', 'banned-ips');
        echo '	</p>';
        echo '</td>';
        echo '<th>';
        echo '</th>';
        echo '</tr>';
        bannedips_options_savebutton_tr();
    } else {
        echo '<input type="hidden" name="options[graph_width]" value="' . $options['graph_width'] . '">';
        echo '<input type="hidden" name="options[graph_height]" value="' . $options['graph_height'] . '">';
    }
}

function bannedips_options_f2bdb()
{
    $options = get_option('bannedips', array());
    
    echo '<!--  DB, DB verify -->';
    echo '<tr>';
    echo '	<th colspan=2 style="text-decoration: underline"><h2>';
    echo '	<b>';
    _e('Fail2Ban Database', 'banned-ips');
    echo '	</b>';
    echo '	</h2></th>';
    echo '</tr>';
    
    echo '<tr style="background-color:#FFFFFF;">';
    echo '	<th>';
    _e('Fail2Ban DB', 'banned-ips');
    echo '	</th>';
    echo '	<td>';
    echo '	<input type="text" size="24" name="options[db]"';
    echo '		value="';
    if (file_exists($options['db'])) {
        echo $options['db'];
    } else {
        echo $options['db'];
        // _e( ': not found', 'banned-ips');
        // $options['db'] = 'WARNING: fail2ban database not found';
    }
    ;
    echo '">';
    echo '<p class="description">';
    _e('Select Fail2Ban DB', 'banned-ips');
    // echo strpos($options['db'], 'WARNING');
    echo "<br>";
    if (! file_exists($options['db'])) {
        // _e ('try:','banned-ips');
        // echo '<br>' . $options ['db_autodetect'];
    } else {
        // echo $options['db'] . "<br>";
        echo "<br>";
    }
    echo '</p></td>';
    
    echo '	<th colspan=1>';
    _e('Please note', 'banned-ips');
    echo '</th>';
    echo '	<td colspan=3>';
    if (file_exists($options['db'])) {
        _e('To change group and get read access run:', 'banned-ips');
    } else {
        echo $options['db'];
        _e(': not found', 'banned-ips');
    }
    echo '		<p class="description">';
    if (file_exists($options['db'])) {
        echo ((PHP_OS == "Linux") ? ('$ sudo chown :www-data ' . $options['db'] . '<br> $ sudo chmod g=+r ' . $options['db']) : ('$ sudo chown :www ' . $options['db'] . '<br> $ sudo chmod g=+r ' . $options['db'])) . "<br>";
    } else {
        _e('try:', 'banned-ips');
        echo '<br>' . $options['db_autodetect'];
    }
    echo '</p>';
    echo '	</td>';
    
    echo '</tr>';
    
    bannedips_options_savebutton_tr();
}

function bannedips_options_language()
{
    $options = get_option('bannedips', array());
    
    ?>
<!--  Language TODO:change to WP language-->
<tr>
	<th colspan=2 style="text-decoration: underline"><h2>
			<b>Language</b>
		</h2></th>
</tr>
<tr style="background-color: #FFFFFF;">
	<th><?php _e('Select Output Language', 'banned-ips') ?></th>
	<td><input type="checkbox" name="options[lang]" value="1"
		<?php echo isset($options['lang']) ? 'checked' : ''; ?>>
		<p class="description">
                        <?php _e('Select German', 'banned-ips') ?>
                    </p></td>
	<th colspan=4></th>
</tr>
<?php
    
    bannedips_options_savebutton_tr();
}

function bannedips_options_links()
{
    $options = get_option('bannedips', array());
    ?>
<!--  Links to AbuseIPDB and Blocklist -->
<tr>
	<th colspan=2 style="text-decoration: underline"><h2>
			<b>Links to AbuseIPDB and Blocklist</b>
		</h2></th>
</tr>
<tr style="background-color: #FFFFFF;">
	<th><?php _e('Show Links to AbuseIPDB.com', 'banned-ips') ?></th>
	<td><input type="checkbox" name="options[ab_links]" value="1"
		<?php echo isset($options['ab_links']) ? 'checked' : ''; ?>>
		<p class="description">
                        <?php _e('AbuseIPDB.com', 'banned-ips') ?>
                    </p></td>

	<th><?php _e('Show Links to BlockList.de', 'banned-ips') ?></th>
	<td valign="top"><input type="checkbox" name="options[bl_links]"
		value="1" <?php echo isset($options['bl_links']) ? 'checked' : ''; ?>>
		<p class="description">
                        <?php _e('BlockList.de', 'banned-ips') ?>
                    </p></td>
	<th colspan=2></th>
</tr>


<?php
    
    bannedips_options_savebutton_tr();
}

function bannedips_options_cron()
{
    $options = get_option('bannedips', array());
    
    ?>

<!--  Cron -->
<tr>
	<th colspan=2 style="text-decoration: underline"><h2>
			<b>Cronjobs</b>
		</h2></th>
</tr>
<tr style="background-color: #FFFFFF;">
	<th><?php _e('Activate Banned IPs Cron', 'banned-ips') ?></th>
	<td><input type="checkbox" name="options[sys_cron]" value="1"
		<?php echo isset($options['sys_cron']) ? 'checked' : ''; ?>>
		<p class="description">
                        <?php _e('Activate Banned IPs Cron Jobs', 'banned-ips') ?>
                    </p></td>

	<th><?php _e('Cron Method', 'banned-ips') ?></th>
	<td><select name="options[sys_cron_methode]">
			<option value="WordPress Cron"
				<?php if($options['sys_cron_methode']=="WordPress Cron"){echo " selected";}?>>WordPress
				Cron</option>
			<option value="OS Cronjob"
				<?php if($options['sys_cron_methode']=="OS Cronjob"){echo " selected";}?>>OS
				Cronjob (not done)</option>
			<option value="External Call"
				<?php if($options['sys_cron_methode']=="External Call"){echo " selected";}?>>External
				Call (not done)</option>

	</select>
		<p class="description">
                        <?php _e('Select Cron Method', 'banned-ips') ?>
                    </p></td>
	<th colspan=2></th>
</tr>
<?php
    
    // Cron Method
    if (isset($options['sys_cron']) && $options['sys_cron_methode'] !== 'WordPress Cron') {
        echo '<tr style="background-color:#FFFFFF;">';
        echo '<th><br></th>';
        echo '<td>';
        echo '		<p class="description"></p>';
        echo '</td>';
        echo '<th>';
        _e('Cron Method Activation', 'banned-ips');
        echo '</th>';
        echo '<td colspan=3>';
        _e('To activate do the follwing...', 'banned-ips');
        echo '<p class="description">';
        echo '$ sudo crontab...';
        echo '</p></td>';
        
        echo '</tr>';
    }
    
    bannedips_options_savebutton_tr();
}

function bannedips_options_log()
{
    global $bips;
    
    $options = get_option('bannedips', array());
    
    

    echo ('<!--  log -->');
    
    echo ('<tr>');
    echo ('	<th colspan=2 style="text-decoration: underline"><h2>');
    echo ('			<b>Log Level</b>');
    echo ('		</h2></th>');
    echo ('</tr>');
    echo ('<tr style="background-color: #FFFFFF;">');
   // echo ('	<th>');
   // _e('Activate Banned IPs log', 'banned-ips');
   // echo (' </th>');
   // echo ('	<td><input type="checkbox" name="options[sys_log]" value="0"');
   // 			echo isset($options['sys_cron']) ? 'checked' : '';
   // echo ('>');
   // echo ('		<p class="description">');
   // _e('Activate Banned IPs log', 'banned-ips');
   // echo ('                    </p></td>');
    
   
    
    echo ('	<th >');
    _e ('Log Level', 'banned-ips');
    echo ('</th>');
    echo ('	<td><select name="options[sys_log_level]">');
    echo ('			<option value="NONE"');
    					if($options['sys_log_level']=="NONE"){echo " selected";}
    					echo ('>NONE</option>');
    echo ('			<option value="NOTICE"');
    					if($options['sys_log_level']=="NOTICE"){echo " selected";}
    					echo ('>NOTICE</option>');
    echo ('			<option value="INFO"');
    					if($options['sys_log_level']=="INFO"){echo " selected";}
    					echo ('>INFO</option>');
    echo ('			<option value="DEBUG"');
    					if($options['sys_log_level']=="DEBUG"){echo " selected";}
    					echo ('>DEBUG</option>');
    
    echo ('	</select>');
    echo ('		<p class="description">');
    				_e('Select log level', 'banned-ips');
    echo ('                   </p></td>');
    echo ('	<th colspan=4></th>');
    echo ('</tr>');
    
    
    // show log file
    if ( $options['sys_log_level'] !== 'none') {
        echo '<tr style="background-color:#FFFFFF;" rowspan=3>';
        //echo '<th><br></th>';
        //echo '<td>';
        //echo '		<p class="description"></p>';
        //echo '</td>';
        echo '<th>';
        _e('Log File: ', 'banned-ips');
        echo '</th>';
        echo '<td colspan=5>';
        //_e('logs: ', 'banned-ips');
        echo '<textarea cols="80" rows="10">';
        echo $bips->get_logs();
        echo '</textarea></td>';
        
        echo '</tr>';
        echo '<tr>';
        echo '</tr>';
        echo '<tr>';
        echo '</tr>';
        
    }
    
    bannedips_options_savebutton_tr();
}


function bannedips_options_savebutton_tr()
{
    // echo '<tr style="text-align: right";>';
    echo '<tr style="text-align: right";>';
    echo '<th>';
    echo '</th>';
    echo '<td>';
    echo '</td>';
    echo '<th>';
    echo '</th>';
    echo '<td>';
    echo '</td>';
    echo '<th>';
    echo '</th>';
    bannedips_options_savebutton_td();
    echo '</tr>';
}

function bannedips_options_savebutton_td()
{
    
    // echo '<tr>';
    // echo '<th>';
    echo '<td>';
    bannedips_options_savebutton();
    echo '</td>';
    // echo '</th>';
    // echo '</tr>';
}

function bannedips_options_savebutton()
{
    
    // echo '<tr>';
    // echo '<th>';
    // echo '<td>';
    echo '<p class="submit">';
    echo '<input class="button button-primary" type="submit" name="save" value="';
    _e("Save", "banned-ips");
    echo '">';
    echo '</p>';
    // echo '</td>';
    // echo '</th>';
    // echo '</tr>';
}