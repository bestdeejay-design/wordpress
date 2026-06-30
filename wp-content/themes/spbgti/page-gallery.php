<?php /* Template Name: Галерея */
get_header();

$paged = max(1, get_query_var('paged'));
$per_page = 30;

$query = new WP_Query([
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'posts_per_page' => $per_page,
    'paged' => $paged,
    'post_status' => 'inherit',
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => [
        ['key' => '_show_in_gallery', 'value' => '1'],
    ],
]);
$images = $query->posts;
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
    </aside>

    <main class="center-content">
      <div class="content-section">
        <h2>Фотогалерея</h2>
        <p>Поток фотографий с мероприятий фонда и событий к 200-летию Технологического института.</p>

        <?php if ($images) : ?>
        <div class="gallery-grid">
          <?php foreach ($images as $image) :
            $caption = wp_get_attachment_caption($image->ID) ?: $image->post_title;
            $parent = $image->post_parent ? get_the_title($image->post_parent) : '';
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
          <?php echo paginate_links([
              'total' => $query->max_num_pages,
              'current' => $paged,
              'mid_size' => 2,
              'prev_text' => '&larr;',
              'next_text' => '&rarr;',
          ]); ?>
        </div>
        <?php endif; ?>

        <?php else : ?>
        <p style="color:var(--text-muted)">Фотографии появятся после проведения мероприятий.</p>
        <?php endif; ?>
      </div>
    </main>
  </div>
</div>

<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>
