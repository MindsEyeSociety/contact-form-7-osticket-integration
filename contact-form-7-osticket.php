<?php
/**
 * @author Joe Terranova <joeterranova@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

/*
Plugin Name: Contact Form 7 OSTicket integration
Plugin URI: https://github.com/MindsEyeSociety/contact-form-7-osticket-integration
Description: Submit contact form 7 to OSTicket
Version: 1.0.0
Author: Joe Terranova
Author URI: https://www.mindseyesociety.org
License: AGPLv3
Text Domain: ccontact-form-7-osticket-integration
*/

define( 'CF7_OSTICKET__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CF7_OSTICKET__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( CF7_OSTICKET__PLUGIN_DIR . 'cf7_osticket_settings.php' );
require_once( CF7_OSTICKET__PLUGIN_DIR . 'class.api.php' );

if ( is_admin() ) {
  require_once( CF7_OSTICKET__PLUGIN_DIR . 'cf7_osticket_admin.php' );
  add_action( 'init', array( 'cf7_osticket_admin', 'init' ) );
}

add_filter( 'wpcf7_contact_form_properties', 'contact_form_properties');
add_action('wpcf7_before_send_mail', 'cf7_osticket_before_send_mail');
add_filter( 'wpcf7_skip_mail', 'cf7_osticket_skip_mail',10,2);
function cf7_osticket_before_send_mail($contact_form) {
  $properties = $contact_form->get_properties();
  if (empty($properties['osticket']['enable'])) {
    return;
  }

  $server = cf7_osticket_settings::getHost();
  $apikey = cf7_osticket_settings::getApiKey();
  $path = cf7_osticket_settings::getPath();

  $submission = WPCF7_Submission::get_instance();
  $submittedData = $submission->get_posted_data();
  $data = array();
  foreach($submittedData as $key => $val) {
    if (is_array($val)) {
      $val = implode(", ", $val);
    }
    $data[$key] = $val;
  }

  $parameters = Array();
  $post = Array();
  parse_str($properties['osticket']['parameters'],$parameters);
  foreach($parameters as $key => $val) {
    if (!empty($key)) {
      if(substr($val,0,1) == '[' && substr($val,-1) == ']'){
          $index = substr($val,1,-1);
          if(isset($data[$index])) $val = $data[$index];
      }
      $post[$key] = $val;
    }
  }
  $post['ip'] = $_SERVER['REMOTE_ADDR'];
  #curl post
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $server.$path);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
  curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.8');
  curl_setopt($ch, CURLOPT_HEADER, FALSE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$apikey));
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  //curl_setopt($ch, CURLOPT_VERBOSE, true);
  //$verbose = fopen('php://temp', 'w+');
  //curl_setopt($ch, CURLOPT_STDERR, $verbose);
  $result=curl_exec($ch);
  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  //rewind($verbose);
  //echo stream_get_contents($verbose);
  return true;
}

function contact_form_properties($properties) {

  if (!isset($properties['osticket'])) {
    $properties['osticket'] = array(
      'enable' => false,
      'skipmail' => true,
      'parameters' => 'name=[your-name]&email=[your-email]&subject=[your-subject]&message=[your-message]&topicId=[your-topic]'
    );
  }
  return $properties;
}

function cf7_osticket_skip_mail($skip_mail,$contact_form){
  $properties = $contact_form->get_properties();
  if (empty($properties['osticket']['enable'])) {
    return;
  }
  if (empty($properties['osticket']['skipmail'])){
      return false;
  }
  return true;
}