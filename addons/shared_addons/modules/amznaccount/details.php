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
			),
		);
	}

	public function install()
	{
		$emailTemplates = array(
			array(
				'slug' => 'amznaccount_created',
				'name' => 'Amazon Account Created',
				'description' => 'Amazon Account Created',
				'subject' => '{{ settings:site_name }} :: Amazon Account created by {{ name }}',
				'body' => '{{ settings:site_name }} :: Amazon Account created by {{ name }}<br />Account Name: {{ accountName}}',
				'lang' => 'en',
				'is_default' => '0',
				'module' => '',
			),
			array(
				'slug' => 'amznaccount_updated',
				'name' => 'Amazon Account Updated',
				'description' => 'Amazon Account Updated',
				'subject' => '{{ settings:site_name }} :: Amazon Account Updated by {{ name }}',
				'body' => '{{ settings:site_name }} :: Amazon Account created by {{ name }}<br />Account Name: {{ accountName}}',
				'lang' => 'en',
				'is_default' => '0',
				'module' => '',
			),
			array(
				'slug' => 'amznaccount_deleted',
				'name' => 'Amazon Account Deleted',
				'description' => 'Amazon Account Deleted',
				'subject' => '{{ settings:site_name }} :: Amazon Account Deleted by {{ name }}',
				'body' => '{{ settings:site_name }} :: Amazon Account created by {{ name }}<br />Account Name: {{ accountName}}',
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
