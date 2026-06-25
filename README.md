# VicSolar — Electro Child Theme

Child theme **Electro** cho website VicSolar, gồm landing page lắp điện mặt trời và các tùy chỉnh WooCommerce.

## Yêu cầu

- WordPress
- Theme parent **Electro** (đã cài và kích hoạt)
- **ACF Pro** (Options page `Landing Solar`)
- WooCommerce (cho shop / sản phẩm liên quan)

## Cài đặt

1. Copy thư mục `electro-child` vào `wp-content/themes/`
2. Kích hoạt theme **Electro Child** trong WP Admin
3. Tạo trang mới → chọn template **Landing — Lắp điện mặt trời**
4. Vào **Landing Solar** (menu admin) → kiểm tra nội dung → **Lưu nội dung**

## Landing Solar

- **Template:** `template-landing-solar.php`
- **Sections đang render:** 01, 04–10 (+ CTA sau mỗi section)
- **Quản trị:** WP Admin → **Landing Solar**
- **Defaults:** `inc/landing-solar-*-defaults.php` — hiển thị khi DB trống; deploy code không ghi đè nội dung admin đã lưu
- **Dữ liệu:** lưu trong `wp_options` (ACF Options), không theo từng trang

## Cấu trúc chính

```
inc/                    # ACF, helpers, defaults
template-parts/landing/ # Section partials
assets/css/             # landing-solar*.css
assets/js/              # landing-factory-slider.js
_tools/                 # Script dev (extract defaults), không dùng runtime
```

## Deploy lên production (cPanel Git)

1. cPanel → **Git™ Version Control** → Clone `https://github.com/minhthangdev93/vicsolar.git`
2. **Repository Path:** `repositories/vicsolar` (không clone thẳng vào `public_html`)
3. Sau mỗi lần push GitHub: **Manage** → **Pull or Deploy** → **Deploy HEAD commit**
4. File `.cpanel.yml` copy code sang `public_html/wp-content/themes/electro-child/`
5. Vào **Landing Solar** → **Lưu nội dung** (lần đầu) → xóa cache → Ctrl+F5

## Hiệu năng (PageSpeed / Core Web Vitals)

- `inc/landing-solar-performance.php` — preload LCP, preconnect font, defer CSS/JS không critical
- Slider section 09: tối đa **12 bài** mỗi khối (filter `electro_child_landing_factory_posts_per_page`)
- Trên production: bật cache (WP Rocket / LiteSpeed), WebP ảnh, CDN nếu có


- Section 02, 03, 11 có file partial nhưng **chưa** nằm trong danh sách render (`inc/landing-solar.php`)
- Ảnh upload qua ACF cần có trong `wp-content/uploads/` trên server
