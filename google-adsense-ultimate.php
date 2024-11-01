<?php

/**
 * Plugin Name:       Ultimate Google Adsense for WordPress
 * Plugin URI:        https://ThemeBing.com/ultimate-google-adsense
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.3
 * Author:            ThemeBing
 * Author URI:        https://ThemeBing.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ultimate-google-adsense
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class GoogleAdsenseUltimate {

	// Initialized
	function __construct() {
		if (is_admin()) {
            add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2);
        }
		add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_init', array($this, 'ultimate_google_adsense_init'));
		add_action('wp_head', array($this, 'add_adsense_code_to_header'));
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__),  array( $this, 'ultimate_google_adsense_pro_link' ));

	}

	// Text Domain
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'ultimate-google-adsense', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}

	// Plugin Settings URL
	public function plugin_action_links($links, $file) {
        if ($file == plugin_basename(dirname(__FILE__) . '/ultimate-google-adsense.php')) {
            $links[] = '<a href="options-general.php?page=ultimate-google-adsense">'.__('Settings', 'ultimate-google-adsense').'</a>';
        }
        return $links;
    }

	// Create a Menu
	public function admin_menu() {
        if (is_admin()) {
            add_menu_page( __( 'Ultimate Google Adsense', 'ultimate-google-adsense' ),  __( 'Google Adsense', 'ultimate-google-adsense' ), 'manage_options', 'ultimate-google-adsense', array( $this, 'settings_page_content' ), plugin_dir_url( __FILE__ ) . 'assets/images/adsense-logo.png', 10 );
        }
    }

    /**
     * Settings page display callback.
     */
    function settings_page_content() { ?>
	    <div class="wrap">               
        	<h1><?php echo esc_html__( 'Ultimate Google Adsense', 'ultimate-google-adsense' ) ?></h1>

			<div class="update-nag">
				<?php echo esc_html__('Please visit the', 'ultimate-google-adsense' ); ?>
				<a target="_blank" href="https://themebing.com/how-to-add-google-adsense-to-wordpress-site/"><?php echo esc_html__('Ultimate Google Adsense', 'ultimate-google-adsense' ); ?></a>
				<?php echo esc_html__('documentation page for full setup instructions.', 'ultimate-google-adsense' ); ?>
			</div>

	        <form action='options.php' method='post'>
		        <?php
		        settings_fields('ultimate_google_adsense_group');
		        do_settings_sections('ultimate_google_adsense_settings');
		        submit_button();
		        ?>
	        </form>
        </div>
    <?php }



	// Plugin input fields init
	public function ultimate_google_adsense_init() {

		register_setting( 'ultimate_google_adsense_group', 'ultimate_google_adsense_option', array(
	        'type' => 'string',
	        'sanitize_callback' => 'sanitize_text_field',
	        'default' => NULL,
	    ) );

		add_settings_section( 'ultimate_google_adsense_section', __('General Settings', 'ultimate-google-adsense'), array($this, 'ultimate_google_adsense_section_callback'), 'ultimate_google_adsense_settings' );

		add_settings_field( 'ultimate_google_adsense_field', __('Publisher ID', 'ultimate-google-adsense'),  array($this, 'ultimate_google_adsense_setting_callback'), 'ultimate_google_adsense_settings', 'ultimate_google_adsense_section' );
	}

	// ------------------------------------------------------------------
	// callback function for ultimate_google_adsense_group
	// ------------------------------------------------------------------
	
	function ultimate_google_adsense_section_callback() {
	 	echo '<p>Please enter your Publisher ID </p>';
	}

	// ------------------------------------------------------------------
	// Callback function for ultimate_google_adsense_field
	// ------------------------------------------------------------------
	function ultimate_google_adsense_setting_callback() { ?>

        <input type='text' class="regular-text" name="ultimate_google_adsense_option" value="<?php echo get_option('ultimate_google_adsense_option') ?>">

        <p class="description">
        	<?php printf(__('Enter your Google AdSense Publisher ID (e.g %s).', 'ultimate-google-adsense'), 'pub-1234567890111213');?>
        	<a href="https://themebing.com/how-to-add-google-adsense-to-wordpress-site/"><?php echo esc_html__( 'See how to get your publisher ID', 'ultimate-google-adsense' ) ?></a>
        </p>

    <?php
	}

	public function add_adsense_code_to_header(){

		if(!empty(get_option('ultimate_google_adsense_option'))){?>

			<script data-ad-client="ca-<?php echo get_option('ultimate_google_adsense_option') ?>" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

		<?php
		}

	}

	// Add pro link to plugin actions
	public function ultimate_google_adsense_pro_link($links){
	    $links['go_pro'] = '<a style="color:green;" title="Upgrade to Pro" href="https://themebing.com/shop/plugins/ultimate-google-adsense-pro/" target="_blank"><b>Go Pro</b></a>';
	    return $links;
	}

}

new GoogleAdsenseUltimate;