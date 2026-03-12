<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div class="footer__brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="footer__logo-link">
                    <?php if (has_custom_logo()) :
                        $logo_id  = get_theme_mod('custom_logo');
                        $logo_url = wp_get_attachment_url($logo_id);
                        $mime     = get_post_mime_type($logo_id);
                        if ($mime === 'image/svg+xml') : ?>
                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="footer__logo-img">
                        <?php else :
                            echo wp_get_attachment_image($logo_id, 'medium', false, ['class' => 'footer__logo-img', 'alt' => get_bloginfo('name')]);
                        endif;
                    else : ?>
                        <span class="footer__logo"><?php echo esc_html(get_bloginfo('name')); ?></span>
                    <?php endif; ?>
                </a>
                <?php $tagline = get_theme_mod('ah_footer_tagline', 'Diseño de interiores con alma'); ?>
                <?php if ($tagline) : ?>
                    <p class="footer__tagline"><?php echo esc_html($tagline); ?></p>
                <?php endif; ?>
            </div>
            <div class="footer__links">
                <?php
                wp_nav_menu([
                    'theme_location'  => 'footer',
                    'container'       => false,
                    'menu_class'      => 'footer__menu',
                    'depth'           => 1,
                    'fallback_cb'     => 'ah_fallback_footer_menu',
                ]);
                ?>
            </div>
            <div class="footer__social">
                <?php $ig = get_theme_mod('ah_instagram'); if ($ig) : ?>
                    <a href="<?php echo esc_url($ig); ?>" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                <?php endif; ?>
                <?php $fb = get_theme_mod('ah_facebook'); if ($fb) : ?>
                    <a href="<?php echo esc_url($fb); ?>" target="_blank" rel="noopener" aria-label="Facebook">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                <?php endif; ?>
                <?php $li = get_theme_mod('ah_linkedin'); if ($li) : ?>
                    <a href="<?php echo esc_url($li); ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer__bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php echo esc_html(get_theme_mod('ah_footer_copy', 'Todos los derechos reservados.')); ?></p>
            <div class="footer__legal">
                <?php
                $privacy_page = get_privacy_policy_url();
                if ($privacy_page) : ?>
                    <a href="<?php echo esc_url($privacy_page); ?>">Política de Privacidad</a>
                <?php endif; ?>
                <?php
                $cookies_page = get_page_by_path('politica-de-cookies');
                if ($cookies_page) : ?>
                    <a href="<?php echo esc_url(get_permalink($cookies_page)); ?>">Política de Cookies</a>
                <?php endif; ?>
                <?php
                $legal_page = get_page_by_path('aviso-legal');
                if ($legal_page) : ?>
                    <a href="<?php echo esc_url(get_permalink($legal_page)); ?>">Aviso Legal</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/34636272186" class="whatsapp-btn" target="_blank" rel="noopener" aria-label="Contactar por WhatsApp">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
    </svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
<?php
function ah_fallback_footer_menu() {
    $base = esc_url(home_url('/'));
    echo '<ul class="footer__menu">';
    echo '<li><a href="' . $base . '#estudio">Estudio</a></li>';
    echo '<li><a href="' . $base . '#servicios">Servicios</a></li>';
    echo '<li><a href="' . $base . '#portafolio">Portafolio</a></li>';
    echo '<li><a href="' . $base . '#contacto">Contacto</a></li>';
    echo '</ul>';
}
?>
