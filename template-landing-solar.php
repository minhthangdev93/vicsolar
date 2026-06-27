<?php
/**
 * Template Name: Landing — Lắp điện mặt trời
 * Template Post Type: page
 *
 * Clone layout/content from vietnamsolar.vn/lap-dat-dien-mat-troi/
 *
 * @package electro-child
 */

remove_action( 'electro_content_top', 'electro_breadcrumb', 10 );

electro_get_header();
?>

<div id="primary" class="content-area page-template-landing-solar">
	<main id="main" class="site-main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'vs-landing-article' ); ?>>

				<div id="vs-landing-content" role="main" class="vs-landing-solar vs-flatsome-shim">

					<?php
					foreach ( electro_child_landing_solar_sections() as $section_slug ) {
						get_template_part( 'template-parts/landing/' . $section_slug );

						// Bỏ 2 nút Gọi ngay / Nhắn Zalo ngay sau section 01.
						if ( 'section-01-section_623584978' === $section_slug ) {
							continue;
						}

						get_template_part( 'template-parts/landing/part', 'section-contact-cta' );
					}
					?>

				</div>

			</article>

		<?php endwhile; ?>

	</main>
</div>

<?php
get_footer();
