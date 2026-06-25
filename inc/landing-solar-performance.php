<?php
/**
 * Landing Solar — PageSpeed / Core Web Vitals optimizations.
 *
 * @package electro-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return bool
 */
function electro_child_landing_is_template() {
	return is_page_template( 'template-landing-solar.php' );
}

/**
 * Resource hints (preconnect) for fonts.
 */
function electro_child_landing_resource_hints( $urls, $relation_type ) {
	if ( ! electro_child_landing_is_template() || 'preconnect' !== $relation_type ) {
		return $urls;
	}

	$urls[] = array(
		'href' => 'https://fonts.googleapis.com',
	);
	$urls[] = array(
		'href'        => 'https://fonts.gstatic.com',
		'crossorigin' => 'anonymous',
	);

	return $urls;
}
add_filter( 'wp_resource_hints', 'electro_child_landing_resource_hints', 10, 2 );

/**
 * Preload LCP hero background image.
 */
function electro_child_landing_preload_lcp() {
	if ( ! electro_child_landing_is_template() || ! function_exists( 'electro_child_landing_image_url' ) ) {
		return;
	}

	$url = electro_child_landing_image_url( 's01_bg_image' );
	if ( ! $url ) {
		return;
	}

	printf(
		'<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n",
		esc_url( $url )
	);
}
add_action( 'wp_head', 'electro_child_landing_preload_lcp', 1 );

/**
 * Load non-critical stylesheets without blocking first paint.
 *
 * @param string $html   Link tag HTML.
 * @param string $handle Style handle.
 * @return string
 */
function electro_child_landing_async_styles( $html, $handle ) {
	if ( ! electro_child_landing_is_template() ) {
		return $html;
	}

	$async_handles = array(
		'electro-child-landing-montserrat',
		'electro-child-landing-solar-article',
		'electro-child-landing-solar-inline',
	);

	if ( ! in_array( $handle, $async_handles, true ) ) {
		return $html;
	}

	$async = str_replace( "media='all'", "media='print' onload=\"this.media='all'\"", $html );
	$async = str_replace( 'media="all"', 'media="print" onload="this.media=\'all\'"', $async );

	return $async . '<noscript>' . $html . '</noscript>';
}
add_filter( 'style_loader_tag', 'electro_child_landing_async_styles', 10, 2 );

/**
 * Defer slider script (WP 6.3+).
 *
 * @param string $tag    Script tag.
 * @param string $handle Handle.
 * @return string
 */
function electro_child_landing_defer_scripts( $tag, $handle ) {
	if ( ! electro_child_landing_is_template() ) {
		return $tag;
	}

	if ( 'electro-child-landing-factory-slider' === $handle && false === strpos( $tag, ' defer' ) ) {
		return str_replace( ' src', ' defer src', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'electro_child_landing_defer_scripts', 10, 2 );

/**
 * Drop assets not needed on the landing template.
 */
function electro_child_landing_dequeue_assets() {
	if ( ! electro_child_landing_is_template() ) {
		return;
	}

	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'wc-blocks-style' );
	wp_dequeue_style( 'wc-blocks-vendors-style' );

	wp_dequeue_script( 'wp-embed' );

	if ( ! is_user_logged_in() ) {
		wp_dequeue_style( 'dashicons' );
	}
}
add_action( 'wp_enqueue_scripts', 'electro_child_landing_dequeue_assets', 100 );

/**
 * Disable emoji scripts/styles on landing (minor JS/CSS savings).
 */
function electro_child_landing_disable_emojis() {
	if ( ! electro_child_landing_is_template() ) {
		return;
	}

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'electro_child_landing_disable_emojis' );

/**
 * Cap factory/project card queries (avoid loading every post).
 *
 * @param int $per_page Posts per query.
 * @return int
 */
function electro_child_landing_cap_card_queries( $per_page ) {
	if ( $per_page < 0 ) {
		return 12;
	}

	return $per_page;
}
add_filter( 'electro_child_landing_factory_posts_per_page', 'electro_child_landing_cap_card_queries' );
add_filter( 'electro_child_landing_projects_posts_per_page', 'electro_child_landing_cap_card_queries' );
