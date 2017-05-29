<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */


class JFormFieldCurrency extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'currency';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
        $db = Checkmydrive::getDbo();
        $query = $db->query('SELECT CONCAT(symbol," - ",code) AS text, code as value FROM clientrol_currencies '.Checkmydrive::buildWhere().' ORDER BY code');
        $options = $query->result();
        return JHtml::_('select.genericlist', $options, $this->name, '', 'value', 'text', $this->value, $this->id);
	}
}