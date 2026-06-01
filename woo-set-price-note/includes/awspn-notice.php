<?php
/**
 * Woo Set Price Note - Admin Optimization Notices
 * Handles intelligent, user-dismissible dashboards notices for Pro Upgrades and WordPress.org Ratings.
 *
 * @package Woo_Set_Price_Note
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class AWSPN_Upgrade_Notice
 * Manages the high-fidelity Pro Upgrade notice with strict dismiss cycles.
 */
class AWSPN_Upgrade_Notice {

    private $notice_id = 'awspn_upgrade_notice';

    public function __construct() {
        add_action( 'admin_init', array( $this, 'initialize_notice_lifecycle' ) );
        add_action( 'admin_notices', array( $this, 'render_upgrade_notice_markup' ) );
        add_action( 'wp_ajax_awspn_dismiss_upgrade_notice', array( $this, 'handle_ajax_dismissal' ) );
    }

    /**
     * Establish the activation baseline time frame.
     */
    public function initialize_notice_lifecycle() {
        if ( ! get_option( 'awspn_upgrade_notice_genesis_time' ) ) {
            update_option( 'awspn_upgrade_notice_genesis_time', time() );
        }
    }

    /**
     * Evaluates whether the active admin context is eligible to see the upsell notice.
     */
    private function should_display_notice() {
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return false;
        }

        // Avoid showing notice immediately; wait 2 days from initial activation.
        $genesis_time = get_option( 'awspn_upgrade_notice_genesis_time', time() );
        if ( time() < ( $genesis_time + ( 2 * DAY_IN_SECONDS ) ) ) {
            return false;
        }

        $user_id = get_current_user_id();

        // Check for permanent dismissal.
        if ( 'yes' === get_user_meta( $user_id, 'awspn_dismiss_upgrade_permanently', true ) ) {
            return false;
        }

        // Check for temporary dismissal (snoozed for 14 days).
        $temporary_dismiss_time = get_user_meta( $user_id, 'awspn_dismiss_upgrade_temporary_time', true );
        if ( $temporary_dismiss_time && time() < ( intval( $temporary_dismiss_time ) + ( 14 * DAY_IN_SECONDS ) ) ) {
            return false;
        }

        // Check if the rating notice is currently active to avoid clashing notification elements.
        if ( did_action( 'awspn_rating_notice_displayed' ) ) {
            return false;
        }

        return true;
    }

    /**
     * Process dismissal triggers securely via AJAX endpoints.
     */
    public function handle_ajax_dismissal() {
        check_ajax_referer( 'awspn_upgrade_notice_nonce', 'security' );

        $user_id = get_current_user_id();
        $type    = isset( $_POST['dismiss_type'] ) ? sanitize_text_field( $_POST['dismiss_type'] ) : 'temporary';

        if ( 'permanent' === $type ) {
            update_user_meta( $user_id, 'awspn_dismiss_upgrade_permanently', 'yes' );
        } else {
            update_user_meta( $user_id, 'awspn_dismiss_upgrade_temporary_time', time() );
        }

        wp_send_json_success();
    }

    /**
     * Visual markup injection for the Pro conversion dashboard frame.
     */
    public function render_upgrade_notice_markup() {
        if ( ! $this->should_display_notice() ) {
            return;
        }

        do_action( 'awspn_upgrade_notice_displayed' );

        // Dynamic routing configurations
        $settings_page_url = admin_url( 'admin.php?page=awspn-settings-pricing' );
        $nonce             = wp_create_nonce( 'awspn_upgrade_notice_nonce' );
        ?>
        <div id="<?php echo esc_attr( $this->notice_id ); ?>" class="notice notice-info awspn-custom-admin-notice" style="padding: 24px; position: relative; border-left: 4px solid #2563eb; background: #ffffff; margin-top: 20px; margin-bottom: 15px; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; gap: 28px; align-items: flex-start; flex-wrap: wrap;">
                
                <div style="flex: 1; min-width: 300px;">
                    <div style="display: inline-block; background: #eff6ff; color: #2563eb; padding: 4px 12px; border-radius: 30px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 14px;">
                        <?php esc_html_e( 'Premium Expansion Pack Available', 'woo-set-price-note' ); ?>
                    </div>

                    <h2 style="margin: 0 0 12px 0; font-size: 22px; line-height: 1.3; font-weight: 700; color: #111827; letter-spacing: -0.01em;">
                        <?php esc_html_e( 'Maximize Store Conversions with Set Price Note Pro', 'woo-set-price-note' ); ?>
                    </h2>

                    <p style="margin: 0 0 20px 0; font-size: 14px; color: #4b5563; max-width: 880px; line-height: 1.7;">
                        <?php esc_html_e( 'Take control over catalog text displays. Upgrade to unlock individual product variation overrides, dynamic category rules, custom font configurations, and native high-performance Gutenberg block compatibility.', 'woo-set-price-note' ); ?>
                    </p>

                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 24px;">
                        <span style="background: #f3f4f6; color: #374151; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500;"><?php esc_html_e( '✓ Variation Overrides', 'woo-set-price-note' ); ?></span>
                        <span style="background: #f3f4f6; color: #374151; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500;"><?php esc_html_e( '✓ Bulk Category Processing', 'woo-set-price-note' ); ?></span>
                        <span style="background: #f3f4f6; color: #374151; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500;"><?php esc_html_e( '✓ Visual Style Engines', 'woo-set-price-note' ); ?></span>
                        <span style="background: #f3f4f6; color: #374151; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500;"><?php esc_html_e( '✓ Cart/Checkout Block Sync', 'woo-set-price-note' ); ?></span>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                        <a class="button button-primary" style="background: #2563eb; border-color: #2563eb; color: #fff; padding: 4px 16px 5px; font-weight: 600; height: auto; line-height: 2; border-radius: 6px; box-shadow: 0 2px 4px rgba(37,99,235,0.15);" href="<?php echo esc_url( $settings_page_url ); ?>">
                            <?php esc_html_e( 'Upgrade to Pro Variant', 'woo-set-price-note' ); ?>
                        </a>

                        <button type="button" class="button button-secondary awspn-upgrade-action" data-dismiss="temporary" style="border-radius: 6px; height: auto; padding: 5px 14px; line-height: 1.8;">
                            <?php esc_html_e( 'Maybe later', 'woo-set-price-note' ); ?>
                        </button>

                        <button type="button" class="button button-secondary awspn-upgrade-action" data-dismiss="permanent" style="color: #6b7280; text-decoration: none; font-size: 13px; margin-left: 8px;">
                            <?php esc_html_e( 'Don\'t show this again', 'woo-set-price-note' ); ?>
                        </button>
                    </div>
                </div>

                <div style="width: 250px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px;">
                    <div style="font-size: 13px; font-weight: 700; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.02em; margin-bottom: 12px;">
                        <?php esc_html_e( 'Why Go Premium?', 'woo-set-price-note' ); ?>
                    </div>
                    <ul style="margin: 0; padding-left: 16px; color: #475569; line-height: 1.7; font-size: 13px; list-style-type: disc;">
                        <li style="margin-bottom: 6px;"><?php esc_html_e( 'Target wholesale vs retail roles', 'woo-set-price-note' ); ?></li>
                        <li style="margin-bottom: 6px;"><?php esc_html_e( 'Set conditional minimum subtotals', 'woo-set-price-note' ); ?></li>
                        <li style="margin-bottom: 6px;"><?php esc_html_e( 'Hide base price structures on emails', 'woo-set-price-note' ); ?></li>
                        <li style="margin-bottom: 0;"><?php esc_html_e( 'Priority expert ticketing access', 'woo-set-price-note' ); ?></li>
                    </ul>
                </div>

            </div>
            
            <button type="button" class="notice-dismiss awspn-upgrade-action" data-dismiss="permanent" style="outline: none; box-shadow: none;"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss notice permanently', 'woo-set-price-note' ); ?></span></button>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#<?php echo esc_js( $this->notice_id ); ?>').on('click', '.awspn-upgrade-action', function(e) {
                    e.preventDefault();
                    var $notice = $('#<?php echo esc_js( $this->notice_id ); ?>');
                    var dismissType = $(this).data('dismiss') || 'permanent';

                    $notice.fadeTo(150, 0, function() {
                        $notice.slideUp(150, function() {
                            $notice.remove();
                        });
                    });

                    wp.ajax.post('awspn_dismiss_upgrade_notice', {
                        security: '<?php echo esc_js( $nonce ); ?>',
                        dismiss_type: dismissType
                    });
                });
            });
        </script>
        <?php
    }
}


/**
 * Class AWSPN_Rating_Notice
 * Manages the WordPress.org plugin review request cycle.
 */
class AWSPN_Rating_Notice {

    private $notice_id = 'awspn_rating_notice';

    public function __construct() {
        add_action( 'admin_init', array( $this, 'initialize_notice_lifecycle' ) );
        add_action( 'admin_notices', array( $this, 'render_rating_notice_markup' ) );
        add_action( 'wp_ajax_awspn_dismiss_rating_notice', array( $this, 'handle_ajax_dismissal' ) );
    }

    /**
     * Establish tracking benchmarks on initialization.
     */
    public function initialize_notice_lifecycle() {
        if ( ! get_option( 'awspn_rating_notice_genesis_time' ) ) {
            update_option( 'awspn_rating_notice_genesis_time', time() );
        }
    }

    /**
     * Determines whether the current operator is due for a review prompt.
     */
    private function should_display_notice() {
        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return false;
        }

        // Give the merchant time to test the plugin; run only 7 days after tracking starts.
        $genesis_time = get_option( 'awspn_rating_notice_genesis_time', time() );
        if ( time() < ( $genesis_time + ( 7 * DAY_IN_SECONDS ) ) ) {
            return false;
        }

        $user_id = get_current_user_id();

        // Check for permanent user dismissal.
        if ( 'yes' === get_user_meta( $user_id, 'awspn_dismiss_rating_permanently', true ) ) {
            return false;
        }

        // Check for temporary user dismissal (snoozed for 7 days).
        $temporary_dismiss_time = get_user_meta( $user_id, 'awspn_dismiss_rating_temporary_time', true );
        if ( $temporary_dismiss_time && time() < ( intval( $temporary_dismiss_time ) + ( 7 * DAY_IN_SECONDS ) ) ) {
            return false;
        }

        return true;
    }

    /**
     * Save dismissal state via secure asynchronously processed tracking payloads.
     */
    public function handle_ajax_dismissal() {
        check_ajax_referer( 'awspn_rating_notice_nonce', 'security' );

        $user_id = get_current_user_id();
        $type    = isset( $_POST['dismiss_type'] ) ? sanitize_text_field( $_POST['dismiss_type'] ) : 'temporary';

        if ( 'permanent' === $type ) {
            update_user_meta( $user_id, 'awspn_dismiss_rating_permanently', 'yes' );
        } else {
            update_user_meta( $user_id, 'awspn_dismiss_rating_temporary_time', time() );
        }

        wp_send_json_success();
    }

    /**
     * Structural rendering function generating the feedback presentation block.
     */
    public function render_rating_notice_markup() {
        if ( ! $this->should_display_notice() ) {
            return;
        }

        // Alert downline modules that the layout slot is occupied.
        do_action( 'awspn_rating_notice_displayed' );

        $repo_review_url = 'https://wordpress.org/support/plugin/woo-set-price-note/reviews/?filter=5';
        $nonce           = wp_create_nonce( 'awspn_rating_notice_nonce' );
        ?>
        <div id="<?php echo esc_attr( $this->notice_id ); ?>" class="notice notice-info awspn-custom-admin-notice" style="padding: 24px; position: relative; border-left: 4px solid #eab308; background: #ffffff; margin-top: 20px; margin-bottom: 15px; border-radius: 4px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; gap: 28px; align-items: flex-start; flex-wrap: wrap;">
                
                <div style="flex: 1; min-width: 300px;">
                    <div style="display: inline-block; background: #fef9c3; color: #854d0e; padding: 4px 12px; border-radius: 30px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 14px;">
                        <?php esc_html_e( 'Community Feedback', 'woo-set-price-note' ); ?>
                    </div>

                    <h2 style="margin: 0 0 12px 0; font-size: 22px; line-height: 1.3; font-weight: 700; color: #111827; letter-spacing: -0.01em;">
                        <?php esc_html_e( 'Is Set Price Note for WooCommerce Helping Your Store?', 'woo-set-price-note' ); ?>
                    </h2>

                    <p style="margin: 0 0 20px 0; font-size: 14px; color: #4b5563; max-width: 880px; line-height: 1.7;">
                        <?php esc_html_e( 'We hope you are enjoying the plugin! If it makes your operations even a little bit easier, please support us by dropping a 5-star rating on WordPress.org. Honest reviews keep our independent open-source team motivated to ship free enhancements and security maintenance patches!', 'woo-set-price-note' ); ?>
                    </p>

                    <div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                        <a class="button button-primary awspn-rating-action" data-dismiss="permanent" style="background: #ffcc00; border-color: #ca8a04; color: #713f12; padding: 4px 16px 5px; font-weight: 600; height: auto; line-height: 2; border-radius: 6px; box-shadow: 0 2px 4px rgba(234,179,8,0.15); text-shadow: none;" href="<?php echo esc_url( $repo_review_url ); ?>" target="_blank" rel="noopener noreferrer">
                            <?php esc_html_e( 'Leave a Review ★★★★★', 'woo-set-price-note' ); ?>
                        </a>

                        <button type="button" class="button button-secondary awspn-rating-action" data-dismiss="temporary" style="border-radius: 6px; height: auto; padding: 5px 14px; line-height: 1.8;">
                            <?php esc_html_e( 'Maybe later', 'woo-set-price-note' ); ?>
                        </button>

                        <button type="button" class="button button-secondary awspn-rating-action" data-dismiss="permanent" style="color: #6b7280; text-decoration: none; font-size: 13px; margin-left: 8px;">
                            <?php esc_html_e( 'I\'ve already rated it / Dismiss permanently', 'woo-set-price-note' ); ?>
                        </button>
                    </div>
                </div>

                <div style="width: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #fefce8; border: 1px dashed #fef08a; border-radius: 10px; padding: 22px; text-align: center; align-self: center;">
                    <div style="font-size: 32px; line-height: 1; color: #eab308; margin-bottom: 8px; letter-spacing: 2px;">★★★★★</div>
                    <div style="font-size: 12px; font-weight: 600; color: #713f12; line-height: 1.4;">
                        <?php esc_html_e( 'Takes under 60 seconds to complete', 'woo-set-price-note' ); ?>
                    </div>
                </div>

            </div>
            
            <button type="button" class="notice-dismiss awspn-rating-action" data-dismiss="permanent" style="outline: none; box-shadow: none;"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss notice permanently', 'woo-set-price-note' ); ?></span></button>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#<?php echo esc_js( $this->notice_id ); ?>').on('click', '.awspn-rating-action', function(e) {
                    var $notice = $('#<?php echo esc_js( $this->notice_id ); ?>');
                    var dismissType = $(this).data('dismiss') || 'permanent';

                    // Only stop standard link propagation if it's an inline button action, not the external click link
                    if ( ! $(this).is('a') || dismissType === 'permanent' && ! $(this).attr('target') ) {
                        e.preventDefault();
                    }

                    $notice.fadeTo(150, 0, function() {
                        $notice.slideUp(150, function() {
                            $notice.remove();
                        });
                    });

                    wp.ajax.post('awspn_dismiss_rating_notice', {
                        security: '<?php echo esc_js( $nonce ); ?>',
                        dismiss_type: dismissType
                    });
                });
            });
        </script>
        <?php
    }
}

/**
 * Orchestrator initialization loop firing execution modules.
 */
function awspn_run_admin_notices_subsystem() {
    if ( ! is_admin() ) {
        return;
    }

    new AWSPN_Upgrade_Notice();
    new AWSPN_Rating_Notice();
}
add_action( 'admin_init', 'awspn_run_admin_notices_subsystem' );