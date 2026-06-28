<?php /* Template Name: Галерея */
get_header();

$images = get_posts([
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'posts_per_page' => -1,
    'post_status' => 'inherit',
    'orderby' => 'date',
    'order' => 'DESC',
]);
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
        <?php else : ?>
        <p style="color:var(--text-muted)">Фотографии появятся после проведения мероприятий.</p>
        <?php endif; ?>
      </div>
    </main>
  </div>
</div>

<?php get_footer(); ?>
