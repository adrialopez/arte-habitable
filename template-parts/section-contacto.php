<?php
$email    = get_theme_mod('ah_email', 'info@artehabitable.es');
$ig_url   = get_theme_mod('ah_instagram', 'https://www.instagram.com/artehabitable/');
$ig_name  = get_theme_mod('ah_instagram_handle', '@artehabitable');
$address  = get_theme_mod('ah_address', 'Sant Cugat del Vallès, Barcelona');
$cf7      = get_theme_mod('ah_cf7_shortcode', '');
$bg_url   = ah_get_image_url('ah_contacto_bg', 'ah-hero');
?>
<section class="contacto" id="contacto">
    <?php if ($bg_url) : ?>
        <div class="contacto__bg" style="background-image:url('<?php echo esc_url($bg_url); ?>')"></div>
    <?php endif; ?>
    <div class="container">
        <div class="contacto__grid">
            <div class="contacto__content reveal">
                <span class="section__label">Contacto</span>
                <h2 class="section__title section__title--light">Hablemos de<br>tu proyecto</h2>
                <p class="contacto__text">Cuéntanos tu idea y descubramos juntos las posibilidades de tu espacio.</p>
                <div class="contacto__details">
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="contacto__link"><?php echo esc_html($email); ?></a>
                    <?php $phone = get_theme_mod('ah_phone'); if ($phone) : ?>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^+0-9]/', '', $phone)); ?>" class="contacto__link"><?php echo esc_html($phone); ?></a>
                    <?php endif; ?>
                    <?php if ($ig_url) : ?>
                        <a href="<?php echo esc_url($ig_url); ?>" target="_blank" rel="noopener" class="contacto__link contacto__link--ig">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                            <?php echo esc_html($ig_name); ?>
                        </a>
                    <?php endif; ?>
                    <p class="contacto__address"><?php echo esc_html($address); ?></p>
                </div>
            </div>
            <div class="contacto__form-wrap reveal">
                <?php if ($cf7) : ?>
                    <?php echo do_shortcode($cf7); ?>
                <?php else : ?>
                    <p class="contacto__form-notice" style="color:rgba(255,255,255,0.5);font-size:0.9rem;">
                        Configura el shortcode de Contact Form 7 en <strong>Personalizar > Contacto</strong>.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
