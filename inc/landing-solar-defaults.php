<?php
/**
 * Default copy for landing solar — pre-fills admin fields and front-end fallbacks.
 *
 * On first load, empty ACF options are written to the database (per field only).
 * Admin edits are never overwritten by theme deploys.
 *
 * @package electro-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(
	// Section 01 — hero (ảnh nền: upload trong admin; không fallback URL ngoài).
	's01_title'          => 'Bảng Giá Chi Phí Lắp Điện Năng Lượng Mặt Trời 2026',
	's01_intro_1'        => 'Lắp điện năng lượng mặt trời trọn gói tại VicSolar năm 2026 có giá từ 75 triệu đồng cho hệ 5 kWp hòa lưới (tấm pin Aiko 660Wp ABC, bảo hành 30 năm) đến hơn 1 tỷ đồng cho hệ 110 kWp ba pha công suất lớn.',
	's01_intro_2'        => 'Hoàn vốn trung bình 4-5 năm dựa trên dữ liệu hơn 3.000 dự án thực tế của đội ngũ VicSolar 2024-2025. Áp dụng IEC 62619:2022 cho pin lưu trữ và TCVN 7447 cho hệ thống điện hộ gia đình.',

	// Section 01 — pháp lý.
	's01_legal_badge'    => '⚖️ Cơ sở pháp lý điện mặt trời',
	's01_legal_title'    => 'Lắp đặt điện mặt trời: Pháp lý, quy định hiện hành',
	's01_legal_desc'     => 'Việc lắp đặt điện mặt trời cần tuân thủ Nghị định số 58/2025/NĐ-CP của Chính phủ. Cụ thể:',
	's01_legal_bullet_1' => 'Hộ gia đình <span style="color: #ff6a00; font-weight: 950;">&lt; 100 kW</span> (tự dùng): Phải thông báo đầy đủ đến cơ sở ban ngành theo đúng quy định. Sản phẩm phải có đầy đủ chứng từ <strong><span style="color: #ff6600;">CO,CQ,VAT</span>. <span style="color: #ff6600;"><span style="color: #000000;">Xử phạt hành chính</span> từ nhắc nhở đến 100tr đồng nếu vi phạm <span style="color: #000000;">một số điều trong Nghị Định 133/2026/NĐ-CP</span></span></strong>',
	's01_legal_bullet_2' => 'Công suất <span style="color: #ff6a00; font-weight: 950;">≥ 100 kW</span> hoặc có bán điện dư: tùy trường hợp phải đăng ký và/hoặc xin phép theo quy định.',
	's01_legal_actions_title' => 'Liên hệ báo giá ngay',
	's01_zalo_label'     => 'Chat Zalo',
	's01_zalo_url'       => 'https://zalo.me/0966856555',
	's01_call_label'     => 'Gọi ngay',
	's01_call_phone'     => '0966856555',

	// Section 01 — video.
	's01_video_label'    => 'Videos',
	's01_video_badge'    => '▶️ YouTube',
	's01_youtube_url'    => 'https://www.youtube.com/watch?v=oqhsFoxivEU',
	's01_video_title'    => 'Videos',
	's01_legal_note'     => 'Nội dung mang tính tham khảo. Khi triển khai, chúng tôi hỗ trợ rà soát hồ sơ và hướng dẫn tuân thủ theo quy định hiện hành.',

	// CTA sau mỗi section.
	'cta_phone'          => '0966856555',
	'cta_phone_label'    => 'Gọi ngay',
	'cta_zalo_url'       => 'https://zalo.me/0966856555',
	'cta_zalo_label'     => 'Nhắn Zalo',

	// Section 09 — nhà xưởng.
	's09_badge_icon'           => '🛠️',
	's09_badge_text'           => 'Dự án tiêu biểu',
	's09_title'                => 'ĐIỆN MẶT TRỜI NHÀ XƯỞNG',
	's09_subtitle_1'           => '300+ Doanh Nghiệp – Nhà Xưởng Lựa Chọn',
	's09_subtitle_2'           => 'VicSolar Là Đơn Vị Tổng Thầu',
	's09_factory_aria_label'   => 'Điện mặt trời nhà xưởng',
	's09_projects_title'       => 'Các dự án tiêu biểu của chúng tôi',
	's09_projects_subtitle'    => 'Đảm bảo chất lượng và hiệu quả',
	's09_projects_aria_label'  => 'Các dự án tiêu biểu',
	's09_card_link_label'      => 'Xem chi tiết',

	// Section 10 — phụ đề hàng 2 thương hiệu.
	's10_subtitle_2'           => 'Pin lưu trữ điện mặt trời – Lithium',
);
