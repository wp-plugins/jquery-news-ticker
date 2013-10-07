<?php
/*
Plugin Name: Jquery news ticker
Description: Jquery news ticker plugin brings a lightweight, flexible and easy to configure news ticker plugin to wordpress website. This plugin adds scrolling horizontal tickers to your site.
Author: Gopi.R
Version: 1.0
Plugin URI: http://www.gopiplus.com/work/2013/10/03/jquery-news-ticker-wordpress-plugin/
Author URI: http://www.gopiplus.com/work/2013/10/03/jquery-news-ticker-wordpress-plugin/
Donate link: http://www.gopiplus.com/work/2013/10/03/jquery-news-ticker-wordpress-plugin/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $Jntp_db_version;
define("Jntp_Table", $wpdb->prefix . "jquery_newsticker");
define("Jntp_UNIQUE_NAME", "jquery-news-ticker");
define("Jntp_TITLE", "Jquery news ticker");
define('Jntp_FAV', 'http://www.gopiplus.com/work/2013/10/03/jquery-news-ticker-wordpress-plugin/');
define('Jntp_LINK', 'Check official website for more information <a target="_blank" href="'.Jntp_FAV.'">click here</a>');
$Jntp_db_version = "1.0";

function newsticker( $group = "", $title = "", $direction = "", $type = "", $pause = "", $speed = "" )
{
	global $wpdb;
	$ArrInput = array();
	$ArrInput["group"] = $group;
	$ArrInput["title"] = $title;
	$ArrInput["direction"] = $direction;
	$ArrInput["type"] = $type;
	$ArrInput["pause"] = $pause;
	$ArrInput["speed"] = $speed;
	echo Jntp_shortcode( $ArrInput );
}

function Jntp_shortcode( $atts ) 
{
	global $wpdb;

	// [jquery-news-ticker group="GROUP1" title="News" direction="ltr" type="reveal" pause="2000" speed="0.10"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$Jntp_group = isset($atts['group']) ? $atts['group'] : '';
	$Jntp_title = isset($atts['title']) ? $atts['title'] : '';
	$Jntp_direction = isset($atts['direction']) ? $atts['direction'] : '';
	$Jntp_type = isset($atts['type']) ? $atts['type'] : '';
	$Jntp_pause = isset($atts['pause']) ? $atts['pause'] : '';
	$Jntp_speed = isset($atts['speed']) ? $atts['speed'] : '';
	
	if(!is_numeric($Jntp_pause))
	{ 
		$Jntp_pause = 2000; 
	}
	
	if($Jntp_type <> "reveal" && $Jntp_type <> "fade")
	{
		$Jntp_type = "reveal";
	}
	
	if($Jntp_direction <> "ltr" && $Jntp_direction <> "rtl")
	{
		$Jntp_direction = "ltr";
	}
	
	if($Jntp_speed == "")
	{
		$Jntp_speed = "0.10";
	}
	
	$sSql = "select * from ".Jntp_Table." where Jntp_status = 'YES'";
	if($Jntp_group <> "" )
	{
		$sSql = $sSql . " and Jntp_group='$Jntp_group'";
	}
	
	$sSql = $sSql . " and ( Jntp_dateend >= NOW() or Jntp_dateend = '0000-00-00 00:00:00' )";
	$sSql = $sSql . " Order by Jntp_order";

	$Jntp = "";
	$data = $wpdb->get_results($sSql);

	global $Jntp_cssclass;
	if (!isset($Jntp_cssclass) || $Jntp_cssclass !== true)
	{
		$Jntp_cssclass = true;
		$Jntp_classname = "gticker-news1";
	}
	else
	{
		$Jntp_classname = "gticker-news2";
	}
	
	
	if ( ! empty($data) ) 
	{
		$Jntp = $Jntp . '<ul id="'.$Jntp_classname.'" class="gticker-hidden">';
		foreach ( $data as $data ) 
		{
			$Jntp_id = $data->Jntp_id;
			$Jntp_text = stripslashes($data->Jntp_text);
			$Jntp_link = $data->Jntp_link;
			$Jntp = $Jntp . '<li class="gticker-item"><a href="'.$Jntp_link.'">'.$Jntp_text.'</a></li>';
		}		
		$Jntp = $Jntp . '</ul>';
		$Jntp = $Jntp . '<script type="text/javascript">';
		$Jntp = $Jntp . 'jQuery(function () {';
		$Jntp = $Jntp . "jQuery('#".$Jntp_classname."').ticker({";
		$Jntp = $Jntp . 'speed: '.$Jntp_speed.', ';
		$Jntp = $Jntp . 'htmlFeed: true, ';
		$Jntp = $Jntp . "titleText: '".$Jntp_title ."', ";
		$Jntp = $Jntp . "direction: '".$Jntp_direction."',   ";
		$Jntp = $Jntp . 'controls: false,'; 
		$Jntp = $Jntp . "displayType: '".$Jntp_type."', "; 
		$Jntp = $Jntp . 'pauseOnItems: '.$Jntp_pause.', ';
		$Jntp = $Jntp . 'fadeInSpeed: 600,';
		$Jntp = $Jntp . 'fadeOutSpeed: 300 ';
		$Jntp = $Jntp . '});';
		$Jntp = $Jntp . '});';
		$Jntp = $Jntp . '</script>';
	}
	else
	{
		// No records available.
	}
	
	return $Jntp;
}

function Jntp_install() 
{
	global $wpdb, $Jntp_db_version;
	$Jntp_pluginversion = get_option("Jntp_pluginversion");
	
	if($wpdb->get_var("show tables like '". Jntp_Table . "'") != Jntp_Table)
	{
		$Jntp_tableexists = "NO";
	}
	
	if($Jntp_db_version != $Jntp_pluginversion) 
	{
		$sSql = "CREATE TABLE ". Jntp_Table . " (
			 Jntp_id mediumint(9) NOT NULL AUTO_INCREMENT,
			 Jntp_text text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			 Jntp_link VARCHAR(1024) DEFAULT '#' NOT NULL,
			 Jntp_order int(11) NOT NULL default '0',
			 Jntp_status char(3) NOT NULL default 'YES',
			 Jntp_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			 Jntp_group VARCHAR(100) DEFAULT 'GROUP1' NOT NULL,
			 Jntp_dateend datetime DEFAULT '9999-12-31 00:00:00' NOT NULL,
			 Jntp_extra1 VARCHAR(100) NOT NULL default '' ,
			 Jntp_extra2 VARCHAR(100) NOT NULL default '' ,
			 Jntp_extra3 VARCHAR(100) NOT NULL default '' ,
			 UNIQUE KEY Jntp_id (Jntp_id)
		  );";  
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sSql );
	}
	
	if($Jntp_pluginversion == "")
	{
		add_option('Jntp_pluginversion', "1.0");
	}
	else
	{
		update_option( "Jntp_pluginversion", $Jntp_db_version );
	}
	
	if($Jntp_tableexists == "NO")
	{
		$welcome_text = "Congratulations, you just completed the installation!";		
		$rows_affected = $wpdb->insert( Jntp_Table , array( 'Jntp_text' => $welcome_text) );
	}
}

function Jntp_deactivation() 
{
	// No action required.
}

function Jntp_admin()
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/content-management-edit.php');
			break;
		case 'add':
			include('pages/content-management-add.php');
			break;
		default:
			include('pages/content-management-show.php');
			break;
	}
}

function Jntp_add_to_menu() 
{
	add_options_page('Jquery news ticker', 'Jquery news ticker', 'manage_options', 'jquery-news-ticker', 'Jntp_admin' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'Jntp_add_to_menu');
}

function Jntp_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script('jquery');
		wp_enqueue_style( 'jquery.ticker', get_option('siteurl').'/wp-content/plugins/jquery-news-ticker/inc/jquery-news-ticker.css');
		wp_enqueue_script('jquery.news.ticker', get_option('siteurl').'/wp-content/plugins/jquery-news-ticker/inc/jquery-news-ticker.js');
	}
}   


class Jntp_widget_register extends WP_Widget 
{
	function __construct() 
	{
		$widget_ops = array('classname' => 'widget_text newsticker-widget', 'description' => __('Jquery news ticker'), 'jquery-news-ticker');
		parent::__construct('jquery-news-ticker', __('Jquery news ticker', 'jquery-news-ticker'), $widget_ops);
	}
	
	function widget( $args, $instance ) 
	{
		extract( $args, EXTR_SKIP );

		$title 				= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$Jntp_titletext		= $instance['Jntp_titletext'];
		$Jntp_direction		= $instance['Jntp_direction'];
		$Jntp_displaytype	= $instance['Jntp_displaytype'];
		$Jntp_pause			= $instance['Jntp_pause'];
		$Jntp_group			= $instance['Jntp_group'];

		echo $args['before_widget'];
		if ( ! empty( $title ) )
		{
			echo $args['before_title'] . $title . $args['after_title'];
		}
		// Call widget method
		$arr = array();
		$arr["title"] 		= $Jntp_titletext;
		$arr["direction"] 	= $Jntp_direction;
		$arr["type"] 		= $Jntp_displaytype;
		$arr["pause"] 		= $Jntp_pause;
		$arr["group"] 		= $Jntp_group;
		echo Jntp_shortcode($arr);
		
		// Call widget method
		echo $args['after_widget'];
	}
	
	function update( $new_instance, $old_instance ) 
	{
		$instance 						= $old_instance;
		$instance['title'] 				= ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['Jntp_titletext'] 	= ( ! empty( $new_instance['Jntp_titletext'] ) ) ? strip_tags( $new_instance['Jntp_titletext'] ) : '';
		$instance['Jntp_direction'] 	= ( ! empty( $new_instance['Jntp_direction'] ) ) ? strip_tags( $new_instance['Jntp_direction'] ) : '';
		$instance['Jntp_displaytype'] 	= ( ! empty( $new_instance['Jntp_displaytype'] ) ) ? strip_tags( $new_instance['Jntp_displaytype'] ) : '';
		$instance['Jntp_pause'] 		= ( ! empty( $new_instance['Jntp_pause'] ) ) ? strip_tags( $new_instance['Jntp_pause'] ) : '';
		$instance['Jntp_group'] 		= ( ! empty( $new_instance['Jntp_group'] ) ) ? strip_tags( $new_instance['Jntp_group'] ) : '';
		return $instance;
	}

	function form( $instance ) 
	{
		$defaults = array(
			'title' 		=> '',
            'Jntp_titletext' 	=> '',
            'Jntp_direction' 	=> '',
            'Jntp_displaytype' 	=> '',
			'Jntp_pause' 		=> '',
			'Jntp_group' 		=> ''
        );
		
		$instance 			= wp_parse_args( (array) $instance, $defaults);
        $title 				= $instance['title'];
        $Jntp_titletext 	= $instance['Jntp_titletext'];
        $Jntp_direction 	= $instance['Jntp_direction'];
        $Jntp_displaytype 	= $instance['Jntp_displaytype'];
		$Jntp_pause 		= $instance['Jntp_pause'];
		$Jntp_group 		= $instance['Jntp_group'];
	
		?>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget title', 'jquery-news-ticker'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('Jntp_titletext'); ?>"><?php _e('News prefix text', 'jquery-news-ticker'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('Jntp_titletext'); ?>" name="<?php echo $this->get_field_name('Jntp_titletext'); ?>" type="text" value="<?php echo $Jntp_titletext; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('Jntp_direction'); ?>"><?php _e('Direction', 'jquery-news-ticker'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('Jntp_direction'); ?>" name="<?php echo $this->get_field_name('Jntp_direction'); ?>">
				<option value="ltr" <?php $this->Jntp_render_selected($Jntp_direction=='ltr'); ?>>Left to Right</option>
				<option value="rtl" <?php $this->Jntp_render_selected($Jntp_direction=='rtl'); ?>>Right to Left</option>
			</select>
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('Jntp_displaytype'); ?>"><?php _e('Display type', 'jquery-news-ticker'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('Jntp_displaytype'); ?>" name="<?php echo $this->get_field_name('Jntp_displaytype'); ?>">
				<option value="reveal" <?php $this->Jntp_render_selected($Jntp_displaytype=='reveal'); ?>>Reveal</option>
				<option value="fade" <?php $this->Jntp_render_selected($Jntp_displaytype=='fade'); ?>>Fade</option>
			</select>
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('Jntp_pause'); ?>"><?php _e('Pause time (Only number)', 'jquery-news-ticker'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('Jntp_pause'); ?>" name="<?php echo $this->get_field_name('Jntp_pause'); ?>" type="text" value="<?php echo $Jntp_pause; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('Jntp_group'); ?>"><?php _e('Group', 'jquery-news-ticker'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('Jntp_group'); ?>" name="<?php echo $this->get_field_name('Jntp_group'); ?>">
				<option value=''>Select</option>
				<option value='GROUP1' <?php $this->Jntp_render_selected($Jntp_group=='GROUP1'); ?>>GROUP1</option>
				<option value='GROUP2' <?php $this->Jntp_render_selected($Jntp_group=='GROUP2'); ?>>GROUP2</option>
				<option value='GROUP3' <?php $this->Jntp_render_selected($Jntp_group=='GROUP3'); ?>>GROUP3</option>
				<option value='GROUP4' <?php $this->Jntp_render_selected($Jntp_group=='GROUP4'); ?>>GROUP4</option>
				<option value='GROUP5' <?php $this->Jntp_render_selected($Jntp_group=='GROUP5'); ?>>GROUP5</option>
				<option value='GROUP6' <?php $this->Jntp_render_selected($Jntp_group=='GROUP6'); ?>>GROUP6</option>
				<option value='GROUP7' <?php $this->Jntp_render_selected($Jntp_group=='GROUP7'); ?>>GROUP7</option>
				<option value='GROUP8' <?php $this->Jntp_render_selected($Jntp_group=='GROUP8'); ?>>GROUP8</option>
				<option value='GROUP9' <?php $this->Jntp_render_selected($Jntp_group=='GROUP9'); ?>>GROUP9</option>
				<option value='GROUP10' <?php $this->Jntp_render_selected($Jntp_group=='GROUP10'); ?>>GROUP10</option>
			</select>
			
        </p>
		<p><?php echo Jntp_LINK; ?></p>
		<?php
	}

	function Jntp_render_selected($var) 
	{
		if ($var==1 || $var==true) 
		{
			echo 'selected="selected"';
		}
	}
}

function Jntp_widget_loading()
{
	register_widget( 'Jntp_widget_register' );
}

add_shortcode( 'jquery-news-ticker', 'Jntp_shortcode' );
add_action('wp_enqueue_scripts', 'Jntp_add_javascript_files');
register_activation_hook(__FILE__, 'Jntp_install');
register_deactivation_hook(__FILE__, 'Jntp_deactivation');
add_action( 'widgets_init', 'Jntp_widget_loading');
?>