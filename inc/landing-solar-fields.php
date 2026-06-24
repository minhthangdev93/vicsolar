<?php
/**
 * Landing Solar — data helpers (pricing, brands, repeaters).
 *
 * @package electro-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return array<string, array<string, mixed>>
 */
function electro_child_landing_pricing_defaults() {
	static $data = null;
	if ( null === $data ) {
		$data = require get_stylesheet_directory() . '/inc/landing-solar-pricing-defaults.php';
	}
	return $data;
}

/**
 * @return array<string, mixed>
 */
function electro_child_landing_brands_defaults() {
	static $data = null;
	if ( null === $data ) {
		$data = require get_stylesheet_directory() . '/inc/landing-solar-brands-defaults.php';
	}
	return $data;
}

/**
 * Merge flat default keys from pricing + brands into main defaults.
 *
 * @param array<string, mixed> $defaults Base defaults.
 * @return array<string, mixed>
 */
function electro_child_landing_merge_structured_defaults( $defaults ) {
	foreach ( electro_child_landing_pricing_defaults() as $prefix => $section ) {
		if ( ! empty( $section['pricing_badge'] ) ) {
			$defaults[ $prefix . '_pricing_badge' ] = $section['pricing_badge'];
		}
		if ( ! empty( $section['pricing_title'] ) ) {
			$defaults[ $prefix . '_pricing_title' ] = $section['pricing_title'];
		}
		if ( ! empty( $section['pricing_subtitle'] ) ) {
			$defaults[ $prefix . '_pricing_subtitle' ] = $section['pricing_subtitle'];
		}
		if ( ! empty( $section['tier_title'] ) ) {
			$defaults[ $prefix . '_tier_title' ] = $section['tier_title'];
		}
		if ( ! empty( $section['tier_subtitle'] ) ) {
			$defaults[ $prefix . '_tier_subtitle' ] = $section['tier_subtitle'];
		}
	}

	$brands = electro_child_landing_brands_defaults();
	$defaults['s10_title_line_1'] = $brands['title_line_1'];
	$defaults['s10_title_line_2'] = $brands['title_line_2'];
	$defaults['s10_subtitle']     = $brands['subtitle'];

	return $defaults;
}

/**
 * Canonical keys for a pricing card row (template + defaults).
 *
 * @return string[]
 */
function electro_child_landing_price_card_keys() {
	return array(
		'card_image',
		'power',
		'list_price',
		'brand_line',
		'variant',
		'storage_tag',
		'package_label',
		'package_price',
		'package_note',
		'config_html',
		'output_html',
		'btn_label',
		'btn_url',
	);
}

/**
 * ACF subfield name for a pricing card key (unique per section prefix).
 *
 * @param string $prefix s04–s08.
 * @param string $key    Canonical key.
 * @return string
 */
function electro_child_landing_price_card_acf_name( $prefix, $key ) {
	return $prefix . '_' . $key;
}

/**
 * ACF field keys for pricing card subfields (admin repeater rows use keys, not names).
 *
 * @param string $prefix s04–s08.
 * @return array<string, string> Canonical key => ACF field key.
 */
function electro_child_landing_price_card_field_keys( $prefix ) {
	$map = array();
	foreach ( electro_child_landing_price_card_keys() as $key ) {
		$map[ $key ] = 'field_' . $prefix . '_card_' . $key;
	}
	return $map;
}

/**
 * @param array<string, mixed> $card   Raw ACF row.
 * @param string               $prefix s04–s08.
 * @return array<string, mixed>
 */
function electro_child_landing_normalize_price_card( $card, $prefix ) {
	if ( ! is_array( $card ) ) {
		$card = array();
	}

	$normalized = array();
	$field_keys = electro_child_landing_price_card_field_keys( $prefix );

	foreach ( electro_child_landing_price_card_keys() as $key ) {
		$acf_name  = electro_child_landing_price_card_acf_name( $prefix, $key );
		$field_key = $field_keys[ $key ] ?? '';

		if ( $field_key && isset( $card[ $field_key ] ) && ! electro_child_landing_acf_value_is_empty( $card[ $field_key ] ) ) {
			$normalized[ $key ] = $card[ $field_key ];
		} elseif ( isset( $card[ $acf_name ] ) && ! electro_child_landing_acf_value_is_empty( $card[ $acf_name ] ) ) {
			$normalized[ $key ] = $card[ $acf_name ];
		} elseif ( isset( $card[ $key ] ) && ! electro_child_landing_acf_value_is_empty( $card[ $key ] ) ) {
			$normalized[ $key ] = $card[ $key ];
		} else {
			$normalized[ $key ] = '';
		}
	}

	return $normalized;
}

/**
 * @param array<string, mixed> $card Canonical card row.
 * @param string               $prefix s04–s08.
 * @return array<string, mixed> Row keyed by ACF field keys (admin UI).
 */
function electro_child_landing_prepare_price_card_for_acf_admin( $card, $prefix ) {
	$canonical = electro_child_landing_normalize_price_card( $card, $prefix );
	$row       = array();

	foreach ( electro_child_landing_price_card_field_keys( $prefix ) as $key => $field_key ) {
		$row[ $field_key ] = $canonical[ $key ] ?? '';
	}

	return $row;
}

/**
 * @param array<int, array<string, mixed>> $cards  Canonical cards.
 * @param string                           $prefix s04–s08.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_prepare_price_cards_for_acf_admin( $cards, $prefix ) {
	$prepared = array();
	foreach ( $cards as $card ) {
		$prepared[] = electro_child_landing_prepare_price_card_for_acf_admin( $card, $prefix );
	}
	return $prepared;
}

/**
 * @param array<string, mixed> $card Canonical card row.
 * @param string               $prefix s04–s08.
 * @return array<string, mixed> Row keyed by ACF subfield names (update_field / seed).
 */
function electro_child_landing_prepare_price_card_for_acf( $card, $prefix ) {
	$row = array();
	foreach ( electro_child_landing_price_card_keys() as $key ) {
		$acf_name        = electro_child_landing_price_card_acf_name( $prefix, $key );
		$row[ $acf_name ] = isset( $card[ $key ] ) ? $card[ $key ] : '';
	}
	return $row;
}

/**
 * @param array<int, array<string, mixed>> $cards  Canonical cards.
 * @param string                           $prefix s04–s08.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_prepare_price_cards_for_acf( $cards, $prefix ) {
	$prepared = array();
	foreach ( $cards as $card ) {
		$prepared[] = electro_child_landing_prepare_price_card_for_acf( $card, $prefix );
	}
	return $prepared;
}

/**
 * @param array<string, mixed> $card Canonical card.
 * @return bool
 */
function electro_child_landing_price_card_row_is_empty( $card ) {
	if ( ! is_array( $card ) ) {
		return true;
	}

	foreach ( array( 'power', 'list_price', 'package_label', 'package_price', 'package_note', 'btn_label' ) as $key ) {
		if ( ! electro_child_landing_acf_value_is_empty( $card[ $key ] ?? '' ) ) {
			return false;
		}
	}

	return true;
}

/**
 * @param mixed  $rows   Repeater rows from DB.
 * @param string $prefix Optional s04–s08 for pricing card normalization.
 * @return bool
 */
function electro_child_landing_price_repeater_is_hollow( $rows, $prefix = '' ) {
	if ( ! is_array( $rows ) || empty( $rows ) ) {
		return true;
	}

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}

		if ( $prefix ) {
			$row = electro_child_landing_normalize_price_card( $row, $prefix );
			if ( ! electro_child_landing_price_card_row_is_empty( $row ) ) {
				return false;
			}
			continue;
		}

		if ( ! electro_child_landing_brand_card_row_is_empty( $row ) ) {
			return false;
		}
	}

	return true;
}

/**
 * @param array<string, mixed> $card Brand card row.
 * @return bool
 */
function electro_child_landing_brand_card_row_is_empty( $card ) {
	if ( ! is_array( $card ) ) {
		return true;
	}

	$card = electro_child_landing_normalize_brand_card( $card );

	foreach ( array( 'title', 'bullet_1', 'bullet_2', 'bullet_3', 'btn_label', 'btn_url' ) as $key ) {
		if ( ! electro_child_landing_acf_value_is_empty( $card[ $key ] ?? '' ) ) {
			return false;
		}
	}

	return true;
}

/**
 * @param mixed $rows Repeater rows from DB.
 * @return bool
 */
function electro_child_landing_brand_repeater_is_hollow( $rows ) {
	if ( ! is_array( $rows ) || empty( $rows ) ) {
		return true;
	}

	foreach ( $rows as $row ) {
		if ( ! electro_child_landing_brand_card_row_is_empty( $row ) ) {
			return false;
		}
	}

	return true;
}

/**
 * ACF field keys for brand card subfields (admin repeater rows use keys, not names).
 *
 * @return array<string, string> Canonical key => ACF field key.
 */
function electro_child_landing_brand_card_field_keys() {
	return array(
		'card_image' => 'field_s10_brand_card_image',
		'title'      => 'field_s10_brand_card_title',
		'bullet_1'   => 'field_s10_brand_card_bullet_1',
		'bullet_2'   => 'field_s10_brand_card_bullet_2',
		'bullet_3'   => 'field_s10_brand_card_bullet_3',
		'btn_label'  => 'field_s10_brand_card_btn_label',
		'btn_url'    => 'field_s10_brand_card_btn_url',
		'row_group'  => 'field_s10_brand_card_row',
	);
}

/**
 * Canonical keys for a brand card row.
 *
 * @return string[]
 */
function electro_child_landing_brand_card_keys() {
	return array(
		'card_image',
		'title',
		'bullet_1',
		'bullet_2',
		'bullet_3',
		'btn_label',
		'btn_url',
		'row_group',
	);
}

/**
 * @param array<string, mixed> $card Raw ACF row.
 * @return array<string, mixed>
 */
function electro_child_landing_normalize_brand_card( $card ) {
	if ( ! is_array( $card ) ) {
		$card = array();
	}

	$normalized = array();
	$field_keys = electro_child_landing_brand_card_field_keys();

	foreach ( electro_child_landing_brand_card_keys() as $key ) {
		$prefixed   = 's10_' . $key;
		$field_key  = $field_keys[ $key ] ?? '';

		if ( $field_key && isset( $card[ $field_key ] ) && ! electro_child_landing_acf_value_is_empty( $card[ $field_key ] ) ) {
			$normalized[ $key ] = $card[ $field_key ];
		} elseif ( isset( $card[ $prefixed ] ) && ! electro_child_landing_acf_value_is_empty( $card[ $prefixed ] ) ) {
			$normalized[ $key ] = $card[ $prefixed ];
		} elseif ( isset( $card[ $key ] ) && ! electro_child_landing_acf_value_is_empty( $card[ $key ] ) ) {
			$normalized[ $key ] = $card[ $key ];
		} else {
			$normalized[ $key ] = '';
		}
	}

	return $normalized;
}

/**
 * @param array<string, mixed> $card Canonical card row.
 * @return array<string, mixed> Row keyed by ACF field keys (admin UI).
 */
function electro_child_landing_prepare_brand_card_for_acf_admin( $card ) {
	$canonical = electro_child_landing_normalize_brand_card( $card );
	$row       = array();

	foreach ( electro_child_landing_brand_card_field_keys() as $key => $field_key ) {
		$row[ $field_key ] = $canonical[ $key ] ?? '';
	}

	return $row;
}

/**
 * @param array<int, array<string, mixed>> $cards Canonical cards.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_prepare_brand_cards_for_acf_admin( $cards ) {
	$prepared = array();
	$row_key  = electro_child_landing_brand_card_field_keys()['row_group'];

	foreach ( $cards as $index => $card ) {
		$row = electro_child_landing_prepare_brand_card_for_acf_admin( $card );
		if ( electro_child_landing_acf_value_is_empty( $row[ $row_key ] ?? '' ) ) {
			$row[ $row_key ] = $index < 5 ? 'panels' : 'storage';
		}
		$prepared[] = $row;
	}

	return $prepared;
}

/**
 * @param array<string, mixed> $card Canonical card row.
 * @return array<string, mixed> Row keyed by ACF subfield names (update_field / seed).
 */
function electro_child_landing_prepare_brand_card_for_acf( $card ) {
	$row = array();
	foreach ( electro_child_landing_brand_card_keys() as $key ) {
		$row[ 's10_' . $key ] = isset( $card[ $key ] ) ? $card[ $key ] : '';
	}
	return $row;
}

/**
 * @param array<int, array<string, mixed>> $cards Canonical cards.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_prepare_brand_cards_for_acf( $cards ) {
	$prepared = array();
	foreach ( $cards as $index => $card ) {
		$row = electro_child_landing_prepare_brand_card_for_acf( $card );
		if ( electro_child_landing_acf_value_is_empty( $row['s10_row_group'] ?? '' ) ) {
			$row['s10_row_group'] = $index < 5 ? 'panels' : 'storage';
		}
		$prepared[] = $row;
	}
	return $prepared;
}

/**
 * Option key prefixes used by ACF (current + legacy).
 *
 * @return string[]
 */
function electro_child_landing_option_prefixes() {
	return array( 'options_', 'vs-landing-solar_', 'option_' );
}

/**
 * Read brand repeater rows directly from wp_options (bypasses ACF field assembly).
 *
 * @return array<int, array<string, mixed>>|null
 */
function electro_child_landing_read_brand_cards_from_options() {
	$repeater = 's10_brand_cards';
	$best     = null;

	foreach ( electro_child_landing_option_prefixes() as $opt_prefix ) {
		$count = get_option( $opt_prefix . $repeater, false );
		if ( false === $count || '' === $count || (int) $count <= 0 ) {
			continue;
		}

		$row_count = (int) $count;
		$rows      = array();

		for ( $i = 0; $i < $row_count; $i++ ) {
			$row = array();
			foreach ( electro_child_landing_brand_card_keys() as $key ) {
				$acf_name   = 's10_' . $key;
				$option_key = $opt_prefix . $repeater . '_' . $i . '_' . $acf_name;
				$value      = get_option( $option_key, null );

				if ( null === $value || false === $value ) {
					$legacy_key = $opt_prefix . $repeater . '_' . $i . '_' . $key;
					$value      = get_option( $legacy_key, null );
				}

				if ( null !== $value && false !== $value && ! electro_child_landing_acf_value_is_empty( $value ) ) {
					$row[ $acf_name ] = $value;
				}
			}
			$rows[] = $row;
		}

		if ( ! electro_child_landing_brand_repeater_is_hollow( $rows ) ) {
			return $rows;
		}

		$best = $rows;
	}

	return $best;
}

/**
 * @param array<int, array<string, mixed>> $rows     Stored rows.
 * @param array<int, array<string, mixed>> $defaults Default cards.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_merge_brand_cards( $rows, $defaults ) {
	$merged = array();
	$count  = max( count( $rows ), count( $defaults ) );

	for ( $i = 0; $i < $count; $i++ ) {
		$row     = isset( $rows[ $i ] ) && is_array( $rows[ $i ] ) ? electro_child_landing_normalize_brand_card( $rows[ $i ] ) : array();
		$default = isset( $defaults[ $i ] ) && is_array( $defaults[ $i ] ) ? $defaults[ $i ] : array();

		if ( electro_child_landing_brand_card_row_is_empty( $row ) ) {
			$merged[] = $default;
			continue;
		}

		if ( electro_child_landing_acf_value_is_empty( $row['row_group'] ?? '' ) ) {
			$row['row_group'] = $i < 5 ? 'panels' : 'storage';
		}

		$combined = $default;
		foreach ( $row as $key => $value ) {
			if ( ! electro_child_landing_acf_value_is_empty( $value ) ) {
				$combined[ $key ] = $value;
			}
		}
		$merged[] = $combined;
	}

	return electro_child_landing_normalize_brand_cards( $merged );
}

/**
 * @param array<int, array<string, mixed>> $rows     Stored rows.
 * @param array<int, array<string, mixed>> $defaults Default cards.
 * @param string                           $prefix   s04–s08.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_merge_price_cards( $rows, $defaults, $prefix ) {
	$merged = array();
	$count  = max( count( $rows ), count( $defaults ) );

	for ( $i = 0; $i < $count; $i++ ) {
		$row     = isset( $rows[ $i ] ) && is_array( $rows[ $i ] ) ? electro_child_landing_normalize_price_card( $rows[ $i ], $prefix ) : array();
		$default = isset( $defaults[ $i ] ) && is_array( $defaults[ $i ] ) ? $defaults[ $i ] : array();

		if ( electro_child_landing_price_card_row_is_empty( $row ) ) {
			$merged[] = $default;
			continue;
		}

		$combined = $default;
		foreach ( $row as $key => $value ) {
			if ( ! electro_child_landing_acf_value_is_empty( $value ) ) {
				$combined[ $key ] = $value;
			}
		}
		$merged[] = $combined;
	}

	return $merged;
}

/**
 * Read pricing repeater rows directly from wp_options.
 *
 * @param string $prefix s04–s08.
 * @return array<int, array<string, mixed>>|null
 */
function electro_child_landing_read_price_cards_from_options( $prefix ) {
	$repeater = $prefix . '_cards';
	$best     = null;

	foreach ( electro_child_landing_option_prefixes() as $opt_prefix ) {
		$count = get_option( $opt_prefix . $repeater, false );
		if ( false === $count || '' === $count || (int) $count <= 0 ) {
			continue;
		}

		$row_count = (int) $count;
		$rows      = array();

		for ( $i = 0; $i < $row_count; $i++ ) {
			$row = array();
			foreach ( electro_child_landing_price_card_keys() as $key ) {
				$acf_name   = electro_child_landing_price_card_acf_name( $prefix, $key );
				$option_key = $opt_prefix . $repeater . '_' . $i . '_' . $acf_name;
				$value      = get_option( $option_key, null );

				if ( null === $value || false === $value ) {
					$legacy_key = $opt_prefix . $repeater . '_' . $i . '_' . $key;
					$value      = get_option( $legacy_key, null );
				}

				if ( null !== $value && false !== $value && ! electro_child_landing_acf_value_is_empty( $value ) ) {
					$row[ $acf_name ] = $value;
				}
			}
			$rows[] = $row;
		}

		if ( ! electro_child_landing_price_repeater_is_hollow( $rows, $prefix ) ) {
			return $rows;
		}

		$best = $rows;
	}

	return $best;
}

/**
 * Pricing repeater rows for ACF admin (merge stored + code defaults).
 *
 * @param string                             $prefix   s04–s08.
 * @param array<int, array<string, mixed>> $acf_rows Optional rows from ACF load (field keys).
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_price_cards_for_acf_admin( $prefix, $acf_rows = array() ) {
	$pricing  = electro_child_landing_pricing_defaults();
	$defaults = isset( $pricing[ $prefix ]['cards'] ) ? $pricing[ $prefix ]['cards'] : array();

	$rows = array();
	if ( is_array( $acf_rows ) && ! empty( $acf_rows ) && ! electro_child_landing_price_repeater_is_hollow( $acf_rows, $prefix ) ) {
		$rows = $acf_rows;
	} else {
		$rows = electro_child_landing_read_price_cards_from_options( $prefix );
		if ( ! is_array( $rows ) || electro_child_landing_price_repeater_is_hollow( $rows, $prefix ) ) {
			$rows = electro_child_landing_get_field_raw( $prefix . '_cards' );
		}
	}

	$merged = electro_child_landing_merge_price_cards( is_array( $rows ) ? $rows : array(), $defaults, $prefix );
	return electro_child_landing_prepare_price_cards_for_acf_admin( $merged, $prefix );
}

/**
 * @param string $prefix s04–s08.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_get_price_cards( $prefix ) {
	$key      = $prefix . '_cards';
	$defaults = isset( electro_child_landing_pricing_defaults()[ $prefix ]['cards'] )
		? electro_child_landing_pricing_defaults()[ $prefix ]['cards']
		: array();

	$rows = electro_child_landing_read_price_cards_from_options( $prefix );
	if ( ! is_array( $rows ) || electro_child_landing_price_repeater_is_hollow( $rows, $prefix ) ) {
		$rows = electro_child_landing_get_field_raw( $key );
	}

	if ( electro_child_landing_price_repeater_is_hollow( $rows, $prefix ) ) {
		return $defaults;
	}

	return electro_child_landing_merge_price_cards( is_array( $rows ) ? $rows : array(), $defaults, $prefix );
}

/**
 * Brand repeater rows for ACF admin (merge stored + code defaults).
 *
 * @param array<int, array<string, mixed>> $acf_rows Optional rows from ACF load (field keys).
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_brand_cards_for_acf_admin( $acf_rows = array() ) {
	$defaults = electro_child_landing_brands_defaults();
	$defaults = isset( $defaults['cards'] ) ? electro_child_landing_normalize_brand_cards( $defaults['cards'] ) : array();

	$rows = array();
	if ( is_array( $acf_rows ) && ! empty( $acf_rows ) && ! electro_child_landing_brand_repeater_is_hollow( $acf_rows ) ) {
		$rows = $acf_rows;
	} else {
		$rows = electro_child_landing_read_brand_cards_from_options();
		if ( ! is_array( $rows ) || electro_child_landing_brand_repeater_is_hollow( $rows ) ) {
			$rows = electro_child_landing_get_field_raw( 's10_brand_cards' );
		}
	}

	$merged = electro_child_landing_merge_brand_cards( is_array( $rows ) ? $rows : array(), $defaults );
	return electro_child_landing_prepare_brand_cards_for_acf_admin( $merged );
}

/**
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_get_brand_cards() {
	$defaults = electro_child_landing_brands_defaults();
	$defaults = isset( $defaults['cards'] ) ? electro_child_landing_normalize_brand_cards( $defaults['cards'] ) : array();

	$rows = electro_child_landing_read_brand_cards_from_options();
	if ( ! is_array( $rows ) || electro_child_landing_brand_repeater_is_hollow( $rows ) ) {
		$rows = electro_child_landing_get_field_raw( 's10_brand_cards' );
	}

	if ( electro_child_landing_brand_repeater_is_hollow( $rows ) ) {
		return $defaults;
	}

	return electro_child_landing_merge_brand_cards( is_array( $rows ) ? $rows : array(), $defaults );
}

/**
 * @param array<int, array<string, mixed>> $cards Brand cards.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_normalize_brand_cards( $cards ) {
	foreach ( $cards as $index => $card ) {
		$cards[ $index ] = electro_child_landing_normalize_brand_card( is_array( $card ) ? $card : array() );
		if ( empty( $cards[ $index ]['row_group'] ) ) {
			$cards[ $index ]['row_group'] = $index < 5 ? 'panels' : 'storage';
		}
	}

	return $cards;
}

/**
 * Placeholder image when no product image uploaded.
 *
 * @return string
 */
function electro_child_landing_placeholder_image_url() {
	return 'https://placehold.co/300x300/png?text=Placeholder';
}

/**
 * @param array<string, mixed> $card Card row.
 * @return string
 */
function electro_child_landing_price_card_image_url( $card ) {
	if ( empty( $card['card_image'] ) ) {
		return '';
	}

	return electro_child_landing_image_url_from_value( $card['card_image'] );
}

/**
 * @param array<string, mixed> $card Brand card row.
 * @return string
 */
function electro_child_landing_brand_card_image_url( $card ) {
	$url = electro_child_landing_price_card_image_url( $card );
	return $url ? $url : electro_child_landing_placeholder_image_url();
}

/**
 * @param mixed $value Image field value.
 * @return string
 */
function electro_child_landing_image_url_from_value( $value ) {
	if ( is_array( $value ) && ! empty( $value['url'] ) ) {
		$url = (string) $value['url'];
		return electro_child_landing_is_local_url( $url ) ? $url : '';
	}

	if ( is_numeric( $value ) ) {
		$url = wp_get_attachment_image_url( (int) $value, 'medium' );
		return $url ? $url : '';
	}

	return '';
}
