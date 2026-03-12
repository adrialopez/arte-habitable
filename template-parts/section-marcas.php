<section class="section marcas">
    <div class="container">
        <div class="marcas__header reveal">
            <h2 class="section__title section__title--sm">Marcas que nos respaldan</h2>
        </div>
        <div class="marcas__grid reveal">
            <?php
            $marcas = [
                ['name' => 'Antaix',      'style' => 'font-weight:300;letter-spacing:0.15em;text-transform:uppercase'],
                ['name' => 'Devina Nais',  'style' => 'font-weight:300;letter-spacing:0.12em;text-transform:uppercase'],
                ['name' => 'calligaris',   'style' => 'font-weight:400;color:#c0392b'],
                ['name' => 'Temasdos',     'style' => 'font-weight:300;letter-spacing:0.1em;text-transform:uppercase'],
                ['name' => 'Vibia',        'style' => 'font-weight:300;letter-spacing:0.2em;text-transform:uppercase'],
                ['name' => 'Blasco&Vila',  'style' => 'font-weight:400'],
                ['name' => 'rondadesign',  'style' => 'font-weight:300;font-style:italic'],
                ['name' => 'MIDI',         'style' => 'font-weight:700;letter-spacing:0.05em;text-transform:uppercase'],
                ['name' => 'Dekton&reg;',  'style' => 'font-weight:700;letter-spacing:0.08em;text-transform:uppercase'],
                ['name' => 'FLOS',         'style' => 'font-weight:300;font-size:1.8rem;letter-spacing:0.05em'],
                ['name' => 'Pianca',       'style' => 'font-weight:300;letter-spacing:0.15em;text-transform:uppercase'],
                ['name' => 'Coordonné',    'style' => 'font-weight:400'],
                ['name' => 'Cumellas',     'style' => 'font-weight:300;font-style:italic'],
                ['name' => 'MIDJ',         'style' => 'font-weight:700;letter-spacing:0.05em'],
                ['name' => 'Emede',        'style' => 'font-weight:300;font-family:var(--font-display);font-size:1.6rem'],
            ];
            foreach ($marcas as $m) : ?>
                <span class="marca" style="<?php echo esc_attr($m['style']); ?>"><?php echo $m['name']; ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>
