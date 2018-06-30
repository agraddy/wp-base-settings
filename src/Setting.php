<?php
namespace agraddy\base;

class Setting {
	public $action = '';
	public $codes = [];
	public $config = [];
	public $descriptions = [];
	public $elements = [];
	public $extras = [];
	public $htm = '';
	public $key;
	public $names = [];
	public $sanitizes = [];
	public $sanitizes_args = [];
	public $title = '';
	public $titles = [];
	public $values = [];

	function __construct($key, $title, $action) {
		$this->key = $key;
		$this->title = $title;
		$this->action = $action;

		add_action('init', array($this, 'init'));
	}

	function action() {
		$pass = true;
		$message = '';

		// Check nonce
		if(!wp_verify_nonce($_POST['_wpnonce'], $this->action)) {
			$pass = false;
			$message .= 'There was a problem processing the request.';
			$message .= '<br>';
		}

		if($pass) {
			for($i = 0; $i < count($this->names); $i++) {
				if($this->elements[$i] == 'table_expand') {
					$extra = $this->codes[$i];
					for($j = 0; $j < count($extra['fields']); $j++) {
						$value = $_POST[$extra['fields'][$j]];
						update_option($this->key . '_' . $extra['fields'][$j], $value);
					}
				} else {
					array_push($this->sanitizes_args[$i], $_POST[$this->names[$i]]);
					$value = call_user_func_array($this->sanitizes[$i], $this->sanitizes_args[$i]);
					$value = stripslashes($value);
					update_option($this->key . '_' . $this->names[$i], $value);
				}
			}

			$output = array();
			$output['status'] = 'success';
			echo json_encode($output);
		} else {
			$output = array();
			$output['status'] = 'error';
			$output['message'] = $message;
			http_response_code(400);
			echo json_encode($output);
		}
		die;
	}

	function add($type, $title, $description = '', $extra = []) {
		$name = $this->parseToKey($title);
		if($type == 'table_expand') {
			$value = '';
		} else {
			$value = get_option($this->key . '_' . $name);
		}
		array_push($this->titles, $title);
		array_push($this->descriptions, $description);

		if($type == 'select_user') {
			array_push($this->codes, wp_dropdown_users(array(
				'show_option_none' => __( 'Please Select...' ),
				'name' => $name,
				'echo' => 0,
				'selected' => $value
			)));  

			array_push($this->extras, []);
			array_push($this->names, $name);
			array_push($this->values, $value);
			array_push($this->elements, 'select');
			array_push($this->sanitizes, 'preg_replace');
			array_push($this->sanitizes_args, ['/[^0-9]/', '']);
		} elseif(strpos($type, 'select_') === 0) {
			array_push($this->codes, wp_dropdown_pages(array(
				'show_option_none' => __( 'Please Select...' ),
				'post_type'=> substr($type, 7),
				'name' => $name,             
				'echo' => 0,                    
				'selected' => $value
			)));  

			array_push($this->extras, []);
			array_push($this->names, $name);
			array_push($this->values, $value);
			array_push($this->elements, 'select');
			array_push($this->sanitizes, 'preg_replace');
			array_push($this->sanitizes_args, ['/[^0-9]/', '']);
		} elseif($type == 'table_expand') {
			$extra['values'] = [];
			for($i = 0; $i < count($extra['fields']); $i++) {
				$extra['values'][$extra['fields'][$i]] = get_option($this->key . '_' . $extra['fields'][$i]);
				if(!$extra['values'][$extra['fields'][$i]]) {
					$extra['values'][$extra['fields'][$i]] = [''];
				}
			}
			array_push($this->codes, $extra);
			array_push($this->extras, []);
			array_push($this->names, $name);
			array_push($this->values, $value);
			array_push($this->elements, $type);
			array_push($this->sanitizes, 'sanitize_text_field');
			array_push($this->sanitizes_args, []);
		} elseif($type == 'textarea') {
			array_push($this->codes, '');
			array_push($this->extras, []);
			array_push($this->names, $name);
			array_push($this->values, $value);
			array_push($this->elements, $type);
			array_push($this->sanitizes, 'sanitize_textarea_field');
			array_push($this->sanitizes_args, []);
		} else {
			array_push($this->codes, '');
			array_push($this->extras, $extra);
			array_push($this->names, $name);
			array_push($this->values, $value);
			array_push($this->elements, $type);
			array_push($this->sanitizes, 'sanitize_text_field');
			array_push($this->sanitizes_args, []);
		}
	}

	function adminEnqueueScripts($hook) {
		if((isset($this->config['page_hook']) && $this->config['page_hook'] == $hook) || $hook == 'admin_page_' . $this->action || $hook == 'settings_page_' . $this->action) {
			wp_enqueue_script($this->action, plugins_url('setting.js', __FILE__), array('jquery'));
			wp_enqueue_style($this->action, plugins_url('setting.css', __FILE__));
		}
	}

	function config($key, $value) {
		$this->config[$key] = $value;
	}

	function html() {
		$action = $this->action;
		$codes = $this->codes;
		$descriptions = $this->descriptions;
		$elements = $this->elements;
		$extras = $this->extras;
		$names = $this->names;
		$title = $this->title;
		$titles = $this->titles;
		$values = $this->values;

		ob_start();
		include 'view.php';
		$this->htm = ob_get_clean();
		return $this->htm;
	}

	function init() {
		add_action('wp_ajax_' . $this->action, array($this, 'action'));
		add_action('admin_enqueue_scripts', array($this, 'adminEnqueueScripts'));
	}

	function parseToKey($input) {   
		$output = $input;               
		$output = strtolower($output);  
		$output = str_replace(' ', '_', $output); 
		return $output;
	}
}

?>
