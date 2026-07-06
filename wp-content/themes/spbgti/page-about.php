<?php /* Template Name: О фонде */
get_header(); while (have_posts()) : the_post(); ?>

<div class="page-banner">
  <div class="container">
    <h1><?php the_title(); ?></h1>
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
      <?php
        add_filter('the_content', 'add_section_ids', 9);
        add_filter('the_content', 'append_about_sections', 9);
        the_content();
        remove_filter('the_content', 'add_section_ids', 9);
        remove_filter('the_content', 'append_about_sections', 9);
      ?>
    </main>

    <aside class="sidebar sidebar-right">
      <?php if (is_active_sidebar('sidebar-right')) dynamic_sidebar('sidebar-right'); ?>
    </aside>
  </div>
</div>

<?php endwhile; get_footer(); ?>
