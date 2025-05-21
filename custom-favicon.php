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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Themeist_Custom_Favicon' ) ) {

	class Themeist_Custom_Favicon {

		private $option_key = 'custom_favicon_settings';
		private $legacy_option_key = 'dot_cfi_settings';

		function __construct() {
			add_action( 'init', array( $this, 'load_localisation' ), 0 );
			add_action( 'admin_menu', array( $this, 'custom_favicon_add_settings_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'custom_favicon_enqueue_assets' ) );
			add_action( 'admin_init', array( $this, 'custom_favicon_register_settings' ) );
			add_action( 'admin_init', array( $this, 'custom_favicon_migrate_settings' ) );
			add_action( 'wp_head', array( $this, 'custom_favicon_output_frontend_tags' ) );
			add_action( 'admin_head', array( $this, 'custom_favicon_output_admin_tags' ) );
			add_action( 'login_head', array( $this, 'custom_favicon_output_admin_tags' ) );
		}

		public function load_localisation() {
			load_plugin_textdomain( 'custom-favicon', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		public function custom_favicon_add_settings_menu() {
			add_options_page(
				__( 'Custom Favicon', 'custom-favicon' ),
				__( 'Custom Favicon', 'custom-favicon' ),
				'manage_options',
				'custom_favicon',
				array( $this, 'custom_favicon_render_settings_page' )
			);
		}

		public function custom_favicon_enqueue_assets( $hook_suffix ) {
			if ( $hook_suffix !== 'settings_page_custom_favicon' ) {
				return;
			}
			wp_enqueue_media();
			wp_register_script(
				'custom_favicon_admin',
				plugins_url( '/js/custom-favicon-admin.js', __FILE__ ),
				array( 'jquery' ),
				filemtime( plugin_dir_path( __FILE__ ) . 'js/custom-favicon-admin.js' ),
				true
			);
			wp_enqueue_script( 'custom_favicon_admin' );
		}

		public function custom_favicon_migrate_settings() {
			$old = get_option( $this->legacy_option_key );
			$new = get_option( $this->option_key );
			if ( $old && ! $new ) {
				update_option( $this->option_key, $old );
				delete_option( $this->legacy_option_key );
			}
		}

		public function custom_favicon_register_settings() {
			register_setting( $this->option_key, $this->option_key, array( $this, 'settings_validate' ) );

			add_settings_section( 'favicon', __( 'Custom Favicon & Apple touch icon', 'custom-favicon' ), '__return_false', $this->option_key );

			add_settings_field( 'favicon_frontend_url', __( 'Favicon for Website', 'custom-favicon' ), array( $this, 'field_image_url' ), $this->option_key, 'favicon', [ 'key' => 'favicon_frontend_url' ] );
			add_settings_field( 'favicon_backend_url', __( 'Favicon for Admin', 'custom-favicon' ), array( $this, 'field_image_url' ), $this->option_key, 'favicon', [ 'key' => 'favicon_backend_url' ] );
			add_settings_field( 'apple_icon_frontend_url', __( 'Apple Touch Icon for Website', 'custom-favicon' ), array( $this, 'field_image_url' ), $this->option_key, 'favicon', [ 'key' => 'apple_icon_frontend_url' ] );
			add_settings_field( 'apple_icon_backend_url', __( 'Apple Touch Icon for Admin', 'custom-favicon' ), array( $this, 'field_image_url' ), $this->option_key, 'favicon', [ 'key' => 'apple_icon_backend_url' ] );
			add_settings_field( 'apple_icon_style', __( 'Basic Apple Touch Icon', 'custom-favicon' ), array( $this, 'field_icon_style' ), $this->option_key, 'favicon' );
		}

		public function custom_favicon_render_settings_page() {
			?>
			<div class="wrap">
				<h2><?php echo esc_html( __( 'Custom Favicon Settings', 'custom-favicon' ) ); ?></h2>
				<form method="post" action="options.php">
					<?php
					settings_fields( $this->option_key );
					do_settings_sections( $this->option_key );
					submit_button();
					?>
				</form>
			</div>
			<?php
		}

		public function field_image_url( $args ) {
			$key = $args['key'];
			$options = get_option( $this->option_key );
			$value = $options[ $key ] ?? '';
			?>
			<span class="upload">
				<input type="text" id="<?php echo esc_attr( $this->option_key . "[$key]" ); ?>" class="regular-text text-upload" name="<?php echo esc_attr( $this->option_key . "[$key]" ); ?>" value="<?php echo esc_url( $value ); ?>" />
				<input type="button" class="button button-upload" value="<?php esc_attr_e( 'Upload an image', 'custom-favicon' ); ?>" /><br>
				<?php if ( $value ) : ?>
					<img class="preview-upload" style="max-width: 300px; display: block;" src="<?php echo esc_url( $value ); ?>" />
				<?php endif; ?>
			</span>
			<?php
		}

		public function field_icon_style() {
			$options = get_option( $this->option_key );
			$value = $options['apple_icon_style'] ?? '0';
			echo '<input type="hidden" name="' . esc_attr( $this->option_key . '[apple_icon_style]' ) . '" value="0" />';
			echo '<label><input type="checkbox" name="' . esc_attr( $this->option_key . '[apple_icon_style]' ) . '" value="1"' . checked( $value, '1', false ) . ' /> ';
			echo esc_html__( 'Disable Curved Border & reflective shine for Apple touch icon', 'custom-favicon' ) . '</label>';
		}

		public function settings_validate( $input ) {
			foreach ( $input as $key => $val ) {
				$input[ $key ] = esc_url_raw( $val );
			}
			return $input;
		}

		public function custom_favicon_output_frontend_tags() {
			$options = get_option( $this->option_key );
			if ( ! empty( $options['favicon_frontend_url'] ) ) {
				echo '<link rel="shortcut icon" href="' . esc_url( $options['favicon_frontend_url'] ) . '" />' . "\n";
			}
			if ( ! empty( $options['apple_icon_frontend_url'] ) ) {
				$rel = ( $options['apple_icon_style'] === '0' ) ? 'apple-touch-icon' : 'apple-touch-icon-precomposed';
				echo '<link rel="' . esc_attr( $rel ) . '" href="' . esc_url( $options['apple_icon_frontend_url'] ) . '" />' . "\n";
			}
		}

		public function custom_favicon_output_admin_tags() {
			$options = get_option( $this->option_key );
			if ( ! empty( $options['favicon_backend_url'] ) ) {
				echo '<link rel="shortcut icon" href="' . esc_url( $options['favicon_backend_url'] ) . '" />' . "\n";
			}
			if ( ! empty( $options['apple_icon_backend_url'] ) ) {
				$rel = ( $options['apple_icon_style'] === '0' ) ? 'apple-touch-icon' : 'apple-touch-icon-precomposed';
				echo '<link rel="' . esc_attr( $rel ) . '" href="' . esc_url( $options['apple_icon_backend_url'] ) . '" />' . "\n";
			}
		}
	}

	new Themeist_Custom_Favicon();
}
