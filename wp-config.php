<?php

define('WP_CACHE', false);
/**
 * Cấu hình cơ bản cho WordPress
 *
 * Trong quá trình cài đặt, file "wp-config.php" sẽ được tạo dựa trên nội dung 
 * mẫu của file này. Bạn không bắt buộc phải sử dụng giao diện web để cài đặt, 
 * chỉ cần lưu file này lại với tên "wp-config.php" và điền các thông tin cần thiết.
 *
 * File này chứa các thiết lập sau:
 *
 * * Thiết lập MySQL
 * * Các khóa bí mật
 * * Tiền tố cho các bảng database
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Thiết lập MySQL - Bạn có thể lấy các thông tin này từ host/server ** //
/** Tên database MySQL */
define( 'DB_NAME', 'shopaccthoaixuan' );

/** Username của database */
define( 'DB_USER', 'root' );

/** Mật khẩu của database */
define( 'DB_PASSWORD', '' );

/** Hostname của database */
define( 'DB_HOST', 'localhost' );

/** Database charset sử dụng để tạo bảng database. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Kiểu database collate. Đừng thay đổi nếu không hiểu rõ. */
define('DB_COLLATE', '');

/**#@+
 * Khóa xác thực và salt.
 *
 * Thay đổi các giá trị dưới đây thành các khóa không trùng nhau!
 * Bạn có thể tạo ra các khóa này bằng công cụ
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Bạn có thể thay đổi chúng bất cứ lúc nào để vô hiệu hóa tất cả
 * các cookie hiện có. Điều này sẽ buộc tất cả người dùng phải đăng nhập lại.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'wkZJ:}r2p-OS[AOmC]vETwhDr-fOtaL*&LCM157AmspxwifjR(;:8(c^}9NK~9G8' );
define( 'SECURE_AUTH_KEY',  'U*:N%7|S4S*VUe46[(eWx:O}B,v7-_jK#PmL(^iyU-S^5VD3vH~YVk~saD_$eMsp' );
define( 'LOGGED_IN_KEY',    'K8Gd3qe!@J9a|9y94W9@-QJ+^U$;Oyw_L0)$UrYs;&}PM4bX~^(os-k%i2jrJd.?' );
define( 'NONCE_KEY',        '!$$fqKRSbQ=dS=/d6HJBnkvxIF;J/tX_E^X^22oF!v@K!Q,.OQsl%e6{Z`bdVfZl' );
define( 'AUTH_SALT',        'v0IJ>`!_oLtUhfPf(o6_4J9d wOYzhsbT7q@2<3|E8[Mw`?eRH;Uk*2dMQ8O2K@k' );
define( 'SECURE_AUTH_SALT', 'CBfwck@<w:DG~rCFaVvo2)nIJ3S^Ey#&I+r$fM1G%)k5TADfDTd5)V:K|FR{&6 t' );
define( 'LOGGED_IN_SALT',   'Hd#}YAh)Y4Br(0S7^jV5Ddp:9HgQ^37 &q$e?uNu9skcEfX,]edrC5/51`a)lWnu' );
define( 'NONCE_SALT',       'fK*bW4rYQ?r{YO*t]C<~;Wnh+%]xFt{=oZbJJ n6%X3>z!rE {Ui{E?u8xBsWH#Z' );

/**#@-*/

/**
 * Tiền tố cho bảng database.
 *
 * Đặt tiền tố cho bảng giúp bạn có thể cài nhiều site WordPress vào cùng một database.
 * Chỉ sử dụng số, ký tự và dấu gạch dưới!
 */
$table_prefix  = 'wp_';

/**
 * Dành cho developer: Chế độ debug.
 *
 * Thay đổi hằng số này thành true sẽ làm hiện lên các thông báo trong quá trình phát triển.
 * Chúng tôi khuyến cáo các developer sử dụng WP_DEBUG trong quá trình phát triển plugin và theme.
 *
 * Để có thông tin về các hằng số khác có thể sử dụng khi debug, hãy xem tại Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Đó là tất cả thiết lập, ngưng sửa từ phần này trở xuống. Chúc bạn viết blog vui vẻ. */

/** Đường dẫn tuyệt đối đến thư mục cài đặt WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Thiết lập biến và include file. */
require_once(ABSPATH . 'wp-settings.php');
