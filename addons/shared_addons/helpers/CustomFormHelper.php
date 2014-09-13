<?php

class CustomFormHelper
{
	public static function getDropdown($selectName, 
									   $data, 
									   $required = false,
									   $optionName = 'id', 
									   $optionValue = 'name', 
									   $multiple = false, 
									   $defualtOption = array('Select an Option' => ''))
	{
		$multi = '';
		if($multiple) 
		{
			$multi ='multiple';
			$selectName = $selectName.'[]';
		}
		$req = '';
		if($required) $req = 'class="required"';
		$ret = '<select name="'.$selectName . '"' . $multi. ' ' . $req . '>';
		
		foreach($defualtOption as $optionKey => $optionVal)
		{
			$ret .= '<option value="'.$optionVal.'">'.$optionKey.'</option>';
		}
		$firstSelected = 0;
		foreach($data as $row)
		{
			if($multiple && $firstSelected == 0)
			{
				$firstSelected++;
				$ret .= '<option value="'. $row->{$optionName} .'" selected="selected">'. $row->{$optionValue} .'</option>';
			}
			else 
			{
				$ret .= '<option value="'. $row->{$optionName} .'">'. $row->{$optionValue} .'</option>';
			}
		}
		
		$ret .= '</select>';
		return $ret;
	}
}