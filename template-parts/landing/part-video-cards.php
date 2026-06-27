<?php
/**
 * YouTube video cards slider (section 09 — Điện mặt trời nhà xưởng).
 *
 * Expects $args['videos'] (array of ['embed','title']) and optional $args['aria_label'].
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$videos     = isset( $args['videos'] ) && is_array( $args['videos'] ) ? $args['videos'] : array();
$aria_label = ! empty( $args['aria_label'] ) ? (string) $args['aria_label'] : __( 'Video', 'electro-child' );

if ( empty( $videos ) ) {
	return;
}

$video_count = count( $videos );
?>
<div class="vs-factory-slider vs-video-slider" tabindex="0" aria-label="<?php echo esc_attr( $aria_label ); ?>" data-post-count="<?php echo esc_attr( (string) $video_count ); ?>">
	<button type="button" class="vs-factory-slider__nav vs-factory-slider__nav--prev" aria-label="<?php esc_attr_e( 'Xem video trước', 'electro-child' ); ?>">
		<span aria-hidden="true">‹</span>
	</button>
	<div class="vs-factory-grid">
		<?php
		foreach ( $videos as $index => $video ) :
			$embed = isset( $video['embed'] ) ? (string) $video['embed'] : '';
			if ( '' === $embed ) {
				continue;
			}
			$title = isset( $video['title'] ) ? trim( (string) $video['title'] ) : '';
			/* translators: %d: video number. */
			$frame_title = '' !== $title ? $title : sprintf( __( 'Video %d', 'electro-child' ), (int) $index + 1 );
			$loading     = 0 === $index ? 'eager' : 'lazy';
			?>
			<article class="vs-factory-card vs-video-card">
				<div class="vs-factory-card-media vs-video-card-media">
					<iframe
						loading="<?php echo esc_attr( $loading ); ?>"
						src="<?php echo esc_url( $embed ); ?>"
						title="<?php echo esc_attr( $frame_title ); ?>"
						allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
						allowfullscreen
					></iframe>
				</div>
				<?php if ( '' !== $title ) : ?>
					<div class="vs-factory-card-body vs-video-card-body">
						<h3 class="vs-factory-card-title vs-video-card-title"><?php echo esc_html( $title ); ?></h3>
					</div>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>
	<button type="button" class="vs-factory-slider__nav vs-factory-slider__nav--next" aria-label="<?php esc_attr_e( 'Xem video tiếp', 'electro-child' ); ?>">
		<span aria-hidden="true">›</span>
	</button>
</div>
