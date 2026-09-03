<?php
/**
 * Front Page Template
 * 首页模板 - 对应静态版 index.html
 */
get_header();
?>

<!-- Hero Section -->
<section class="hero">
  <div class="container">
    <div class="hero__content fade-in">
      <span class="hero__badge">光通信测试设备 · 专业制造商</span>
      <h1 class="hero__title">光通信测试<br><span>设备与解决方案</span></h1>
      <p class="hero__desc">
        铜陵展鹏电子有限公司专注于光通信测试仪器的研发与制造，
        提供1x8光开关、多通道稳定光源等产品，服务于光纤通信网络建设与维护。
      </p>
      <div class="hero__actions">
        <a href="<?php echo home_url('/products'); ?>" class="btn btn--primary btn--lg">查看产品</a>
        <a href="<?php echo home_url('/contact'); ?>" class="btn btn--outline btn--lg" style="border-color: rgba(255,255,255,0.4); color: #fff;">在线询盘</a>
      </div>
    </div>
    <div class="hero__visual">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero-product.png" alt="展鹏电子产品展示">
    </div>
  </div>
  <div class="hero__scroll">
    <span>向下滚动</span>
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 13l5 5 5-5M7 6l5 5 5-5"/></svg>
  </div>
</section>

<!-- Product Categories -->
<section class="section" id="products">
  <div class="container">
    <div class="section__header fade-in">
      <span class="section__label">产品中心</span>
      <h2 class="section__title">核心产品线</h2>
      <p class="section__subtitle">覆盖多品类光通信测试设备，满足不同行业应用需求</p>
    </div>

    <div class="product-categories">
      <?php
      $categories = get_terms(array('taxonomy' => 'product_category', 'hide_empty' => false));
      $icons = ['connectors' => '🔌', 'pcb' => '📟', 'interface' => '🔗'];
      $delays = ['fade-in-delay-1', 'fade-in-delay-2', 'fade-in-delay-3'];
      $i = 0;
      foreach ($categories as $cat):
        $icon = isset($icons[$cat->slug]) ? $icons[$cat->slug] : '📦';
      ?>
      <a href="<?php echo get_term_link($cat); ?>" class="category-card fade-in <?php echo $delays[$i++ % 3]; ?>">
        <div class="category-card__icon"><?php echo $icon; ?></div>
        <h3 class="category-card__title"><?php echo $cat->name; ?></h3>
        <p class="category-card__desc"><?php echo $cat->description; ?></p>
        <span class="category-card__link">查看产品 →</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Company Stats -->
<section class="section section--gray">
  <div class="container">
    <div style="text-align: center; max-width: 800px; margin: 0 auto;">
      <h3 style="font-size: var(--font-size-xl); margin-bottom: var(--space-lg);">专业光通信测试设备制造商</h3>
      <p style="color: var(--color-gray); line-height: 1.9;">
        公司专注于光开关、SLED稳定光源等光通信测试仪器的研发与制造，
        拥有自主研发团队和完善的测试实验设施，产品技术指标达到行业先进水平。
      </p>
    </div>
  </div>
</section>

<!-- Featured Products -->
<section class="section">
  <div class="container">
    <div class="section__header fade-in">
      <span class="section__label">精选产品</span>
      <h2 class="section__title">热门推荐</h2>
      <p class="section__subtitle">严选品质，每款产品均经过严格测试与质量管控</p>
    </div>

    <div class="product-highlights">
      <?php
      $featured = new WP_Query(array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'meta_key'       => '_zhanpeng_featured',
        'meta_value'     => '1',
      ));
      $delay = 1;
      while ($featured->have_posts()): $featured->the_post();
      ?>
      <div class="product-card fade-in fade-in-delay-<?php echo $delay++; ?>">
        <div class="product-card__image">
          <?php if (has_post_thumbnail()): the_post_thumbnail('medium'); endif; ?>
        </div>
        <div class="product-card__body">
          <h3 class="product-card__title"><?php the_title(); ?></h3>
          <div class="product-card__specs">
            <?php
            $specs = get_post_meta(get_the_ID(), '_zhanpeng_specs', true);
            if ($specs):
              foreach (explode(',', $specs) as $spec):
            ?>
            <span class="product-card__spec"><?php echo trim($spec); ?></span>
            <?php endforeach; endif; ?>
          </div>
          <p class="product-card__desc"><?php echo get_the_excerpt(); ?></p>
          <div class="product-card__action">
            <a href="<?php the_permalink(); ?>" class="btn btn--outline btn--sm">查看详情</a>
          </div>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>

<!-- Advantages -->
<section class="section section--gray">
  <div class="container">
    <div class="section__header fade-in">
      <span class="section__label">为什么选择我们</span>
      <h2 class="section__title">企业核心优势</h2>
    </div>
    <div class="advantages">
      <div class="advantage-card fade-in fade-in-delay-1">
        <div class="advantage-card__icon">💡</div>
        <h3 class="advantage-card__title">研发创新能力</h3>
        <p class="advantage-card__desc">拥有专业研发团队，持续投入新产品开发与工艺改进，可根据客户需求进行定制设计。</p>
      </div>
      <div class="advantage-card fade-in fade-in-delay-2">
        <div class="advantage-card__icon">🛡️</div>
        <h3 class="advantage-card__title">品质管理体系</h3>
        <p class="advantage-card__desc">严格执行ISO质量管理体系标准，全流程质量管控，从原材料到成品层层把关。</p>
      </div>
      <div class="advantage-card fade-in fade-in-delay-3">
        <div class="advantage-card__icon">⚡</div>
        <h3 class="advantage-card__title">快速响应交付</h3>
        <p class="advantage-card__desc">柔性生产管理，快速响应客户需求。标准产品现货供应，定制产品高效交付。</p>
      </div>
    </div>
  </div>
</section>

<!-- Application Industries -->
<section class="section">
  <div class="container">
    <div class="section__header fade-in">
      <span class="section__label">应用领域</span>
      <h2 class="section__title">产品应用行业</h2>
      <p class="section__subtitle">产品广泛应用于以下领域，为客户提供可靠连接与信号传输解决方案</p>
    </div>

    <div class="industries">
      <?php
      $industries = get_terms(array('taxonomy' => 'product_industry', 'hide_empty' => false));
      $gradients = [
        'linear-gradient(135deg, #1e3a5f, #2d5a87)',
        'linear-gradient(135deg, #1a3a2a, #2d6b4f)',
        'linear-gradient(135deg, #3a3520, #6b5d2d)',
        'linear-gradient(135deg, #3a1f2d, #6b3d5a)',
      ];
      $j = 0;
      foreach ($industries as $industry):
        $bg = $gradients[$j % 4];
        $j++;
      ?>
      <a href="<?php echo get_term_link($industry); ?>" class="industry-card fade-in">
        <div class="industry-card__bg" style="background: <?php echo $bg; ?>;"></div>
        <div class="industry-card__overlay"></div>
        <div class="industry-card__content">
          <h3 class="industry-card__title"><?php echo $industry->name; ?></h3>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- News -->
<section class="section section--gray">
  <div class="container">
    <div class="section__header fade-in">
      <span class="section__label">新闻动态</span>
      <h2 class="section__title">最新资讯</h2>
    </div>

    <div class="news-grid">
      <?php
      $news = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 3));
      $delay = 1;
      while ($news->have_posts()): $news->the_post();
      ?>
      <article class="news-card fade-in fade-in-delay-<?php echo $delay++; ?>">
        <div class="news-card__image">
          <?php if (has_post_thumbnail()): the_post_thumbnail('medium'); endif; ?>
        </div>
        <div class="news-card__body">
          <time class="news-card__date"><?php echo get_the_date('Y-m-d'); ?></time>
          <h3 class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <p class="news-card__excerpt"><?php echo get_the_excerpt(); ?></p>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
</section>


<?php get_footer(); ?>
