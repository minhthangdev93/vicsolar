<?php
/**
 * Landing Solar — long article helpers (section 10).
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return array<string, mixed>
 */
function electro_child_landing_article_defaults() {
	static $defaults = null;
	if ( null === $defaults ) {
		$defaults = require get_stylesheet_directory() . '/inc/landing-solar-article-defaults.php';
	}
	return $defaults;
}

/**
 * Scalar article field with code default fallback.
 *
 * @param string $key Field name.
 * @return string
 */
function electro_child_landing_article_get( $key ) {
	$defaults = electro_child_landing_article_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	$stored   = electro_child_landing_get_field_raw( $key );
	if ( ! electro_child_landing_acf_value_is_empty( $stored ) ) {
		return is_string( $stored ) ? $stored : (string) $default;
	}
	return is_string( $default ) ? $default : '';
}

/**
 * @param string $body Newline-separated list.
 * @return string[]
 */
function electro_child_landing_article_lines( $body ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $body );
	$lines = array_map( 'trim', is_array( $lines ) ? $lines : array() );
	return array_values( array_filter( $lines, static function ( $line ) {
		return '' !== $line;
	} ) );
}

/**
 * Repeater config: canonical keys + ACF subfield names + field keys.
 *
 * @param string $repeater Repeater field name.
 * @return array{keys: string[], names: array<string, string>, field_keys: array<string, string>}|null
 */
function electro_child_landing_article_repeater_config( $repeater ) {
	$map = array(
		's10_article_b4_cards' => array(
			'cmp_style',
			'cmp_tag',
			'cmp_name',
			'cmp_tagline',
			'cmp_spec_1_label',
			'cmp_spec_1_value',
			'cmp_spec_2_label',
			'cmp_spec_2_value',
			'cmp_spec_3_label',
			'cmp_spec_3_value',
			'cmp_spec_4_label',
			'cmp_spec_4_value',
			'cmp_spec_5_label',
			'cmp_spec_5_value',
			'cmp_pros_title',
			'cmp_pros_body',
		),
		's10_article_b5_features' => array(
			'feat_tag_style',
			'feat_tag',
			'feat_title',
			'feat_desc',
			'feat_spec',
		),
		's10_article_b6_cards' => array(
			'cmp_style',
			'cmp_tag',
			'cmp_name',
			'cmp_tagline',
			'cmp_spec_1_label',
			'cmp_spec_1_value',
			'cmp_spec_2_label',
			'cmp_spec_2_value',
			'cmp_spec_3_label',
			'cmp_spec_3_value',
			'cmp_spec_4_label',
			'cmp_spec_4_value',
			'cmp_spec_5_label',
			'cmp_spec_5_value',
			'cmp_pros_title',
			'cmp_pros_body',
		),
		's10_article_b6_spec_rows' => array(
			'spec_col1_label',
			'spec_col1_value',
			'spec_col2_label',
			'spec_col2_value',
		),
		's10_article_b7_cards' => array(
			'cmp_style',
			'cmp_tag',
			'cmp_name',
			'cmp_tagline',
			'cmp_spec_1_label',
			'cmp_spec_1_value',
			'cmp_spec_2_label',
			'cmp_spec_2_value',
			'cmp_spec_3_label',
			'cmp_spec_3_value',
			'cmp_spec_4_label',
			'cmp_spec_4_value',
			'cmp_spec_5_label',
			'cmp_spec_5_value',
			'cmp_pros_title',
			'cmp_pros_body',
		),
		's10_article_choose_criteria' => array(
			'choose_tag',
			'choose_title',
			'choose_body',
		),
		's10_article_choose_table_rows' => array(
			'choose_criterion',
			'choose_value',
		),
	);

	if ( ! isset( $map[ $repeater ] ) ) {
		return null;
	}

	$keys        = $map[ $repeater ];
	$names       = array();
	$field_keys  = array();
	foreach ( $keys as $key ) {
		$acf_name              = 's10a_' . $key;
		$names[ $key ]         = $acf_name;
		$field_keys[ $key ]    = 'field_s10a_' . $key;
	}

	return array(
		'keys'        => $keys,
		'names'       => $names,
		'field_keys'  => $field_keys,
	);
}

/**
 * Map legacy default row keys to canonical article repeater keys.
 *
 * @param string               $repeater Repeater name.
 * @param array<string, mixed> $row      Default row.
 * @return array<string, mixed>
 */
function electro_child_landing_article_map_default_row( $repeater, $row ) {
	if ( ! is_array( $row ) ) {
		return array();
	}

	$legacy_maps = array(
		's10_article_b4_cards' => array(
			'style' => 'cmp_style', 'tag' => 'cmp_tag', 'name' => 'cmp_name', 'tagline' => 'cmp_tagline',
			'spec_1_label' => 'cmp_spec_1_label', 'spec_1_value' => 'cmp_spec_1_value',
			'spec_2_label' => 'cmp_spec_2_label', 'spec_2_value' => 'cmp_spec_2_value',
			'spec_3_label' => 'cmp_spec_3_label', 'spec_3_value' => 'cmp_spec_3_value',
			'spec_4_label' => 'cmp_spec_4_label', 'spec_4_value' => 'cmp_spec_4_value',
			'spec_5_label' => 'cmp_spec_5_label', 'spec_5_value' => 'cmp_spec_5_value',
			'pros_title' => 'cmp_pros_title', 'pros_body' => 'cmp_pros_body',
		),
		's10_article_b5_features' => array(
			'tag_style' => 'feat_tag_style', 'tag' => 'feat_tag', 'title' => 'feat_title',
			'desc' => 'feat_desc', 'spec' => 'feat_spec',
		),
		's10_article_b6_cards' => array(
			'style' => 'cmp_style', 'tag' => 'cmp_tag', 'name' => 'cmp_name', 'tagline' => 'cmp_tagline',
			'spec_1_label' => 'cmp_spec_1_label', 'spec_1_value' => 'cmp_spec_1_value',
			'spec_2_label' => 'cmp_spec_2_label', 'spec_2_value' => 'cmp_spec_2_value',
			'spec_3_label' => 'cmp_spec_3_label', 'spec_3_value' => 'cmp_spec_3_value',
			'spec_4_label' => 'cmp_spec_4_label', 'spec_4_value' => 'cmp_spec_4_value',
			'spec_5_label' => 'cmp_spec_5_label', 'spec_5_value' => 'cmp_spec_5_value',
			'pros_title' => 'cmp_pros_title', 'pros_body' => 'cmp_pros_body',
		),
		's10_article_b6_spec_rows' => array(
			'col1_label' => 'spec_col1_label', 'col1_value' => 'spec_col1_value',
			'col2_label' => 'spec_col2_label', 'col2_value' => 'spec_col2_value',
		),
		's10_article_b7_cards' => array(
			'style' => 'cmp_style', 'tag' => 'cmp_tag', 'name' => 'cmp_name', 'tagline' => 'cmp_tagline',
			'spec_1_label' => 'cmp_spec_1_label', 'spec_1_value' => 'cmp_spec_1_value',
			'spec_2_label' => 'cmp_spec_2_label', 'spec_2_value' => 'cmp_spec_2_value',
			'spec_3_label' => 'cmp_spec_3_label', 'spec_3_value' => 'cmp_spec_3_value',
			'spec_4_label' => 'cmp_spec_4_label', 'spec_4_value' => 'cmp_spec_4_value',
			'spec_5_label' => 'cmp_spec_5_label', 'spec_5_value' => 'cmp_spec_5_value',
			'pros_title' => 'cmp_pros_title', 'pros_body' => 'cmp_pros_body',
		),
		's10_article_choose_criteria' => array(
			'tag' => 'choose_tag', 'title' => 'choose_title', 'body' => 'choose_body',
		),
		's10_article_choose_table_rows' => array(
			'criterion' => 'choose_criterion', 'value' => 'choose_value',
		),
	);

	$config = electro_child_landing_article_repeater_config( $repeater );
	if ( ! $config ) {
		return $row;
	}

	$normalized = array();
	foreach ( $config['keys'] as $key ) {
		$normalized[ $key ] = '';
	}

	$legacy = $legacy_maps[ $repeater ] ?? array();
	foreach ( $legacy as $from => $to ) {
		if ( isset( $row[ $from ] ) && ! electro_child_landing_acf_value_is_empty( $row[ $from ] ) ) {
			$normalized[ $to ] = $row[ $from ];
		}
	}

	foreach ( $config['keys'] as $key ) {
		$acf_name = $config['names'][ $key ];
		if ( isset( $row[ $acf_name ] ) && ! electro_child_landing_acf_value_is_empty( $row[ $acf_name ] ) ) {
			$normalized[ $key ] = $row[ $acf_name ];
		} elseif ( isset( $row[ $key ] ) && ! electro_child_landing_acf_value_is_empty( $row[ $key ] ) ) {
			$normalized[ $key ] = $row[ $key ];
		} elseif ( isset( $config['field_keys'][ $key ] ) && isset( $row[ $config['field_keys'][ $key ] ] ) ) {
			$normalized[ $key ] = $row[ $config['field_keys'][ $key ] ];
		}
	}

	return $normalized;
}

/**
 * @param string               $repeater Repeater name.
 * @param array<string, mixed> $row      Raw row.
 * @return array<string, mixed>
 */
function electro_child_landing_article_normalize_row( $repeater, $row ) {
	return electro_child_landing_article_map_default_row( $repeater, is_array( $row ) ? $row : array() );
}

/**
 * @param string               $repeater Repeater name.
 * @param array<string, mixed> $row      Canonical row.
 * @return bool
 */
function electro_child_landing_article_row_is_empty( $repeater, $row ) {
	$row = electro_child_landing_article_normalize_row( $repeater, $row );
	foreach ( $row as $value ) {
		if ( ! electro_child_landing_acf_value_is_empty( $value ) ) {
			return false;
		}
	}
	return true;
}

/**
 * @param mixed  $rows     Repeater rows.
 * @param string $repeater Repeater name.
 * @return bool
 */
function electro_child_landing_article_repeater_is_hollow( $rows, $repeater ) {
	if ( ! is_array( $rows ) || empty( $rows ) ) {
		return true;
	}
	foreach ( $rows as $row ) {
		if ( ! electro_child_landing_article_row_is_empty( $repeater, $row ) ) {
			return false;
		}
	}
	return true;
}

/**
 * @param array<int, array<string, mixed>> $rows     Stored rows.
 * @param array<int, array<string, mixed>> $defaults Default rows.
 * @param string                           $repeater Repeater name.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_article_merge_rows( $rows, $defaults, $repeater ) {
	$merged = array();
	$count  = max( count( $rows ), count( $defaults ) );
	for ( $i = 0; $i < $count; $i++ ) {
		$row     = isset( $rows[ $i ] ) ? electro_child_landing_article_normalize_row( $repeater, $rows[ $i ] ) : array();
		$default = isset( $defaults[ $i ] ) ? electro_child_landing_article_map_default_row( $repeater, $defaults[ $i ] ) : array();
		if ( electro_child_landing_article_row_is_empty( $repeater, $row ) ) {
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
 * @param array<int, array<string, mixed>> $rows     Canonical rows.
 * @param string                           $repeater Repeater name.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_article_prepare_rows_for_acf( $rows, $repeater ) {
	$config = electro_child_landing_article_repeater_config( $repeater );
	if ( ! $config ) {
		return $rows;
	}
	$prepared = array();
	foreach ( $rows as $row ) {
		$canonical = electro_child_landing_article_normalize_row( $repeater, $row );
		$acf_row   = array();
		foreach ( $config['keys'] as $key ) {
			$acf_row[ $config['names'][ $key ] ] = $canonical[ $key ] ?? '';
		}
		$prepared[] = $acf_row;
	}
	return $prepared;
}

/**
 * @param array<int, array<string, mixed>> $rows     Canonical rows.
 * @param string                           $repeater Repeater name.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_article_prepare_rows_for_acf_admin( $rows, $repeater ) {
	$config = electro_child_landing_article_repeater_config( $repeater );
	if ( ! $config ) {
		return $rows;
	}
	$prepared = array();
	foreach ( $rows as $row ) {
		$canonical = electro_child_landing_article_normalize_row( $repeater, $row );
		$acf_row   = array();
		foreach ( $config['keys'] as $key ) {
			$acf_row[ $config['field_keys'][ $key ] ] = $canonical[ $key ] ?? '';
		}
		$prepared[] = $acf_row;
	}
	return $prepared;
}

/**
 * @param string $repeater Repeater field name.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_article_get_repeater( $repeater ) {
	$defaults = electro_child_landing_article_defaults();
	$default_rows = isset( $defaults[ $repeater ] ) && is_array( $defaults[ $repeater ] ) ? $defaults[ $repeater ] : array();
	$default_rows = array_map(
		static function ( $row ) use ( $repeater ) {
			return electro_child_landing_article_map_default_row( $repeater, is_array( $row ) ? $row : array() );
		},
		$default_rows
	);

	$rows = electro_child_landing_get_field_raw( $repeater );
	if ( electro_child_landing_article_repeater_is_hollow( $rows, $repeater ) ) {
		return $default_rows;
	}

	return electro_child_landing_article_merge_rows( is_array( $rows ) ? $rows : array(), $default_rows, $repeater );
}

/**
 * @param string                             $repeater Repeater field name.
 * @param array<int, array<string, mixed>> $acf_rows Optional ACF-loaded rows.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_article_repeater_for_acf_admin( $repeater, $acf_rows = array() ) {
	$defaults = electro_child_landing_article_defaults();
	$default_rows = isset( $defaults[ $repeater ] ) && is_array( $defaults[ $repeater ] ) ? $defaults[ $repeater ] : array();
	$default_rows = array_map(
		static function ( $row ) use ( $repeater ) {
			return electro_child_landing_article_map_default_row( $repeater, is_array( $row ) ? $row : array() );
		},
		$default_rows
	);

	$rows = array();
	if ( is_array( $acf_rows ) && ! empty( $acf_rows ) && ! electro_child_landing_article_repeater_is_hollow( $acf_rows, $repeater ) ) {
		$rows = $acf_rows;
	} else {
		$rows = electro_child_landing_get_field_raw( $repeater );
	}

	$merged = electro_child_landing_article_merge_rows( is_array( $rows ) ? $rows : array(), $default_rows, $repeater );
	return electro_child_landing_article_prepare_rows_for_acf_admin( $merged, $repeater );
}

/**
 * Article sections used for auto quick-nav (label = admin H2, href = fixed anchor).
 *
 * @return array<int, array{heading_key: string, anchor: string}>
 */
function electro_child_landing_article_nav_sections() {
	return array(
		array(
			'heading_key' => 's10_article_b4_heading',
			'anchor'      => 'h2-4',
		),
		array(
			'heading_key' => 's10_article_b5_heading',
			'anchor'      => 'h2-5',
		),
		array(
			'heading_key' => 's10_article_b6_heading',
			'anchor'      => 'h2-6',
		),
		array(
			'heading_key' => 's10_article_b7_heading',
			'anchor'      => 'h2-7',
		),
		array(
			'heading_key' => 's10_article_b8_heading',
			'anchor'      => 'h2-8',
		),
		array(
			'heading_key' => 's10_article_choose_heading',
			'anchor'      => 'h2-choose',
		),
	);
}

/**
 * Quick-nav pills derived from block headings (no separate ACF repeater).
 *
 * @return array<int, array{label: string, href: string}>
 */
function electro_child_landing_article_nav_links() {
	$items = array();

	foreach ( electro_child_landing_article_nav_sections() as $section ) {
		$label = trim( electro_child_landing_article_get( $section['heading_key'] ) );
		if ( '' === $label ) {
			continue;
		}

		$items[] = array(
			'label' => $label,
			'href'  => '#' . $section['anchor'],
		);
	}

	return $items;
}

/**
 * Flat scalar keys for article defaults seeding.
 *
 * @return string[]
 */
function electro_child_landing_article_scalar_keys() {
	$keys = array();
	foreach ( electro_child_landing_article_defaults() as $key => $value ) {
		if ( ! is_array( $value ) ) {
			$keys[] = $key;
		}
	}
	return $keys;
}

/**
 * Repeater field names for article.
 *
 * @return string[]
 */
function electro_child_landing_article_repeater_keys() {
	return array(
		's10_article_b4_cards',
		's10_article_b5_features',
		's10_article_b6_cards',
		's10_article_b6_spec_rows',
		's10_article_b7_cards',
		's10_article_choose_criteria',
		's10_article_choose_table_rows',
	);
}

/**
 * Convert canonical cmp row to template-friendly keys.
 *
 * @param array<string, mixed> $row Canonical cmp row.
 * @return array<string, mixed>
 */
function electro_child_landing_article_cmp_row_for_template( $row ) {
	$row = is_array( $row ) ? $row : array();
	return array(
		'style'    => (string) ( $row['cmp_style'] ?? 'a' ),
		'tag'      => (string) ( $row['cmp_tag'] ?? '' ),
		'name'     => (string) ( $row['cmp_name'] ?? '' ),
		'tagline'  => (string) ( $row['cmp_tagline'] ?? '' ),
		'specs'    => array(
			array( 'label' => (string) ( $row['cmp_spec_1_label'] ?? '' ), 'value' => (string) ( $row['cmp_spec_1_value'] ?? '' ) ),
			array( 'label' => (string) ( $row['cmp_spec_2_label'] ?? '' ), 'value' => (string) ( $row['cmp_spec_2_value'] ?? '' ) ),
			array( 'label' => (string) ( $row['cmp_spec_3_label'] ?? '' ), 'value' => (string) ( $row['cmp_spec_3_value'] ?? '' ) ),
			array( 'label' => (string) ( $row['cmp_spec_4_label'] ?? '' ), 'value' => (string) ( $row['cmp_spec_4_value'] ?? '' ) ),
			array( 'label' => (string) ( $row['cmp_spec_5_label'] ?? '' ), 'value' => (string) ( $row['cmp_spec_5_value'] ?? '' ) ),
		),
		'pros_title' => (string) ( $row['cmp_pros_title'] ?? '' ),
		'pros_lines' => electro_child_landing_article_lines( $row['cmp_pros_body'] ?? '' ),
	);
}

/**
 * @param array<string, mixed> $row Canonical feature row.
 * @return array<string, mixed>
 */
function electro_child_landing_article_feature_row_for_template( $row ) {
	$row = is_array( $row ) ? $row : array();
	return array(
		'tag_style' => (string) ( $row['feat_tag_style'] ?? 'tier1' ),
		'tag'       => (string) ( $row['feat_tag'] ?? '' ),
		'title'     => (string) ( $row['feat_title'] ?? '' ),
		'desc'      => (string) ( $row['feat_desc'] ?? '' ),
		'spec'      => (string) ( $row['feat_spec'] ?? '' ),
	);
}
