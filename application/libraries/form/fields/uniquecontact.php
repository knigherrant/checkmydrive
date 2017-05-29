<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */



class JFormFieldUniqueContact extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'uniquecontact';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
            $db = JFactory::getDbo();
            $db->setQuery('SELECT a.name AS text, a.id as value FROM #__users a LEFT JOIN #__clientrol_contacts c ON a.id=c.id WHERE c.id IS NULL || c.id='.(int)$this->value);
            $options = $db->loadAssocList();
            if($options) foreach($options as $k=>$option){
                if(CheckmydriveHelper::userIsAdmin($option['value'])) unset($options[$k]);
            }
            return JHtml::_('select.genericlist', $options, $this->name, '', 'value', 'text', $this->value, $this->id);
	}
}