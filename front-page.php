<?php
/**
 * Template: Front Page
 */
get_header();
?>

<?php get_template_part('template-parts/section', 'hero'); ?>
<?php get_template_part('template-parts/section', 'estudio'); ?>
<?php get_template_part('template-parts/section', 'servicios'); ?>
<?php get_template_part('template-parts/section', 'portafolio'); ?>
<?php get_template_part('template-parts/section', 'equipo'); ?>
<?php get_template_part('template-parts/section', 'marcas'); ?>
<?php get_template_part('template-parts/section', 'contacto'); ?>

<!-- Project Modal -->
<div class="modal" id="projectModal">
    <button class="modal__close" aria-label="Cerrar">&times;</button>
    <div class="modal__content">
        <div class="modal__info" id="modalInfo"></div>
        <div class="modal__gallery" id="modalGallery"></div>
    </div>
</div>

<?php get_footer(); ?>
