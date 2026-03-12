<?php
/**
 * Customizer: Editable content for the front page
 */

add_action('customize_register', function ($wp_customize) {

    // ── Hero Section ────────────────────────
    $wp_customize->add_section('ah_hero', [
        'title'    => 'Hero / Portada',
        'priority' => 30,
    ]);

    $wp_customize->add_setting('ah_hero_subtitle', ['default' => 'Estudio de diseño de interiores', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('ah_hero_subtitle', ['label' => 'Subtítulo', 'section' => 'ah_hero', 'type' => 'text']);

    $wp_customize->add_setting('ah_hero_logo', ['sanitize_callback' => 'absint']);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'ah_hero_logo', [
        'label'       => 'Logo / Imagen del título',
        'description' => 'SVG o PNG. Si se añade, reemplaza el título de texto.',
        'section'     => 'ah_hero',
        'mime_type'   => 'image',
    ]));

    $wp_customize->add_setting('ah_hero_title', ['default' => 'Arte Habitable', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('ah_hero_title', ['label' => 'Título (si no hay logo)', 'section' => 'ah_hero', 'type' => 'text']);

    $wp_customize->add_setting('ah_hero_text', ['default' => 'Transformamos espacios en experiencias que combinan belleza, funcionalidad y emoción.', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('ah_hero_text', ['label' => 'Texto', 'section' => 'ah_hero', 'type' => 'textarea']);

    // Hero slides (up to 5)
    for ($i = 1; $i <= 5; $i++) {
        $wp_customize->add_setting("ah_hero_slide_{$i}", ['sanitize_callback' => 'absint']);
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, "ah_hero_slide_{$i}", [
            'label'     => "Slide {$i}",
            'section'   => 'ah_hero',
            'mime_type' => 'image',
        ]));
    }

    // ── Estudio Section ─────────────────────
    $wp_customize->add_section('ah_estudio', [
        'title'    => 'Estudio / Sobre nosotros',
        'priority' => 31,
    ]);

    $wp_customize->add_setting('ah_estudio_text_1', ['default' => 'En Arte Habitable, creemos que tu hogar debe ser más que un lugar bonito: debe contar tu historia. Nuestro trabajo consiste en escucharla, entender quién la habita y transformarla en una experiencia que combine belleza, funcionalidad y emoción.', 'sanitize_callback' => 'sanitize_textarea_field']);
    $wp_customize->add_control('ah_estudio_text_1', ['label' => 'Párrafo 1', 'section' => 'ah_estudio', 'type' => 'textarea']);

    $wp_customize->add_setting('ah_estudio_text_2', ['default' => 'Nos inspira acompañarte en ese proceso de transformación. Queremos descubrir contigo las posibilidades ocultas de tu espacio y dar forma a cada rincón con intención y sensibilidad, para que, al final, sientas que tu hogar te representa, te acoge y te inspira cada día.', 'sanitize_callback' => 'sanitize_textarea_field']);
    $wp_customize->add_control('ah_estudio_text_2', ['label' => 'Párrafo 2', 'section' => 'ah_estudio', 'type' => 'textarea']);

    $wp_customize->add_setting('ah_estudio_img_1', ['sanitize_callback' => 'absint']);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'ah_estudio_img_1', [
        'label'   => 'Imagen principal', 'section' => 'ah_estudio', 'mime_type' => 'image',
    ]));

    $wp_customize->add_setting('ah_estudio_img_2', ['sanitize_callback' => 'absint']);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'ah_estudio_img_2', [
        'label'   => 'Imagen secundaria', 'section' => 'ah_estudio', 'mime_type' => 'image',
    ]));

    // ── Servicios Section ───────────────────
    $wp_customize->add_section('ah_servicios', [
        'title'    => 'Servicios',
        'priority' => 32,
    ]);

    $wp_customize->add_setting('ah_servicios_intro', ['default' => 'Te acompañamos desde la idea inicial hasta los últimos detalles, transformando tus necesidades y sueños en un entorno que se siente auténtico, acogedor y equilibrado.', 'sanitize_callback' => 'sanitize_textarea_field']);
    $wp_customize->add_control('ah_servicios_intro', ['label' => 'Texto introductorio', 'section' => 'ah_servicios', 'type' => 'textarea']);

    $wp_customize->add_setting('ah_servicios_img', ['sanitize_callback' => 'absint']);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'ah_servicios_img', [
        'label' => 'Imagen servicios', 'section' => 'ah_servicios', 'mime_type' => 'image',
    ]));

    $wp_customize->add_setting('ah_ejecucion_img', ['sanitize_callback' => 'absint']);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'ah_ejecucion_img', [
        'label' => 'Imagen ejecución', 'section' => 'ah_servicios', 'mime_type' => 'image',
    ]));

    // ── Contacto Section ────────────────────
    $wp_customize->add_section('ah_contacto', [
        'title'    => 'Contacto',
        'priority' => 35,
    ]);

    $wp_customize->add_setting('ah_email', ['default' => 'info@artehabitable.es', 'sanitize_callback' => 'sanitize_email']);
    $wp_customize->add_control('ah_email', ['label' => 'Email', 'section' => 'ah_contacto', 'type' => 'email']);

    $wp_customize->add_setting('ah_phone', ['default' => '', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('ah_phone', ['label' => 'Teléfono', 'section' => 'ah_contacto', 'type' => 'text', 'description' => 'Ej: +34 93 123 45 67']);

    $wp_customize->add_setting('ah_instagram', ['default' => 'https://www.instagram.com/artehabitable/', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control('ah_instagram', ['label' => 'Instagram URL', 'section' => 'ah_contacto', 'type' => 'url']);

    $wp_customize->add_setting('ah_instagram_handle', ['default' => '@artehabitable', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('ah_instagram_handle', ['label' => 'Instagram @handle', 'section' => 'ah_contacto', 'type' => 'text']);

    $wp_customize->add_setting('ah_address', ['default' => 'Sant Cugat del Vallès, Barcelona', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('ah_address', ['label' => 'Dirección', 'section' => 'ah_contacto', 'type' => 'text']);

    $wp_customize->add_setting('ah_cf7_shortcode', ['default' => '', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('ah_cf7_shortcode', ['label' => 'Contact Form 7 Shortcode', 'section' => 'ah_contacto', 'type' => 'text', 'description' => 'Ej: [contact-form-7 id="123" title="Contacto"]']);

    $wp_customize->add_setting('ah_contacto_bg', ['sanitize_callback' => 'absint']);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'ah_contacto_bg', [
        'label' => 'Imagen de fondo contacto', 'section' => 'ah_contacto', 'mime_type' => 'image',
    ]));

    // ── Footer Section ─────────────────────────
    $wp_customize->add_section('ah_footer', [
        'title'    => 'Footer',
        'priority' => 36,
    ]);

    $wp_customize->add_setting('ah_footer_tagline', ['default' => 'Diseño de interiores con alma', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('ah_footer_tagline', ['label' => 'Tagline del footer', 'section' => 'ah_footer', 'type' => 'text']);

    $wp_customize->add_setting('ah_footer_copy', ['default' => 'Todos los derechos reservados.', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('ah_footer_copy', ['label' => 'Texto copyright', 'section' => 'ah_footer', 'type' => 'text']);

    $wp_customize->add_setting('ah_facebook', ['default' => '', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control('ah_facebook', ['label' => 'Facebook URL', 'section' => 'ah_footer', 'type' => 'url']);

    $wp_customize->add_setting('ah_linkedin', ['default' => '', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control('ah_linkedin', ['label' => 'LinkedIn URL', 'section' => 'ah_footer', 'type' => 'url']);
});
