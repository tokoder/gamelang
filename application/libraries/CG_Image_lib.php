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
 * CG_Image_lib Class
 *
 * Extends CodeIgniter Image_lib library in order to automatically handle
 * resizing/cropping images.
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class CG_Image_lib extends CI_Image_lib
{
	/**
	 * Holds a copy of user specified width before it's
	 * modified by parent class.
	 * @var int
	 */
	private $_width  = 0;

	/**
	 * Holds a copy of user specified height before it's
	 * modified by parent class.
	 * @var int
	 */
	private $_height = 0;

	/**
	 * Holds a copy of user specified x_axis before it's
	 * modified by parent class.
	 * @var int
	 */
	private $_x_axis = '';

	/**
	 * Holds a copy of user specified y_axis before it's
	 * modified by parent class.
	 * @var int
	 */
	private $_y_axis = '';

	/**
	 * initialize
	 *
	 * Simply cache user specified properties then call parent's initialize method.
	 *
	 * @access 	public
	 * @param 	array
	 * @return 	bool
	 */
	public function initialize($props = array())
	{
		// Cache user specified properties before they are modified.
		isset($props['width']) && $this->_width = $props['width'];
		isset($props['height']) && $this->_height = $props['height'];
		isset($props['x_axis']) && $this->_x_axis = $props['x_axis'];
		isset($props['y_axis']) && $this->_y_axis = $props['y_axis'];

		// Call parent's "initialize" method.
		return parent::initialize($props);
	}

	// ------------------------------------------------------------------------

	/**
	 * clear
	 *
	 * Reset this class properties then let the parent handle the rest.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function clear()
	{
		// We reset this class properties.
		$this->_width = 0;
		$this->_height = 0;
		$this->_x_axis = '';
		$this->_y_axis = '';

		// Let the parent do the rest.
		return parent::clear();
	}

	// ------------------------------------------------------------------------

	/**
	 * process
	 *
	 * This is the method handling the hardest task: automatic resizing/cropping.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	bool
	 */
	public function process()
	{
		// Use user specified dimensions.
		$this->width  = $this->_width;
		$this->height = $this->_height;

		/**
		 * Mode 1:
		 * Auto-scale the image to fit one dimension.
		 */
		if ($this->_width == 0 OR $this->_height == 0)
		{
			if ($this->_width == 0)
			{
				$this->width = ceil($this->_height * $this->orig_width / $this->orig_height);
			}
			else
			{
				$this->height = ceil($this->_width * $this->orig_height / $this->orig_width);
			}

			return $this->resize();
		}

		/**
		 * Mode 2:
		 * Resize and crop the image to fit both dimensions.
		 */
		$this->width  = ceil($this->_height * ($this->orig_width / $this->orig_height));
		$this->height = ceil($this->_width * ($this->orig_height / $this->orig_width));

        if ($this->_width != $this->width && $this->_height != $this->height)
        {
            if ($this->master_dim == 'height')
            {
				$this->width = $this->_width;
            }
            else
            {
				$this->height = $this->_height;
            }
        }

        // We save the last dynamic output status for later use.
		$dynamic_output       = $this->dynamic_output;
		$this->dynamic_output = false;

        // We use a temporary file for dynamic output.
        $tempfile = false;

        // Dynamic output set to true?
        if (true === $dynamic_output)
        {
        	// We create the file.
			$temp                = tmpfile();
			$tempfile            = array_search('uri', @array_flip(stream_get_meta_data($temp)));
			$this->full_dst_path = $tempfile;
        }

        // In case of an issue resizing the image.
        if (false === $this->resize())
        {
			return false;
        }

        // Now we calculate cropping axis.
        $this->x_axis = (is_numeric($this->_x_axis))
			? $this->_x_axis
			: floor(($this->width - $this->_width) / 2);

        $this->y_axis = (is_numeric($this->_y_axis))
			? $this->_y_axis
			: floor(($this->height - $this->_height) / 2);

        // We prepare class cropping options.
		$this->orig_width  = $this->width;
		$this->orig_height = $this->height;
		$this->width       = $this->_width;
		$this->height      = $this->_height;

        // We use the previous generated image for output.
        $this->full_src_path = $this->full_dst_path;

        // Put back dynamic output status to where it was.
        $this->dynamic_output = $dynamic_output;

        // Issue cropping the file?
        if (false === $this->crop())
        {
			return false;
        }

        /**
         * Because we are nice enough :) ... We make sure to close and
         * remove the temporary created file.
         */
        if (false !== $tempfile)
        {
            fclose($temp);
        }

        return true;
	}

}