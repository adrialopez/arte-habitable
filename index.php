<?php
/**
 * Fallback template — redirects to front page
 */
get_header();
?>

<main class="fallback-page">
    <div class="container" style="padding: 10rem 0; text-align: center;">
        <h1 style="font-family: var(--font-display); font-size: 3rem; font-weight: 300;">
            <?php echo esc_html(get_bloginfo('name')); ?>
        </h1>
        <p style="margin-top: 1rem; color: var(--color-text-light);">
            <a href="<?php echo esc_url(home_url('/')); ?>">Volver al inicio</a>
        </p>
    </div>
</main>

<?php get_footer(); ?>
