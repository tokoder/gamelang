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
 * Gamelang_crud_interface
 *
 * Semua perpustakaan khusus kami yang menangani
 * operasi CRUD akan diimplementasikan antarmuka ini.
 *
 * @category 	Interfaces
 * @author		Tokoder Team
 */
interface Gamelang_crud_interface
{
	public function create(array $data = array());
	public function get($id);
	public function get_by($field, $match = null);
	public function get_many($field = null, $match = null, $limit = 0, $offset = 0);
	public function get_all($limit = 0, $offset = 0);
	public function update($id, array $data = array());
	public function update_by();
	public function delete($id);
	public function delete_by($field = null, $match = null);
}