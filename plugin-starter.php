<?php

/**
 *
 * @wordpress-plugin
 * Plugin Name: Plugin Starter
 * Description: The Agency WordPress plugin boilerplate
 * Author: Agency
 * Version: 1.0
 * Author URI: http://agency.sc/
 */

/*
 * GETTING STARTED WITH THE STARTER
 * ---------------------------------------------------------
 *
 * Every time you use the plugin starter you need to do
 * run a few find/replaces on the files.
 * 
 * On this file you need to process the following strings:
 *
 * 1. Plugin_Starter
 * 2. Plugin Starter
 * 3. plugin_starter
 * 4. plugin-starter
 *
 * Then rename this file, the template tags file and the title in the plugin options.
 *
 * FOLDER STRUCTURE
 * Should you need to add any vendor libraries (think API wrappers),
 * add them to a folder called 'libs'. Initiate the classes in __construct.
 *
 * CONSISTENT DOCUMENTATION
 * All functions you write in this class should be documented in phpDoc,
 * including what the function does, what params it takes and what it outputs.
 * You can see the full phpDoc syntax here: goo.gl/09S2wc
 *
 * WRAPPING UP
 * Keep code clean. Once you've done these steps, remove these instructions
 * 
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/*==========  Activation Hook  ==========*/
register_activation_hook( __FILE__, array( 'Plugin_Starter', 'install' ) );


/**
 * Main Plugin_Starter Class
 * 
 * @class Plugin_Starter
 * @version 0.1
 */
class Plugin_Starter {

	public $errors = false;
	public $notices = false;
	
	function __construct() {

		$this->path = plugin_dir_path(__FILE__);
		$this->folder = basename($this->path);
		$this->dir = plugin_dir_url(__FILE__);
		$this->version = '1.0';
		
		$this->errors = false;
		$this->notice = false;
		
		// Actions
		add_action('init', array($this, 'setup'), 10, 0);
		add_action('wp_enqueue_scripts', array($this, 'scripts'));
		add_action('wp_loaded', array($this , 'forms'));
		add_action('parse_request', array($this , 'custom_url_paths'));
		add_action('admin_menu', array($this, 'register_options_page'));

		// Shortcodes
		// add_shortcode('students', array($this, 'shortcode'));
		
		// Notices (add these when you need to show the notice)
		// add_action( 'admin_notices', array($this, 'admin_success'));
		// add_action( 'admin_notices', array($this, 'admin_error'));
				
	}

	public static function install() {

		/**
		*
		* Add methods here that should be run when the plugin is activated.
		*
		**/

	}

	public function setup() {

		// register types
		$this->register_types();

	}

	
	public function register_types() {


		/*==========  POST TYPE NAME  ==========*/

		$args = array(
			'labels'             => self::build_type_labels('Topic', 'Topics'),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'topics' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_icon'			 => 'dashicons-welcome-learn-more',
			'menu_position'      => 40,
			'supports'           => array( 'title', 'editor', 'page-attributes' )
		);

		// register_post_type( 'topic', $args );


	}

	private static function build_type_labels($name, $plural) {

		return array(
			'name'               => $plural,
			'singular_name'      => $name,
			'add_new'            => "Add New",
			'add_new_item'       => "Add New $name",
			'edit_item'          => "Edit $name",
			'new_item'           => "New $name",
			'all_items'          => "All $plural",
			'view_item'          => "View $name",
			'search_items'       => "Search $plural",
			'not_found'          => "No " . strtolower($plural) . " found",
			'not_found_in_trash' => "No " . strtolower($plural) . " found in trash",
			'parent_item_colon'  => '',
			'menu_name'          => $plural
		);

	}

	/**
	 * Enqueue Scripts
	 */
	public function scripts() {

		// wp_enqueue_script('jquery.validate', '//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js', array('jquery'), $this->version, true);
		
	}
	
	/**
	 * Form processing
	 * 
	 * This function powers your form processing,
	 * based on the value of a hidden "plugin_starter_action" field
	 * 
	 * Route forms to other functions within this class.
	 */
	public function forms() {

		if (!isset($_POST['plugin_starter_action'])) return;

		switch ($_POST['plugin_starter_action']) {

			case 'action':
				// $this->action($_POST);
				break;

			default:
				break;
		}

	}


	/**
	 * Custom URLS
	 * 
	 * Manages custom permalinks and routes for
	 * plugin pages and API calls
	 * 
	 */
	public function custom_url_paths($wp) {

		$pagename = (isset($wp->query_vars['pagename'])) ? $wp->query_vars['pagename'] : $wp->request;

		switch ($pagename) {

			case 'pluginstarter/api':
				// $this->api($_GET);
				break;

			default:
				break;

		}

	}

	/**
	 * Register options page
	 */
	public function register_options_page() {

		// main page
		add_options_page('Plugin Starter', 'Plugin Starter', 'manage_options', 'plugin_starter_options', array($this, 'include_options'));
		add_action('admin_init', array($this, 'plugin_options'));
		
	}


	/**
	 * Get options template
	 */
	public function include_options() { require('templates/options.php'); }


	/**
	 * Register plugin settings
	 * 
	 * Register each unique setting administered on your 
	 * options page in a new line in the array.
	 */
	public function plugin_options() {

		$options = array(
			'plugin_starter_test',
			'plugin_starter_test2'
		);

		foreach ($options as $option) {
			register_setting('plugin_starter_options', $option);
		}

	}

	/**
	 * Shortcode Include
	 */
	public function shortcode() {

		$errors = $this->errors;

		ob_start();
		// include $this->template('template.php');
		return ob_get_clean();

	}

	/**
	 * Outputs a WordPress error notice
	 * 
	 * Push your error to $this->errors then show with:
	 * add_action( 'admin_notices', array($this, 'admin_error'));
	 */
	public function admin_error() {

		if(!$this->errors) return;

		foreach($this->errors as $error) :
		
	?>

		<div class="error settings-error notice is-dismissible"> 
			
			<p><strong><?php print $error ?></strong></p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		
		</div>

	<?php 

		endforeach;

	}

	/**
	 * Outputs a WordPress notice
	 * 
	 * Push your error to $this->notices then show with:
	 * add_action( 'admin_notices', array($this, 'admin_success'));
	 */
	public function admin_success() {

		if(!$this->notices) return;

		foreach($this->notices as $notice) : 

	?>

		<div class="updated settings-error notice is-dismissible"> 
			
			<p><strong><?php print $notice ?></strong></p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		
		</div>

	<?php 
		
		endforeach;

	}

	/**
	 * Email wrapper, to allow for string replacement
	 * 
	 * @param string $to email address
	 * @param string $subject
	 * @param string $message
	 * @param array $replacements array of key => value replacements
	 */
	public function email($to, $subject, $message, $replacements = array()) {

		//replacements
		foreach ($replacements as $variable => $replacement) {
			$message = str_replace($variable, $replacement, $message);
			$subject = str_replace($variable, $replacement, $subject);
		}

		//Send from the site email
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: ' . get_bloginfo('name') . ' <' . get_bloginfo('admin_email') . '>'
		);

		//WP mail function
		wp_mail( $to, $subject, $message , $headers);

	}


	/**
	 * Output JSON
	 * 
	 * @param $array Array to encode
	 */
	public function output_json($array) {

		header('Content-type: application/json');
		echo json_encode($array);
		exit();

	}

	/**
	 * Template lookup
	 * 
	 * Could do theme overrides in the future
	 */
	public function template($filename) {

		return 'templates/' . $filename;

	}


	public function redirect($path) {
		
		wp_safe_redirect( $path );
	  	exit();

	}

	/**
	 * Output CSV
	 * 
	 * @param $array Array to output (keyed)
	 * @param $filename Filename to download
	 */
	public function output_csv($array, $filename = 'report.csv') {
		
		ob_clean();
		ob_start();

		$file = fopen('php://output', 'w');
		
		// generate csv lines from the inner arrays
		$headings = array();
		foreach ($array[0] as $key => $line) {
			$headings[] = $key;
		}

		fputcsv($file, $headings);
		foreach($array as $row) {
		    fputcsv($file, $row);
		}

	    // rewind file
	    $output = stream_get_contents($file);
	    fclose($file);

	    // prep download
	    header("Content-type: text/x-csv");
	    header("Content-Transfer-Encoding: binary");
	    header('Content-Disposition: attachement; filename="' . $filename . '";');
	    header("Pragma: no-cache");
	    header("Expires: 0");
	    
	    echo $output;
	    exit();

	}

}


/**
 * @var class Plugin_Starter $plugin_starter
 */

require_once('plugin-starter-template-tags.php');
$plugin_starter = new Plugin_Starter();




