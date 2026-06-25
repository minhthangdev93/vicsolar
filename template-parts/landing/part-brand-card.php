<?php
/**
 * Single brand card column.
 *
 * @package electro-child
 *
 * @var array<string, mixed> $args {
 *     @type array<string, mixed> $card  Brand card data.
 *     @type int                $index 0-based index.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$card  = isset( $args['card'] ) && is_array( $args['card'] ) ? $args['card'] : array();
$index = isset( $args['index'] ) ? (int) $args['index'] : 0;

$title     = (string) ( $card['title'] ?? '' );
$bullet_1  = (string) ( $card['bullet_1'] ?? '' );
$bullet_2  = (string) ( $card['bullet_2'] ?? '' );
$bullet_3  = (string) ( $card['bullet_3'] ?? '' );
$btn_label = (string) ( $card['btn_label'] ?? '' );
$btn_url   = electro_child_landing_esc_href( (string) ( $card['btn_url'] ?? '#' ) );
$row_group = ! empty( $card['row_group'] ) && 'storage' === $card['row_group'] ? 'storage' : 'panels';

$img_url   = electro_child_landing_brand_card_image_url( $card );
$col_class = 'storage' === $row_group ? 'medium-3 small-6 large-3' : 'medium-2 small-6 large-2';
$use_box   = ( 'panels' === $row_group && $index >= 2 ) || 'storage' === $row_group;
$col_id    = 'col-brand-' . $index;
?>
<div id="<?php echo esc_attr( $col_id ); ?>" class="col <?php echo esc_attr( $col_class ); ?>">
	<div class="col-inner">
		<?php if ( $use_box ) : ?>
			<div class="box has-hover vs-brand-unit box-text-bottom">
				<div class="box-image vs-brand-product-img">
					<div>
						<img decoding="async" loading="lazy" width="300" height="300" src="<?php echo esc_url( $img_url ); ?>" class="attachment-medium size-medium" alt="<?php echo esc_attr( wp_strip_all_tags( $title ) ); ?>">
					</div>
				</div>
				<div class="box-text text-center">
					<div class="box-text-inner">
		<?php else : ?>
			<div class="icon-box featured-box icon-box-top text-left vs-brand-unit">
				<div class="icon-box-img vs-brand-product-img">
					<div class="icon">
						<div class="icon-inner">
							<img decoding="async" loading="lazy" width="300" height="300" src="<?php echo esc_url( $img_url ); ?>" class="attachment-medium size-medium" alt="<?php echo esc_attr( wp_strip_all_tags( $title ) ); ?>">
						</div>
					</div>
				</div>
				<div class="icon-box-text last-reset">
		<?php endif; ?>

		<section style="font-family: Montserrat,Inter,Segoe UI,Roboto,Arial,sans-serif; padding: 0; margin: 0; box-sizing: border-box;">
			<div class="vs-brand-card-outer">
				<div class="vs-brand-card-shell">
					<div class="vs-brand-card">
						<div style="position: absolute; left: 10px; right: 10px; top: 10px; height: 2px; border-radius: 999px; background: linear-gradient(90deg, rgba(240,90,18,0) 0%, rgba(240,90,18,.55) 50%, rgba(240,90,18,0) 100%); opacity: .9;"></div>
						<div class="vs-brand-card-header">
							<div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
								<div style="min-width: 0;">
									<?php if ( $title ) : ?>
										<div class="vs-brand-card-title"><?php echo wp_kses_post( $title ); ?></div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="vs-brand-card-body">
							<?php foreach ( array( $bullet_1, $bullet_2, $bullet_3 ) as $bullet ) : ?>
								<?php if ( '' === trim( $bullet ) ) : ?>
									<?php continue; ?>
								<?php endif; ?>
								<div style="display: flex; gap: 10px; align-items: flex-start; margin: 8px 0; text-align: left;">
									<div style="margin-top: 5px; width: 8px; height: 8px; border-radius: 999px; background: #F05A12; flex: 0 0 8px;"></div>
									<div class="vs-brand-card-line"><?php echo electro_child_landing_kses_rich( $bullet ); ?></div>
								</div>
							<?php endforeach; ?>
						</div>
						<?php if ( $btn_label ) : ?>
							<div class="vs-brand-card-cta">
								<a style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 13px 14px; border-radius: 14px; text-decoration: none; font-weight: 950; color: #fff; background: radial-gradient(120% 160% at 15% 10%, rgba(255,255,255,.28) 0%, rgba(255,255,255,0) 55%), linear-gradient(90deg,#F05A12 0%, #FF8A2C 55%, #FFB15A 100%); box-shadow: 0 14px 34px rgba(240,90,18,.22); box-sizing: border-box;" href="<?php echo esc_url( $btn_url ); ?>">
									<span style="font-size: 75%;"><?php echo esc_html( $btn_label ); ?></span>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<?php if ( $use_box ) : ?>
					</div>
				</div>
			</div>
		<?php else : ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
