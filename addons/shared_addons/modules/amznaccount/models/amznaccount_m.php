<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\amazonaccount\Models
 */
class AmznAccount_m extends MY_Model
{
	protected $_table = 'amzn_accounts';

	public function get_all()
	{
		$this -> db -> select('amzn_accounts.*') -> select('users.username, profiles.display_name') -> join('profiles', 'profiles.user_id = amzn_accounts.user_id', 'left') -> join('users', 'amzn_accounts.user_id = users.id', 'left') -> order_by('amzn_accounts.created_on', 'DESC');

		return $this -> db -> get('amzn_accounts') -> result();
	}

	public function getCommonView()
	{
		$this -> db -> select('amzn_accounts.id, amzn_accounts.name as "Account Name", amzn_accounts.api_key as API Key') -> select('users.username, profiles.display_name as Created By') -> join('profiles', 'profiles.user_id = amzn_accounts.user_id', 'left') -> join('users', 'amzn_accounts.user_id = users.id', 'left') -> order_by('amzn_accounts.created_on', 'DESC');

		return $this -> db -> get('amzn_accounts') -> result();
	}

	public function getAccounts()
	{
		$this -> db -> select('amzn_accounts.*') -> order_by('created_on', 'DESC');
		$accounts = $this -> db -> get('amzn_accounts') -> result();
		$options = array();
		foreach ($accounts as $key)
		{
			$options[$key -> id] = $key -> name;
		}
		return $options;
	}

	public function getAccounts2()
	{
		$this -> db -> select('amzn_accounts.*') -> order_by('created_on', 'DESC');
		$accounts = $this -> db -> get('amzn_accounts') -> result();

		return $accounts;
	}

	public function get($id)
	{
		return $this -> db -> select('amzn_accounts.*') -> select('users.username, profiles.display_name') -> join('profiles', 'profiles.user_id = amzn_accounts.user_id', 'left') -> join('users', 'amzn_accounts.user_id = users.id', 'left') -> where('amzn_accounts.id', $id) -> get('amzn_accounts') -> row();
	}

	public function get_by($key = nullf, $value = null)
	{
		$this -> db -> select('amzn_accounts.*, users.username, profiles.display_name') -> join('profiles', 'profiles.user_id = amzn_accounts.user_id', 'left') -> join('users', 'amzn_accounts.user_id = users.id', 'left');

		if (is_array($key))
		{
			$this -> db -> where($key);
		} else
		{
			$this -> db -> where($key, $value);
		}

		return $this -> db -> get($this -> _table) -> row();
	}

	public function get_many_by($params = array())
	{
		if (!empty($params['name']))
		{
			$this -> db -> or_like('amzn_accounts.name', trim($params)) -> or_like('amzn_accounts.account_id', trim($params)) -> or_like('amzn_accounts.api_key', trim($params));
		}
		return $this -> get_all();
	}

	public function count_by($params = array())
	{
		return $this -> db -> count_all_results('amzn_accounts');
	}

	public function update($id, $input, $skip_validation = false)
	{
		$input['updated_on'] = now();
		return parent::update($id, $input);
	}

	public function publish($id = 0)
	{
		return parent::update($id, array('status' => 'live', 'preview_hash' => ''));
	}

	public function check_exists($field, $value = '', $id = 0)
	{
		if (is_array($field))
		{
			$params = $field;
			$id = $value;
		} else
		{
			$params[$field] = $value;
		}
		$params['id !='] = (int)$id;

		return parent::count_by($params) == 0;
	}

}
