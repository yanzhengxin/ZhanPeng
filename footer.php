<?php
/**
 * Theme Footer
 * 全局页脚模板
 */
?>

<footer class="footer">
  <div class="container">
    <div class="footer__grid">
      <div class="footer__col">
        <h4 class="footer__col-title">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-white.png" alt="<?php bloginfo('name'); ?>" style="height: 36px; margin-bottom: var(--space-md);">
        </h4>
        <p class="footer__about">
          <?php bloginfo('description'); ?>
        </p>
      </div>

      <div class="footer__col">
        <h4 class="footer__col-title">快速导航</h4>
        <?php
        wp_nav_menu(array(
          'theme_location' => 'footer',
          'container'      => false,
          'menu_class'     => 'footer__links',
          'fallback_cb'    => false,
          'depth'          => 1,
        ));
        ?>
      </div>

      <div class="footer__col">
        <h4 class="footer__col-title">产品分类</h4>
        <ul class="footer__links">
          <?php
          $categories = get_terms(array('taxonomy' => 'product_category', 'hide_empty' => false));
          foreach ($categories as $cat) {
            echo '<li><a href="' . get_term_link($cat) . '">' . $cat->name . '</a></li>';
          }
          ?>
        </ul>
      </div>

      <div class="footer__col">
        <h4 class="footer__col-title">联系方式</h4>
        <div class="footer__contact-item"><strong>电话：</strong> <?php echo get_theme_mod('zhanpeng_phone', '+86 18452069980'); ?></div>
        <div class="footer__contact-item"><strong>邮箱：</strong> <?php echo get_theme_mod('zhanpeng_email', 'sales@zhanpeng-dz.com'); ?></div>
        <div class="footer__contact-item"><strong>地址：</strong> <?php echo get_theme_mod('zhanpeng_address', '铜陵市郊区经济开发区光电智造产业园'); ?></div>
      </div>
    </div>

    <div class="footer__bottom">
      <span>© <?php echo date('Y'); ?> <?php bloginfo('name'); ?> 版权所有</span>
      <span>
        <a href="<?php echo home_url('/privacy'); ?>">隐私政策</a>
        <span style="margin: 0 var(--space-sm); color: rgba(255,255,255,0.2);">|</span>
        <a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener"><?php echo get_theme_mod('zhanpeng_icp', '皖ICP备XXXXXXXX号'); ?></a>
      </span>
    </div>
  </div>
</footer>

<button class="back-to-top" aria-label="回到顶部">↑</button>

<script src="<?php echo get_template_directory_uri(); ?>/assets/js/main.js"></script>
<?php wp_footer(); ?>
</body>
</html>
