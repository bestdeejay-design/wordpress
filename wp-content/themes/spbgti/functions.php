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
});

// Admin settings page for contacts
$foundation_contacts_defaults = [
    'email'   => 'mbrulina@rambler.ru',
    'phone'   => '+7 (812) XXX-XX-XX',
    'address' => 'Загородный проспект, д. 2',
    'city'    => 'Санкт-Петербург',
    'inn'     => '7841041417',
    'kpp'     => '784101001',
    'ogrn'    => '1167800052826',
    'bank'    => 'ПАО Сбербанк',
    'bik'     => '044030653',
    'account' => '40703810955000000153',
];

add_action('admin_menu', function () {
    add_options_page(
        'Контакты фонда',
        'Контакты фонда',
        'manage_options',
        'foundation-contacts',
        'foundation_contacts_page'
    );
});

add_action('admin_init', function () {
    register_setting('foundation_contacts', 'foundation_contacts');
});

function foundation_contacts_page() {
    global $foundation_contacts_defaults;
    $contacts = get_option('foundation_contacts', $foundation_contacts_defaults);
    ?>
    <div class="wrap">
        <h1>Контакты фонда</h1>
        <div class="notice notice-info" style="background:#f0f6fc;border-left-color:#72aee6;">
            <p><strong>Основные контакты</strong> — телефон, email, адрес и город отображаются в подвале сайта и через шорткод <code>[contact field="..."]</code>.</p>
            <p><strong>Реквизиты</strong> — ИНН, КПП, ОГРН, банковские реквизиты выводятся шорткодом <code>[contact_requisites]</code>.</p>
        </div>
        <form method="post" action="options.php">
            <?php settings_fields('foundation_contacts'); ?>
            <table class="form-table" role="presentation">
                <tr><th colspan="2"><h2 style="margin:0">Основные контакты</h2></th></tr>
                <tr><th scope="row"><label for="fc_email">Email</label></th><td><input name="foundation_contacts[email]" type="email" id="fc_email" value="<?php echo esc_attr($contacts['email']) ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><label for="fc_phone">Телефон</label></th><td><input name="foundation_contacts[phone]" type="text" id="fc_phone" value="<?php echo esc_attr($contacts['phone']) ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><label for="fc_city">Город</label></th><td><input name="foundation_contacts[city]" type="text" id="fc_city" value="<?php echo esc_attr($contacts['city']) ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><label for="fc_address">Адрес</label></th><td><input name="foundation_contacts[address]" type="text" id="fc_address" value="<?php echo esc_attr($contacts['address']) ?>" class="regular-text"></td></tr>


                <tr><th colspan="2"><h2 style="margin:0">Реквизиты</h2></th></tr>
                <tr><th scope="row"><label for="fc_inn">ИНН</label></th><td><input name="foundation_contacts[inn]" type="text" id="fc_inn" value="<?php echo esc_attr($contacts['inn']) ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><label for="fc_kpp">КПП</label></th><td><input name="foundation_contacts[kpp]" type="text" id="fc_kpp" value="<?php echo esc_attr($contacts['kpp']) ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><label for="fc_ogrn">ОГРН</label></th><td><input name="foundation_contacts[ogrn]" type="text" id="fc_ogrn" value="<?php echo esc_attr($contacts['ogrn']) ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><label for="fc_bank">Банк</label></th><td><input name="foundation_contacts[bank]" type="text" id="fc_bank" value="<?php echo esc_attr($contacts['bank']) ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><label for="fc_bik">БИК</label></th><td><input name="foundation_contacts[bik]" type="text" id="fc_bik" value="<?php echo esc_attr($contacts['bik']) ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><label for="fc_account">Расчётный счёт</label></th><td><input name="foundation_contacts[account]" type="text" id="fc_account" value="<?php echo esc_attr($contacts['account']) ?>" class="regular-text"></td></tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

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
    $terms = get_terms(['taxonomy' => 'program_status', 'hide_empty' => false, 'orderby' => 'term_id', 'order' => 'ASC']);
    if (is_wp_error($terms) || empty($terms)) return '<p>Программы скоро появятся</p>';

    ob_start();
    foreach ($terms as $term) {
        $query = new WP_Query([
            'post_type' => 'program',
            'posts_per_page' => -1,
            'tax_query' => [[
                'taxonomy' => 'program_status',
                'field'    => 'slug',
                'terms'    => $term->slug,
            ]],
            'orderby' => 'date',
            'order' => 'ASC',
        ]);

        if (!$query->have_posts()) continue;

        echo '<div class="content-section"><h2>' . esc_html($term->name) . '</h2>';

        while ($query->have_posts()) {
            $query->the_post();
            $tag_terms = wp_get_post_terms(get_the_ID(), 'program_tag');
            $tag_text = $tag_terms ? $tag_terms[0]->name : '';
            echo '<div class="initiative-item">';
            if ($tag_text) echo '<span class="tag">' . esc_html($tag_text) . '</span>';
            echo '<h4>' . get_the_title() . '</h4>';
        $pcontent = get_post_field("post_content", get_the_ID());
        echo '<p>' . ($pcontent ?: get_the_excerpt()) . '</p>';
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

// Custom Post Type: Контакты (сотрудники)
add_action('init', function () {
    register_post_type('contact', [
        'labels' => [
            'name'               => 'Контакты',
            'singular_name'      => 'Контакт',
            'add_new'            => 'Добавить контакт',
            'add_new_item'       => 'Новый контакт',
            'edit_item'          => 'Редактировать контакт',
            'view_item'          => 'Смотреть контакт',
            'search_items'       => 'Поиск контактов',
            'not_found'          => 'Контакты не найдены',
            'not_found_in_trash' => 'В корзине нет контактов',
        ],
        'public'       => true,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-id',
        'menu_position' => 8,
        'supports'     => ['title', 'editor', 'thumbnail', 'page-attributes'],
        'has_archive'  => false,
        'rewrite'      => ['slug' => 'contact'],
        'show_in_menu' => true,
    ]);

    register_taxonomy('contact_label', 'contact', [
        'labels' => [
            'name'              => 'Метки',
            'singular_name'     => 'Метка',
            'add_new_item'      => 'Добавить метку',
        ],
        'public'       => true,
        'show_ui'      => true,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'contact-label'],
    ]);

    if (!term_exists('board', 'contact_label')) {
        wp_insert_term('Руководство фонда', 'contact_label', ['slug' => 'board']);
        wp_insert_term('Сотрудники', 'contact_label', ['slug' => 'staff']);
    }
});

// Contact meta boxes
add_action('add_meta_boxes', function () {
    add_meta_box('contact_details', 'Детали контакта', function ($post) {
        $email = get_post_meta($post->ID, '_contact_email', true);
        $phone = get_post_meta($post->ID, '_contact_phone', true);
        $role  = get_post_meta($post->ID, '_contact_role', true);
        wp_nonce_field('contact_details_save', 'contact_details_nonce');
        echo '<p><label>Описание должности: <input type="text" name="contact_role" value="' . esc_attr($role) . '" class="widefat"></label></p>';
        echo '<p style="color:#666;font-size:12px;margin-top:-8px">Основное описание сотрудника пишите в редакторе под заголовком.</p>';
        echo '<p><label>Email: <input type="email" name="contact_email" value="' . esc_attr($email) . '" class="widefat"></label></p>';
        echo '<p><label>Телефон: <input type="text" name="contact_phone" value="' . esc_attr($phone) . '" class="widefat" placeholder="+7 (812) 999-99-99"></label></p>';
    }, 'contact', 'normal', 'high');
});

add_action('save_post_contact', function ($post_id) {
    if (!isset($_POST['contact_details_nonce']) || !wp_verify_nonce($_POST['contact_details_nonce'], 'contact_details_save')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    foreach (['contact_email', 'contact_phone', 'contact_role'] as $f) {
        if (!isset($_POST[$f])) continue;
        $val = $_POST[$f];
        if ($f === 'contact_phone') {
            $val = preg_replace('/[^+0-9\s\-\(\)]/', '', $val);
        } else {
            $val = sanitize_text_field($val);
        }
        update_post_meta($post_id, '_' . $f, $val);
    }
});

// Add columns to contact admin list
add_filter('manage_contact_posts_columns', function ($columns) {
    $cols = [];
    foreach ($columns as $k => $v) {
        $cols[$k] = $v;
        if ($k === 'title') {
            $cols['role'] = 'Должность';
            $cols['email_col'] = 'Email';
        }
    }
    return $cols;
});

add_action('manage_contact_posts_custom_column', function ($column, $post_id = 0) {
    if (!$post_id) $post_id = get_the_ID();
    if ($column === 'role') echo esc_html(get_post_meta($post_id, '_contact_role', true) ?: '—');
    if ($column === 'email_col') echo esc_html(get_post_meta($post_id, '_contact_email', true) ?: '—');
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
        echo '<p><label>URL сайта:<br><input type="text" name="partner_url" value="' . esc_attr($url) . '" style="width:100%" placeholder="axiiom.ru (https:// добавится автоматически)"></label></p>';
    }, 'partner', 'normal', 'default');
});

add_action('save_post_partner', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    $url = isset($_POST['partner_url']) ? trim(wp_unslash($_POST['partner_url'])) : '';
    if ($url && !preg_match('#^https?://#i', $url)) {
        $url = 'https://' . $url;
    }
    update_post_meta($post_id, 'partner_url', esc_url_raw($url));
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
        $partner_post = get_post();
        $cat_terms = wp_get_post_terms($partner_post->ID, 'partner_category');
        $tag_text = $cat_terms ? $cat_terms[0]->name : '';
        echo '<div class="initiative-item">';
        echo '<h3 class="fw-toc-heading" style="font-size:1rem;margin:0 0 4px;font-weight:500;color:var(--text)">' . get_the_title() . '</h3>';
        if ($tag_text) echo '<span class="tag">' . esc_html($tag_text) . '</span>';
        $pdesc = !empty(trim($partner_post->post_content)) ? $partner_post->post_content : get_the_excerpt();
        echo '<p>' . $pdesc . '</p>';
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

// Shortcode: [contact field="phone"] — выводит одно поле из настроек Контакты фонда
add_shortcode('contact', function ($atts) {
    $a = shortcode_atts(['field' => 'phone'], $atts);
    $defaults = [
        'email'   => 'mbrulina@rambler.ru',
        'phone'   => '+7 (812) XXX-XX-XX',
        'address' => 'Загородный проспект, д. 2',
        'city'    => 'Санкт-Петербург',
        'inn'     => '7841041417',
        'kpp'     => '784101001',
        'ogrn'    => '1167800052826',
        'bank'    => 'ПАО Сбербанк',
        'bik'     => '044030653',
        'account' => '40703810955000000153',
    ];
    $c = get_option('foundation_contacts', $defaults);
    $val = $c[$a['field']] ?? '';
    if ($a['field'] === 'email') $val = antispambot($val);
    return esc_html($val);
});

// Shortcode: [contacts_list label="board"] — список людей из CPT Контакты по метке
add_shortcode('contacts_list', function ($atts) {
    $a = shortcode_atts(['label' => 'board'], $atts);
    $people = get_posts([
        'post_type' => 'contact',
        'posts_per_page' => -1,
        'tax_query' => [[
            'taxonomy' => 'contact_label',
            'field'    => 'slug',
            'terms'    => $a['label'],
        ]],
        'orderby' => 'menu_order',
        'order'   => 'ASC',
    ]);
    if (empty($people)) return '';
    ob_start();
    foreach ($people as $person) {
        $p_email = antispambot(get_post_meta($person->ID, '_contact_email', true));
        $p_phone = get_post_meta($person->ID, '_contact_phone', true);
        $p_role  = get_post_meta($person->ID, '_contact_role', true);
        echo '<div class="initiative-item">';
        echo '<h4>' . esc_html($person->post_title) . '</h4>';
        echo '<p>';
        if ($p_role) echo esc_html($p_role) . '<br>';
        if ($p_email) echo 'Email: <a href="mailto:' . $p_email . '">' . $p_email . '</a>';
        if ($p_phone && $p_email) echo '<br>';
        if ($p_phone) echo esc_html($p_phone);
        echo '</p></div>';
    }
    return ob_get_clean();
});

// Shortcode: [contacts_by_label] — все метки с контактами (динамически)
add_shortcode('contacts_by_label', function () {
    $terms = get_terms(['taxonomy' => 'contact_label', 'hide_empty' => true, 'orderby' => 'term_id', 'order' => 'ASC']);
    if (empty($terms) || is_wp_error($terms)) return '';
    ob_start();
    foreach ($terms as $term) {
        $people = get_posts([
            'post_type' => 'contact',
            'posts_per_page' => -1,
            'tax_query' => [['taxonomy' => 'contact_label', 'field' => 'slug', 'terms' => $term->slug]],
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]);
        if (empty($people)) continue;
        echo '<div class="content-section">';
        echo '<h2>' . esc_html($term->name) . '</h2>';
        foreach ($people as $person) {
            $p_email = antispambot(get_post_meta($person->ID, '_contact_email', true));
            $p_phone = get_post_meta($person->ID, '_contact_phone', true);
            $p_role  = get_post_meta($person->ID, '_contact_role', true);
            $p_desc  = apply_filters('the_content', get_post_field('post_content', $person->ID));
            echo '<div class="initiative-item">';
            echo '<h4>' . esc_html($person->post_title) . '</h4>';
            if ($p_role) echo '<p><em>' . esc_html($p_role) . '</em></p>';
            if ($p_desc && trim($p_desc) !== '') echo '<div style="font-size:0.9rem;margin-bottom:8px">' . $p_desc . '</div>';
            echo '<p>';
            if ($p_email) echo 'Email: <a href="mailto:' . $p_email . '">' . $p_email . '</a>';
            if ($p_phone && $p_email) echo '<br>';
            if ($p_phone) echo esc_html($p_phone);
            echo '</p></div>';
        }
        echo '</div>';
    }
    return ob_get_clean();
});

// Shortcode: [contact_email_link] — защищённая email-ссылка
add_shortcode('contact_email_link', function () {
    $defaults = ['email' => 'mbrulina@rambler.ru'];
    $c = get_option('foundation_contacts', $defaults);
    $email = antispambot($c['email']);
    return '<a href="mailto:' . $email . '">' . $email . '</a>';
});

// Shortcode: [contact_requisites] — банковские реквизиты
add_shortcode('contact_requisites', function () {
    $defaults = [
        'inn'     => '7841041417',
        'kpp'     => '784101001',
        'ogrn'    => '1167800052826',
        'bank'    => 'ПАО Сбербанк',
        'bik'     => '044030653',
        'account' => '40703810955000000153',
    ];
    $c = get_option('foundation_contacts', $defaults);
    $items = [
        'ИНН' => $c['inn'],
        'КПП' => $c['kpp'],
        'ОГРН' => $c['ogrn'],
        'Расчётный счёт' => $c['account'],
        'Банк' => $c['bank'],
        'БИК' => $c['bik'],
    ];
    $out = '<ul class="contacts-list">';
    foreach ($items as $label => $val) {
        $out .= '<li><span class="label">' . esc_html($label) . '</span><span class="value">' . esc_html($val) . '</span></li>';
    }
    return $out . '</ul>';
});

// Contextual help tabs for custom post types
function spbgti_cpt_help_tabs() {
    $screen = get_current_screen();
    if (!$screen || !in_array($screen->post_type, ['initiative', 'program', 'partner', 'contact'])) {
        return;
    }
    $content = '';
    switch ($screen->post_type) {
        case 'initiative':
            $content = '<p><strong>Заголовок</strong> — название инициативы, отображается на сайте.</p>
                    <p><strong>Редактор</strong> — подробное описание инициативы.</p>
                    <p><strong>Этапы (таксономия)</strong> — текущий статус: Идея, Запланировано, В процессе, Завершено.</p>
                    <p><strong>Цитата</strong> — краткое описание для карточки инициативы на сайте.</p>
                    <p><strong>Миниатюра (изображение)</strong> — картинка для превью инициативы.</p>';
            break;
        case 'program':
            $content = '<p><strong>Заголовок</strong> — название программы.</p>
                    <p><strong>Редактор</strong> — развёрнутое описание программы.</p>
                    <p><strong>Статус (таксономия program_status)</strong> — Действующая или Планируемая.</p>
                    <p><strong>Метка (таксономия program_tag)</strong> — тип программы: Стипендии, Хакатон, Школа, Гранты, Инвестиции, Стажировки.</p>';
            break;
        case 'partner':
            $content = '<p><strong>Заголовок</strong> — название организации-партнёра.</p>
                    <p><strong>Редактор</strong> — описание партнёра.</p>
                    <p><strong>Цитата</strong> — краткое описание, отображается в виджете партнёров на сайте.</p>
                    <p><strong>Категория (таксономия partner_category)</strong> — Образование, Государство, Промышленность, Бизнес и т.д.</p>
                    <p><strong>Сайт партнёра (мета-поле partner_url)</strong> — URL сайта организации (https:// добавится автоматически).</p>';
            break;
        case 'contact':
            $content = '<p><strong>Заголовок</strong> — ФИО сотрудника.</p>
                    <p><strong>Редактор (под заголовком)</strong> — подробное описание сотрудника, его обязанности, биография.</p>
                    <p><strong>Метки (таксономия contact_label)</strong> — Руководство фонда или Сотрудники. Определяет, в каком разделе страницы контактов появится запись.</p>
                    <p><strong>Описание должности, Email, Телефон (мета-поля)</strong> — контактные данные, отображаются на странице контактов.</p>';
            break;
    }
    if ($content) {
        $screen->add_help_tab([
            'id'      => 'spbgti_help',
            'title'   => 'Обзор полей',
            'content' => $content,
        ]);
    }
}
add_action('load-post.php', 'spbgti_cpt_help_tabs');
add_action('load-post-new.php', 'spbgti_cpt_help_tabs');

// Help page
add_action('admin_menu', function () {
    add_dashboard_page('Помощь по сайту', 'Помощь', 'edit_posts', 'spbgti-help', function () { ?>
<div class="wrap"><h1>Помощь по управлению сайтом</h1>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;margin-top:20px">
<div class="postbox"><div class="postbox-header"><h2 class="hndle">Инициативы</h2></div><div class="inside">
<p><strong>Где:</strong> Инициативы → Добавить инициативу</p>
<p><strong>Заголовок</strong> — название инициативы</p>
<p><strong>Редактор</strong> — подробное описание</p>
<p><strong>Этапы</strong> — статус: Идея → Запланировано → В процессе → Завершено</p>
<p><strong>Цитата</strong> — краткое описание для карточки</p>
<p><strong>Как выглядит на сайте:</strong> группируется по этапам на странице /initiatives/. Каждый этап — отдельный раздел.</p>
</div></div>
<div class="postbox"><div class="postbox-header"><h2 class="hndle">Программы</h2></div><div class="inside">
<p><strong>Где:</strong> Программы → Добавить программу</p>
<p><strong>Заголовок</strong> — название программы</p>
<p><strong>Редактор</strong> — описание программы</p>
<p><strong>Статус (таксономия)</strong> — Действующие / Планируемые / Завершенные</p>
<p><strong>Метка (таксономия)</strong> — Стипендии, Хакатон, Школа, Гранты и т.д.</p>
<p><strong>Как выглядит на сайте:</strong> группируется по статусам на странице /programs/. Статусы добавляются в таксономии «Статусы».</p>
</div></div>
<div class="postbox"><div class="postbox-header"><h2 class="hndle">Партнёры</h2></div><div class="inside">
<p><strong>Где:</strong> Партнёры → Добавить партнёра</p>
<p><strong>Заголовок</strong> — название организации</p>
<p><strong>Редактор</strong> — развёрнутое описание (показывается на странице /partners/)</p>
<p><strong>Цитата</strong> — краткое описание (показывается в виджете на главной и в сайдбаре)</p>
<p><strong>Категория</strong> — Образование, Государство, Промышленность, Бизнес и т.д.</p>
<p><strong>Сайт партнёра (мета-поле)</strong> — URL сайта. https:// добавится автоматически.</p>
</div></div>
<div class="postbox"><div class="postbox-header"><h2 class="hndle">Контакты (сотрудники)</h2></div><div class="inside">
<p><strong>Где:</strong> Контакты → Добавить контакт</p>
<p><strong>Заголовок</strong> — ФИО сотрудника</p>
<p><strong>Редактор</strong> — биография, обязанности</p>
<p><strong>Метки</strong> — Руководство фонда / Сотрудники. Определяет раздел на странице контактов.</p>
<p><strong>Описание должности, Email, Телефон</strong> — контактные данные</p>
<p><strong>Как добавить новый раздел?</strong> Создайте новую метку в Контакты → Метки, добавьте туда сотрудников — раздел появится автоматически на странице контактов.</p>
</div></div>
<div class="postbox"><div class="postbox-header"><h2 class="hndle">Контакты фонда (глобальные)</h2></div><div class="inside">
<p><strong>Где:</strong> Настройки → Контакты фонда</p>
<p>Телефон, email, адрес — отображаются в подвале сайта и через шорткод <code>[contact field="..."]</code></p>
<p>Реквизиты — ИНН, КПП, ОГРН, банк, счёт — выводятся шорткодом <code>[contact_requisites]</code></p>
</div></div>
<div class="postbox"><div class="postbox-header"><h2 class="hndle">Новости</h2></div><div class="inside">
<p><strong>Где:</strong> Записи → Добавить запись</p>
<p>Обычные записи WordPress. Отображаются на странице /news/ в виде ленты.</p>
<p>Миниатюра записи — фото для превью в ленте.</p>
</div></div>
<div class="postbox"><div class="postbox-header"><h2 class="hndle">Страница «О фонде»</h2></div><div class="inside">
<p><strong>Где:</strong> Страницы → О фонде</p>
<p>Редактируется как обычная WordPress-страница. Используйте HTML-блоки для вёрстки.</p>
<p>Доступные CSS-классы для оформления:</p>
<ul>
<li><code>content-section</code> — белый блок с рамкой</li>
<li><code>contacts-list</code> — список вида «название: значение» (для реквизитов)</li>
<li><code>initiative-item</code> — карточка с заголовком и текстом</li>
</ul>
</div></div>
<div class="postbox"><div class="postbox-header"><h2 class="hndle">Шорткоды</h2></div><div class="inside">
<table class="widefat striped">
<thead><tr><th>Шорткод</th><th>Что выводит</th></tr></thead>
<tbody>
<tr><td><code>[contact field="phone"]</code></td><td>Телефон из настроек</td></tr>
<tr><td><code>[contact_email_link]</code></td><td>Email ссылкой (защита от спама)</td></tr>
<tr><td><code>[contact_requisites]</code></td><td>Банковские реквизиты</td></tr>
<tr><td><code>[contacts_by_label]</code></td><td>Все разделы контактов (динамически)</td></tr>
<tr><td><code>[contacts_list label="board"]</code></td><td>Список контактов по метке</td></tr>
<tr><td><code>[programs_list]</code></td><td>Все программы, сгруппированные по статусам</td></tr>
<tr><td><code>[initiatives_list]</code></td><td>Все инициативы, сгруппированные по этапам</td></tr>
<tr><td><code>[partners_list]</code></td><td>Список всех партнёров</td></tr>
</tbody></table>
</div></div>
</div></div>
<?php });
});

// Dashboard widget with help links
add_action('wp_dashboard_setup', function () {
    wp_add_dashboard_widget('spbgti_help_widget', 'Быстрая справка', function () {
        echo '<p><strong>Разделы сайта:</strong></p>';
        echo '<ul style="margin:0 0 12px 16px;list-style:disc">';
        echo '<li><b>Инициативы</b> — Инициативы → Добавить инициативу</li>';
        echo '<li><b>Программы</b> — Программы → Добавить программу</li>';
        echo '<li><b>Партнёры</b> — Партнёры → Добавить партнёра</li>';
        echo '<li><b>Контакты (люди)</b> — Контакты → Добавить контакт</li>';
        echo '<li><b>Контакты фонда</b> — Настройки → Контакты фонда</li>';
        echo '<li><b>Новости</b> — Записи → Добавить запись</li>';
        echo '<li><b>Страницы</b> — Страницы (О фонде, Контакты, Галерея)</li>';
        echo '</ul>';
        echo '<p><a href="' . admin_url('index.php?page=spbgti-help') . '" class="button">Полная справка</a></p>';
    });
});

// Gallery visibility meta
add_action('add_attachment', function ($post_id) {
    add_post_meta($post_id, '_show_in_gallery', 1, true);
});

add_filter('attachment_fields_to_edit', function ($form_fields, $post) {
    $value = get_post_meta($post->ID, '_show_in_gallery', true);
    if ($value === '') $value = 1;
    $form_fields['show_in_gallery'] = [
        'label' => 'В галерее',
        'input' => 'html',
        'html'  => '<label><input type="checkbox" name="attachments[' . $post->ID . '][show_in_gallery]" value="1" ' . checked($value, 1, false) . '> Показывать на странице галереи</label>',
        'value' => $value,
    ];
    return $form_fields;
}, 10, 2);

add_filter('attachment_fields_to_save', function ($post, $attachment) {
    if (isset($attachment['show_in_gallery'])) {
        update_post_meta($post['ID'], '_show_in_gallery', 1);
    } else {
        update_post_meta($post['ID'], '_show_in_gallery', 0);
    }
    return $post;
}, 10, 2);

add_filter('manage_media_columns', function ($columns) {
    $columns['show_in_gallery'] = 'В галерее';
    return $columns;
});

add_action('manage_media_custom_column', function ($column_name, $post_id) {
    if ($column_name === 'show_in_gallery') {
        $value = get_post_meta($post_id, '_show_in_gallery', true);
        $checked = $value == 1 ? ' checked' : '';
        echo '<label><input type="checkbox" class="show-in-gallery-toggle" data-id="' . $post_id . '"' . $checked . '></label>';
    }
}, 10, 2);

add_action('admin_print_footer_scripts', function () {
    if (get_current_screen()->base !== 'upload') return;
    ?>
<script>
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('show-in-gallery-toggle')) {
        var data = new FormData();
        data.append('action', 'toggle_show_in_gallery');
        data.append('id', e.target.dataset.id);
        data.append('value', e.target.checked ? 1 : 0);
        navigator.sendBeacon(ajaxurl, data);
    }
});
</script>
    <?php
});

add_action('wp_ajax_toggle_show_in_gallery', function () {
    $id = intval($_POST['id']);
    $val = intval($_POST['value']);
    if ($id && current_user_can('edit_post', $id)) {
        update_post_meta($id, '_show_in_gallery', $val);
        wp_die('1');
    }
    wp_die('0');
});
