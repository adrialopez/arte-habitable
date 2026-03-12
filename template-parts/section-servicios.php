<section class="section servicios" id="servicios">
    <div class="container">
        <div class="servicios__header reveal">
            <span class="section__label">Servicios</span>
            <h2 class="section__title">Confía tu diseño<br>residencial con nosotros</h2>
            <p class="servicios__intro"><?php echo esc_html(get_theme_mod('ah_servicios_intro', 'Te acompañamos desde la idea inicial hasta los últimos detalles, transformando tus necesidades y sueños en un entorno que se siente auténtico, acogedor y equilibrado.')); ?></p>
        </div>

        <?php $simg = ah_get_image_url('ah_servicios_img', 'ah-hero'); if ($simg) : ?>
            <div class="servicios__fullimage reveal">
                <img src="<?php echo esc_url($simg); ?>" alt="Proceso de diseño Arte Habitable" loading="lazy">
            </div>
        <?php endif; ?>

        <div class="proceso">
            <div class="proceso__step reveal">
                <span class="proceso__number">01</span>
                <h3 class="proceso__title">Conceptualización</h3>
                <p class="proceso__text">Definimos tu personalidad y estilo. Elaboramos moodboards y tableros de acabado que marcan identidad en cada espacio.</p>
            </div>
            <div class="proceso__step reveal">
                <span class="proceso__number">02</span>
                <h3 class="proceso__title">Previsualización</h3>
                <p class="proceso__text">Veremos la integración del concepto, materiales escogidos y FF&amp;E en imágenes realistas. Podremos hacer cambios para asegurarnos que los espacios responden a tus necesidades.</p>
            </div>
            <div class="proceso__step reveal">
                <span class="proceso__number">03</span>
                <h3 class="proceso__title">Planos y acabados</h3>
                <p class="proceso__text">Elaboramos los planos necesarios para la construcción de cada espacio y su mobiliario. Alzados, reformas y detalles de acabados.</p>
            </div>
            <div class="proceso__step reveal">
                <span class="proceso__number">04</span>
                <h3 class="proceso__title">Presupuesto</h3>
                <p class="proceso__text">Elaboramos un primer presupuesto con todo lo aprobado en obra. Podemos ajustarnos a tu presupuesto con segundas opciones de materiales y fabricantes.</p>
            </div>
            <div class="proceso__step reveal">
                <span class="proceso__number">05</span>
                <h3 class="proceso__title">Entregable</h3>
                <p class="proceso__text">Carpeta impresa de planos y entrega digital completa con renders, planos, presentación y presupuesto.</p>
            </div>
        </div>

        <div class="ejecucion reveal">
            <div class="ejecucion__content">
                <span class="section__label">Ejecución</span>
                <h3 class="section__title section__title--sm">¡Manos a la obra!</h3>
                <p>Con más de 30 años de experiencia, la gestión de obra es uno de nuestros puntos más fuertes. Minimizamos imprevistos y planificamos hasta el último detalle.</p>
                <p>Coordinamos todo lo necesario en las diferentes fases de tu proyecto: mano de obra especializada, instalaciones industriales, montaje de mobiliario y limpieza final.</p>
                <p>La comunicación es muy importante para nosotros. Estarás al tanto de cada paso que demos. Contamos con montadores expertos en instalaciones de alta complejidad bajo nuestra exigente supervisión.</p>
            </div>
            <?php $eimg = ah_get_image_url('ah_ejecucion_img', 'ah-project-gallery'); if ($eimg) : ?>
                <div class="ejecucion__image">
                    <img src="<?php echo esc_url($eimg); ?>" alt="Ejecución de obra" loading="lazy">
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
