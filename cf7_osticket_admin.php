<?php
/**
 * @author Joe Terranova <joeterranova@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class cf7_osticket_admin {
  const NONCE = 'cf7_osticket_admin';


  protected static $initiated = false;

  public static function init() {
    if (!self::$initiated) {
      self::$initiated = true;
      add_action( 'admin_menu', array( 'cf7_osticket_admin', 'admin_menu' ) );
      add_action( 'admin_enqueue_scripts', array('cf7_osticket_admin', 'admin_enqueue_scripts') );
      add_action( 'wpcf7_save_contact_form', array('cf7_osticket_admin', 'save_contact_form'));

      add_filter( 'wpcf7_editor_panels', array('cf7_osticket_admin', 'panels'));
    }
  }

  public static function admin_menu() {
    add_options_page( __('OSTicket Settings', 'contact-form-7-osticket-integration'), __('OSTicket Settings', 'contact-form-7-osticket-integration'), 'manage_options', 'cf7_osticket_admin', array( 'cf7_osticket_admin', 'display_page' ) );
  }

  public static function get_page_url( $page = 'config' ) {

    $args = array( 'page' => 'cf7_osticket_admin' );
    $url = add_query_arg( $args, admin_url( 'options-general.php' ) );

    return $url;
  }

  public static function display_page() {
    $host = cf7_osticket_settings::getHost();
    $api_key = cf7_osticket_settings::getApiKey();
    $path = cf7_osticket_settings::getPath();

    if (isset($_POST['host'])) {
      cf7_osticket_settings::setHost($_POST['host']);
    }
    if (isset($_POST['api_key'])) {
      cf7_osticket_settings::setApiKey($_POST['api_key']);
    }
    if (isset($_POST['path'])) {
      cf7_osticket_settings::setPath($_POST['path']);
    }
    cf7_osticket_admin::view( 'settings', compact( 'host', 'api_key', 'path') );
  }

  public static function view( $name, array $args = array() ) {
    $args = apply_filters( 'cf7_osticket_view_arguments', $args, $name );

    foreach ( $args AS $key => $val ) {
      $$key = $val;
    }

    load_plugin_textdomain( 'contact-form-7-osticket-integration' );

    $file = CF7_OSTICKET__PLUGIN_DIR . 'views/'. $name . '.php';

    include( $file );
  }

  /**
   * Add a OSTicket setting panel to the contact form admin section.
   *
   * @param array $panels
   * @return array
   */
  public static function panels($panels) {
    $panels['contact-form-7-osticket-integration'] = array(
      'title' => __( 'OSTicket', 'contact-form-7-osticket-integration' ),
      'callback' => array('cf7_osticket_admin', 'osticket_panel'),
    ) ;
    return $panels;
  }

  public static function osticket_panel($post) {
    $osticket = $post->prop('osticket' );
    cf7_osticket_admin::view('osticket_panel', array('post' => $post, 'osticket' => $osticket));
  }

  public static function save_contact_form($contact_form) {
    $properties = $contact_form->get_properties();
    $osticket = $properties['osticket'];

    $osticket['enable'] = true;

    if ( isset( $_POST['osticket-parameters'] ) ) {
      $osticket['parameters'] = trim( $_POST['osticket-parameters'] );
    }
    if (!empty($_POST['osticket-skipmail'] ) ) {
      $osticket['skipmail'] = true;
    }
    else{
        $osticket['skipmail'] = false;
    }

    $properties['osticket'] = $osticket;
    $contact_form->set_properties($properties);
  }

  public static function admin_enqueue_scripts($hook_suffix) {
    if ( false === strpos( $hook_suffix, 'wpcf7' ) ) {
      return;
    }

    wp_enqueue_script( 'cf7_osticket-admin',
      CF7_OSTICKET__PLUGIN_URL. 'js/admin.js',
      array( 'jquery', 'jquery-ui-tabs' )
    );
  }

}