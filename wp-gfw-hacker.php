<?php
/*
  Plugin Name: GFW Hacker
  Version: 0.4.0
  Description: 为墙内的 WordPress 站点而生
  Plugin URI: https://github.com/aidistan/wp-gfw-hacker
  Author: Aidi Stan
  Author URI: http://github.com/aidistan/
*/

define('BASE_PATH', dirname(__FILE__));
require_once(BASE_PATH . '/geo/geoip.inc');

// 匹配出css、js、图片地址
function gfwh_replace_url($str){
  $geoData     = geoip_open(BASE_PATH . '/geo/GeoIP.dat', GEOIP_STANDARD);
  $countryCode = geoip_country_code_by_addr($geoData, $_SERVER['REMOTE_ADDR']);
  geoip_close($geoData);

  if( $countryCode === 'CN' ) {
    $regexp = "/<(link|script|img)([^<>]+)>/i";
    $str = preg_replace_callback( $regexp, "gfwh_replace_callback", $str );
  }

  return $str;
}

// 匹配需要替换掉的链接地址
function gfwh_replace_callback($matches) {
  $str = $matches[0];

  $patterns = array();
  $replacements = array();

  // 匹配谷歌CDN链接
  // $patterns[0] = '/\.googleapis\.com/';

  // 匹配头像链接
  $patterns[0] = '/http:\/\/[0-9]\.gravatar\.com\//';
  $patterns[1] = '/http%3A%2F%2F[0-9]\.gravatar\.com%2F/';

  // 使用中科大CDN地址
  // $replacements[0] = '.lug.ustc.edu.cn';

  // 目前使用https可以访问到头像图片
  $replacements[0] = 'https://secure.gravatar.com/';
  $replacements[1] = 'https%3A%2F%2Fsecure.gravatar.com%2F';

  return preg_replace($patterns, $replacements, $str);
}

function gfwh_buffer_start() {
  //开启缓冲
  ob_start("gfwh_replace_url");
}

function gfwh_buffer_end() {
  // 关闭缓冲
  ob_end_flush();
}

/**
* 分别将开启和关闭缓冲添加到wp_loaded和shutdown动作
* 也可以尝试添加到其他动作，只要内容输出在两个动作之间即可
* 参考链接：http://codex.wordpress.org/Plugin_API/Action_Reference
*/
add_action('init',     'gfwh_buffer_start');
add_action('shutdown', 'gfwh_buffer_end');

?>
