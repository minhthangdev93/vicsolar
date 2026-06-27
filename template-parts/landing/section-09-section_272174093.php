<?php
/**
 * Landing section: Điện mặt trời nhà xưởng (tin-tuc) + Dự án tiêu biểu (du-an-da-lam).
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$factory_videos = electro_child_landing_get_factory_videos();
$projects_query = electro_child_landing_projects_posts_query();
$s09_bg_url     = electro_child_landing_image_url( 's09_bg_image' );

$s09_badge_icon          = electro_child_landing_get( 's09_badge_icon' );
$s09_badge_text          = electro_child_landing_get( 's09_badge_text' );
$s09_title               = electro_child_landing_get( 's09_title' );
$s09_subtitle_1          = electro_child_landing_get( 's09_subtitle_1' );
$s09_subtitle_2          = electro_child_landing_get( 's09_subtitle_2' );
$s09_factory_aria_label  = electro_child_landing_get( 's09_factory_aria_label' );
$s09_projects_title      = electro_child_landing_get( 's09_projects_title' );
$s09_projects_subtitle   = electro_child_landing_get( 's09_projects_subtitle' );
$s09_projects_aria_label = electro_child_landing_get( 's09_projects_aria_label' );
$s09_card_link_label     = electro_child_landing_get( 's09_card_link_label' );
?>
<section class="section vs-factory-section" id="section_272174093">
	<div class="section-bg fill">
		<?php if ( $s09_bg_url ) : ?>
			<img
				loading="lazy"
				decoding="async"
				width="1536"
				height="1024"
				src="<?php echo esc_url( $s09_bg_url ); ?>"
				class="bg attachment-original size-original"
				alt=""
			>
		<?php endif; ?>
		<div class="section-bg-overlay absolute fill"></div>
	</div>

	<div class="section-content relative">
		<header class="vs-factory-header">
			<div class="vs-factory-header-bar">
				<div class="vs-factory-header-inner">
					<span class="vs-factory-badge">
						<?php if ( $s09_badge_icon ) : ?>
							<span class="vs-factory-badge-icon" aria-hidden="true"><?php echo electro_child_landing_kses_content( $s09_badge_icon ); ?></span>
						<?php endif; ?>
						<?php if ( $s09_badge_text ) : ?>
							<span class="vs-factory-badge-text"><?php echo electro_child_landing_kses_content( $s09_badge_text ); ?></span>
						<?php endif; ?>
					</span>
					<?php if ( $s09_title ) : ?>
					<h2 class="vs-factory-title">
						<span class="vs-factory-title-line"><?php echo electro_child_landing_kses_content( $s09_title ); ?></span>
					</h2>
					<?php endif; ?>
					<p class="vs-factory-subtitle">
						<?php if ( $s09_subtitle_1 ) : ?>
						<span class="vs-factory-subtitle-line"><?php echo electro_child_landing_kses_content( $s09_subtitle_1 ); ?></span>
						<?php endif; ?>
						<?php if ( $s09_subtitle_2 ) : ?>
						<span class="vs-factory-subtitle-line"><?php echo electro_child_landing_kses_content( $s09_subtitle_2 ); ?></span>
						<?php endif; ?>
					</p>
				</div>
			</div>
		</header>

		<div class="row row-full-width vs-factory-wrap vs-factory-wrap--news vs-factory-wrap--videos" id="row-factory-tin-tuc">
			<div class="col small-12 large-12">
				<div class="col-inner">
					<?php
					get_template_part(
						'template-parts/landing/part',
						'video-cards',
						array(
							'videos'     => $factory_videos,
							'aria_label' => $s09_factory_aria_label,
						)
					);
					?>
				</div>
			</div>
		</div>

		<div class="vs-factory-block-divider" aria-hidden="true">
			<span class="vs-factory-block-divider__line"></span>
			<span class="vs-factory-block-divider__mark"></span>
			<span class="vs-factory-block-divider__line"></span>
		</div>

		<div class="vs-factory-projects-intro">
			<?php if ( $s09_projects_title ) : ?>
			<h3 class="vs-factory-projects-title"><?php echo electro_child_landing_kses_content( $s09_projects_title ); ?></h3>
			<?php endif; ?>
			<?php if ( $s09_projects_subtitle ) : ?>
			<p class="vs-factory-projects-subtitle"><?php echo electro_child_landing_kses_content( $s09_projects_subtitle ); ?></p>
			<?php endif; ?>
		</div>

		<div class="row row-full-width vs-factory-wrap vs-factory-wrap--projects" id="row-factory-du-an">
			<div class="col small-12 large-12">
				<div class="col-inner">
					<?php
					get_template_part(
						'template-parts/landing/part',
						'factory-post-cards',
						array(
							'factory_cards_query'      => $projects_query,
							'factory_cards_aria_label' => $s09_projects_aria_label,
							'factory_card_link_label'  => $s09_card_link_label,
						)
					);
					?>
				</div>
			</div>
		</div>
	</div>
</section>
