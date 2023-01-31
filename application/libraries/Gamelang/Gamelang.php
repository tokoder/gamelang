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
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gamelang Driver.
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class Gamelang extends CI_Driver_Library
{
	/**
	 * Instance of CI object.
	 * @var object
	 */
	public $ci;

	/**
	 * Array of crud drivers.
	 * @var array
	 */
	public $crud_drivers = array(
		'users',
		'groups',
		'objects',
		'options',
		'entities',
		'metadata',
		'relations',
		'variables',
		'activities',
	);

	/**
	 * Array of class drivers.
	 * @var array
	 */
	public $class_drivers = array(
		'auth',
		'menus',
		'themes',
		'packages',
		'language',
		'validation',
	);

    /**
     * Class constructor
     */
    public function __construct()
    {
		$this->ci =& get_instance();

        // Beberapa driver memerlukan antarmuka CRUD
		interface_exists('Gamelang_crud_interface', FALSE) OR require_once(APPPATH.'libraries/Gamelang/Gamelang_crud_interface.php');

		// Gabungkan semua drivers menjadi variable valid_drivers
		$this->valid_drivers = array_merge( $this->crud_drivers, $this->class_drivers );

		// We initialize options.
		$this->options->initialize();
		$this->ci->options =& $this->options;

		// Initialize library drivers.
		foreach ($this->valid_drivers as $driver)
		{
			if ( ! in_array($driver, ['options', 'auth']))
			{
				if (method_exists($this->{$driver}, 'initialize'))
				{
					$this->{$driver}->initialize();
				}
				$this->ci->{$driver} =& $this->{$driver};
			}
		}

		// Initialize authentication library.
		$this->auth->initialize();
		$this->ci->auth =& $this->auth;

		log_message('info', 'Gamelang Class Initialized');
    }

	// ------------------------------------------------------------------------

	/**
	 * Database LIKE clause generator.
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @param 	string 	$type 		The type of search: users, groups, objects OR null.
	 * @return 	object 	it returns the DB object so that the method can be chainable.
	 */
	public function find($field, $match = null, $limit = 0, $offset = 0, $type = null)
	{
		// We make sure $field is an array.
		(is_array($field)) OR $field = array($field => $match);

		/**
		 * Pencarian dipicu tergantung dari apa yang kita cari.
		 * Ini berguna karena terkadang kita mungkin ingin mengambil entitas
		 * oleh metadata mereka. Jika tidak, kami menghasilkan klausa LIKE default.
		 */
		switch ($type)
		{
			// In case of looking for an entity.
			case 'users':
			case 'groups':
			case 'objects':

				// We make sure to join the required table.
				$this->ci->load->helper('inflector');
				$this->ci->db
					// We select only main tables fields to avoid joining metadata.
					->select("entities.*, {$type}.*")
					->distinct()
					->where('entities.type', singular($type))
					->join($type, "{$type}.guid = entities.id");

				// The following anchoris  used to avoid multiple join.
				$metadata_joint = true;

				// Generate the query.
				$count = 1;
				foreach ($field as $key => $val)
				{
					/**
					 * If we are searching by a field that exists in one of the main
					 * tables: entities, users, groups or objects.
					 */
					if (in_array($key, $this->{$type}->fields())
						OR in_array($key, $this->entities->fields()))
					{
						// Make sure not to search in metadata.
						$metadata_joint = false;

						if ( ! is_array($val))
						{
							$method = ($count == 1) ? 'like' : 'or_like';
							if (strpos($key, '!') === 0)
							{
								$method = ($count == 1) ? 'not_like' : 'or_not_like';
								$key = str_replace('!', '', $key);
							}

							$this->ci->db->{$method}($key, $val);
						}
						else
						{
							foreach ($val as $_val)
							{
								$method = 'like';
								if (strpos($key, '!') === 0)
								{
									$method = 'not_like';
									$key = str_replace('!', '', $key);
								}

								$this->ci->db->{$method}($key, $val);
							}
						}

						$count++;
					}
					// Otherwise, we search by metadata.
					else
					{
						// Join metadata table?
						if ($metadata_joint === true)
						{
							$this->ci->db->join('metadata', 'metadata.guid = entities.id');

							// Stop multiple joins.
							$metadata_joint = false;
						}

						if ( ! is_array($val))
						{
							$method = ($count == 1) ? 'like' : 'or_like';
							if (strpos($key, '!') === 0)
							{
								$method = ($count == 1) ? 'not_like' : 'or_not_like';
								$key = str_replace('!', '', $key);
							}

							$this->ci->db->where('metadata.name', $key);
							$this->ci->db->{$method}('metadata.value', $val);
						}
						else
						{
							foreach ($val as $_val)
							{
								$method = 'like';
								if (strpos($key, '!') === 0)
								{
									$method = 'not_like';
									$key = str_replace('!', '', $key);
								}

								$this->ci->db->where('metadata.name', $key);
								$this->ci->db->{$method}('metadata.value', $val);
							}
						}

						$count++;
					}
				}

				break;	// End of case 'users', 'groups', 'objects'.

			// Generating default LIKE clause.
			default:

				// Let's now generate the query.
				$count = 1;
				foreach ($field as $key => $val)
				{
					if ( ! is_array($val))
					{
						$method = ($count == 1) ? 'like' : 'or_like';
						if (strpos($key, '!') === 0)
						{
							$method = ($count == 1) ? 'not_like' : 'or_not_like';
							$key = str_replace('!', '', $key);
						}

						$this->ci->db->{$method}($key, $val);
					}
					else
					{
						foreach ($val as $_val)
						{
							$method = 'like';
							if (strpos($key, '!') === 0)
							{
								$method = 'not_like';
								$key = str_replace('!', '', $key);
							}

							$this->ci->db->{$method}($key, $val);
						}
					}

					$count++;
				}

				break;	// End of "default".
		}

		// Apakah kami memberikan batasan?
		if ($limit > 0)
		{
			$this->ci->db->limit($limit, $offset);
		}

		// Kembalikan agar metode ini dapat dirantai.
		return $this->ci->db;
	}

	// ------------------------------------------------------------------------

	/**
	 * Database WHERE clause generator.
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	object 	it returns the DB object so that the method can be chainable.
	 */
	public function where($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Make sure $field is an array.
		(is_array($field)) OR $field = array($field => $match);

		// Let's generate the WHERE clause.
		foreach ($field as $key => $val)
		{
			// We make sure to ignore empty key.
			if (empty($key) OR is_int($key))
			{
				continue;
			}

			// The default method to call.
			$method = 'where';

			// In case $val is an array.
			if (is_array($val))
			{
				// The default method to call is "where_in".
				$method = 'where_in';

				// Should we use the "or_where_not_in"?
				if (strpos($key, 'or:!') === 0)
				{
					$method = 'or_where_not_in';
					$key    = str_replace('or:!', '', $key);
				}
				// Should we use the "or_where_in"?
				elseif (strpos($key, 'or:') === 0)
				{
					$method = 'or_where_in';
					$key    = str_replace('or:', '', $key);
				}
				// Should we use the "where_not_in"?
				elseif (strpos($key, '!') === 0)
				{
					$method = 'where_not_in';
					$key    = str_replace('!', '', $key);
				}
			}
			elseif (strpos($key, 'or:') === 0)
			{
				$method = 'or_where';
				$key    = str_replace('or:', '', $key);
			}

			$this->ci->db->{$method}($key, $val);
		}

		if ($limit > 0)
		{
			$this->ci->db->limit($limit, $offset);
		}

		return $this->ci->db;
	}

	// --------------------------------------------------------------------

	/**
	 * Better way of sending email messages.
	 *
	 * @param 	mixed 	$user 		The user's ID or object.
	 * @param 	string 	$subect 	The email subject.
	 * @param 	string 	$message 	The message to be sent.
	 * @param 	array 	$data 		Array of data to pass to views.
	 * @return send_mail()
	 */
	public function send_email($user, $subject, $message, $data = array())
	{
		if (empty($message) OR empty($user))
		{
			set_alert(__('lang_empty_message'), 'error');
			return false;
		}

		$user = ($user instanceof CG_User) ? $user : $this->ci->users->get($user);
		if ( ! $user)
		{
			set_alert(__('lang_account_not_exists'), 'error');
			return false;
		}

		// We add IP Address.
		if ( ! isset($data['ip_link']))
		{
			$ip_address = $this->ci->input->ip_address();
			$data['ip_link'] = html_tag('a', array(
				'href'   => 'https://www.iptolocation.net/trace-'.$ip_address,
				'target' => '_blank',
				'rel'    => 'nofollow',
			), $ip_address);
		}

		$email       = isset($data['email']) ? $data['email'] : $user->email;
		$name        = isset($data['name']) ? $data['name'] : $user->full_name;
		$site_name   = $this->ci->config->item('site_name');
		$site_anchor = anchor('', $site_name, 'target="_blank"');

		/**
		 * There are three options to load messages
		 * 1. Just pass the message.
		 * 2. Use "view:xx" to load a specific view file.
		 * 3. Use "lang:xx" to use a language file.
		 */
		if (1 === sscanf($message, 'view:%s', $view))
		{
			$message = $this->ci->load->view($view, null, true);
		}
		elseif (1 === sscanf($message, 'lang:%s', $line))
		{
			$message = __($line);
		}

		// Prepare default output replacements.
		$search  = array('{name}', '{site_name}', '{site_anchor}');
		$replace = array($name, $site_name, $site_anchor);

		// If we have any other elements, use theme.
		if ( ! empty($data))
		{
			foreach ($data as $key => $val)
			{
				$search[]  = "{{$key}}";
				$replace[] = $val;
			}
		}

		// Message subject.
		$subject = str_replace($search, $replace, $subject);

		// Prepare message body and alternative message.
		$raw_message = str_replace($search, $replace, $message);
		$alt_message = strip_all_tags($raw_message);

		$message = $this->ci->load->view('emails/_header', null, true);
		$message .= nl2br($raw_message);
		$message .= $this->ci->load->view('emails/_footer', null, true);

		// Start by setting up the email config.
		$config['useragent']    = $this->ci->config->item('mail_library');
		$config['protocol']     = $this->ci->config->item('mail_protocol');
		$config['mailpath']     = $this->ci->config->item('sendmail_path');
		$config['smtp_host']    = $this->ci->config->item('smtp_host');
		$config['smtp_port']    = $this->ci->config->item('smtp_port');
		$config['smtp_user']    = $this->ci->config->item('smtp_user');
		$config['smtp_pass']    = $this->ci->config->item('smtp_pass');
		$config['smtp_crypto']  = $this->ci->config->item('smtp_crypto') == 'none' ? '' : $this->ci->config->item('smtp_crypto');
		$config['smtp_timeout'] = 30;
		$config['smtp_auth']    = true;
		$config['validate']     = true;
		$config['mailtype']     = 'html';

		// Let's now initialize email library.
		(class_exists('CI_Email', false)) OR $this->ci->load->library('email', $config);

		// alternative message?
		if ( ! empty($alt_message)) {
			$this->ci->email->set_alt_message(nl2br($alt_message));
		}

		// And here we go! Send it.
		$result = $this->ci->email
			->from($this->ci->config->item('mail_address'), $site_name)
			->to($email)
			->subject($subject)
			->message($message)
			->send(false);

		if ( ! $result)
		{
			set_alert($this->ci->email->print_debugger([]), 'error');
			return false;
		}

		return true;
	}
}