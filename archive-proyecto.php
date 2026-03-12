<?php
/**
 * Template: Archive Proyectos — Portfolio Page
 */
get_header();

$terms = get_terms([
    'taxonomy'   => 'tipo_proyecto',
    'hide_empty' => true,
]);

$projects = ah_get_projects(-1);
?>

<section class="archive-hero">
    <div class="archive-hero__bg"></div>
    <div class="archive-hero__overlay"></div>
    <div class="archive-hero__content">
        <div class="container">
            <span class="archive-hero__label">Portafolio</span>
            <h1 class="archive-hero__title">Nuestros<br>Proyectos</h1>
            <p class="archive-hero__text">Cada espacio tiene una historia. Aquí están las que hemos ayudado a contar.</p>
        </div>
    </div>
    <div class="archive-hero__scroll">
        <span>Descubre</span>
        <div class="archive-hero__scroll-line"></div>
    </div>
</section>

<?php if ($projects->have_posts()) : ?>
<section class="archive-projects">
    <div class="container">

        <?php if (!empty($terms) && !is_wp_error($terms) && count($terms) > 1) : ?>
        <div class="archive-filters reveal">
            <button class="archive-filter active" data-filter="all">Todos</button>
            <?php foreach ($terms as $term) : ?>
                <button class="archive-filter" data-filter="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="archive-grid">
            <?php while ($projects->have_posts()) : $projects->the_post();
                $location    = get_post_meta(get_the_ID(), '_ah_location', true);
                $type        = get_post_meta(get_the_ID(), '_ah_project_type', true);
                $gallery_ids = ah_get_project_gallery();
                $terms_proj  = get_the_terms(get_the_ID(), 'tipo_proyecto');
                $tag         = $terms_proj ? $terms_proj[0]->name : '';
                $slug        = $terms_proj ? $terms_proj[0]->slug : '';

                $gallery_urls = [];
                foreach ($gallery_ids as $gid) {
                    $url = wp_get_attachment_image_url($gid, 'ah-project-gallery');
                    if ($url) $gallery_urls[] = $url;
                }
            ?>
                <article class="archive-card reveal"
                    data-category="<?php echo esc_attr($slug); ?>"
                    data-title="<?php echo esc_attr(get_the_title()); ?>"
                    data-location="<?php echo esc_attr($type . ($location ? ' · ' . $location : '')); ?>"
                    data-description="<?php echo esc_attr(wp_strip_all_tags(get_the_content())); ?>"
                    data-gallery="<?php echo esc_attr(wp_json_encode($gallery_urls)); ?>"
                    data-permalink="<?php echo esc_url(get_permalink()); ?>">
                    <div class="archive-card__image">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('ah-project-thumb', ['loading' => 'lazy', 'alt' => get_the_title()]); ?>
                        <?php endif; ?>
                        <div class="archive-card__overlay">
                            <div class="archive-card__actions">
                                <span class="archive-card__quick">Vista rápida</span>
                                <a href="<?php the_permalink(); ?>" class="archive-card__detail" onclick="event.stopPropagation();">Ver proyecto</a>
                            </div>
                        </div>
                    </div>
                    <div class="archive-card__info">
                        <h2 class="archive-card__title"><?php the_title(); ?></h2>
                        <?php if ($type || $location) : ?>
                            <p class="archive-card__meta"><?php echo esc_html($type); ?><?php if ($location) echo ' &middot; ' . esc_html($location); ?></p>
                        <?php endif; ?>
                        <?php if ($tag) : ?>
                            <span class="archive-card__tag"><?php echo esc_html($tag); ?></span>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

    </div>
</section>
<?php endif; ?>

<!-- Project Modal (same as front page) -->
<div class="modal" id="projectModal">
    <button class="modal__close" aria-label="Cerrar">&times;</button>
    <div class="modal__content">
        <div class="modal__info" id="modalInfo"></div>
        <div class="modal__gallery" id="modalGallery"></div>
    </div>
</div>

<?php get_footer(); ?>
