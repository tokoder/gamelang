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
 * CG_Email Class
 *
 * We extend Email library in order to use PHPMailer.
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CG_Email extends CI_Email
{
	/**
	 * Instance of CI object.
	 * @var object
	 */
	protected $ci;

	/**
	 * Holds an instance of PHPMailer class.
	 * @var object
	 */
	protected $phpmailer;

	/**
	 * Default PHPMailer configuration.
	 * @var array
	 */
	protected $default = array(
		'useragent'           => 'CodeIgniter',
		'mailpath'            => '/usr/sbin/sendmail',
		'protocol'            => 'mail',
		'smtp_host'           => '',
		'smtp_user'           => '',
		'smtp_pass'           => '',
		'smtp_port'           => 25,
		'smtp_timeout'        => 5,
		'smtp_keepalive'      => false,
		'smtp_crypto'         => '',
		'wordwrap'            => true,
		'wrapchars'           => 76,
		'mailtype'            => 'text',
		'charset'             => 'UTF-8',
		'multipart'           => 'mixed',
		'alt_message'         => '',
		'validate'            => false,
		'priority'            => 3,
		'newline'             => "\r\n",
		'crlf'                => "\n",
		'dsn'                 => false,
		'send_multipart'      => true,
		'bcc_batch_mode'      => false,
		'bcc_batch_size'      => 200,
		'debug_output'        => '',
		'smtp_debug'          => 0,
		'encoding'            => '8bit',
		'smtp_auto_tls'       => true,
		'smtp_conn_options'   => array(),
		'dkim_domain'         => '',
		'dkim_private'        => '',
		'dkim_private_string' => '',
		'dkim_selector'       => '',
		'dkim_passphrase'     => '',
		'dkim_identity'       => '',
	);

	/**
	 * Current instance configuration.
	 * @var array
	 */
	protected $config = array();

	/**
	 * The “user agent”.
	 * @var string
	 */
	public $useragent = 'codeigniter';

	/**
	 * Array of available protocols.
	 * @var array
	 */
	protected $protocols = array('mail', 'sendmail', 'smtp');

	/**
	 * Array of email types.
	 * @var array
	 */
	protected $mailtypes = array('html', 'text');

	/**
	 * CodeIgniter and PHPMailer encodings.
	 * @var array
	 */
	protected $encodings_ci = array('8bit', '7bit');
	protected $encodings_phpmailer = array('8bit', '7bit', 'binary', 'base64', 'quoted-printable');

	/**
	 * Class constructor
	 * @access 	public
	 * @param 	array 	$config
	 * @return 	void
	 */
	public function __construct(array $config = array())
	{
		$this->ci = get_instance();
		$this->ci->load->helper('email');

		$this->default['debug_output'] = 'html';
		if (strpos(PHP_SAPI, 'cli') !== false OR defined('STDIN'))
		{
			$this->default['debug_output'] = 'echo';
		}

		foreach (array_keys($this->default) as $key)
		{
			if (property_exists($this, $key))
			{
				unset($this->{$key});
			}
		}

		$this->config = $this->default;
		$this->initialize($config);
		$this->_safe_mode = (!is_php('5.4') && ini_get('safe_mode'));

		log_message('info', 'CG_Email Class Initialized (Useragent: '.$this->useragent.')');
	}

	// ------------------------------------------------------------------------

	/**
	 * Triggers the setter functions to do their job.
	 * @access 	protected
	 * @param 	none
	 * @return 	void
	 */
	protected function _refresh_config()
	{
		foreach (array_keys($this->default) as $key)
		{
			$this->{$key} = $this->{$key};
		}
	}

	// ------------------------------------------------------------------------
	// Magic Methods.
	// ------------------------------------------------------------------------

	/**
	 * Class destructor.
	 * @access 	public
	 * @return 	void
	 */
	public function __destruct()
	{
		is_callable('parent::__destruct') && parent::__destruct();
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic class setter.
	 * @access 	public
	 * @param 	string 	$name
	 * @param 	mixed 	$value
	 * @return 	void
	 */
	public function __set($name, $value)
	{
		$method = 'set_'.$name;

		if (is_callable(array($this, $method)))
		{
			$this->$method($value);
		}
		else
		{
			$this->config[$name] = $value;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic class getter.
	 * @access 	public
	 * @param 	string 	$name
	 * @return 	mixed
	 */
	public function __get($name)
	{
		if (array_key_exists($name, $this->config))
		{
			return $this->config[$name];
		}

		throw new OutOfBoundsException("The property {$name} does not exists.");
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic class checker.
	 * @access 	public
	 * @param 	string 	$name
	 * @return 	bool
	 */
	public function __isset($name)
	{
		return isset($this->config[$name]);
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic class unsetter.
	 * @access 	public
	 * @param 	string 	$name
	 * @return 	void
	 */
	public function __unset($name)
	{
		$this->{$name} = null;

		if (array_key_exists($name, $this->config))
		{
			unset($this->config[$name]);
		}
		else
		{
			unset($this->{$name});
		}
	}

	// -----------------------------------------------------------------------------

	/**
	 * Quick method to send emails.
	 * @param 	string 	$to 			the whom send the email.
	 * @param 	string 	$subject 		the email's subject?
	 * @param 	string 	$message 		the email's body.
	 * @param 	string 	$alt_message 	Alternative message;
	 * @param 	string 	$cc 			carbon copy.
	 * @param 	string 	$bcc 			blind carbon copy.
	 */
	public function send_email($to, $subject = '', $message = '', $alt_message = null, $cc = null, $bcc = null)
	{
		// Start by setting up the email config.
		$mail_protocol = $this->ci->config->item('mail_protocol');
		$config['protocol'] = $mail_protocol;

		// Now we set the rest of the config parameters
		// depending on the mail protocol.
		switch ($mail_protocol)
		{
			// Using SMTP?
			case 'smtp':
				$config['smtp_host']   = $this->ci->config->item('smtp_host');
				$config['smtp_user']   = $this->ci->config->item('smtp_user');
				$config['smtp_pass']   = $this->ci->config->item('smtp_pass');
				$config['smtp_port']   = $this->ci->config->item('smtp_port');
				$config['smtp_crypto'] = $this->ci->config->item('smtp_crypto');
				($config['smtp_crypto'] == 'none') && $config['smtp_crypto'] = '';
				break;

			// Using sendmain?
			case 'sendmail':
				// The server path to Sendmail. Default: '/usr/sbin/sendmail'
				$config['mailpath'] = $this->ci->config->item('sendmail_path');
				break;

			// Default (which is mail).
			case 'mail':
			default:
				break;
		}

		// the rest of the config items we don't need to
		// worry about which protocol the site is using...
		$config['useragent'] = 'phpmailer';
		$config['mailtype']  = 'html';

		// Let's now initialize email library.
		$this->initialize($config);

		// The from is obviously from the database.
		$this->from(
			$this->ci->config->item('server_email'),
			$this->ci->config->item('site_name')
		);

		// To whow send this email.
		$this->to($to);

		// A carbon copy is set?
		if ( ! empty($cc))
		{
			$this->cc($cc);
		}

		// A blind carbon copy is set?
		if ( ! empty($bcc))
		{
			$this->bcc($bcc);
		}

		// Prepare the email subject.
		$this->subject($subject);

		// Set the email message and alternative message.
		$this->message($message);

		if ( ! empty($alt_message))
		{
			$this->set_alt_message(nl2br($alt_message));
		}

		// And here we go! Send it.
		if ( ! $this->send())
		{
			log_message('error', 'Emails are not being sent!');
			parent::print_debugger();
		}

		return true;
	}

	// ------------------------------------------------------------------------

	/**
	 * Initialize class preferences.
	 * @access 	public
	 * @param 	array 	$confg
	 * @return 	CG_Email
	 */
	public function initialize(array $config = array())
	{
		foreach ($config as $key => $value)
		{
			$this->{$key} = $value;
		}

		$this->_refresh_config();
		$this->clear(true);

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Clear previously stored data.
	 * @access 	public
	 * @param 	bool 	$clear_attachments
	 * @return 	CG_Email
	 */
	public function clear($clear_attachments = false)
	{
		$clear_attachments = (!empty($clear_attachments));

		// We clear CodeIgniter Email preferences first.
		parent::clear($clear_attachments);

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->clearAllRecipients();
			$this->phpmailer->clearReplyTos();
			$clear_attachments && $this->phpmailer->clearAttachments();
			$this->phpmailer->clearCustomHeaders();
			$this->phpmailer->Subject = '';
			$this->phpmailer->Body = '';
			$this->phpmailer->AltBody = '';
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set email FROM
	 * @access 	public
	 * @param 	string 	$from
	 * @param 	string 	$name
	 * @param 	string 	$return_path
	 * @return 	CG_Email
	 */
	public function from($from, $name = '', $return_path = null)
	{
		$from = (string) $from;
		$name = (string) $name;
		$return_path = (string) $return_path;

		if ($this->useragent == 'phpmailer')
		{
			if (preg_match('/\<(.*)\>/', $from, $match))
			{
				$from = $match['1'];
			}

			if ($this->validate)
			{
				$this->validate_email($this->_str_to_array($from));

				if ($return_path)
				{
					$this->validate_email($this->_str_to_array($return_path));
				}
			}

			$this->phpmailer->setFrom($from, $name, 0);

			if ( ! $return_path)
			{
				$return_path = $from;
			}

			$this->phpmailer->Sender = $return_path;
		}
		else
		{
			parent::from($from, $name, $return_path);
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Reply-to
	 *
	 * @param	string
	 * @param	string
	 * @return	CG_Email
	 */
	public function reply_to($replyto, $name = '')
	{
		$replyto = (string) $replyto;
		$name = (string) $name;

		if ($this->useragent == 'phpmailer')
		{
			if (preg_match('/\<(.*)\>/', $replyto, $match))
			{
				$replyto = $match['1'];
			}

			if ($this->validate)
			{
				$this->validate_email($this->_str_to_array($replyto));
			}

			($name == '') && $name = $replyto;

			$this->phpmailer->addReplyTo($replyto, $name);

			$this->_replyto_flag = true;
		}
		else
		{
			parent::reply_to($replyto, $name);
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Recipients
	 *
	 * @param	string
	 * @return	CG_Email
	 */
	public function to($to)
	{
		if ($this->useragent == 'phpmailer')
		{
			$to = $this->_str_to_array($to);
			$names = $this->_extract_name($to);
			$to = $this->clean_email($to);

			if ($this->validate)
			{
				$this->validate_email($to);
			}

			$i = 0;
			foreach ((object)$to as $address)
			{
				$this->phpmailer->addAddress($address, $names[$i]);
				$i++;
			}
		}
		else
		{
			parent::to($to);
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set CC
	 *
	 * @param	string
	 * @return	CG_Email
	 */
	public function cc($cc)
	{
		if ($this->useragent == 'phpmailer')
		{
			$cc = $this->_str_to_array($cc);
			$names = $this->_extract_name($cc);
			$cc = $this->clean_email($cc);

			if ($this->validate)
			{
				$this->validate_email($cc);
			}

			$i = 0;
			foreach ((object)$cc as $address)
			{
				$this->phpmailer->addCC($address, $names[$i]);
				$i++;
			}
		}
		else
		{
			parent::cc($cc);
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set BCC
	 *
	 * @param	string
	 * @param	string
	 * @return	CG_Email
	 */
	public function bcc($bcc, $limit = '')
	{
		if ($this->useragent == 'phpmailer')
		{
			$bcc = $this->_str_to_array($bcc);
			$names = $this->_extract_name($bcc);
			$bcc = $this->clean_email($bcc);

			if ($this->validate)
			{
				$this->validate_email($bcc);
			}

			$i = 0;
			foreach ((object)$bcc as $address)
			{
				$this->phpmailer->addBCC($address, $names[$i]);
				$i++;
			}
		}
		else
		{
			parent::bcc($bcc, $limit);
		}
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Email Subject
	 *
	 * @param	string
	 * @return	CG_Email
	 */
	public function subject($subject)
	{
		$subject = (string) $subject;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->Subject = str_replace(array(
				'{unwrap}',
				'{/unwrap}'
			), '', $subject);
		}
		else
		{
			parent::subject($subject);
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Body
	 *
	 * @param	string
	 * @return	CG_Email
	 */
	public function message($body)
	{
		$body = (string) $body;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->Body = str_replace(array(
				'{unwrap}',
				'{/unwrap}'
			), '', $body);
		}

		parent::message($body);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Assign file attachments
	 *
	 * @param	string	$file	Can be local path, URL or buffered content
	 * @param	string	$disposition = 'attachment'
	 * @param	string	$newname = NULL
	 * @param	string	$mime = ''
	 * @return	CG_Email
	 */
	public function attach($file, $disposition = '', $newname = null, $mime = '', $embedded_image = false)
	{
		$file = (string) $file;

		$disposition = (string) $disposition;

		($disposition == '') && $disposition = 'attachment';

		$newname = (string) $newname;

		($newname == '') && $newname = null;

		$mime = (string) $mime;

		if ($this->useragent == 'phpmailer')
		{
			if ($mime == '')
			{
				if (strpos($file, '://') === false && !file_exists($file))
				{
					parent::_set_error_message('lang:email_attachment_missing', $file);
					return $this;
				}

				if ( ! $fp = @fopen($file, FOPEN_READ))
				{
					parent::_set_error_message('lang:email_attachment_unreadable', $file);
					return $this;
				}

				$file_content = stream_get_contents($fp);
				$mime = $this->_mime_types(pathinfo($file, PATHINFO_EXTENSION));
				fclose($fp);

				$this->_attachments[] = array(
					'name'        => array($file, $newname),
					'disposition' => $disposition,
					'type'        => $mime
				);

				$newname = $newname === null ? basename($file) : $newname;
				$cid = $this->attachment_cid($file);
			}
			else
			{
				$file_content =& $file;

				$this->_attachments[] = array(
					'name'        => array($newname, $newname),
					'disposition' => $disposition,
					'type'        => $mime
				);

				$cid = $this->attachment_cid($newname);
			}

			if (empty($embedded_image))
			{
				$this->phpmailer->addStringAttachment($file_content, $newname, 'base64', $mime, $disposition);
			}
			else
			{
				$this->phpmailer->addStringEmbeddedImage($file_content, $cid, $newname, 'base64', $mime, $disposition);
			}
		}
		else
		{
			parent::attach($file, $disposition, $newname, $mime);
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set and return attachment Content-ID
	 *
	 * Useful for attached inline pictures
	 *
	 * @param	string	$filename
	 * @return	string
	 */
	public function attachment_cid($filename)
	{
		if ($this->useragent == 'phpmailer')
		{
			for ($i = 0, $c = count($this->_attachments); $i < $c; $i++)
			{
				if ($this->_attachments[$i]['name'][0] === $filename)
				{
					$this->_attachments[$i]['cid'] = uniqid(basename($this->_attachments[$i]['name'][0]).'@');
					return $this->_attachments[$i]['cid'];
				}
			}

		}
		else
		{
			return parent::attachment_cid($filename);
		}

		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the attachment Content-ID.
	 *
	 * @param 	string 	$filename
	 * @return string
	 */

	public function get_attachment_cid($filename)
	{
		for ($i = 0, $c = count($this->_attachments); $i < $c; $i++)
		{
			if ($this->_attachments[$i]['name'][0] === $filename)
			{
				return empty($this->_attachments[$i]['cid']) ? false : $this->_attachments[$i]['cid'];
			}
		}

		return false;
	}

	// ------------------------------------------------------------------------

	/**
	 * Send Email
	 *
	 * @param	bool	$auto_clear = TRUE
	 * @return	bool
	 */
	public function send($auto_clear = true)
	{
		$auto_clear = !empty($auto_clear);

		if ($this->useragent == 'phpmailer')
		{
			if ($this->mailtype == 'html')
			{
				$this->phpmailer->AltBody = str_replace(array('{unwrap}', '{/unwrap}'), '', $this->_get_alt_message());
			}

			try {
				// Send email
				$result = (bool) $this->phpmailer->send();
				parent::_set_error_message('lang:email_sent', $this->_get_protocol());
				$auto_clear && $this->clear();
			}
			catch (Exception $e) {
				echo $e->getMessage();
				parent::_set_error_message($this->phpmailer->ErrorInfo);
				die;
			}
		}
		else
		{
			$result = parent::send($auto_clear);
		}

		return $result;
	}

	// -----------------------------------------------------------------------------
	// Set Method Magic
	// -----------------------------------------------------------------------------

	/**
	 * Add a Header Item
	 *
	 * @param	string
	 * @param	string
	 * @return	CG_Email
	 */
	public function set_header($header, $value)
	{
		$header = (string) $header;
		$value = (string) $value;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->addCustomHeader($header, str_replace(array("\n", "\r"), '', $value));
		}

		parent::set_header($header, $value);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change the user agent.
	 * @access 	public
	 * @param 	string 	$useragent
	 * @return 	CG_Mail
	 */
	public function set_useragent($useragent)
	{
		$useragent = (string) $useragent;
		$this->config['useragent'] = $useragent;
		$this->useragent($useragent);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change the server path to Sendmail.
	 * @access 	public
	 * @param 	string 	$path
	 * @return 	CG_Mail
	 */
	public function set_mailpath($path)
	{
		$path = (string) $path;
		$this->config['mailpath'] = $path;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->Sendmail = $path;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change the mail sending protocol.
	 * @access 	public
	 * @param 	string 	$protocol
	 * @return 	CG_Mail
	 */
	public function set_protocol($protocol = 'mail')
	{
		$protocol = in_array($protocol, $this->protocols, true) ? strtolower($protocol) : 'mail';

		$this->config['protocol'] = $protocol;

		if ($this->useragent == 'phpmailer')
		{
			switch ($protocol)
			{
				case 'mail':
					$this->phpmailer->isMail();
					break;

				case 'sendmail':
					$this->phpmailer->isSendmail();
					break;

				case 'smtp':
					$this->phpmailer->isSMTP();
					break;
			}
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change SMTP Server Address.
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mal
	 */
	public function set_smtp_host($server)
	{
		$server = (string) $server;
		$this->config['smtp_host'] = $server;

		if ($this->useragent == 'phpmailer')
		{
			$server = (strpos($server, "ssl://") !== false)
				? str_replace('ssl://', '', $server)
				: $server;
			$this->phpmailer->Host = $server;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change SMTP Username.
	 * @access	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_smtp_user($user)
	{
		$user = (string) $user;

		$this->config['smtp_user'] = $user;
		$this->_smtp_auth = !($user == '' && $this->smtp_pass == '');

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->Username = $user;
			$this->phpmailer->SMTPAuth = $this->_smtp_auth;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change SMTP Password.
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_smtp_pass($pass)
	{
		$pass = (string) $pass;

		$this->config['smtp_pass'] = $pass;
		$this->_smtp_auth = !($this->smtp_user == '' && $pass == '');

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->Password = $pass;
			$this->phpmailer->SMTPAuth = $this->_smtp_auth;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Changes SMTP Port.
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_smtp_port($port)
	{
		$port = (int) $port;

		$this->config['smtp_port'] = $port;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->Port = $port;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Changes SMTP Timeout (in seconds).
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_smtp_timeout($timeout)
	{
		$timeout = (int) $timeout;

		$this->config['smtp_timeout'] = $timeout;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->Timeout = $timeout;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Enable persistent SMTP connections.
	 * @access 	public
	 * @param 	mixed
	 * @return 	CG_Mail
	 */
	public function set_smtp_keepalive($value)
	{
		$value = ( ! empty($value));
		$this->config['smtp_keepalive'] = $value;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->SMTPKeepAlive = $value;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change SMTP Encryption.
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_smtp_crypto($crypto = '')
	{
		$crypto = trim(strtolower($crypto));

		in_array($crypto, array('tls', 'ssl')) OR $crypto = '';

		$this->config['smtp_crypto'] = $crypto;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->SMTPSecure = $crypto;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change word-wrap.
	 * @access 	public
	 * @param 	bool
	 * @return 	CG_Mail
	 */
	public function set_wordwrap($wordwrap = true)
	{
		$wordwrap = !empty($wordwrap);

		$this->config['wordwrap'] = $wordwrap;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->WordWrap = $wordwrap ? (int) $this->wrapchars : 0;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change Character count to wrap at.
	 * @access 	public
	 * @param 	int
	 * @return 	CG_Mail
	 */
	public function set_wrapchars($wrapchars)
	{

		$wrapchars = (int) $wrapchars;

		$this->config['wrapchars'] = $wrapchars;

		if ($this->useragent == 'phpmailer')
		{
			if ( ! $this->wordwrap)
			{
				$this->phpmailer->WordWrap = 0;
			}
			else
			{
				empty($wrapchars) && $wrapchars = 76;
				$this->phpmailer->WordWrap = (int) $wrapchars;
			}
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change Type of mail.
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_mailtype($type = 'text')
	{
		$type = trim(strtolower($type));
		in_array($type, $this->mailtypes) OR $type = 'text';

		$this->config['mailtype'] = $type;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->isHTML($type == 'html');
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change Character set.
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_charset($charset)
	{
		($charset == '') && $charset = config_item('charset');

		$charset = strtoupper($charset);

		$this->config['charset'] = $charset;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->CharSet = $charset;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Change multipart alternative.
	 * @access 	public
	 * @param 	bool
	 * @return 	CG_Mail
	 */
	public function set_multipart($value)
	{
		$this->config['multipart'] = (string) $value;
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Alternative e-mail message body
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_alt_message($str)
	{
		$this->config['alt_message'] = (string) $str;
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Whether to validate the email address.
	 * @access 	public
	 * @param 	bool
	 * @return 	CG_Mail
	 */
	public function set_validate($value)
	{
		$this->config['validate'] = ( ! empty($value));
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Email Priority.
	 * @access 	public
	 * @param 	int
	 * @return 	CG_Mail
	 */
	public function set_priority($n = 3)
	{
		$n = preg_match('/^[1-5]$/', $n) ? (int) $n : 3;

		$this->config['priority'] = $n;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->Priority = $n;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * St Newline character.
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_newline($newline = "\n")
	{
		$newline = in_array($newline, array("\n", "\r\n", "\r" )) ? $newline : "\n";

		$this->config['newline'] = $newline;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->getLE();
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Newline character.
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_crlf($crlf = "\n")
	{
		$crlf = ($crlf !== "\n" && $crlf !== "\r\n" && $crlf !== "\r") ? "\n" : $crlf;

		$this->config['crlf'] = $crlf;

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Enable notify message from server.
	 * @access 	public
	 * @param 	bool
	 * @return 	CG_Mail
	 */
	public function set_dsn($value)
	{
		$this->config['dsn'] = !empty($value);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set the multipart alternatives.
	 * @access 	public
	 * @param 	bool
	 * @return 	CG_Mail
	 */
	public function set_send_multipart($value)
	{
		$this->config['send_multipart'] = ( ! empty($value));
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set BCC batch mode.
	 * @access 	public
	 * @param 	bool
	 * @return 	CG_Mail
	 */
	public function set_bcc_batch_mode($value)
	{
		$this->config['bcc_batch_mode'] = ( ! empty($value));
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set BCC batch size.
	 * @access 	public
	 * @param 	bool
	 * @return 	CG_Mail
	 */
	public function set_bcc_batch_size($value)
	{
		$this->config['bcc_batch_size'] = (int) $value;
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set smtp_debug
	 * @access 	public
	 * @param 	int
	 * @return 	CG_Mail
	 */
	public function set_smtp_debug($level)
	{
		$level = (int) $level;

		($level < 0) && $level = 0;

		$this->config['smtp_debug'] = $level;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->SMTPDebug = $level;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * St debug_output
	 * @access 	public
	 * @return CG_Mail
	 */
	public function set_debug_output($handle)
	{
		if ($handle === null OR is_string($handle) && $handle == '')
		{
			$handle = $this->default['debug_output'];
		}

		$this->config['debug_output'] = $handle;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->Debugoutput = $handle;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set encoding.
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_encoding($encoding)
	{
		$encoding = (string) $encoding;

		$encodings = ($this->useragent == 'phpmailer') ? $this->encodings_phpmailer : $this->encodings_ci;

		if ( ! in_array($encoding, $encodings))
		{
			$encoding = '8bit';
		}

		$this->config['encoding'] = $encoding;
		$this->_encoding = $encoding;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->Encoding = $encoding;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set SMTP auto TLS
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_smtp_auto_tls($value)
	{
		$value = ( ! empty($value));

		$this->config['smtp_auto_tls'] = $value;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->SMTPAutoTLS = $value;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set smtp_conn_options.
	 * @access 	public
	 * @param 	string
	 * @return 	CG_Mail
	 */
	public function set_smtp_conn_options($value)
	{
		is_array($value) OR $value = array();

		$this->config['smtp_conn_options'] = $value;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->SMTPOptions = $value;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set dkim_domain
	 * @param 	string 	$value
	 * @return 	CG_Mail
	 */
	public function set_dkim_domain($value)
	{
		$value = (string) $value;
		$this->config['dkim_domain'] = $value;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->DKIM_domain = $value;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set dkim_private
	 * @param 	string 	$value
	 * @return 	CG_Mail
	 */
	public function set_dkim_private($value)
	{
		$value = (string) $value;
		$this->config['dkim_private'] = $value;

		$vars = $this->_get_file_name_variables();
		$value_parsed = str_replace(array_keys($vars), array_values($vars), $value);

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->DKIM_private = $value_parsed;
		}

		($value != '') && $this->set_dkim_private_string('');
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set dkim_private_string
	 * @param 	string 	$value
	 * @return 	CG_Mail
	 */
	public function set_dkim_private_string($value)
	{
		$value = (string) $value;
		$this->config['dkim_private_string'] = $value;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->DKIM_private_string = $value;
		}

		($value != '') && $this->set_dkim_private('');
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set dkim_selector
	 * @param 	string 	$value
	 * @return 	CG_Mail
	 */
	public function set_dkim_selector($value)
	{
		$value = (string) $value;
		$this->config['dkim_selector'] = $value;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->DKIM_selector = $value;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set dkim_passphrase
	 * @param 	string 	$value
	 * @return 	CG_Mail
	 */
	public function set_dkim_passphrase($value)
	{
		$value = (string) $value;
		$this->config['dkim_passphrase'] = $value;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->DKIM_passphrase = $value;
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set dkim_identity
	 * @param 	string 	$value
	 * @return 	CG_Mail
	 */
	public function set_dkim_identity($value)
	{
		$value = (string) $value;
		$this->config['dkim_identity'] = $value;

		if ($this->useragent == 'phpmailer')
		{
			$this->phpmailer->DKIM_identity = $value;
		}

		return $this;
	}

	// ------------------------------------------------------------------------
	// Method for setting settings.
	// ------------------------------------------------------------------------

	/**
	 * Change mailer engine.
	 * @access 	public
	 * @param 	string 	$engine
	 * @return 	CG_Mail
	 */
	public function useragent($engine)
	{
		$engine = strpos(strtolower($engine), 'phpmailer') !== false ? 'phpmailer' : 'codeigniter';

		if ($this->useragent == $engine)
		{
			return $this;
		}

		$this->useragent = $engine;

		if ( ! is_object($this->phpmailer))
		{
			// PHPMailer object
			$this->phpmailer = new PHPMailer(true);
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Email Validation
	 *
	 * @param	string
	 * @return	bool
	 */
	public function valid_email($email)
	{
		return valid_email($email);
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the alternative message content.
	 * @access 	protected
	 * @param 	none
	 * @return 	string
	 */
	protected function _get_alt_message()
	{
		$alt_message = (string) $this->alt_message;

		($alt_message == '') && $alt_message = $this->_plain_text($this->_body);

		if ($this->useragent == 'phpmailer')
		{
			return $alt_message;
		}

		return ($this->wordwrap) ? $this->word_wrap($alt_message, 76) : $alt_message;
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns the plain text of the HTML output.
	 * @access 	protected
	 * @param 	string 	$html
	 * @return 	string
	 */
	protected function _plain_text($html)
	{
		if ( is_string($html))
		{
			$raw_html = $html;

			$html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
			$html = preg_match('/\<body.*?\>(.*)\<\/body\>/si', $html, $match) ? $match[1] : $html;
			$html = str_replace("\t", '', preg_replace('#<!--(.*)--\>#', '', trim(strip_tags($html))));

			for ($i = 20; $i >= 3; $i--)
			{
				$html = str_replace(str_repeat("\n", $i), "\n\n", $html);
			}

			$html = preg_replace('| +|', ' ', $html);
		}

		return $html;
	}

	// ------------------------------------------------------------------------

	/**
	 * Extracts the name from the email address.
	 * @access 	protected
	 * @param 	string
	 * @return 	array
	 */
	protected function _extract_name($address)
	{
		if ( ! is_array($address))
		{
			$address = trim($address);

			if (preg_match('/(.*)\<(.*)\>/', $address, $match))
			{
				return trim($match['1']);
			}

			return '';
		}

		$result = array();

		foreach ($address as $addr)
		{
			$addr = trim($addr);

			$result[] = (preg_match('/(.*)\<(.*)\>/', $addr, $match)) ? trim($match['1']) : '';
		}

		return $result;
	}

	// ------------------------------------------------------------------------

	protected function _get_file_name_variables()
	{
		static $result = null;

		if ($result === null)
		{
			$result = array('{APPPATH}' => APPPATH);
		}

		return $result;
	}
}