<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Woo Set Price Note - Admin Class (Lite)
 * Engineered with interactive Pro upsell interfaces and cross-plugin ecosystems.
 */
class Woo_Set_Price_Note_Admin {

    private $settings_slug = 'awspn-settings';

    public function __construct() {
        // Core lifecycle instantiation
    }

    /**
     * Initialize core actions if Pro variant is not executing
     */
    public function init() {
        add_action( 'admin_menu', array( $this, 'awspn_register_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'awspn_register_settings' ) );
    }

    /**
     * Internal locator for standardizing route hooks across plugins
     */
    protected function get_settings_slug() {
        return $this->settings_slug;
    }

    /**
     * Production defaults matrix
     */
    public static function get_default_settings() {
        return [
            'separator' => '/',
        ];
    }

    /**
     * Add settings registry configuration hook
     */
    public function awspn_register_settings() {
        register_setting( 'awspn_options_group', 'awspn_global_settings', [
            'type'              => 'array',
            'sanitize_callback' => array( $this, 'sanitize_lite_settings' ),
            'default'           => self::get_default_settings()
        ]);
    }

    /**
     * Explicit sanitization layer isolating core data from pro mocks
     */
    public function sanitize_lite_settings( $input ) {
        $sanitized = self::get_default_settings();
        if ( isset( $input['separator'] ) ) {
            $sanitized['separator'] = sanitize_text_field( $input['separator'] );
        }
        return $sanitized;
    }

    /**
     * Inject integrated settings dashboard into WooCommerce schema
     */
    public function awspn_register_admin_menu() {
        add_menu_page(
            __( 'Price Note Settings', 'woo-set-price-note' ),
            __( 'Set Price Note', 'woo-set-price-note' ),
            'manage_woocommerce',
            $this->get_settings_slug(),
            array( $this, 'awspn_render_settings_page' ),
            'dashicons-tag',
            56
        );

        add_submenu_page(
            $this->get_settings_slug(), // Parent slug.
            __( 'Global Settings', 'woo-set-price-note' ), // Page title.
            __( 'Global Settings', 'woo-set-price-note' ), // Menu title.
            'manage_woocommerce', // Capability.
            $this->get_settings_slug(), // Menu slug.
            array( $this, 'awspn_render_settings_page' ) // Callback function.

        );
    }

    /**
     * Render the unified high-fidelity settings environment
     */
    public function awspn_render_settings_page() {
        $options = get_option( 'awspn_global_settings', self::get_default_settings() );
        $current_separator = $options['separator'] ?? '/';
        ?>
        <style>
            :root {
                --ny-bg: #fcfcfd; --ny-card: #ffffff; --ny-dark: #111111; --ny-muted: #666666;
                --ny-border: #e4e4e7; --ny-accent: #2563eb; --ny-accent-hover: #1d4ed8; --ny-radius: 6px;
                --ny-lock: #a1a1aa; --ny-pro-bg: #eff6ff; --ny-pro-color: #1d4ed8;
                --ny-eco-bg: #f0fdf4; --ny-eco-color: #16a34a; --ny-eco-hover: #15803d;
                --font-studio: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            }
            .awspn-ny-dashboard { font-family: var(--font-studio); background-color: var(--ny-bg); color: var(--ny-dark); max-width: 1250px; margin: 24px 20px 0 0; padding: 40px; border: 1px solid var(--ny-border); border-radius: var(--ny-radius); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02); position: relative; box-sizing: border-box; }
            .awspn-ny-dashboard * { box-sizing: border-box; }
            .awspn-ny-header { border-bottom: 1px solid var(--ny-dark); padding-bottom: 24px; margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; }
            .awspn-ny-header h1 { font-size: 24px; font-weight: 700; letter-spacing: -0.02em; margin: 0; color: var(--ny-dark); line-height: 1; display: flex; align-items: center; gap: 10px; }
            .awspn-ny-header p { margin: 6px 0 0 0; font-size: 13px; color: var(--ny-muted); }
            
            /* Action Button */
            .awspn-go-pro-btn { background: var(--ny-pro-color); color: #fff !important; text-decoration: none !important; padding: 10px 18px; font-size: 13px; font-weight: 600; border-radius: var(--ny-radius); transition: background 0.15s ease; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.15); }
            .awspn-go-pro-btn:hover { background: var(--ny-accent-hover); }

            /* Two-Column Grid Layout */
            .awspn-layout-body { display: grid; grid-template-columns: 1fr 340px; gap: 40px; align-items: start; }
            @media (max-width: 1024px) {
                .awspn-layout-body { grid-template-columns: 1fr; }
            }

            /* Navigation Framework */
            .awspn-ny-tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 32px; border-bottom: 1px solid var(--ny-border); padding-bottom: 0; }
            .awspn-tab-trigger { font-family: var(--font-studio); font-size: 14px; font-weight: 500; color: var(--ny-muted); padding: 10px 16px; border: none; background: transparent; cursor: pointer; position: relative; transition: color 0.2s ease; outline: none !important; display: flex; align-items: center; gap: 6px; }
            .awspn-tab-trigger:hover { color: var(--ny-dark); }
            .awspn-tab-trigger.is-active { color: var(--ny-dark); font-weight: 600; }
            .awspn-tab-trigger.is-active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: var(--ny-dark); }
            
            /* Layout Grid Tables */
            .awspn-tab-content { display: none; animation: awspnFade 0.25s cubic-bezier(0.16, 1, 0.3, 1); }
            .awspn-tab-content.is-active { display: block; }
            @keyframes awspnFade { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
            
            .awspn-form-table { width: 100%; margin-top: 0; border-collapse: collapse; }
            .awspn-form-table tr { border-bottom: 1px solid #f4f4f5; }
            .awspn-form-table tr:last-child { border-bottom: none; }
            .awspn-form-table th { font-weight: 600; color: var(--ny-dark); font-size: 14px; padding: 24px 16px 24px 0; width: 220px; text-align: left; vertical-align: top; }
            .awspn-form-table td { padding: 24px 0; vertical-align: top; }
            
            /* Input Control Definitions */
            .awspn-modern-input { background: #ffffff; border: 1px solid var(--ny-border) !important; border-radius: var(--ny-radius) !important; padding: 10px 14px !important; font-size: 14px !important; color: var(--ny-dark) !important; box-shadow: none !important; transition: border-color 0.15s ease, box-shadow 0.15s ease !important; width: 100%; max-width: 420px; }
            .awspn-modern-input:focus { border-color: var(--ny-dark) !important; box-shadow: 0 0 0 1px var(--ny-dark) !important; }
            .awspn-modern-input.small-text { width: 120px !important; }
            .awspn-modern-select { border: 1px solid var(--ny-border) !important; border-radius: var(--ny-radius) !important; padding: 10px !important; font-size: 14px !important; color: var(--ny-dark) !important; width: 100%; max-width: 420px; height: 120px !important; background: #ffffff; }
            
            /* Formatting Wrappers */
            .awspn-input-with-prefix, .awspn-input-with-suffix { display: inline-flex; align-items: center; position: relative; width: 100%; max-width: 420px; }
            .awspn-prefix-label, .awspn-suffix-label { position: absolute; font-size: 13px; font-weight: 600; color: #a1a1aa; font-family: var(--font-studio); }
            .awspn-input-with-prefix .awspn-prefix-label { left: 14px; }
            .awspn-input-with-prefix .awspn-modern-input { padding-left: 36px !important; }
            .awspn-input-with-suffix .awspn-suffix-label { right: 14px; }
            .awspn-input-with-suffix .awspn-modern-input { padding-right: 44px !important; }
            
            /* Custom Form Elements */
            .awspn-toggle-container { position: relative; display: inline-block; width: 44px; height: 24px; }
            .awspn-toggle-container input { opacity: 0; width: 0; height: 0; margin: 0 !important; }
            .awspn-toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e4e4e7; transition: .2s cubic-bezier(0.16, 1, 0.3, 1); border-radius: 24px; }
            .awspn-toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .2s cubic-bezier(0.16, 1, 0.3, 1); border-radius: 50%; box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); }
            .awspn-toggle-container input:checked+.awspn-toggle-slider { background-color: var(--ny-dark); }
            .awspn-toggle-container input:checked+.awspn-toggle-slider:before { transform: translateX(20px); }
            
            .awspn-color-picker-wrapper { display: flex; align-items: center; gap: 12px; }
            .awspn-color-picker-wrapper input[type="color"] { -webkit-appearance: none; border: 1px solid var(--ny-border); width: 40px; height: 40px; border-radius: var(--ny-radius); cursor: pointer; background: none; padding: 0; }
            .awspn-color-picker-wrapper input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
            .awspn-color-picker-wrapper input[type="color"]::-webkit-color-swatch { border: none; border-radius: var(--ny-radius); }
            .awspn-field-desc { font-size: 13px; color: var(--ny-muted); margin: 8px 0 0 0; line-height: 1.4; }
            
            /* Premium Engine States */
            .awspn-pro-badge { background: var(--ny-pro-bg); color: var(--ny-pro-color); font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 2px 6px; border-radius: 4px; letter-spacing: 0.05em; display: inline-block; vertical-align: middle; }
            .awspn-ecosystem-badge { background: var(--ny-eco-bg); color: var(--ny-eco-color); font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 2px 6px; border-radius: 4px; letter-spacing: 0.05em; display: inline-block; vertical-align: middle; }
            .awspn-tab-trigger .awspn-pro-badge { font-size: 9px; padding: 1px 4px; }
            .awspn-row-locked { position: relative; }
            .awspn-row-locked th, .awspn-row-locked td { opacity: 0.55; }
            .awspn-row-locked .awspn-modern-input, .awspn-row-locked .awspn-modern-select, .awspn-row-locked input[type="color"] { background: #f4f4f5 !important; cursor: not-allowed !important; border-color: var(--ny-border) !important; }
            .awspn-row-locked .awspn-toggle-slider { background-color: #e4e4e7 !important; cursor: not-allowed !important; }

            /* Interactive Sidebar Upsells */
            .awspn-sidebar-promo-container { display: flex; flex-direction: column; gap: 24px; }
            .awspn-sidebar-section-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--ny-dark); margin: 0 0 12px 0; padding-bottom: 8px; border-bottom: 1px solid var(--ny-border); }
            .awspn-sidebar-box { background: #ffffff; border: 1px solid var(--ny-border); border-radius: var(--ny-radius); padding: 18px; transition: border-color 0.2s ease, box-shadow 0.2s ease; margin-bottom: 14px; }
            .awspn-sidebar-box:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); }
            .awspn-sidebar-box.premium-highlight { background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); border-color: #bfdbfe; }
            .awspn-sidebar-box.ecosystem-highlight { background: linear-gradient(180deg, #ffffff 0%, #fbfdfb 100%); border-color: #bbf7d0; }
            .awspn-sidebar-box-title { font-size: 14px; font-weight: 600; display: flex; align-items: center; justify-content: space-between; margin: 0 0 8px 0; color: var(--ny-dark); line-height: 1.3; }
            .awspn-sidebar-box-desc { font-size: 12.5px; line-height: 1.45; color: var(--ny-muted); margin: 0 0 14px 0; }
            .awspn-sidebar-action-link { display: inline-flex; align-items: center; gap: 4px; font-size: 12.5px; font-weight: 600; color: var(--ny-pro-color); text-decoration: none; transition: color 0.15s ease; }
            .awspn-sidebar-action-link:hover { color: var(--ny-accent-hover); text-decoration: underline; }
            .awspn-sidebar-box.ecosystem-highlight .awspn-sidebar-action-link { color: var(--ny-eco-color); }
            .awspn-sidebar-box.ecosystem-highlight .awspn-sidebar-action-link:hover { color: var(--ny-eco-hover); }
            
            /* Footer Styling */
            .awspn-ny-footer { margin-top: 40px; padding-top: 24px; border-top: 1px solid var(--ny-border); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; }
            .awspn-ny-footer .submit { padding: 0; margin: 0; }
            .awspn-ny-footer .submit button, .awspn-ny-footer .submit input[type="submit"] { background: var(--ny-dark) !important; border: 1px solid var(--ny-dark) !important; color: #ffffff !important; padding: 12px 24px !important; font-size: 14px !important; font-weight: 500 !important; font-family: var(--font-studio) !important; border-radius: var(--ny-radius) !important; cursor: pointer !important; box-shadow: none !important; text-shadow: none !important; transition: background 0.15s ease !important; height: auto !important; }
            .awspn-ny-footer .submit button:hover, .awspn-ny-footer .submit input[type="submit"]:hover { background: #222222 !important; }
            .awspn-footer-promo { font-size: 13px; color: var(--ny-muted); }
            .awspn-footer-promo a { color: var(--ny-pro-color); text-decoration: none; font-weight: 600; }
            .awspn-footer-promo a:hover { text-decoration: underline; }
        </style>

        <div class="wrap awspn-ny-dashboard">
            <div class="awspn-ny-header">
                <div>
                    <h1><?php _e( 'Set Price Note', 'woo-set-price-note' ); ?></h1>
                    <p><?php _e( 'Engineered configuration control panels.', 'woo-set-price-note' ); ?></p>
                </div>
                <div>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $this->get_settings_slug() . '-pricing' ) ); ?>" target="_blank" class="awspn-go-pro-btn">
                        <span class="dashicons dashicons-admin-plugins" style="font-size:16px; width:16px; height:16px; margin:0;"></span>
                        <?php _e( 'Upgrade to Pro version', 'woo-set-price-note' ); ?>
                    </a>
                </div>
            </div>

            <!-- Layout Body Workspace Split -->
            <div class="awspn-layout-body">
                
                <!-- Main Controls Engine Column (Left) -->
                <div class="awspn-layout-main">
                    <nav class="awspn-ny-tabs">
                        <button type="button" class="awspn-tab-trigger is-active" data-target="core-config"><?php _e( 'General Settings', 'woo-set-price-note' ); ?></button>
                        <button type="button" class="awspn-tab-trigger" data-target="oe-config"><?php _e( 'Orders &amp; Emails', 'woo-set-price-note' ); ?>  <span class="awspn-pro-badge"><?php _e( 'Pro', 'woo-set-price-note' ); ?></span></button>
                        <button type="button" class="awspn-tab-trigger" data-target="visual-config"><?php _e( 'Visual Designer', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge"><?php _e( 'Pro', 'woo-set-price-note' ); ?></span></button>
                        <button type="button" class="awspn-tab-trigger" data-target="conditional-config"><?php _e( 'Display Constraints', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge"><?php _e( 'Pro', 'woo-set-price-note' ); ?></span></button>
                    </nav>

                    <form method="post" action="options.php">
                        <?php settings_fields( 'awspn_options_group' ); ?>

                        <div id="core-config" class="awspn-tab-content is-active">
                            <table class="awspn-form-table" role="presentation">
                                <tr>
                                    <th scope="row">
                                        <label for="awspn_global_settings_separator"><?php _e( 'Default Separator', 'woo-set-price-note' ); ?></label>
                                    </th>
                                    <td>
                                        <input type="text" id="awspn_global_settings_separator" name="awspn_global_settings[separator]" value="<?php echo esc_attr( $current_separator ); ?>" class="awspn-modern-input small-text" placeholder="/">
                                        <p class="awspn-field-desc"><?php _e( 'Character displaying between price and note (e.g. / or - )', 'woo-set-price-note' ); ?></p>
                                    </td>
                                </tr>
                                <tr class="awspn-row-locked">
                                    <th scope="row">
                                        <?php _e( 'Default Global Note', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge"><?php _e( 'Pro', 'woo-set-price-note' ); ?></span>
                                    </th>
                                    <td>
                                        <input type="text" disabled class="awspn-modern-input regular-text" placeholder="e.g. Per Piece">
                                        <p class="awspn-field-desc"><?php _e( 'Default message text appended beside standard numerical currency blocks.', 'woo-set-price-note' ); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div id="oe-config" class="awspn-tab-content">
                            <table class="awspn-form-table" role="presentation">
                                <tr class="awspn-row-locked">
                                    <th scope="row"><?php _e( 'Include on Order/Emails', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge">Pro</span></th>
                                    <td>
                                        <label class="awspn-toggle-container">
                                            <input type="checkbox" disabled>
                                            <span class="awspn-toggle-slider"></span>
                                        </label>
                                        <p class="awspn-field-desc"><?php _e( 'Renders text fields on checkout and receipt markup.', 'woo-set-price-note' ); ?></p>
                                    </td>
                                </tr>
                                <tr class="awspn-row-locked">
                                    <th scope="row"><?php _e( 'Default Custom Label', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge">Pro</span></th>
                                    <td>
                                        <input type="text" disabled class="awspn-modern-input regular-text" placeholder="Price Note:">
                                        <p class="awspn-field-desc"><?php _e( 'Prefix text block.', 'woo-set-price-note' ); ?></p>
                                    </td>
                                </tr>
                                <tr class="awspn-row-locked">
                                    <th scope="row"><?php _e( 'Default Custom Texts', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge">Pro</span></th>
                                    <td>
                                        <input type="text" disabled class="awspn-modern-input regular-text">
                                        <p class="awspn-field-desc"><?php _e( 'Appended text blocks.', 'woo-set-price-note' ); ?></p>
                                    </td>
                                </tr>
                                <tr class="awspn-row-locked">
                                    <th scope="row"><?php _e( 'Exclude Price on Emails', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge">Pro</span></th>
                                    <td>
                                        <label class="awspn-toggle-container">
                                            <input type="checkbox" disabled>
                                            <span class="awspn-toggle-slider"></span>
                                        </label>
                                        <p class="awspn-field-desc"><?php _e( 'Hides numerical price data in outgoing message tables.', 'woo-set-price-note' ); ?></p>
                                    </td>
                                </tr>
                                <tr class="awspn-row-locked">
                                    <th scope="row"><?php _e( 'Exclude Separator on Emails', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge">Pro</span></th>
                                    <td>
                                        <label class="awspn-toggle-container">
                                            <input type="checkbox" disabled>
                                            <span class="awspn-toggle-slider"></span>
                                        </label>
                                        <p class="awspn-field-desc"><?php _e( 'Drops divider character inside email templates.', 'woo-set-price-note' ); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div id="visual-config" class="awspn-tab-content">
                            <table class="awspn-form-table" role="presentation">
                                <tr class="awspn-row-locked">
                                    <th scope="row"><?php _e( 'Default Font Size (px)', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge">Pro</span></th>
                                    <td>
                                        <div class="awspn-input-with-suffix">
                                            <input type="number" disabled value="12" class="awspn-modern-input">
                                            <span class="awspn-suffix-label">PX</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="awspn-row-locked">
                                    <th scope="row"><?php _e( 'Default Text Color', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge">Pro</span></th>
                                    <td>
                                        <div class="awspn-color-picker-wrapper">
                                            <input type="color" disabled value="#000000">
                                            <input type="text" disabled class="awspn-modern-input small-text" value="#000000">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div id="conditional-config" class="awspn-tab-content">
                            <table class="awspn-form-table" role="presentation">
                                <tr class="awspn-row-locked">
                                    <th scope="row"><?php _e( 'Display for User Roles', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge">Pro</span></th>
                                    <td>
                                        <select disabled class="awspn-modern-select">
                                            <option value=""><?php _e( 'All Customer States...', 'woo-set-price-note' ); ?></option>
                                        </select>
                                        <p class="awspn-field-desc"><?php _e( 'Select specific user brackets. Keep unselected to run globally across all customer states.', 'woo-set-price-note' ); ?></p>
                                    </td>
                                </tr>
                                <tr class="awspn-row-locked">
                                    <th scope="row"><?php _e( 'Minimum Cart Total', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge">Pro</span></th>
                                    <td>
                                        <div class="awspn-input-with-prefix">
                                            <span class="awspn-prefix-label">$</span>
                                            <input type="number" disabled class="awspn-modern-input">
                                        </div>
                                        <p class="awspn-field-desc"><?php _e( 'Only display values if subtotal passes threshold value.', 'woo-set-price-note' ); ?></p>
                                    </td>
                                </tr>
                                <tr class="awspn-row-locked">
                                    <th scope="row"><?php _e( 'Exclude Categories', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge">Pro</span></th>
                                    <td>
                                        <select disabled class="awspn-modern-select">
                                            <option value=""><?php _e( 'All Inventory Clusters...', 'woo-set-price-note' ); ?></option>
                                        </select>
                                        <p class="awspn-field-desc"><?php _e( 'Bypass visibility logic across target inventory clusters.', 'woo-set-price-note' ); ?></p>
                                    </td>
                                </tr>
                                <tr class="awspn-row-locked">
                                    <th scope="row"><?php _e( 'Active From Date', 'woo-set-price-note' ); ?> <span class="awspn-pro-badge">Pro</span></th>
                                    <td>
                                        <input type="date" disabled class="awspn-modern-input text-regular">
                                        <p class="awspn-field-desc"><?php _e( 'Time constraint filter activation.', 'woo-set-price-note' ); ?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="awspn-ny-footer">
                            <div>
                                <?php submit_button( __( 'Save Changes', 'woo-set-price-note' ) ); ?>
                            </div>
                            <div class="awspn-footer-promo">
                                <?php echo sprintf( __( 'Want to configure conditional visual styles & target positions? %s', 'woo-set-price-note' ), '<a href="'. esc_url( admin_url( 'admin.php?page=' . $this->get_settings_slug() . '-pricing' ) ) .'" target="_blank">' . __( 'Get the Pro Version &rarr;', 'woo-set-price-note' ) . '</a>' ); ?>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Unified Promotional Sidebar Column (Right) -->
                <div class="awspn-sidebar-promo-container">
                    
                    <!-- Section: Premium Enhancements -->
                    <div class="awspn-sidebar-group">
                        <div class="awspn-sidebar-section-title"><?php _e( 'Set Price Note Pro Features', 'woo-set-price-note' ); ?></div>
                        
                        <div class="awspn-sidebar-box premium-highlight">
                            <div class="awspn-sidebar-box-title">
                                <?php _e( 'Individual Variations', 'woo-set-price-note' ); ?>
                                <span class="awspn-pro-badge">Pro</span>
                            </div>
                            <p class="awspn-sidebar-box-desc">
                                <?php _e( 'Assign individual variable price notes unique to distinct product attributes, colors, and sizes instead of a flat fallback schema.', 'woo-set-price-note' ); ?>
                            </p>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $this->get_settings_slug() . '-pricing' ) ); ?>" target="_blank" class="awspn-sidebar-action-link">
                                <?php _e( 'Unlock Feature &rarr;', 'woo-set-price-note' ); ?>
                            </a>
                        </div>

                        <div class="awspn-sidebar-box">
                            <div class="awspn-sidebar-box-title">
                                <?php _e( 'Bulk Edit Tools', 'woo-set-price-note' ); ?>
                                <span class="awspn-pro-badge">Pro</span>
                            </div>
                            <p class="awspn-sidebar-box-desc">
                                <?php _e( 'Process hundreds of product price notes in seconds. Seamlessly integrated with WooCommerce native core bulk edit action routines.', 'woo-set-price-note' ); ?>
                            </p>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $this->get_settings_slug() . '-pricing' ) ); ?>" target="_blank" class="awspn-sidebar-action-link">
                                <?php _e( 'Explore Bulk Editing &rarr;', 'woo-set-price-note' ); ?>
                            </a>
                        </div>

                        <div class="awspn-sidebar-box">
                            <div class="awspn-sidebar-box-title">
                                <?php _e( 'Gutenberg Blocks Support', 'woo-set-price-note' ); ?>
                                <span class="awspn-pro-badge">Pro</span>
                            </div>
                            <p class="awspn-sidebar-box-desc">
                                <?php _e( 'Fully optimized layout support rendering dynamic price notes smoothly inside high-fidelity Gutenberg Cart and Checkout blocks.', 'woo-set-price-note' ); ?>
                            </p>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $this->get_settings_slug() . '-pricing' ) ); ?>" target="_blank" class="awspn-sidebar-action-link">
                                <?php _e( 'Check Block Support &rarr;', 'woo-set-price-note' ); ?>
                            </a>
                        </div>
                    </div>

                    <!-- Section: Ecosystem Cross-Promotion -->
                    <div class="awspn-sidebar-group">
                        <div class="awspn-sidebar-section-title"><?php _e( 'More Essential Free Tools', 'woo-set-price-note' ); ?></div>

                        <div class="awspn-sidebar-box ecosystem-highlight">
                            <div class="awspn-sidebar-box-title">
                                <?php _e( 'Floating Minicart', 'woo-set-price-note' ); ?>
                                <span class="awspn-ecosystem-badge"><?php _e( 'Free Addon', 'woo-set-price-note' ); ?></span>
                            </div>
                            <p class="awspn-sidebar-box-desc">
                                <?php _e( 'Eliminate checkout friction! Adds a sleek, hardware-accelerated slide-out drawer minicart that keeps customers in the buying flow everywhere on your site.', 'woo-set-price-note' ); ?>
                            </p>
                            <a href="https://wordpress.org/plugins/woo-floating-minicart/" target="_blank" class="awspn-sidebar-action-link">
                                <?php _e( 'Get Free Plugin &rarr;', 'woo-set-price-note' ); ?>
                            </a>
                        </div>

                        <div class="awspn-sidebar-box ecosystem-highlight">
                            <div class="awspn-sidebar-box-title">
                                <?php _e( 'Total Sales Counts', 'woo-set-price-note' ); ?>
                                <span class="awspn-ecosystem-badge"><?php _e( 'Free Addon', 'woo-set-price-note' ); ?></span>
                            </div>
                            <p class="awspn-sidebar-box-desc">
                                <?php _e( 'Drive high-trust social proof by displaying real unit sales counters on single product viewports and shop archives automatically.', 'woo-set-price-note' ); ?>
                            </p>
                            <a href="https://wordpress.org/plugins/woo-total-sales/" target="_blank" class="awspn-sidebar-action-link">
                                <?php _e( 'Get Free Plugin &rarr;', 'woo-set-price-note' ); ?>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                var triggers = document.querySelectorAll('.awspn-tab-trigger');
                var contents = document.querySelectorAll('.awspn-tab-content');

                triggers.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var targetId = this.getAttribute('data-target');
                        triggers.forEach(function(t) { t.classList.remove('is-active'); });
                        this.classList.add('is-active');

                        contents.forEach(function(box) {
                            if (box.id === targetId) {
                                box.classList.add('is-active');
                            } else {
                                box.classList.remove('is-active');
                            }
                        });
                    });
                });
            });
        </script>
        <?php
    }
}

// Inline Conditional Engine Handler:
add_action( 'plugins_loaded', function() {
    if ( ! class_exists( 'Woo_Set_Price_Note_Admin_Pro' ) ) {
        $admin = new Woo_Set_Price_Note_Admin();
        $admin->init();
    }
}, 20 );