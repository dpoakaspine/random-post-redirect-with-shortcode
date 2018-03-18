<?php
/**
 * Plugin Name: Redirect Post to URL
 * Description: Redirects a post (or any other any post-type) with a custom field <code>'redirect'</code> to another URL
 * Version: 1.1
 * Author: wp-hotline.com ~ Stefan
 * Author URI:  https://www.wp-hotline.com
 */

defined( 'ABSPATH' ) or exit;

function bhrdr2p_redirect_post_to_url() {
    if( !is_singular() ) return;

    global $post;
    $redirect = esc_url( get_post_meta( $post->ID, 'redirect', true ) );
    if( $redirect ) {
        wp_redirect( $redirect, 301 );
        exit;
    }
}
add_action( 'template_redirect', 'bhrdr2p_redirect_post_to_url' );
