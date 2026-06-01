<?php
if (!defined('ABSPATH')) exit;

/**
 * Woo Set Price Note - Backend Product Meta Configuration Class
 * Engineered with smooth transitions, interactive conditional visibility, and explicit 
 * architectural cross-linking back to the central plugin dashboard ecosystem.
 */
class Woo_Set_Price_Note_Backend {

    /**
     * Bind administrative hooks to the native WooCommerce lifecycle.
     */
    public function __construct() {
        add_action('woocommerce_product_options_general_product_data', [$this, 'render_admin_trigger']);
        add_action('woocommerce_process_product_meta', [$this, 'save_product_meta']);
        add_filter('woocommerce_product_data_tabs', [$this, 'add_product_data_tab'], 0);
        add_action('woocommerce_product_data_panels', [$this, 'render_product_data_panel']);
        add_action('admin_footer', [$this, 'inject_admin_scripts']);
    }

    /**
     * Register the dedicated custom meta panel tab on the WooCommerce data meta box structure.
     * Restricts presentation dynamically using native standard product classification keys.
     *
     * @param array $tabs Registered admin dashboard core product panel tabs data structures.
     * @return array
     */
    public function add_product_data_tab($tabs) {
        $tabs['awspn-woo-price-note'] = [
            'label'  => __('Price Note', 'woo-set-price-note'),
            'target' => 'awspn_product_data_panel',
            'class'  => ['show_if_simple', 'show_if_grouped', 'show_if_external', 'show_if_subscription', 'show_if_bundle'],
        ];
        return $tabs;
    }

    /**
     * Render the high-fidelity settings panel container displaying granular single-product parameters.
     * Extends control rows with explicit cross-links returning users to the central global settings ecosystem.
     */
    public function render_product_data_panel() {
        global $post;
        $product = wc_get_product($post->ID);
        $global  = Woo_Set_Price_Note_Admin::get_default_settings();
        $stored  = get_option('awspn_global_settings', []);
        $opts    = array_merge($global, $stored);

        // Generate absolute admin URI back to central settings framework
        $global_settings_url = admin_url('admin.php?page=awspn-settings');

        echo '<div id="awspn_product_data_panel" class="panel woocommerce_options_panel hidden">';
        wp_nonce_field('save_awspn_meta', 'awspn_nonce');

        // Layout Inline CSS adjustments targeting custom markup elements inside standard elements
        echo '<style>
            .awspn-meta-header-box { display: flex; justify-content: space-between; align-items: center; background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 16px 20px; margin-bottom: 12px; }
            .awspn-meta-header-box h3 { margin: 0; font-size: 14px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px; }
            .awspn-meta-global-link { font-size: 13px; font-weight: 600; color: #2563eb; text-decoration: none !important; display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; border: 1px solid #bfdbfe; border-radius: 4px; background: #eff6ff; transition: all 0.15s ease-in-out; }
            .awspn-meta-global-link:hover { background: #dbeafe; color: #1d4ed8; border-color: #93c5fd; }
            .awspn-meta-global-link .dashicons { font-size: 16px; width: 16px; height: 16px; line-height: 1.2; }
            .awspn-price-note-preview-wrapper { background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 16px; margin: 12px 20px 20px 162px; box-sizing: border-box; }
            .awspn-price-note-preview-wrapper p.form-field { margin-left: 0 !important; padding-left: 0 !important; }
            .awspn-price-note-preview-wrapper p.form-field label { width: 135px !important; margin-left: 0 !important; }
            @media (max-width: 782px) {
                .awspn-price-note-preview-wrapper { margin: 12px 10px; padding: 12px; }
                .awspn-price-note-preview-wrapper p.form-field label { width: 100% !important; }
            }
        </style>';

        // Top Panel Contextual Ribbon Bar containing cross-linking components
        echo '<div class="awspn-meta-header-box">';
        echo '<h3><span class="dashicons dashicons-tag"></span>' . __('Individual Product Price Note Settings', 'woo-set-price-note') . '</h3>';
        echo '<a href="' . esc_url($global_settings_url) . '" class="awspn-meta-global-link" target="_blank">';
        echo '<span class="dashicons dashicons-admin-generic"></span>' . __('Global Settings &rarr;', 'woo-set-price-note') . '</a>';
        echo '</div>';

        // Block Group One: Core Localization Controls 
        echo '<div class="options_group">';
        woocommerce_wp_text_input([
            'id'          => 'awspn_product_price_note_separator', 
            'label'       => __('Separator', 'woo-set-price-note'), 
            'placeholder' => $opts['separator'] ?? '/'
        ]);
        woocommerce_wp_text_input([
            'id'          => 'awspn_product_price_note', 
            'label'       => __('Price Note', 'woo-set-price-note'), 
            'placeholder' => $opts['note_text'] ?? ''
        ]);
        echo '</div>';

        // Block Group Two: Transactional Order Document Injections
        echo '<div class="options_group">';
        woocommerce_wp_checkbox([
            'id'    => 'awspn_show_on_order_and_email', 
            'label' => __('Include on Order/Emails', 'woo-set-price-note')
        ]);

        $is_checked = get_post_meta($post->ID, 'awspn_show_on_order_and_email', true);
        
        // Contextually bounded container holding isolated conditional child controls
        echo '<div class="awspn-price-note-preview-wrapper" style="display:' . ($is_checked === 'yes' ? 'block' : 'none') . ';">';
        woocommerce_wp_checkbox([
            'id'    => 'awspn_excl_price_on_order_and_email', 
            'label' => __('Exclude Price', 'woo-set-price-note')
        ]);
        woocommerce_wp_checkbox([
            'id'    => 'awspn_excl_sep_on_order_and_email', 
            'label' => __('Exclude Separator', 'woo-set-price-note')
        ]);
        woocommerce_wp_text_input([
            'id'    => 'awspn_product_price_note_oe_label', 
            'label' => __('Custom Label', 'woo-set-price-note')
        ]);
        woocommerce_wp_text_input([
            'id'    => 'awspn_product_price_note_oe_texts', 
            'label' => __('Custom Texts', 'woo-set-price-note')
        ]);
        echo '</div></div></div>';
    }

    /**
     * Provide rapid-access navigational trigger shortcuts in general product panels.
     */
    public function render_admin_trigger() {
        echo '<div class="options_group" style="padding: 10px 20px; border-top: 1px solid #eee;">';
        echo '<button type="button" class="button button-secondary" id="awspn-open-panel" style="display:inline-flex; align-items:center; gap:4px;">';
        echo '<span class="dashicons dashicons-tag" style="font-size:16px; width:16px; height:16px; line-height:1.3;"></span>' . __('Configure Price Note', 'woo-set-price-note') . '</button>';
        echo '</div>';
    }

    /**
     * Inject DOM manipulation event routines safely into admin footer layouts.
     */
    public function inject_admin_scripts() {
        global $pagenow, $post_type;
        if (!in_array($pagenow, ['post.php', 'post-new.php']) || $post_type !== 'product') return;
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // Smooth conditional drawer toggling for visibility control sets
                $('#awspn_show_on_order_and_email').on('change', function() {
                    var $wrapper = $('.awspn-price-note-preview-wrapper');
                    if ($(this).is(':checked')) {
                        $wrapper.slideDown(180);
                    } else {
                        $wrapper.slideUp(180);
                    }
                });

                // Focus routing script map to shift focus to designated custom panels instantly
                $('#awspn-open-panel').on('click', function(e) {
                    e.preventDefault();
                    $('.wc-tabs a[href="#awspn_product_data_panel"]').click();
                    $('html, body').animate({
                        scrollTop: $('#woocommerce-product-data').offset().top - 40
                    }, 200);
                });
            });
        </script>
        <?php
    }

    /**
     * Securely intercept, sanitize, validate and synchronize input values inside DB structures.
     *
     * @param int $post_id Standard numeric identification string mapping to processed inventory row entries.
     */
    public function save_product_meta($post_id) {
        if (!isset($_POST['awspn_nonce']) || !wp_verify_nonce($_POST['awspn_nonce'], 'save_awspn_meta')) return;

        // Process standard string matrices
        $fields = [
            'awspn_product_price_note', 
            'awspn_product_price_note_separator', 
            'awspn_product_price_note_oe_label', 
            'awspn_product_price_note_oe_texts'
        ];
        foreach ($fields as $f) {
            if (isset($_POST[$f])) {
                update_post_meta($post_id, $f, sanitize_text_field($_POST[$f]));
            }
        }

        // Standardize conditional tracking checkboxes to strict binary schemas (yes/no states)
        $checkboxes = [
            'awspn_show_on_order_and_email', 
            'awspn_excl_price_on_order_and_email', 
            'awspn_excl_sep_on_order_and_email'
        ];
        foreach ($checkboxes as $cb) {
            update_post_meta($post_id, $cb, isset($_POST[$cb]) ? 'yes' : 'no');
        }
    }
}
new Woo_Set_Price_Note_Backend();