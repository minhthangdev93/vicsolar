<?php
/**
 * Default copy — Section 10 long article + chọn đơn vị uy tín.
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return array(

	's10_article_b4_heading' => '4. Ba loại hệ thống điện mặt trời',
	's10_article_b4_intro'   => 'Hệ thống điện mặt trời chia làm ba loại theo cách kết nối lưới: Hòa lưới (Grid-tied), Hybrid (lưu trữ + hòa lưới), Off-grid (độc lập). Mỗi loại phù hợp tình huống sử dụng khác nhau.',

	's10_article_b4_cards' => array(
		array(
			'style'       => 'a',
			'tag'         => 'Grid-tied',
			'name'        => 'Hòa lưới',
			'tagline'     => 'Tấm pin + inverter HL · Đẩy toàn bộ vào lưới EVN',
			'spec_1_label' => 'Chi phí',
			'spec_1_value' => 'Thấp nhất',
			'spec_2_label' => 'ROI',
			'spec_2_value' => 'Hấp dẫn nhất',
			'spec_3_label' => 'Backup mất điện',
			'spec_3_value' => 'Không có (IEEE 1547)',
			'spec_4_label' => 'Phù hợp',
			'spec_4_value' => '80% hộ gia đình',
			'pros_title'  => 'Khuyến nghị:',
			'pros_body'   => "Hộ ban ngày sinh hoạt nhiều\nVăn phòng, xưởng nhỏ\nKhu vực ổn định lưới EVN",
		),
		array(
			'style'       => 'b',
			'tag'         => 'Storage',
			'name'        => 'Hybrid',
			'tagline'     => 'Tấm pin + inverter hybrid + pin LFP',
			'spec_1_label' => 'Chi phí',
			'spec_1_value' => 'Cao hơn HL 35-50%',
			'spec_2_label' => 'ROI',
			'spec_2_value' => 'Dài hơn 1-2 năm',
			'spec_3_label' => 'Backup mất điện',
			'spec_3_value' => 'Có (tải critical)',
			'spec_4_label' => 'Phù hợp',
			'spec_4_value' => 'Hộ tiêu thụ tối nhiều',
			'pros_title'  => 'Khuyến nghị:',
			'pros_body'   => "Khu vực hay cúp điện\nHộ tiêu thụ ban đêm nhiều\nMục tiêu tự chủ năng lượng",
		),
		array(
			'style'       => 'c',
			'tag'         => 'Independent',
			'name'        => 'Off-grid',
			'tagline'     => 'Hoàn toàn độc lập · Không kết nối EVN',
			'spec_1_label' => 'Chi phí',
			'spec_1_value' => 'Cao nhất',
			'spec_2_label' => 'Pin dung lượng',
			'spec_2_value' => 'Lớn (≥30 kWh)',
			'spec_3_label' => 'Máy phát dự phòng',
			'spec_3_value' => 'Cần thiết',
			'spec_4_label' => 'Phù hợp',
			'spec_4_value' => '5% tổng dự án',
			'pros_title'  => 'Khuyến nghị:',
			'pros_body'   => "Đảo, vùng sâu xa lưới\nTrang trại biệt lập\nTrạm BTS / tháp truyền",
		),
	),

	's10_article_b5_heading' => '5. Cấu tạo hệ thống — 6 thành phần chính',
	's10_article_b5_intro'   => 'Một hệ thống điện mặt trời hoàn chỉnh gồm 6 nhóm thiết bị cốt lõi tuân thủ tiêu chuẩn IEC 62548:2023 + TCVN 7447:2010. Mỗi dự án nghiệm thu đo điện trở tiếp địa ≤ 4 Ohm và kiểm tra cách điện DC ≥ 1 MOhm trước khi bàn giao.',
	's10_article_b5_footer'  => 'Phần còn lại (10-15%) là dây cáp DC/AC chuẩn IEC 62930 + nhân công thi công kỹ sư VNS chứng chỉ ETT/EVN.',

	's10_article_b5_features' => array(
		array(
			'tag_style' => 'tier1',
			'tag'       => 'TIER 1 · BLOOMBERGNEF',
			'title'     => 'Tấm pin năng lượng mặt trời',
			'desc'      => 'Aiko 660Wp ABC hoặc Panasonic 620Wp TOPCon. Tier 1 BloombergNEF, hiệu suất ≥22,7%, bảo hành 25-30 năm.',
			'spec'      => '35-45% chi phí · Hiệu suất 22,7-23,0%',
		),
		array(
			'tag_style' => 'global',
			'tag'       => 'TIER 1 · GLOBAL',
			'title'     => 'Inverter (biến tần)',
			'desc'      => 'Sungrow / Huawei / Sigenergy. Hòa lưới 98-98,6% hoặc Hybrid 97-98% có cổng pin DC + AC backup.',
			'spec'      => '12-18% chi phí · Bảo hành 5-10 năm',
		),
		array(
			'tag_style' => 'grade',
			'tag'       => 'GRADE A · LFP',
			'title'     => 'Pin lưu trữ Lithium LFP',
			'desc'      => 'BYD / Pylontech / Sigen / Huawei LUNA / Stack100. Chu kỳ ≥6.000 lần ở 80% DoD theo IEC 62619:2022.',
			'spec'      => '20-35% chi phí · Tuổi thọ 10-15 năm',
		),
		array(
			'tag_style' => 'marine',
			'tag'       => 'MARINE GRADE',
			'title'     => 'Khung giàn cơ khí',
			'desc'      => 'Nhôm 6063-T5 + vít inox 304. Chống ăn mòn 25 năm trong môi trường mặn ven biển. Đảm bảo chịu gió cấp 12.',
			'spec'      => '6-8% chi phí · Bảo hành 10 năm',
		),
		array(
			'tag_style' => 'iec',
			'tag'       => 'IEC 62548:2023',
			'title'     => 'Tủ điện DC/AC',
			'desc'      => 'DC SPD chống sét + AC tủ đóng cắt + MCB chống dòng rò. Tuân thủ IEC 62548:2023 cho thiết kế DC array.',
			'spec'      => '4-6% chi phí · IEC 62548:2023',
		),
		array(
			'tag_style' => 'tcvn',
			'tag'       => 'TCVN 9385:2012',
			'title'     => 'Hệ tiếp địa chống sét',
			'desc'      => 'Theo TCVN 9385:2012, điện trở tiếp địa ≤4 Ohm. Bao gồm cọc đồng + dây dẫn + kẹp.',
			'spec'      => '2-3% chi phí · TCVN 9385:2012',
		),
	),

	's10_article_b6_heading' => '6. Tấm pin Aiko 660Wp ABC vs Panasonic 620Wp TOPCon',
	's10_article_b6_badge'   => '⭐ Top bestseller',
	's10_article_b6_intro'   => 'Hai dòng tấm pin N-type cao cấp VicSolar phân phối song song năm 2026 là Aiko 660Wp ABC và Anchor by Panasonic 620Wp N-Type TOPCon. Cả hai đều thuộc Tier 1 BloombergNEF, hiệu suất module ≥22,7%.',

	's10_article_b6_cards' => array(
		array(
			'style'       => 'a',
			'tag'         => '⭐ All Back Contact',
			'name'        => 'Aiko 660Wp ABC',
			'tagline'     => 'Toàn bộ busbar mặt sau · Mặt trước đen tuyền',
			'spec_1_label' => 'Công suất',
			'spec_1_value' => '660 Wp',
			'spec_2_label' => 'Hiệu suất',
			'spec_2_value' => '22,7%',
			'spec_3_label' => 'Hệ số nhiệt Pmax',
			'spec_3_value' => '-0,26%/°C',
			'spec_4_label' => 'Suy giảm tuyến tính',
			'spec_4_value' => '-0,35%/năm',
			'spec_5_label' => 'Bảo hành SP / HS',
			'spec_5_value' => '15 năm / 25 năm ≥87,4%',
			'pros_title'  => 'Phù hợp:',
			'pros_body'   => "Mái nhỏ cần mật độ kWp/m² cao\nThẩm mỹ all-black đẹp\nBifacial gain 70%",
		),
		array(
			'style'       => 'b',
			'tag'         => '🏆 N-Type TOPCon',
			'name'        => 'Panasonic 620Wp TOPCon',
			'tagline'     => 'Zero LID Loss · PID resistant · Industry-leading 30 năm',
			'spec_1_label' => 'Công suất',
			'spec_1_value' => '620 Wp',
			'spec_2_label' => 'Hiệu suất',
			'spec_2_value' => '23,0%',
			'spec_3_label' => 'Hệ số nhiệt Pmax',
			'spec_3_value' => '-0,29%/°C',
			'spec_4_label' => 'Suy giảm tuyến tính',
			'spec_4_value' => '-0,4%/năm',
			'spec_5_label' => 'Bảo hành SP / HS',
			'spec_5_value' => '15 năm / 30 năm',
			'pros_title'  => 'Phù hợp:',
			'pros_body'   => "Khách hàng ưu tiên hiệu suất cao nhất\nBảo hành HS dài nhất TG (30 năm)\nĐịnh cư >25 năm",
		),
	),

	's10_article_b6_spec_brand' => 'Anchor by Panasonic',
	's10_article_b6_spec_model' => 'AE13TDXXXTHC16B5 — 620Wp N-Type TOPCon',
	's10_article_b6_spec_meta'  => 'Ấn Độ · N-Type TOPCon · IEC 61215 · IEC 61730 · IEC 61701 · IEC 62716',

	's10_article_b6_spec_rows' => array(
		array( 'col1_label' => 'Cell', 'col1_value' => '132 half-cell 182×210mm', 'col2_label' => 'Kích thước', 'col2_value' => '2382×1134×30mm' ),
		array( 'col1_label' => 'Trọng lượng', 'col1_value' => '32,5 kg', 'col2_label' => 'Bifaciality', 'col2_value' => '80% ±5%' ),
		array( 'col1_label' => 'Suy giảm năm đầu', 'col1_value' => '-1%', 'col2_label' => 'Sản xuất tại', 'col2_value' => 'Panasonic Life Solutions India' ),
		array( 'col1_label' => 'Bảo hành SP', 'col1_value' => '15 năm', 'col2_label' => 'Bảo hành HS', 'col2_value' => '30 năm — industry-leading' ),
	),

	's10_article_b6_closing' => 'Đội ngũ kỹ sư VNS khuyến nghị <strong>Panasonic 620Wp TOPCon</strong> cho khách hàng ưu tiên hiệu suất cao nhất và bảo hành performance dài nhất thị trường; <strong>Aiko 660Wp ABC</strong> cho dự án ưu tiên thẩm mỹ all-black, công suất tuyệt đối cao hơn và bifacial gain mạnh. Chênh lệch giá hai dòng dưới 5%.',
	's10_article_b6_exp_label' => 'Kinh nghiệm thực tế từ VicSolar',
	's10_article_b6_exp_quote' => '“Qua 800+ dự án Aiko 660Wp ABC và Panasonic 620Wp TOPCon 2024-2025, chúng tôi ghi nhận degradation thực tế Aiko -0,8%/năm và Panasonic -0,35%/năm sau 18 tháng — đều thấp hơn cam kết hãng. Kỹ sư VNS khuyến nghị Panasonic 620Wp TOPCon cho khách hàng định cư >25 năm nhờ bảo hành hiệu suất 30 năm industry-leading, Aiko ABC cho mái nhỏ cần mật độ kWp/m² cao nhất.”',

	's10_article_b7_heading' => '7. Inverter — Hòa lưới vs Hybrid',
	's10_article_b7_intro'   => 'Inverter (biến tần) là bộ não của hệ thống điện mặt trời, chuyển dòng điện DC từ tấm pin thành AC đồng bộ lưới EVN. Hai loại inverter chính: Hòa lưới (string inverter) và Hybrid (hybrid inverter có cổng pin).',

	's10_article_b7_cards' => array(
		array(
			'style'       => 'a',
			'tag'         => 'String Inverter',
			'name'        => 'Inverter Hòa lưới',
			'tagline'     => 'Cổng DC + cổng AC ra lưới · Không sạc pin',
			'spec_1_label' => 'Cổng DC pin',
			'spec_1_value' => 'Không có',
			'spec_2_label' => 'Cổng AC backup',
			'spec_2_value' => 'Không có',
			'spec_3_label' => 'Hiệu suất',
			'spec_3_value' => '98-98,6%',
			'spec_4_label' => 'Chi phí',
			'spec_4_value' => '2-4 tr/kWp',
			'spec_5_label' => 'Hãng',
			'spec_5_value' => 'Sungrow / Huawei / Sigen',
			'pros_title'  => 'Phù hợp:',
			'pros_body'   => "Hệ chỉ đẩy điện vào lưới\nHộ ngân sách hạn chế\nKhu vực ổn định lưới EVN",
		),
		array(
			'style'       => 'b',
			'tag'         => 'Hybrid Inverter',
			'name'        => 'Inverter Hybrid',
			'tagline'     => '+ Cổng DC sạc pin · + Cổng AC backup',
			'spec_1_label' => 'Cổng DC pin',
			'spec_1_value' => 'Có (sạc LFP)',
			'spec_2_label' => 'Cổng AC backup',
			'spec_2_value' => 'Có (mất điện vẫn dùng)',
			'spec_3_label' => 'Hiệu suất',
			'spec_3_value' => '97-98%',
			'spec_4_label' => 'Chi phí',
			'spec_4_value' => '4-7 tr/kWp',
			'spec_5_label' => 'Hãng',
			'spec_5_value' => '5 dòng phổ biến',
			'pros_title'  => '5 dòng VNS phân phối:',
			'pros_body'   => "Sigenergy SigenStor (tích hợp inverter+pin)\nHuawei SUN2000 + LUNA2000\nSungrow SH RT + SBR · Growatt SPH + ARK · Deye SUN-K + BOS-G",
		),
	),

	's10_article_b7_answer' => 'Inverter Hybrid có <strong>cổng pin DC</strong> + <strong>cổng AC backup</strong>. Hòa lưới chỉ đẩy điện vào lưới, mất điện sẽ tự ngắt theo IEEE 1547.',

	's10_article_b8_heading'      => '8. Pin lưu trữ Lithium LFP — 5 hãng',
	's10_article_b8_para_1'       => 'Pin lưu trữ (BESS — Battery Energy Storage System) chuẩn IEC 62619:2022 là Lithium iron phosphate (LFP) thay vì NMC truyền thống. LFP có ưu điểm: an toàn nhiệt cao (không cháy nổ ở nhiệt độ thường), chu kỳ sạc ≥ 6.000 lần ở 80% DoD, tuổi thọ thực tế 10-15 năm.',
	's10_article_b8_para_2'       => 'VicSolar phân phối 5 dòng pin LFP chính hãng: BYD B-Box Premium (Tier 1 BloombergNEF), Pylontech Force-L (chuẩn cũ phổ biến), Sigenergy SigenStor BAT (tích hợp với inverter Sigen), Huawei LUNA2000 (đồng bộ với SUN2000), Stack100 (modular công nghiệp). Tất cả đều đạt chứng nhận UL 9540A về an toàn cháy nổ.',
	's10_article_b8_para_3'       => 'Đội ngũ kỹ sư VNS khuyến nghị BYD B-Box hoặc Sigenergy SigenStor cho hộ gia đình; Stack100 cho doanh nghiệp 30-100 kWh. Dung lượng tối ưu cho hộ tiêu thụ 1.000 kWh/tháng là 10-15 kWh — đủ phủ 70% tiêu thụ ban đêm.',
	's10_article_b8_callout_title' => 'Lưu ý an toàn pin LFP',
	's10_article_b8_callout_body'  => 'Pin LFP chuẩn IEC 62619:2022 không cháy nổ ở nhiệt độ thường, BMS giám sát 24/7. Đội ngũ VNS đã lắp 800+ hệ Hybrid residential 2024-2025 với 0 sự cố pin.',

	's10_article_choose_heading' => 'Cách Chọn Đơn Vị Lắp Điện Mặt Trời Uy Tín — 6 Tiêu Chí Không Thể Bỏ Qua',
	's10_article_choose_intro'   => 'Thị trường lắp đặt điện mặt trời hiện có hàng trăm đơn vị từ tập đoàn lớn đến nhà thầu nhỏ lẻ. Chênh lệch giá 10–20% giữa các đơn vị là bình thường — nhưng chênh lệch chất lượng có thể kéo dài suốt 25 năm vận hành. Dưới đây là 6 tiêu chí VicSolar khuyến nghị kiểm tra trước khi ký hợp đồng với bất kỳ đơn vị nào.',

	's10_article_choose_criteria' => array(
		array(
			'tag'   => 'TIÊU CHÍ 1',
			'title' => 'Giấy phép hoạt động điện lực',
			'body'  => 'Đơn vị thi công phải có Giấy phép do Sở Công Thương cấp. Không có giấy phép = vi phạm pháp luật, hệ thống có thể bị yêu cầu tháo dỡ. <strong>VNS: Giấy phép 175/GP-SCT.</strong>',
		),
		array(
			'tag'   => 'TIÊU CHÍ 2',
			'title' => 'Chứng chỉ hành nghề kỹ sư',
			'body'  => 'Kỹ sư trực tiếp thi công phải có chứng chỉ hành nghề điện và xây dựng do cơ quan Nhà nước cấp. Yêu cầu xem chứng chỉ trước khi ký hợp đồng.',
		),
		array(
			'tag'   => 'TIÊU CHÍ 3',
			'title' => 'Nhà phân phối chính hãng',
			'body'  => 'Có hợp đồng phân phối chính hãng với nhà sản xuất mới đảm bảo thiết bị có nguồn gốc rõ ràng và bảo hành đúng hạn. Tránh thiết bị không rõ nguồn gốc.',
		),
		array(
			'tag'   => 'TIÊU CHÍ 4',
			'title' => 'Số dự án thực tế',
			'body'  => 'Kinh nghiệm thực tế quan trọng hơn quảng cáo. Yêu cầu xem danh sách công trình, địa chỉ cụ thể và liên hệ khách hàng cũ để xác nhận.',
		),
		array(
			'tag'   => 'TIÊU CHÍ 5',
			'title' => 'Cam kết bảo hành bằng văn bản',
			'body'  => 'Hợp đồng ghi rõ thời hạn bảo hành từng hạng mục, quy trình xử lý sự cố, thời gian phản hồi và điều khoản phạt nếu vi phạm tiến độ.',
		),
		array(
			'tag'   => 'TIÊU CHÍ 6',
			'title' => 'Hỗ trợ thủ tục EVN',
			'body'  => 'Đơn vị uy tín phải hỗ trợ làm toàn bộ hồ sơ đăng ký EVN theo NĐ 58/2025/NĐ-CP. Không hỗ trợ thủ tục này là dấu hiệu cần xem xét lại.',
		),
	),

	's10_article_choose_table_caption' => 'VicSolar — Kiểm tra 6 tiêu chí',
	's10_article_choose_table_rows'    => array(
		array( 'criterion' => 'Giấy phép hoạt động điện lực', 'value' => '175/GP-SCT (Sở Công Thương HCM)' ),
		array( 'criterion' => 'Giấy phép năng lực xây dựng', 'value' => 'HCM-00076550' ),
		array( 'criterion' => 'Chứng nhận chất lượng', 'value' => 'ISO 9001 · ISO 14001 · ISO 45001' ),
		array( 'criterion' => 'Dự án đã triển khai', 'value' => '3.000+ hệ thống toàn quốc' ),
		array( 'criterion' => 'Phân phối chính hãng', 'value' => 'Aiko · Huawei · BYD · Pylontech · Sungrow' ),
		array( 'criterion' => 'Hỗ trợ thủ tục EVN', 'value' => 'Miễn phí khi ký hợp đồng' ),
	),

	's10_article_choose_recommend' => '<strong>Khuyến nghị:</strong> Yêu cầu kiểm tra đầy đủ 6 tiêu chí trước khi ký hợp đồng với bất kỳ đơn vị nào. Hotline VicSolar: <strong>088.60.60.660</strong> — tư vấn và báo giá miễn phí trong 2 giờ làm việc.',
);
