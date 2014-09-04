<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Amazon Accounts module
 *
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\AmazonAccount
 */
class Module_AmznAccount extends Module
{
	public $version = '2.0.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Amazon Account',
			),
			'description' => array(
				'en' => 'Amazon Account - Captures details needed for Amazon Account.',
			),
			'frontend' => false,
			'backend' => true,
			'skip_xss' => true,
			'menu' => 'accounts',

			'roles' => array(
				'add_amznaccount', 'edit_amznaccount', 'delete_amznaccount'
			),

			'sections' => array(
				'accounts' => array(
					'name' => 'amznaccount:list_title',
					'uri' => 'admin/amznaccount',
					'shortcuts' => array(
						array(
							'name' => 'amznaccount:create_title',
							'uri' => 'admin/amznaccount/create',
							'class' => 'add',
						),
					),
				),
				'keys' => array(
					'name' => 'amznaccount:cloud',
					'uri' => 'admin/Cloud/index',
				),
				
			),
		);
	}

	public function install()
	{
		$emailTemplates = array(
			array(
				'slug' => 'amznaccount_created',
				'name' => 'Amazon Created',
				'description' => 'Amazon Created',
				'subject' => '{{ settings:site_name }} :: Amazon created by {{ name }}',
				'body' => '{{ settings:site_name }} :: Amazon created by {{ name }}<br />Account ID: {{ account_id}}',
				'lang' => 'en',
				'is_default' => '0',
				'module' => '',
			),
			array(
				'slug' => 'amznaccount_updated',
				'name' => 'Amazon Updated',
				'description' => 'Amazon Updated',
				'subject' => '{{ settings:site_name }} :: Amazon Updated by {{ name }}',
				'body' => '{{ settings:site_name }} :: Amazon created by {{ name }}<br />Account ID: {{ account_id}}',
				'lang' => 'en',
				'is_default' => '0',
				'module' => '',
			),
			array(
				'slug' => 'amznaccount_deleted',
				'name' => 'Amazon Deleted',
				'description' => 'Amazon Deleted',
				'subject' => '{{ settings:site_name }} :: Amazon Deleted by {{ name }}',
				'body' => '{{ settings:site_name }} :: Amazon created by {{ name }}<br />Account ID: {{ account_id}}',
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
