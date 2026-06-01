<?php
/*
 * Plugin Name:       Set Price Note (Units, Offers, Editions) for WooCommerce
 * Plugin URI:        https://github.com/shshanker/woo-set-price-note
 * Description:       Clarify your pricing. Boost customer confidence and reduce support queries by adding custom units, offer details, or edition labels directly to your product prices.
 * Version:           3.0.0
 * Author:            Shiva Shanker Bhatta
 * Author URI:        https://github.com/shshanker
 * Text Domain:       woo-set-price-note
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') or die('No script kiddies please!');

if (!class_exists('Woo_Set_Price_Note')) :

    class Woo_Set_Price_Note
    {
        const VERSION = '3.0.0';
        protected static $instance = null;

        public function __construct()
        {
            defined('AWSPN_VERSION') or define('AWSPN_VERSION', '3.3.9');
            defined('AWSPN') or define('AWSPN', __FILE__);
            defined('AWSPN_PLUGIN_BASE') or define('AWSPN_PLUGIN_BASE', plugin_basename(AWSPN));
            
            // Use unique constants for the Free plugin context
            defined('AWSPN_DIR_PATH') or define('AWSPN_DIR_PATH', plugin_dir_path(AWSPN));
            defined('AWSPN_DIR_URL') or define('AWSPN_DIR_URL', plugin_dir_url(AWSPN));
            defined('AWSPN_SHOW_PRO_NOTICES') || define('AWSPN_SHOW_PRO_NOTICES', false);

            if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
                add_action('admin_footer', array($this, 'awspn_print_price_note_style'));
                add_action('admin_enqueue_scripts', array($this, 'awspn_enqueue_backend_scripts'));

                include_once 'includes/awspn-admin.php';
                include_once 'includes/awspn-backend.php';
                include_once 'includes/awspn-frontend.php';
                include_once 'includes/awspn-notice.php';
            } else {
                add_action('admin_init', array($this, 'awspn_plugin_deactivate'));
                add_action('admin_notices', array($this, 'awspn_woocommerce_missing_notice'));
            }

            

			if(!class_exists( 'Woo_Set_Price_Note_Pro' )){
				// Load the unified freemius bootloader file safely
				require_once AWSPN_DIR_PATH . '/freemius.php';
			}
        }

        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function awspn_print_price_note_style()
        {
            $screen = get_current_screen();
            if (!$screen || 'product' !== $screen->post_type) {
                return;
            }
            echo "<style>.awspn_show_on_order_and_email_box.hide,.awspn-oe-price.hide,.awspn-oe-sep.hide{display:none;}.awspn-table{width:100%;padding:10px;background:#eee;}</style>";
        }

        public function awspn_enqueue_backend_scripts()
        {
            $screen = get_current_screen();
            if (!$screen || 'product' !== $screen->post_type) {
                return;
            }
            wp_enqueue_script('awspn-backend-scripts', plugins_url('assets/js/awspn-backend-scripts.js', __FILE__), array('jquery'), null, true);
        }

        public function awspn_woocommerce_missing_notice()
        {
            echo '<div class="error"><p>' . sprintf(__('Woocommerce Price Note says "There must be active install of %s to take a flight!"', 'woo-set-price-note'), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">' . __('WooCommerce', 'woo-set-price-note') . '</a>') . '</p></div>';
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        }

        public function awspn_plugin_deactivate()
        {
            deactivate_plugins(plugin_basename(__FILE__));
        }
    }

    add_action('plugins_loaded', array('Woo_Set_Price_Note', 'get_instance'), 0);

endif;