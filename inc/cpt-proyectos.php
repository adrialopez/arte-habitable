<?php
/**
 * Custom Post Type: Proyectos
 */

add_action('init', function () {
    register_post_type('proyecto', [
        'labels' => [
            'name'               => 'Proyectos',
            'singular_name'      => 'Proyecto',
            'add_new'            => 'Añadir Proyecto',
            'add_new_item'       => 'Añadir Nuevo Proyecto',
            'edit_item'          => 'Editar Proyecto',
            'view_item'          => 'Ver Proyecto',
            'all_items'          => 'Todos los Proyectos',
            'search_items'       => 'Buscar Proyectos',
            'not_found'          => 'No se encontraron proyectos',
            'not_found_in_trash' => 'No hay proyectos en la papelera',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'proyectos'],
        'menu_icon'    => 'dashicons-portfolio',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    register_taxonomy('tipo_proyecto', 'proyecto', [
        'labels' => [
            'name'          => 'Tipos de Proyecto',
            'singular_name' => 'Tipo de Proyecto',
            'add_new_item'  => 'Añadir Tipo',
            'new_item_name' => 'Nuevo Tipo',
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'tipo-proyecto'],
        'show_in_rest' => true,
    ]);
});

// ── Meta Boxes ──────────────────────────────
add_action('add_meta_boxes', function () {
    add_meta_box(
        'ah_proyecto_details',
        'Detalles del Proyecto',
        'ah_proyecto_details_html',
        'proyecto',
        'normal',
        'high'
    );

    add_meta_box(
        'ah_proyecto_gallery',
        'Galería del Proyecto',
        'ah_proyecto_gallery_html',
        'proyecto',
        'normal',
        'high'
    );
});

function ah_proyecto_details_html($post) {
    wp_nonce_field('ah_proyecto_meta', 'ah_proyecto_nonce');

    $location = get_post_meta($post->ID, '_ah_location', true);
    $project_type = get_post_meta($post->ID, '_ah_project_type', true);
    $order = get_post_meta($post->ID, '_ah_order', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="ah_location">Ubicación</label></th>
            <td><input type="text" id="ah_location" name="ah_location" value="<?php echo esc_attr($location); ?>" class="regular-text" placeholder="Ej: Sant Cugat del Vallès"></td>
        </tr>
        <tr>
            <th><label for="ah_project_type">Tipo (subtítulo)</label></th>
            <td><input type="text" id="ah_project_type" name="ah_project_type" value="<?php echo esc_attr($project_type); ?>" class="regular-text" placeholder="Ej: Residencia unifamiliar"></td>
        </tr>
        <tr>
            <th><label for="ah_order">Orden</label></th>
            <td><input type="number" id="ah_order" name="ah_order" value="<?php echo esc_attr($order); ?>" class="small-text" placeholder="0"></td>
        </tr>
    </table>
    <?php
}

function ah_proyecto_gallery_html($post) {
    $gallery = get_post_meta($post->ID, '_ah_gallery', true);
    $gallery_ids = $gallery ? explode(',', $gallery) : [];
    ?>
    <div id="ah-gallery-container">
        <div id="ah-gallery-images" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:15px;">
            <?php foreach ($gallery_ids as $id) :
                $img = wp_get_attachment_image_src($id, 'thumbnail');
                if ($img) : ?>
                    <div class="ah-gallery-item" data-id="<?php echo esc_attr($id); ?>" style="position:relative;">
                        <img src="<?php echo esc_url($img[0]); ?>" style="width:120px;height:90px;object-fit:cover;border:2px solid #ddd;cursor:move;">
                        <button type="button" class="ah-remove-image" style="position:absolute;top:-5px;right:-5px;background:red;color:white;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:12px;line-height:1;">&times;</button>
                    </div>
                <?php endif;
            endforeach; ?>
        </div>
        <input type="hidden" name="ah_gallery" id="ah_gallery" value="<?php echo esc_attr($gallery); ?>">
        <button type="button" id="ah-add-gallery" class="button button-primary">Añadir imágenes a la galería</button>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('#ah-add-gallery').on('click', function(e) {
            e.preventDefault();
            var frame = wp.media({
                title: 'Seleccionar imágenes',
                multiple: true,
                library: { type: 'image' }
            });
            frame.on('select', function() {
                var attachments = frame.state().get('selection').toJSON();
                attachments.forEach(function(att) {
                    var current = $('#ah_gallery').val();
                    var ids = current ? current.split(',') : [];
                    if (ids.indexOf(String(att.id)) === -1) {
                        ids.push(att.id);
                        $('#ah_gallery').val(ids.join(','));
                        var thumb = att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
                        $('#ah-gallery-images').append(
                            '<div class="ah-gallery-item" data-id="'+att.id+'" style="position:relative;">' +
                            '<img src="'+thumb+'" style="width:120px;height:90px;object-fit:cover;border:2px solid #ddd;cursor:move;">' +
                            '<button type="button" class="ah-remove-image" style="position:absolute;top:-5px;right:-5px;background:red;color:white;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:12px;line-height:1;">&times;</button>' +
                            '</div>'
                        );
                    }
                });
            });
            frame.open();
        });

        $(document).on('click', '.ah-remove-image', function() {
            var item = $(this).closest('.ah-gallery-item');
            var id = item.data('id');
            var ids = $('#ah_gallery').val().split(',').filter(function(v) { return v != id; });
            $('#ah_gallery').val(ids.join(','));
            item.remove();
        });

        // Sortable
        if ($.fn.sortable) {
            $('#ah-gallery-images').sortable({
                update: function() {
                    var ids = [];
                    $('#ah-gallery-images .ah-gallery-item').each(function() {
                        ids.push($(this).data('id'));
                    });
                    $('#ah_gallery').val(ids.join(','));
                }
            });
        }
    });
    </script>
    <?php
}

// ── Save Meta ───────────────────────────────
add_action('save_post_proyecto', function ($post_id) {
    if (!isset($_POST['ah_proyecto_nonce']) || !wp_verify_nonce($_POST['ah_proyecto_nonce'], 'ah_proyecto_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['ah_location'])) {
        update_post_meta($post_id, '_ah_location', sanitize_text_field($_POST['ah_location']));
    }
    if (isset($_POST['ah_project_type'])) {
        update_post_meta($post_id, '_ah_project_type', sanitize_text_field($_POST['ah_project_type']));
    }
    if (isset($_POST['ah_order'])) {
        update_post_meta($post_id, '_ah_order', intval($_POST['ah_order']));
    }
    if (isset($_POST['ah_gallery'])) {
        update_post_meta($post_id, '_ah_gallery', sanitize_text_field($_POST['ah_gallery']));
    }
});

// ── Admin columns ───────────────────────────
add_filter('manage_proyecto_posts_columns', function ($columns) {
    $new = [];
    foreach ($columns as $key => $val) {
        $new[$key] = $val;
        if ($key === 'title') {
            $new['ah_location'] = 'Ubicación';
            $new['ah_order'] = 'Orden';
        }
    }
    return $new;
});

add_action('manage_proyecto_posts_custom_column', function ($column, $post_id) {
    if ($column === 'ah_location') {
        echo esc_html(get_post_meta($post_id, '_ah_location', true));
    }
    if ($column === 'ah_order') {
        echo esc_html(get_post_meta($post_id, '_ah_order', true));
    }
}, 10, 2);

add_filter('manage_edit-proyecto_sortable_columns', function ($columns) {
    $columns['ah_order'] = 'ah_order';
    return $columns;
});
