<?php
// Collect all projects into an array first
$projects = ah_get_projects(-1);
if (!$projects->have_posts()) return;

$all_projects = [];
while ($projects->have_posts()) : $projects->the_post();
    $gallery_ids  = ah_get_project_gallery();
    $gallery_urls = [];
    foreach ($gallery_ids as $gid) {
        $url = wp_get_attachment_image_url($gid, 'ah-project-gallery');
        if ($url) $gallery_urls[] = $url;
    }
    $terms = get_the_terms(get_the_ID(), 'tipo_proyecto');
    $type  = get_post_meta(get_the_ID(), '_ah_project_type', true);
    $loc   = get_post_meta(get_the_ID(), '_ah_location', true);

    $all_projects[] = [
        'title'       => get_the_title(),
        'permalink'   => get_permalink(),
        'thumbnail'   => get_the_post_thumbnail_url(get_the_ID(), 'full'),
        'type'        => $type,
        'location'    => $loc,
        'location_str'=> $type . ($loc ? ' · ' . $loc : ''),
        'description' => wp_strip_all_tags(get_the_content()),
        'gallery'     => $gallery_urls,
        'tag'         => $terms ? $terms[0]->name : '',
    ];
endwhile;
wp_reset_postdata();

$count        = count($all_projects);
$count_padded = str_pad($count, 2, '0', STR_PAD_LEFT);
?>

<div class="portafolio-wrapper" style="--slide-count: <?php echo $count; ?>" id="portafolio">
    <section class="portafolio section" aria-label="Portafolio">

        <!-- Top chrome: label + story bars + counter -->
        <div class="portafolio__chrome">
            <span class="portafolio__chrome-label section__label">Portafolio</span>

            <div class="portafolio__stories" aria-hidden="true">
                <?php for ($i = 0; $i < $count; $i++) : ?>
                    <div class="portafolio__story">
                        <div class="portafolio__story-fill"></div>
                    </div>
                <?php endfor; ?>
            </div>

            <div class="portafolio__counter" aria-live="polite">
                <span class="portafolio__counter-current">01</span>
                <span class="portafolio__counter-sep"> / </span>
                <span class="portafolio__counter-total"><?php echo $count_padded; ?></span>
            </div>
        </div>

        <!-- Cards area: clips overflow + centers cards vertically -->
        <div class="portafolio__cards-area">

            <!-- Navigation arrows -->
            <button class="portafolio__nav portafolio__nav--prev disabled" aria-label="Proyecto anterior">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button class="portafolio__nav portafolio__nav--next" aria-label="Proyecto siguiente">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>

            <!-- Slides track -->
            <div class="projects-track">
                <?php foreach ($all_projects as $i => $proj) : ?>
                    <article class="project-slide"
                        data-title="<?php echo esc_attr($proj['title']); ?>"
                        data-location="<?php echo esc_attr($proj['location_str']); ?>"
                        data-description="<?php echo esc_attr($proj['description']); ?>"
                        data-gallery="<?php echo esc_attr(wp_json_encode($proj['gallery'])); ?>"
                        data-permalink="<?php echo esc_url($proj['permalink']); ?>">

                        <div class="project-slide__bg">
                            <?php if ($proj['thumbnail']) : ?>
                                <img src="<?php echo esc_url($proj['thumbnail']); ?>"
                                     alt="<?php echo esc_attr($proj['title']); ?>"
                                     loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>">
                            <?php endif; ?>
                        </div>
                        <div class="project-slide__overlay"></div>

                        <div class="project-slide__info">
                            <?php if ($proj['tag']) : ?>
                                <span class="project-slide__tag"><?php echo esc_html($proj['tag']); ?></span>
                            <?php endif; ?>
                            <h3 class="project-slide__title"><?php echo esc_html($proj['title']); ?></h3>
                            <?php if ($proj['location_str']) : ?>
                                <p class="project-slide__location"><?php echo esc_html($proj['location_str']); ?></p>
                            <?php endif; ?>
                            <div class="project-slide__actions">
                                <button class="project-slide__quickview btn-quickview">Vista rápida</button>
                                <a href="<?php echo esc_url($proj['permalink']); ?>" class="project-slide__link">
                                    Ver proyecto
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

        </div><!-- /.portafolio__cards-area -->

        <!-- Explorar todos los proyectos -->
        <div class="portafolio__more">
            <a href="<?php echo esc_url(get_post_type_archive_link('proyecto')); ?>" class="portafolio__more-btn">
                <span>Explorar todos los proyectos</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

    </section>
</div>
