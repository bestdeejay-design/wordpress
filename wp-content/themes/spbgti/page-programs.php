<?php /* Template Name: Программы */
get_header(); while (have_posts()) : the_post(); ?>

<div class="page-banner">
  <div class="container">
    <h1><?php the_title(); ?></h1>
    <p>Образовательные инициативы и программы поддержки</p>
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
      <?php echo do_shortcode('[programs_list]'); ?>
    </main>
  </div>
</div>

<?php endwhile; get_footer(); ?>
