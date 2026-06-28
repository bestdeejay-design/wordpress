<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header>
  <div class="container">
    <div class="header-inner">
      <a href="<?php echo home_url(); ?>" class="logo">
        <div class="logo-icon">200</div>
        <span class="logo-text">ФОНД "ТЕХНОЛОГИЧЕСКОМУ ИНСТИТУТУ-200 ЛЕТ"</span>
      </a>
      <button class="hamburger" aria-label="Меню">
        <span></span><span></span><span></span>
      </button>
      <nav class="nav" id="header-nav">
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'nav-links',
            'fallback_cb' => 'spbgti_menu_fallback',
            'walker' => new SPbGTI_Walker(),
            'depth' => 1,
        ]);
        ?>
      </nav>
      <div class="nav-overlay"></div>
    </div>
  </div>
</header>
