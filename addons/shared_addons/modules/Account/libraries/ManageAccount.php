<?php
require_once (SHARED_ADDONPATH . 'libraries/Xervmon/CloudType.php');
class ManageAccount 
{
	public static function Fields($provider)
	{
		$ret = '';
		switch ($provider)
		{
			case CloudType::AWS_CLOUD:
							$ret = '<input type="text" name="apiAccessKey" id="apiAccessKey"  class="input-xlarge">';
							$ret .= '<input type="text" name="apiAccessKey" id="apiAccessKey"  class="input-xlarge">';
					break;
		}
		return $ret;
	}
	
}
