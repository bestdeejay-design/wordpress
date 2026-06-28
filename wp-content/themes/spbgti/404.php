<?php get_header(); ?>

<div class="page-banner">
  <div class="container">
    <h1>404 — Страница не найдена</h1>
  </div>
</div>

<div class="container" style="padding:80px 16px;text-align:center">
  <p style="font-size:1.2rem;color:var(--text-muted)">Запрашиваемая страница не существует.</p>
  <a href="<?php echo home_url(); ?>" style="display:inline-block;margin-top:20px;padding:12px 24px;background:var(--accent);color:var(--primary);border-radius:8px;font-weight:600">На главную</a>
</div>

<?php get_footer(); ?>
