<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */


class JFormFieldMessagefrom extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'messagefrom';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
        $params = CheckmydriveHelper::getConfigs();
        if(!$this->value || $this->value == Checkmydrive::getCreatedById()) $sender = CheckmydriveHelper::getUser(Checkmydrive::getCreatedById());
        else{
            $user = CheckmydriveHelper::getUser($this->value);
            $sender = new stdClass();
            $sender->id = $user->user->id;
            $sender->name = $user->user->name.' - '.$user->contact->client_name;
        }
        ob_start();
        ?>
        <input type="text" id="<?php echo $this->id;?>" value="<?php echo $sender->name;?>" readonly="true" class="readonly" aria-invalid="false">
        <input type="hidden" id="<?php echo $this->id;?>_id" name="<?php echo $this->name;?>" value="<?php echo $sender->id;?>">
        <?php
        return ob_get_clean();
	}
}