<?php
/**
 * Theme Header
 * 全局页头模板
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="header">
  <div class="container header__inner">
    <a href="<?php echo home_url('/'); ?>" class="header__logo">
      <?php if (has_custom_logo()): ?>
        <?php the_custom_logo(); ?>
      <?php else: ?>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo('name'); ?>" width="160" height="42">
      <?php endif; ?>
    </a>

    <nav class="nav">
      <?php
      wp_nav_menu(array(
        'theme_location' => 'primary',
        'container'      => false,
        'menu_class'     => 'nav__list',
        'fallback_cb'    => false,
        'link_before'    => '',
        'link_after'     => '',
        'depth'          => 2,
      ));
      ?>
    </nav>

    <div class="header__actions">
      <span class="header__phone">
        <svg class="header__phone-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
        <?php echo get_theme_mod('zhanpeng_phone', '+86 18452069980'); ?>
      </span>

      <button class="header__toggle" aria-label="菜单" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>
