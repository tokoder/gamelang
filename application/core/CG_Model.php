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
 * CG_Model Class
 *
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * Everyone is permitted to copy and distribute verbatim or modified copies of this license document,
 * and changing it is allowed as long as the name is changed.
 *
 * @category	Libraries
 * @author		Tokoder Team, who hacked at it a bit.
 * @author		avenirer <avenir.ro@gmail.com>
 * @copyright   Copyright (c) 2014 @avenirer [avenir.ro@gmail.com]
 * @link		https://github.com/avenirer/CodeIgniter-MY_Model
 */
class CG_Model extends CI_Model
{
    /**
     * Select the database connection from the group names defined inside the database.php configuration file or an
     * array.
     */
    protected $_database_connection = NULL;

	/**
	 * This model's default database table. Automatically
	 * guessed by pluralising the model name.
	 */
	protected $_table;

	/**
	 * The database connection object. Will be set to the default
	 * connection. This allows individual models to use different DBs
	 * without overwriting CI's global $this->db connection.
	 */
    protected $_database;

	/**
	 * This model's default primary key or unique identifier.
	 * Used by the get(), update() and delete() functions.
	 */
    protected $primary_key = 'id';

	/**
	 * Support for soft deletes and this model's 'deleted' key
	 */
    protected $soft_delete = TRUE;
	protected $soft_delete_key = 'deleted';
	protected $_temporary_with_deleted = false;
	protected $_temporary_only_deleted = false;

    /**
     * The various callbacks available to the model. Each are
     * simple lists of method names (methods will be run on $this).
     */
    protected $before_create = array();
    protected $after_create = array();
    protected $before_update = array();
    protected $after_update = array();
    protected $before_get = array();
    protected $after_get = array();
    protected $before_delete = array();
    protected $after_delete = array();
    protected $before_count = array();
    protected $after_count = array();

    protected $callback_parameters = array();

	/**
	 * Protected, non-modifiable attributes
	 */
	protected $protected_attributes = array();

	/**
	 * Relationship arrays. Use flat strings for defaults or string
	 * => array to customise the class name and primary key
	 */
	protected $belongs_to = array();
	protected $has_many = array();

	protected $_with = array();

	/**
	 * An array of validation rules. This needs to be the same format
	 * as validation rules passed to the Form_validation library.
	 */
	protected $validate = array();

	/**
	 * Optionally skip the validation. Used in conjunction with
	 * skip_validation() to skip data validation for any future calls.
	 */
	protected $skip_validation = false;

	/**
	 * By default we return our results as objects. If we need to override
	 * this, we can, or, we could use the `as_array()` and `as_object()` scopes.
	 */
	protected $return_type = 'object';
	protected $_temporary_return_type = null;

	/**
	 * Whether to use unix_timestamp or datatime.
	 * Set to 'timestamp' or 'Y-m-d H:i:s'
	 */
	protected $datetime_format = 'Y-m-d H:i:s';

	// -----------------------------------------------------------------------------
	// GENERIC METHODS
	// -----------------------------------------------------------------------------

	/**
	 * Initialise the model, tie into the CodeIgniter superobject and
	 * try our best to guess the table name.
	 */
    public function __construct()
    {
        parent::__construct();

        $this->_set_connection();
        $this->_fetch_table();

		array_unshift($this->before_create, 'protect_attributes');
		array_unshift($this->before_update, 'protect_attributes');

		if ($this->soft_delete === true)
		{
			array_unshift($this->before_delete, 'deleted_at');
		}

		// array_unshift($this->after_get, 'prepare_numeric', 'cached_column');

		$this->_temporary_return_type = $this->return_type;
    }

	// -----------------------------------------------------------------------------
	// CRUD INTERFACE
	// -----------------------------------------------------------------------------

	/**
	 * Fetch a single record based on the primary key. Returns an object.
	 */
	public function get($primary_value = null)
	{
		return $this->get_by($this->primary_key, $primary_value);
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch a single record based on an arbitrary WHERE call. Can be
	 * any valid value to $this->_database->where().
	 */
	public function get_by()
	{
		$where = func_get_args();

		$this->trigger('before_get');

		if ($this->soft_delete && $this->_temporary_with_deleted !== true)
		{
			$this->_database->where($this->soft_delete_key, (bool)$this->_temporary_only_deleted);
		}

		$this->_set_where($where);

		$row = $this->_database->get($this->_table)
						->{$this->_return_type()}();
		$this->_temporary_return_type = $this->return_type;

		$row = $this->trigger('after_get', $row);

		$this->_with = array();

		return $row;
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch an array of records based on an array of primary values.
	 */
	public function get_many()
	{
		$ids = func_get_args();
		(isset($ids[0]) && is_array($ids[0])) && $ids = $ids[0];
		$this->_database->where_in($this->primary_key, $ids);

		return $this->get_all();
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch an array of records based on an arbitrary WHERE call.
	 */
	public function get_many_by()
	{
		$where = func_get_args();

		$this->_set_where($where);

		return $this->get_all();
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch all the records in the table. Can be used as a generic call
	 * to $this->_database->get() with scoped methods.
	 */
	public function get_all()
	{
		$this->trigger('before_get');

		if ($this->soft_delete && $this->_temporary_with_deleted !== true)
		{
			$this->_database->where($this->soft_delete_key, (bool)$this->_temporary_only_deleted);
		}

		$result = $this->_database->get($this->_table)
                        ->{$this->_return_type(1)}();
		$this->_temporary_return_type = $this->return_type;

		foreach ($result as $key => &$row)
		{
			$row = $this->trigger('after_get', $row, ($key == count($result) - 1));
		}

		$this->_with = array();

		return $result;
	}

	// ------------------------------------------------------------------------

	/**
	 * Insert a new row into the table. $data should be an associative array
	 * of data to be inserted. Returns newly created ID.
	 */
	public function insert($data, $skip_validation = false)
	{
		if ($skip_validation === false)
		{
			$data = $this->validate($data);
		}

		if ($data !== false)
		{
			$data = $this->trigger('before_create', $data);

			$this->_database->insert($this->_table, $data);

			$insert_id = $this->_database->insert_id();

			$this->trigger('after_create', $insert_id);

			return $insert_id;
		}
		else
		{
			return false;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Insert multiple rows into the table. Returns an array of multiple IDs.
	 */
	public function insert_many($data, $skip_validation = false)
	{
		$ids = array();

		foreach ($data as $key => $row)
		{
			$ids[] = $this->insert($row, $skip_validation, ($key == count($data) - 1));
		}

		return $ids;
	}

	// ------------------------------------------------------------------------

	/**
	 * Updated a record based on the primary value.
	 */
	public function update($primary_value, $data, $skip_validation = false)
	{
		$data = $this->trigger('before_update', $data);

		if ($skip_validation === false)
		{
			$data = $this->validate($data);
		}

		if ($data !== false)
		{
			$result = $this->_database->where($this->primary_key, $primary_value)
                            ->set($data)
                            ->update($this->_table);

			$this->trigger('after_update', array($data, $result));

			return $result;
		}
		else
		{
			return false;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Update many records, based on an array of primary values.
	 */
	public function update_many($primary_values, $data, $skip_validation = false)
	{
		$data = $this->trigger('before_update', $data);

		if ($skip_validation === false)
		{
			$data = $this->validate($data);
		}

		if ($data !== false)
		{
			$result = $this->_database->where_in($this->primary_key, $primary_values)
                            ->set($data)
                            ->update($this->_table);

			$this->trigger('after_update', array($data, $result));

			return $result;
		}
		else
		{
			return false;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Updated a record based on an arbitrary WHERE clause.
	 */
	public function update_by()
	{
		$args = func_get_args();
		$data = array_pop($args);

		$data = $this->trigger('before_update', $data);

		if ($this->validate($data) !== false)
		{
			$this->_set_where($args);

			$result = $this->_database->set($data)
                            ->update($this->_table);
			$this->trigger('after_update', array($data, $result));

			return $result;
		}
		else
		{
			return false;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Update all records
	 */
	public function update_all($data)
	{
		$data = $this->trigger('before_update', $data);
		$result = $this->_database->set($data)
                        ->update($this->_table);
		$this->trigger('after_update', array($data, $result));

		return $result;
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete a row from the table by the primary value
	 */
	public function delete($id = 0)
	{
		$this->trigger('before_delete', $id);

		if (is_numeric($id) && $id > 0)
		{
			$this->_database->where($this->primary_key, $id);

			if ($this->soft_delete)
			{
				$result = $this->_database->update($this->_table, array( $this->soft_delete_key => true ));
			}
			else
			{
				$result = $this->_database->delete($this->_table);
			}
		}
		else
		{
			$result = $this->_database->delete($this->_table);
		}

		$this->trigger('after_delete', $result);

		return $result;
	}

	public function remove($id)
	{
		$this->soft_delete = false;
		return $this->delete($id);
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete a row from the database table by an arbitrary WHERE clause
	 */
	public function delete_by()
	{
		$where = func_get_args();

		$where = $this->trigger('before_delete', $where);

		$this->_set_where($where);

		if ($this->soft_delete)
		{
			$result = $this->_database->update($this->_table, array( $this->soft_delete_key => true ));
		}
		else
		{
			$result = $this->_database->delete($this->_table);
		}

		$this->trigger('after_delete', $result);

		return $result;
	}

	public function remove_by()
	{
		$this->soft_delete = false;
		return call_user_func_array(array($this, 'delete_by'), func_get_args());
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete many rows from the database table by multiple primary values
	 */
	public function delete_many($primary_values)
	{
		$primary_values = $this->trigger('before_delete', $primary_values);

		$this->_database->where_in($this->primary_key, $primary_values);

		if ($this->soft_delete)
		{
			$result = $this->_database->update($this->_table, array( $this->soft_delete_key => true ));
		}
		else
		{
			$result = $this->_database->delete($this->_table);
		}

		$this->trigger('after_delete', $result);

		return $result;
	}

	public function remove_many($primary_values)
	{
		$this->soft_delete = false;
		return $this->delete_many($primary_values);
	}

	// ------------------------------------------------------------------------

	/**
	 * Truncates the table
	 */
	public function truncate()
	{
		$result = $this->_database->truncate($this->_table);

		return $result;
	}

	// -----------------------------------------------------------------------------
	// RELATIONSHIPS
	// -----------------------------------------------------------------------------

	public function with($relationship)
	{
		$this->_with[] = $relationship;

		if (!in_array('relate', $this->after_get))
		{
			$this->after_get[] = 'relate';
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	public function relate($row)
	{
		if (empty($row))
		{
			return $row;
		}

		foreach ($this->belongs_to as $key => $value)
		{
			if (is_string($value))
			{
				$relationship = $value;
				$options = array( 'primary_key' => $value . '_id', 'model' => $value . '_model' );
			}
			else
			{
				$relationship = $key;
				$options = $value;
			}

			if (in_array($relationship, $this->_with))
			{
				$this->load->model($options['model'], $relationship . '_model');

				if (is_object($row))
				{
					$row->{$relationship} = $this->{$relationship . '_model'}->get($row->{$options['primary_key']});
				}
				else
				{
					$row[$relationship] = $this->{$relationship . '_model'}->get($row[$options['primary_key']]);
				}
			}
		}

		foreach ($this->has_many as $key => $value)
		{
			if (is_string($value))
			{
				$relationship = $value;
				$options = array( 'primary_key' => singular($this->_table) . '_id', 'model' => singular($value) . '_model' );
			}
			else
			{
				$relationship = $key;
				$options = $value;
			}

			if (in_array($relationship, $this->_with))
			{
				$this->load->model($options['model'], $relationship . '_model');

				if (is_object($row))
				{
					$row->{$relationship} = $this->{$relationship . '_model'}->get_many_by($options['primary_key'], $row->{$this->primary_key});
				}
				else
				{
					$row[$relationship] = $this->{$relationship . '_model'}->get_many_by($options['primary_key'], $row[$this->primary_key]);
				}
			}
		}

		return $row;
	}

	// -----------------------------------------------------------------------------
	// UTILITY METHODS
	// -----------------------------------------------------------------------------

	/**
	 * Retrieve and generate a form_dropdown friendly array
	 */
	function dropdown()
	{
		$args = func_get_args();

		if(count($args) == 2)
		{
			list($key, $value) = $args;
		}
		else
		{
			$key = $this->primary_key;
			$value = $args[0];
		}

		$this->trigger('before_dropdown', array( $key, $value ));

		if ($this->soft_delete && $this->_temporary_with_deleted !== true)
		{
			$this->_database->where($this->soft_delete_key, false);
		}

		$result = $this->_database->select(array($key, $value))
                        ->get($this->_table)
                        ->result();

		$options = array();

		foreach ($result as $row)
		{
			$options[$row->{$key}] = $row->{$value};
		}

		$options = $this->trigger('after_dropdown', $options);

		return $options;
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch a count of rows based on an arbitrary WHERE call.
	 */
	public function count_by()
	{
		$where = func_get_args();

		if (count($where)) {
			$this->_set_where($where);
		}

		return $this->count_all();
	}

	// ------------------------------------------------------------------------

	/**
	 * Fetch a total count of rows, disregarding any previous conditions
	 */
	public function count_all()
	{
		$this->trigger('before_count');

		if ($this->soft_delete && $this->_temporary_with_deleted !== true)
		{
			$this->_database->where($this->soft_delete_key, (bool)$this->_temporary_only_deleted);
		}

		return $this->_database->count_all_results($this->_table);
	}

	// ------------------------------------------------------------------------

	/**
	 * Direct use of ActiveRecord join
	 */
	public function join($table, $condition, $type = '', $escape = null)
	{
		$this->_database->join($table, $condition, $type, $escape);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Generates the SELECT portion of the query
	 */
	public function select($select = '*', $escape = null)
	{
		$this->_database->select($select, $escape);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Generates the WHERE portion of the query.
	 * Separates multiple calls with 'AND'.
	 */
	public function where($key, $value = NULL, $escape = NULL)
	{
		$this->_database->where($key, $value, $escape);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Generates a WHERE field IN('item', 'item') SQL query,
	 * joined with 'AND' if appropriate.
	 */
	public function where_in($key = NULL, $values = NULL, $escape = NULL)
	{
		$this->_database->where_in($key, $values, $escape);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Generates the WHERE portion of the query.
	 * Separates multiple calls with 'OR'.
	 */
	public function or_where($key, $value = NULL, $escape = NULL)
	{
		$this->_database->or_where($key, $value, $escape);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Generates a WHERE field IN('item', 'item') SQL query,
	 * joined with 'OR' if appropriate.
	 */
	public function or_where_in($key = NULL, $values = NULL, $escape = NULL)
	{
		$this->_database->or_where_in($key, $values, $escape);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Generates a WHERE field NOT IN('item', 'item') SQL query,
	 * joined with 'AND' if appropriate.
	 */
	public function where_not_in($key = NULL, $values = NULL, $escape = NULL)
	{
		$this->_database->where_not_in($key, $values, $escape);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Generates a WHERE field NOT IN('item', 'item') SQL query,
	 * joined with 'OR' if appropriate.
	 */
	public function or_where_not_in($key = NULL, $values = NULL, $escape = NULL)
	{
		$this->_database->or_where_not_in($key, $values, $escape);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Generates a %LIKE% portion of the query.
	 * Separates multiple calls with 'AND'.
	 */
	public function like($field, $match = '', $side = 'both', $escape = NULL)
	{
		$this->_database->like($field, $match, $side, $escape);
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a NOT LIKE portion of the query.
	 * Separates multiple calls with 'AND'.
	 */
	public function not_like($field, $match = '', $side = 'both', $escape = NULL)
	{
		$this->_database->not_like($field, $match, $side, $escape);
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a %LIKE% portion of the query.
	 * Separates multiple calls with 'OR'.
	 */
	public function or_like($field, $match = '', $side = 'both', $escape = NULL)
	{
		$this->_database->or_like($field, $match, $side, $escape);
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Generates a NOT LIKE portion of the query.
	 * Separates multiple calls with 'OR'.
	 */
	public function or_not_like($field, $match = '', $side = 'both', $escape = NULL)
	{
		$this->_database->or_not_like($field, $match, $side, $escape);
		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Tell the class to skip the insert validation
	 */
	public function skip_validation()
	{
		$this->skip_validation = true;
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Get the skip validation status
	 */
	public function get_skip_validation()
	{
		return $this->skip_validation;
	}

	// --------------------------------------------------------------------

	/**
	 * Return the next auto increment of the table. Only tested on MySQL.
	 */
	public function get_next_id()
	{
		return (int) $this->_database->select('AUTO_INCREMENT')
			->from('information_schema.TABLES')
			->where('TABLE_NAME', $this->_table)
			->where('TABLE_SCHEMA', $this->_database->database)->get()->row()->AUTO_INCREMENT;
	}

	// --------------------------------------------------------------------

	/**
	 * Getter for the table name
	 */
	public function table()
	{
		return $this->_table;
	}

	// -----------------------------------------------------------------------------
	// GLOBAL SCOPES
	// -----------------------------------------------------------------------------

	/**
	 * Return the next call as an array rather than an object
	 */
	public function as_array()
	{
		$this->_temporary_return_type = 'array';
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Return the next call as an object rather than an array
	 */
	public function as_object()
	{
		$this->_temporary_return_type = 'object';
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Don't care about soft deleted rows on the next call
	 */
	public function with_deleted()
	{
		$this->_temporary_with_deleted = true;
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Only get deleted rows on the next call
	 */
	public function only_deleted()
	{
		$this->_temporary_only_deleted = true;
		return $this;
	}

	// -----------------------------------------------------------------------------
	// OBSERVERS
	// -----------------------------------------------------------------------------

	/**
	 * MySQL DATETIME created_at, updated_at and deleted_at
	 */
	public function created_at($row)
	{
		if (is_object($row))
		{
			$row->created_at = $this->_datetime();
		}
		else
		{
			$row['created_at'] = $this->_datetime();
		}

		return $row;
	}

	// --------------------------------------------------------------------

	public function updated_at($row)
	{
		if (is_object($row))
		{
			$row->updated_at = $this->_datetime();
		}
		else
		{
			$row['updated_at'] = $this->_datetime();
		}

		return $row;
	}

	// --------------------------------------------------------------------

	public function deleted_at($row)
	{
		if (is_object($row))
		{
			$row->deleted_at = $this->_datetime();
		}
		else
		{
			$row['deleted_at'] = $this->_datetime();
		}

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * Returns unix timestamp or a date in a given format.
	 * @return string date or unix_timestamp
	 */
	private function _datetime()
	{
		return ($this->datetime_format == 'timestamp')
				? time()
				: date($this->datetime_format);
	}

	// --------------------------------------------------------------------

    /**
	 * Serialises data for you automatically, allowing you to pass
	 * through objects and let it handle the serialisation in the background
	 */
	public function serialize($row)
	{
		foreach ($this->callback_parameters as $column)
		{
			$row[$column] = serialize($row[$column]);
		}

		return $row;
	}

	// --------------------------------------------------------------------

	public function unserialize($row)
	{
		foreach ($this->callback_parameters as $column)
		{
			if (is_array($row))
			{
				$row[$column] = unserialize($row[$column]);
			}
			else
			{
				$row->$column = unserialize($row->$column);
			}
		}

		return $row;
	}

	// ------------------------------------------------------------------------

	public function prepare_input($row)
	{
		foreach ($this->callback_parameters as $column)
		{
			if (is_array($row) && isset($row[$column]))
			{
				$row[$column] = to_bool_or_serialize($row[$column]);
			}
			elseif (isset($row->{$column}))
			{
				$row->{$column} = to_bool_or_serialize($row->{$column});
			}
		}

		return $row;
	}

	// ------------------------------------------------------------------------

	public function prepare_output($row)
	{
		foreach ($this->callback_parameters as $column)
		{
			if (is_array($row) && isset($row[$column]))
			{
				$row[$column] = from_bool_or_serialize($row[$column]);
			}
			elseif (isset($row->{$column}))
			{
				$row->{$column} = from_bool_or_serialize($row->{$column});
			}
		}

		return $row;
	}

	// ------------------------------------------------------------------------

	public function prepare_numeric($row)
	{
		if (empty($row))
		{
			return $row;
		}

		foreach ($row as $key => $val)
		{
			if (is_numeric($val))
			{
				if (is_array($row))
				{
					$row[$key] = (int) $val;
				}
				else
				{
					$row->{$key} = (int) $val;
				}
			}
		}
		return $row;
	}

	// ------------------------------------------------------------------------

	public function cached_column($row)
	{
		if (is_object($row))
		{
			$row->cached = false;
		}
		elseif (is_array($row))
		{
			$row['cached'] = false;
		}

		return $row;
	}

	// --------------------------------------------------------------------

	/**
	 * Protect attributes by removing them from $row array
	 */
	public function protect_attributes($row)
	{
		foreach ($this->protected_attributes as $attr)
		{
			if (is_object($row))
			{
				unset($row->$attr);
			}
			else
			{
				unset($row[$attr]);
			}
		}

		return $row;
	}

	// -----------------------------------------------------------------------------
	// QUERY BUILDER DIRECT ACCESS METHODS
	// -----------------------------------------------------------------------------

	/**
	 * A wrapper to $this->_database->order_by()
	 */
	public function order_by($criteria, $order = 'ASC')
	{
		if ( is_array($criteria) )
		{
			foreach ($criteria as $key => $value)
			{
				$this->_database->order_by($key, $value);
			}
		}
		else
		{
			$this->_database->order_by($criteria, $order);
		}
		return $this;
	}

	// -----------------------------------------------------------------------------

    /**
     * public function group_by($grouping_by)
     * A wrapper to $this->_database->group_by()
     * @param $grouping_by
     * @return $this
     */
    public function group_by($grouping_by)
    {
        $this->_database->group_by($grouping_by);
        return $this;
    }

	// --------------------------------------------------------------------

    /**
	 * A wrapper to $this->_database->limit()
	 */
	public function limit($limit, $offset = 0)
	{
		$this->_database->limit($limit, $offset);
		return $this;
	}

	// -----------------------------------------------------------------------------
	// INTERNAL METHODS
	// -----------------------------------------------------------------------------

	/**
	 * Trigger an event and call its observers. Pass through the event name
	 * (which looks for an instance variable $this->event_name), an array of
	 * parameters to pass through and an optional 'last in interation' boolean
	 */
	public function trigger($event, $data = false, $last = true)
	{
		if (isset($this->$event) && is_array($this->$event))
		{
			foreach ($this->$event as $method)
			{
				if (strpos($method, '('))
				{
					preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);

					$method = $matches[1];
					$this->callback_parameters = explode(',', $matches[3]);
				}

				$data = call_user_func_array(array($this, $method), array($data, $last));
			}
		}

		return $data;
	}

	// --------------------------------------------------------------------

	/**
	 * Run validation on the passed data
	 */
	public function validate($data)
	{
		if($this->skip_validation)
		{
			return $data;
		}

		if(!empty($this->validate))
		{
			foreach($data as $key => $val)
			{
				$_POST[$key] = $val;
			}

			$this->load->library('form_validation');

			if(is_array($this->validate))
			{
				$this->form_validation->set_rules($this->validate);

				if ($this->form_validation->run() === true)
				{
					return $data;
				}
				else
				{
					return false;
				}
			}
			else
			{
				if ($this->form_validation->run($this->validate) === true)
				{
					return $data;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return $data;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * List table's fields.
	 */
	public function list_fields($table = null)
	{
		$table OR $table = $this->_table;
		return $this->_database->list_fields($table);
	}

	// --------------------------------------------------------------------

	/**
	 * Determine if a particular field exits.
	 * @param   string  $field  the field's name
	 * @param   string  $table  the table's name.
	 * @return  bool    TRUE if exists, else false.
	 */
	public function field_exists($field, $table = null)
	{
		$table OR $table = $this->_table;

		// Make sure first that the table exists.
		if ( ! $this->_database->table_exists($table))
		{
			return false;
		}

		return $this->_database->field_exists($field, $table);
	}

	// --------------------------------------------------------------------

	/**
	 * Guess the table name by pluralising the model name
	 */
	private function _fetch_table()
	{
		if ($this->_table == null)
		{
			$this->load->helper('inflector');
			$this->_table = plural(preg_replace('/(_m|_mod|_model)?$/', '', strtolower(get_class($this))));
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Set WHERE parameters, cleverly
	 */
	protected function _set_where($params)
	{
		if (count($params) == 1 && is_array($params[0]))
		{
			foreach ($params[0] as $field => $filter)
			{
				if (is_array($filter))
				{
					$this->_database->where_in($field, $filter);
				}
				else
				{
					if (is_int($field))
					{
						$this->_database->where($filter);
					}
					else
					{
						$this->_database->where($field, $filter);
					}
				}
			}
		}
		else if (count($params) == 1)
		{
			$this->_database->where($params[0]);
		}
		else if(count($params) == 2)
		{
			if (is_array($params[1]))
			{
				$this->_database->where_in($params[0], $params[1]);
			}
			else
			{
				$this->_database->where($params[0], $params[1]);
			}
		}
		else if(count($params) == 3)
		{
			$this->_database->where($params[0], $params[1], $params[2]);
		}
		else
		{
			if (is_array($params[1]))
			{
				$this->_database->where_in($params[0], $params[1]);
			}
			else
			{
				$this->_database->where($params[0], $params[1]);
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Return the method name for the current return type
	 */
	protected function _return_type($multi = false)
	{
		$method = ($multi) ? 'result' : 'row';
		return $this->_temporary_return_type == 'array' ? $method . '_array' : $method;
	}

    // -----------------------------------------------------------------------------

    /**
     * private function _set_connection()
     *
     * Sets the connection to database
     */
    private function _set_connection()
    {
        if(isset($this->_database_connection))
        {
            $this->_database = $this->load->database($this->_database_connection,TRUE);
        }
        else
        {
            $this->load->database();
            $this->_database = $this->db;
        }
        // This may not be required
        return $this;
    }
}
