<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-section">
        <h4>ФОНД "ТЕХНОЛОГИЧЕСКОМУ ИНСТИТУТУ-200 ЛЕТ"</h4>
        <p>Фонд создан для поддержки и развития Санкт-Петербургского технологического института к 200-летнему юбилею.</p>
        <p style="margin-top: 16px;"><strong>ИНН:</strong> <?php echo esc_html(get_theme_mod('contact_inn', '7841041417')); ?></p>
      </div>
      <div class="footer-section">
        <h4>Навигация</h4>
        <a href="<?php echo home_url('/about/'); ?>">О фонде</a>
        <a href="<?php echo home_url('/initiatives/'); ?>">Инициативы</a>
        <a href="<?php echo home_url('/news/'); ?>">Новости</a>
        <a href="<?php echo home_url('/programs/'); ?>">Программы</a>
      </div>
      <div class="footer-section">
        <h4>Контакты</h4>
        <p><?php echo esc_html(get_theme_mod('contact_city', 'Санкт-Петербург')); ?></p>
        <p><?php echo esc_html(get_theme_mod('contact_email', 'mbrumina@rambler.ru')); ?></p>
        <p><?php echo esc_html(get_theme_mod('contact_phone', '+7 (812) XXX-XX-XX')); ?></p>
      </div>
    </div>
    <div class="footer-bottom">
      Основан в 2016 году. К 200-летию СПбТИ.
    </div>
  </div>
</footer>

<div class="lightbox">
  <span class="close">&times;</span>
  <button class="lb-prev">&#8249;</button>
  <button class="lb-next">&#8250;</button>
  <div class="lb-content">
    <img src="" alt="">
  </div>
  <div class="lb-thumbs"></div>
</div>
<?php wp_footer(); ?>
</body>
</html>
