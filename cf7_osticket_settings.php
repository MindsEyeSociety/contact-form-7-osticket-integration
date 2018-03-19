<?php
/**
 * @author Joe Terranova <joeterranova@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

class cf7_osticket_settings {

	public static function getHost() {
		return get_option('cf7_osticket_host');
	}

	public static function getPath() {
		return get_option('cf7_osticket_path') ? get_option('cf7_osticket_path') : '/osticket/api/tickets.json';
	}

	public static function setHost($host) {
		update_option( 'cf7_osticket_host', $host );
	}

	public static function setPath($path) {
		update_option( 'cf7_osticket_path', $path );
	}

	public static function getApiKey() {
		return get_option('cf7_osticket_api_key');
	}

	public static function setApiKey($key) {
		update_option( 'cf7_osticket_api_key', $key );
	}

}