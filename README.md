# ZhanPeng · 展鹏电子

铜陵市展鹏电子有限公司官方网站（静态站）。

## 产品线

- 光开关系列（1x4 / 1x8）
- DFB / SLED 稳定光源
- 可调光衰减仪（VOA）
- 光功率计（OPM）

## 部署

本仓库根目录即网站内容，通过 **GitHub Pages** 自动部署：

- 公网访问：https://yanzhengxin.github.io/ZhanPeng/
- 推送 `main` 分支后，Pages 自动重新发布

## 本地预览

```bash
python -m http.server 3000
# 浏览器打开 http://127.0.0.1:3000/
```

## 性能

首屏关键优化（详见 `tools/optimize.py`，可重复执行、幂等）：

- 图片全部转 **WebP**，引用体积由 **2120 KB → 213 KB（-90%）**
- 所有非首屏 `<img>` 带 `loading="lazy"` + `decoding="async"` + 显式 `width/height`（防 CLS）
- 首屏 hero 图 `fetchpriority="high"` 并在 `<head>` 里 `<link rel="preload">`
- 补齐 `favicon.ico` / `favicon-32.png` / `apple-touch-icon.png`

**新增/替换图片后请重跑一次**：

```bash
python tools/optimize.py
```

> 注意：`main.js` 里保留了一段基于 `data-src` 的 IntersectionObserver 懒加载逻辑，
> 但本站 HTML 从不使用 `data-src`（改用原生 `loading="lazy"`），该段代码当前不生效，
> 后续若改用 JS 懒加载再启用。

## 目录结构

```
├── index.html              # 首页
├── products.html           # 产品中心
├── product-detail-*.html   # 各产品详情页
├── applications.html       # 应用方案
├── about.html              # 关于我们
├── contact.html            # 联系我们
├── privacy.html            # 隐私政策
├── favicon.ico             # 站点图标
├── tools/optimize.py       # 图片压缩 / 懒加载注入（可重复执行）
└── assets/
    ├── css/                # 样式
    ├── js/                 # 脚本
    ├── images/             # 图片（logo、产品图，均为 WebP）
    └── downloads/          # 数据手册 + 上位机软件
```
