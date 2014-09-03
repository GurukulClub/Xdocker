<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\amazonaccount\Models
 */
class AwsBilling_m extends MY_Model
{
	protected $_table = 'aws_billing';

	public function get_all()
	{
		$this -> db -> select('aws_billing.*') -> select('users.username, profiles.display_name') -> join('profiles', 'profiles.user_id = aws_billing.user_id', 'left') -> join('users', 'aws_billing.user_id = users.id', 'left') -> order_by('created_on', 'DESC');

		return $this -> db -> get('aws_billing') -> result();
	}

	public function get_all_between($startDate, $endDate)
	{
		$this -> db -> select('aws_billing.*') -> select('users.username, profiles.display_name') -> join('profiles', 'profiles.user_id = aws_billing.user_id', 'left') -> join('users', 'aws_billing.user_id = users.id', 'left') -> where('aws_billing.UsageStartDate >= ', $startDate) -> where('aws_billing.UsageStartDate <= ', $endDate) -> order_by('created_on', 'DESC');

		return $this -> db -> get('aws_billing') -> result();
	}

}
