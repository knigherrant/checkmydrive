<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */



/**
 * Form Field class for the Joomla Platform.
 * Supports a nested check box field listing user groups.
 * Multiselect is available by default.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldUser extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'User';

	/**
	 * Method to get the user group field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
            
	    $display = ($this->value)? Checkmydrive::getUser($this->value)->name : Checkmydrive::getUser()->name;
            $this->value = ($this->value)? $this->value : Checkmydrive::getUser()->id;
            $html = '<input readonly="readonly" type="hidden" name="'.$this->name.'" value="' . $this->value . '"/>';
            $html .= '<input readonly="readonly" type="text" value="' . $display . '"/>';
            return $html;
	}
}
