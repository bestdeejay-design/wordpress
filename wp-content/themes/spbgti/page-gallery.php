<?php /* Template Name: Галерея */
get_header();

$paged = max(1, get_query_var('paged'));
$per_page = 30;
$selected_year = isset($_GET['year']) ? intval($_GET['year']) : 0;

global $wpdb;
$years = $wpdb->get_col(
    "SELECT DISTINCT YEAR(p.post_date) FROM {$wpdb->posts} p
     JOIN {$wpdb->postmeta} m ON p.ID = m.post_id
     WHERE m.meta_key = '_show_in_gallery' AND m.meta_value = '1'
       AND p.post_type = 'attachment' AND p.post_status = 'inherit'
     ORDER BY YEAR(p.post_date) DESC"
);

$meta_query = [
    ['key' => '_show_in_gallery', 'value' => '1'],
];

$query_args = [
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'posts_per_page' => $per_page,
    'paged' => $paged,
    'post_status' => 'inherit',
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => $meta_query,
];

if ($selected_year) {
    $query_args['year'] = $selected_year;
}

$query = new WP_Query($query_args);
$images = $query->posts;

$base_url = get_permalink();
?>

<div class="page-banner">
  <div class="container">
    <h1><?php the_title(); ?></h1>
    <p>Фотографии событий и мероприятий</p>
  </div>
</div>

<div class="container">
  <div class="main-layout">
    <aside class="sidebar sidebar-left">
      <div class="sidebar-widget">
        <h3>Навигация</h3>
        <ul class="quick-links">
          <?php wp_list_pages(['title_li' => '', 'link_before' => '', 'link_after' => '']); ?>
        </ul>
      </div>
      <div class="sidebar-widget">
        <h3>Архив по годам</h3>
        <form method="get" action="<?php echo esc_url($base_url); ?>" id="gallery-filter">
          <select name="year" onchange="this.form.submit()" style="width:100%;padding:8px;border-radius:6px;background:var(--bg);color:var(--text);border:1px solid var(--border,#333);">
            <option value="">Все годы</option>
            <?php foreach ($years as $y) : ?>
            <option value="<?php echo $y; ?>" <?php selected($selected_year, $y); ?>><?php echo $y; ?></option>
            <?php endforeach; ?>
          </select>
          <noscript><button type="submit" class="button" style="margin-top:6px">Показать</button></noscript>
        </form>
      </div>
    </aside>

    <main class="center-content">
      <div class="content-section">
        <h2>Фотогалерея</h2>
        <p>Поток фотографий с мероприятий фонда и событий к 200-летию Технологического института.</p>

        <?php if ($images) : ?>
        <div class="gallery-grid">
          <?php foreach ($images as $image) :
            $caption = wp_get_attachment_caption($image->ID) ?: $image->post_title;
          ?>
          <div class="gallery-item">
            <img src="<?php echo wp_get_attachment_image_url($image->ID, 'medium_large'); ?>" data-full="<?php echo wp_get_attachment_image_url($image->ID, 'full'); ?>" alt="<?php echo esc_attr($caption); ?>" loading="lazy">
            <?php if ($caption) : ?>
            <div class="gallery-caption"><?php echo esc_html($caption); ?></div>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>

        <?php if ($query->max_num_pages > 1) : ?>
        <div class="pagination">
          <?php
          $pag_args = $selected_year ? ['year' => $selected_year] : [];
          echo paginate_links([
              'total' => $query->max_num_pages,
              'current' => $paged,
              'mid_size' => 2,
              'prev_text' => '&larr;',
              'next_text' => '&rarr;',
              'add_args' => $pag_args,
          ]);
          ?>
        </div>
        <?php endif; ?>

        <?php else : ?>
        <p style="color:var(--text-muted)">
          <?php echo $selected_year ? "Фотографии за $selected_year год появятся после проведения мероприятий." : 'Фотографии появятся после проведения мероприятий.'; ?>
        </p>
        <?php endif; ?>
      </div>
    </main>
  </div>
</div>

<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>
