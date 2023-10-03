<?php
/**
 * tokoder
 *
 * An Open-source online ordering and management system for store
 *
 * @author		Tokoder Team
 * @copyright	Copyright (c) 2022, Tokoder (https://tokoder.com/)
 * @license 	https://opensource.org/licenses/MIT	MIT License
 * @link			https://github.com/tokoder/tokoder
 * @since		1.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CG_Upload Class
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class CG_Upload extends CI_Upload
{
	/**
	 * Upload path
	 *
	 * @var	string
	 */
	public $upload_year_month = TRUE;
	public $create_thumb = TRUE;
	public $limit_medium = 1200;
	public $limit_thumb = 150;

	/**
	 * Holds a cached version of default config to avoid repeating.
	 * @var array
	 */
	protected $_config;

	/**
	 * __construct
	 *
	 * Simply loads the default configuration if not set then pass it to parent.
	 *
	 * @access 	public
	 * @param 	array
	 * @return 	void
	 */
	public function __construct($config = array())
	{
		$config = array_replace_recursive($this->_default_config(), $config);
		log_message('info', 'CG_Upload Class Initialized');
		parent::__construct($config);
	}

	// ------------------------------------------------------------------------

	/**
	 * validate_upload_path
	 *
	 * Verifies that the upload is valid and has proper permissions.
	 * If the folder does not exist, it will create it if possible.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	bool
	 */
	public function validate_upload_path()
	{
		$path = $this->_config['upload_path'];
		empty($this->upload_path) && $this->upload_path = $path;
		$this->upload_path = rtrim($this->upload_path, '/').'/';

		// Are we using YEAR/MONTH path? (Ignore avatars).
		if (false === strpos($this->upload_path, 'avatars')
			&& false === strpos($this->upload_path, date('Y/m/'))
			&& true === get_option('upload_year_month', true)
			&& true === $this->upload_year_month)
		{
			$this->upload_path .= date('Y/m/');
		}

		// We make sure to create the folder if it does not exist.
		if (true !== is_dir($this->upload_path)
			&& false === mkdir($this->upload_path, 0755, true))
		{
			$this->set_error('upload_no_filepath', 'error');
			return false;
		}

		// Another layer of formatting.
		$this->upload_path = str_replace('\\', '/', realpath($this->upload_path));

		if ( ! is_really_writable($this->upload_path))
		{
			$this->set_error('upload_not_writable', 'error');
			return FALSE;
		}

		$this->upload_path = preg_replace('/(.+?)\/*$/', '\\1/',  $this->upload_path);
		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * _default_config
	 *
	 * Returns an array of default configuration in case no config is passed,
	 * with extra filters applied to them.
	 *
	 * @access 	protected
	 * @param 	none
	 * @return 	array
	 */
	protected function _default_config()
	{
		// If not cached, cache it first.
		if ( ! isset($this->_config))
		{
			$this->_config = array(
				// Options stored in database.
				'upload_path'      => APPPATH.get_option('upload_path', 'uploads'),
				'allowed_types'    => get_option('allowed_types', 'gif|png|jpg|jpeg'),
				'max_size'         => get_option('max_size', 2048),
				'max_width'        => get_option('max_width', 1024),
				'max_height'       => get_option('max_height', 1024),
				'min_width'        => get_option('min_width', 0),
				'min_height'       => get_option('min_height', 0),

				// Other options.
				'file_ext_tolower' => false,
				'encrypt_name'     => false,
				'remove_spaces'    => false,
			);

			// Apply filters on settings.
			foreach ($this->_config as $key => &$val)
			{
				$val = ('upload_path' === $key)
					? apply_filters('upload_dir', $val)
					: apply_filters('upload_'.$key, $val);
			}
		}

		return $this->_config;
	}

	// -----------------------------------------------------------------------------

	public function upload_image($photo)
	{
		// proses uploads
		if ( ! $this->do_upload($photo))
		{
			return FALSE;
		}

		// data uploads
		$data = $this->data();

		// PATH
		$source      = $this->upload_path.$data['file_name'] ;
		$destination = $this->upload_path;
		$path = str_replace(normalize_path(APPPATH.config_item('upload_path').'/'), '', $this->upload_path);

		// Permission Configuration
		chmod($source, 0777) ;

		// -----------------------------------------------------------------------------
		// Resizing Processing
		// Configuration Of Image Manipulation :: Static
		// -----------------------------------------------------------------------------
		$this->_CI->load->library('image_lib') ;
		$img['image_library']  = 'GD2';
		$img['create_thumb']   = $this->create_thumb;
		$img['maintain_ratio'] = TRUE;

		/// Limit Width Resize
		$limit_medium   = $this->limit_medium;
		$limit_thumb    = $this->limit_thumb;

		// Size Image Limit was using (LIMIT TOP)
		$limit_use  = $data['image_width'] > $data['image_height']
			? $data['image_width']
			: $data['image_height'] ;

		// Percentase Resize
		if ($limit_use > $limit_medium || $limit_use > $limit_thumb) {
			$percent_medium = $limit_medium/$limit_use ;
		}

		if ($this->limit_thumb > 0)
		{
			// -----------------------------------------------------------------------------
			// Making THUMBNAIL
			// -----------------------------------------------------------------------------
			$img['width']  = $limit_thumb ;
			$img['height'] = $limit_thumb ;

			// Configuration Of Image Manipulation :: Dynamic
			$img['thumb_marker'] = $this->create_thumb ? '_thumb-'.floor($img['width']).'x'.floor($img['height']) : '' ;
			$img['quality']      = '99%' ;
			$img['source_image'] = $source ;
			$img['new_image']    = $destination ;

			$thumb = $data['raw_name']. $img['thumb_marker'].$data['file_ext'];
			$result['thumb'] = $path.$thumb;

			// Do Resizing
			$this->_CI->image_lib->initialize($img);
			$this->_CI->image_lib->process();
			$this->_CI->image_lib->clear() ;
		}

		if ($this->limit_medium > 0)
		{
			// -----------------------------------------------------------------------------
			// Making MEDIUM
			// -----------------------------------------------------------------------------
			$img['width']  = $limit_use > $limit_medium ?  $data['image_width'] * $percent_medium : $data['image_width'] ;
			$img['height'] = $limit_use > $limit_medium ?  $data['image_height'] * $percent_medium : $data['image_height'] ;

			// Configuration Of Image Manipulation :: Dynamic
			$img['thumb_marker'] = $this->create_thumb ? '_medium-'.floor($img['width']).'x'.floor($img['height']) : '' ;
			$img['quality']      = '99%' ;
			$img['source_image'] = $source ;
			$img['new_image']    = $destination ;

			$image = $data['raw_name']. $img['thumb_marker'].$data['file_ext'];
			$result['image'] = $path.$image;

			// Do Resizing
			$this->_CI->image_lib->initialize($img);
			$this->_CI->image_lib->process();
			$this->_CI->image_lib->clear();
		}

		if ($this->create_thumb) {
			unlink($source);
		}

		return $result;
	}

	// -----------------------------------------------------------------------------

	public function upload_avatar($photo)
	{
		// proses uploads
		if ( ! $this->do_upload($photo))
		{
			return FALSE;
		}

		// data uploads
		$data = $this->data();

		// PATH
		$source      = $this->upload_path.$data['file_name'] ;
		$destination = $this->upload_path;

		// Permission Configuration
		chmod($source, 0777) ;

		// -----------------------------------------------------------------------------
		// Resizing Processing
		// Configuration Of Image Manipulation :: Static
		// -----------------------------------------------------------------------------
		$this->_CI->load->library('image_lib') ;
		$img['image_library'] = 'GD2';
		$img['maintain_ratio']= TRUE;

		// -----------------------------------------------------------------------------
		// Making THUMBNAIL
		// -----------------------------------------------------------------------------
		$img['width']  = $this->limit_thumb;
		$img['height'] = $this->limit_thumb;

		// Configuration Of Image Manipulation :: Dynamic
		$img['quality']      = '99%' ;
		$img['source_image'] = $source ;
		$img['new_image']    = $destination ;

		// Do Resizing
		$this->_CI->image_lib->initialize($img);
		$this->_CI->image_lib->process();
		$this->_CI->image_lib->clear() ;

		return $data['file_name'];
	}
}