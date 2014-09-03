<?php
require_once (SHARED_ADDONPATH . 'libraries/Xervmon/CloudType.php');
class ManageAccount 
{
	public static function Fields($provider, $value='')
	{
		$ret = '';
		switch ($provider)
		{
			case CloudType::AWS_CLOUD:
							$ret = self::Field('<input type="text" name="apiAccessKey" id="apiAccessKey" value="$value"  class="input-xlarge">');
							$ret .= self::Field('<input type="text" name="apiAccessKey" id="apiAccessKey"  value="$value"  class="input-xlarge">');
					break;
		}
		return $ret;
	}
	
	
	private static function Field($html)
	{
		'<div class="control-group">
		<label class="control-label" for="title">'.lang('Account:title').'<span>*</span></label>
			<div class="controls">'.
				$html .
			'
			</div>
		</div>';	
	}
	
	
	
}
