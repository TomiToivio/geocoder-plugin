<?php
/*
Plugin Name: Geocoder Plugin Example
Plugin URI: http://api.okf.fi/console/
Description: Example WordPress Plugin for Geocoder API
Version: 1.0
Author: Tomi Toivio
Author URI: http://fi.flossmanuals.net
License: GPL2
*/

/*  Copyright 2013 Tomi Toivio (email: tomi@flossmanuals.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/* Geocoder Widget */
class ttGeocoderWidget extends WP_Widget {

  function SeAnalyticsWidget() {
    // Instantiate the parent object
    parent::__construct( false, 'Geocoder Widget' );
  }

  function widget( $args, $instance ) {
    echo '<div id="text-6" class="widget widget_text well nav nav-list"><h4 class="widgettitle nav-header">Geocoder Widget</h4><div class="textwidget">';
    tt_geocoder();
    echo '</div></div>';
  }

  function update( $new_instance, $old_instance ) {
    // Save widget options
  }

  function form( $instance ) {
    // Output admin widget options form
  }
}

/* Registering Geocoder Widget */
function tt_register_geocoder_widget() {
  register_widget( 'ttGeocoderWidget' );
}
add_action( 'widgets_init', 'tt_register_geocoder_widget' );

/* Content for Geocoder Widget */
function tt_geocoder() {

  /* Get IP address from server */
  $ip= $_SERVER['REMOTE_ADDR'];

  /* Get IP address coordinates from IP Info DB API */
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "http://api.ipinfodb.com/v3/ip-city/?key=dbc019823824b0e7943b0c1f29033328d3a416513a3ead4151b1e133cb253454&ip=" . $ip);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  curl_close($ch);
  $data = explode(";", $data);
  $lat = $data[8];
  $lng = $data[9];

  /* Get formatted address from Geocoder API */
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "http://api.okf.fi/gis/1/geocode.json?lat=" . $lat . "&lng=" . $lng);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  curl_close($ch);
  $data = json_decode($data,true);
  $data=$data['results'];
  $data=$data[0];
  $osoite=$data['formatted_address']; 

  /* Echo the address */
  echo '<p><a href="http://api.okf.fi/console/">Geocoder API</a> result address: ';
  echo $osoite;
  echo '</p>';

}

?>
