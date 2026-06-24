<?php
/**
 * Landing — TOP thương hiệu điện mặt trời (section 10).
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title_1     = electro_child_landing_get( 's10_title_line_1' );
$title_2     = electro_child_landing_get( 's10_title_line_2' );
$subtitle_1  = electro_child_landing_get( 's10_subtitle' );
$subtitle_2  = electro_child_landing_get( 's10_subtitle_2' );
$all_cards   = electro_child_landing_get_brand_cards();
$panel_cards = array();
$storage_cards = array();

foreach ( $all_cards as $index => $card ) {
	$group = ! empty( $card['row_group'] ) && 'storage' === $card['row_group'] ? 'storage' : 'panels';
	if ( 'storage' === $group ) {
		$storage_cards[] = array(
			'card'  => $card,
			'index' => $index,
		);
	} else {
		$panel_cards[] = array(
			'card'  => $card,
			'index' => $index,
		);
	}
}
?>
<div class="vs-brands-block">
	<section class="vs-brands-title-block">
		<div class="vs-brands-title-block__inner">
			<div class="vs-brands-title-block__heading">
				<?php if ( $title_1 ) : ?>
					<span><?php echo esc_html( $title_1 ); ?></span><br>
				<?php endif; ?>
				<?php if ( $title_2 ) : ?>
					<span><?php echo esc_html( $title_2 ); ?></span>
				<?php endif; ?>
			</div>
			<div class="vs-brands-title-block__underline"></div>
		</div>
	</section>

	<?php if ( $subtitle_1 ) : ?>
		<section class="vs-brands-subtitle-block">
			<div class="vs-brands-subtitle-block__inner">
				<div class="vs-brands-subtitle-block__text"><?php echo esc_html( $subtitle_1 ); ?></div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $panel_cards ) ) : ?>
		<div class="row row-collapse row-full-width align-center row-divided row-box-shadow-1 row-box-shadow-5-hover vs-brand-row" id="row-1565098982">
			<?php
			foreach ( $panel_cards as $item ) {
				get_template_part(
					'template-parts/landing/part',
					'brand-card',
					array(
						'card'  => $item['card'],
						'index' => $item['index'],
					)
				);
			}
			?>
		</div>
	<?php endif; ?>

	<?php if ( $subtitle_2 ) : ?>
		<section class="vs-brands-subtitle-block">
			<div class="vs-brands-subtitle-block__inner">
				<div class="vs-brands-subtitle-block__text"><?php echo esc_html( $subtitle_2 ); ?></div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( ! empty( $storage_cards ) ) : ?>
		<div class="row row-collapse align-equal row-divided row-box-shadow-1 row-box-shadow-5-hover vs-brand-row" id="row-176769952">
			<?php
			foreach ( $storage_cards as $item ) {
				get_template_part(
					'template-parts/landing/part',
					'brand-card',
					array(
						'card'  => $item['card'],
						'index' => $item['index'],
					)
				);
			}
			?>
		</div>
	<?php endif; ?>
</div>
