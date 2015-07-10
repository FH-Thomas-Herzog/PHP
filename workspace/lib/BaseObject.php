<?php
/**
 * Created by PhpStorm.
 * User: p24457
 * Date: 13.06.2015
 * Time: 10:08
 */

class BaseObject {

	/**
	 * magic method "call"
	 *
	 * @param string $name
	 * @param array $arguments
	 * @throws Exception
	 */
	public function __call($name, $arguments) {
		throw new Exception('method ' . $name . ' is not declared');
	}
	public function __set($name, $value) {
		throw new Exception('attribute ' . $name . ' is not declared');
	}
	public function __get($name) {
		throw new Exception('attribute ' . $name . ' is not declared');
	}
	public static function __callStatic($name, $arguments) {
		throw new Exception('static method ' . $name . ' is not declared');
	}
}