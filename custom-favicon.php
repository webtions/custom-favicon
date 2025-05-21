<?php
/**
 * Plugin Name: Custom Favicon
 * Plugin URI: https://themeist.com/plugins/wordpress/custom-favicon/
 * Description: Adds support for custom favicons across WordPress frontend, admin, and login pages, including dark mode and SVG support.
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
			add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'admin_init', array( $this, 'migrate_settings' ) );
			add_action( 'wp_head', array( $this, 'output_frontend_favicons' ) );
			add_action( 'admin_head', array( $this, 'output_admin_favicons' ) );
			add_action( 'login_head', array( $this, 'output_admin_favicons' ) );
		}

		public function load_localisation() {
			load_plugin_textdomain( 'custom-favicon', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		public function add_settings_menu() {
			add_options_page(
				__( 'Custom Favicon', 'custom-favicon' ),
				__( 'Custom Favicon', 'custom-favicon' ),
				'manage_options',
				'custom-favicon',
				array( $this, 'render_settings_page' )
			);
		}

		public function enqueue_assets( $hook_suffix ) {
			if ( $hook_suffix !== 'settings_page_custom-favicon' ) {
				return;
			}
			wp_enqueue_media();
			wp_enqueue_script(
				'custom_favicon_admin',
				plugins_url( '/js/custom-favicon-admin.js', __FILE__ ),
				array( 'jquery' ),
				filemtime( plugin_dir_path( __FILE__ ) . 'js/custom-favicon-admin.js' ),
				true
			);
		}

		public function migrate_settings() {
			$old = get_option( $this->legacy_option_key );
			$new = get_option( $this->option_key );

			if ( $old && ! $new ) {
				update_option( $this->option_key, $old );
				delete_option( $this->legacy_option_key );
				$new = $old;
			}

			if ( is_array( $new ) ) {
				$changed = false;
				if ( isset( $new['favicon_frontend_url'] ) && empty( $new['favicon_default_url'] ) ) {
					$new['favicon_default_url'] = $new['favicon_frontend_url'];
					unset( $new['favicon_frontend_url'] );
					$changed = true;
				}
				if ( isset( $new['favicon_backend_url'] ) && empty( $new['favicon_admin_url'] ) ) {
					$new['favicon_admin_url'] = $new['favicon_backend_url'];
					unset( $new['favicon_backend_url'] );
					$changed = true;
				}
				if ( isset( $new['apple_icon_style'] ) ) {
					unset( $new['apple_icon_style'] );
					$changed = true;
				}
				if ( $changed ) {
					update_option( $this->option_key, $new );
				}
			}
		}

		public function register_settings() {
			register_setting( $this->option_key, $this->option_key, array( $this, 'sanitize_settings' ) );

			add_settings_section( 'favicon_section', __( 'Favicon', 'custom-favicon' ), array( $this, 'section_description_favicon' ), $this->option_key );
			add_settings_field( 'favicon_default_url', __( 'Favicon (Default)', 'custom-favicon' ), array( $this, 'field_image_url' ), $this->option_key, 'favicon_section', [ 'key' => 'favicon_default_url' ] );
			add_settings_field( 'favicon_dark_url', __( 'Favicon (Dark Mode Override)', 'custom-favicon' ), array( $this, 'field_image_url' ), $this->option_key, 'favicon_section', [ 'key' => 'favicon_dark_url' ] );
			add_settings_field( 'favicon_admin_url', __( 'Favicon (Admin Area)', 'custom-favicon' ), array( $this, 'field_image_url' ), $this->option_key, 'favicon_section', [ 'key' => 'favicon_admin_url' ] );

			add_settings_section( 'apple_section', __( 'Apple Touch Icons', 'custom-favicon' ), array( $this, 'section_description_apple' ), $this->option_key );
			add_settings_field( 'apple_icon_frontend_url', __( 'Apple Icon (Website)', 'custom-favicon' ), array( $this, 'field_image_url' ), $this->option_key, 'apple_section', [ 'key' => 'apple_icon_frontend_url' ] );
			add_settings_field( 'apple_icon_backend_url', __( 'Apple Icon (Admin Area)', 'custom-favicon' ), array( $this, 'field_image_url' ), $this->option_key, 'apple_section', [ 'key' => 'apple_icon_backend_url' ] );
		}

		public function render_settings_page() {
			?>
			<div class="wrap">
				<h1><?php esc_html_e( 'Custom Favicon', 'custom-favicon' ); ?></h1>
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

		public function section_description_favicon() {
			echo '<p>' . esc_html__( 'Used in browser tabs and bookmarks. You can also override for dark mode and admin area.', 'custom-favicon' ) . '</p>';
			echo '<p><strong>' . esc_html__( 'Recommended size:', 'custom-favicon' ) . '</strong> 512×512px. ';
			echo esc_html__( 'Supports .ico, .png, .svg.', 'custom-favicon' ) . '</p>';
		}

		public function section_description_apple() {
			echo '<p>' . esc_html__( 'Displayed when users save your site to their mobile home screen.', 'custom-favicon' ) . '</p>';
			echo '<p><strong>' . esc_html__( 'Recommended size:', 'custom-favicon' ) . '</strong> 180×180px PNG. ';
			echo esc_html__( 'SVG is not supported by Apple icons.', 'custom-favicon' ) . '</p>';
		}

		public function field_image_url( $args ) {
			$key     = $args['key'];
			$options = get_option( $this->option_key );
			$value   = $options[ $key ] ?? '';
			?>
			<input type="text" id="<?php echo esc_attr( $this->option_key . "[$key]" ); ?>" class="regular-text text-upload" name="<?php echo esc_attr( $this->option_key . "[$key]" ); ?>" value="<?php echo esc_url( $value ); ?>" />
			<input type="button" class="button button-upload" value="<?php esc_attr_e( 'Upload', 'custom-favicon' ); ?>" />
			<?php
		}

		public function sanitize_settings( $input ) {
			foreach ( $input as $key => $val ) {
				$input[ $key ] = esc_url_raw( $val );
			}
			return $input;
		}

		private function output_favicon_tag( $url, $media = '' ) {
			if ( ! $url ) {
				return;
			}
			$type_attr = str_ends_with( $url, '.svg' ) ? ' type="image/svg+xml"' : '';
			$media_attr = $media ? ' media="' . esc_attr( $media ) . '"' : '';
			echo '<link rel="icon" href="' . esc_url( $url ) . '"' . $media_attr . $type_attr . ' />' . "\n";
		}

		public function output_frontend_favicons() {
			$options = get_option( $this->option_key );
			$default = $options['favicon_default_url'] ?? '';
			$dark    = $options['favicon_dark_url'] ?? '';

			if ( $dark ) {
				$this->output_favicon_tag( $dark, '(prefers-color-scheme: dark)' );
			}
			if ( $default ) {
				$this->output_favicon_tag( $default, '(prefers-color-scheme: light)' );
			}
			if ( ! empty( $options['apple_icon_frontend_url'] ) ) {
				echo '<link rel="apple-touch-icon" href="' . esc_url( $options['apple_icon_frontend_url'] ) . '" />' . "\n";
			}
			if ( $default && str_ends_with( $default, '.png' ) ) {
				echo '<meta name="msapplication-TileImage" content="' . esc_url( $default ) . '" />' . "\n";
			}
		}

		public function output_admin_favicons() {
			$options = get_option( $this->option_key );

			if ( ! empty( $options['favicon_admin_url'] ) ) {
				$type = str_ends_with( $options['favicon_admin_url'], '.svg' ) ? ' type="image/svg+xml"' : '';
				echo '<link rel="shortcut icon" href="' . esc_url( $options['favicon_admin_url'] ) . '"' . $type . ' />' . "\n";
			}

			if ( ! empty( $options['apple_icon_backend_url'] ) ) {
				echo '<link rel="apple-touch-icon" href="' . esc_url( $options['apple_icon_backend_url'] ) . '" />' . "\n";
			}
		}
	}

	new Themeist_Custom_Favicon();
}
