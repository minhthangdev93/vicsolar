<?php
/**
 * Landing Solar — enqueue, filters, helpers.
 *
 * @package electro-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Section partials in render order.
 *
 * @return string[]
 */
function electro_child_landing_solar_sections() {
	return array(
		'section-01-section_623584978',
		'section-04-section_1969850638',
		'section-05-section_1537602591',
		'section-06-section_1462343327',
		'section-07-section_245268808',
		'section-08-section_630925722',
		'section-09-section_272174093',
		'section-10-section_387130023',
	);
}

/**
 * @param bool $show Whether to show page header.
 * @return bool
 */
function electro_child_landing_hide_page_header( $show ) {
	if ( is_page_template( 'template-landing-solar.php' ) ) {
		return false;
	}
	return $show;
}
add_filter( 'electro_show_page_header', 'electro_child_landing_hide_page_header' );

/**
 * Hide breadcrumb on landing template.
 *
 * @param bool $show Whether to show breadcrumb.
 * @return bool
 */
function electro_child_landing_hide_breadcrumb( $show ) {
	if ( is_page_template( 'template-landing-solar.php' ) ) {
		return false;
	}
	return $show;
}
add_filter( 'electro_show_breadcrumb', 'electro_child_landing_hide_breadcrumb' );

/**
 * Enqueue landing assets only on the solar landing template.
 */
function electro_child_landing_solar_assets() {
	if ( ! is_page_template( 'template-landing-solar.php' ) ) {
		return;
	}

	$theme_dir = get_stylesheet_directory();
	$theme_uri = get_stylesheet_directory_uri();

	wp_enqueue_style(
		'electro-child-landing-montserrat',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap',
		array(),
		null
	);

	$shim = $theme_dir . '/assets/css/landing-solar.css';
	wp_enqueue_style(
		'electro-child-landing-solar',
		$theme_uri . '/assets/css/landing-solar.css',
		array( 'electro-child-style', 'electro-child-landing-montserrat' ),
		file_exists( $shim ) ? (string) filemtime( $shim ) : '1.0.0'
	);

	$components = $theme_dir . '/assets/css/landing-solar-components.css';
	if ( file_exists( $components ) ) {
		wp_enqueue_style(
			'electro-child-landing-solar-components',
			$theme_uri . '/assets/css/landing-solar-components.css',
			array( 'electro-child-landing-solar' ),
			(string) filemtime( $components )
		);
	}

	$inline = $theme_dir . '/assets/css/landing-solar-inline.css';
	if ( file_exists( $inline ) ) {
		wp_enqueue_style(
			'electro-child-landing-solar-inline',
			$theme_uri . '/assets/css/landing-solar-inline.css',
			array( 'electro-child-landing-solar' ),
			(string) filemtime( $inline )
		);
	}

	$article = $theme_dir . '/assets/css/landing-solar-article.css';
	if ( file_exists( $article ) ) {
		wp_enqueue_style(
			'electro-child-landing-solar-article',
			$theme_uri . '/assets/css/landing-solar-article.css',
			array( 'electro-child-landing-solar' ),
			(string) filemtime( $article )
		);
	}

	$slider_js = $theme_dir . '/assets/js/landing-factory-slider.js';
	if ( file_exists( $slider_js ) ) {
		wp_enqueue_script(
			'electro-child-landing-factory-slider',
			$theme_uri . '/assets/js/landing-factory-slider.js',
			array(),
			(string) filemtime( $slider_js ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'electro_child_landing_solar_assets', 30 );

/**
 * Body class for landing template.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function electro_child_landing_body_class( $classes ) {
	if ( is_page_template( 'template-landing-solar.php' ) ) {
		$classes[] = 'vs-landing-solar-page';
		$classes[] = 'full-width';
	}
	return $classes;
}
add_filter( 'body_class', 'electro_child_landing_body_class' );

/**
 * Plain-text excerpt for landing post cards (no shortcodes / HTML).
 *
 * @param int|null $post        Post ID or null for current post.
 * @param int      $word_limit  Max words.
 * @return string Empty when nothing usable to show.
 */
function electro_child_landing_card_excerpt( $post = null, $word_limit = 22 ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	$text = trim( (string) $post->post_excerpt );
	if ( '' === $text ) {
		$text = (string) $post->post_content;
	}

	$text = strip_shortcodes( $text );
	$text = wp_strip_all_tags( $text );

	// Unregistered shortcodes (su_note, su_list, …) — remove before trim.
	do {
		$prev = $text;
		$text = preg_replace( '/\[[^\]]+\]/', '', $text );
	} while ( $prev !== $text );

	$text = preg_replace( '/\s+/u', ' ', $text );
	$text = trim( $text );

	if ( '' === $text || false !== strpos( $text, '[' ) ) {
		return '';
	}

	$text = wp_trim_words( $text, max( 1, (int) $word_limit ), '…' );

	if ( '' === $text || false !== strpos( $text, '[' ) ) {
		return '';
	}

	return $text;
}

/**
 * WP_Query args for a post category by slug.
 *
 * @param string $category_slug Category slug (e.g. du-an-da-lam, tin-tuc).
 * @param int    $per_page      Number of posts; -1 for all posts in category.
 * @return array
 */
function electro_child_landing_category_posts_query_args( $category_slug, $per_page = 4 ) {
	$per_page = (int) $per_page;
	if ( -1 !== $per_page ) {
		$per_page = max( 1, $per_page );
	}

	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $per_page,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$term = get_term_by( 'slug', sanitize_title( $category_slug ), 'category' );
	if ( $term && ! is_wp_error( $term ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy'         => 'category',
				'field'            => 'term_id',
				'terms'            => array( (int) $term->term_id ),
				'include_children' => true,
			),
		);
	} else {
		$args['category_name'] = sanitize_title( $category_slug );
	}

	return $args;
}

/**
 * WP_Query for posts in a category slug.
 *
 * @param string $category_slug Category slug.
 * @param int    $per_page      Number of posts; -1 for all.
 * @return WP_Query
 */
function electro_child_landing_category_posts_query( $category_slug, $per_page = 4 ) {
	$category_slug = sanitize_title( (string) $category_slug );
	$per_page      = (int) $per_page;
	if ( -1 !== $per_page ) {
		$per_page = max( 1, $per_page );
	}

	$args  = electro_child_landing_category_posts_query_args( $category_slug, $per_page );
	$args  = apply_filters( 'electro_child_landing_category_posts_query_args', $args, $category_slug );
	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		wp_reset_postdata();
		return $query;
	}

	return $query;
}

/**
 * WP_Query — danh mục tin-tuc (khối Điện mặt trời nhà xưởng).
 *
 * @return WP_Query
 */
function electro_child_landing_factory_posts_query() {
	$slug     = apply_filters( 'electro_child_landing_factory_category_slug', 'tin-tuc' );
	$per_page = (int) apply_filters( 'electro_child_landing_factory_posts_per_page', -1 );

	return electro_child_landing_category_posts_query( $slug, $per_page );
}

/**
 * WP_Query — danh mục du-an-da-lam (khối Dự án tiêu biểu).
 *
 * @return WP_Query
 */
function electro_child_landing_projects_posts_query() {
	$slug     = apply_filters( 'electro_child_landing_projects_category_slug', 'du-an-da-lam' );
	$per_page = (int) apply_filters( 'electro_child_landing_projects_posts_per_page', -1 );

	return electro_child_landing_category_posts_query( $slug, $per_page );
}

/**
 * Category archive URL.
 *
 * @param string $category_slug Category slug.
 * @return string
 */
function electro_child_landing_category_archive_url( $category_slug ) {
	$term = get_category_by_slug( sanitize_title( $category_slug ) );
	if ( $term ) {
		$link = get_category_link( $term->term_id );
		if ( ! is_wp_error( $link ) ) {
			return $link;
		}
	}

	return home_url( '/' . sanitize_title( $category_slug ) . '/' );
}

/**
 * Archive URL for section 9 projects block.
 *
 * @return string
 */
function electro_child_landing_du_an_archive_url() {
	return electro_child_landing_category_archive_url(
		apply_filters( 'electro_child_landing_projects_category_slug', 'du-an-da-lam' )
	);
}
