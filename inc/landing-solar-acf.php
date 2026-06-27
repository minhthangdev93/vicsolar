<?php
/**
 * Landing Solar — ACF Pro options, helpers, JSON sync.
 *
 * Data model:
 * - Field definitions: PHP + acf-json/ (deploy with code).
 * - Field values: wp_options via ACF Options Page (per environment DB).
 * - Empty DB fields are pre-filled from landing-solar-defaults.php (admin + seed).
 * - Deploying theme code never overwrites fields admin already saved.
 *
 * @package electro-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ACF options storage id (must match `post_id` in acf_add_options_page).
 *
 * @return string
 */
function electro_child_landing_option_id() {
	return 'option';
}

/**
 * Post IDs / option prefixes that may hold landing data (legacy + ACF normalized).
 *
 * @return string[]
 */
function electro_child_landing_option_post_ids() {
	return array( 'option', 'options', 'vs-landing-solar' );
}

/**
 * @param string $post_id ACF post_id from load_value / save.
 * @return bool
 */
function electro_child_landing_acf_matches_options_page( $post_id ) {
	return in_array( (string) $post_id, electro_child_landing_option_post_ids(), true );
}

/**
 * @return array<string, mixed>
 */
function electro_child_landing_defaults() {
	static $defaults = null;

	if ( null === $defaults ) {
		$defaults = require get_stylesheet_directory() . '/inc/landing-solar-defaults.php';
		$defaults = electro_child_landing_merge_structured_defaults( $defaults );
	}

	return $defaults;
}

/**
 * Field names that receive text defaults (excludes image uploads).
 *
 * @return string[]
 */
function electro_child_landing_default_field_keys() {
	return array_keys( electro_child_landing_defaults() );
}

/**
 * Default string for an ACF field (empty when not defined).
 *
 * @param string $key Field name.
 * @return string
 */
function electro_child_landing_default_for_field( $key ) {
	$defaults = electro_child_landing_defaults();
	if ( isset( $defaults[ $key ] ) ) {
		return (string) $defaults[ $key ];
	}

	$article = electro_child_landing_article_defaults();
	if ( isset( $article[ $key ] ) && ! is_array( $article[ $key ] ) ) {
		return (string) $article[ $key ];
	}

	return '';
}

/**
 * Read raw value from DB without load_value/default injection.
 *
 * @param string $key Field name.
 * @return mixed|null
 */
function electro_child_landing_get_stored_field( $key ) {
	foreach ( electro_child_landing_option_post_ids() as $post_id ) {
		if ( function_exists( 'acf_get_metadata' ) ) {
			$stored = acf_get_metadata( $post_id, $key );
			if ( null !== $stored && false !== $stored && ! electro_child_landing_acf_value_is_empty( $stored ) ) {
				return $stored;
			}
		}
	}

	foreach ( array( 'options_' . $key, 'vs-landing-solar_' . $key, 'option_' . $key ) as $option_key ) {
		$stored = get_option( $option_key, null );
		if ( null !== $stored && false !== $stored && ! electro_child_landing_acf_value_is_empty( $stored ) ) {
			return $stored;
		}
	}

	return null;
}

/**
 * Move values saved under legacy vs-landing-solar_* keys into ACF option storage.
 */
function electro_child_landing_migrate_legacy_option_keys() {
	if ( get_option( 'electro_child_landing_options_migrated' ) ) {
		return;
	}

	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	foreach ( electro_child_landing_default_field_keys() as $key ) {
		$legacy = get_option( 'vs-landing-solar_' . $key, null );
		if ( electro_child_landing_acf_value_is_empty( $legacy ) ) {
			continue;
		}

		$current = get_option( 'options_' . $key, null );
		if ( ! electro_child_landing_acf_value_is_empty( $current ) ) {
			continue;
		}

		update_field( $key, $legacy, electro_child_landing_option_id() );
	}

	update_option( 'electro_child_landing_options_migrated', 1, false );
}
add_action( 'acf/init', 'electro_child_landing_migrate_legacy_option_keys', 20 );

/**
 * Migrate brand repeater rows from legacy subfield names (title, btn_label…) to s10_* keys.
 */
function electro_child_landing_migrate_brand_card_keys() {
	if ( get_option( 'electro_child_landing_brand_cards_migrated_v3' ) ) {
		return;
	}

	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	$rows = electro_child_landing_read_brand_cards_from_options();
	if ( is_array( $rows ) && ! electro_child_landing_brand_repeater_is_hollow( $rows ) ) {
		$prepared = array();
		foreach ( $rows as $row ) {
			$prepared[] = electro_child_landing_prepare_brand_card_for_acf(
				electro_child_landing_normalize_brand_card( is_array( $row ) ? $row : array() )
			);
		}
		update_field( 's10_brand_cards', $prepared, electro_child_landing_option_id() );
	}

	update_option( 'electro_child_landing_brand_cards_migrated_v3', 1, false );
}
add_action( 'acf/init', 'electro_child_landing_migrate_brand_card_keys', 24 );

/**
 * Track that admin saved landing options (prevents auto-seed overwriting edits).
 *
 * @param string|int $post_id ACF post_id.
 */
function electro_child_landing_on_options_save( $post_id ) {
	if ( ! electro_child_landing_acf_matches_options_page( (string) $post_id ) ) {
		return;
	}

	update_option( 'electro_child_landing_user_saved', time(), false );
}
add_action( 'acf/save_post', 'electro_child_landing_on_options_save', 20 );

/**
 * Pre-fill empty option fields in DB (once per field). Never overwrites saved content.
 */
function electro_child_landing_seed_missing_defaults() {
	if ( ! function_exists( 'update_field' ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}

	static $seeded = false;
	if ( $seeded ) {
		return;
	}
	$seeded = true;

	$defaults = electro_child_landing_defaults();

	foreach ( electro_child_landing_default_field_keys() as $key ) {
		if ( ! isset( $defaults[ $key ] ) ) {
			continue;
		}

		$stored = electro_child_landing_get_stored_field( $key );
		if ( ! electro_child_landing_acf_value_is_empty( $stored ) ) {
			continue;
		}

		update_field( $key, $defaults[ $key ], electro_child_landing_option_id() );
	}

	electro_child_landing_seed_repeaters();
	electro_child_landing_seed_article_fields();
}
add_action( 'acf/init', 'electro_child_landing_seed_missing_defaults', 25 );

/**
 * Pre-fill empty repeater fields (pricing cards, brand cards).
 */
function electro_child_landing_seed_repeaters() {
	if ( ! function_exists( 'update_field' ) || ! is_admin() ) {
		return;
	}

	foreach ( array( 's04', 's05', 's06', 's07', 's08' ) as $prefix ) {
		$key  = $prefix . '_cards';
		$rows = electro_child_landing_read_price_cards_from_options( $prefix );
		if ( ! is_array( $rows ) || electro_child_landing_price_repeater_is_hollow( $rows, $prefix ) ) {
			$rows = electro_child_landing_get_field_raw( $key );
		}

		if ( ! electro_child_landing_price_repeater_is_hollow( $rows, $prefix ) ) {
			continue;
		}

		$pricing = electro_child_landing_pricing_defaults();
		if ( empty( $pricing[ $prefix ]['cards'] ) ) {
			continue;
		}

		update_field(
			$key,
			electro_child_landing_prepare_price_cards_for_acf( $pricing[ $prefix ]['cards'], $prefix ),
			electro_child_landing_option_id()
		);
	}

	$video_rows = electro_child_landing_read_videos_from_options();
	if ( ! is_array( $video_rows ) || electro_child_landing_video_repeater_is_hollow( $video_rows ) ) {
		$video_rows = electro_child_landing_get_field_raw( 's09_videos' );
	}

	if ( electro_child_landing_video_repeater_is_hollow( $video_rows ) ) {
		update_field(
			's09_videos',
			electro_child_landing_video_defaults(),
			electro_child_landing_option_id()
		);
	}

	$brand_rows = electro_child_landing_read_brand_cards_from_options();
	if ( ! is_array( $brand_rows ) || electro_child_landing_brand_repeater_is_hollow( $brand_rows ) ) {
		$brand_rows = electro_child_landing_get_field_raw( 's10_brand_cards' );
	}

	if ( electro_child_landing_brand_repeater_is_hollow( $brand_rows ) ) {
		$brands = electro_child_landing_brands_defaults();
		if ( ! empty( $brands['cards'] ) ) {
			update_field(
				's10_brand_cards',
				electro_child_landing_prepare_brand_cards_for_acf( $brands['cards'] ),
				electro_child_landing_option_id()
			);
		}
	}
}

/**
 * Pre-fill article scalar + repeater fields when still empty.
 */
function electro_child_landing_seed_article_fields() {
	if ( ! function_exists( 'update_field' ) || ! is_admin() ) {
		return;
	}

	foreach ( electro_child_landing_article_scalar_keys() as $key ) {
		$stored = electro_child_landing_get_stored_field( $key );
		if ( ! electro_child_landing_acf_value_is_empty( $stored ) ) {
			continue;
		}

		$defaults = electro_child_landing_article_defaults();
		if ( ! isset( $defaults[ $key ] ) ) {
			continue;
		}

		update_field( $key, $defaults[ $key ], electro_child_landing_option_id() );
	}

	foreach ( electro_child_landing_article_repeater_keys() as $repeater ) {
		$rows = electro_child_landing_get_field_raw( $repeater );
		if ( ! electro_child_landing_article_repeater_is_hollow( $rows, $repeater ) ) {
			continue;
		}

		$defaults = electro_child_landing_article_defaults();
		if ( empty( $defaults[ $repeater ] ) || ! is_array( $defaults[ $repeater ] ) ) {
			continue;
		}

		update_field(
			$repeater,
			electro_child_landing_article_prepare_rows_for_acf( $defaults[ $repeater ], $repeater ),
			electro_child_landing_option_id()
		);
	}
}

/**
 * Show default copy inside admin inputs when DB value is still empty.
 *
 * @param mixed  $value   Field value.
 * @param string $post_id Options page id.
 * @param array  $field   Field config.
 * @return mixed
 */
function electro_child_landing_acf_load_default( $value, $post_id, $field ) {
	if ( ! is_admin() ) {
		return $value;
	}

	if ( ! electro_child_landing_acf_matches_options_page( $post_id ) ) {
		return $value;
	}

	if ( empty( $field['name'] ) || empty( $field['type'] ) ) {
		return $value;
	}

	if ( in_array( $field['type'], array( 'tab', 'message', 'image' ), true ) ) {
		return $value;
	}

	if ( 'repeater' === $field['type'] ) {
		if ( 's09_videos' === $field['name'] ) {
			$rows = is_array( $value ) ? $value : array();
			if ( electro_child_landing_video_repeater_is_hollow( $rows ) ) {
				return electro_child_landing_video_defaults_for_admin();
			}
			return $value;
		}

		if ( 's10_brand_cards' === $field['name'] ) {
			return electro_child_landing_brand_cards_for_acf_admin( is_array( $value ) ? $value : array() );
		}

		if ( in_array( $field['name'], electro_child_landing_article_repeater_keys(), true ) ) {
			return electro_child_landing_article_repeater_for_acf_admin( $field['name'], is_array( $value ) ? $value : array() );
		}

		if ( preg_match( '/^(s\d{2})_cards$/', $field['name'], $matches ) ) {
			$prefix = $matches[1];
			return electro_child_landing_price_cards_for_acf_admin( $prefix, is_array( $value ) ? $value : array() );
		}
	}

	if ( ! electro_child_landing_acf_value_is_empty( $value ) ) {
		return $value;
	}

	$default = electro_child_landing_default_for_field( $field['name'] );
	return '' !== $default ? $default : $value;
}
add_filter( 'acf/load_value', 'electro_child_landing_acf_load_default', 10, 3 );

/**
 * @param mixed $value ACF value.
 * @return bool
 */
function electro_child_landing_acf_value_is_empty( $value ) {
	if ( null === $value || false === $value ) {
		return true;
	}

	if ( is_string( $value ) ) {
		return '' === trim( $value );
	}

	if ( is_array( $value ) ) {
		return empty( $value );
	}

	return false;
}

/**
 * Read ACF value without acf/load_value default injection (DB / legacy keys only).
 *
 * @param string $key Field name.
 * @return mixed|null
 */
function electro_child_landing_get_field_raw( $key ) {
	static $filter_depth = 0;

	if ( 's10_brand_cards' === $key ) {
		$from_options = electro_child_landing_read_brand_cards_from_options();
		if ( is_array( $from_options ) && ! electro_child_landing_brand_repeater_is_hollow( $from_options ) ) {
			return $from_options;
		}
	}

	$read = static function () use ( $key ) {
		if ( ! function_exists( 'get_field' ) ) {
			return null;
		}

		foreach ( electro_child_landing_option_post_ids() as $post_id ) {
			$value = get_field( $key, $post_id, false );
			if ( ! electro_child_landing_acf_value_is_empty( $value ) ) {
				return $value;
			}
		}

		return null;
	};

	if ( 0 === $filter_depth ) {
		remove_filter( 'acf/load_value', 'electro_child_landing_acf_load_default', 10 );
	}
	++$filter_depth;

	$value = $read();

	--$filter_depth;
	if ( 0 === $filter_depth ) {
		add_filter( 'acf/load_value', 'electro_child_landing_acf_load_default', 10, 3 );
	}

	if ( ! electro_child_landing_acf_value_is_empty( $value ) ) {
		return $value;
	}

	if ( preg_match( '/^(s\d{2})_cards$|^s10_brand_cards$/', $key ) ) {
		return null;
	}

	return electro_child_landing_get_stored_field( $key );
}

/**
 * Read landing option with code default fallback (never writes to DB).
 *
 * @param string $key     Field name.
 * @param mixed  $default Optional override default.
 * @return mixed
 */
function electro_child_landing_get( $key, $default = null ) {
	$defaults = electro_child_landing_defaults();

	if ( null === $default && isset( $defaults[ $key ] ) ) {
		$default = $defaults[ $key ];
	}

	$stored = electro_child_landing_get_field_raw( $key );
	if ( ! electro_child_landing_acf_value_is_empty( $stored ) ) {
		return $stored;
	}

	return null !== $default ? $default : '';
}

/**
 * @param string $url URL to check.
 * @return bool
 */
function electro_child_landing_is_local_url( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return false;
	}

	if ( '/' === $url[0] ) {
		return true;
	}

	$home   = wp_parse_url( home_url() );
	$parsed = wp_parse_url( $url );

	if ( empty( $parsed['host'] ) ) {
		return true;
	}

	return ! empty( $home['host'] ) && strtolower( $parsed['host'] ) === strtolower( $home['host'] );
}

/**
 * @param string $key         Image field name.
 * @param string $default_url Unused — kept for API compat. No external fallback URLs.
 * @return string Local attachment URL or empty.
 */
function electro_child_landing_image_url( $key, $default_url = '' ) {
	unset( $default_url );

	$value = function_exists( 'get_field' ) ? get_field( $key, electro_child_landing_option_id() ) : null;

	if ( is_array( $value ) && ! empty( $value['url'] ) ) {
		$url = (string) $value['url'];
		return electro_child_landing_is_local_url( $url ) ? $url : '';
	}

	if ( is_numeric( $value ) ) {
		$url = wp_get_attachment_image_url( (int) $value, 'full' );
		return $url ? $url : '';
	}

	if ( is_string( $value ) && '' !== trim( $value ) ) {
		$url = trim( $value );
		return electro_child_landing_is_local_url( $url ) ? $url : '';
	}

	return '';
}

/**
 * Allowed HTML for legal bullet WYSIWYG output.
 *
 * @param string $html Raw HTML.
 * @return string
 */
function electro_child_landing_kses_rich( $html ) {
	return electro_child_landing_kses_content( $html );
}

/**
 * Normalize common HTML typos before sanitizing (e.g. "< / br>" from plain textarea).
 *
 * @param string $html Raw HTML.
 * @return string
 */
function electro_child_landing_normalize_content_html( $html ) {
	$html = (string) $html;
	$html = preg_replace( '/<\s*\/\s*br\s*>/i', '<br>', $html );
	$html = preg_replace( '/<\s*br\s*\/?\s*>/i', '<br>', $html );

	return $html;
}

/**
 * Sanitize landing copy that may contain HTML from admin (text, textarea, wysiwyg).
 *
 * @param string $html Raw HTML.
 * @return string
 */
function electro_child_landing_kses_content( $html ) {
	return wp_kses_post( electro_child_landing_normalize_content_html( $html ) );
}

/**
 * Sanitize WYSIWYG content read raw (format_value off): convert line breaks to <p>/<br>.
 *
 * @param string $html Raw WYSIWYG value.
 * @return string
 */
function electro_child_landing_kses_wysiwyg( $html ) {
	return wp_kses_post( wpautop( electro_child_landing_normalize_content_html( $html ) ) );
}

/**
 * Convert a WYSIWYG/textarea bullet value into <li> rows — one orange dot per line.
 *
 * @param string $html Raw field value.
 * @return string HTML list items.
 */
function electro_child_landing_legal_bullet_items( $html ) {
	$html = electro_child_landing_normalize_content_html( (string) $html );

	// Treat paragraph/line breaks as line separators.
	$html = preg_replace( '#</p\s*>#i', "\n", $html );
	$html = preg_replace( '#<p\b[^>]*>#i', '', $html );
	$html = preg_replace( '#<br\s*/?>#i', "\n", $html );

	$lines = preg_split( '/\r\n|\r|\n/', $html );
	$items = '';

	foreach ( (array) $lines as $line ) {
		$line  = trim( $line );
		$plain = trim( str_replace( "\xc2\xa0", ' ', wp_strip_all_tags( $line ) ) );
		if ( '' === $plain ) {
			continue;
		}

		$items .= '<li class="vs-legal-bullets__item">'
			. '<span class="vs-legal-bullets__dot" aria-hidden="true"></span>'
			. '<div class="vs-legal-bullets__text">' . electro_child_landing_kses_content( $line ) . '</div>'
			. '</li>';
	}

	return $items;
}

/**
 * Allowed HTML for pricing card detail blocks.
 *
 * @param string $html Raw HTML.
 * @return string
 */
function electro_child_landing_kses_price_html( $html ) {
	return wp_kses(
		(string) $html,
		array(
			'details' => array( 'style' => true, 'class' => true, 'open' => true ),
			'summary' => array( 'style' => true, 'class' => true ),
			'div'     => array( 'style' => true, 'class' => true ),
			'p'       => array( 'style' => true, 'class' => true ),
			'span'    => array( 'style' => true, 'class' => true ),
			'b'       => array(),
			'strong'  => array(),
			'br'      => array(),
		)
	);
}

/**
 * Parse YouTube watch/share/embed URL to embed src.
 *
 * @param string $url Any YouTube URL or bare video ID.
 * @return string Embed URL or empty.
 */
function electro_child_landing_youtube_embed_url( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url || '#' === $url ) {
		return '';
	}

	if ( preg_match( '/^[a-zA-Z0-9_-]{11}$/', $url ) ) {
		return 'https://www.youtube.com/embed/' . $url;
	}

	$parsed = wp_parse_url( $url );
	if ( empty( $parsed['host'] ) ) {
		return '';
	}

	$host = strtolower( $parsed['host'] );
	$path = isset( $parsed['path'] ) ? $parsed['path'] : '';
	$query = array();
	if ( ! empty( $parsed['query'] ) ) {
		parse_str( $parsed['query'], $query );
	}

	$video_id = '';

	if ( false !== strpos( $host, 'youtu.be' ) ) {
		$video_id = trim( $path, '/' );
	} elseif ( false !== strpos( $host, 'youtube.com' ) || false !== strpos( $host, 'youtube-nocookie.com' ) ) {
		if ( 0 === strpos( $path, '/embed/' ) ) {
			$video_id = substr( $path, 7 );
		} elseif ( 0 === strpos( $path, '/shorts/' ) ) {
			$video_id = substr( $path, 8 );
		} elseif ( ! empty( $query['v'] ) ) {
			$video_id = (string) $query['v'];
		}
	}

	$video_id = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $video_id );

	if ( strlen( $video_id ) !== 11 ) {
		return '';
	}

	return 'https://www.youtube.com/embed/' . $video_id;
}

/**
 * Safe href for admin-entered links (# and relative paths allowed).
 *
 * @param string $url Link from ACF text field.
 * @return string
 */
function electro_child_landing_esc_href( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url || '#' === $url ) {
		return '#';
	}

	if ( '/' === $url[0] ) {
		return esc_url( home_url( $url ) );
	}

	$escaped = esc_url( $url );
	if ( '' !== $escaped ) {
		return $escaped;
	}

	return '#';
}

/**
 * Sync ACF JSON with theme folder (field groups in git).
 */
function electro_child_landing_acf_json_paths() {
	$path = get_stylesheet_directory() . '/acf-json';
	if ( ! is_dir( $path ) ) {
		wp_mkdir_p( $path );
	}
	return $path;
}

add_filter(
	'acf/settings/save_json',
	function () {
		return electro_child_landing_acf_json_paths();
	}
);

add_filter(
	'acf/settings/load_json',
	function ( $paths ) {
		$paths[] = electro_child_landing_acf_json_paths();
		return $paths;
	}
);

/**
 * Register options page + field groups.
 */
function electro_child_landing_acf_init() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title'      => __( 'Landing Solar', 'electro-child' ),
			'menu_title'      => __( 'Landing Solar', 'electro-child' ),
			'menu_slug'       => 'vs-landing-solar',
			'post_id'         => 'option',
			'capability'      => 'edit_posts',
			'redirect'        => false,
			'icon_url'        => 'dashicons-sun',
			'position'        => 58,
			'update_button'   => __( 'Lưu nội dung', 'electro-child' ),
			'updated_message' => __( 'Đã lưu nội dung landing.', 'electro-child' ),
		)
	);

	acf_add_local_field_group(
		array(
			'key'                   => 'group_vs_landing_solar',
			'title'                 => 'Landing Solar — Nội dung',
			'fields'                => electro_child_landing_acf_fields(),
			'location'              => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'vs-landing-solar',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		)
	);
}
add_action( 'acf/init', 'electro_child_landing_acf_init' );

/**
 * Attach default_value from landing-solar-defaults.php for admin placeholders.
 *
 * @param array<string, mixed> $field ACF field.
 * @return array<string, mixed>
 */
function electro_child_landing_acf_with_default( $field ) {
	if ( ! empty( $field['name'] ) && ! isset( $field['default_value'] ) ) {
		$default = electro_child_landing_default_for_field( $field['name'] );
		if ( '' !== $default ) {
			$field['default_value'] = $default;
		}
	}

	if ( ! empty( $field['type'] ) && in_array( $field['type'], array( 'text', 'textarea' ), true ) && empty( $field['instructions'] ) ) {
		$field['instructions'] = 'Hỗ trợ HTML (xuống dòng, in đậm, màu chữ, link).';
	}

	return $field;
}

/**
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_acf_fields() {
	$fields = array(
		array(
			'key'   => 'field_vs_tab_s01',
			'label' => 'Section 01 — Hero & Pháp lý',
			'name'  => '',
			'type'  => 'tab',
		),
		array(
			'key'           => 'field_s01_bg_image',
			'label'         => 'Ảnh nền hero',
			'name'          => 's01_bg_image',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'library'       => 'all',
			'instructions'  => 'Upload ảnh từ thư viện media của site. Để trống sẽ hiển thị nền màu xanh đậm.',
		),
		array(
			'key'   => 'field_s01_title',
			'label' => 'Tiêu đề H1',
			'name'  => 's01_title',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s01_intro_1',
			'label' => 'Mô tả ngắn — đoạn 1',
			'name'  => 's01_intro_1',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'   => 'field_s01_intro_2',
			'label' => 'Mô tả ngắn — đoạn 2',
			'name'  => 's01_intro_2',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'   => 'field_s01_legal_badge',
			'label' => 'Badge pháp lý',
			'name'  => 's01_legal_badge',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s01_legal_title',
			'label' => 'Tiêu đề khối pháp lý',
			'name'  => 's01_legal_title',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s01_legal_desc',
			'label' => 'Mô tả pháp lý',
			'name'  => 's01_legal_desc',
			'type'  => 'textarea',
			'rows'  => 2,
		),
		array(
			'key'          => 'field_s01_legal_bullet_1',
			'label'        => 'Gạch đầu dòng 1',
			'name'         => 's01_legal_bullet_1',
			'type'         => 'wysiwyg',
			'tabs'         => 'all',
			'toolbar'      => 'basic',
			'media_upload' => 0,
		),
		array(
			'key'          => 'field_s01_legal_bullet_2',
			'label'        => 'Gạch đầu dòng 2',
			'name'         => 's01_legal_bullet_2',
			'type'         => 'wysiwyg',
			'tabs'         => 'all',
			'toolbar'      => 'basic',
			'media_upload' => 0,
		),
		array(
			'key'          => 'field_s01_legal_actions_title',
			'label'        => 'Tiêu đề trên nút liên hệ',
			'name'         => 's01_legal_actions_title',
			'type'         => 'text',
			'instructions' => 'VD: Liên hệ báo giá ngay. Hiển thị phía trên nút Chat Zalo / Gọi ngay.',
		),
		array(
			'key'   => 'field_s01_zalo_label',
			'label' => 'Nút Zalo — nhãn',
			'name'  => 's01_zalo_label',
			'type'  => 'text',
		),
		array(
			'key'          => 'field_s01_zalo_url',
			'label'        => 'Nút Zalo — link',
			'name'         => 's01_zalo_url',
			'type'         => 'text',
			'instructions' => 'VD: https://zalo.me/0966856555',
		),
		array(
			'key'   => 'field_s01_call_label',
			'label' => 'Nút gọi — nhãn',
			'name'  => 's01_call_label',
			'type'  => 'text',
		),
		array(
			'key'          => 'field_s01_call_phone',
			'label'        => 'Nút gọi — số điện thoại',
			'name'         => 's01_call_phone',
			'type'         => 'text',
			'instructions' => 'Chỉ nhập số, VD: 0966856555',
		),
		array(
			'key'   => 'field_s01_video_label',
			'label' => 'Video — nhãn header',
			'name'  => 's01_video_label',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s01_video_badge',
			'label' => 'Video — badge YouTube',
			'name'  => 's01_video_badge',
			'type'  => 'text',
		),
		array(
			'key'          => 'field_s01_youtube_url',
			'label'        => 'Link YouTube',
			'name'         => 's01_youtube_url',
			'type'         => 'text',
			'instructions' => 'Link YouTube (watch, youtu.be, embed) hoặc # để ẩn video.',
		),
		array(
			'key'   => 'field_s01_video_title',
			'label' => 'Video — title iframe (SEO/a11y)',
			'name'  => 's01_video_title',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s01_legal_note',
			'label' => 'Ghi chú cuối khối pháp lý',
			'name'  => 's01_legal_note',
			'type'  => 'textarea',
			'rows'  => 2,
		),
	);

	$extra = electro_child_landing_acf_extra_fields();

	$extra_with_bg = array();
	foreach ( $extra as $field ) {
		if ( isset( $field['key'] ) && 'field_vs_tab_s09_text' === $field['key'] ) {
			continue;
		}

		if ( isset( $field['key'] ) && 'field_s09_badge_icon' === $field['key'] ) {
			$extra_with_bg[] = array(
				'key'   => 'field_vs_tab_s09',
				'label' => 'Section 09 — Nhà xưởng',
				'name'  => '',
				'type'  => 'tab',
			);
			$extra_with_bg[] = array(
				'key'           => 'field_s09_bg_image',
				'label'         => 'Ảnh nền section 09',
				'name'          => 's09_bg_image',
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'library'       => 'all',
				'instructions'  => 'Upload từ media site. Để trống sẽ dùng nền màu xanh đậm.',
			);
		}
		$extra_with_bg[] = $field;
	}

	$fields = array_merge(
		$fields,
		$extra_with_bg,
		array(
		array(
			'key'   => 'field_vs_tab_cta',
			'label' => 'CTA — Gọi ngay / Zalo',
			'name'  => '',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_cta_phone',
			'label' => 'Số điện thoại',
			'name'  => 'cta_phone',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_cta_phone_label',
			'label' => 'Nhãn nút gọi',
			'name'  => 'cta_phone_label',
			'type'  => 'text',
		),
		array(
			'key'          => 'field_cta_zalo_url',
			'label'        => 'Link Zalo',
			'name'         => 'cta_zalo_url',
			'type'         => 'text',
			'instructions' => 'URL Zalo hoặc # nếu chưa có link.',
		),
		array(
			'key'   => 'field_cta_zalo_label',
			'label' => 'Nhãn nút Zalo',
			'name'  => 'cta_zalo_label',
			'type'  => 'text',
		),
		)
	);

	return array_map( 'electro_child_landing_acf_with_default', $fields );
}

/**
 * Admin notice: remind that content is stored in DB per environment.
 */
function electro_child_landing_acf_admin_notice() {
	if ( ! function_exists( 'get_current_screen' ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || 'toplevel_page_vs-landing-solar' !== $screen->id ) {
		return;
	}

	echo '<div class="notice notice-info"><p>';
	echo esc_html__(
		'Các ô trống được điền sẵn nội dung mặc định để chỉnh trực tiếp. Sau khi Lưu, dữ liệu nằm trong database của môi trường này — deploy code không ghi đè nội dung đã lưu.',
		'electro-child'
	);
	echo '</p></div>';
}
add_action( 'admin_notices', 'electro_child_landing_acf_admin_notice' );
