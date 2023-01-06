<?php
/**
 * CodeIgniter Gamelang
 *
 * An open source codeigniter management system
 *
 * @package 	CodeIgniter Gamelang
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link		https://github.com/tokoder/gamelang
 * @since		1.0.0
 */
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/**
 * Default_theme functions.php file.
 *
 * This file is an example of how to use functions.php for your themes.
 * You can use a class or simply a list of functions to add your hooks.
 *
 * @category 	Themes
 * @author		Tokoder Team
 */
// ------------------------------------------------------------------------
if ( ! class_exists('Default_theme', false)):
// ------------------------------------------------------------------------
class Default_theme {
	/**
	 * Class constructor.
	 * @return 	void
	 */
	public function __construct()
	{
		// Let's first change paths to layouts, partials and views.
		$this->set_views_paths();

		// Add IE8 support.
		add_filter( 'extra_head', array( $this, 'globals' ), 0 );
		add_filter( 'extra_head', array( $this, 'extra_head' ), 99 );

		// Register theme menus.
		add_action( 'theme_menus', array( $this, 'theme_menus' ) );

		// Register theme thumbnails sizes and names.
		add_action( 'theme_images', array( $this, 'theme_images' ) );

		// Enqueue our assets.
		add_action( 'after_theme_setup', array( $this, 'after_theme_setup' ), 0);

		// Add some meta tags.
		add_action( 'enqueue_meta', array( $this, 'enqueue_meta' ) );

		// Partials enqueue for caching purpose.
		add_action( 'enqueue_partials', array( $this, 'enqueue_partials' ) );

		// pagination
		add_filter( 'pagination', array( $this, 'pagination' ) );
	}

	// ------------------------------------------------------------------------
	// Views paths methods.
	// ------------------------------------------------------------------------

	/**
	 * Change paths to views, partials and layouts.
	 * @access 	public
	 */
	public function set_views_paths() {
		// Layouts files.
		add_filter( 'theme_layouts_path', function () {
			return get_theme_path( 'layouts/' );
		} );

		// Partials files.
		add_filter( 'theme_partials_path', function () {
			return get_theme_path( 'partials/' );
		});

		// Views files.
		add_filter( 'theme_views_path', function () {
			return get_theme_path( 'templates/' );
		} );
	}

	// ------------------------------------------------------------------------
	// Theme menus.
	// ------------------------------------------------------------------------

	/**
	 * Register themes available menus.
	 * @access 	public
	 * @return 	string
	 */
	public function theme_menus() {
		if ( ! is_callable('register_menu')) {
			return;
		}

		register_menu( array(
			'header-menu'  => 'lang:main_menu',		// Main menu (translated)
			'footer-menu'  => 'lang:footer_menu',	// Footer menu (translated)
			'sidebar-menu' => 'lang:sidebar_menu',	// Sidebar menu (translated)
		) );
	}

	// ------------------------------------------------------------------------
	// Theme images sizes.
	// ------------------------------------------------------------------------

	/**
	 * Register themes images sizes.
	 * @access 	public
	 * @return 	string
	 */
	public function theme_images() {
		if ( ! function_exists('add_image_size')) {
			return;
		}
		// These sizes are dummy ones. Use yours depending on your theme.
		add_image_size( 'thumb', 260, 180, true );
		add_image_size( 'avatar', 100, 100, true );
	}

	// ------------------------------------------------------------------------
	// Assets methods.
	// ------------------------------------------------------------------------

	/**
	 * This method is triggered after theme was installed.
	 * @access 	public
	 */
	public function after_theme_setup()
	{
		add_styles('assets/vendor/bootstrap/css/bootstrap.css');
		add_styles('assets/vendor/fontawesome-free/css/all.css');
		add_styles(get_theme_path('style.css'));

		add_script('assets/vendor/jquery/jquery.js');
		add_script('assets/vendor/bootstrap/js/bootstrap.bundle.js');
	}

	/**
	 * globals
	 *
	 * Method for adding JS global before anything else.
	 *
	 * @access 	public
	 * @param 	string 	$output 	StyleSheets output.
	 * @return 	void
	 */
	public function globals($output)
	{
		$config = array(
			'ajaxURL'      => ajax_url(),
			'site_url'     => site_url(),
			'token_name'   => config_item('csrf_token_name'),
			'token_cookie' => config_item('csrf_cookie_name'),
		);

		$output = '<script>';
		$output .= 'var cg = window.cg = window.cg || {};';
		$output .= 'cg.i18n = cg.i18n || {};';
		$output .= 'cg.config = '.json_encode($config).';';
		$output .= '</script>';

		return $output;
	}

	/**
	 * extra_head
	 *
	 * Method for adding extra stuff to output before closing </head> tag.
	 *
	 * @access 	public
	 * @param 	string 	$output 	The head output.
	 * @return 	void
	 */
	public function extra_head($output)
	{
		add_ie9_support($output, (ENVIRONMENT !== 'development'));
		return $output;
	}

	/**
	 * Enqueue extra meta tags.
	 * @access 	public
	 */
	public function enqueue_meta() {
		// We are only adding favicon.
		add_meta_tag('icon', base_url('favicon.ico'), 'rel', 'type="image/x-icon"');
	}

	/**
	 * We enqueue our partial views so they get cached.
	 * @access 	public
	 */
	public function enqueue_partials() {
		add_partial( 'header' );
		add_partial( 'sidebar' );
		add_partial( 'footer' );
	}

	// ------------------------------------------------------------------------

	/**
	 * Uses Bootstrap for pagination.
	 * @access 	public
	 * @param 	array
	 * @return 	array
	 */
	public function pagination($args)
	{
		$args['full_tag_open']   = '<div class="text-center"><ul class="pagination pagination-centered">';
		$args['full_tag_close']  = '</ul></div>';
		$args['num_links']       = 5;
		$args['num_tag_open']    = '<li>';
		$args['num_tag_close']   = '</li>';
		$args['prev_tag_open']   = '<li>';
		$args['prev_tag_close']  = '</li>';
		$args['next_tag_open']   = '<li>';
		$args['next_tag_close']  = '</li>';
		$args['first_tag_open']  = '<li>';
		$args['first_tag_close'] = '</li>';
		$args['last_tag_open']   = '<li>';
		$args['last_tag_close']  = '</li>';
		$args['cur_tag_open']    = '<li class="active"><span>';
		$args['cur_tag_close']   = '<span class="sr-only">(current)</span></span></li>';

		return $args;
	}

}
// ------------------------------------------------------------------------
endif; // End of class Default_theme.
// ------------------------------------------------------------------------

// Initialize class.
$Default_theme = new Default_theme();

// To remove the copyright added between DOCTYPE and <html>:
if ( ! function_exists('remove_copyright'))
{
	/**
	 * Remove the copyright.
	 * @param 	string 	$copyright
	 * @return 	string
	 */
	function remove_copyright($content)
	{
		// Change it or return an empty string
		// return null or $content = null;
		return $content;
	}

	// Now you add the filer.
	add_filter('CG_copyright', 'remove_copyright');
}

// To remove the generator meta tag:
if ( ! function_exists('remove_generator'))
{
	/**
	 * Remove the generator meta tag.
	 * @param 	string 	$content
	 * @return 	string
	 */
	function remove_generator($content)
	{
		// Change it or return an empty string
		// return null or $content = null;
		return $content;
	}

	// Now you add the filer.
	add_filter('CG_generator', 'remove_generator');
}

// ------------------------------------------------------------------------

if ( ! function_exists( 'fa_icon' ) ) {
	/**
	 * Useful to generate a fontawesome icon.
	 * @param  string $icon the icon to generate.
	 * @return string       the full FA tag.
	 */
	function fa_icon( $icon = 'user' ) {
		return "<i class=\"fa fa-fw fa-{$icon}\"></i>";
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists( 'bs_label' )) {
	function bs_label( $content = '', $type = 'default' ) {
		return "<span class=\"label label-{$type}\">{$content}</span>";
	}
}
// -----------------------------------------------------------------------------

add_filter('alert_template', function($output) {
	$output =<<<EOT
	<div class="{class} alert-dismissible fade show" role="alert">
		{message}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
	EOT;
	return $output;
});

// -----------------------------------------------------------------------------

add_filter('alert_template_js', function($output) {
	$output =<<<EOT
	'<div class="{class} alert-dismissible fade show" role="alert">
	+ '{message}'
	+ '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'
	+ '</div>'
	EOT;
	return $output;
});

/**
 * Example filters on how to edit captcha the way you want
 * We use a class for better performance and to avoid any possible
 * conflict with other components.
 */
if ( ! class_exists('captcha_class', false))
{
	class captcha_class {

		public function __construct() {}

		public function init() {
			if( ! get_instance()->themes->view_exists('auth/login'))  {
				return;
			}
			add_filter('captcha_font_path',        array($this, 'font_path'));
			add_filter('captcha_font_size',        array($this, 'font_size'));
			add_filter('captcha_word_length',      array($this, 'word_length'));
			add_filter('captcha_img_width',        array($this, 'img_width'));
			add_filter('captcha_img_height',       array($this, 'img_height'));
			add_filter('captcha_background_color', array($this, 'background_color'));
			add_filter('captcha_border_color',     array($this, 'border_color'));
			add_filter('captcha_text_color',       array($this, 'text_color'));
			add_filter('captcha_grid_color',       array($this, 'grid_color'));
		}

		// Font file.
		public function font_path($path) {
			// To use theme's provided font.
			return get_theme_path('assets/fonts/Vigasr.ttf');

			// To use CodeIgniter texb.ttf:
			return BASEPATH.'fonts/texb.ttf';

			// To use GD ugly font:
			return false;
		}

		// Font size.
		public function font_size($size) {
			$size = 16;
			return $size;
		}

		// Word length.
		public function word_length($length) {
			$length = 7;
			return $length;
		}

		// Image width.
		public function img_width($w) {
			$w = 150;
			return $w;
		}

		// Image height.
		public function img_height($h) {
			$h = 32;
			return $h;
		}

		// Background color.
		public function background_color($rgb) {
			// Return RGB color.
			return array(255, 255, 255);
		}

		// Border color:
		public function border_color($rgb) {
			// Return RGB color.
			return array(255, 255, 255);
		}

		// Text Color:
		public function text_color($rgb) {
			// Return RGB color.
			return array(200, 200, 200);
		}

		// Grid color.
		public function grid_color($rgb) {
			// Return RGB color.
			return array(235, 235, 235);
		}

	}

	// Initialize class.
	$captcha_class = new captcha_class();
	$captcha_class->init();
}