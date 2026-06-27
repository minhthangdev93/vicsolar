<?php
/**
 * Article — quick nav pills.
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nav_items = electro_child_landing_article_nav_links();
?>
<nav class="vns-nav" aria-label="Điều hướng nhanh">
	<div class="vns-nav-title">📚 <?php echo esc_html__( 'Nội dung bài viết', 'electro-child' ); ?></div>
	<?php if ( ! empty( $nav_items ) ) : ?>
		<div class="vns-nav-pills">
			<?php foreach ( $nav_items as $item ) : ?>
				<?php
				$label = (string) ( $item['label'] ?? '' );
				$href  = electro_child_landing_esc_href( (string) ( $item['href'] ?? '#' ) );
				if ( '' === trim( $label ) ) {
					continue;
				}
				?>
				<a class="vns-nav-pill" href="<?php echo esc_url( $href ); ?>"><?php echo electro_child_landing_kses_content( $label ); ?></a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</nav>
