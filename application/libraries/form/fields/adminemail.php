<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */


class JFormFieldAdminemail extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'adminemail';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput() {
            $db = Checkmydrive::getDbo(true);
            $where = '';
            if(!Checkmydrive::isSuperUser()){
                $where = ' WHERE a.id=' . Checkmydrive::getUser()->id ;
            }
            $query = $db->query('SELECT a.id AS value, a.name AS text FROM users a' . $where);
            $options = $query->result();
            $assigneds = array();
            foreach($options as $item){
                if(!$this->value) $this->value = array($item->value);
                $assigneds[] = $item;
            }
            return JHtml::_('select.genericlist', $assigneds, $this->name, '', 'value', 'text', $this->value, $this->id);
	}
}