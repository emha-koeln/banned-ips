<?php
/**
 * Banned-IPs-Widget.php
 * Part of banned-ips
 * v 0.3
 * (c) 2021 emha.koeln
 * License: GPLv2+
 *
 * Public Widget
 *
 * @since      0.3.0
 *
 * @package    banned-ips
 * @subpackage banned-ips/includes
 */

/**
 * Public Widget
 *
 * This class is the Public Widget.
 *
 * @since      0.3.0
 * @package    banned-ips
 * @subpackage banned-ips/includes
 * @author     emha.koeln
 */
// Wp Widget
// TODO no i18n in Widget 
class Banned_IPs_Widget extends WP_Widget{

    public $imgsrc = '';
    public $imgpath = '';                          
    
    public $args = array(
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget' => '</div></div>'
    );
    /**
     * Store plugin main class to allow public access.
     *
     * @since    20180622
     * @var object      The main class.
     */
    public $main;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    0.3.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( ) {
        
        // set base values for the widget (override parent)
        parent::__construct('Banned_IPs_Widget_ID',                      // ID
            'Banned-IPs', // Name
            array(
                'description' => __('Show Banned IPs Stats Graphs', 'banned-ips')
                
            )
        );
        
        add_action('widgets_init', function () {
            register_widget('Banned_IPs_Widget');
            // load_plugin_textdomain( 'banned-ips', false, str_replace('/cls', '', dirname(plugin_basename( __FILE__ ))) . '/languages/');
        }
        );
        
        
    }
    
    public function widget($args, $instance)
    {
        //global $Bips;
        
        echo $args['before_widget'];
        
        if (! empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
            // _e('Translate me', 'banned-ips');
        } else {
            $defaulttitle = __('Banned-IPs Stats last 24 hours', 'banned-ips');
            echo $args['before_title'] . apply_filters('widget_title', $defaulttitle) . $args['after_title'];
            // _e('Translate me', 'banned-ips');
        }
        
        echo '<ul><li><div class="">';
        
        $imgname_pre = '';
        $imagename = 'f2b_graph';
        $imgname_post = '1';
        $imagetype = 'png';
        
        if ($instance['transparency'] == 'checked') {
            $imgname_pre = 't_';
        }
        
        if ($instance['period'] == 'last hour') {
            _e('since last hour', 'banned-ips');
            $imgname_post = '1';
        } elseif ($instance['period'] == 'last 24 hours') {
            _e('since last 24 hour', 'banned-ips');
            $imgname_post = '24';
        } elseif ($instance['period'] == 'last week') {
            _e('since last week', 'banned-ips');
            $imgname_post = 'week';
        } elseif ($instance['period'] == 'last month') {
            _e('since last month', 'banned-ips');
            $imgname_post = 'month';
        } elseif ($instance['period'] == 'last year') {
            _e('since last year', 'banned-ips');
            $imgname_post = 'year';
            // $this->imgpath = str_replace('/cls', '', plugin_dir_path(__FILE__) ) . 'img/f2b_graph_year.png';
        } elseif ($instance['period'] == 'all') {
            _e('all', 'banned-ips');
            $imgname_post = 'all';
        }
        ;
        
        $this->imgsrc = str_replace( 'includes' , '', plugin_dir_url(__FILE__) ) . 'img/' . $imgname_pre . $imagename . '_' . $imgname_post . '.' . $imagetype;
        $this->imgpath = str_replace( 'includes' , '', plugin_dir_path(__FILE__)) . 'img/' . $imgname_pre . $imagename . '_' . $imgname_post . '.' . $imagetype;
        
        echo '</div>';
        
        echo '<div class="image">';
        // echo '<div>';
        if (! empty($instance['image'])) {
            
            echo ('<img src="' . $instance['image'] . '" alt="Banned IPs Stats">');
            
        } else {
            
            if (file_exists($this->imgpath)) {
                echo '<br><a href="' . $this->imgsrc . '" target="_blank">';
                echo ('<img src="' . $this->imgsrc . '" alt="Current Banned IPs Stats">');
                echo '</a>';
            } else {
                
                global $wp;
                $current_url = home_url(add_query_arg(array(), $wp->request));
                echo '<br>';
                _e('...waiting for Graph to be ceated ', 'banned-ips');
                // echo '<br>(under: ' . $this->imgpath . ')';
                echo '<br><a href="' . $current_url . '">';
                _e('please reload page', 'banned-ips');
                echo '</a>';
            }
        }
        
        echo '</div></li></ul>';
        // echo '</div>';
        
        // _e('Translate me', 'banned-ips');
        
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = ! empty($instance['title']) ? $instance['title'] : esc_html__('Banned-IPs ', 'banned-ips');
        $period = ! empty($instance['period']) ? $instance['period'] : esc_html__('last 24 hours', 'banned_ips');
        $transparency = ! empty($instance['$transparency']) ? $instance['$transparency'] : esc_html__('checked', 'banned_ips');
        // $image = ! empty( $instance['image'] ) ? $instance['image'] : $this->imgsrc;
        ?>

<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'Title' ) ); ?>"><?php echo esc_html__( 'Title:', 'banned_ips' ); ?></label>
	<input class="widefat"
		id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
		name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
		type="text" value="<?php echo esc_attr( $title ); ?>">
</p>

<div>
        	<?php 
/*
               * //var_dump($transparency);
               * echo '<br>';
               * echo 'vd: $this->get_settings()' . var_dump($this->get_settings()). '<br/>';
               * //echo 'vd: $this->id' . var_dump($this->id) . '<br/>';
               *
               * echo '$this->number' . $this->number . '<br/>';
               * echo '$this->get_settings()[$this->number][\'transparency\']' . $this->get_settings()[$this->number]['transparency'] . '<br/>';
               * //$transparency = $this->get_settings()[$this->number]['transparency'];
               *
               * //echo 'ID: ' . $this->get_field_id( 'transparency' ) . '<br/>';
               * //echo 'Name: ' . $this->get_field_name( 'transparency' ). '<br/>';
               * echo 'transparency: ' . $transparency. '<br/>';
               */
        ?>
        </div>



<p>
	<label
		for="<?php echo esc_attr( $this->get_field_id( 'Transparency' ) ); ?>"><?php echo esc_html__( 'Transparency:', 'banned_ips' ); ?></label>
	<input class="widefat"
		id="<?php echo $this->get_field_id( 'transparency' ) ; ?>"
		name="<?php echo $this->get_field_name( 'transparency' ) ; ?>"
		type="checkbox"
		<?php
        if ($this->get_settings()[$this->number]['transparency'] == 'checked') {
            echo 'checked="' . $transparency . '"';
            // echo $transparency ;
        }
        ?>
		value="<?php echo $transparency ; ?>"> <label
		for="<?php echo $this->get_field_id( 'transparency' ) ; ?>">
           		<?php //echo $transparency; ?>
            </label>
</p>

<p>
 
            <?php 
// echo $period;
              // echo __('last week', 'banned-ips'); ?>
            <label for="<?php echo $this->get_field_id('period'); ?>"><?php _e ('Select Period','banned-ips')?></label>
	<select class="widefat"
		name="<?php echo $this->get_field_name('period'); ?>"
		id="<?php echo $this->get_field_id('period'); ?>"
		value="<?php echo $period; ?>">
		<option value="last hour"
			<?php if($period == 'last hour'){ echo 'selected';}?>>
                	<?php _e ('last hour','banned-ips') ?>
                </option>
		<option value="last 24 hours"
			<?php if($period == 'last 24 hours'){ echo 'selected';}?>>
                	<?php _e ('last 24 hours','banned-ips') ?>
                </option>
		<option value="last week"
			<?php if($period == 'last week'){ echo 'selected';}?>>
                	<?php _e ('last week','banned-ips') ?>
                </option>
		<option value="last month"
			<?php if($period == 'last month'){ echo 'selected';}?>>
               	 	<?php _e ('last month','banned-ips') ?>
                </option>
		<option value="last year"
			<?php if($period == 'last year'){ echo 'selected';}?>>
                	<?php _e ('last year','banned-ips') ?>
                </option>
		<option value="all" <?php if($period == 'all'){ echo 'selected';}?>>
                	<?php _e ('all','banned-ips') ?>
                </option>
	</select>
</p>

<!-- 
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'Image' ) ); ?>"><?php echo esc_html__( 'Image:', 'banned_ips' ); ?></label>
           <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" cols="30" rows="10"><?php echo esc_attr( $imgsrc ); ?></textarea>
        </p>
        -->
<?php
    }

    public function update($new_instance, $old_instance)
    {
        //global $Bips;
        $instance = array();
        
        //$Bips->log(__FUNCTION__, 'INFO');
        
        $instance['title'] = (! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        
        $instance['period'] = (! empty($new_instance['period'])) ? strip_tags($new_instance['period']) : '';
        
        $instance['transparency'] = (! empty($new_instance['transparency'])) ? $new_instance['transparency'] : '';
        // $instance['transparency'] = $new_instance['transparency'];
        
        $instance['image'] = (! empty($new_instance['image'])) ? $new_instance['image'] : '';
        
        return $instance;
    }
}
// $bannedips_widget = new BannedIPs_Widget();

?>