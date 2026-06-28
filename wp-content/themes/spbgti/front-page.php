<?php get_header(); 

$target_date = '2028-11-28T12:00:00+03:00';
$hero_title = get_theme_mod('hero_title', 'ФОНД "ТЕХНОЛОГИЧЕСКОМУ ИНСТИТУТУ-200 ЛЕТ"');
$hero_subtitle = get_theme_mod('hero_subtitle', 'К 200-летию Санкт-Петербургского<br>Технологического Института<br><span class="hero-years">(1828-2028)</span>');
$hero_img = get_theme_mod('hero_bg', get_template_directory_uri() . '/assets/img/hero-bg.jpg');
$hero_bg_pos = get_theme_mod('hero_bg_position', 'center 33%');
$hero_overlay_color = get_theme_mod('hero_overlay_color', '#0c1929');
$hero_overlay_opacity = get_theme_mod('hero_overlay_opacity', 0);
$hero_overlay_opacity_float = $hero_overlay_opacity / 100;
$hero_textbox_color = get_theme_mod('hero_textbox_color', '#0c1929');
$hero_textbox_opacity = get_theme_mod('hero_textbox_opacity', 60);
$hero_textbox_opacity_float = $hero_textbox_opacity / 100;
$hero_textbox_blur = get_theme_mod('hero_textbox_blur', 0);
$hero_text_color = get_theme_mod('hero_text_color', '#ffffff');
list($hr, $hg, $hb) = sscanf($hero_textbox_color, '#%02x%02x%02x');
?>

<div class="hero">
  <div class="hero-bg" style="background-image: url('<?php echo esc_url($hero_img); ?>'); background-position: <?php echo esc_attr($hero_bg_pos); ?>;"></div>
  <div class="hero-overlay" style="background: <?php echo esc_attr($hero_overlay_color); ?>; opacity: <?php echo esc_attr($hero_overlay_opacity_float); ?>;"></div>
  <div class="hero-content container">
    <div class="text-box" style="background: rgba(<?php echo "$hr,$hg,$hb,$hero_textbox_opacity_float"; ?>); backdrop-filter: blur(<?php echo esc_attr($hero_textbox_blur); ?>px); -webkit-backdrop-filter: blur(<?php echo esc_attr($hero_textbox_blur); ?>px);">
      <h1 style="color: <?php echo esc_attr($hero_text_color); ?>;"><?php echo $hero_title; ?></h1>
      <p class="hero-subtitle" style="color: <?php echo esc_attr($hero_text_color); ?>;"><?php echo $hero_subtitle; ?></p>
      <div class="countdown" data-target="<?php echo $target_date; ?>">
        <div class="countdown-item"><span class="number" id="countdown-days">--</span><span class="label">Дней</span></div>
        <div class="countdown-item"><span class="number" id="countdown-hours">--</span><span class="label">Часов</span></div>
        <div class="countdown-item"><span class="number" id="countdown-minutes">--</span><span class="label">Минут</span></div>
        <div class="countdown-item"><span class="number" id="countdown-seconds">--</span><span class="label">Секунд</span></div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="main-layout">
    <aside class="sidebar sidebar-left">
      <div class="sidebar-widget">
        <h3>Навигация</h3>
        <ul class="quick-links">
          <?php
          $items = wp_get_nav_menu_items('primary');
          if (!$items) {
              wp_list_pages(['title_li' => '', 'link_before' => '', 'link_after' => '']);
          } else {
              foreach ($items as $item) {
                  $active = '';
                  if ($item->object_id == get_queried_object_id()) $active = ' active';
                  echo '<li><a href="' . esc_url($item->url) . '" class="' . $active . '">' . esc_html($item->title) . '</a></li>';
              }
          }
          ?>
        </ul>
      </div>
      <?php if (is_active_sidebar('sidebar-left')) dynamic_sidebar('sidebar-left'); ?>
    </aside>

    <main class="center-content">
      <div class="content-section">
        <h2>Последние новости</h2>
        <?php
        $news = new WP_Query([
            'posts_per_page' => 5,
            'category__in' => get_terms(['taxonomy' => 'category', 'fields' => 'ids', 'hide_empty' => true]),
        ]);
        if ($news->have_posts()) : while ($news->have_posts()) : $news->the_post(); ?>
        <a href="<?php the_permalink(); ?>" class="news-card">
          <div class="news-text">
            <span class="date"><?php echo get_the_date('j F Y'); ?></span>
            <h4><?php the_title(); ?></h4>
            <p><?php echo wp_trim_words(get_the_excerpt() ?: get_the_content(), 20); ?></p>
          </div>
          <div class="news-thumb">
            <img src="<?php echo spbgti_get_thumb_src(); ?>" alt="" loading="lazy">
          </div>
        </a>
        <?php endwhile; wp_reset_postdata(); else : ?>
        <p style="color:var(--text-muted)">Новости скоро появятся</p>
        <?php endif; ?>
      </div>

      <?php
      $partners = new WP_Query([
          'post_type' => 'partner',
          'posts_per_page' => -1,
          'orderby' => 'menu_order',
          'order' => 'ASC',
      ]);
      if ($partners->have_posts()) : ?>
      <div class="content-section">
        <h2>Наши партнёры</h2>
        <?php while ($partners->have_posts()) : $partners->the_post();
          $cats = wp_get_post_terms(get_the_ID(), 'partner_category');
          $cat_name = $cats ? $cats[0]->name : '';
        ?>
        <div class="initiative-item">
          <?php if ($cat_name) : ?><span class="tag"><?php echo esc_html($cat_name); ?></span><?php endif; ?>
          <h4><?php the_title(); ?></h4>
          <?php $excerpt = get_the_excerpt(); if ($excerpt) : ?>
          <p><?php echo esc_html($excerpt); ?></p>
          <?php endif; ?>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
      <?php endif; ?>
    </main>

    <aside class="sidebar sidebar-right">
      <div class="sidebar-widget" style="padding:0;overflow:hidden">
        <a href="https://spbti.ru/" target="_blank" style="display:block">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/spbti_logo.png" alt="Санкт-Петербургский технологический институт" style="width:100%;display:block;padding:20px">
          <div style="padding:10px 14px;font-size:0.8rem;color:var(--text-muted);text-align:center">Санкт-Петербургский государственный технологический институт &rarr;</div>
        </a>
      </div>

      <?php if (is_active_sidebar('stats-widget')) : ?>
      <div class="sidebar-widget">
        <h3>Ключевые цифры</h3>
        <div class="stats-widget">
          <?php dynamic_sidebar('stats-widget'); ?>
        </div>
      </div>
      <?php endif; ?>

      <?php if (is_active_sidebar('sidebar-right')) dynamic_sidebar('sidebar-right'); ?>
    </aside>
  </div>
</div>

<?php get_footer(); ?>
