<?php
/**
 * Arte Habitable — Instalador automático
 * Ejecutar desde: https://[dominio]/install.php
 * BORRAR DESPUÉS DE USAR
 */
set_time_limit(300);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$step = isset($_GET['step']) ? (int)$_GET['step'] : 0;
$password = isset($_GET['key']) ? $_GET['key'] : '';

// Simple security key
if ($password !== 'AH2026install') {
    die('Acceso denegado. Usa ?key=AH2026install');
}

echo "<pre style='font-family:monospace;background:#1a1817;color:#b5a08a;padding:2rem;max-width:900px;margin:2rem auto;line-height:1.8;'>";
echo "╔══════════════════════════════════════╗\n";
echo "║   Arte Habitable — Instalador WP    ║\n";
echo "╚══════════════════════════════════════╝\n\n";

// ── Step 0: Clean existing WP ───────────────
if ($step === 0) {
    echo "PASO 0: Limpiando instalación existente...\n\n";

    // Remove all WP files except wp-config.php, .htaccess, .well-known, and this script
    $keep = ['wp-config.php', '.htaccess', '.well-known', 'install.php', 'error_log'];

    function deleteDir($dir, $keep = []) {
        if (!is_dir($dir)) return;
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            if (in_array($item, $keep)) continue;
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                deleteDir($path);
                @rmdir($path);
                echo "  Borrado dir: $item\n";
            } else {
                @unlink($path);
            }
        }
    }

    deleteDir(__DIR__, $keep);
    echo "\n✓ Limpieza completada.\n\n";
    echo "<a href='?step=1&key=AH2026install' style='color:#fff;'>→ Paso 1: Descargar WordPress fresco</a>\n";
}

// ── Step 1: Download fresh WP ───────────────
if ($step === 1) {
    echo "PASO 1: Descargando WordPress (español)...\n\n";

    $wp_url = 'https://es.wordpress.org/latest-es_ES.zip';
    $zip_file = __DIR__ . '/wp-latest.zip';

    // Download
    $ch = curl_init($wp_url);
    $fp = fopen($zip_file, 'w');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($fp);

    if ($http_code !== 200 || !file_exists($zip_file) || filesize($zip_file) < 1000000) {
        die("✗ Error descargando WordPress (HTTP $http_code)\n");
    }
    echo "  Descargado: " . round(filesize($zip_file) / 1024 / 1024, 1) . " MB\n";

    // Extract
    echo "  Extrayendo...\n";
    $zip = new ZipArchive;
    if ($zip->open($zip_file) === TRUE) {
        $zip->extractTo(__DIR__);
        $zip->close();

        // Move files from wordpress/ to current dir
        $wp_dir = __DIR__ . '/wordpress';
        if (is_dir($wp_dir)) {
            $items = scandir($wp_dir);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                $src = $wp_dir . '/' . $item;
                $dst = __DIR__ . '/' . $item;
                if (!file_exists($dst)) {
                    rename($src, $dst);
                }
            }
            @rmdir($wp_dir);
        }
        @unlink($zip_file);
        echo "\n✓ WordPress instalado.\n\n";
    } else {
        die("✗ Error extrayendo ZIP\n");
    }

    echo "<a href='?step=2&key=AH2026install' style='color:#fff;'>→ Paso 2: Instalar tema Arte Habitable</a>\n";
}

// ── Step 2: Install theme ───────────────────
if ($step === 2) {
    echo "PASO 2: Instalando tema Arte Habitable...\n\n";

    $theme_dir = __DIR__ . '/wp-content/themes/arte-habitable';

    if (!is_dir($theme_dir)) {
        mkdir($theme_dir, 0755, true);
    }

    // The theme files should already be uploaded via FTP
    // Check if they exist
    if (file_exists($theme_dir . '/functions.php')) {
        echo "✓ Tema ya presente en $theme_dir\n\n";
    } else {
        echo "✗ El tema no está en $theme_dir\n";
        echo "  Sube la carpeta arte-habitable-wp a:\n";
        echo "  wp-content/themes/arte-habitable/\n\n";
        echo "  Después vuelve a ejecutar este paso.\n";
        echo "<a href='?step=2&key=AH2026install' style='color:#fff;'>→ Reintentar paso 2</a>\n";
        echo "</pre>";
        exit;
    }

    echo "<a href='?step=3&key=AH2026install' style='color:#fff;'>→ Paso 3: Configurar WordPress</a>\n";
}

// ── Step 3: Configure WP ────────────────────
if ($step === 3) {
    echo "PASO 3: Configurando WordPress...\n\n";

    // Load WP
    define('WP_USE_THEMES', false);
    require(__DIR__ . '/wp-load.php');

    // Activate theme
    switch_theme('arte-habitable');
    echo "✓ Tema 'arte-habitable' activado\n";

    // Set front page
    $front_page = get_page_by_path('inicio');
    if (!$front_page) {
        $front_id = wp_insert_post([
            'post_title'  => 'Inicio',
            'post_status' => 'publish',
            'post_type'   => 'page',
        ]);
    } else {
        $front_id = $front_page->ID;
    }
    update_option('show_on_front', 'page');
    update_option('page_on_front', $front_id);
    echo "✓ Página de inicio configurada\n";

    // Set permalinks
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
    $wp_rewrite->flush_rules();
    echo "✓ Permalinks configurados\n";

    // Site settings
    update_option('blogname', 'Arte Habitable');
    update_option('blogdescription', 'Estudio de diseño de interiores');
    update_option('WPLANG', 'es_ES');
    update_option('timezone_string', 'Europe/Madrid');
    update_option('date_format', 'd/m/Y');
    echo "✓ Ajustes del sitio configurados\n";

    // Install & activate CF7
    echo "\n  Instalando Contact Form 7...\n";
    include_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
    include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    include_once(ABSPATH . 'wp-admin/includes/file.php');

    $api = plugins_api('plugin_information', ['slug' => 'contact-form-7', 'fields' => ['sections' => false]]);
    if (!is_wp_error($api)) {
        $upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
        $result = $upgrader->install($api->download_link);
        if ($result) {
            activate_plugin('contact-form-7/wp-contact-form-7.php');
            echo "  ✓ Contact Form 7 instalado y activado\n";
        }
    }

    // Create CF7 form
    if (class_exists('WPCF7_ContactForm')) {
        $form_template =
'<div class="form__group">
[text* nombre placeholder "Nombre"]
</div>
<div class="form__group">
[email* email placeholder "Email"]
</div>
<div class="form__group">
[tel telefono placeholder "Teléfono"]
</div>
<div class="form__group">
[textarea* mensaje placeholder "Cuéntanos sobre tu proyecto..."]
</div>
[submit class:form__submit "Enviar mensaje"]';

        $mail_template = "Nuevo mensaje desde artehabitable.es\n=====================================\n\nNombre: [nombre]\nEmail: [email]\nTeléfono: [telefono]\n\nMensaje:\n[mensaje]";

        $contact_form = WPCF7_ContactForm::get_template();
        $props = $contact_form->get_properties();
        $props['form'] = $form_template;
        $props['mail']['subject'] = 'Nuevo mensaje web: [nombre]';
        $props['mail']['body'] = $mail_template;
        $props['mail']['recipient'] = 'info@artehabitable.es';
        $contact_form->set_properties($props);
        $contact_form->set_title('Contacto Arte Habitable');
        $contact_form->save();

        $cf7_id = $contact_form->id();
        set_theme_mod('ah_cf7_shortcode', '[contact-form-7 id="' . $cf7_id . '" title="Contacto Arte Habitable"]');
        echo "  ✓ Formulario de contacto creado (ID: $cf7_id)\n";
    }

    echo "\n<a href='?step=4&key=AH2026install' style='color:#fff;'>→ Paso 4: Crear proyectos</a>\n";
}

// ── Step 4: Create projects ─────────────────
if ($step === 4) {
    echo "PASO 4: Creando proyectos...\n\n";

    define('WP_USE_THEMES', false);
    require(__DIR__ . '/wp-load.php');

    // Flush rewrite rules for CPT
    flush_rewrite_rules();

    // Create taxonomy terms
    $term_de = term_exists('Diseño y ejecución', 'tipo_proyecto');
    if (!$term_de) $term_de = wp_insert_term('Diseño y ejecución', 'tipo_proyecto');
    $term_ri = term_exists('Reforma integral', 'tipo_proyecto');
    if (!$term_ri) $term_ri = wp_insert_term('Reforma integral y mobiliario', 'tipo_proyecto');
    $term_dc = term_exists('Diseño de concepto', 'tipo_proyecto');
    if (!$term_dc) $term_dc = wp_insert_term('Diseño de concepto', 'tipo_proyecto');

    $de_id = is_array($term_de) ? $term_de['term_id'] : $term_de;
    $ri_id = is_array($term_ri) ? $term_ri['term_id'] : $term_ri;
    $dc_id = is_array($term_dc) ? $term_dc['term_id'] : $term_dc;

    echo "✓ Taxonomías creadas\n\n";

    $projects = [
        [
            'title' => 'GDLC',
            'content' => 'Este proyecto nació con un objetivo claro: llenar de luz y calma el hogar de una familia de tres, formada por dos adultos y una niña pequeña, sin renunciar a la calidez y la vida cotidiana que hacen de una casa un verdadero refugio.

Desde la conceptualización del diseño hasta los últimos detalles de mobiliario y decoración, trabajamos para crear un ambiente sereno, funcional y acogedor, donde cada rincón respira armonía. Los tonos suaves, las maderas claras y las líneas limpias fueron nuestros aliados para ganar amplitud y claridad, potenciando la luz natural y manteniendo siempre una sensación de hogar cálido y habitable.

El resultado es una vivienda equilibrada y atemporal, donde la simplicidad elegante se combina con la comodidad familiar, invitando a disfrutar de los espacios con calma y naturalidad.',
            'location' => 'Sant Cugat del Vallès',
            'type' => 'Residencia familiar',
            'order' => 1,
            'term' => $de_id,
        ],
        [
            'title' => 'Casa E.',
            'content' => 'En este proyecto de diseño, reforma, mobiliario a medida y decoración, transformamos una vivienda familiar en un espacio lleno de luz, equilibrio y serenidad. La propuesta buscaba actualizar la estética del hogar sin perder su calidez, potenciando la luz natural y el confort en cada ambiente.

Los tonos neutros, las texturas suaves y la madera natural se combinan para crear una atmósfera envolvente y acogedora, donde cada detalle —desde la iluminación hasta el mobiliario diseñado a medida— contribuye a una sensación de armonía y bienestar.

El resultado es una casa que transmite calma y elegancia, pensada para vivirse con comodidad y disfrutarse cada día.',
            'location' => 'Matadepera',
            'type' => 'Residencia unifamiliar',
            'order' => 2,
            'term' => $de_id,
        ],
        [
            'title' => 'Organ',
            'content' => 'Un proyecto pensado para transformar un espacio de trabajo en un lugar cálido, luminoso y conectado. La idea fue abrir los ambientes sin perder la sensación de independencia, creando espacios compartidos que se sienten personales y acogedores.

La luz natural se convirtió en el hilo conductor del diseño: atraviesa el local y se filtra entre estructuras de madera clara y cristales, aportando transparencia y fluidez. Los tonos neutros, los materiales naturales y el mobiliario a medida refuerzan esa atmósfera serena y profesional, donde cada rincón invita a trabajar, conversar y sentirse a gusto.

El resultado es una oficina que combina funcionalidad y calidez, reflejando una nueva forma de entender los espacios de trabajo: más humanos, más abiertos y más inspiradores.',
            'location' => 'Sant Cugat Centre',
            'type' => 'Oficinas inmobiliaria',
            'order' => 3,
            'term' => $de_id,
        ],
        [
            'title' => 'CanTrabal',
            'content' => 'En la residencia Can Trabal, la reforma integral y el diseño de mobiliario se concibieron para modernizar por completo la vivienda y aportar sofisticación.

El proyecto combina materiales nobles —maderas naturales, piedra y lacas suaves— con piezas icónicas del diseño contemporáneo, creando un diálogo entre elegancia y funcionalidad.',
            'location' => 'Sant Cugat',
            'type' => 'Vivienda unifamiliar',
            'order' => 4,
            'term' => $ri_id,
        ],
        [
            'title' => 'Juan S.',
            'content' => 'En este proyecto, el objetivo fue transformar un espacio amplio y de techos altos en un refugio cálido y elegante. Las maderas oscuras envuelven el ambiente, aportando una sensación de sofisticación serena y equilibrio frente a una base neutra y luminosa.

El mobiliario se propone de formas orgánicas para permitir una circulación natural a través del espacio. La cocina se abre al salón de forma sutil: los dos ambientes se conectan visualmente, pero mantienen su identidad propia, logrando una convivencia fluida entre lo práctico y lo estético.',
            'location' => 'Matadepera',
            'type' => 'Diseño salón y cocina',
            'order' => 5,
            'term' => $dc_id,
        ],
        [
            'title' => 'Oficinas IFF',
            'content' => 'Proyecto de diseño y reforma integral de oficinas, donde la funcionalidad y la estética se unen para crear un espacio de trabajo inspirador y profesional.',
            'location' => '',
            'type' => 'Espacio de trabajo',
            'order' => 6,
            'term' => $de_id,
        ],
    ];

    foreach ($projects as $p) {
        // Check if project already exists
        $existing = get_page_by_title($p['title'], OBJECT, 'proyecto');
        if ($existing) {
            echo "  → {$p['title']} ya existe (ID: {$existing->ID})\n";
            continue;
        }

        $post_id = wp_insert_post([
            'post_title'   => $p['title'],
            'post_content' => $p['content'],
            'post_status'  => 'publish',
            'post_type'    => 'proyecto',
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_ah_location', $p['location']);
            update_post_meta($post_id, '_ah_project_type', $p['type']);
            update_post_meta($post_id, '_ah_order', $p['order']);
            if ($p['term']) {
                wp_set_object_terms($post_id, [(int)$p['term']], 'tipo_proyecto');
            }
            echo "  ✓ {$p['title']} creado (ID: $post_id)\n";
        }
    }

    // Create Equipo parent page
    $equipo_page = get_page_by_path('equipo');
    if (!$equipo_page) {
        $equipo_id = wp_insert_post([
            'post_title'  => 'Equipo',
            'post_name'   => 'equipo',
            'post_status' => 'publish',
            'post_type'   => 'page',
        ]);
        echo "\n✓ Página 'Equipo' creada (ID: $equipo_id)\n";
    } else {
        $equipo_id = $equipo_page->ID;
        echo "\n→ Página 'Equipo' ya existe\n";
    }

    // Create team member pages
    $members = [
        ['name' => 'Elena', 'role' => 'Dirección creativa', 'order' => 1],
        ['name' => 'Patri', 'role' => 'Diseño de interiores', 'order' => 2],
        ['name' => 'Gabriela', 'role' => 'Diseño de interiores', 'order' => 3],
        ['name' => 'Esther', 'role' => 'Gestión de proyectos', 'order' => 4],
    ];

    foreach ($members as $m) {
        $existing = get_page_by_title($m['name'], OBJECT, 'page');
        if (!$existing) {
            wp_insert_post([
                'post_title'   => $m['name'],
                'post_excerpt' => $m['role'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_parent'  => $equipo_id,
                'menu_order'   => $m['order'],
            ]);
            echo "  ✓ Miembro: {$m['name']}\n";
        }
    }

    echo "\n<a href='?step=5&key=AH2026install' style='color:#fff;'>→ Paso 5: Limpiar contenido antiguo e importar imágenes</a>\n";
}

// ── Step 5: Clean old content + Import images ──
if ($step === 5) {
    echo "PASO 5: Limpiando contenido antiguo e importando imágenes...\n\n";

    define('WP_USE_THEMES', false);
    require(__DIR__ . '/wp-load.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    // ── Delete default WP content ──
    echo "Borrando contenido por defecto...\n";

    // Delete "Hello World" post
    $hello = get_page_by_path('hello-world', OBJECT, 'post');
    if ($hello) { wp_delete_post($hello->ID, true); echo "  ✓ Borrado post 'Hello World'\n"; }

    // Delete "Sample Page"
    $sample = get_page_by_path('sample-page', OBJECT, 'page');
    if ($sample) { wp_delete_post($sample->ID, true); echo "  ✓ Borrada 'Sample Page'\n"; }

    // Delete default comment
    $comment = get_comment(1);
    if ($comment) { wp_delete_comment(1, true); echo "  ✓ Borrado comentario por defecto\n"; }

    // Delete "Privacy Policy" draft
    $privacy = get_page_by_path('privacy-policy', OBJECT, 'page');
    if ($privacy) { wp_delete_post($privacy->ID, true); echo "  ✓ Borrada 'Privacy Policy'\n"; }

    echo "\n";

    // ── Import images from static site ──
    // Static site images are at ../artehabitable.es/img/
    $img_base = realpath(__DIR__ . '/../artehabitable.es/img');
    if (!$img_base || !is_dir($img_base)) {
        echo "✗ No se encontró carpeta de imágenes en: " . __DIR__ . "/../artehabitable.es/img\n";
        echo "  Comprueba que la web estática sigue en websites/artehabitable.es/\n";
        echo "</pre>";
        exit;
    }
    echo "✓ Carpeta de imágenes encontrada: $img_base\n\n";

    // Helper function to import an image into WP media library
    function ah_import_image($file_path, $title = '') {
        if (!file_exists($file_path)) return 0;

        $upload_dir = wp_upload_dir();
        $filename = basename($file_path);
        if (!$title) $title = pathinfo($filename, PATHINFO_FILENAME);

        // Copy file to uploads
        $dest = $upload_dir['path'] . '/' . $filename;

        // Avoid duplicates by checking title
        $existing = get_posts([
            'post_type' => 'attachment',
            'title' => $title,
            'posts_per_page' => 1,
        ]);
        if (!empty($existing)) return $existing[0]->ID;

        copy($file_path, $dest);

        $filetype = wp_check_filetype($filename);
        $attachment = [
            'guid'           => $upload_dir['url'] . '/' . $filename,
            'post_mime_type' => $filetype['type'],
            'post_title'     => $title,
            'post_content'   => '',
            'post_status'    => 'inherit'
        ];

        $attach_id = wp_insert_attachment($attachment, $dest);
        $attach_data = wp_generate_attachment_metadata($attach_id, $dest);
        wp_update_attachment_metadata($attach_id, $attach_data);

        return $attach_id;
    }

    // ── Import Hero slides ──
    echo "Importando slides del hero...\n";
    for ($i = 1; $i <= 5; $i++) {
        $file = $img_base . "/frontpage/hero-{$i}.jpg";
        if (file_exists($file)) {
            $id = ah_import_image($file, "Hero slide {$i}");
            if ($id) {
                set_theme_mod("ah_hero_slide_{$i}", $id);
                echo "  ✓ Hero slide {$i} (ID: $id)\n";
            }
        }
    }

    // ── Import Estudio images ──
    echo "\nImportando imágenes del estudio...\n";
    $about1 = ah_import_image($img_base . '/frontpage/about-1.jpg', 'Estudio imagen principal');
    if ($about1) { set_theme_mod('ah_estudio_img_1', $about1); echo "  ✓ Estudio img 1 (ID: $about1)\n"; }
    $about2 = ah_import_image($img_base . '/frontpage/about-2.jpg', 'Estudio imagen secundaria');
    if ($about2) { set_theme_mod('ah_estudio_img_2', $about2); echo "  ✓ Estudio img 2 (ID: $about2)\n"; }

    // ── Import Servicios images ──
    echo "\nImportando imágenes de servicios...\n";
    $serv1 = ah_import_image($img_base . '/frontpage/services-1.jpg', 'Servicios imagen');
    if ($serv1) { set_theme_mod('ah_servicios_img', $serv1); echo "  ✓ Servicios img (ID: $serv1)\n"; }
    $serv2 = ah_import_image($img_base . '/frontpage/services-2.jpg', 'Ejecución imagen');
    if ($serv2) { set_theme_mod('ah_ejecucion_img', $serv2); echo "  ✓ Ejecución img (ID: $serv2)\n"; }

    // ── Import Contact background ──
    echo "\nImportando imagen de contacto...\n";
    $contact_bg = ah_import_image($img_base . '/contact/contact-bg.jpg', 'Contacto fondo');
    if ($contact_bg) { set_theme_mod('ah_contacto_bg', $contact_bg); echo "  ✓ Contacto fondo (ID: $contact_bg)\n"; }

    // ── Import Team photos ──
    echo "\nImportando fotos del equipo...\n";
    $team_map = [
        'Elena'    => $img_base . '/team/elena.jpg',
        'Patri'    => $img_base . '/team/patri.jpg',
        'Gabriela' => $img_base . '/team/gabriela.jpg',
        'Esther'   => $img_base . '/team/esther.jpg',
    ];
    foreach ($team_map as $name => $file) {
        $page = get_page_by_title($name, OBJECT, 'page');
        if ($page && file_exists($file)) {
            $id = ah_import_image($file, "Equipo - $name");
            if ($id) {
                set_post_thumbnail($page->ID, $id);
                echo "  ✓ $name (ID: $id)\n";
            }
        }
    }

    // ── Import Project images ──
    echo "\nImportando imágenes de proyectos...\n";
    $project_map = [
        'GDLC'          => 'casa-lavoisier',
        'Casa E.'        => 'casa-sq',
        'Organ'          => 'organ',
        'CanTrabal'      => 'valles',
        'Juan S.'        => 'pg',
        'Oficinas IFF'   => 'oficinas-iff',
    ];

    foreach ($project_map as $project_title => $folder) {
        $project_dir = $img_base . '/projects/' . $folder;
        if (!is_dir($project_dir)) {
            echo "  ✗ No existe carpeta: $folder\n";
            continue;
        }

        // Find project post
        $project_posts = get_posts([
            'post_type' => 'proyecto',
            'title' => $project_title,
            'posts_per_page' => 1,
        ]);
        if (empty($project_posts)) {
            echo "  ✗ No existe proyecto: $project_title\n";
            continue;
        }
        $post_id = $project_posts[0]->ID;

        echo "  $project_title:\n";

        // Import thumbnail
        $thumb_file = $project_dir . '/thumb.jpg';
        if (file_exists($thumb_file)) {
            $thumb_id = ah_import_image($thumb_file, "$project_title - Thumbnail");
            if ($thumb_id) {
                set_post_thumbnail($post_id, $thumb_id);
                echo "    ✓ Thumbnail (ID: $thumb_id)\n";
            }
        }

        // Import gallery images
        $gallery_ids = [];
        for ($i = 1; $i <= 10; $i++) {
            $num = str_pad($i, 2, '0', STR_PAD_LEFT);
            $img_file = $project_dir . "/{$num}.jpg";
            if (file_exists($img_file)) {
                $img_id = ah_import_image($img_file, "$project_title - Imagen $i");
                if ($img_id) {
                    $gallery_ids[] = $img_id;
                    echo "    ✓ Imagen $num (ID: $img_id)\n";
                }
            }
        }
        if (!empty($gallery_ids)) {
            update_post_meta($post_id, '_ah_gallery', implode(',', $gallery_ids));
        }
    }

    echo "\n\n══════════════════════════════════════\n";
    echo "✓ INSTALACIÓN COMPLETADA\n";
    echo "══════════════════════════════════════\n\n";
    echo "Próximos pasos:\n";
    echo "1. Ve a wp-admin > Apariencia > Personalizar\n";
    echo "2. Sube tu logo SVG en Identidad del sitio > Logo\n";
    echo "3. Configura el menú en Apariencia > Menús\n";
    echo "4. IMPORTANTE: Borra este archivo install.php del servidor\n\n";

    $admin_url = admin_url();
    echo "<a href='$admin_url' style='color:#fff;'>→ Ir al panel de WordPress</a>\n";
}

echo "</pre>";
?>
