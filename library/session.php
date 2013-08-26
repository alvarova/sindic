<?php
/**
 * Objeto que guarda y codifica cookies
 *
 * LICENSE: Copyright: Abraham Cruz Sustaita
 *
 * @category   Sit_Session
 * @copyright  http://creativecommons.org/licenses/by-nc-sa/3.0/deed.es
 * @license    TODO
 * @version    $Id:1.0.0$
 * @since      File available since Release 1.0.0
 */
/**
 * Objeto de sesión
 * Para las sesiones se hace uso de cookies
 * Para aumentar la seguridad, estás se encuentra codificadas en md5 y base64
 * Aunque la codificación en md5 no es por sí misma segura dado la facilidad de 
 * decodificación, se creó una regla que agrega un valor codificado al string a decodificar
 * Al ser este string codificado basado en una palabra del sistema hace imposible para 
 * las personas desencriptarlo
 * 
 * @author Abraham Cruz (abraham.sustaita@gmail.com)
 *
 */
class Sit_Session {
	/**
	 * Borra todas las cookies creadas asignando una fecha en el pasado como fecha de expiración
	 */
	public static function destroyCookies() {
		self::setCookie ( 'usr', '---', 315554400, '/' );
		self::setCookie ( 'pwd', '---', 315554400, '/' );
		self::setCookie ( 'lvl', '---', 315554400, '/' );
	}
	/**
	 * Destruye una cookie específica
	 * @param string $cookieName
	 */
	public static function unsetCookie($cookieName = null) {
		if ($cookieName) {
			self::setCookie ( $cookieName, '---', 315554400, '/' );
		}
	}
	/**
	 * Crea las cookies de autenticación
	 * Si se le pasa el tercer parámetro como true entonces asigna el tiempo de 
	 * expiración de acuerdos a los días guardados en el archivos de configuración
	 * Si no, entonces sólo guarda la sesión durante 20 minutos
	 * 
	 * @param string $user
	 * @param string $password
	 * @param boolean $remember
	 */
	public static function setLogginCookies($user, $password, $lvl, $remember = false) {
		self::setCookie ( 'usr', $user, self::_getExpiredCookieTime ( $remember ), '/' );
		self::setCookie ( 'pwd', $password, self::_getExpiredCookieTime ( $remember ), '/' );
		self::setCookie ( 'lvl', $lvl, self::_getExpiredCookieTime ( $remember ), '/' );
		self::setCookie ( 'remember', ( int ) $remember, self::_getExpiredCookieTime ( $remember ), '/' );
	}
	/**
	 * Método que actualiza las cookies de la sesión de acuerdo a los datos guardados
	 * Primero revisa si el usuario selecciono ser recordado
	 * Después en base a eso actualiza el tiempo de vida de las 3 cookies
	 */
	public static function updateLoginCookies() {
		$remember = ( bool ) ( int ) self::getDecodeCookieValue ( self::getCookie ( 'remember' ) );
		$user = self::getDecodeCookieValue ( self::getCookie ( 'usr' ) );
		$password = self::getDecodeCookieValue ( self::getCookie ( 'pwd' ) );
		$lvl = self::getDecodeCookieValue ( self::getCookie ( 'lvl' ) );
		self::setCookie ( 'usr', $user, self::_getExpiredCookieTime ( $remember ), '/' );
		self::setCookie ( 'pwd', $password, self::_getExpiredCookieTime ( $remember ), '/' );
		self::setCookie ( 'remember', ( int ) $remember, self::_getExpiredCookieTime ( $remember ), '/' );
		self::setCookie ( 'lvl', $user, self::_getExpiredCookieTime ( $remember ), '/' );
	}
	/**
	 * Método que setea una cookie en el sistema
	 * 
	 * @param string $name Nombre de la cookie que después será codificado en md5 y base64
	 * @param string $value Valor de la cookie. Se codificará en base64
	 * @param int $time Timestamp en el que caducará la cookie
	 * @param string $path Path de la cookie
	 * @param boolean $secure
	 */
	public static function setCookie($name, $value, $time = null, $path = null, $secure = false, $safe = true) {
		$time = $time !== null ? $time : time () + (Zend_Registry::getInstance ()->cookie ['lifetime'] * 24 * 360);
		$path = $path !== null ? $path : Zend_Registry::getInstance ()->cookie ['path'];
		return setcookie ( self::getCookieName ( $name ), ($safe === true ? self::encodeCookieValue ( $value ) : $value), $time, $path, $secure );
	}
	/**
	 * Obtiene el tiempo en que caducará la cookie
	 * Si se le pasa un true usa el número de días que se definió en la 
	 * configuración del sistema, de otro modo, deja viva la cookie durante 20 minuntos
	 * 
	 * @param boolean $remember
	 * @return int 
	 */
	private static function _getExpiredCookieTime($remember = false) {
		$remember = ( bool ) $remember;
		if ($remember === true) {
			$addTime = Zend_Registry::getInstance ()->cookie ['lifetime'] * 24 * 360;
		} else {
			$addTime = 20 * 60;
		}
		return time () + $addTime;
	}
	/**
	 * Regresa el nombre con el que se creará una cookie
	 * El nombre será el string enviado en $name codificado en base64
	 * y luego encriptado en md5
	 * 
	 * @param string $name
	 * @return string
	 */
	public static function getCookieName($name = null) {
		$name = $name ? $name : 'c00k13C0r3';
		return md5 ( base64_encode ( Zend_Registry::getInstance ()->cookie ['secure'] ) . base64_encode ( $name ) );
	}
	/**
	 * Codifca un valor para guardarlo en la cookie.
	 * El valor será codificado en base64, después de lo que se le agregará otro string 
	 * codificado en base64 y por último se volverá a codificar en base64
	 * @param string $value
	 */
	public static function encodeCookieValue($value) {
		$encodeSafeString = base64_encode ( Zend_Registry::getInstance ()->cookie ['secure'] );
		$length = strlen ( $encodeSafeString );
		$limit = ceil ( $length / 2 );
		$firstPart = substr ( $encodeSafeString, 0, $limit );
		$secondPart = substr ( $encodeSafeString, $limit );
		$encodeWord = base64_encode ( $value );
		$wordlength = ceil ( strlen ( $encodeWord ) / 3 );
		$encodeWord1 = substr ( $encodeWord, 0, $wordlength );
		$encodeWord2 = substr ( $encodeWord, $wordlength, $wordlength );
		$encodeWord3 = substr ( $encodeWord, ($wordlength * 2) );
		$valueEncode = base64_encode ( $encodeWord1 . $firstPart . $encodeWord2 . $secondPart . $encodeWord3 );
		//echo $encodeWord . ' - tiene: ' . $wordlength . '<br />';
		//echo $encodeWord1 . ' | ' . $firstPart . ' | ' . $encodeWord2 . ' | ' . $secondPart . ' | ' . $encodeWord3 . '<br />';
		//echo $valueEncode . '<br />';
		//echo self::getDecodeCookieValue($valueEncode);
		//exit ();
		return $valueEncode;
	}
	/**
	 * Decodifica un string codificado en base64
	 * @param string $encode
	 */
	public static function getDecodeCookieValue($encode = null) {
		//echo $encode . ' -> ' . base64_decode ( str_replace ( base64_encode ( Zend_Registry::getInstance ()->cookie['secure'] ), '', base64_decode ( $encode ) ) ) . '<br />';
		if ($encode) {
			$encodeSafeString = base64_encode ( Zend_Registry::getInstance ()->cookie ['secure'] );
			$length = strlen ( $encodeSafeString );
			$limit = ceil ( $length / 2 );
			$firstPart = substr ( $encodeSafeString, 0, $limit );
			$secondPart = substr ( $encodeSafeString, $limit );
			$first = base64_decode ( $encode );
			$secondA = str_replace ( $firstPart, '', $first );
			$secondB = str_replace ( $secondPart, '', $secondA );
			$ret = base64_decode ( $secondB );
			return $ret;
		}
		return '';
	}
	/**
	 * Revisa si la cookie existe
	 * Encripta en md5 un string compuesto del nombre y un string codificado en md5
	 * Se usa así porque de esta manera se evita el que se pueda usar un diccionario de datos
	 * @param string $name
	 */
	public static function getCookie($name = '') {
		$name = md5 ( base64_encode ( Zend_Registry::getInstance ()->cookie ['secure'] ) . base64_encode ( $name ) );
		//echo $name;
		if (isset ( $_COOKIE [$name] )) {
			return $_COOKIE [$name];
		}
		return false;
	}
	/**
	 * Evita que sea instanciado
	 */
	protected function __construct() {
	}
}
	