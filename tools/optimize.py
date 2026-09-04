#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
ZhanPeng 官网加载速度优化（一次性/可重复执行）

做五件事：
  1. 把被引用过的 PNG 转成 WebP（超大图按比例缩放），大幅减小体积
  2. 把 HTML/PHP 中的图片引用从 .png 改为 .webp
  3. 给所有 <img> 补 loading="lazy" / decoding="async" / width / height（首屏除外）
  4. 生成 favicon.ico / favicon-32.png / apple-touch-icon.png 并注入 <head>
  5. 首屏 hero 图加 <link rel="preload">

幂等：重复执行不会重复追加属性。
"""
import os
import re
import sys
from PIL import Image

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(ROOT, "assets", "images")

# (相对路径, 目标最大宽度) —— 只处理 HTML/PHP 中真实引用过的图片
TARGETS = [
    ("logo-icon.png",             256),
    ("logo.png",                  512),   # header.php 以 160px 宽展示
    ("logo-white.png",            512),   # footer.php 以 36px 高展示
    ("hero-product.png",          600),
    ("devices/sws05-ch1.png",    1200),
    ("products/D-SWS09B.png",    1200),
    ("products/D-OS108D.png",    1200),
    ("products/D-OS104F.png",    1200),
    ("products/D-OS504A.png",    1200),
    ("products/D-OS504B.png",    1200),
    ("products/D-PMS04A.png",    1200),
    ("products/D-PMS08A.png",    1200),
    ("products/D-VAS08A.png",    1200),
    ("products/D-VAS08B.png",    1200),
]

WEBP_Q = 82
dims = {}          # 相对路径(webm) -> (w, h)


def convert(rel, max_w):
    src = os.path.join(IMG_DIR, rel)
    if not os.path.exists(src):
        print("  ! 缺失 %s" % rel)
        return None
    dst = os.path.join(IMG_DIR, os.path.splitext(rel)[0] + ".webp")
    im = Image.open(src)
    if im.width > max_w:
        h = round(im.height * max_w / im.width)
        im = im.resize((max_w, h), Image.LANCZOS)
    if im.mode in ("RGBA", "LA", "P"):
        im = im.convert("RGBA")
    else:
        im = im.convert("RGB")
    im.save(dst, "WEBP", quality=WEBP_Q, method=6)
    old = os.path.getsize(src)
    new = os.path.getsize(dst)
    dims["assets/images/" + os.path.splitext(rel)[0] + ".webp"] = im.size
    print("  %-34s %7.0f KB -> %6.0f KB  (-%.0f%%)" %
          (rel, old / 1024, new / 1024, (1 - new / old) * 100))
    return rel


def make_icons():
    src = os.path.join(IMG_DIR, "logo-icon.png")
    im = Image.open(src).convert("RGBA")
    ico = os.path.join(ROOT, "favicon.ico")
    im.save(ico, "ICO", sizes=[(16, 16), (32, 32), (48, 48)])
    p32 = os.path.join(IMG_DIR, "favicon-32.png")
    im.resize((32, 32), Image.LANCZOS).save(p32, "PNG", optimize=True)
    at = os.path.join(IMG_DIR, "apple-touch-icon.png")
    im.resize((180, 180), Image.LANCZOS).save(at, "PNG", optimize=True)
    print("  favicon.ico / favicon-32.png / apple-touch-icon.png 已生成")


IMG_RE = re.compile(r"<img\b[^>]*>", re.I)


def patch_img_tag(tag, eager):
    if "loading=" in tag:
        return tag                      # 幂等
    src = re.search(r'src="([^"]+)"', tag)
    attrs = ""
    if src:
        key = src.group(1)
        if key in dims:
            w, h = dims[key]
            attrs += ' width="%d" height="%d"' % (w, h)
    if eager:
        attrs += ' fetchpriority="high"'
    else:
        attrs += ' loading="lazy"'
    if "decoding=" not in tag:
        attrs += ' decoding="async"'
    return tag[:-1].rstrip() + attrs + ">"


HEAD_ICON = (
    '<link rel="icon" href="favicon.ico" sizes="any">\n'
    '<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32.png">\n'
    '<link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">\n'
)


def process_html(path):
    with open(path, "r", encoding="utf-8") as f:
        c = f.read()
    orig = c

    # 1) .png -> .webp
    for rel, _ in TARGETS:
        old = "assets/images/" + rel
        new = "assets/images/" + os.path.splitext(rel)[0] + ".webp"
        if new in dims:
            c = c.replace(old, new)

    # 2) 首屏区间（hero section）+ header logo 不做懒加载
    hero = [(m.start(), m.end()) for m in re.finditer(r'<section class="hero">.*?</section>', c, re.S)]

    def repl(m):
        tag = m.group(0)
        pos = m.start()
        in_hero = any(s <= pos < e for s, e in hero)
        is_logo = "header__logo-icon" in tag
        return patch_img_tag(tag, eager=(in_hero or is_logo))

    c = IMG_RE.sub(repl, c)

    # 3) favicon 注入
    if "rel=\"icon\"" not in c:
        c = c.replace('<link rel="stylesheet" href="assets/css/main.css">',
                      HEAD_ICON + '<link rel="stylesheet" href="assets/css/main.css">', 1)

    # 4) hero 图预加载
    if hero and "rel=\"preload\"" not in c:
        c = c.replace('<link rel="stylesheet" href="assets/css/main.css">',
                      '<link rel="stylesheet" href="assets/css/main.css">\n'
                      '<link rel="preload" as="image" href="assets/images/products/D-SWS09B.webp" fetchpriority="high">', 1)

    if c != orig:
        with open(path, "w", encoding="utf-8", newline="\n") as f:
            f.write(c)
        return True
    return False


def main():
    os.chdir(ROOT)
    print("[1/4] 转换图片为 WebP")
    for rel, w in TARGETS:
        convert(rel, w)

    print("[2/4] 生成 favicon")
    make_icons()

    print("[3/4] 改写 HTML 引用与懒加载")
    html_files = sorted(f for f in os.listdir(ROOT) if f.endswith(".html"))
    php_files = sorted(f for f in os.listdir(ROOT) if f.endswith(".php"))

    # PHP 是 WordPress 模板（不参与 Pages 部署），只同步图片后缀，保持两边一致
    for f in php_files:
        p = os.path.join(ROOT, f)
        with open(p, "r", encoding="utf-8") as fh:
            c = fh.read()
        orig = c
        for rel, _ in TARGETS:
            new = "assets/images/" + os.path.splitext(rel)[0] + ".webp"
            if new in dims:
                c = c.replace("assets/images/" + rel, new)
        if c != orig:
            with open(p, "w", encoding="utf-8", newline="\n") as fh:
                fh.write(c)
            print("  %-28s 已同步图片后缀" % f)

    for f in html_files:
        changed = process_html(os.path.join(ROOT, f))
        print("  %-28s %s" % (f, "已更新" if changed else "无变化"))

    print("[4/4] 清理空文件")
    for junk in (".htaccess", "nginx.htaccess"):
        p = os.path.join(ROOT, junk)
        if os.path.exists(p) and os.path.getsize(p) == 0:
            os.remove(p)
            print("  删除空文件 %s" % junk)
    print("  保留 style.css（WordPress 主题清单文件，删了 WP 主题会失效）")

    print("\n完成。")


if __name__ == "__main__":
    sys.stdout.reconfigure(encoding="utf-8")
    main()
