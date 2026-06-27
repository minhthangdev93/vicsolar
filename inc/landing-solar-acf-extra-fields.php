<?php
/**
 * Landing Solar — ACF field definitions for sections 04–10.
 *
 * @package electro-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_acf_price_card_subfields( $prefix ) {
	$field_name = static function ( $key ) use ( $prefix ) {
		return $prefix . '_' . $key;
	};

	return array(
		array(
			'key'           => 'field_' . $prefix . '_card_image',
			'label'         => 'Ảnh sản phẩm',
			'name'          => $field_name( 'card_image' ),
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'library'       => 'all',
		),
		array(
			'key'   => 'field_' . $prefix . '_card_power',
			'label' => 'Công suất (tiêu đề thẻ)',
			'name'  => $field_name( 'power' ),
			'type'  => 'text',
		),
		array(
			'key'   => 'field_' . $prefix . '_card_list_price',
			'label' => 'Giá niêm yết',
			'name'  => $field_name( 'list_price' ),
			'type'  => 'text',
		),
		array(
			'key'   => 'field_' . $prefix . '_card_brand_line',
			'label' => 'Dòng thương hiệu',
			'name'  => $field_name( 'brand_line' ),
			'type'  => 'text',
		),
		array(
			'key'     => 'field_' . $prefix . '_card_variant',
			'label'   => 'Loại thẻ',
			'name'    => $field_name( 'variant' ),
			'type'    => 'select',
			'choices' => array(
				'grid'    => 'Không lưu trữ',
				'storage' => 'Có lưu trữ',
			),
			'default_value' => 'grid',
		),
		array(
			'key'          => 'field_' . $prefix . '_card_storage_tag',
			'label'        => 'Nhãn lưu trữ',
			'name'         => $field_name( 'storage_tag' ),
			'type'         => 'text',
			'instructions' => 'VD: KHÔNG LƯU TRỮ, CÓ LƯU TRỮ BYD. Để trống sẽ dùng nhãn mặc định theo loại thẻ.',
		),
		array(
			'key'   => 'field_' . $prefix . '_card_package_label',
			'label' => 'Gói — nhãn',
			'name'  => $field_name( 'package_label' ),
			'type'  => 'text',
		),
		array(
			'key'   => 'field_' . $prefix . '_card_package_price',
			'label' => 'Gói — giá',
			'name'  => $field_name( 'package_price' ),
			'type'  => 'text',
		),
		array(
			'key'   => 'field_' . $prefix . '_card_package_note',
			'label' => 'Gói — ghi chú',
			'name'  => $field_name( 'package_note' ),
			'type'  => 'text',
		),
		array(
			'key'          => 'field_' . $prefix . '_card_config_html',
			'label'        => 'Khối cấu hình (HTML)',
			'name'         => $field_name( 'config_html' ),
			'type'         => 'wysiwyg',
			'tabs'         => 'all',
			'toolbar'      => 'full',
			'media_upload' => 0,
		),
		array(
			'key'          => 'field_' . $prefix . '_card_output_html',
			'label'        => 'Khối sản lượng (HTML)',
			'name'         => $field_name( 'output_html' ),
			'type'         => 'wysiwyg',
			'tabs'         => 'all',
			'toolbar'      => 'full',
			'media_upload' => 0,
		),
		array(
			'key'   => 'field_' . $prefix . '_card_btn_label',
			'label' => 'Nút — nhãn',
			'name'  => $field_name( 'btn_label' ),
			'type'  => 'text',
		),
		array(
			'key'          => 'field_' . $prefix . '_card_btn_url',
			'label'        => 'Nút — link',
			'name'         => $field_name( 'btn_url' ),
			'type'         => 'text',
			'instructions' => 'URL đầy đủ hoặc #.',
		),
	);
}

/**
 * @param string $prefix   s04–s08.
 * @param string $num      Section number label.
 * @param bool   $shared   Section 04 shared pricing header.
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_acf_pricing_tab_fields( $prefix, $num, $shared = false ) {
	$fields = array(
		array(
			'key'   => 'field_vs_tab_' . $prefix,
			'label' => 'Section ' . $num . ' — Bảng giá',
			'name'  => '',
			'type'  => 'tab',
		),
	);

	if ( $shared ) {
		$fields[] = array(
			'key'   => 'field_' . $prefix . '_pricing_badge',
			'label' => 'Badge tiêu đề chung',
			'name'  => $prefix . '_pricing_badge',
			'type'  => 'text',
		);
		$fields[] = array(
			'key'   => 'field_' . $prefix . '_pricing_title',
			'label' => 'Tiêu đề chung (H2 cam)',
			'name'  => $prefix . '_pricing_title',
			'type'  => 'text',
		);
		$fields[] = array(
			'key'   => 'field_' . $prefix . '_pricing_subtitle',
			'label' => 'Phụ đề chung',
			'name'  => $prefix . '_pricing_subtitle',
			'type'  => 'text',
		);
	}

	$fields[] = array(
		'key'   => 'field_' . $prefix . '_tier_title',
		'label' => 'Tiêu đề nhóm (H3 cam)',
		'name'  => $prefix . '_tier_title',
		'type'  => 'text',
	);
	$fields[] = array(
		'key'   => 'field_' . $prefix . '_tier_subtitle',
		'label' => 'Phụ đề nhóm',
		'name'  => $prefix . '_tier_subtitle',
		'type'  => 'text',
	);
	$fields[] = array(
		'key'          => 'field_' . $prefix . '_cards',
		'label'        => 'Thẻ giá (trái → phải)',
		'name'         => $prefix . '_cards',
		'type'         => 'repeater',
		'layout'       => 'block',
		'button_label' => 'Thêm thẻ giá',
		'min'          => 1,
		'max'          => 6,
		'sub_fields'   => electro_child_landing_acf_price_card_subfields( $prefix ),
	);

	return $fields;
}

/**
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_acf_brand_card_subfields() {
	$field_name = static function ( $key ) {
		return 's10_' . $key;
	};

	return array(
		array(
			'key'           => 'field_s10_brand_card_image',
			'label'         => 'Ảnh thương hiệu',
			'name'          => $field_name( 'card_image' ),
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'library'       => 'all',
		),
		array(
			'key'   => 'field_s10_brand_card_title',
			'label' => 'Tên thương hiệu',
			'name'  => $field_name( 'title' ),
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_brand_card_bullet_1',
			'label' => 'Gạch đầu dòng 1',
			'name'  => $field_name( 'bullet_1' ),
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_brand_card_bullet_2',
			'label' => 'Gạch đầu dòng 2',
			'name'  => $field_name( 'bullet_2' ),
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_brand_card_bullet_3',
			'label' => 'Gạch đầu dòng 3',
			'name'  => $field_name( 'bullet_3' ),
			'type'  => 'text',
		),
		array(
			'key'   => 'field_s10_brand_card_btn_label',
			'label' => 'Nút — nhãn',
			'name'  => $field_name( 'btn_label' ),
			'type'  => 'text',
		),
		array(
			'key'          => 'field_s10_brand_card_btn_url',
			'label'        => 'Nút — link',
			'name'         => $field_name( 'btn_url' ),
			'type'         => 'text',
			'instructions' => 'URL đầy đủ hoặc #.',
		),
		array(
			'key'     => 'field_s10_brand_card_row',
			'label'   => 'Hàng hiển thị',
			'name'    => $field_name( 'row_group' ),
			'type'    => 'select',
			'choices' => array(
				'panels'  => 'Hàng 1 — Tấm pin & biến tần',
				'storage' => 'Hàng 2 — Pin lưu trữ',
			),
			'default_value' => 'panels',
		),
	);
}

/**
 * Extra ACF tabs/fields inserted between section 01 and CTA.
 *
 * @return array<int, array<string, mixed>>
 */
function electro_child_landing_acf_extra_fields() {
	$fields = array();

	foreach (
		array(
			array( 's04', '04', true ),
			array( 's05', '05', false ),
			array( 's06', '06', false ),
			array( 's07', '07', false ),
			array( 's08', '08', false ),
		) as $section
	) {
		$fields = array_merge( $fields, electro_child_landing_acf_pricing_tab_fields( $section[0], $section[1], $section[2] ) );
	}

	$fields = array_merge(
		$fields,
		array(
			array(
				'key'   => 'field_vs_tab_s09_text',
				'label' => 'Section 09 — Nhà xưởng (nội dung)',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'   => 'field_s09_badge_icon',
				'label' => 'Badge — icon (emoji)',
				'name'  => 's09_badge_icon',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_s09_badge_text',
				'label' => 'Badge — chữ',
				'name'  => 's09_badge_text',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_s09_title',
				'label' => 'Tiêu đề H2',
				'name'  => 's09_title',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_s09_subtitle_1',
				'label' => 'Phụ đề — dòng 1',
				'name'  => 's09_subtitle_1',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_s09_subtitle_2',
				'label' => 'Phụ đề — dòng 2',
				'name'  => 's09_subtitle_2',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_s09_factory_aria_label',
				'label' => 'Slider video — aria label',
				'name'  => 's09_factory_aria_label',
				'type'  => 'text',
			),
			array(
				'key'          => 'field_s09_videos',
				'label'        => 'Video YouTube (khối nhà xưởng)',
				'name'         => 's09_videos',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Thêm video',
				'min'          => 0,
				'max'          => 20,
				'instructions' => 'Dán link YouTube. Hơn 4 video sẽ tự trượt ngang trên desktop. Mobile hiển thị 1 video, trượt ngang.',
				'sub_fields'   => array(
					array(
						'key'          => 'field_s09_video_url',
						'label'        => 'Link YouTube',
						'name'         => 's09_video_url',
						'type'         => 'text',
						'instructions' => 'VD: https://www.youtube.com/watch?v=oqhsFoxivEU',
					),
					array(
						'key'   => 'field_s09_video_title',
						'label' => 'Tiêu đề (tuỳ chọn)',
						'name'  => 's09_video_title',
						'type'  => 'text',
					),
				),
			),
			array(
				'key'   => 'field_s09_projects_title',
				'label' => 'Dự án — tiêu đề',
				'name'  => 's09_projects_title',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_s09_projects_subtitle',
				'label' => 'Dự án — phụ đề',
				'name'  => 's09_projects_subtitle',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_s09_projects_aria_label',
				'label' => 'Slider dự án — aria label',
				'name'  => 's09_projects_aria_label',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_s09_card_link_label',
				'label' => 'Nhãn link thẻ bài viết',
				'name'  => 's09_card_link_label',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_vs_tab_s10',
				'label' => 'Section 10 — Thương hiệu',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'   => 'field_s10_title_line_1',
				'label' => 'Tiêu đề thương hiệu — dòng 1',
				'name'  => 's10_title_line_1',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_s10_title_line_2',
				'label' => 'Tiêu đề thương hiệu — dòng 2',
				'name'  => 's10_title_line_2',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_s10_subtitle',
				'label' => 'Phụ đề hàng 1',
				'name'  => 's10_subtitle',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_s10_subtitle_2',
				'label' => 'Phụ đề hàng 2 (pin lưu trữ)',
				'name'  => 's10_subtitle_2',
				'type'  => 'text',
			),
			array(
				'key'          => 'field_s10_brand_cards',
				'label'        => 'Thẻ thương hiệu (trái → phải, trên → dưới)',
				'name'         => 's10_brand_cards',
				'type'         => 'repeater',
				'layout'       => 'block',
				'button_label' => 'Thêm thương hiệu',
				'min'          => 1,
				'max'          => 12,
				'sub_fields'   => electro_child_landing_acf_brand_card_subfields(),
			),
		)
	);

	$fields = array_merge( $fields, electro_child_landing_acf_article_fields() );

	return $fields;
}
