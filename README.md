# agraddy/wp-base-settings

Allows easy creation of Wordpress settings pages.

## Installation

```
composer require agraddy/wp-base-settings
```

## Why

Creating Wordpress settings pages takes a lot or work. The goal of this library is to simplify the process so that you can quickly output custom pages with custom fields.

## Basic Example

You can create a very simple custom plugin settings page following these steps:

* Make a directory named custom-plugin inside your Wordpress /wp-content/plugins directory
* Inside the /wp-content/plugins/custom-plugin directory, use composer to install agraddy/wp-base-settings (this should create a vendor directory)
* Create a file named main.php and add the code below.
* Put the shortcode [cp\_data\_output] on a page to view the output. 

```
<?php
/**   
 * Plugin Name: Custom Plugin  
 * Description: A simple custom plugin to demonstrate creating a settings page.
 * Version: 1.0.0              
 */

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

class Custom_Plugin {
        public $key = 'cp';
        public $key_ = 'cp_';

        public $settings;
        public $page;

        public function __construct() {
                add_action('init', [$this, 'init']);

                // Create an instance of Settings.
                $this->settings = new \agraddy\base\Settings();

                // Set the key that needs to be used.
                $this->settings->config('key', $this->key);

                // Create the title for the page and the menu slug (used in the url)
                // In this example, it is: cp_settings
                $this->page = $this->settings->page('Custom Settings', $this->key_ . 'settings');

                // Create three different types of fields to save on the settings page.
                $this->page->add('text', 'First Example');
                $this->page->add('textarea', 'For Longer Text');
                $this->page->add('select_page', 'Pick A Page');
        }

        public function init() {
                // Add to the menu using typical Wordpress code (only allow admins to access):
                add_action('admin_menu', function() {
                        add_options_page('Custom Settings', 'Custom Settings', 'manage_options', $this->key_ . 'settings', [$this, 'customSettings']);
                });

                // Put the shortcode [cp_data_output] on a page to view the output.
                add_shortcode('cp_data_output', [$this, 'shortcodeDataOutput']);
        }

        public function customSettings() {
                // Output the html for the settings page.
                echo $this->page->html();
        }

        public function shortcodeDataOutput() {
                // The data is stored in Wordpress options.
                // It is prefixed by the key that was setup.
                // The name that was used for the field is converted to lowercase and spaces replace with underscores.
                $first_example = get_option('cp_first_example');
                $longer_text = get_option('cp_for_longer_text');
                $page_id = get_option('cp_pick_a_page');

                $output = '';
                $output .= 'First Example: ' . esc_html($first_example);
                $output .= '<br>';
                $output .= 'For Longer Text: ' . esc_html($longer_text);
                $output .= '<br>';
                $output .= 'Pick A Page: ' . esc_html($page_id);
                $output .= '<br>';

                return $output;
        }
}

new Custom_Plugin();

?>
```

## Notes
* The main settings code is in the \_\_construct method (most of the other code is to setup a custom plugin to see it working).
* This is just the introduction page to explain the basics. If you have any questions, please open an issue.

## License

MIT

