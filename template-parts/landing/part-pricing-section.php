<?php
/**
 * Pricing section (04–08) from ACF.
 *
 * @package electro-child
 *
 * @var array<string, mixed> $args {
 *     @type string $prefix             s04–s08.
 *     @type string $section_id         HTML section id.
 *     @type string $row_id             Price row id.
 *     @type bool   $show_shared_header Section 04 shared header.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$prefix             = isset( $args['prefix'] ) ? (string) $args['prefix'] : 's04';
$section_id         = isset( $args['section_id'] ) ? (string) $args['section_id'] : '';
$row_id             = isset( $args['row_id'] ) ? (string) $args['row_id'] : '';
$show_shared_header = ! empty( $args['show_shared_header'] );

$cards       = electro_child_landing_get_price_cards( $prefix );
$tier_title  = electro_child_landing_get( $prefix . '_tier_title' );
$tier_sub    = electro_child_landing_get( $prefix . '_tier_subtitle' );
$pricing_badge = electro_child_landing_get( $prefix . '_pricing_badge' );
$pricing_title = electro_child_landing_get( $prefix . '_pricing_title' );
$pricing_sub   = electro_child_landing_get( $prefix . '_pricing_subtitle' );
?>
<section class="section" id="<?php echo esc_attr( $section_id ); ?>">
	<div class="section-bg fill"></div>
	<div class="section-content relative">
		<?php if ( $show_shared_header ) : ?>
			<section style="font-family: Montserrat,Inter,Segoe UI,Roboto,Arial,sans-serif; width: 100%; padding: 18px 16px 12px; margin: 0; box-sizing: border-box; background: transparent; text-align: center;">
				<?php if ( $pricing_badge ) : ?>
					<div style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 14px; border-radius: 999px; background: rgba(255,120,40,.08); border: 1.2px solid rgba(255,120,40,.32); box-shadow: 0 10px 26px rgba(0,0,0,.05); color: #ff6a1a; font-weight: 850; font-size: 13px; line-height: 1; box-sizing: border-box; margin: 0 0 10px;">
						<span style="font-size: 14px; line-height: 1;"><?php echo esc_html( $pricing_badge ); ?></span>
					</div>
				<?php endif; ?>
				<?php if ( $pricing_title ) : ?>
					<h2 style="margin: 0 0 8px; font-size: clamp(22px,3.1vw,44px); font-weight: 950; letter-spacing: -.4px; line-height: 1.12; background: linear-gradient(90deg,#FFB15A 0%, #FF7A1A 45%, #EE5E09 100%); -webkit-background-clip: text; background-clip: text; color: transparent; text-shadow: 0 14px 34px rgba(238,94,9,.14);"><?php echo esc_html( $pricing_title ); ?></h2>
				<?php endif; ?>
				<div style="width: 140px; height: 4px; margin: 0 auto 10px; border-radius: 999px; background: linear-gradient(90deg,#FFB15A,#FF7A1A,#EE5E09); box-shadow: 0 12px 28px rgba(238,94,9,.16);"></div>
				<?php if ( $pricing_sub ) : ?>
					<div style="margin: 0; font-size: clamp(13px,1.25vw,17px); font-weight: 550; line-height: 1.55; color: rgba(11,19,32,.82);"><?php echo esc_html( $pricing_sub ); ?></div>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<section style="font-family: Montserrat,Inter,Segoe UI,Roboto,Arial,sans-serif; padding: 0; margin: 0; box-sizing: border-box; background: transparent;">
			<div style="width: 100%; background: transparent; padding: 10px 16px; margin: 0; box-sizing: border-box; text-align: center;">
				<?php if ( $show_shared_header ) : ?>
					<?php if ( $tier_title ) : ?>
						<h3 style="margin: 0; padding: 0; font-size: 28px; line-height: 1.08; font-weight: 950; color: #f05a12; letter-spacing: .2px; text-transform: uppercase;"><?php echo esc_html( $tier_title ); ?></h3>
					<?php endif; ?>
					<?php if ( $tier_sub ) : ?>
						<div style="margin-top: 6px; font-size: 14px; line-height: 1.35; font-weight: 800; color: rgba(240,90,18,.92);"><?php echo esc_html( $tier_sub ); ?></div>
					<?php endif; ?>
				<?php else : ?>
					<?php if ( $tier_title ) : ?>
						<div style="margin: 0; font-size: clamp(13px,1.25vw,17px); font-weight: 550; line-height: 1.55; color: rgba(11,19,32,.82);">
							<span style="color: #f05a12; font-size: 28px; font-weight: 950; letter-spacing: 0.2px; text-transform: uppercase; background-color: transparent;"><?php echo esc_html( $tier_title ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( $tier_sub ) : ?>
						<div style="margin: 0; font-size: clamp(13px,1.25vw,17px); font-weight: 550; line-height: 1.55; color: rgba(11,19,32,.82);">
							<span style="color: rgba(240, 90, 18, 0.92); font-size: 14px; font-weight: 800; background-color: transparent;"><?php echo esc_html( $tier_sub ); ?></span>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</section>

		<div class="row row-collapse align-equal row-divided row-box-shadow-1 row-box-shadow-5-hover vs-price-row" id="<?php echo esc_attr( $row_id ); ?>">
			<?php
			foreach ( $cards as $index => $card ) {
				get_template_part(
					'template-parts/landing/part',
					'pricing-card',
					array(
						'card'  => $card,
						'index' => $index,
					)
				);
			}
			?>
		</div>
	</div>
</section>
