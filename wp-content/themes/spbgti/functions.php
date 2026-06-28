<?php
/**
 * SPbGTI Foundation Theme Functions
 */

// Theme setup
add_action('after_setup_theme', function () {
    // Core WP features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 40,
        'width'       => 120,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', [
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'
    ]);
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('customize-selective-refresh-widgets');

    // Block editor color palette
    add_theme_support('editor-color-palette', [
        ['name' => 'Primary',   'slug' => 'primary',   'color' => '#0c1929'],
        ['name' => 'Secondary', 'slug' => 'secondary', 'color' => '#162a42'],
        ['name' => 'Accent',    'slug' => 'accent',    'color' => '#c9a227'],
        ['name' => 'Accent Light', 'slug' => 'accent-light', 'color' => '#e4c358'],
        ['name' => 'Background','slug' => 'bg',        'color' => '#f0f2f5'],
        ['name' => 'Text',      'slug' => 'text',      'color' => '#1e293b'],
    ]);

    // Menus
    register_nav_menus([
        'primary' => 'Основное меню',
        'footer'  => 'Меню в подвале',
    ]);

    // Default content width
    if (!isset($content_width)) $content_width = 1280;
});

// Enqueue assets
add_action('wp_enqueue_scripts', function () {
    $ver = wp_get_theme()->get('Version');
    wp_enqueue_style('spbgti-style', get_template_directory_uri() . '/assets/css/style.css', [], $ver);
    wp_enqueue_script('spbgti-main', get_template_directory_uri() . '/assets/js/main.js', [], $ver, true);
});

// Register widget areas
add_action('widgets_init', function () {
    register_sidebar([
        'name'          => 'Сайдбар слева',
        'id'            => 'sidebar-left',
        'description'   => 'Виджеты в левой колонке',
        'before_widget' => '<div class="sidebar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ]);
    register_sidebar([
        'name'          => 'Сайдбар справа',
        'id'            => 'sidebar-right',
        'description'   => 'Виджеты в правой колонке',
        'before_widget' => '<div class="sidebar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ]);
    register_sidebar([
        'name'          => 'Статистика (главная)',
        'id'            => 'stats-widget',
        'description'   => 'Блоки статистики на главной странице',
        'before_widget' => '<div class="stat %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="stat-value">',
        'after_title'   => '</span>',
    ]);
});

// Custom menu walker
class SPbGTI_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="sub-menu">';
    }
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $active = in_array('current-menu-item', $item->classes) ? ' active' : '';
        $output .= '<li class="menu-item' . $active . '">';
        $output .= '<a href="' . esc_url($item->url) . '" class="' . $active . '">' . esc_html($item->title) . '</a>';
    }
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}

// Customizer
add_action('customize_register', function ($wp_customize) {
    // Section: Hero section
    $wp_customize->add_section('spbgti_hero', [
        'title'    => 'Главный экран (Hero)',
        'priority' => 30,
    ]);

    $wp_customize->add_setting('hero_title', ['default' => 'ФОНД "ТЕХНОЛОГИЧЕСКОМУ ИНСТИТУТУ-200 ЛЕТ"', 'sanitize_callback' => 'wp_kses_post']);
    $wp_customize->add_control('hero_title', [
        'label'   => 'Заголовок',
        'section' => 'spbgti_hero',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('hero_subtitle', ['default' => 'К 200-летию Санкт-Петербургского<br>Технологического Института<br><span class="hero-years">(1828-2028)</span>', 'sanitize_callback' => 'wp_kses_post']);
    $wp_customize->add_control('hero_subtitle', [
        'label'   => 'Подзаголовок',
        'section' => 'spbgti_hero',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('hero_bg', ['sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_bg', [
        'label'    => 'Фоновое изображение',
        'section'  => 'spbgti_hero',
    ]));

    $wp_customize->add_setting('hero_bg_position', ['default' => 'center 33%', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('hero_bg_position', [
        'label'   => 'Позиция фона',
        'section' => 'spbgti_hero',
        'type'    => 'select',
        'choices' => [
            'center top'       => 'Вверху',
            'center 20%'       => 'Чуть выше',
            'center 33%'       => 'По центру (1/3)',
            'center'           => 'Центр',
            'center 66%'       => 'Чуть ниже',
            'center bottom'    => 'Внизу',
        ],
    ]);

    $wp_customize->add_setting('hero_overlay_color', ['default' => '#0c1929', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hero_overlay_color', [
        'label'    => 'Цвет затемнения',
        'section'  => 'spbgti_hero',
    ]));

    $wp_customize->add_setting('hero_overlay_opacity', ['default' => 0, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control('hero_overlay_opacity', [
        'label'       => 'Прозрачность затемнения',
        'section'     => 'spbgti_hero',
        'type'        => 'range',
        'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 5],
    ]);

    $wp_customize->add_setting('hero_textbox_color', ['default' => '#0c1929', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hero_textbox_color', [
        'label'    => 'Цвет подложки текста',
        'section'  => 'spbgti_hero',
    ]));

    $wp_customize->add_setting('hero_textbox_opacity', ['default' => 60, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control('hero_textbox_opacity', [
        'label'       => 'Прозрачность подложки текста',
        'section'     => 'spbgti_hero',
        'type'        => 'range',
        'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 5],
    ]);

    $wp_customize->add_setting('hero_textbox_blur', ['default' => 0, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control('hero_textbox_blur', [
        'label'       => 'Размытие фона (blur)',
        'section'     => 'spbgti_hero',
        'type'        => 'range',
        'input_attrs' => ['min' => 0, 'max' => 40, 'step' => 1],
    ]);

    $wp_customize->add_setting('hero_text_color', ['default' => '#ffffff', 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hero_text_color', [
        'label'    => 'Цвет текста',
        'section'  => 'spbgti_hero',
    ]));

    // Section: Contacts
    $wp_customize->add_section('spbgti_contacts', [
        'title'    => 'Контакты',
        'priority' => 40,
    ]);

    $fields = [
        'contact_email' => ['label' => 'Email', 'default' => 'mbrumina@rambler.ru'],
        'contact_phone' => ['label' => 'Телефон', 'default' => '+7 (812) XXX-XX-XX'],
        'contact_inn'   => ['label' => 'ИНН', 'default' => '7841041417'],
        'contact_ogrn'  => ['label' => 'ОГРН', 'default' => '1167800052826'],
        'contact_city'  => ['label' => 'Город', 'default' => 'Санкт-Петербург'],
    ];

    foreach ($fields as $key => $field) {
        $wp_customize->add_setting($key, ['default' => $field['default'], 'sanitize_callback' => 'sanitize_text_field']);
        $wp_customize->add_control($key, [
            'label'   => $field['label'],
            'section' => 'spbgti_contacts',
            'type'    => 'text',
        ]);
    }
});

// Editor styles
add_action('after_setup_theme', function () {
    add_editor_style('assets/css/editor.css');
});

// Excerpt
add_filter('excerpt_length', function () { return 20; });
add_filter('excerpt_more', function () { return '...'; });

// Custom Post Type: Инициативы
add_action('init', function () {
    register_post_type('initiative', [
        'labels' => [
            'name'               => 'Инициативы',
            'singular_name'      => 'Инициатива',
            'add_new'            => 'Добавить инициативу',
            'add_new_item'       => 'Новая инициатива',
            'edit_item'          => 'Редактировать инициативу',
            'view_item'          => 'Смотреть инициативу',
            'search_items'       => 'Поиск инициатив',
            'not_found'          => 'Инициативы не найдены',
            'not_found_in_trash' => 'В корзине нет инициатив',
        ],
        'public'       => true,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-megaphone',
        'menu_position' => 5,
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'initiative'],
        'show_in_menu' => true,
    ]);

    register_taxonomy('initiative_stage', 'initiative', [
        'labels' => [
            'name'              => 'Этапы',
            'singular_name'     => 'Этап',
            'add_new_item'      => 'Добавить этап',
            'edit_item'         => 'Редактировать этап',
        ],
        'public'       => true,
        'show_ui'      => true,
        'show_in_menu' => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'stage'],
    ]);

    // Seed default stages if none exist
    if (!term_exists('zaversheno', 'initiative_stage')) {
        wp_insert_term('Завершено', 'initiative_stage', ['slug' => 'zaversheno']);
        wp_insert_term('В процессе', 'initiative_stage', ['slug' => 'v-protsesse']);
        wp_insert_term('Запланировано', 'initiative_stage', ['slug' => 'zaplanirovano']);
        wp_insert_term('Идея', 'initiative_stage', ['slug' => 'ideya']);
    }
});

// Custom Post Type: Программы
add_action('init', function () {
    register_post_type('program', [
        'labels' => [
            'name'               => 'Программы',
            'singular_name'      => 'Программа',
            'add_new'            => 'Добавить программу',
            'add_new_item'       => 'Новая программа',
            'edit_item'          => 'Редактировать программу',
            'view_item'          => 'Смотреть программу',
            'search_items'       => 'Поиск программ',
            'not_found'          => 'Программы не найдены',
            'not_found_in_trash' => 'В корзине нет программ',
        ],
        'public'       => true,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-welcome-learn-more',
        'menu_position' => 6,
        'supports'     => ['title', 'editor', 'excerpt'],
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'program'],
        'show_in_menu' => true,
    ]);

    register_taxonomy('program_tag', 'program', [
        'labels' => [
            'name'              => 'Метки программ',
            'singular_name'     => 'Метка',
            'add_new_item'      => 'Добавить метку',
        ],
        'public'       => true,
        'show_ui'      => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'program-tag'],
    ]);

    register_taxonomy('program_status', 'program', [
        'labels' => [
            'name'              => 'Статусы',
            'singular_name'     => 'Статус',
            'add_new_item'      => 'Добавить статус',
        ],
        'public'       => true,
        'show_ui'      => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'program-status'],
    ]);

    if (!term_exists('deystvuyushchie', 'program_status')) {
        wp_insert_term('Действующие', 'program_status', ['slug' => 'deystvuyushchie']);
        wp_insert_term('Планируемые', 'program_status', ['slug' => 'planiruemye']);
    }

    if (!term_exists('stipendii', 'program_tag')) {
        wp_insert_term('Стипендии', 'program_tag', ['slug' => 'stipendii']);
        wp_insert_term('Хакатон', 'program_tag', ['slug' => 'hakaton']);
        wp_insert_term('Школа', 'program_tag', ['slug' => 'shkola']);
        wp_insert_term('Гранты', 'program_tag', ['slug' => 'granty']);
        wp_insert_term('Инвестиции', 'program_tag', ['slug' => 'investitsii']);
        wp_insert_term('Стажировки', 'program_tag', ['slug' => 'stazhirovki']);
    }
});

// Add columns to programs admin list
add_filter('manage_program_posts_columns', function ($columns) {
    $columns['status'] = 'Статус';
    $columns['tag'] = 'Метка';
    return $columns;
});
add_action('manage_program_posts_custom_column', function ($column, $post_id) {
    if ($column === 'status') {
        $terms = wp_get_post_terms($post_id, 'program_status');
        if ($terms) echo esc_html($terms[0]->name);
    }
    if ($column === 'tag') {
        $terms = wp_get_post_terms($post_id, 'program_tag');
        if ($terms) echo esc_html($terms[0]->name);
    }
}, 10, 2);

// Shortcode to display programs
add_shortcode('programs_list', function () {
    $statuses = ['deystvuyushchie' => 'Действующие программы', 'planiruemye' => 'Планируемые программы'];

    ob_start();
    foreach ($statuses as $slug => $label) {
        $term = get_term_by('slug', $slug, 'program_status');
        if (!$term) continue;

        $query = new WP_Query([
            'post_type' => 'program',
            'posts_per_page' => -1,
            'tax_query' => [[
                'taxonomy' => 'program_status',
                'field'    => 'slug',
                'terms'    => $slug,
            ]],
            'orderby' => 'date',
            'order' => 'ASC',
        ]);

        if (!$query->have_posts()) continue;

        echo '<div class="content-section"><h2>' . esc_html($label) . '</h2>';

        while ($query->have_posts()) {
            $query->the_post();
            $tag_terms = wp_get_post_terms(get_the_ID(), 'program_tag');
            $tag_text = $tag_terms ? $tag_terms[0]->name : '';
            echo '<div class="initiative-item">';
            if ($tag_text) echo '<span class="tag">' . esc_html($tag_text) . '</span>';
            echo '<h4>' . get_the_title() . '</h4>';
            echo '<p>' . (get_the_excerpt() ?: get_the_content()) . '</p>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();
    }

    return ob_get_clean();
});

// Custom Post Type: Партнёры
add_action('init', function () {
    register_post_type('partner', [
        'labels' => [
            'name'               => 'Партнёры',
            'singular_name'      => 'Партнёр',
            'add_new'            => 'Добавить партнёра',
            'add_new_item'       => 'Новый партнёр',
            'edit_item'          => 'Редактировать партнёра',
            'view_item'          => 'Смотреть партнёра',
            'search_items'       => 'Поиск партнёров',
            'not_found'          => 'Партнёры не найдены',
            'not_found_in_trash' => 'В корзине нет партнёров',
        ],
        'public'       => true,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-groups',
        'menu_position' => 7,
        'supports'     => ['title', 'editor', 'excerpt', 'page-attributes'],
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'partner'],
        'show_in_menu' => true,
    ]);

    register_taxonomy('partner_category', 'partner', [
        'labels' => [
            'name'              => 'Категории',
            'singular_name'     => 'Категория',
            'add_new_item'      => 'Добавить категорию',
        ],
        'public'       => true,
        'show_ui'      => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'partner-cat'],
    ]);

    if (!term_exists('obrazovanie', 'partner_category')) {
        wp_insert_term('Образование', 'partner_category', ['slug' => 'obrazovanie']);
        wp_insert_term('Государство', 'partner_category', ['slug' => 'gosudarstvo']);
        wp_insert_term('Федерация', 'partner_category', ['slug' => 'federatsiya']);
        wp_insert_term('Промышленность', 'partner_category', ['slug' => 'promyshlennost']);
        wp_insert_term('Бизнес', 'partner_category', ['slug' => 'biznes']);
    }
});

add_filter('manage_partner_posts_columns', function ($columns) {
    $columns['category'] = 'Категория';
    $columns['menu_order'] = 'Порядок';
    return $columns;
});
add_action('manage_partner_posts_custom_column', function ($column, $post_id) {
    if ($column === 'category') {
        $terms = wp_get_post_terms($post_id, 'partner_category');
        if ($terms) echo esc_html($terms[0]->name);
    }
    if ($column === 'menu_order') {
        echo get_post_field('menu_order', $post_id);
    }
}, 10, 2);

// Make order column sortable
add_filter('manage_edit-partner_sortable_columns', function ($columns) {
    $columns['menu_order'] = 'menu_order';
    return $columns;
});

// Partner website URL meta box
add_action('add_meta_boxes', function () {
    add_meta_box('partner_url', 'Сайт партнёра', function ($post) {
        $url = get_post_meta($post->ID, 'partner_url', true);
        echo '<p><label>URL сайта:<br><input type="url" name="partner_url" value="' . esc_attr($url) . '" style="width:100%" placeholder="https://example.com"></label></p>';
    }, 'partner', 'normal', 'default');
});

add_action('save_post_partner', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    $url = isset($_POST['partner_url']) ? sanitize_text_field(wp_unslash($_POST['partner_url'])) : '';
    if ($url && !preg_match('#^https?://#i', $url)) {
        $url = 'https://' . $url;
    }
    update_post_meta($post_id, 'partner_url', $url);
});

add_shortcode('partners_list', function () {
    $query = new WP_Query([
        'post_type' => 'partner',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);

    if (!$query->have_posts()) return '<p>Партнёры скоро появятся</p>';

    ob_start();
    echo '<div class="content-section"><h2>Наши партнёры</h2>';

    while ($query->have_posts()) {
        $query->the_post();
        $cat_terms = wp_get_post_terms(get_the_ID(), 'partner_category');
        $tag_text = $cat_terms ? $cat_terms[0]->name : '';
        echo '<div class="initiative-item">';
        echo '<h3 class="fw-toc-heading" style="font-size:1rem;margin:0 0 4px;font-weight:500;color:var(--text)">' . get_the_title() . '</h3>';
        if ($tag_text) echo '<span class="tag">' . esc_html($tag_text) . '</span>';
        echo '<p>' . (get_the_excerpt() ?: get_the_content()) . '</p>';
        echo '</div>';
    }

    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
});

// Add stage column to admin list
add_filter('manage_initiative_posts_columns', function ($columns) {
    $columns['stage'] = 'Этап';
    return $columns;
});
add_action('manage_initiative_posts_custom_column', function ($column, $post_id) {
    if ($column === 'stage') {
        $terms = wp_get_post_terms($post_id, 'initiative_stage');
        if ($terms) echo esc_html($terms[0]->name);
    }
}, 10, 2);

// Shortcode to display initiatives
add_shortcode('initiatives_list', function () {
    $stages = get_terms([
        'taxonomy' => 'initiative_stage',
        'hide_empty' => false,
        'orderby' => 'term_id',
        'order' => 'ASC',
    ]);

    ob_start();
    foreach ($stages as $term) {
        $slug = $term->slug;

        $query = new WP_Query([
            'post_type' => 'initiative',
            'posts_per_page' => -1,
            'tax_query' => [[
                'taxonomy' => 'initiative_stage',
                'field'    => 'slug',
                'terms'    => $slug,
            ]],
            'orderby' => 'date',
            'order' => 'DESC',
        ]);

        if (!$query->have_posts()) continue;

        echo '<div class="content-section"><h2>' . esc_html($term->name) . '</h2>';

        while ($query->have_posts()) {
            $query->the_post();
            $tag_text = $term->name;
            echo '<div class="initiative-item">';
            echo '<span class="tag">' . esc_html($tag_text) . '</span>';
            echo '<h4>' . get_the_title() . '</h4>';
            echo '<p>' . get_the_excerpt() . '</p>';
            echo '</div>';
        }

        echo '</div>';
        wp_reset_postdata();
    }

    return ob_get_clean();
});

// Allow font uploads
add_filter('upload_mimes', function ($mimes) {
    $mimes['ttf']   = 'font/ttf';
    $mimes['otf']   = 'font/otf';
    $mimes['woff']  = 'font/woff';
    $mimes['woff2'] = 'font/woff2';
    $mimes['eot']   = 'application/vnd.ms-fontobject';
    return $mimes;
});

// Get post thumbnail with fallback to first content image, then to default
function spbgti_get_thumb_src($post_id = null) {
    if (!$post_id) $post_id = get_the_ID();

    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, 'medium');
    }

    $content = get_post_field('post_content', $post_id);
    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $m)) {
        return $m[1];
    }

    return get_template_directory_uri() . '/assets/img/no-photo.png';
}

// Auto-compress uploaded images
add_filter('jpeg_quality', function () { return 82; });

// Move Simple History above plugins in admin menu
add_filter('wp_handle_upload_prefilter', function ($file) {
    $max_w = 1920;
    $max_h = 1920;
    $type = $file['type'];
    if ($type !== 'image/jpeg' && $type !== 'image/png') return $file;

    $img = wp_get_image_editor($file['tmp_name']);
    if (is_wp_error($img)) return $file;

    $size = $img->get_size();
    if ($size['width'] > $max_w || $size['height'] > $max_h) {
        $img->resize($max_w, $max_h, false);
        $img->set_quality(82);
        $img->save($file['tmp_name']);
    }
    return $file;
});

// Menu fallback (no menu assigned)
function spbgti_menu_fallback() {
    echo '<ul class="nav-links">';
    echo '<li><a href="' . esc_url(home_url()) . '">Главная</a></li>';
    wp_list_pages(['title_li' => '', 'echo' => true]);
    echo '</ul>';
}
