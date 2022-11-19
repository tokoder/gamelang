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
 * Gamelang_activities Class
 *
 * @category 	Libraries
 * @author		Tokoder Team
 */
class Gamelang_activities extends CI_Driver implements Gamelang_crud_interface
{
	/**
	 * Initialize class preferences.
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function initialize()
	{
		log_message('info', 'Gamelang_activities Class Initialized');
	}

    // ------------------------------------------------------------------------

    /**
     * Generates the SELECT portion of the query
     */
    public function select($select = '*', $escape = null)
    {
		$this->ci->db->select($select, $escape);
		return $this;
    }

	// ------------------------------------------------------------------------

	/**
	 * Return an array of activities table fields.
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	array
	 */
	public function fields()
	{
		if (isset($this->fields))
		{
			return $this->fields;
		}

		$this->fields = $this->ci->db->list_fields('activities');
		return $this->fields;
	}

	// ------------------------------------------------------------------------
	// CRUD Interface.
	// ------------------------------------------------------------------------

	/**
	 * Create a new options.
	 * @access 	public
	 * @param 	array 	$data 	Array of data to insert.
	 * @return 	the new row ID if found, else false.
	 */
	public function create(array $data = array())
    {
		// Without $data, nothing to do.
		if (empty($data))
		{
			return false;
		}

		// Multiple activities?
		if (isset($data[0]) && is_array($data[0]))
		{
			$ids = array();
			foreach ($data as $_data)
			{
				$ids[] = $this->create($_data);
			}

			return $ids;
		}

		// Let's complete some data.
		if ( ! isset($data['package']))
		{
			$data['package'] = (method_exists($this->ci->router, 'fetch_package'))
				? $this->ci->router->fetch_package()
				: null;
		}
		(isset($data['controller'])) OR $data['controller'] = $this->ci->router->fetch_class();
		(isset($data['method']))     OR $data['method']     = $this->ci->router->fetch_method();
		(isset($data['created_at'])) OR $data['created_at'] = time();
		(isset($data['ip_address'])) OR $data['ip_address'] = $this->ci->input->ip_address();

		// Proceed to creation and return the ID.
		$this->ci->db->insert('activities', $data);
		return $this->ci->db->insert_id();
    }

	// ------------------------------------------------------------------------

	/**
	 * Retrieve a single row by primary key.
	 * @access 	public
	 * @param 	mixed 	$id 	The primary key value.
	 * @return 	object if found, else null
	 */
	public function get($id)
    {
		// Getting by id?
		if (is_numeric($id))
		{
			return $this->get_by('id', $id);
		}

		// Otherwise, let "get_by" method handle the rest.
		return $this->get_by($id);
    }

	// ------------------------------------------------------------------------

	/**
	 * Retrieve a single row by arbitrary WHERE clause.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @return 	object if found, else null.
	 */
	public function get_by($field, $match = null)
    {
		// We start with an emoty $activity.
		$activity = false;

		// Attempt to get the entity from database.
		$db_activity = $this->_parent
			->where($field, $match, 1, 0)
			->order_by('id', 'DESC')
			->get('activities')
			->row();

		// If found, we create its object.
		if ($db_activity)
		{
			$activity = new CG_Activity($db_activity);
		}

		// Return the final result.
		return $activity;
    }

	/**
	 * Retrieve multiple rows by arbitrary WHERE clause.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @param 	int 	$limit 	Limit to use for getting records.
	 * @param 	int 	$offset Database offset.
	 * @return 	array o objects if found, else null.
	 */
	public function get_many($field = null, $match = null, $limit = 0, $offset = 0)
    {
		// We start with an empty $activities.
		$activities = false;

		// Attempt to get activities from database.
		$db_activities = $this->_parent
			->where($field, $match, $limit, $offset)
			->order_by('id', 'DESC')
			->get('activities')
			->result();

		// If we found any, create their objects.
		if ($db_activities)
		{
			foreach ($db_activities as $db_activity)
			{
				$activities[] = new CG_Activity($db_activity);
			}
		}

		// Return the final result
		return $activities;

    }

	// ------------------------------------------------------------------------

	/**
	 * Retrieve all rows.
	 * @access 	public
	 * @param 	int 	$limit 	Limit to use for getting records.
	 * @param 	int 	$offset Database offset.
	 * @return 	array o objects if found, else null.
	 */
	public function get_all($limit = 0, $offset = 0)
	{
		return $this->get_many(null, null, $limit, $offset);
	}

	// ------------------------------------------------------------------------

	/**
	 * This method is used in order to search activities table.
	 *
	 * @access 	public
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	mixed 	array of objects if found any, else false.
	 */
	public function find($field, $match = null, $limit = 0, $offset = 0)
	{
		// We start with empty activities
		$activities = false;

		// Attempt to find activities.
		$db_activities = $this->_parent
			->find($field, $match, $limit, $offset)
			->order_by('id', 'DESC')
			->get('activities')
			->result();

		// If we found any, we create their objects.
		if ($db_activities)
		{
			foreach ($db_activities as $db_activity)
			{
				$activities[] = new CG_Activity($db_activity);
			}
		}

		// Return the final result.
		return $activities;
	}

	// ------------------------------------------------------------------------

	/**
	 * Update a single row by its primary key.
	 * @access 	public
	 * @param 	mixed 	$id 	The primary key value.
	 * @param 	array 	$data 	Array of data to update.
	 * @return 	boolean
	 */
	public function update($id, array $data = array())
	{
		// Updating by ID?
		if (is_numeric($id))
		{
			return $this->update_by(array('id' => $id), $data);
		}

		// Otherwise, let "update_by" handle the rest.
		return $this->update_by($id, $data);
	}

	// ------------------------------------------------------------------------

	/**
	 * Update all or multiple rows by arbitrary WHERE clause.
	 * @access 	public
	 * @return 	boolean
	 */
	public function update_by()
    {
		// Collect arguments first and make sure there are some.
		$args = func_get_args();
		if (empty($args))
		{
			return false;
		}

		// Data to set is always the last argument.
		$data = array_pop($args);
		if ( ! is_array($data) OR empty($data))
		{
			return false;
		}

		// Start updating/
		$this->ci->db->update($data);

		// If there are arguments left, use the as WHERE clause.
		if ( ! empty($args))
		{
			// Get rid of nasty deep array.
			(is_array($args[0])) && $args = $args[0];

			// Let the parent generate the WHERE clause.
			$this->_parent->where($args);
		}

		// Proceed to update.
		$this->ci->db->update('activities');
		return ($this->ci->db->affected_rows() > 0);
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete a single row by its primary key.
	 * @access 	public
	 * @param 	mixed 	$id 	The primary key value.
	 * @return 	boolean
	 */
	public function delete($id)
    {
		// Deleting by ID?
		if (is_numeric($id))
		{
			return $this->delete_by('id', $id, 1, 0);
		}

		// Otherwise, let "delete_by" handle the rest.
		return $this->delete_by($id, null, 1, 0);
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete multiple or all rows by arbitrary WHER clause.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value.
	 * @return 	boolean
	 */
	public function delete_by($field = null, $match = null, $limit = 0, $offset = 0)
    {
		// Let's delete.
		$this->_parent
			->where($field, $match, $limit, $offset)
			->delete('activities');

		// See if there are affected rows.
		return ($this->ci->db->affected_rows() > 0);
	}

	// --------------------------------------------------------------------

	/**
	 * Quick access to log activity.
	 * @access 	public
	 * @param 	int 	$user_id
	 * @param 	string 	$activity
	 * @param 	string 	$controller 	the controller details
	 * @return 	int 	the activity id.
	 */
	public function log_activity($user_id, $activity)
	{
		// Both user's ID and activity are required.
		if (empty($user_id) OR empty($activity))
		{
			return false;
		}

		return $this->create(array(
			'user_id'  => $user_id,
			'activity' => $activity,
		));
	}

	// ------------------------------------------------------------------------

	/**
	 * Count activities by arbitrary WHERE clause.
	 *
	 * @access 	public
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	int
	 */
	public function count($field = null, $match = null, $limit = 0, $offset = 0)
	{
		// Let's build the query first.
		$query = $this->_parent
			->where($field, $match, $limit, $offset)
			->get('activities');

		// We return the count.
		return $query->num_rows();
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete all activities of which the entity no longer exist.
	 *
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	public function purge($limit = 0, $offset = 0)
	{
		// Get only users IDS.
		$ids = $this->_parent->entities->get_ids('type', 'user');

		// Let's delete.
		$this->_parent
			->where('!user_id', $ids, $limit, $offset)
			->delete('activities');

		return ($this->ci->db->affected_rows() > 0);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('log_activity'))
{
	/**
	 * Log user's activity.
	 * @param 	int 	$user_id
	 * @param 	string 	$activity
	 * @return 	int 	the activity id.
	 */
	function log_activity($user_id, $activity)
	{
		return get_instance()->activities->log_activity($user_id, $activity);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_activity'))
{
	/**
	 * Retrieve a single activity by its ID or arbitrary WHERE clause.
	 * @access 	public
	 * @param 	mixed 	$field 	ID, column name or associative array.
	 * @param 	mixed 	$match 	Comparison value, array or null.
	 * @return 	object if found, else null.
	 */
	function get_activity($field, $match = null)
	{
		// In case of using the ID.
		if (is_numeric($field))
		{
			return get_instance()->activities->get($field);
		}

		return get_instance()->activities->get_by($field, $match);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('get_activities'))
{
	/**
	 * Retrieve multiple activities by arbitrary WHERE clause or
	 * retrieve all activities if no arguments passed.
	 * @access 	public
	 * @param 	mixed 	$field 	Column name or associative array.
	 * @param 	mixed 	$match 	Comparison value, array or null.
	 * @return 	array of objects if found, else null.
	 */
	function get_activities($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->activities->get_many($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('find_activities'))
{
	/**
	 * This function is used in order to search activities.
	 *
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	array of activities if found, else null.
	 */
	function find_activities($field, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->activities->find($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('update_activity'))
{
	/**
	 * Update a single activity by its ID.
	 * @param 	int 	$id 	The activity's ID.
	 * @param 	array 	$data 	Array of data to update.
	 * @return 	bool
	 */
	function update_activity($id, array $data = array())
	{
		return get_instance()->activities->update($id, $data);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('update_activity_by'))
{
	/**
	 * Update a single activity, multiple activities by arbitrary WHERE
	 * clause, or even update all activities.
	 * @return 	bool
	 */
	function update_activity_by()
	{
		return call_user_func_array(
			array(get_instance()->activities, 'update_by'),
			func_get_args()
		);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('update_activities'))
{
	/**
	 * Update a single activity, multiple activities by arbitrary WHERE
	 * clause, or even update all activities.
	 * @return 	bool
	 */
	function update_activities()
	{
		return call_user_func_array(
			array(get_instance()->activities, 'update_by'),
			func_get_args()
		);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('delete_activity'))
{
	/**
	 * Delete a single activity by its ID.
	 * @access 	public
	 * @param 	int 	$id 	The activity's ID.
	 * @return 	bool
	 */
	function delete_activity($id)
	{
		return get_instance()->activities->delete($id);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('delete_activity_by'))
{
	/**
	 * Delete a single activity, multiple activities by arbitrary WHERE
	 * clause or even delete all activities.
	 *
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	function delete_activity_by($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->activities->delete_by($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('delete_activities'))
{
	/**
	 * Delete a single activity, multiple activities by arbitrary WHERE
	 * clause or even delete all activities.
	 *
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	function delete_activities($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->activities->delete_by($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('count_activities'))
{
	/**
	 * Count activities by arbitrary WHERE clause.
	 *
	 * @param 	mixed 	$field
	 * @param 	mixed 	$match
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	int
	 */
	function count_activities($field = null, $match = null, $limit = 0, $offset = 0)
	{
		return get_instance()->activities->count($field, $match, $limit, $offset);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('purge_activities'))
{
	/**
	 * Delete all activities of which the entity no longer exist.
	 *
	 * @param 	int 	$limit
	 * @param 	int 	$offset
	 * @return 	bool
	 */
	function purge_activities($limit = 0, $offset = 0)
	{
		return get_instance()->activities->purge($limit, $offset);
	}
}

// ------------------------------------------------------------------------

class CG_Activity
{
	/**
	 * Activity data container.
	 * @var 	object
	 */
	public $data;

	/**
	 * The activity's ID.
	 * @var 	integer
	 */
	public $id = 0;

	/**
	 * Array of data awaiting to be updated.
	 * @var 	array
	 */
	protected $queue = array();

	/**
	 * Constructor.
	 *
	 * Retrieves the activity data and passes it to CG_Activity::init().
	 *
	 * @access 	public
	 * @param 	mixed	 $id 	Activity's ID, activityname, object or WHERE clause.
	 * @return 	void
	 */
	public function __construct($id = 0) {
		// In case we passed an instance of this object.
		if ($id instanceof CG_Activity) {
			$this->init($id->data);
			return;
		}

		// In case we passed the entity's object.
		elseif (is_object($id)) {
			$this->init($id);
			return;
		}

		if ($id) {
			$activity = get_activity($id);
			if ($activity) {
				$this->init($activity->data);
			} else {
				$this->data = new stdClass();
			}
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Sets up object properties.
	 * @access 	public
	 * @param 	object
	 */
	public function init($activity) {
		$this->data = $activity;
		$this->id   = (int) $activity->id;

		// We add user details to the $data object.
		$this->data->user = get_user($activity->user_id);
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic method for checking the existence of a property.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 * @return 	bool 	true if the property exists, else false.
	 */
	public function __isset($key) {
		// Just make it possible to use ID.
		if ('ID' == $key) {
			$key = 'id';
		}

		// Return true only if found in $data or this object.
		return (isset($this->data->{$key}) OR isset($this->{$key}));
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic method for getting a property value.
	 * @access 	public
	 * @param 	string 	$key 	The property key to retrieve.
	 * @return 	mixed 	Depends on the property value.
	 */
	public function __get($key) {
		// We start with an empty value.
		$value = false;

		// Is if found in $data object?
		if (isset($this->data->{$key})) {
			$value = $this->data->{$key};
		}

		// Then we return the final result.
		return $value;
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic method for setting a property value.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 * @param 	mixed 	$value 	The property value.
	 */
	public function __set($key, $value) {
		// Just make it possible to use ID.
		if ('ID' == $key) {
			$key = 'id';
		}

		// If found, we make sure to set it.
		$this->data->{$key} = $value;

		// We enqueue it for later use.
		$this->queue[$key]  = $value;
	}

	// ------------------------------------------------------------------------

	/**
	 * Magic method for unsetting a property.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 */
	public function __unset($key) {
		// Remove it from $data object.
		if (isset($this->data->{$key})) {
			unset($this->data->{$key});
		}

		// We remove it if queued.
		if (isset($this->queue[$key])) {
			unset($this->queue[$key]);
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for checking the existence of an activity in database.
	 * @access 	public
	 * @param 	none
	 * @return 	bool 	true if the activity exists, else false.
	 */
	public function exists() {
		return ( ! empty($this->id));
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for checking the existence of a property.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 * @return 	bool 	true if the property exists, else false.
	 */
	public function has($key) {
		return $this->__isset($key);
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns an array representation of this object data.
	 *
	 * @access 	public
	 * @return 	array
	 */
	public function to_array() {
		return get_object_vars($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for setting a property value.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 * @param 	string 	$value 	The property value.
	 * @return 	object 	we return the object to make it chainable.
	 */
	public function set($key, $value) {
		$this->__set($key, $value);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for getting a property value.
	 * @access 	public
	 * @param 	string 	$key 	The property key.
	 * @return 	mixed 	Depends on the property's value.
	 */
	public function get($key) {
		return $this->__get($key);
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for updating the activity in database.
	 *
	 * @access 	public
	 * @param 	string 	$key 	The field name.
	 * @param 	mixed 	$value 	The field value.
	 * @return 	bool 	true if updated, else false.
	 */
	public function update($key, $value = null) {
		// We make sure things are an array.
		$data = (is_array($key)) ? $key : array($key => $value);

		// Keep the status in order to dequeue the key.
		$status = update_activity($this->id, $data);

		if ($status === true) {
			foreach ($data as $k => $v) {
				if (isset($this->queue[$k])) {
					unset($this->queue[$k]);
				}
			}
		}

		return $status;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for saving anything changes.
	 * @access 	public
	 * @param 	void
	 * @return 	bool 	true if updated, else false.
	 */
	public function save() {
		// We start if FALSE status.
		$status = false;

		// If there are enqueued changes, apply them.
		if ( ! empty($this->queue)) {
			$status = update_activity($this->id, $this->queue);

			// If the update was successful, we reset $queue array.
			if ($status === true) {
				$this->queue = array();
			}
		}

		// We return the final status.
		return $status;
	}

	// ------------------------------------------------------------------------

	/**
	 * Method for retrieving the array of data waiting to be saved.
	 * @access 	public
	 * @return 	array
	 */
	public function dirty() {
		return $this->queue;
	}

}