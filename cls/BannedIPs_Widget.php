<?php
/**
 * BannedIPs_Widget.php
 * Part of banned-ips
 * v 0.1.5.alpha
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */
defined( 'ABSPATH' ) or 
	die( );

// Wp Widget
class BannedIPs_Widget extends WP_Widget{

	private $imgsrc = '';
	private $imgpath = '';
	//initialise widget values
	public function __construct(){
		//set base values for the widget (override parent)
		parent::__construct(
				'BannedIPs_Widget_ID',	// ID
				'BannedIPs', // Name
				array('description' => __('Show Banned IPs Stats Graphs','banned-ips'))
				);
		
		$this->imgsrc = str_replace('/cls', '', plugin_dir_url(__FILE__) ) . 'img/f2b_graph_24.png';
		$this->imgsrc = str_replace('/cls', '', plugin_dir_path(__FILE__) ) . 'img/f2b_graph_24.png';
		
		add_action( 'widgets_init', function() {
			register_widget( 'BannedIPs_Widget' );
			//load_plugin_textdomain( 'banned-ips', false, str_replace('/cls', '', dirname(plugin_basename( __FILE__ ))) . '/languages/');
		});
		
	}

	public $args = array(
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => '</h4>',
			'before_widget' => '<div class="widget-wrap">',
			'after_widget'  => '</div></div>'
	);
	
	public function widget( $args, $instance ) {
		
		echo $args['before_widget'];
		
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			//_e('Translate me', 'banned-ips');
		} else {
			$defaulttitle =  __('Banned-IPs Stats last 24 hours','banned-ips' );
			echo $args['before_title'] . apply_filters( 'widget_title', $defaulttitle ) . $args['after_title'];
			//_e('Translate me', 'banned-ips');
		}
		
		echo '<ul><li><div class="">';
		if ( $instance['period'] == 'last hour'){
			_e ('since last hour','banned-ips');
			$this->imgsrc = str_replace('/cls', '', plugin_dir_url(__FILE__) ) . 'img/f2b_graph_1.png';
			$this->imgpath = str_replace('/cls', '', plugin_dir_path(__FILE__) ) . 'img/f2b_graph_1.png';
		}elseif ( $instance['period'] == 'last 24 hours'){
			_e ('since last 24 hour','banned-ips');
			$this->imgsrc = str_replace('/cls', '', plugin_dir_url(__FILE__) ) . 'img/f2b_graph_24.png';
			$this->imgpath = str_replace('/cls', '', plugin_dir_path(__FILE__) ) . 'img/f2b_graph_24.png';
		}elseif ( $instance['period'] == 'last week'){
			_e ('since last week','banned-ips');
			$this->imgsrc = str_replace('/cls', '', plugin_dir_url(__FILE__) ) . 'img/f2b_graph_week.png';
			$this->imgpath = str_replace('/cls', '', plugin_dir_path(__FILE__) ) . 'img/f2b_graph_week.png';
		}elseif ( $instance['period'] == 'last month'){
			_e ('since last month','banned-ips');
			$this->imgsrc = str_replace('/cls', '', plugin_dir_url(__FILE__) ) . 'img/f2b_graph_month.png';
			$this->imgpath = str_replace('/cls', '', plugin_dir_path(__FILE__) ) . 'img/f2b_graph_month.png';
		}elseif ( $instance['period'] == 'last year'){
			_e ('since last year','banned-ips');
			$this->imgsrc = str_replace('/cls', '', plugin_dir_url(__FILE__) ) . 'img/f2b_graph_year.png';
			$this->imgpath = str_replace('/cls', '', plugin_dir_path(__FILE__) ) . 'img/f2b_graph_year.png';
		}elseif ( $instance['period'] == 'all'){
			_e ('all','banned-ips');
			$this->imgsrc = str_replace('/cls', '', plugin_dir_url(__FILE__) ) . 'img/f2b_graph_all.png';
			$this->imgpath = str_replace('/cls', '', plugin_dir_path(__FILE__) ) . 'img/f2b_graph_all.png';
		}
		echo '</div>';
		
		echo '<div class="image">';
		//echo '<div>'; 
		if (! empty($instance['image'])) {
			
			echo ('<img src="' . $instance['image'] . '" alt="Banned IPs Stats">');

		} else{
			
			if(file_exists($this->imgpath)){			
				echo '<br><a href="' . $this->imgsrc . '" target="_blank">';
				echo ('<img src="' . $this->imgsrc . '" alt="Current Banned IPs Stats">');
				echo '</a>';
			}else{
				
				global $wp;
				$current_url = home_url(add_query_arg(array(),$wp->request));
				echo '<br>';
				_e ('...waiting for Graph to be ceated', 'banned-ips');
				//echo '<br>(' . $this->imgpath . ')';
				echo '<br><a href="' . $current_url . '">'; 
				_e ('please reload page', 'banned-ips');
				echo '</a>';
				
			}

		}
		
		echo '</div></li></ul>';
		//echo '</div>';
		
		//_e('Translate me', 'banned-ips');
		
		echo $args['after_widget'];
		
	}
	
	public function form( $instance ) {
		
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Banned-IPs ','banned-ips' );
		$period = ! empty( $instance['period'] ) ? $instance['period'] : esc_html__( 'last 24 hours', 'banned_ips' );
		//$image = ! empty( $instance['image'] ) ? $instance['image'] : esc_html__( '', 'banned_ips' );
		//$image = ! empty( $instance['image'] ) ? $instance['image'] : $this->imgsrc;
		?>
		
        <p>
         <label for="<?php echo esc_attr( $this->get_field_id( 'Title' ) ); ?>"><?php echo esc_html__( 'Title:', 'banned_ips' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        
        <p>
 
            <?php //echo $period; 
            	//echo __('last week', 'banned-ips');?>
            <label for="<?php echo $this->get_field_id('period'); ?>"><?php _e ('Select Period','banned-ips')?></label>
            <select class="widefat" name="<?php echo $this->get_field_name('period'); ?>" id="<?php echo $this->get_field_id('period'); ?>" value="<?php echo $period; ?>">
                <option value="last hour" <?php if($period == 'last hour'){ echo 'selected';}?>>
                	<?php _e ('last hour','banned-ips') ?>
                </option>
                <option value="last 24 hours" <?php if($period == 'last 24 hours'){ echo 'selected';}?>>
                	<?php _e ('last 24 hours','banned-ips') ?>
                </option>
                <option value="last week" <?php if($period == 'last week'){ echo 'selected';}?>>
                	<?php _e ('last week','banned-ips') ?>
                </option>
                <option value="last month" <?php if($period == 'last month'){ echo 'selected';}?>>
               	 	<?php _e ('last month','banned-ips') ?>
                </option>
                <option value="last year" <?php if($period == 'last year'){ echo 'selected';}?>>
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
 
    public function update( $new_instance, $old_instance ) {
 
        $instance = array();
 
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        
        $instance['period'] = ( !empty( $new_instance['period'] ) ) ? strip_tags( $new_instance['period'] ) : '';
        //$instance['title'] = strip_tags( esc_html__( 'Banned-IPs ','banned-ips' ) . $instance['period'] );
        
        $instance['image'] = ( !empty( $new_instance['image'] ) ) ? $new_instance['image'] : '';
 
        return $instance;
    }
 
}
//$bannedips_widget = new BannedIPs_Widget();

	
	
?>