<?php
/* Template Name: Новости */
get_header();
$paged = get_query_var('paged') ?: 1;
$news_query = new WP_Query(['post_type' => 'post', 'posts_per_page' => 20, 'paged' => $paged]);
?>

<div class="page-banner">
  <div class="container">
    <h1><?php the_title(); ?></h1>
    <p>Последние события и обновления фонда</p>
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
        <h2>Все новости</h2>
        <?php if ($news_query->have_posts()) : while ($news_query->have_posts()) : $news_query->the_post(); ?>
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
        <?php endwhile; ?>
        <div style="margin-top:20px;display:flex;gap:8px;justify-content:center;">
          <?php echo paginate_links(['total' => $news_query->max_num_pages]); ?>
        </div>
        <?php else : ?>
        <p style="color:var(--text-muted)">Новости скоро появятся</p>
        <?php endif; wp_reset_postdata(); ?>
      </div>


    </main>

    <aside class="sidebar sidebar-right">
      <div class="sidebar-widget">
        <h3>Архив новостей</h3>
        <ul class="quick-links">
          <?php wp_get_archives(['type' => 'yearly', 'format' => 'html', 'show_post_count' => true]); ?>
        </ul>
      </div>
    </aside>
  </div>
</div>

<?php get_footer(); ?>
