<?php
declare( strict_types=1 );

namespace WatchTheDot\Plugins;

/**
 * Plugin Name:       BCC All Emails
 * Plugin URI:        https://support.watchthedot.com/our-plugins/bcc-all-emails
 * Description:       A simple plugin that allows you to bcc email address to all emails sent from the site
 * Version:           1.1.1
 *
 * Requires PHP:      7.4
 *
 * Requires at least: 5.2
 * Tested up to:      6.4
 *
 * Author:            Watch The Dot
 * Author URI:        https://www.watchthedot.com
 *
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text-Domain:       bcc-all-emails
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

use WatchTheDot\Plugins\Dependencies\Watchthedot\Library\Settings\SettingsPage;
use WatchTheDot\Plugins\Dependencies\Watchthedot\Library\Settings as LibSettings;

$class_map = array_merge(
	// Third-party classes.
	include __DIR__ . '/third-party/vendor/composer/autoload_classmap.php'
);

spl_autoload_register(
	static function ( $class ) use ( $class_map ) {
		if ( isset( $class_map[ $class ] ) ) {
			require_once $class_map[ $class ];

			return true;
		}
	},
	true,
	true
);

class BCCEmails {
	/**
	 * The name of the plugin displayed in the admin panel
	 */
	const NAME = 'BCC All Emails';

	/**
	 * The version number used for certain errors when raised
	 */
	const VERSION = '1.1.1';

	/**
	 * The namespace for the plugins settings
	 */
	const TOKEN = 'bccemails';

	/**
	 * The ONLY instance of the plugin.
	 * Accessable via ::instance().
	 * Ensures that the hooks are only added once
	 */
	private static ?self $instance;

	/**
	 * The FULL filepath to this file
	 */
	private string $file;

	/**
	 * The FULL directory path to this folder
	 */
	private string $dir;

	/**
	 * The directory where the plugin's assets are stored
	 */
	private string $assets_dir;

	/**
	 * The URL to the plugin's assets.
	 * Used when enqueuing styles and scripts
	 */
	private string $assets_url;

	private SettingsPage $settings_page;

	private function __construct() {
		$this->file       = __FILE__;
		$this->dir        = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->settings_page = new SettingsPage( $this->file, self::NAME, self::TOKEN );

		$this->settings_page->add_tab(
			( new LibSettings\FormTab( 'General' ) )
			->add_field(
				new class( 'Email Addresses' ) extends LibSettings\Field\Textarea {
					public function sanitize( $value ) {
						return implode(
							"\n",
							array_filter(
								array_map( 'trim', explode( "\n", $value ?: '' ) ),
								'is_email'
							)
						);
					}
				}
			)
		);

		add_action( 'init', [ $this, 'action_init' ] );
		add_action( 'admin_init', [ $this, 'action_admin_init' ] );
		add_filter( 'plugin_row_meta', [ $this, 'filter_plugin_row_meta' ], 10, 2 );
	}

	public function action_init() {
		add_filter( 'wp_mail', [ $this, 'add_bcc_addresses' ], PHP_INT_MAX, 1 );
	}

	public function add_bcc_addresses( array $args ): array {
		$headers = $args['headers'] ?? [];

		$email_addresses = explode( "\n", $this->settings_page->get_option( 'emailaddresses' ) ?? [] );

		foreach ( $email_addresses as $email_address ) {
			if ( is_array( $headers ) ) {
				$headers[] = 'Bcc:' . $email_address;
			} else {
				if ( ! empty( $headers ) && ! str_ends_with( $headers, "\n" ) ) {
					$headers .= "\n";
				}

				$headers .= "Bcc:{$email_address}\n";
			}
		}

		$args['headers'] = $headers;

		return $args;
	}

	public function action_admin_init() {

	}

	public function filter_plugin_row_meta( array $plugin_meta, $plugin_file ): array {
		if ( $plugin_file !== plugin_basename( __FILE__ ) ) {
			return $plugin_meta;
		}

		return array_merge(
			array_slice( $plugin_meta, 0, 2 ),
			[
				sprintf(
					"<a href='https://support.watchthedot.com/our-plugins/bcc-all-emails/#documentation' target='_blank'>%s</a>",
					__( 'Documentation', 'bcc-all-emails' )
				),
			],
			array_slice( $plugin_meta, 2 )
		);
	}

	/* === GETTERS AND INSTANCES === */

	public function get_filename(): string {
		return $this->file;
	}

	public function get_directory(): string {
		return $this->dir;
	}

	public function get_assets_dir(): string {
		return $this->assets_dir;
	}

	public function get_assets_url(): string {
		return $this->assets_url;
	}

	public static function instance(): self {
		return self::$instance ??= new self();
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html( 'Cloning of ' . self::class . ' is forbidden' ), esc_attr( self::VERSION ) );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html( 'Unserializing instances of ' . self::class . ' is forbidden' ), esc_attr( self::VERSION ) );
	}
}

BCCEmails::instance();
