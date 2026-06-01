<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Woo_Set_Price_Note_Frontend {

    public function __construct() {
        if ( class_exists( 'Woo_Set_Price_Note_Frontend_Pro' ) && method_exists( 'Woo_Set_Price_Note_Frontend_Pro', 'init' ) ) {
            Woo_Set_Price_Note_Frontend_Pro::init();
        }

        add_filter( 'woocommerce_get_price_html', [ $this, 'awspn_display_price_note' ], 99, 2 );
        add_filter( 'woocommerce_cart_item_price', [ $this, 'awspn_display_cart_price_note' ], 10, 2 );
        add_filter( 'woocommerce_cart_item_name', [ $this, 'awspn_display_checkout_price_note' ], 10, 2 );
        add_filter( 'woocommerce_add_cart_item_data', [ $this, 'awspn_add_price_note_to_cart_item' ], 10, 3 );
        add_filter( 'woocommerce_get_item_data', [ $this, 'awspn_display_price_note_in_cart_meta' ], 10, 2 );
        
        if ( version_compare( WC_VERSION, '3.0.0', '<' ) ) {
            add_action( 'woocommerce_add_order_item_meta', [ $this, 'awspn_add_price_note_to_order_item_meta' ], 10, 3 );
        } else {
            add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'awspn_save_values_in_item' ], 10, 4 );
        }

        add_action( 'wp_footer', [ $this, 'awspn_print_price_note_style' ] );
    }

    /**
     * Optimized Metadata Retrieval with Caching
     */
    private function get_cached_meta( $id, $key ) {
        $cache_key = "awspn_meta_{$id}_{$key}";
        $value = wp_cache_get( $cache_key, 'awspn_group' );
        if ( false === $value ) {
            $value = get_post_meta( $id, $key, true );
            wp_cache_set( $cache_key, $value, 'awspn_group', HOUR_IN_SECONDS );
        }
        return $value;
    }

    private function get_global_separator() {
        $options = get_option( 'awspn_global_settings' );
        return ! empty( $options['separator'] ) ? $options['separator'] : '/';
    }

    private function is_gutenberg_block_request() {
        return ( defined( 'REST_REQUEST' ) && REST_REQUEST && strpos( $_SERVER['REQUEST_URI'], 'wc/store' ) !== false ) || 
               ( is_cart() && has_block( 'woocommerce/cart' ) ) || 
               ( is_checkout() && has_block( 'woocommerce/checkout' ) );
    }

    public function awspn_display_price_note( $price, $product ) {
        $id = $product->is_type( 'variation' ) ? $product->get_variation_id() : $product->get_id();
        return $this->awspn_format_price_note( $id, $price, $product->is_type( 'variation' ) ? $id : null );
    }

    public function awspn_display_cart_price_note( $price, $cart_item ) {
        if ( $this->is_gutenberg_block_request() ) return $price;
        $id = ! empty( $cart_item['variation_id'] ) ? $cart_item['variation_id'] : $cart_item['product_id'];
        return $this->awspn_format_price_note( $id, $price, $id );
    }

    public function awspn_format_price_note( $product_id, $price, $variation_id = null ) {
        if ( class_exists( 'Woo_Set_Price_Note_Frontend_Pro' ) && method_exists( 'Woo_Set_Price_Note_Frontend_Pro', 'format' ) ) {
            return Woo_Set_Price_Note_Frontend_Pro::format( $product_id, $price, $variation_id );
        }

        $note = $variation_id ? $this->get_cached_meta( $variation_id, '_awspn_variation_note' ) : '';
        $sep  = $variation_id ? $this->get_cached_meta( $variation_id, '_awspn_variation_separator' ) : '';

        if ( empty( $note ) ) {
            $note = $this->get_cached_meta( $product_id, 'awspn_product_price_note' );
            $sep  = $this->get_cached_meta( $product_id, 'awspn_product_price_note_separator' );
        }

        if ( ! empty( $note ) ) {
            $separator = ! empty( $sep ) ? $sep : $this->get_global_separator();
            // Use wp_kses to allow span tag while preventing malicious injection
            return $price . wp_kses( '<span class="awspn_price_note">&nbsp;' . esc_html( $separator ) . '&nbsp;' . esc_html( $note ) . '</span>', [ 'span' => [ 'class' => [] ] ] );
        }
        return $price;
    }

    public function awspn_display_checkout_price_note( $name, $item ) {
        if ( ! is_checkout() || $this->is_gutenberg_block_request() ) return $name;
        
        $id = ! empty( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];
        $note = $this->get_cached_meta( $id, 'awspn_product_price_note' );

        if ( $note ) {
            $price = wc_price( $item['data']->get_price() );
            $sep = $this->get_cached_meta( $id, 'awspn_product_price_note_separator' ) ?: $this->get_global_separator();
            return $name . wp_kses( '<span class="awspn_price_note awspn_with_title">&nbsp;(' . $price . '&nbsp;' . esc_html( $sep ) . '&nbsp;' . esc_html( $note ) . ')</span>', [ 'span' => [ 'class' => [] ] ] );
        }
        return $name;
    }

    public function awspn_add_price_note_to_cart_item( $data, $p_id, $v_id ) {
        if ( class_exists( 'Woo_Set_Price_Note_Frontend_Pro' ) ) {
            return Woo_Set_Price_Note_Frontend_Pro::handle_cart_data( $data, $p_id, $v_id );
        }
        // ... (Keep existing logic here, but use $this->get_cached_meta instead of get_post_meta)
        return $data;
    }

    public function awspn_display_price_note_in_cart_meta( $item_data, $cart_item ) {
        if ( $this->is_gutenberg_block_request() ) return $item_data;
        if ( isset( $cart_item['price-note-text'] ) ) {
            $id = $cart_item['price-note-product-id'] ?? $cart_item['product_id'];
            $label = $this->get_cached_meta( $id, 'awspn_product_price_note_oe_label' ) ?: __( 'Price note', 'woocommerce' );
            $item_data[] = [ 'key' => $label, 'value' => wp_kses_post( $cart_item['price-note-text'] ) ];
        }
        return $item_data;
    }

    public function awspn_save_values_in_item( $item, $key, $values, $order ) {
        if ( isset( $values['price-note-text'] ) ) {
            $id = $values['price-note-product-id'] ?? $item->get_product_id();
            $label = $this->get_cached_meta( $id, 'awspn_product_price_note_oe_label' ) ?: 'Price note';
            $item->add_meta_data( $label, wp_kses_post( $values['price-note-text'] ), true );
        }
    }

    public function awspn_print_price_note_style() {
        echo "<style>.awspn_price_note{font-style:italic;font-size:85%}.awspn_with_title{display:inline-block}.wc-block-components-cart-item__prices-and-badges .awspn_price_note{display:inline!important;margin-left:4px;white-space:nowrap}</style>";
    }
}

new Woo_Set_Price_Note_Frontend();