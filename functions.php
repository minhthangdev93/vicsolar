<?php
/**
 * Electro Child
 *
 * @package electro-child
 */

/**
 * Include all your custom code here
 */
require_once get_stylesheet_directory() . '/inc/landing-solar-fields.php';
require_once get_stylesheet_directory() . '/inc/landing-solar-article.php';
require_once get_stylesheet_directory() . '/inc/landing-solar-article-acf-fields.php';
require_once get_stylesheet_directory() . '/inc/landing-solar-acf-extra-fields.php';
require_once get_stylesheet_directory() . '/inc/landing-solar.php';
require_once get_stylesheet_directory() . '/inc/landing-solar-performance.php';
require_once get_stylesheet_directory() . '/inc/landing-solar-acf.php';
require_once get_stylesheet_directory() . '/inc/woocommerce-comment-spam.php';

/**
 * Footer payment icons: skip invalid/missing attachment IDs (avoids PHP warnings).
 */
if ( ! function_exists( 'redux_apply_footer_credit_icons' ) ) {
	function redux_apply_footer_credit_icons( $content ) {
		global $electro_options;

		if ( empty( $electro_options['footer_credit_icons'] ) ) {
			return $content;
		}

		$credit_card_icons = explode( ',', $electro_options['footer_credit_icons'] );
		$items_html        = '';

		foreach ( $credit_card_icons as $credit_card_icon ) {
			$credit_card_icon = absint( trim( $credit_card_icon ) );
			if ( ! $credit_card_icon ) {
				continue;
			}

			$credit_card_image_atts = wp_get_attachment_image_src( $credit_card_icon, 'full' );
			if ( ! is_array( $credit_card_image_atts ) || empty( $credit_card_image_atts[0] ) ) {
				continue;
			}

			$items_html .= sprintf(
				'<li class="card-item"><img class="h-auto" src="%s" alt="" width="%s" height="%s"></li>',
				esc_url( $credit_card_image_atts[0] ),
				esc_attr( $credit_card_image_atts[1] ),
				esc_attr( $credit_card_image_atts[2] )
			);
		}

		if ( '' === $items_html ) {
			return $content;
		}

		return sprintf(
			'<div class="footer-payment-logo"><ul class="nav cash-card card-inline">%s</ul></div>',
			$items_html
		);
	}
}
 
/**
 * Luôn load style.css child (khi Theme Options tắt "Load child theme style.css").
 */
add_action( 'wp_enqueue_scripts', 'electro_child_enqueue_styles', 25 );

function electro_child_enqueue_styles() {
	if ( wp_style_is( 'electro-child-style', 'enqueued' ) || wp_style_is( 'electro-child-style', 'done' ) ) {
		return;
	}

	$child_style = get_stylesheet_directory() . '/style.css';
	$version     = file_exists( $child_style ) ? (string) filemtime( $child_style ) : '1.0.0';

	wp_enqueue_style(
		'electro-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'electro-style' ),
		$version
	);
}


add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
function woo_rename_tabs( $tabs ) {
	$tabs['reviews']['title'] = __( 'Đánh giá' );				// Rename the reviews tab
	return $tabs;
}
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'custom_output_related_products_same_category', 20 );

function get_deepest_child_category_id( $term_id, $taxonomy = 'product_cat' ) {
    $children = get_term_children( $term_id, $taxonomy );

    // Nếu term này không có con => chính nó là sâu nhất
    if ( empty( $children ) ) {
        return $term_id;
    }

    // Nếu có con => duyệt tiếp để lấy sâu nhất
    foreach ( $children as $child_id ) {
        return get_deepest_child_category_id( $child_id, $taxonomy );
    }
}

function custom_output_related_products_same_category() {
    global $product;

    $terms = wp_get_post_terms( $product->get_id(), 'product_cat' );

    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return;
    }

    $deepest_categories = array();

    foreach ( $terms as $term ) {
        $deepest_categories[] = get_deepest_child_category_id( $term->term_id, 'product_cat' );
    }

    // Loại bỏ trùng lặp
    $deepest_categories = array_unique( $deepest_categories );

    $args = array(
        'post_type'           => 'product',
        'posts_per_page'      => 4,
        'post__not_in'        => array( $product->get_id() ),
        'ignore_sticky_posts' => 1,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'tax_query'           => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $deepest_categories,
                'operator' => 'IN',
            ),
        ),
    );

    $related_products = new WP_Query( $args );

    if ( $related_products->have_posts() ) : ?>
        <section class="related products">
            <?php
            $heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );
            if ( $heading ) :
                ?>
                <h3 class="h2"><?php echo esc_html( $heading ); ?></h3>
            <?php endif; ?>

            <ul class="products products list-unstyled row g-0 row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-4 row-cols-xxl-4">
                <?php while ( $related_products->have_posts() ) : $related_products->the_post(); ?>
                    <?php wc_get_template_part( 'content', 'product' ); ?>
                <?php endwhile; ?>
            </ul>
        </section>
    <?php endif;

    wp_reset_postdata();
}
// === Thêm nút Zalo và Hotline sau nút "Thêm vào giỏ hàng" ===
add_action('woocommerce_after_add_to_cart_button', 'add_zalo_hotline_buttons');

function add_zalo_hotline_buttons() {
    $zalo_url = 'https://zalo.me/0966856555'; // 👉 Link Zalo của bạn
    $hotline = '0966856555'; // 👉 Số hotline của bạn

    echo '
    <div class="custom-zalo-hotline" style="display:flex;gap:8px;margin-top:10px;">
        <a href="' . esc_url($zalo_url) . '" target="_blank" class="button zalo-btn" 
           style="flex:1;text-align:center;background:#0084ff;color:#fff;font-weight:600;padding:12px 0;border-radius:6px;">
           💬 Nhắn tin Zalo
        </a>
        <a href="tel:' . esc_attr($hotline) . '" class="button hotline-btn" 
           style="flex:1;text-align:center;background:#28a745;color:#fff;font-weight:600;padding:12px 0;border-radius:6px;">
           📞 Gọi Hotline
        </a>
    </div>';
}
/**
 * MT Contact Bar - thanh liên hệ desktop (góc phải) + mobile (dính đáy)
 */
add_action( 'wp_footer', 'mt_contact_bar_output', 99 );

function mt_contact_bar_output() {
	// Chỉnh link / số / URL ảnh tại đây
	$mt_map_url     = 'https://maps.app.goo.gl/5aUD67abDByj6irC7';
	$mt_zalo_url    = 'https://zalo.me/0966856555';
	$mt_phone       = '0966856555';
	$mt_youtube_url = 'https://www.youtube.com/@%C4%90i%E1%BB%87nM%E1%BA%B7tTr%E1%BB%9DiVicSolar';
	$mt_tiktok_url  = 'https://www.tiktok.com/@dienmattroivicsolar';

	// Đổi URL ảnh sang Media của site bạn khi có (hiện dùng vicsolar.vn)
	$mt_icon_map     = 'https://vicsolar.vn/wp-content/uploads/2026/05/widget_icon_map.png';
	$mt_icon_zalo    = 'https://vicsolar.vn/wp-content/uploads/2026/05/icon_zalo.png';
	$mt_icon_phone   = 'https://vicsolar.vn/wp-content/uploads/2026/05/phone.png';
	$mt_icon_youtube = 'https://vicsolar.vn/wp-content/uploads/2026/05/icon_youtube.png';
	$mt_icon_tiktok  = 'https://vicsolar.vn/wp-content/uploads/2026/05/icon_tiktok.png';
	?>
	<style>
	/* MT Contact Bar */
	.mt-contact-phone-mobile {
		display: none;
	}

	.mt-contact-nav {
		position: fixed;
		right: 13px;
		bottom: 50px;
		z-index: 999;
		width: auto;
		padding: 10px 0;
		background: #ffffff;
		border: 2px solid #f2f2f2;
		border-radius: 10px;
		box-shadow: -5px 10px 8px rgba(0, 0, 0, 0.25);
	}

	.mt-contact-nav ul {
		list-style: none;
		padding: 0;
		margin: 0;
	}

	.mt-contact-nav ul li {
		list-style: none !important;
		margin: 0;
		padding: 0;
	}

	.mt-contact-nav ul li a {
		display: block;
		max-width: 74px;
		padding: 4px 6px;
		border: none;
		border-radius: 5px;
		text-align: center;
		font-size: 10px;
		line-height: 15px;
		color: #515151;
		font-weight: 700;
		text-decoration: none;
		background: transparent;
	}

	.mt-contact-nav ul li a:hover {
		color: #ef5261;
	}

	.mt-contact-icon {
		display: block;
		width: 36px;
		height: 36px;
		margin: 0 auto 2px;
		background-repeat: no-repeat;
		background-position: center;
		background-size: contain;
	}

	.mt-icon-map {
		background-image: url("<?php echo esc_url( $mt_icon_map ); ?>");
	}

	.mt-icon-zalo {
		background-image: url("<?php echo esc_url( $mt_icon_zalo ); ?>");
	}

	.mt-icon-phone {
		background-image: url("<?php echo esc_url( $mt_icon_phone ); ?>");
	}

	.mt-icon-youtube {
		background-image: url("<?php echo esc_url( $mt_icon_youtube ); ?>");
	}

	.mt-icon-tiktok {
		background-image: url("<?php echo esc_url( $mt_icon_tiktok ); ?>");
	}

	@media only screen and (max-width: 600px) {
		.mt-contact-phone-mobile {
			display: block !important;
		}

		.mt-contact-nav {
			left: 0;
			right: auto;
			bottom: 0;
			width: 100%;
			height: 62px;
			padding: 5px 0;
			margin: 0;
			border: none;
			border-radius: 0;
			background: #ffffff;
			box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.18);
		}

		.mt-contact-nav ul {
			display: flex;
			align-items: center;
			justify-content: space-between;
			height: 100%;
		}

		.mt-contact-nav ul li {
			width: 20%;
			height: 52px;
		}

		.mt-contact-nav ul li a {
			max-width: none;
			height: 52px;
			padding: 0;
			margin: 0 auto;
			font-size: 10px;
			line-height: 14px;
		}

		.mt-contact-icon {
			width: 28px;
			height: 28px;
			margin-bottom: 2px;
		}

		.mt-contact-phone-mobile a {
			position: relative;
		}

		.mt-contact-phone-circle {
			position: absolute;
			top: -18px;
			left: 50%;
			transform: translateX(-50%);
			width: 50px;
			height: 50px;
			border: 2px solid #ffffff;
			border-radius: 50%;
			background: #ef5261;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.mt-contact-phone-circle .mt-icon-phone {
			width: 28px;
			height: 28px;
			margin: 0;
		}

		.mt-contact-phone-text {
			display: block;
			position: relative;
			top: 34px;
			font-size: 10px;
			font-weight: 700;
			color: #515151;
		}
	}
	</style>

	<div class="mt-contact-nav">
		<ul>
			<li>
				<a href="<?php echo esc_url( $mt_map_url ); ?>" rel="nofollow" target="_blank">
					<i class="mt-contact-icon mt-icon-map" aria-hidden="true"></i>
					Địa chỉ
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( $mt_zalo_url ); ?>" rel="nofollow" target="_blank">
					<i class="mt-contact-icon mt-icon-zalo" aria-hidden="true"></i>
					Zalo
				</a>
			</li>
			<li class="mt-contact-phone-mobile">
				<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $mt_phone ) ); ?>" rel="nofollow">
					<span class="mt-contact-phone-circle">
						<i class="mt-contact-icon mt-icon-phone" aria-hidden="true"></i>
					</span>
					<span class="mt-contact-phone-text">Gọi điện</span>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( $mt_youtube_url ); ?>" rel="nofollow" target="_blank">
					<i class="mt-contact-icon mt-icon-youtube" aria-hidden="true"></i>
					Youtube
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( $mt_tiktok_url ); ?>" rel="nofollow" target="_blank">
					<i class="mt-contact-icon mt-icon-tiktok" aria-hidden="true"></i>
					TikTok
				</a>
			</li>
		</ul>
	</div>
	<?php
}
/**
 * Footer CTA: Gọi Hotline + Zalo (thay form CF7 trong footer newsletter).
 */
add_action( 'after_setup_theme', 'electro_child_replace_footer_newsletter', 20 );

function electro_child_replace_footer_newsletter() {
	remove_action( 'electro_footer_divider_v2', 'electro_footer_newsletter_v2', 10 );
	add_action( 'electro_footer_divider_v2', 'electro_child_footer_newsletter_v2', 10 );
}

function electro_child_footer_newsletter_v2() {
	if ( ! apply_filters( 'electro_footer_newsletter', true ) ) {
		return;
	}

	$hotline     = apply_filters( 'electro_child_footer_hotline', '0966856555' );
	$zalo_url    = apply_filters( 'electro_child_footer_zalo_url', 'https://zalo.me/0966856555' );
	$hotline_tel = preg_replace( '/\s+/', '', $hotline );
	$cta_note    = apply_filters(
		'electro_child_footer_cta_note',
		'* Nhắn nhu cầu của bạn (khu vực, công suất, hóa đơn điện) để mình tư vấn nhanh nhất.'
	);
	?>
	<div class="footer-newsletter">
		<div class="td-register-full">
			<div class="container">
				<div class="td-register-full-wrap">
					<div class="td-register-title">Tư vấn điện mặt trời miễn phí</div>
					<div class="td-register-form">
						<div class="td-register-cta-box">
							<div class="td-register-cta-buttons">
								<a href="tel:<?php echo esc_attr( $hotline_tel ); ?>" class="td-cta-btn td-cta-hotline" rel="nofollow">
									<span class="td-cta-btn-icon" aria-hidden="true">📞</span>
									<?php esc_html_e( 'Gọi Hotline', 'electro-child' ); ?>
								</a>
								<a href="<?php echo esc_url( $zalo_url ); ?>" class="td-cta-btn td-cta-zalo" target="_blank" rel="nofollow noopener">
									<span class="td-cta-btn-icon" aria-hidden="true">💬</span>
									<?php esc_html_e( 'Nhắn tin Zalo', 'electro-child' ); ?>
								</a>
							</div>
							<p class="td-register-cta-note"><?php echo esc_html( $cta_note ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}


