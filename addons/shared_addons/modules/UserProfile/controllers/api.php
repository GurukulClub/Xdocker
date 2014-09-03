<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User controller for the users module (frontend)
 *
 * @author		 Phil Sturgeon
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Users\Controllers
 */
require_once (SHARED_ADDONPATH . 'libraries/Curl.php');
class API extends REST_Controller
{
	/**
	 * Constructor method
	 *
	 * @return \Users
	 */
	public function __construct()
	{
		parent::__construct();
		ci()->load->model('users/user_m');
		
	}
	
	public function index_get()
	{
    	$this->response($this->db->get('users')->result());
	}
	
	public function Authenticate_get()
	{
		if(!$this->get('username'))
        {
        	$this->response('Username Parameter missing!', 400);
        }
		
		$user = $this->db->where(array('username' => $this->get('username') ))->get('users')->row();
		
		if($this->_username_check($this->get('username')))
		{
			$this->response(array('status' => 'OK', 'message'=> 'Tenant authentication successfull!'), 200);
		}
		else 
		{
			$this->response(array('status' => 'error', 'message'=> 'Tenant authentication failure!'), 404);
		}
	}	
	
	/**
	 * Callback method used during login
	 *
	 * @param str $email The Email address
	 *
	 * @return bool
	 */
	public function _check_login($email)
	{
		$remember = false;
		if ($this->input->post('remember') == 1)
		{
			$remember = true;
		}

		if ($this->ion_auth->login($email, $this->input->post('password'), $remember))
		{
			return true;
		}

		Events::trigger('login_failed', $email);
		error_log('Login failed for user '.$email);

		$this->form_validation->set_message('_check_login', $this->ion_auth->errors());
		return false;
	}

	/**
	 * Username check
	 *
	 * @author Ben Edmunds
	 *
	 * @param string $username The username to check.
	 *
	 * @return bool
	 */
	private function _username_check($username)
	{
		if ($this->ion_auth->username_check($username))
		{
			return true;
		}

		return false;
	}

	/**
	 * Email check
	 *
	 * @author Ben Edmunds
	 *
	 * @param string $email The email to check.
	 *
	 * @return bool
	 */
	public function _email_check($email)
	{
		if ($this->ion_auth->email_check($email))
		{
			return true;
		}

		return false;
	}

	
	
}