<?php
/**
 * @package   random-post-redirect-and-shortcode
 * @author    Stefan Böttcher
 *
 * @wordpress-plugin
 * Plugin Name: Random Post Redirect (also with Shortcode)
 * Description: adds a custom query var for random post redirects, also includes a shortcode to show a random post
 * Version:     1.0
 * Author:      wp-hotline.com ~ Stefan
 * Author URI:  https://wp-hotline.com/
 * License: GPLv2 or later
 */
add_action('init','random_post_redirect_add_rewrite');
function random_post_redirect_add_rewrite() {
       global $wp;
       $wp->add_query_var( random_post_redirect_get_query_var() );
       add_rewrite_rule( random_post_redirect_get_query_var().'/?$', 'index.php?'.random_post_redirect_get_query_var().'=1', 'top');
}

function random_post_redirect_get_query_var() {
  return sanitize_title( apply_filters('random_post_redirect_query_var',__('random','randompostredirect')));
}


add_action('template_redirect','random_post_redirect_template_redirect');
function random_post_redirect_template_redirect() {
       if (get_query_var( random_post_redirect_get_query_var() ) == 1) {
               $posts = get_posts('post_type=post&orderby=rand&numberposts=1');
               foreach($posts as $post) {
                       $link = get_permalink($post);
               }
               wp_redirect($link,307);
               exit;
       }
}

add_shortcode( 'random_post', 'random_post_redirect_post_html' );
function random_post_redirect_post_html( $atts ) {

  $a = shortcode_atts( array(
      'limit' => 1,
      'exclude' => false,
  ), $atts );

  $posts = get_posts('post_type=post&orderby=rand&numberposts='.intval($a["limit"]).'');
  //var_dump( $posts );
  $html = '';
  foreach($posts as $post) {
    $html .= '<a href="'.get_permalink( $post->ID ).'" title="'. esc_attr( get_bloginfo('name').' '.$post->post_title ) .'">';
    $html .= '<img src="'.get_the_post_thumbnail_url( $post->ID ).'" />';
    $html .= '<span class="button">'.$post->post_title.'</span>';
    $html .= '</a>';
  }
  return $html;

}

add_action( 'after_switch_theme', 'random_post_redirect_flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'random_post_redirect_flush_rewrite_rules' );
register_activation_hook( __FILE__, 'random_post_redirect_flush_rewrite_rules' );
function random_post_redirect_flush_rewrite_rules() {
	flush_rewrite_rules();
}
