<?php
if ( ! function_exists( 'wspnp_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wspnp_fs() {
        global $wspnp_fs;

        if ( ! isset( $wspnp_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';

            $wspnp_fs = fs_dynamic_init( array(
                'id'                  => '30929',
                'slug'                => 'woo-set-price-note',
                'premium_slug'        => 'woo-set-price-note-pro',
                'type'                => 'plugin',
                'public_key'          => 'pk_822c9c6a0f784ef0de3dd412063f9',
                'is_premium'          => false,
                'has_addons'          => false,
                'is_premium_only'     => false,
                'has_paid_plans'      => true,
                'is_org_compliant'    => true,
                'menu'                => array(
                    'slug'           => 'awspn-settings',
                    'first-path'     => 'admin.php?page=awspn-settings',
                    'support'        => false,
                ),
            ) );
        }

        return $wspnp_fs;
    }

    // Init Freemius.
    wspnp_fs();
    // Signal that SDK was initiated.
    do_action( 'wspnp_fs_loaded' );
}