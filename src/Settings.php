<?php
namespace agraddy\base;
use agraddy\base\Setting;

class Settings {
	public $config = [];
	public $fields = [];

	function __construct() {
	}

	function add($title) {
		$temp = [];
		$temp['title'] = $title;
		$this->fields[] = $temp;
	}

	function config($key, $value) {
		$this->config[$key] = $value;
	}

	function get($name) {
		$name = $this->parseToKey($name);
		return get_option($this->config['key'] . '_' . $name);
	}

	function page($title, $action) {
		return new Setting($this->config['key'], $title, $action);
	}

	function parseToKey($input) {   
		$output = $input;               
		$output = strtolower($output);  
		$output = str_replace(' ', '_', $output); 
		return $output;
	}
}

?>
