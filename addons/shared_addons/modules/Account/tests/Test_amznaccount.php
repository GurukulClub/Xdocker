<?php

$path = 'modules/pyrotoast/controllers/test/toast.php';

if(file_exists(ADDONPATH . $path))
{
	require_once(ADDONPATH . $path);
}
else
{
	require_once(SHARED_ADDONPATH . $path);
}
require_once(SHARED_ADDONPATH.'libraries/Xervmon/CloudType.php');
class Test_amznaccount extends Toast
{
	private $testConfig = array();
	function Test_amznaccount()
	{
		parent::__construct(); // Remember this
		$this->init();
                //In the file /system/cms/config/config.php adding the line $config['csrf_protection'] = FALSE; will avoid errors for using cURL to post data.
	}
	
	
	private function init() 
	{
                //Initialize Login and post data
		$this->testConfig['login_url'] = site_url().'/admin/login' ;
		$this->testConfig['login_post'] = "email=admin&password=Java0man&remember=on&submit=Log+In" ;

		$this->testConfig['cookie'] = 'cookie.txt';
		
		//Each index is a test case number pointing to an array of data and method which would consume the array
		$this->testConfig['post_data'] = array(array('name'=>'Bhargav',
							'account_id'=>12345,
                                                        'cloud_provider'=>  CloudType::AWS_CLOUD,
							'api_key'=>54321,
							'secret_key'=>'098765',
							'support_cost_management'=>'',
							'account_user'=>'bhargav',
							'password'=>'test',
							'method' => site_url().'/admin/amznaccount/saveData'
                                                            ),
                                                        array('name'=>'Dummy',
							'account_id'=>34324,
                                                        'cloud_provider'=>  CloudType::AWS_CLOUD,
							'api_key'=>32321,
							'secret_key'=>'198765',
							'support_cost_management'=>'',
							'account_user'=>'dummy',
							'password'=>'dummytest',
							'method' => site_url().'/admin/amznaccount/saveData'
                                                            ),
                                                        array('name'=>'Diddly',
							'account_id'=>34125,
                                                        'cloud_provider'=>  CloudType::AWS_CLOUD,
							'api_key'=>998321,
							'secret_key'=>'678765',
							'support_cost_management'=>'',
							'account_user'=>'diddly',
							'password'=>'diddlytest',
							'method' => site_url().'/admin/amznaccount/saveData'
                                                            )
                                                        );
		$this->testConfig['output_message'] = '{"status":"success","status_msg":"Amazon Account was added successfully"}';
		$this->testConfig['post_url']       = site_url().'/admin/amznaccount/saveData'; 	
	}
	

	public function test_saveData()
	{
         //pass the login info and other parameters like remember me.
		$params['login_url'] 		= $this->testConfig['login_url'];
		$params['login_post']		= $this->testConfig['login_post'];
		$params['cookie']               = $this->testConfig['cookie'];
		$params['binary']               = false;
                $params['post_url']             = $this->testConfig['post_url'];
		$arloop['post_data']            = $this->testConfig['post_data'];//array of array testConfig['post_data'][$integer]
			
		foreach ($arloop['post_data'] as $index => $post_data) {
                    
                    $result=$this->login_post_data($params,$post_data);
                    if ($result == $this->testConfig['output_message'])
                    {
                        $this->assert_true(TRUE);
                    }
                    else
                    {
                        $this->assert_true(FALSE);
                        echo $result;
                    
                    }
                    
                }
                
	}
}


?>
