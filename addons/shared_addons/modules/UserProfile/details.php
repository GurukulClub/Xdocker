<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Widgets Module
 *
 * @author Xervmon Dev Team
 * @package Xervmon\Core\Modules\Widgets
 */
   require_once(SHARED_ADDONPATH.'helpers/StringHelper.php');
class Module_UserProfile extends Module {

	public $version = '1.2.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'UserProfile - Edit Profile',
			
			),
			'description' => array(
				'en' => 'Overrides User profile view on backend',
			),
			'frontend' 	=> false,
			'backend'  	=> true,
			'menu'	  	=> '',
			'skip_xss'	=> true,
			
		
		);
	}

	public function install()
	{
		
		/*$settings = array(
			array(
				'slug' => 'app_key',
				'title' => 'App Tenant Key',
				'description' => 'App Tenant Key for APIs',
				'type' => 'text',
				'default' => '',
				'value' => md5(StringHelper::gen_uuid()),
				'options' => '',
				'is_required' => 0,
				'is_gui' => 1,
				'module' => 'users',
				'order' => 964,
			),
			array(
				'slug' => 'app_secret',
				'title' => 'App Secret Key',
				'description' => 'App Secret Key for APIs',
				'type' => 'text',
				'default' => '',
				'value' => StringHelper::gen_uuid(),
				'options' => '',
				'is_required' => 0,
				'is_gui' => 1,
				'module' => 'users',
				'order' => 964,
			)
			
			);
		
		foreach ($settings as $setting)
		{
			if ( ! $this->db->insert('settings', $setting))
			{
				return false;
			}
		}*/
		
		// Install the emailTemplate
		$emailTemplates = array(
			array(
				'slug' => 'login_success',
				'name' => 'Login Success',
				'description' => 'Login Success',
				'subject' => '{{ settings:site_name }} ::  Xervmon Security Notice',
				'body' => "{{ settings:site_name }} <br/> There's been some activity on your account. <br/> sudhi@xervmon.com This is a security notification from Xervmon User: sudhi@xervmon.com Note: login( Logged in by {{ username}}<br />
				Email Id: {{ email}}<br />
				IP Address: {{ ip_address}}<br />
				Display Name: {{ display_name}} )<br/> If this activity is your own, or a co-worker's, then there's no need to respond — you can simply ignore this email. <br/> If this seems odd, we recommend that you: <br/> Scan your computer for viruses or malware.<br/> Get in touch with our support team to report potentially malicious activity on your account.
				See what steps you can take in the event your account has been compromised.
				Your account's security is important to us, and we want it to be important to you as well. We wrote a guide on improving your security. <br/>© 2013 Xervmon®, All Rights Reserved. 
				<br/> 13940 Bammel N Houston Road. • Suite 108 • Houston, TX 77377 USA",
				'lang' => 'en',
				'is_default' => '0',
				'module' => '',
			),
			);
			
		foreach ($emailTemplates as $emailTemplate)
		{
			if ( ! $this->db->insert('email_templates', $emailTemplate))
			{
				return false;
			}
		}
		return true;
	}

	public function uninstall()
	{
		// This is a core module, lets keep it around.
		return false;
	}

	public function upgrade($old_version)
	{
		return true;
	}

}
