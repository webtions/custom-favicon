<?php
/**
 * Plugin Name: Custom Favicon
 * Plugin URI: https://themeist.com/plugins/wordpress/custom-favicon/
 * Description: Adds support for custom favicons across WordPress frontend, admin, and login pages, including light/dark mode support.
 * Version: 1.1.0
 * Author: Harish Chouhan, Themeist
 * Author URI: https://themeist.com/
 * Author Email: support@themeist.com/
 * Text Domain: custom-favicon
 * License: GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 *
 * @package Themeist_Custom_Favicon
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Themeist_Custom_Favicon' ) ) {

	class Themeist_Custom_Favicon {

		function __construct() {
			add_action( 'init', array( &$this, 'load_localisation' ), 0 );
			add_action( 'admin_menu', array( &$this, 'dot_cfi_menu' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'dot_cfi_assets' ) );
			add_action( 'admin_init', array( &$this, 'dot_cfi_settings' ) );
			add_action( 'wp_head', array( &$this, 'dot_cfi_favicon_frontend' ) );
			add_action( 'admin_head', array( &$this, 'dot_cfi_favicon_backend' ) );
			add_action( 'login_head', array( &$this, 'dot_cfi_favicon_backend' ) );
		}

		public function load_localisation() {
			load_plugin_textdomain( 'dot_cfi', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}

		function constants() {
			define( 'DOT_CFI_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		}

		function dot_cfi_menu() {
			$page_title = __('Custom Favicon', 'dot_cfi');
			$menu_title = __('Custom Favicon', 'dot_cfi');
			$capability = 'manage_options';
			$menu_slug = 'dot_cfi';
			$function =  array( &$this, 'dot_cfi_menu_contents');
			add_options_page($page_title, $menu_title, $capability, $menu_slug, $function);
		}

		function dot_cfi_assets() {
		    if (isset($_GET['page']) && $_GET['page'] == 'dot_cfi') {
    			wp_enqueue_style( 'thickbox' );
   				wp_enqueue_script( 'thickbox' );
    			wp_enqueue_script( 'media-upload' );
		        wp_register_script('dot_cfi_admin', plugins_url( '/js/dot_cfi_admin.js' , __FILE__ ), array( 'thickbox', 'media-upload' ));
		        wp_enqueue_script('dot_cfi_admin');
		    }
		}

		public function dot_cfi_settings() {
			register_setting( 'dot_cfi_settings', 'dot_cfi_settings', array(&$this, 'settings_validate') );
			add_settings_section( 'favicon', __( 'Custom Favicon & Apple touch icon', 'dot_cfi' ), array( &$this, 'section_favicon' ), 'dot_cfi_settings' );
			add_settings_field( 'favicon_frontend_url', __( 'Favicon for Website', 'dot_cfi' ), array( &$this, 'section_favicon_frontend_url' ), 'dot_cfi_settings', 'favicon' );
			add_settings_field( 'favicon_backend_url', __( 'Favicon for Admin', 'dot_cfi' ), array( &$this, 'section_favicon_backend_url' ), 'dot_cfi_settings', 'favicon' );
			add_settings_field( 'apple_icon_frontend_url', __( 'Apple Touch Icon for Website', 'dot_cfi' ), array( &$this, 'section_apple_icon_frontend_url' ), 'dot_cfi_settings', 'favicon' );
			add_settings_field( 'apple_icon_backend_url', __( 'Apple Touch Icon for Admin', 'dot_cfi' ), array( &$this, 'section_apple_icon_backend_url' ), 'dot_cfi_settings', 'favicon' );
			add_settings_field( 'apple_icon_style', __( 'Basic Apple Touch Icon', 'dot_cfi' ), array( &$this, 'section_apple_icon_style' ), 'dot_cfi_settings', 'favicon' );
		}

		public function dot_cfi_menu_contents() {
		?>
			<div class="wrap">
				<div id="icon-options-general" class="icon32"><br></div>
				<h2><?php _e('Custom Favicon Settings', 'dot_cfi'); ?></h2>
				<form method="post" action="options.php">
					<?php settings_fields('dot_cfi_settings'); ?>
					<?php do_settings_sections('dot_cfi_settings'); ?>
					<p class="submit">
						<input name="Submit" type="submit" class="button-primary" value="<?php _e('Save Changes', 'dot_cfi'); ?>" />
					</p>
				</form>
			</div>
		<?php
		}

		function section_favicon() {}

		function section_favicon_frontend_url() {
		    $options = get_option( 'dot_cfi_settings' );
		    ?>
		    <span class='upload'>
		        <input type='text' id='dot_cfi_settings[favicon_frontend_url]' class='regular-text text-upload' name='dot_cfi_settings[favicon_frontend_url]' value='<?php echo esc_url( $options["favicon_frontend_url"] ); ?>'/>
		        <input type='button' class='button button-upload' value='Upload an image'/></br>
		        <img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["favicon_frontend_url"] ); ?>' class='preview-upload' />
		    </span>
		    <?php
		}

		function section_favicon_backend_url() {
		    $options = get_option( 'dot_cfi_settings' );
		    ?>
		    <span class='upload'>
		        <input type='text' id='dot_cfi_settings[favicon_backend_url]' class='regular-text text-upload' name='dot_cfi_settings[favicon_backend_url]' value='<?php echo esc_url( $options["favicon_backend_url"] ); ?>'/>
		        <input type='button' class='button button-upload' value='Upload an image'/></br>
		        <img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["favicon_backend_url"] ); ?>' class='preview-upload' />
		    </span>
		    <?php
		}

		function section_apple_icon_frontend_url() {
		    $options = get_option( 'dot_cfi_settings' );
		    ?>
		    <span class='upload'>
		        <input type='text' id='dot_cfi_settings[apple_icon_frontend_url]' class='regular-text text-upload' name='dot_cfi_settings[apple_icon_frontend_url]' value='<?php echo esc_url( $options["apple_icon_frontend_url"] ); ?>'/>
		        <input type='button' class='button button-upload' value='Upload an image'/></br>
		        <img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["apple_icon_frontend_url"] ); ?>' class='preview-upload' />
		    </span>
		    <?php
		}

		function section_apple_icon_backend_url() {
		    $options = get_option( 'dot_cfi_settings' );
		    ?>
		    <span class='upload'>
		        <input type='text' id='dot_cfi_settings[apple_icon_backend_url]' class='regular-text text-upload' name='dot_cfi_settings[apple_icon_backend_url]' value='<?php echo esc_url( $options["apple_icon_backend_url"] ); ?>'/>
		        <input type='button' class='button button-upload' value='Upload an image'/></br>
		        <img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["apple_icon_backend_url"] ); ?>' class='preview-upload' />
		    </span>
		    <?php
		}

		function section_apple_icon_style() {
			$options = get_option( 'dot_cfi_settings' );
			if( !isset($options['apple_icon_style']) ) $options['apple_icon_style'] = '0';
			echo '<input type="hidden" name="dot_cfi_settings[apple_icon_style]" value="0" />
			<label><input type="checkbox" name="dot_cfi_settings[apple_icon_style]" value="1"'. (($options['apple_icon_style']) ? ' checked="checked"' : '') .' />
			 Disable Curved Border & reflective shine for Apple touch icon</label><br />';
		}

		function settings_validate($input) {
			return $input;
		}

		function dot_cfi_favicon_frontend() {
			$options =  get_option('dot_cfi_settings');
			if( $options['favicon_frontend_url'] != "" ) {
		        echo '<link rel="shortcut icon" href="'.  esc_url( $options["favicon_frontend_url"] )  .'"/>'."\n";
		    }
		    if( $options['apple_icon_frontend_url'] != "" ) {
		    	if ( $options['apple_icon_style'] == '0') {
		        	echo '<link rel="apple-touch-icon" href="'.  esc_url( $options["apple_icon_frontend_url"] )  .'"/>'."\n";
		    	} else {
		    		echo '<link rel="apple-touch-icon-precomposed" href="'.  esc_url( $options["apple_icon_frontend_url"] )  .'"/>'."\n";
		    	}
		    }
		}

		function dot_cfi_favicon_backend() {
			$options =  get_option('dot_cfi_settings');
			if( $options['favicon_backend_url'] != "" ) {
		        echo '<link rel="shortcut icon" href="'.  esc_url( $options["favicon_backend_url"] )  .'"/>'."\n";
		    }
		    if( $options['apple_icon_backend_url'] != "" ) {
		    	if ( $options['apple_icon_style'] == '0') {
		        	echo '<link rel="apple-touch-icon" href="'.  esc_url( $options["apple_icon_backend_url"] )  .'"/>'."\n";
		    	} else {
		    		echo '<link rel="apple-touch-icon-precomposed" href="'.  esc_url( $options["apple_icon_backend_url"] )  .'"/>'."\n";
		    	}
		    }
		}
	}

	// Initiation call of plugin
	$themeist_custom_favicon = new Themeist_Custom_Favicon();
}
?>
