<?php

/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */


/**
 * Methods supporting a list of Checkmydrive records.
 */
class Settings_model extends CI_Model {

    var $name;
    
    public function __construct() {
        parent::__construct();
	if (empty($this->name)) {
            $r = null;
            if (!preg_match('/(.*)\_model/i', get_class($this), $r))
            {
                    throw new Exception(Checkmydrive::_('JLIB_APPLICATION_ERROR_MODEL_GET_NAME'), 500);
            }
            $this->name = strtolower($r[1]);
        }
        
    }

    public function getItem($id = null) {
        $query = Checkmydrive::getDbo(true);
        $retult = $query->query('SELECT configs FROM configs WHERE id=' . $id);
        $item = json_decode($retult->row()->configs);
        return $item;
    }

    public function save($data){
        if($_FILES['jform']['name']['logo']){
                $files = $_FILES;
                $name = array('name','type','tmp_name','error','size');
                foreach ($name as $n) $_FILES['logo'][$n] = $files['jform'][$n]['logo'];
                $logo = $this->uploadLogo($_FILES);
                if(!$logo) return false;
        }
        if(isset($logo)) $data['logo'] = $logo;
        $save = false;
        $configs = array(
            'id' => 1,
            'configs' => json_encode($data)
        );
        $db = Checkmydrive::getDbo(true);
        $db->where('id='. $configs['id']);
        $save = $db->update('configs', $configs);
        return $save;
    }

	public function uploadLogo($file){
        $path = FCPATH.'/images/';
        $pathinfo = pathinfo($file['logo']['name']);
        $filename = $pathinfo['filename'];
        if(!Checkmydrive::upload('logo', $path, $filename)){
            Checkmydrive::setMessage(Checkmydrive::_('CHECKMYDTIVE_MSG_FILE_UPLOAD_ERROR_OCCURED'));
			return false;
        }
        else{
            $file = 'images/' . $filename .'.'.$pathinfo['extension'];
            
            return $file;
        }
    }
	
  
}
