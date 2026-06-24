<?php
/**
 * ACF fields — Section 10 long article blocks.
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param string $key   Canonical subfield key.
 * @param string $label Admin label.
 * @param string $type  ACF field type.
 * @param array  $extra Extra field args.
 * @return array<string, mixed>
 */
function electro_child_landing_acf_article_subfield( $key, $label, $type = 'text', $extra = array() ) {
	$field = array_merge(
		array(
			'key'   => 'field_s10a_' . $key,
			'label' => $label,
			'name'  => 's10a_' . $key,
			'type'  => $type,
		),
		$extra
	);
	return $field;
}

/**
 * @param string   $repeater Repeater field name.
 * @param string[] $keys     Canonical keys from article config.
 * @param array<string, string> $labels Key => label.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_acf_article_repeater_subfields( $repeater, $keys, $labels ) {
	$fields = array();
	foreach ( $keys as $key ) {
		$label = $labels[ $key ] ?? $key;
		$type  = 'text';
		$extra = array();

		if ( 'cmp_pros_body' === $key ) {
			$type  = 'textarea';
			$extra = array( 'rows' => 4, 'new_lines' => '' );
		} elseif ( 'choose_body' === $key ) {
			$type  = 'textarea';
			$extra = array( 'rows' => 3, 'new_lines' => 'br' );
		} elseif ( 'feat_desc' === $key ) {
			$type  = 'textarea';
			$extra = array( 'rows' => 3, 'new_lines' => 'br' );
		} elseif ( 'cmp_style' === $key ) {
			$type  = 'select';
			$extra = array(
				'choices' => array(
					'a' => 'Kiểu A (cam)',
					'b' => 'Kiểu B (xanh)',
					'c' => 'Kiểu C (xanh lá)',
				),
				'default_value' => 'a',
			);
		} elseif ( 'feat_tag_style' === $key ) {
			$type  = 'select';
			$extra = array(
				'choices' => array(
					'tier1'  => 'TIER 1',
					'global' => 'GLOBAL',
					'grade'  => 'GRADE A',
					'marine' => 'MARINE',
					'iec'    => 'IEC',
					'tcvn'   => 'TCVN',
				),
				'default_value' => 'tier1',
			);
		}

		$fields[] = electro_child_landing_acf_article_subfield( $key, $label, $type, $extra );
	}
	return $fields;
}

/**
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_acf_article_fields() {
	$cmp_labels = array(
		'cmp_style'         => 'Kiểu thẻ (màu)',
		'cmp_tag'           => 'Nhãn tag',
		'cmp_name'          => 'Tên thẻ',
		'cmp_tagline'       => 'Mô tả ngắn',
		'cmp_spec_1_label'  => 'Thông số 1 — nhãn',
		'cmp_spec_1_value'  => 'Thông số 1 — giá trị',
		'cmp_spec_2_label'  => 'Thông số 2 — nhãn',
		'cmp_spec_2_value'  => 'Thông số 2 — giá trị',
		'cmp_spec_3_label'  => 'Thông số 3 — nhãn',
		'cmp_spec_3_value'  => 'Thông số 3 — giá trị',
		'cmp_spec_4_label'  => 'Thông số 4 — nhãn',
		'cmp_spec_4_value'  => 'Thông số 4 — giá trị',
		'cmp_spec_5_label'  => 'Thông số 5 — nhãn (tùy chọn)',
		'cmp_spec_5_value'  => 'Thông số 5 — giá trị',
		'cmp_pros_title'    => 'Khuyến nghị — tiêu đề',
		'cmp_pros_body'     => 'Khuyến nghị — danh sách (mỗi dòng 1 mục)',
	);

	$cmp_keys = electro_child_landing_article_repeater_config( 's10_article_b4_cards' )['keys'];

	return array(
		array(
			'key'   => 'field_vs_tab_s10_article_b4',
			'label' => 'Khối 4 — 3 loại hệ thống',
			'name'  => '',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_s10_article_b4_heading',
			'label' => 'Tiêu đề H2',
			'name'  => 's10_article_b4_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_article_b4_intro',
			'label' => 'Đoạn mở đầu',
			'name'  => 's10_article_b4_intro',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'          => 'field_s10_article_b4_cards',
			'label'        => '3 thẻ so sánh',
			'name'         => 's10_article_b4_cards',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Thêm thẻ',
			'min'          => 1,
			'max'          => 6,
			'sub_fields'   => electro_child_landing_acf_article_repeater_subfields( 's10_article_b4_cards', $cmp_keys, $cmp_labels ),
		),
		array(
			'key'   => 'field_vs_tab_s10_article_b5',
			'label' => 'Khối 5 — 6 thành phần',
			'name'  => '',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_s10_article_b5_heading',
			'label' => 'Tiêu đề H2',
			'name'  => 's10_article_b5_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_article_b5_intro',
			'label' => 'Đoạn mở đầu',
			'name'  => 's10_article_b5_intro',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'          => 'field_s10_article_b5_features',
			'label'        => '6 thành phần',
			'name'         => 's10_article_b5_features',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Thêm thành phần',
			'min'          => 1,
			'max'          => 12,
			'sub_fields'   => electro_child_landing_acf_article_repeater_subfields(
				's10_article_b5_features',
				array( 'feat_tag_style', 'feat_tag', 'feat_title', 'feat_desc', 'feat_spec' ),
				array(
					'feat_tag_style' => 'Kiểu nhãn tag',
					'feat_tag'       => 'Nhãn tag',
					'feat_title'     => 'Tiêu đề',
					'feat_desc'      => 'Mô tả',
					'feat_spec'      => 'Dòng thông số dưới',
				)
			),
		),
		array(
			'key'   => 'field_s10_article_b5_footer',
			'label' => 'Đoạn kết',
			'name'  => 's10_article_b5_footer',
			'type'  => 'textarea',
			'rows'  => 2,
		),
		array(
			'key'   => 'field_vs_tab_s10_article_b6',
			'label' => 'Khối 6 — Aiko vs Panasonic',
			'name'  => '',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_s10_article_b6_heading',
			'label' => 'Tiêu đề H2',
			'name'  => 's10_article_b6_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_article_b6_badge',
			'label' => 'Badge (text, giữ emoji nếu cần)',
			'name'  => 's10_article_b6_badge',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_article_b6_intro',
			'label' => 'Đoạn mở đầu',
			'name'  => 's10_article_b6_intro',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'          => 'field_s10_article_b6_cards',
			'label'        => '2 thẻ so sánh',
			'name'         => 's10_article_b6_cards',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Thêm thẻ',
			'min'          => 1,
			'max'          => 4,
			'sub_fields'   => electro_child_landing_acf_article_repeater_subfields( 's10_article_b6_cards', $cmp_keys, $cmp_labels ),
		),
		array(
			'key'   => 'field_s10_article_b6_spec_brand',
			'label' => 'Bảng spec — nhãn thương hiệu',
			'name'  => 's10_article_b6_spec_brand',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_article_b6_spec_model',
			'label' => 'Bảng spec — model',
			'name'  => 's10_article_b6_spec_model',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_article_b6_spec_meta',
			'label' => 'Bảng spec — meta dòng',
			'name'  => 's10_article_b6_spec_meta',
			'type'  => 'text',
		),
		array(
			'key'          => 'field_s10_article_b6_spec_rows',
			'label'        => 'Bảng spec — hàng dữ liệu',
			'name'         => 's10_article_b6_spec_rows',
			'type'         => 'repeater',
			'layout'       => 'table',
			'button_label' => 'Thêm hàng',
			'sub_fields'   => electro_child_landing_acf_article_repeater_subfields(
				's10_article_b6_spec_rows',
				array( 'spec_col1_label', 'spec_col1_value', 'spec_col2_label', 'spec_col2_value' ),
				array(
					'spec_col1_label' => 'Cột 1 — nhãn',
					'spec_col1_value' => 'Cột 1 — giá trị',
					'spec_col2_label' => 'Cột 2 — nhãn',
					'spec_col2_value' => 'Cột 2 — giá trị',
				)
			),
		),
		array(
			'key'   => 'field_s10_article_b6_closing',
			'label' => 'Đoạn kết (cho phép <strong>)',
			'name'  => 's10_article_b6_closing',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'   => 'field_s10_article_b6_exp_label',
			'label' => 'Kinh nghiệm — nhãn',
			'name'  => 's10_article_b6_exp_label',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_article_b6_exp_quote',
			'label' => 'Kinh nghiệm — trích dẫn',
			'name'  => 's10_article_b6_exp_quote',
			'type'  => 'textarea',
			'rows'  => 4,
		),
		array(
			'key'   => 'field_vs_tab_s10_article_b7',
			'label' => 'Khối 7 — Inverter',
			'name'  => '',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_s10_article_b7_heading',
			'label' => 'Tiêu đề H2',
			'name'  => 's10_article_b7_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_article_b7_intro',
			'label' => 'Đoạn mở đầu',
			'name'  => 's10_article_b7_intro',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'          => 'field_s10_article_b7_cards',
			'label'        => '2 thẻ so sánh',
			'name'         => 's10_article_b7_cards',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Thêm thẻ',
			'min'          => 1,
			'max'          => 4,
			'sub_fields'   => electro_child_landing_acf_article_repeater_subfields( 's10_article_b7_cards', $cmp_keys, $cmp_labels ),
		),
		array(
			'key'   => 'field_s10_article_b7_answer',
			'label' => 'Hộp trả lời ngắn (cho phép <strong>)',
			'name'  => 's10_article_b7_answer',
			'type'  => 'textarea',
			'rows'  => 2,
		),
		array(
			'key'   => 'field_vs_tab_s10_article_b8',
			'label' => 'Khối 8 — Pin lưu trữ',
			'name'  => '',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_s10_article_b8_heading',
			'label' => 'Tiêu đề H2',
			'name'  => 's10_article_b8_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_article_b8_para_1',
			'label' => 'Đoạn 1',
			'name'  => 's10_article_b8_para_1',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'   => 'field_s10_article_b8_para_2',
			'label' => 'Đoạn 2',
			'name'  => 's10_article_b8_para_2',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'   => 'field_s10_article_b8_para_3',
			'label' => 'Đoạn 3',
			'name'  => 's10_article_b8_para_3',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'   => 'field_s10_article_b8_callout_title',
			'label' => 'Callout — tiêu đề',
			'name'  => 's10_article_b8_callout_title',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_article_b8_callout_body',
			'label' => 'Callout — nội dung',
			'name'  => 's10_article_b8_callout_body',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'   => 'field_vs_tab_s10_article_choose',
			'label' => 'Chọn đơn vị uy tín',
			'name'  => '',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_s10_article_choose_heading',
			'label' => 'Tiêu đề',
			'name'  => 's10_article_choose_heading',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_article_choose_intro',
			'label' => 'Đoạn mở đầu',
			'name'  => 's10_article_choose_intro',
			'type'  => 'textarea',
			'rows'  => 4,
		),
		array(
			'key'          => 'field_s10_article_choose_criteria',
			'label'        => '6 tiêu chí (thẻ)',
			'name'         => 's10_article_choose_criteria',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => 'Thêm tiêu chí',
			'min'          => 1,
			'max'          => 12,
			'sub_fields'   => electro_child_landing_acf_article_repeater_subfields(
				's10_article_choose_criteria',
				array( 'choose_tag', 'choose_title', 'choose_body' ),
				array(
					'choose_tag'   => 'Nhãn (VD: TIÊU CHÍ 1)',
					'choose_title' => 'Tiêu đề',
					'choose_body'  => 'Nội dung (cho phép <strong>)',
				)
			),
		),
		array(
			'key'   => 'field_s10_article_choose_table_caption',
			'label' => 'Bảng — tiêu đề',
			'name'  => 's10_article_choose_table_caption',
			'type'  => 'text',
		),
		array(
			'key'          => 'field_s10_article_choose_table_rows',
			'label'        => 'Bảng — hàng VicSolar',
			'name'         => 's10_article_choose_table_rows',
			'type'         => 'repeater',
			'layout'       => 'table',
			'button_label' => 'Thêm hàng',
			'sub_fields'   => electro_child_landing_acf_article_repeater_subfields(
				's10_article_choose_table_rows',
				array( 'choose_criterion', 'choose_value' ),
				array(
					'choose_criterion' => 'Tiêu chí',
					'choose_value'     => 'Giá trị VicSolar',
				)
			),
		),
		array(
			'key'   => 'field_s10_article_choose_recommend',
			'label' => 'Khuyến nghị cuối (cho phép <strong>)',
			'name'  => 's10_article_choose_recommend',
			'type'  => 'textarea',
			'rows'  => 3,
		),
	);
}
