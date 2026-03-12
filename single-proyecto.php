<?php
/**
 * Template: Single Proyecto
 */
get_header();

$location = get_post_meta(get_the_ID(), '_ah_location', true);
$type     = get_post_meta(get_the_ID(), '_ah_project_type', true);
$gallery  = ah_get_project_gallery();
$terms    = get_the_terms(get_the_ID(), 'tipo_proyecto');
$tag      = $terms ? $terms[0]->name : '';
?>

<article class="single-proyecto">
    <!-- Breadcrumbs -->
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <div class="container">
            <a href="<?php echo esc_url(home_url('/')); ?>">Inicio</a>
            <span class="breadcrumbs__sep">/</span>
            <a href="<?php echo esc_url(get_post_type_archive_link('proyecto')); ?>">Proyectos</a>
            <span class="breadcrumbs__sep">/</span>
            <span class="breadcrumbs__current"><?php the_title(); ?></span>
        </div>
    </nav>

    <!-- Hero -->
    <div class="proyecto-hero">
        <?php if (has_post_thumbnail()) : ?>
            <div class="proyecto-hero__img">
                <?php the_post_thumbnail('ah-hero'); ?>
            </div>
        <?php endif; ?>
        <div class="proyecto-hero__overlay"></div>
        <div class="proyecto-hero__content">
            <div class="container">
                <h1 class="proyecto-hero__title"><?php the_title(); ?></h1>
                <?php if ($type || $location) : ?>
                    <p class="proyecto-hero__meta">
                        <?php echo esc_html($type); ?>
                        <?php if ($location) echo ' &middot; ' . esc_html($location); ?>
                    </p>
                <?php endif; ?>
                <?php if ($tag) : ?>
                    <span class="proyecto-hero__tag"><?php echo esc_html($tag); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="proyecto-content">
        <div class="container">
            <div class="proyecto-content__text">
                <?php the_content(); ?>
            </div>
        </div>
    </div>

    <!-- Gallery -->
    <?php if (!empty($gallery)) : ?>
        <div class="proyecto-gallery">
            <div class="container">
                <div class="proyecto-gallery__grid">
                    <?php foreach ($gallery as $img_id) :
                        $img = wp_get_attachment_image($img_id, 'ah-project-gallery', false, ['loading' => 'lazy']);
                        if (!$img) continue;
                    ?>
                        <div class="proyecto-gallery__item">
                            <?php echo $img; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Navigation -->
    <div class="proyecto-nav">
        <div class="container">
            <div class="proyecto-nav__grid">
                <?php
                $prev = get_previous_post();
                $next = get_next_post();
                ?>
                <?php if ($prev) : ?>
                    <a href="<?php echo esc_url(get_permalink($prev)); ?>" class="proyecto-nav__link proyecto-nav__link--prev">
                        <span class="proyecto-nav__label">&larr; Anterior</span>
                        <span class="proyecto-nav__name"><?php echo esc_html($prev->post_title); ?></span>
                    </a>
                <?php else : ?>
                    <div></div>
                <?php endif; ?>
                <a href="<?php echo esc_url(get_post_type_archive_link('proyecto')); ?>" class="proyecto-nav__back">Todos los proyectos</a>
                <?php if ($next) : ?>
                    <a href="<?php echo esc_url(get_permalink($next)); ?>" class="proyecto-nav__link proyecto-nav__link--next">
                        <span class="proyecto-nav__label">Siguiente &rarr;</span>
                        <span class="proyecto-nav__name"><?php echo esc_html($next->post_title); ?></span>
                    </a>
                <?php else : ?>
                    <div></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>

<?php get_footer(); ?>
