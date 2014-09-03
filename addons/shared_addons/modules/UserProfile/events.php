<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Email Template Events Class
 *
 * @author      Stephen Cozart
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Templates
 */
 require_once (SHARED_ADDONPATH . 'modules/Cloud/libraries/XIALibrary.php');
 
class Events_UserProfile {

    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();

        //register the email event
        Events::register('user_created', array($this, 'UserCreate'));
		//register the email event
        Events::register('user_updated', array($this, 'UserUpdate'));
    }

  public function UserCreate($id)
  {
		$userModel  = ci()->load->model('users/user_m') ;
		$user = $userModel ->get($id);
	}
  
}
/* End of file events.php */