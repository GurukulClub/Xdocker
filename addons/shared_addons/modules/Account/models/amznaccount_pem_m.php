<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\amazonaccount_pem\Models
 */
class AmznAccount_pem_m extends MY_Model
{
	protected $_table = 'amzn_account_pem';

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
