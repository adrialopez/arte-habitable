<?php
/**
 * SEO & GEO: Schema.org, Open Graph, Meta Tags
 */

// ── Meta Description ───────────────────────────
add_action('wp_head', function () {
    $desc = '';

    if (is_front_page()) {
        $desc = 'Arte Habitable — Estudio de diseño de interiores en Sant Cugat del Vallès, Barcelona. Transformamos espacios en experiencias que combinan belleza, funcionalidad y emoción.';
    } elseif (is_post_type_archive('proyecto')) {
        $desc = 'Portafolio de proyectos de diseño de interiores de Arte Habitable. Residencias, oficinas y espacios comerciales en Barcelona y alrededores.';
    } elseif (is_singular('proyecto')) {
        $type = get_post_meta(get_the_ID(), '_ah_project_type', true);
        $loc  = get_post_meta(get_the_ID(), '_ah_location', true);
        $desc = get_the_excerpt() ?: wp_trim_words(get_the_content(), 25, '...');
        if ($type || $loc) {
            $desc = $type . ($loc ? ' en ' . $loc : '') . '. ' . $desc;
        }
    }

    if ($desc) {
        echo '<meta name="description" content="' . esc_attr(wp_trim_words($desc, 30, '...')) . '">' . "\n";
    }
}, 1);

// ── Open Graph & Twitter Cards ─────────────────
add_action('wp_head', function () {
    $title = wp_get_document_title();
    $url   = is_front_page() ? home_url('/') : get_permalink();
    $desc  = '';
    $image = '';

    if (is_front_page()) {
        $desc  = 'Estudio de diseño de interiores en Sant Cugat del Vallès, Barcelona. Transformamos espacios en experiencias.';
        $slide = get_theme_mod('ah_hero_slide_1');
        if ($slide) $image = wp_get_attachment_url($slide);
    } elseif (is_post_type_archive('proyecto')) {
        $desc = 'Portafolio de proyectos de diseño de interiores de Arte Habitable.';
        $url  = get_post_type_archive_link('proyecto');
    } elseif (is_singular('proyecto')) {
        $desc  = get_the_excerpt() ?: wp_trim_words(get_the_content(), 25, '...');
        $image = get_the_post_thumbnail_url(get_the_ID(), 'ah-hero');
    }

    if (!$image && has_custom_logo()) {
        $image = wp_get_attachment_url(get_theme_mod('custom_logo'));
    }

    if ($desc) :
?>
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($desc); ?>">
    <meta property="og:url" content="<?php echo esc_url($url); ?>">
    <?php if ($image) : ?>
        <meta property="og:image" content="<?php echo esc_url($image); ?>">
    <?php endif; ?>
    <meta property="og:locale" content="es_ES">
    <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($desc); ?>">
    <?php if ($image) : ?>
        <meta name="twitter:image" content="<?php echo esc_url($image); ?>">
    <?php endif; ?>
<?php
    endif;
}, 2);

// ── Schema.org JSON-LD ─────────────────────────
add_action('wp_head', function () {
    $site_name = get_bloginfo('name');
    $site_url  = home_url('/');
    $email     = get_theme_mod('ah_email', 'info@artehabitable.es');
    $phone     = get_theme_mod('ah_phone', '');
    $ig        = get_theme_mod('ah_instagram', '');
    $address   = get_theme_mod('ah_address', 'Sant Cugat del Vallès, Barcelona');
    $logo_url  = '';
    if (has_custom_logo()) {
        $logo_url = wp_get_attachment_url(get_theme_mod('custom_logo'));
    }

    // LocalBusiness — all pages
    $business = [
        '@context'    => 'https://schema.org',
        '@type'       => ['InteriorDesigner', 'LocalBusiness'],
        'name'        => $site_name,
        'url'         => $site_url,
        'email'       => $email,
        'description' => 'Estudio de diseño de interiores en Sant Cugat del Vallès, Barcelona. Transformamos espacios en experiencias que combinan belleza, funcionalidad y emoción.',
        'address'     => [
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Sant Cugat del Vallès',
            'addressRegion'   => 'Barcelona',
            'addressCountry'  => 'ES',
        ],
        'geo' => [
            '@type'     => 'GeoCoordinates',
            'latitude'  => 41.4737,
            'longitude' => 2.0862,
        ],
        'areaServed' => [
            ['@type' => 'City', 'name' => 'Sant Cugat del Vallès'],
            ['@type' => 'City', 'name' => 'Barcelona'],
            ['@type' => 'State', 'name' => 'Cataluña'],
        ],
        'priceRange'    => '€€€',
        'knowsLanguage' => ['es', 'ca'],
    ];

    if ($phone) $business['telephone'] = $phone;
    if ($logo_url) $business['logo'] = $logo_url;
    if ($ig) $business['sameAs'] = [$ig];

    $fb = get_theme_mod('ah_facebook');
    $li = get_theme_mod('ah_linkedin');
    if ($fb) $business['sameAs'][] = $fb;
    if ($li) $business['sameAs'][] = $li;

    $schemas = [$business];

    // CollectionPage — Archive
    if (is_post_type_archive('proyecto')) {
        $collection = [
            '@context'    => 'https://schema.org',
            '@type'       => 'CollectionPage',
            'name'        => 'Proyectos — ' . $site_name,
            'description' => 'Portafolio de proyectos de diseño de interiores.',
            'url'         => get_post_type_archive_link('proyecto'),
        ];
        $schemas[] = $collection;
    }

    // CreativeWork — Single Proyecto
    if (is_singular('proyecto')) {
        $type     = get_post_meta(get_the_ID(), '_ah_project_type', true);
        $location = get_post_meta(get_the_ID(), '_ah_location', true);
        $gallery  = ah_get_project_gallery();
        $images   = [];
        foreach ($gallery as $img_id) {
            $url = wp_get_attachment_url($img_id);
            if ($url) $images[] = $url;
        }
        $thumb = get_the_post_thumbnail_url(get_the_ID(), 'ah-hero');
        if ($thumb) array_unshift($images, $thumb);

        $work = [
            '@context'    => 'https://schema.org',
            '@type'       => 'CreativeWork',
            'name'        => get_the_title(),
            'description' => wp_trim_words(get_the_content(), 40, '...'),
            'url'         => get_permalink(),
            'creator'     => [
                '@type' => 'Organization',
                'name'  => $site_name,
            ],
        ];
        if ($type) $work['genre'] = $type;
        if ($location) $work['locationCreated'] = [
            '@type' => 'Place',
            'name'  => $location,
        ];
        if ($images) $work['image'] = $images;

        // BreadcrumbList
        $breadcrumb = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type'    => 'ListItem',
                    'position' => 1,
                    'name'     => 'Inicio',
                    'item'     => $site_url,
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => 'Proyectos',
                    'item'     => get_post_type_archive_link('proyecto'),
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 3,
                    'name'     => get_the_title(),
                ],
            ],
        ];

        $schemas[] = $work;
        $schemas[] = $breadcrumb;
    }

    foreach ($schemas as $schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
}, 5);

// ── Canonical URLs ─────────────────────────────
add_action('wp_head', function () {
    if (is_singular()) {
        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">' . "\n";
    } elseif (is_post_type_archive('proyecto')) {
        echo '<link rel="canonical" href="' . esc_url(get_post_type_archive_link('proyecto')) . '">' . "\n";
    } elseif (is_front_page()) {
        echo '<link rel="canonical" href="' . esc_url(home_url('/')) . '">' . "\n";
    }
}, 1);

// ── GEO Meta Tags ──────────────────────────────
add_action('wp_head', function () {
    if (is_front_page() || is_post_type_archive('proyecto')) :
?>
    <meta name="geo.region" content="ES-CT">
    <meta name="geo.placename" content="Sant Cugat del Vallès">
    <meta name="geo.position" content="41.4737;2.0862">
    <meta name="ICBM" content="41.4737, 2.0862">
<?php
    endif;
}, 1);
