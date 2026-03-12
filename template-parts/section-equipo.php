<?php
// Team members from a repeatable customizer approach
// For simplicity: uses pages with 'equipo' category or a dedicated query
// The simplest approach: query pages assigned to a specific parent page called "Equipo"
$equipo_parent = get_page_by_path('equipo');
$members = [];

if ($equipo_parent) {
    $member_query = new WP_Query([
        'post_type'      => 'page',
        'post_parent'    => $equipo_parent->ID,
        'posts_per_page' => 10,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ]);
    if ($member_query->have_posts()) {
        while ($member_query->have_posts()) {
            $member_query->the_post();
            $role = get_post_meta(get_the_ID(), '_ah_team_role', true);
            if (!$role) $role = get_the_excerpt();
            $members[] = [
                'name'  => get_the_title(),
                'role'  => $role,
                'photo' => get_the_post_thumbnail_url(get_the_ID(), 'ah-team'),
            ];
        }
        wp_reset_postdata();
    }
}

// Fallback: hardcoded team if no pages exist yet
if (empty($members)) {
    $members = [
        ['name' => 'Elena', 'role' => 'Dirección creativa', 'photo' => ''],
        ['name' => 'Patri', 'role' => 'Diseño de interiores', 'photo' => ''],
        ['name' => 'Gabriela', 'role' => 'Diseño de interiores', 'photo' => ''],
        ['name' => 'Esther', 'role' => 'Gestión de proyectos', 'photo' => ''],
    ];
}

if (!empty($members)) : ?>
<section class="section equipo" id="equipo">
    <div class="container">
        <div class="equipo__header reveal">
            <span class="section__label">Equipo</span>
            <h2 class="section__title">Las personas detrás<br>de cada proyecto</h2>
        </div>
        <div class="equipo__grid">
            <?php foreach ($members as $member) : ?>
                <div class="equipo__member reveal">
                    <div class="equipo__photo">
                        <?php if (!empty($member['photo'])) : ?>
                            <img src="<?php echo esc_url($member['photo']); ?>" alt="<?php echo esc_attr($member['name']); ?>" loading="lazy">
                        <?php else : ?>
                            <div style="width:100%;height:100%;background:var(--color-bg-warm);"></div>
                        <?php endif; ?>
                    </div>
                    <h3 class="equipo__name"><?php echo esc_html($member['name']); ?></h3>
                    <p class="equipo__role"><?php echo esc_html($member['role']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
