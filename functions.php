<?php
/**
 * Zhanpeng Electronics Theme Functions
 * 铜陵展鹏电子有限公司 - 主题功能配置
 */

// 主题设置
function zhanpeng_theme_setup() {
    // 支持网站标题
    add_theme_support('title-tag');

    // 支持特色图片
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(800, 800, true);

    // 支持自定义Logo
    add_theme_support('custom-logo', array(
        'height'      => 80,
        'width'       => 320,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // 支持HTML5
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));

    // 注册导航菜单位置
    register_nav_menus(array(
        'primary' => '主导航菜单',
        'footer'  => '页脚导航菜单',
    ));

    // 支持区块编辑器样式
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'zhanpeng_theme_setup');

// 加载CSS和JS
function zhanpeng_enqueue_assets() {
    // 主样式表
    wp_enqueue_style(
        'zhanpeng-main',
        get_template_directory_uri() . '/assets/css/main.css',
        array(),
        '1.0.0'
    );

    // JavaScript
    wp_enqueue_script(
        'zhanpeng-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'zhanpeng_enqueue_assets');

// 自定义产品文章类型
function zhanpeng_register_post_types() {
    // 产品
    register_post_type('product', array(
        'labels' => array(
            'name'          => '产品',
            'singular_name' => '产品',
            'add_new'       => '添加产品',
            'add_new_item'  => '添加新产品',
            'edit_item'     => '编辑产品',
            'view_item'     => '查看产品',
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'products'),
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon'    => 'dashicons-products',
        'show_in_rest' => true,
    ));

    // 产品分类
    register_taxonomy('product_category', 'product', array(
        'labels' => array(
            'name'          => '产品分类',
            'singular_name' => '产品分类',
        ),
        'hierarchical'  => true,
        'rewrite'       => array('slug' => 'product-category'),
        'show_in_rest'  => true,
    ));

    // 应用行业
    register_taxonomy('product_industry', 'product', array(
        'labels' => array(
            'name'          => '应用行业',
            'singular_name' => '应用行业',
        ),
        'hierarchical'  => true,
        'rewrite'       => array('slug' => 'industry'),
        'show_in_rest'  => true,
    ));

    // 客户案例
    register_post_type('case', array(
        'labels' => array(
            'name'          => '客户案例',
            'singular_name' => '客户案例',
        ),
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => array('slug' => 'cases'),
        'supports'     => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'    => 'dashicons-awards',
        'show_in_rest' => true,
    ));
}
add_action('init', 'zhanpeng_register_post_types');

// 限制上传文件类型（允许 datasheet 等）
function zhanpeng_upload_mimes($mimes) {
    $mimes['stp']  = 'application/step';
    $mimes['step'] = 'application/step';
    $mimes['stl']  = 'application/sla';
    return $mimes;
}
add_filter('upload_mimes', 'zhanpeng_upload_mimes');

// 移除WordPress版本号（安全）
remove_action('wp_head', 'wp_generator');

// 禁用Gutenberg默认样式（使用自定义CSS）
function zhanpeng_remove_block_styles() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
}
add_action('wp_enqueue_scripts', 'zhanpeng_remove_block_styles', 100);
