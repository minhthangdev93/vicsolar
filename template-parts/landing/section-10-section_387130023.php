<?php
/**
 * Landing section 10 — brands + long article.
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section" id="section_387130023">
	<div class="section-bg fill"></div>
	<div class="section-content relative">
		<div class="row" id="row-1792450912">
			<div id="col-1032472731" class="col small-12 large-12">
				<div class="col-inner">
					<section class="vns-a">
						<?php get_template_part( 'template-parts/landing/part', 'article-nav' ); ?>
						<?php get_template_part( 'template-parts/landing/part', 'article-block-systems' ); ?>
						<?php get_template_part( 'template-parts/landing/part', 'brands-block' ); ?>
						<?php get_template_part( 'template-parts/landing/part', 'article-block-components' ); ?>
						<?php get_template_part( 'template-parts/landing/part', 'article-block-panels' ); ?>
						<?php get_template_part( 'template-parts/landing/part', 'article-block-inverters' ); ?>
						<?php get_template_part( 'template-parts/landing/part', 'article-block-storage' ); ?>
						<?php get_template_part( 'template-parts/landing/part', 'article-block-choose' ); ?>
					</section>
				</div>
			</div>
		</div>
	</div>
	<style>
		#section_387130023 {
			padding-top: 30px;
			padding-bottom: 30px;
		}
	</style>
</section>
