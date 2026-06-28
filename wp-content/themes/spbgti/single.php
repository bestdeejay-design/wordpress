<?php get_header(); while (have_posts()) : the_post(); ?>

<div class="page-banner">
  <div class="container">
    <h1><?php the_title(); ?></h1>
    <p><?php echo get_the_date('j F Y'); ?></p>
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
        <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:16px">
          <a href="<?php echo home_url(); ?>">Главная</a> /
          <a href="<?php echo get_post_type_archive_link('post'); ?>">Новости</a> /
          <?php the_title(); ?>
        </p>

        <?php the_content(); ?>

        <?php
        $images = get_attached_media('image', get_the_ID());
        $featured_id = get_post_thumbnail_id();
        if ($images) :
        ?>
        <div class="news-gallery">
          <?php foreach ($images as $image) :
            if ($image->ID == $featured_id) continue;
          ?>
          <img src="<?php echo wp_get_attachment_image_url($image->ID, 'medium'); ?>" data-full="<?php echo wp_get_attachment_image_url($image->ID, 'full'); ?>" alt="<?php echo esc_attr(get_post_meta($image->ID, '_wp_attachment_image_alt', true) ?: $image->post_title); ?>" loading="lazy">
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <p style="margin-top:24px">
          <a href="<?php echo get_post_type_archive_link('post'); ?>" style="display:inline-block;padding:10px 20px;background:var(--accent);color:var(--primary);border-radius:8px;font-weight:600">&larr; Все новости</a>
        </p>
      </div>
    </main>

    <aside class="sidebar sidebar-right">
      <div class="sidebar-widget">
        <h3>Другие новости</h3>
        <ul class="quick-links">
          <?php
          $recent = new WP_Query(['posts_per_page' => 5, 'post__not_in' => [get_the_ID()]]);
          while ($recent->have_posts()) : $recent->the_post();
          ?>
          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          <?php endwhile; wp_reset_postdata(); ?>
        </ul>
      </div>
    </aside>
  </div>
</div>

<?php endwhile; get_footer(); ?>
