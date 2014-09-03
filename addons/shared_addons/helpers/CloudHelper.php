<?php

require_once SHARED_ADDONPATH.'libraries/aws/S3.php';
require_once SHARED_ADDONPATH.'libraries/aws/S3Wrapper.php';

class CloudHelper
{
	public static function getModule($slug)
	{
		$moduleArr = module_array();
		foreach ($moduleArr as $key => $module)
		{
			if($slug == $module['slug'])
			{
				return $module;
			}
		}
	}
	
	public static function uploadToS3($key, $access, $outputFile, $streamPath)
	{
		S3::setAuth($key, $access);
		log_message('debug', 'Writing to stream.. ' . $streamPath);
		$ret = file_put_contents($streamPath, file_get_contents($outputFile));
		log_message('debug', 'Total total bytes.. ' . $ret);
	
	}
	
	public static function xiaGenerated($key, $access, $outputFile)
	{
		S3::setAuth($key, $access);
		$content = @file_get_contents($outputFile, true);
		if($content === false) return false; else return true;
	}
	
	public static function downloadFromS3($key, $access, $outputFile, $file)
	{
		S3::setAuth($key, $access);
		log_message('debug', 'Reading from stream.. ' . $outputFile);
		$content = file_get_contents($outputFile);
	
		header('Content-Description: File Transfer');
		header('Content-Type: application/zip' );
		header('Content-Disposition: attachment; filename='.$file);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		//header('Content-Length: ' . $ret['message']['ContentLength']);
		echo $content;
	
	
	}
	
}
