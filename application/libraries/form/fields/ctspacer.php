<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */



class JFormFieldCtSpacer extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'ctspacer';

    /**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
        return '<hr style="background-color: #ddd"/>';
	}

    protected function getLabel()
    {
        return '<strong class="hasTooltip" title="'.Checkmydrive::_($this->element['desciption']).'">'.Checkmydrive::_($this->element['label']).'</strong>';
    }
}