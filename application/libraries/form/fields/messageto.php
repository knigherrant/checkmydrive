<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */



class JFormFieldMessageto extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'messageto';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
            if(!$this->form->getData()->get('id', 0)){
                $db = Checkmydrive::getDbo();
                $where = Checkmydrive::buildWhere ('c');
                $query = $db->query('SELECT u.id AS value, CONCAT(u.name," - ",cl.name) AS text FROM clientrol_contacts c '
                        . 'LEFT JOIN users u ON u.id=c.id '
                        . 'LEFT JOIN clientrol_clients cl ON c.client_id=cl.id ' . $where);
                $options = $query->result();
                return JHtml::_('select.genericlist', $options, $this->name, '', 'value', 'text', $this->value, $this->id);
            }else{
                $params = CheckmydriveHelper::getConfigs();
                if($this->value != Checkmydrive::getCreatedById()){
                    $user = CheckmydriveHelper::getUser($this->value);
                    $receiver = new stdClass();
                    $receiver->id = $user->user->id;
                    $receiver->name = $user->user->name.' - '.$user->contact->client_name;
                }else{
                    $receiver = CheckmydriveHelper::getUser(Checkmydrive::getCreatedById());
                }
                ob_start();
                ?>
                <input type="text" id="<?php echo $this->id;?>" value="<?php echo $receiver->name;?>" readonly="true" class="readonly" aria-invalid="false">
                <input type="hidden" id="<?php echo $this->id;?>_id" name="<?php echo $this->name;?>" value="<?php echo $receiver->id;?>">
                <?php
                return ob_get_clean();
            }
	}
}