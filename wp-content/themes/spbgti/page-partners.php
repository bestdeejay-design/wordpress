<?php /* Template Name: Партнёры */
get_header(); while (have_posts()) : the_post(); ?>

<div class="page-banner">
  <div class="container">
    <h1><?php the_title(); ?></h1>
    <p>Мы сотрудничаем с ведущими организациями</p>
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
        <?php the_content(); ?>
      </div>
    </main>

    <aside class="sidebar sidebar-right">
      <div class="sidebar-widget">
        <h3>Стать партнёром</h3>
        <p style="font-size:0.9rem;color:var(--text-muted)">Приглашаем организации и предприятия к сотрудничеству.</p>
        <a href="mailto:<?php echo antispambot(get_theme_mod('contact_email', 'mbrumina@rambler.ru')); ?>?subject=<?php echo urlencode('Партнёрство с фондом'); ?>" style="display:inline-block;margin-top:12px;padding:10px 16px;background:var(--accent);color:var(--primary);border-radius:8px;font-weight:600">Стать партнёром</a>
      </div>
      <?php if (is_active_sidebar('sidebar-right')) dynamic_sidebar('sidebar-right'); ?>
    </aside>
  </div>
</div>

<?php endwhile; get_footer(); ?>
