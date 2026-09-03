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

## 目录结构

```
├── index.html              # 首页
├── products.html           # 产品中心
├── product-detail-*.html   # 各产品详情页
├── applications.html       # 应用方案
├── about.html              # 关于我们
├── contact.html            # 联系我们
└── assets/
    ├── css/                # 样式
    ├── js/                 # 脚本
    ├── images/             # 图片（logo、产品图）
    └── downloads/          # 数据手册 + 上位机软件
```
