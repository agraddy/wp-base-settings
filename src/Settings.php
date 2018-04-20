<?php
namespace agraddy\base;

class Settings {
	function __construct() {
	}

	function config($key, $value) {
		echo 'key: ' . $key;
		echo '<br>';
		echo 'value: ' . $value;
		echo '<br>';
		die;
	}
}

?>
