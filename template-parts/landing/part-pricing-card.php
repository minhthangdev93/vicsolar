<?php
/**
 * Single pricing card column.
 *
 * @package electro-child
 *
 * @var array<string, mixed> $args {
 *     @type array<string, mixed> $card Card data.
 *     @type int                $index 0-based index.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$card  = isset( $args['card'] ) && is_array( $args['card'] ) ? $args['card'] : array();
$index = isset( $args['index'] ) ? (int) $args['index'] : 0;

$variant   = ! empty( $card['variant'] ) && 'storage' === $card['variant'] ? 'storage' : 'grid';
$is_storage = 'storage' === $variant;

$tag_text = trim( (string) ( $card['storage_tag'] ?? '' ) );
if ( '' === $tag_text ) {
	$tag_text = $is_storage ? 'CÓ LƯU TRỮ' : 'KHÔNG LƯU TRỮ';
}

$tag_icon = $is_storage ? '🔋' : '⚡';
$tag_style = $is_storage
	? 'display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 999px; background: linear-gradient(180deg,#ffffff 0%, rgba(255,255,255,.72) 100%); border: 1.6px solid rgba(194,65,12,.28); box-shadow: 0 12px 30px rgba(194,65,12,.14); color: #c2410c; font-weight: 950; font-size: 12.5px; letter-spacing: .2px; line-height: 1;'
	: 'display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 999px; background: linear-gradient(180deg,#ffffff 0%, rgba(255,255,255,.72) 100%); border: 1.6px solid rgba(11,46,81,.28); box-shadow: 0 12px 30px rgba(11,46,81,.14); color: #0b2e51; font-weight: 950; font-size: 12.5px; letter-spacing: .2px; line-height: 1;';

$hero_style = $is_storage
	? 'margin: 10px 0 0; border-radius: 16px; padding: 12px 14px; background: radial-gradient(140% 200% at 10% 0%,rgba(255,255,255,.55) 0%,rgba(255,255,255,0) 55%), linear-gradient(90deg,#C2410C 0%, #EA580C 55%, #F97316 100%); box-shadow: 0 14px 34px rgba(194,65,12,.22); position: relative; overflow: hidden; box-sizing: border-box;'
	: 'margin: 10px 0 0; border-radius: 16px; padding: 12px 14px; background: radial-gradient(140% 200% at 10% 0%,rgba(255,255,255,.55) 0%,rgba(255,255,255,0) 55%), linear-gradient(90deg,#0B2E51 0%, #0E497C 55%, #1C6FB0 100%); box-shadow: 0 14px 34px rgba(11,46,81,.22); position: relative; overflow: hidden; box-sizing: border-box;';

$img_url = electro_child_landing_price_card_image_url( $card );
if ( ! $img_url ) {
	$img_url = electro_child_landing_placeholder_image_url();
}

$power         = (string) ( $card['power'] ?? '' );
$list_price    = (string) ( $card['list_price'] ?? '' );
$brand_line    = (string) ( $card['brand_line'] ?? '' );
$package_label = (string) ( $card['package_label'] ?? '' );
$package_price = (string) ( $card['package_price'] ?? '' );
$package_note  = (string) ( $card['package_note'] ?? '' );
$config_html   = (string) ( $card['config_html'] ?? '' );
$output_html   = (string) ( $card['output_html'] ?? '' );
$btn_label     = (string) ( $card['btn_label'] ?? '' );
$btn_url       = electro_child_landing_esc_href( (string) ( $card['btn_url'] ?? '#' ) );
$col_id        = 'col-price-' . $index;
?>
<div id="<?php echo esc_attr( $col_id ); ?>" class="col medium-3 small-6 large-3">
	<div class="col-inner">
		<div class="icon-box featured-box icon-box-center text-center vs-price-unit">
			<div class="icon-box-img vs-price-product-img">
				<div class="icon">
					<div class="icon-inner">
						<img decoding="async" width="300" height="300" src="<?php echo esc_url( $img_url ); ?>" class="attachment-medium size-medium" alt="<?php echo esc_attr( $power ); ?>">
					</div>
				</div>
			</div>
			<div class="icon-box-text last-reset">
				<div class="text">
					<section class="vs-price-card-shell" style="font-family: Montserrat,Inter,Segoe UI,Roboto,Arial,sans-serif; padding: 0; margin: 0; box-sizing: border-box;">
						<div class="vs-price-card-body" style="width: 100%; margin: 0; padding: 0; background: #fff; box-sizing: border-box;">
							<div class="vs-price-card-inner" style="padding: 0; margin: 0;">
								<div class="vs-price-card-header" style="font-weight: 950; font-size: 17px; color: #111827; line-height: 1.15; margin: 0; text-align: center;">
									<?php if ( $power ) : ?>
										<div style="font-weight: 950; font-size: 17px; color: #111827; line-height: 1.15; margin: 0; text-align: center;"><?php echo esc_html( $power ); ?></div>
									<?php endif; ?>
									<?php if ( $list_price ) : ?>
										<div style="margin: 2px 0 0;"><span style="color: #ed1c24;"><?php echo esc_html( $list_price ); ?></span></div>
									<?php endif; ?>
									<?php if ( $brand_line ) : ?>
										<div style="font-weight: 950; font-size: 17px; color: #111827; line-height: 1.15; margin: 2px 0 0; text-align: center;"><?php echo esc_html( $brand_line ); ?></div>
									<?php endif; ?>
								</div>
								<div class="vs-price-card-tag" style="margin: 8px 0 0; display: flex; justify-content: center;">
									<span style="<?php echo esc_attr( $tag_style ); ?>">
										<span style="display: inline-flex; width: 18px; height: 18px; align-items: center; justify-content: center; border-radius: 999px; background: rgba(249,115,22,.12); border: 1px solid rgba(249,115,22,.22); line-height: 1;"><?php echo esc_html( $tag_icon ); ?></span>
										<span style="font-size: 75%; line-height: 1;"><?php echo esc_html( $tag_text ); ?></span>
									</span>
								</div>
								<div class="vs-price-card-hero" style="<?php echo esc_attr( $hero_style ); ?>">
									<div style="position: absolute; right: -60px; top: -80px; width: 180px; height: 180px; border-radius: 999px; background: radial-gradient(circle,rgba(255,255,255,.20) 0%,rgba(255,255,255,0) 62%);"></div>
									<div style="color: rgba(255,255,255,.95); position: relative; text-align: center;">
										<?php if ( $package_label ) : ?>
											<div style="font-size: 12px; font-weight: 950; letter-spacing: .2px;"><span style="font-size: 75%;"><?php echo esc_html( $package_label ); ?></span></div>
										<?php endif; ?>
										<?php if ( $package_price ) : ?>
											<div style="font-size: 26px; font-weight: 950; line-height: 1.05; margin-top: 2px;"><span style="font-size: 75%;"><?php echo esc_html( $package_price ); ?></span></div>
										<?php endif; ?>
										<?php if ( $package_note ) : ?>
											<div style="margin-top: 4px; font-size: 12.5px; font-weight: 800; opacity: .9;"><span style="font-size: 75%;"><?php echo esc_html( $package_note ); ?></span></div>
										<?php endif; ?>
									</div>
								</div>
								<?php if ( $config_html ) : ?>
									<?php echo electro_child_landing_kses_price_html( $config_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endif; ?>
								<?php if ( $output_html ) : ?>
									<?php echo electro_child_landing_kses_price_html( $output_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endif; ?>
							</div>
						</div>
						<?php if ( $btn_label ) : ?>
							<div class="vs-price-card-cta-wrap" style="width: 100%; margin: 0; padding: 0; background: #fff; box-sizing: border-box;">
								<div style="padding: 0; margin: 0;">
									<div class="vs-price-card-cta" style="padding: 12px 0 0; margin: 0;">
										<span style="font-size: 75%;">
											<a style="display: flex; justify-content: center; align-items: center; width: 100%; padding: 12px 14px; border-radius: 14px; text-decoration: none; font-weight: 950; color: #0b2e51; background: #fff; border: 1.6px solid rgba(11,46,81,.28); box-sizing: border-box;" href="<?php echo esc_url( $btn_url ); ?>"><?php echo esc_html( $btn_label ); ?></a>
										</span>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>
