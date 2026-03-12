<section class="section estudio" id="estudio">
    <div class="container">
        <div class="estudio__grid">
            <div class="estudio__content reveal">
                <span class="section__label">Nuestro estudio</span>
                <h2 class="section__title">Cada espacio tiene una historia esperando ser contada</h2>
                <p class="estudio__text"><?php echo esc_html(get_theme_mod('ah_estudio_text_1', 'En Arte Habitable, creemos que tu hogar debe ser más que un lugar bonito: debe contar tu historia. Nuestro trabajo consiste en escucharla, entender quién la habita y transformarla en una experiencia que combine belleza, funcionalidad y emoción.')); ?></p>
                <p class="estudio__text"><?php echo esc_html(get_theme_mod('ah_estudio_text_2', 'Nos inspira acompañarte en ese proceso de transformación. Queremos descubrir contigo las posibilidades ocultas de tu espacio y dar forma a cada rincón con intención y sensibilidad, para que, al final, sientas que tu hogar te representa, te acoge y te inspira cada día.')); ?></p>
            </div>
            <div class="estudio__images reveal">
                <?php $img1 = ah_get_image_url('ah_estudio_img_1', 'ah-project-gallery'); if ($img1) : ?>
                    <div class="estudio__img estudio__img--main">
                        <img src="<?php echo esc_url($img1); ?>" alt="Interior diseñado por Arte Habitable" loading="lazy">
                    </div>
                <?php endif; ?>
                <?php $img2 = ah_get_image_url('ah_estudio_img_2', 'ah-project-gallery'); if ($img2) : ?>
                    <div class="estudio__img estudio__img--secondary">
                        <img src="<?php echo esc_url($img2); ?>" alt="Detalle de diseño interior" loading="lazy">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
