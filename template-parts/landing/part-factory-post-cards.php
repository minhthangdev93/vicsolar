<?php
/**
 * Factory / project post cards slider loop.
 *
 * Expects $factory_cards_query (WP_Query) and optional $factory_cards_aria_label (string).
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $factory_cards_query ) && isset( $args ) && is_array( $args ) && ! empty( $args['factory_cards_query'] ) ) {
	$factory_cards_query = $args['factory_cards_query'];
}

if ( empty( $factory_cards_aria_label ) && isset( $args ) && is_array( $args ) && ! empty( $args['factory_cards_aria_label'] ) ) {
	$factory_cards_aria_label = $args['factory_cards_aria_label'];
}

if ( empty( $factory_card_link_label ) && isset( $args ) && is_array( $args ) && ! empty( $args['factory_card_link_label'] ) ) {
	$factory_card_link_label = $args['factory_card_link_label'];
}

if ( empty( $factory_cards_query ) || ! $factory_cards_query instanceof WP_Query || ! $factory_cards_query->have_posts() ) {
	return;
}

$factory_cards_aria_label = ! empty( $factory_cards_aria_label ) ? $factory_cards_aria_label : __( 'Bài viết', 'electro-child' );
$factory_card_link_label  = ! empty( $factory_card_link_label ) ? $factory_card_link_label : __( 'Xem chi tiết', 'electro-child' );
$post_count               = (int) $factory_cards_query->post_count;
?>
<div class="vs-factory-slider" tabindex="0" aria-label="<?php echo esc_attr( $factory_cards_aria_label ); ?>" data-post-count="<?php echo esc_attr( (string) $post_count ); ?>">
	<button type="button" class="vs-factory-slider__nav vs-factory-slider__nav--prev" aria-label="<?php esc_attr_e( 'Xem bài trước', 'electro-child' ); ?>">
		<span aria-hidden="true">‹</span>
	</button>
	<div class="vs-factory-grid">
		<?php
		$card_index = 0;
		while ( $factory_cards_query->have_posts() ) :
			$factory_cards_query->the_post();
			$permalink = get_permalink();
			$excerpt   = electro_child_landing_card_excerpt( get_the_ID() );
			$loading   = 0 === $card_index ? 'eager' : 'lazy';
			?>
			<article <?php post_class( 'vs-factory-card' ); ?>>
				<a class="vs-factory-card-media" href="<?php echo esc_url( $permalink ); ?>" aria-label="<?php the_title_attribute(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php
						$thumb_attrs = array(
							'class'    => 'vs-factory-card-img',
							'loading'  => $loading,
							'decoding' => 'async',
						);
						the_post_thumbnail( 'medium_large', $thumb_attrs );
						?>
					<?php else : ?>
						<span class="vs-factory-card-placeholder" aria-hidden="true"></span>
					<?php endif; ?>
				</a>
				<div class="vs-factory-card-body">
					<h3 class="vs-factory-card-title">
						<a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a>
					</h3>
					<?php if ( $excerpt ) : ?>
						<p class="vs-factory-card-excerpt"><?php echo esc_html( $excerpt ); ?></p>
					<?php endif; ?>
					<a class="vs-factory-card-link" href="<?php echo esc_url( $permalink ); ?>">
						<?php echo electro_child_landing_kses_content( $factory_card_link_label ); ?>
						<span aria-hidden="true">→</span>
					</a>
				</div>
			</article>
			<?php
			++$card_index;
		endwhile;
		wp_reset_postdata();
		?>
	</div>
	<button type="button" class="vs-factory-slider__nav vs-factory-slider__nav--next" aria-label="<?php esc_attr_e( 'Xem bài tiếp', 'electro-child' ); ?>">
		<span aria-hidden="true">›</span>
	</button>
</div>
