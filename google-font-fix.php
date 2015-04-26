<?php

define('PLUGIN_PATH', plugin_dir_path(__FILE__));
require_once(PLUGIN_PATH . "geo/geoip.inc");

function google_apis_fix($buffer) {
  $geoData     = geoip_open(PLUGIN_PATH . 'geo/GeoIP.dat', GEOIP_STANDARD);
  $countryCode = geoip_country_code_by_addr($geoData, $_SERVER['REMOTE_ADDR']);
  geoip_close($geoData);

  if( $countryCode === 'CN' ) {
    return str_replace('googleapis.com', 'useso.com', $buffer);
  }
  else {
    return $buffer;
  }
}

function gff_buffer_start() {
  ob_start("google_apis_fix");
}

function gff_buffer_end() {
  while ( ob_get_level() > 0 ) {
    ob_end_flush();
  }
}

add_action('init', 'gff_buffer_start');
add_action('shutdown', 'gff_buffer_end');

?>
