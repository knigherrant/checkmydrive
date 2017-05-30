<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 * aloudmedia/$P$BcJdNmQRKhKfoJB2TqUfEtCXRqR7zm1
 * beaconhospital/$2a$08$lnRmQ4tmwrVYkibEaHbj.OwjUn/JN024Ovh7/3CpZUudh.2y4vu.W
 * celticbeds/$2a$08$UHDBEb3Qy4dgWqkN0PRio.cmLiHi0QJqkoxDTjllM59//FsD/rfg.
 */

class Checkmydrive extends CheckmydriveHelper{
    
    static $pagination = 3;
    static $prefix = 'clientrol_';
    static $comment = array('#','--','-- ','DELIMITER','/*!');
    static $delimiter = ';';
    static $string_quotes = '\''; 
    static $db_connection_charset = 'utf8';
    static $max_query_lines = 300000;
    static $gzipmode = false;
    static $DATA_CHUNK_LENGTH = 16384;

    public static function root(){
        //self::addColunm2Table();
        $config = new CI_Config;
        return $config->config['base_url'];
    }
    
    public static function generateStrongPassword($length = 12, $add_dashes = false, $available_sets = 'luds'){
            $sets = array();
            if(strpos($available_sets, 'l') !== false)
                    $sets[] = 'abcdefghjkmnpqrstuvwxyz';
            if(strpos($available_sets, 'u') !== false)
                    $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
            if(strpos($available_sets, 'd') !== false)
                    $sets[] = '23456789';
            if(strpos($available_sets, 's') !== false)
                    $sets[] = '!@#$%&*?';
            $all = '';
            $password = '';
            foreach($sets as $set)
            {
                    $password .= $set[array_rand(str_split($set))];
                    $all .= $set;
            }
            $all = str_split($all);
            for($i = 0; $i < $length - count($sets); $i++)
                    $password .= $all[array_rand($all)];
            $password = str_shuffle($password);
            if(!$add_dashes)
                    return $password;
            $dash_len = floor(sqrt($length));
            $dash_str = '';
            while(strlen($password) > $dash_len)
            {
                    $dash_str .= substr($password, 0, $dash_len) . '-';
                    $password = substr($password, $dash_len);
            }
            $dash_str .= $password;
            return $dash_str;
    }
    
    
   
    
    public static function route($url = '',$build = true){
        $base = Checkmydrive::root();
        $base = rtrim($base , '/');
        if(!$url) return $base;
        $url = ltrim($url , '/');
        return  $base . '/'. $url;
    }
    
    public static function getDbo($master = false, $sale = false){
        $CI = get_instance();
        return  $CI->db;
    }
    
    public static function isBusiness(){
        $segments = self::segments();
        if($segments[1] == 'business') return true;
        return false;
    }
    
    
    public static function segments(){
        $CI = get_instance();
        if(empty($CI->uri->segments)) $CI->uri->segments = array(0=>'',1=>'');
        return $CI->uri->segments;
    }
    
    public static function rsegments(){
        $CI = get_instance();
        if(empty($CI->uri->rsegments)) $CI->uri->rsegments = array(0=>'',1=>'');
        return $CI->uri->rsegments;
    }
    
    public static function urlTheme($admin = false){
        $CI = get_instance();
        if($admin) return self::root() . 'themes/admin/';
        return self::root() . $CI->template->get_theme_path() ;
    }
    
    public static function pathTheme($admin = false){
        $CI = get_instance();
        if($admin) return FCPATH . 'themes/admin/';
        return FCPATH . $CI->template->get_theme_path() ;
    }
    
    
    public static function _($text){
        $CI = get_instance();
        return ($CI->lang->line($text))? $CI->lang->line($text) : $text;
       
    }
    
    public static function sprintf($text){
        $CI = get_instance();
        $args = func_get_args();
        unset($args[0]);
        $text =  ($CI->lang->line($text))? $CI->lang->line($text) : $text;
        return sprintf($text, implode(',', $args));
    }
    
    
    public static function userGroup($key){
        $group = array(
            3 => self::_('CHECKMYDTIVE_TITLE_ADMINISTRATOR'),
            2 => self::_('CHECKMYDTIVE_TITLE_SUBSCRIBER'),
            1 => self::_('CHECKMYDTIVE_TITLE_USER'),
        );
        return isset($group[$key])? $group[$key] : $group[2];
    }
    
    public static function input(){
        $CI = get_instance();
        return $CI->input;
    }
    
    public static function isPageAdmin($userid = null){
        $CI = get_instance();
        $rsegments = Checkmydrive::rsegments();
        if(isset($rsegments[1])){
            $views = array('dashboard');
            $sider = CheckmydriveHelper::getSidebar(true);
            foreach ($sider as $v)$views[] = $v['name'];
            return (in_array($rsegments[1], $views))? true : false;
        }
        return false;
    }
    
    
    public static function isSuperUser($userid = null){
        if(!$userid) $user = Checkmydrive::getUser ();
        else $user = Checkmydrive::getUser ($userid);
        return ($user->user_level == 3)? true : false;
    }
    
    public static function cssInline($style){
        ob_start();
        ?> <style> <?php echo $style; ?> </style> <?php
        return ob_get_clean();
    }
    
    public static function getUserByFields($mail = '', $field = 'email'){
        static $user;
        if(!isset($user[$mail])){
            $query = Checkmydrive::getDbo(true);
            $query->select('*')->from('users')->where("$field ='" .$mail ."'");
            $jUser =  $query->get()->row();
            
            $user[$mail] = $jUser;
        }
        return $user[$mail];
    }
    
    public static function getDate($now = 'now',$add = false, $format = 'Y-m-d h:i:s'){
        
        $timezone = config_item('time_reference');
        
        if ($timezone === 'local' OR $timezone === date_default_timezone_get())  $datetime = new DateTime($now);    
        else $datetime = new DateTime($now, new DateTimeZone($timezone));
        if($add) $datetime->modify($add);
        $date = (object) array(
            'toSql' => $datetime->format('Y-m-d h:i:s'),
            'toFormat' => $datetime->format($format),
        );
        return $date;
    }
    
    
    public static function loadForm(){
        $CI = get_instance();
        $CI->load->library('form');
        $CI->load->library('jregistry');
        $CI->load->library('JFolder');
        // include table
        JForm::addFormPath(APPPATH . '/models/forms');
        JHtml::addIncludePath(APPPATH . '/libraries/form/html');
    }
    
    
    
     public static function getForm($name, $item ,  $options = array(), $clear = false, $xpath = false){
        static $jform;
        $CI = get_instance();
        $CI->load->library('form');
        $CI->load->library('jregistry');
        if(is_object($item) && key_exists('id', $item)){
            if(!$item->id && key_exists('created', $item)) $item->created = Checkmydrive::getDate ()->toSql;
        }else if(is_array($item)){
            if(!$item['id'] && key_exists('created', $item)) $item['created'] = Checkmydrive::getDate ()->toSql;
        }
         // Create a signature hash.
        $hash = md5($name . serialize($options));
        // Check if we can use a previously loaded form.
        if (isset($jform[$hash]) && !$clear)
        {
                return $jform[$hash];
        }
         
        
        // include table
        JForm::addFormPath(APPPATH . '/models/forms');
        JHtml::addIncludePath(APPPATH . '/libraries/form/html');
        try
        {
                $source = $name;
                $form = JForm::getInstance($name, $source ,$options, false, $xpath);
                if (isset($options['load_data']) && $options['load_data'])
                {
                        // Get the data for the form.
                        $data = $item;
                }
                else
                {
                        $data = array();
                }

                // Load the data into the form after the plugins have operated.
                $form->bind($data);

        }
        catch (Exception $e)
        {
                print_r($e->getMessage());
                return false;
        }

        // Store the form for later.
        $jform[$hash] = $form;

        return $form;
         
     }
    
    
     public static function getUser($uid = 0, $reNew = false){
        static $user;
        $CI = get_instance();
        if(!$uid) $uid = $CI->tank_auth->get_user_id();
        $userid = $CI->tank_auth->get_user_id();
        if(!isset($user[$uid . $reNew])){
            $jUser = new stdClass();
            $query = Checkmydrive::getDbo(true);
            if(($userid) && ($uid == $userid || !$uid)){
                $query->select('u.*')->from('users u')->where('u.`id`="'.(int)$userid.'"');
                $jUser =  $query->get()->row();
                if(!$jUser) Checkmydrive::setMessage ('Please clear cache to login again');
            }else{
                $query->select('u.*')->from('users u')->where('u.`id`="'.(int)$uid.'"');
                $jUser =  $query->get()->row();
                if(!$jUser){
                    $fields = Checkmydrive::getDbo(true)->list_fields('users');
                    $jUser = new stdClass();
                    foreach ($fields as $f) $jUser->$f = null;
                }
            }
            if(isset($jUser->params)) $jUser->params = json_decode ($jUser->params);
            $user[$uid] = $jUser;
        }
        return $user[$uid];
    }
    
    
    public static function getStateFromRequest($key, $name, $default = null){
        $cur_state = self::getState($key);
        $new_state = self::input()->post_get($name);

        // Save the new value only if it was set in this request.
        if ($new_state !== null) {
                self::setState($key, $new_state);
        } else if($cur_state) {
                $new_state = $cur_state;
        }else $new_state = $default;
        return $new_state;
    }
    
    public static function setState($key, $value){
        $CI = get_instance();
        $CI->session->set_userdata($key, $value);
    }
    
    public static function getState($key, $default = ''){
        $CI = get_instance();
        $value =  $CI->session->userdata($key);
        return ($value || $value=='0' || $value===0) ? $value : $default;
    }
    
    
    public static function select($name, $options, $id = '', $class = '',$submit= true, $attr = ''){
        ob_start();
        ?>
        <select name="<?php echo $name; ?>" id="<?php echo ($id)? $id : $name;?>" class="span12 small chzn-done <?php echo $class; ?>" 
            <?php echo ($submit)? 'onchange="this.form.submit()"' : ''; ?>
            <?php if($attr) echo $attr; ?>
        >
            <?php echo is_array($options)? implode('', $options) : $options;?>
        </select>
        <?php
        return ob_get_clean();
    }
    
    public static function options($options, $default = ''){
        ob_start(); 
        foreach ($options as $option){ ?>
            <?php 
            $selected = '';
            if(is_array($default)){
                if(in_array($option->value, $default)) $selected = 'selected="selected"';
            }else if($default === $option->value) $selected = 'selected="selected"';
            else if($option->value === 0){
                if($default=='0' || $default === 0){
                    $selected = 'selected="selected"';
                }else if($default === ''){
                    $default = '';
                }
            }else if($option->value !=0){
                if($default == $option->value) $selected = 'selected="selected"';
            }
            
            ?>
            <option <?php echo $selected; ?> value="<?php echo $option->value; ?>"><?php echo $option->text; ?></option>
        <?php } 
        return ob_get_clean();
    }
    
    public static function uri(){
        $uri = Checkmydrive::rsegments();
        $get = array(
            'view' => isset($uri[1])?$uri[1]:'',
            'layout' => isset($uri[2])?$uri[2]:'',
            'id' => isset($uri[3])?$uri[3]:0,
        );
        return (object)$get;
    }
    
    public static function pagination($url, $total, $perpage =  20){
        $config = array(
            'base_url' => Checkmydrive::route($url . '/index'),
            'total_rows' => $total,
            'per_page' => $perpage,
            'uri_segment' => self::$pagination,
            'full_tag_open' => '<ul class="pagination-list">',
            'full_tag_close' => '</ul>',
            'first_tag_open' => '<li>',
            'first_tag_close' => '</li>',
            'cur_tag_open' => '<a class="active">',
            'cur_tag_close' => '</a>',
        );
        $CI = get_instance();
	return $CI->pagination->initialize($config); 
    }
    
    public static function sort($title, $field = '', $listDirn='', $listOrder=''){
        if(!$listDirn || $listDirn=='asc'){
            $listDirn = 'desc';
            $icon = '<i class="icon-arrow-up-3"></i>';
        }
        else{
            $listDirn = 'asc';
            $icon = '<i class="icon-arrow-down-3"></i>';
        }
        if($field !== $listOrder)$icon ='';
        return '<a class="hasTooltip" title="" onclick="jSont.tableOrdering(\''.$field.'\',\''.$listDirn.'\',\''.$listOrder.'\');return false;" href="#" data-original-title="">'
                . self::_($title)
                . $icon .
                '</a>';
       
    }
    public static function checkall(){
        return '<input class="hasTooltip" type="checkbox" onclick="jSont.checkAll(this)" title="" value="" name="checkall-toggle" data-original-title="Check All">';
    }
    
    public static function checkbox($i, $id){
        return '<input id="cb'.$i.'" type="checkbox" onclick="jSont.isChecked(this.checked);" value="'.$id.'" name="cid[]">';
    }
    
    public static function setMessage($msg, $type = 'info'){
        $CI = get_instance();
        $message = $CI->session->userdata('message');
        if(!$message) $message = array();
        $message[md5($msg)][$type] = $msg;
        $CI->session->set_userdata('message',$message);	
    }
    
    public static function getMessage(){
        $CI = get_instance();
        $message = $CI->session->userdata('message');
        $CI->session->unset_userdata('message');
        return $message;
    }
    
    
    public static function printMessage(){
        if($message = self::getMessage()){
            ?>
            <div id="message" class="alert alert-info">
                <?php foreach ($message as $msg) foreach($msg as $t=>$m){ ?>
                    <div class="<?php echo $t; ?> <?php echo $t; ?>-info"><i class="fa fa-<?php echo $t; ?>"></i><?php echo $m; ?></div>
                <?php } ?>
            </div>
            <?php
        }
    }
    
    
    public static function load($table, $id){
        static $load;
        $key = md5($table . $id);
        if(!isset($load[$key])){
            $db = self::getDbo();
            $row = $db->query("SELECT * FROM clientrol_{$table} WHERE id='$id'");
            $load[$key] = $row->row();
        }
        return $load[$key];
    }
    
    public static function delete($table, $id = array()){
        if(!is_array($id)) $id = array($id);
        $db = self::getDbo();
        $db->where('id IN (' . implode(',', $id) . ')');
        return $db->delete($table);
    }
    
    public static function getUploader(){
        $uploader = 'self';
        if(self::isGoogle()) $uploader = 'Google';
        if(self::isDropbox()) $uploader = 'JDropbox';
        return $uploader;
    }
    
    public static function getTypeUploader(){
        $type = 'local';
        if(self::isGoogle()) $type = 'google';
        if(self::isDropbox()) $type = 'dropbox';
        return $type;
    }
    
    public static function isDropbox(){
        static $tt;
        if(!isset($tt)){
            $user = Checkmydrive::getUser(Checkmydrive::getCreatedById());
            if(isset($user->params->use)){
                if($user->params->use == 'dropbox') $tt = true;
            }else $tt = false;
        }
        return $tt;
    }
    public static function isGoogle(){
        static $tt;
        if(!isset($tt)){
            $user = Checkmydrive::getUser(Checkmydrive::getCreatedById());
            if(isset($user->params->use)){
                if($user->params->use == 'google') $tt = true;
            } else $tt = false;
        }
        return $tt;
    }
     public static function moveUpload($source, $name ){
         $uploader = Checkmydrive::getUploader();
         if($uploader){
             $fileID = $uploader::upload($source, $name );
             return $fileID;
         }
         return false;
     }
     
     public static function getFile(&$row){
         if($row->type == 'google' || $row->type == 'dropbox'){
             $file = json_decode($row->fileID);
             $file->download = $row->download = $row->url = $file->url; 
         }else{
             $file = (object) array(
                'url' => Checkmydrive::root().'images/clientrol/attach/'.Checkmydrive::getFolder().$row->savename,
                'download' => Checkmydrive::route('files/download?file='.urlencode(Checkmydrive::root().'images/clientrol/attach/'.Checkmydrive::getFolder().$row->savename).'&name='.urlencode($row->filename))
             );
         }
         return $file;
     }
     
     public static function deleteFile($row){
        $uploader = Checkmydrive::getUploader();
         if(self::isGoogle()) return Google::delete ($row->fileID);
         if(self::isDropbox()) return JDropbox::delete ($row->fileID);
         return false;
    }
     
     
    //$file name of input
    //path to upload
    //name of new file
    public static function upload($file, $path, $name ){
        $CI = get_instance();
        $file_ext =  array(
            'archive' => array('7z', 'ace', 'bz2', 'dmg', 'gz', 'rar', 'tgz', 'zip'),
            'document' => array('csv', 'doc', 'docx', 'html', 'key', 'keynote', 'odp', 'ods', 'odt', 'pages', 'pdf', 'pps', 'ppt', 'pptx', 'rtf', 'tex', 'txt', 'xls', 'xlsx', 'xml'),
            'image' => array('bmp', 'exif', 'gif', 'ico', 'jpeg', 'jpg', 'png', 'psd', 'tif', 'tiff'),
            'audio' => array('aac', 'aif', 'aiff', 'alac', 'amr', 'au', 'cdda', 'flac', 'm3u', 'm3u', 'm4a', 'm4a', 'm4p', 'mid', 'mp3', 'mp4', 'mpa', 'ogg', 'pac', 'ra', 'wav', 'wma'),
            'video' => array('3gp', 'asf', 'avi', 'flv', 'm4v', 'mkv', 'mov', 'mp4', 'mpeg', 'mpg', 'ogg', 'rm', 'swf', 'vob', 'wmv')
        );
        $ext = array();
        foreach ($file_ext as $eee) foreach ($eee as $ee) $ext[] = $ee;
        $config['upload_path'] = $path;
        $config['file_name'] = $name;
        $config['allowed_types'] = implode('|', $ext);
        $config['max_size']	= '1024000000';
        $config['max_width']  = '20460';
        $config['max_height']  = '10240';
        $CI->load->library('upload');
        $CI->upload->initialize($config);
        return $CI->upload->do_upload($file);
        //k($CI->upload->display_errors());
    }
    
    
    public static function resize($source, $with = 200, $height = 200){
        $config['image_library'] = 'gd2';
        $config['source_image'] = $source;
        $config['create_thumb'] = false;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = $with;
        $config['height']       = $height;
        $CI = get_instance();
        $CI->load->library('image_lib');
        $CI->image_lib->initialize($config);
        return $CI->image_lib->resize();
    }
    
    
    public static function calendar(){
        static $load;
        if(!isset($load)){
            $load = true;
            ?>
            <link rel="stylesheet" href="<?php echo self::urlTheme(); ?>assets/css/datepicker3.css" type="text/css" />
            <script src="<?php echo self::urlTheme(); ?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
            <?php
        }
    }
    
   
    
    public static function addScript($script){
        ?>
            <script>
                <?php echo $script; ?>
            </script>
        <?php
    }
    
    public static function publish($table, $cid, $state = 0){
        if(!is_array($cid)) $cid = array($cid);
        $db = Checkmydrive::getDbo();
        $db->where('id IN (' . implode(',', $cid) . ')' );
        $db->set('status', $state);
        $db->update($table);
    }
    
    public static function getMailer(){
        $CI = get_instance();
        $CI->load->library('Jsmtp');
        $config = Checkmydrive::getConfigs();
        $mail = Jsmtp::getSMTP($config);
        return $mail;
    }
    
    
    public static function sendMail($from, $fromName, $to, $subject, $body, $html = true){
        $CI = get_instance();
        $CI->load->library('JSont');
        $config = Checkmydrive::getConfigs(true);
        $configMail = array(
            'Port' => $config->smtp_port,
            'SMTPSecure' => $config->smtp_secure,
            'Host' => $config->smtp_host,
            'Username' => $config->smtp_user,
            'Password' => $config->smtp_pass,
            'From' => $config->smtp_user,
            'jksmtp' => 'http://www.kieuan.info/jksmtp/smtp.php'
        );
        // SEND BY SERVER JOMKUNGFU
        //JSont::init($configMail);
        //$msg = JSont::sendMail($from, $fromName, $to, $subject, $body);
        //if($msg != 'OK') Checkmydrive::setMessage($msg);
        $mail = self::getMailer();
        if($from && $fromName) $mail->setFrom($from, $fromName);
        $mail->addAddress($to); // to email
        $mail->Subject = $subject;
        $mail->msgHTML($body);
        if(!$mail->send()){
            k($mail->ErrorInfo);
            die;
        }
        return true;
    }
    
    
     public static function tscript($text){
         return $text;
     }
     
    
     public static function  scriptLang(){
         if(self::isPageAdmin()){
            $lang = array(
                'CHECKMYDTIVE_MSG_NEW_FOLDER_NAME_REQUIRED',
               'CHECKMYDTIVE_MSG_PARENT_DIRECTORY_REQUIRED',
               'CHECKMYDTIVE_MSG_CANT_DELETE_ROOT',
               'CHECKMYDTIVE_NEW_COMMENT',
               'CHECKMYDTIVE_MSG_PLAN_SETTINGS_UPDATED_BY_USER',
               'CHECKMYDTIVE_MSG_PLEASE_COMPLETE_REQUIRED_FIELDS',
               'CHECKMYDTIVE_MSG_CONFIRM_PASSWORD_ERROR',
               'CHECKMYDTIVE_MSG_FOLDER_REQUIRED',
               'CHECKMYDTIVE_UPLOAD_ATTACH_FILE',
               'CHECKMYDTIVE_MSG_MESSAGE_REQUIRED',
               'CHECKMYDTIVE_REPLY',
               'CHECKMYDTIVE_DELETE_MESSAGE',
               'CHECKMYDTIVE_NEW_MESSAGE',
               'CHECKMYDTIVE_MSG_SUBJECT_REQUIRED',
               'CHECKMYDTIVE_ADD_COMMENT',
               'CHECKMYDTIVE_DELETE_COMMENT',
               'CHECKMYDTIVE_SUBJECT',
               'CHECKMYDTIVE_MESSAGE',
               'CHECKMYDTIVE_ATTACHMENTS',
               'CHECKMYDTIVE_DESC_PLEASE_SUBMIT_YOUR_SUPPORT_REQUEST',
            );
         }else{
             $lang = array(
                 'CHECKMYDTIVE_NEW_COMMENT',
                'CHECKMYDTIVE_DELETE_COMMENT',
                'CHECKMYDTIVE_UPLOAD_ATTACH_FILE',
                'CHECKMYDTIVE_REPLY',
                'CHECKMYDTIVE_DELETE_MESSAGE',
                'CHECKMYDTIVE_MESSAGE',
                'CHECKMYDTIVE_MSG_SUBJECT_REQUIRED',
                'CHECKMYDTIVE_NEW_MESSAGE',
                'CHECKMYDTIVE_CHANGE_AVATAR',
                'CHECKMYDTIVE_EDIT_PRIVATE_INFO',
                'CHECKMYDTIVE_EDIT_PROFILE',
                'CHECKMYDTIVE_MSG_PLEASE_COMPLETE_REQUIRED_FIELDS',
                'CHECKMYDTIVE_MSG_CONFIRM_PASSWORD_ERROR',
                'CHECKMYDTIVE_ADD_COMMENT',
                'CHECKMYDTIVE_MSG_MESSAGE_REQUIRED',
                'CHECKMYDTIVE_MSG_FIELD_REQUIRED',
                'CHECKMYDTIVE_NEW_TICKET',
             );
         }
         $js = array();
         foreach ($lang as $l){
             $js[$l] = self::_($l);
         }
         self::addScript('var lang=' . json_encode($js));
     }
     
     public static function setSessions($sid, $userid = null ){
        $db = Checkmydrive::getDbo(true);
        if(!$userid) $userid = Checkmydrive::getUser ()->id;
        if(!$userid) return;
        //delete user have not access 5minute
        $time = time() - 300; //5 minute prev
        $db->where('last_activity < ' . $time)->delete('ci_sessions');
        //check user online
        $query = $db->query('SELECT session_id FROM ci_sessions WHERE userid='. $userid);
        if(isset($query->row()->session_id)){
           $db->where('userid', $userid)->update('ci_sessions', array('session_id'=> $sid, 'userid'=> $userid, 'last_activity'=> time()));
        }else  $db->insert('ci_sessions', array('session_id'=> $sid, 'userid'=> $userid, 'last_activity'=> time()));
     }
     
     public static function clearSessions($userid = null){
        if(!$userid) $userid = Checkmydrive::getUser ()->id;
        if(!$userid) return;
        Checkmydrive::getDbo(true)->where('userid', $userid)->delete('ci_sessions');
     }
     
  
     
     public static function textSub($key = 0, $master = false){
         $config = Checkmydrive::getConfigs($master);
         $text =  array(
             0 => self::_('Free Trial (30 Days Expiry) '),
             1 => self::_('Monthly Subscription ($'. (int) $config->subscription.' per month)'),
             12 => self::_('Annual Subscription Save 10% ($'. (int) ($config->subscription * 12 * 90 / 100 ).' per year)'),
         );
         if(isset($text[$key])) return $text[$key];
         return $text[0];
     }
     
     public static function createSelect($default = 0){
         
         ?>
        <select name="subscription" id="subscription" class="input-medium">
            <option value="0" <?php if ($default == '0') echo 'selected="selected"'; ?>><?php echo self::textSub();?></option>
            <option value="1" <?php if ($default == '1') echo 'selected="selected"'; ?>><?php echo self::textSub(1);?></option>
            <option value="12" <?php if ($default == '12') echo 'selected="selected"'; ?>><?php echo self::textSub(12);?></option>
        </select>
        <?php
     }
     
     public static function checkSubscriber($userid = 0){
         if(!$userid) $userid = Checkmydrive::getUser ()->id;
         if(self::isSuperUser($userid)) return false;
         $user = Checkmydrive::getUser($userid);
         if(!$user->subscriber_end) return false;
         $date = new DateTime($user->subscriber_end);
         if($date->getTimestamp() > time()){
             if($user->ban_reason){
                 $db = Checkmydrive::getDbo(true);
                 $db->set('banned',0)->set('ban_reason','')->where('created_by', $user->id)->update('users');
                 $db->set('ban_reason','')->where('id', $user->id)->update('users');
             }
             return true;
         }else{
             if(!$user->ban_reason){
                 // ban all user
                 $db = Checkmydrive::getDbo(true);
                 $db->set('banned',1)->set('ban_reason','The Subscription of Admin have been expied.')->where('created_by', $user->id)->update('users');
                 $db->set('ban_reason','all client banned because you have been expired')->where('id', $user->id)->update('users');
             }
             return false;
         }
     }
     
    public static function isSubscriber($userid = null){
        if(!$userid) $user = Checkmydrive::getUser ();
        else $user = Checkmydrive::getUser ($userid);
		static $sub;
		if(empty($sub[$userid])){
                    if(!self::checkSubscriber($userid)){
                            $sub[$userid] = false;
                            return false;
                    }
                    $sub[$userid] = true;
		}
        return $sub[$userid];
        
    }

     
     public static function isRedirectAdmin($level = null){
         $views = array('admin', 'superadmin');
         $segments = Checkmydrive::segments();
         if(in_array($segments[2], $views)) return true;
         if(in_array($segments[1], $views)) return true;
         return false;
     }
     
     
}
/**
 * Checkmydrive helper.
 */

class CheckmydriveHelper
{
    //Back-end
    public static $filters;
    
    
    public static function getSidebar($view = false){
        $menu =  array(
            array('name'=>'dashboard', 'label'=>Checkmydrive::_('Dashboard'), 'icon'=>'fa fa-ct-dashboard'),
            array('name'=>'jusers', 'label'=>Checkmydrive::_('Users'), 'icon'=>'fa fa-ct-contact'),
            array('name'=>'settings', 'label'=>Checkmydrive::_('CHECKMYDTIVE_TITLE_SETTINGS'), 'icon'=>'fa fa-cog'),
            
        );
        return $menu;
    }
    
    public static function getSiderBnt($bnt = array('new', 'edit', 'del')){
        ?>
        <div id="toolbar" class="btn-toolbar">
            <?php //if(self::canCreate()){ ?>
                <?php if(in_array('new', $bnt)){ ?>
                <div id="toolbar-new" class="btn-wrapper">
                        <button class="btn btn-small btn-success" onclick="jSont.submitbutton('new'); " type="button">
                        <span class="icon-new icon-white"></span> New</button>
                </div>
                <?php } ?>
            <?php //} ?>
            <?php if(in_array('edit', $bnt)){ ?>
            <div id="toolbar-edit" class="btn-wrapper">
                    <button class="btn btn-small" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ jSont.submitbutton('edit')}" type="button">
                    <span class="icon-edit"></span> Edit</button>
            </div>
            <?php } ?>
            <?php if(in_array('new', $bnt)){ ?>
            <div id="toolbar-delete" class="btn-wrapper">
                    <button class="btn btn-small" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ jSont.submitbutton('delete')}" type="button">
                    <span class="icon-delete"></span> Delete</button>
            </div>
            <?php } ?>
            
            
            <?php if(in_array('apply', $bnt)){ ?>
            <div id="toolbar-apply" class="btn-wrapper">
                <button class="btn btn-small btn-success" onclick="jSont.submitbutton('apply')" type="button">
                    <span class="icon-apply icon-white"></span>Save
                </button>
            </div>
            <?php } ?>
            <?php if(in_array('save', $bnt)){ ?>
            <div id="toolbar-save" class="btn-wrapper">
                    <button class="btn btn-small" onclick="jSont.submitbutton('save')" type="button">
                        <span class="icon-save"></span> Save &amp; Exit
                    </button>
            </div>
            <?php } ?>
            <?php if(in_array('cancel', $bnt)){ ?>
            <div id="toolbar-cancel" class="btn-wrapper">
                    <button class="btn btn-small" onclick="jSont.submitbutton('cancel')" type="button">
                        <span class="icon-cancel"></span>Exit
                    </button>
            </div>
            <?php } ?>
        </div>    
        <?php
    }

    public static function addFilter($datafiled){
        static::$filters[] = $datafiled;
    }
    
    public static function renderFilter(){
        if(!isset(static::$filters)) return '';
        ob_start();
        ?>
            <?php foreach (static::$filters as $filter){ ?>
                <div class="btn-group pull-right hidden-phone"><?php echo $filter; ?> </div>   
            <?php } ?>
        <?php
        return ob_get_clean();
    }
    
     public static function addAdminSideBar($filter=''){
        $input = Checkmydrive::input();
        $request = Checkmydrive::uri();
        $users = self::getOnlineUser();
        $user = self::getUser();
        $configs = self::getConfigs();
        $sidebars = self::getSidebar();
        ob_start();?>
        <div class="main-header">
            <!-- logo -->
            <a href="" class="logo"><img src="<?php echo ($configs->logo)? Checkmydrive::root() . $configs->logo : Checkmydrive::urlTheme().'assets/images/logo.png'?>" alt="Logo" style="height: 50px"/></a>
            <nav class="navbar navbar-static-top">
               
                <div class="pull-right image">
                        <img src="<?php echo @$user->avatar?>" alt="Avatar" style="height: 50px" />
                        <p>
                                <a href="<?php echo Checkmydrive::route('profile');?>"><?php echo Checkmydrive::_('Account Profile')?></a> <a href="<?php echo Checkmydrive::route('logout'); ?>"><?php echo Checkmydrive::_('CHECKMYDTIVE_LOGOUT')?></a>
                        </p>
                </div>    
                
            </nav>               
            
        </div><!-- header -->
        <div class="main-sidebar">
            <div class="sidebar ">
                <div class="user-panel">
                        <div class="pull-left info">
                            <h3><?php echo 'Welcome '. $user->name?></h3>
                            <small><?php echo $user->email?></small>
                        </div>
                </div>
                    <?php
                        $sidebars = self::buildChild($sidebars);
                        ?>
                        <ul class="sidebar-menu">
                         <li class="headerli"><?php echo Checkmydrive::_('CHECKMYDTIVE_MAIN_NAVIGATION')?></li>   
                            <?php foreach($sidebars as $item){?>
                                <?php
                                $li_class =""; $i_class="";
                                if($request->view == $item['name']){
                                     $li_class .="active";
                                     $i_class .= "current";
                                }                                                         
                                $li_class .= isset($item['child']) ? " treeview" : '';
                                ?>
                                <li class="<?php echo  $li_class;?>">
                                    <a href="<?php echo Checkmydrive::route ($item['name']); ?>">
                                        <i class="<?php echo $item['icon'];?>"></i><span><?php echo $item['label'];?></span>                         
                                    </a>
                                     <?php if(isset($item['child']) ){ ?> <i class="fa fa-angle-left pull-right <?php echo $i_class; ?>" data-toggle-menu=".treeview-menu"></i> <?php } ?>
                                    <?php if(isset($item['child'])){ ?>
                                            <ul class="treeview-menu menu-<?php echo $item['name']; ?>">
                                            <?php foreach ($item['child'] as $mm){ ?>
                                                    <li class="<?php if($request->view == $mm['name']) echo 'active';?>">
                                                            <a href="<?php echo Checkmydrive::route ($mm['name']); ?>">
                                                                    <i class="<?php echo $mm['icon'];?>"></i><span><?php echo $mm['label'];?></span>
                                                            </a>
                                                    </li>
                                            <?php }?>
                                            </ul>
                                    <?php } ?>
                                </li>
                            <?php }?>
                        </ul>
                        <ul class="sidebar-menu">
                            <li class="headerli"><?php echo Checkmydrive::_('CHECKMYDTIVE_WHO_ONLINE')?></li>  
                            <?php foreach($users as $user){?>
                                <li class="online-user">
                                    <img width="25px" class="userpic" src="<?php echo @$user->avatar?>">                                    
                                    <span class="online-username">
                                    <?php echo $user->name;?>&nbsp;&nbsp;<img src="<?php echo Checkmydrive::urlTheme().'assets/images/online.png';?>">
                                    </span>
                                </li>
                            <?php }?>
                        </ul>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

	public static function buildChild($menus){
		$parent = array();
		foreach ($menus as $m){
			if(!isset($m['parent'])) $parent[] = $m;
		}
		foreach ($menus as $mm){
			foreach ($parent as &$p){
				if( (isset($mm['parent'])) && $mm['parent'] == $p['name'] ){
					$p['child'][] = $mm;
				}
			}
		}
		return $parent;
	}
	
    public static function getBreadcrumbs(){
        $input = Checkmydrive::input();
        $uri = Checkmydrive::uri();
        $view = $uri->view;
        $sidebars = self::getSidebar();
        
        ob_start();
        $br = array();
        foreach($sidebars as $item) if($item['name'] == $view) $br[] = $item;
        if($view == 'dashboard' && (int)$input->get_post('client')){
            $br[] = array('name'=>'favourite', 'label'=>'Favourite');
        }
        if( (isset($br[0])) && isset($br[0]['parent'])) foreach($sidebars as $item) if($item['name'] == $br[0]['parent']){
            $item['parent'] = null;
            array_unshift($br, $item);
        }
        ?>
        <div class="breadcrumbs">
            <a href="<?php echo Checkmydrive::route('dashboard'); ?>"><?php echo Checkmydrive::_('CHECKMYDTIVE_TITLE_DASHBOARD')?></a>

            <?php if($view != 'dashboard') foreach($br as $k=>$item){?>
                <?php if($k<count($br)-1){?>
                    <a href="<?php echo Checkmydrive::route($item['name']); ?>"><?php echo $item['label'];?></a>
                <?php }else{ ?>
                    <span><?php echo $item['label'];?></span>
                <?php }?>
            <?php }?>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    public static function addAsset(){
        
        $CI = get_instance();
        $rsegments = Checkmydrive::rsegments();
        $input = $CI->input;
        $script = $CI->javascript;
        echo $script->external(Checkmydrive::urlTheme(true).'assets/js/jquery.js');
        echo $script->external(Checkmydrive::urlTheme(true).'assets/js/jquery.noconflict.js');
        echo $script->external(Checkmydrive::urlTheme(true).'assets/js/jsont.js');
        echo $script->external(Checkmydrive::urlTheme(true).'assets/js/chosen.jquery.min.js');
        // pnotify
        echo link_tag(Checkmydrive::urlTheme(true).'assets/pnotify/pnotify.custom.min.css');
        echo link_tag(Checkmydrive::urlTheme(true).'assets/css/chosen.min.css');
        echo $script->external(Checkmydrive::urlTheme(true).'assets/pnotify/pnotify.custom.min.js');
        
        // font-awesome
        //echo link_tag('http://fonts.googleapis.com/css?family=Open+Sans');
        echo link_tag(Checkmydrive::urlTheme(true).'assets/font-awesome/css/font-awesome.min.css');

        echo link_tag(Checkmydrive::urlTheme(true).'assets/css/general.css');
       // echo link_tag(Checkmydrive::urlTheme(true).'assets/bootstrap/css/bootstrap-responsive.css');
        
        if(Checkmydrive::isPageAdmin()){
            echo link_tag(Checkmydrive::urlTheme(true).'assets/css/clientrol.css');
            echo link_tag(Checkmydrive::urlTheme(true).'assets/css/AdminLTE.css');
            echo $script->external(Checkmydrive::urlTheme(true).'assets/bootstrap/js/bootstrap.min.js');
            echo link_tag(Checkmydrive::urlTheme().'assets/themeforest-admin/css/style.css');
            echo link_tag(Checkmydrive::urlTheme().'assets/css/fix-themeforest.css');
        }else{
            echo link_tag(Checkmydrive::urlTheme().'assets/css/template.css');
            echo link_tag(Checkmydrive::urlTheme(true).'assets/bootstrap/css/bootstrap.min.css');
            echo link_tag(Checkmydrive::urlTheme().'assets/css/clientrol.css');
        }
    }

	
    public static function getOnlineUser(){
        $where = '';
        //need fixed
        $query	= Checkmydrive::getDbo(true)
            ->select('DISTINCT(userid), us.name')
            ->from('ci_sessions AS a')
            ->join('users us','us.id=a.userid','LEFT')
            //->join('clientrol_contacts ct', 'ct.id=a.userid','LEFT')
            ->where('a.userid<>0');
        $users = $query->get()->result_object();
        return $users;
    }


    public static function getStatusOptions($default = false){
        $options = array();
        if($default){
            $options[] = (object)array('value' => '', 'text' => Checkmydrive::_('- Select Status -')) ;
            $options[] = (object)array('value' => 1, 'text' => Checkmydrive::_('JPUBLISHED')) ;
            $options[] = (object)array('value' => 0, 'text' => Checkmydrive::_('JUNPUBLISHED')) ;
        }else{
            $options[] = (object)array('value' => 0, 'text' => Checkmydrive::_('CHECKMYDTIVE_STATUS_OPEN')) ;
            $options[] = (object)array('value' => 1, 'text' => Checkmydrive::_('CHECKMYDTIVE_STATUS_DONE')) ;
        }
        return $options;
    }

    //General
    public static function getConfigs(){
        static $registry;
        if(!isset($registry)){
            $db = Checkmydrive::getDbo(true);
            $row = $db->query('SELECT configs FROM configs WHERE id=1')->row()->configs;
            $config = json_decode($row);
            Checkmydrive::loadForm();
            $registry = new JRegistry($config);
            foreach ($config as $k=>$v) $registry->$k = $v;
            $registry->limit = 20;
        }
        return $registry;
    }

    
    public static function getUser($id=0){
        return Checkmydrive::getUser($id);
    }


    public static function cutString($str, $limit=100, $endChar='...'){
        if(strlen($str)<=$limit) return $str;
        if(strpos($str," ",$limit) > $limit){
            $new_limit=strpos($str," ",$limit);
            $new_str = substr($str,0,$new_limit).$endChar;
            return $new_str;
        }
        $new_str = substr($str,0,$limit).$endChar;
        return $new_str;
    }


    //Front-end
    public static function buildSidebar(){
       

            $sidebars = array(
                array('name'=>'homepage', 'label'=>Checkmydrive::_('DASHBOARD')),
                array('name'=>'google', 'label'=>Checkmydrive::_('GOOGLE DRIVE')),
                array('name'=>'dropbox', 'label'=>Checkmydrive::_('DROPBOX')),
                array('name'=>'profile', 'label'=>Checkmydrive::_('MY ACCOUNT')),
            );
            $children = array(
                'google' => array(
                    array('name'=>'google/app', 'label'=>Checkmydrive::_('APPS WITH ACCESS')),
                    array('name'=>'google/user', 'label'=>Checkmydrive::_('USERS WITH ACCESS')),
                    array('name'=>'google/folder', 'label'=>Checkmydrive::_('PUBLIC FILES / FOLDERS')),
                    array('name'=>'google/files', 'label'=>Checkmydrive::_('EMPTY FILES')),
                ),
                'dropbox' => array(
                    array('name'=>'dropbox/app', 'label'=>Checkmydrive::_('APPS WITH ACCESS')),
                    array('name'=>'dropbox/user', 'label'=>Checkmydrive::_('USERS WITH ACCESS')),
                    array('name'=>'dropbox/folder', 'label'=>Checkmydrive::_('PUBLIC FILES / FOLDERS')),
                    array('name'=>'dropbox/files', 'label'=>Checkmydrive::_('EMPTY FILES')),
                )
            );
            $uri = Checkmydrive::uri();
            ob_start();
            ?>
            <ul class="menuLeft">
                <?php foreach($sidebars as $item){ ?>
                    <li class="<?php if($uri->view == $item['name']) echo 'active';?>">
                        <a class="active" href="<?php echo Checkmydrive::route($item['name']);?>">
                            <?php echo $item['label'];?>
                        </a>
                        <?php if(isset($children[$item['name']])){ ?>
                            <ul class="children">
                            <?php foreach ($children[$item['name']] as $child){ ?>
                                <li>
                                    <a href="<?php echo Checkmydrive::route($child['name']);?>" > <?php echo $child['label'];?></a>
                                </li>
                            <?php } ?>
                            </ul>
                        <?php } ?>
                    </li>
                <?php }?>
                    <li>
                        <a class="active" href="<?php echo Checkmydrive::route('logout');?>">
                            <?php echo Checkmydrive::_('Logout'); ?>
                        </a>
                    </li>
            </ul>
            <?php if(!Checkmydrive::isSubscriber() && !Checkmydrive::isSuperUser()){ ?>
                <div class="alert alert-subscriber">
                    <i class="fa fa-info"></i>
                    Your Subscription has Expired, Please Renew to Continue.
                </div>
            <?php } ?>
            <?php
            return ob_get_clean();
        
    }

    public static function formatNumber($number, $digit=4){
        return str_pad($number, $digit, '0', STR_PAD_LEFT);
    }

    public static function formatMoney($amount){
        return number_format((float)$amount, 2, '.', '');
    }

    
    
    public static function buildPaypalForm($return, $cancel_return, $item_name, $item_number, $amount, $currency_code, $icon, $submit_text, $userid = 0, $master = false){
		$configs = self::getConfigs($master);
        if(!$configs->paypal_account) return Checkmydrive::_('CHECKMYDTIVE_MSG_PAYPAL_ACCOUNT_NOT_CONFIG');

        if((int)$configs->paypal_sandbox) $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        else $url = 'https://www.paypal.com/cgi-bin/webscr';
        ob_start();
        ?>
        <form action="<?php echo $url;?>" method="post" class="ct_paypal_form">
            <input type="hidden" value="_xclick" name="cmd">
            <input type="hidden" value="utf-8" name="charset">
            <input type="hidden" value="<?php echo $return;?>" name="return">
            <input type="hidden" value="<?php echo $cancel_return;?>" name="cancel_return">
            <input type="hidden" value="<?php echo $configs->paypal_account;?>" name="business">
            <input type="hidden" value="2" name="rm">
            <input type="hidden" value="1" name="no_shipping">
            <input type="hidden" value="1" name="no_note">
            <input type="hidden" value="<?php echo ($configs->paypal_lang_code)?$configs->paypal_lang_code : 'US';?>" name="lc">
            <input type="hidden" value="<?php echo $item_name;?>" name="item_name">
            <input type="hidden" value="<?php echo $item_number;?>" name="item_number">
            <input type="hidden" value="<?php echo $amount;?>" name="amount">
            <input type="hidden" value="<?php echo $currency_code;?>" name="currency_code">
            <input type="hidden" value="<?php echo ($u = Checkmydrive::getUser())? $u->id : $userid;?>" name="custom">
            <button type="button" class="btn"><i class="<?php echo $icon;?>"></i> <?php echo $submit_text;?></button>
        </form>
        <?php
        return ob_get_clean();
    }


    public static function formatDate($date){
        $d = new DateTime($date);
        $configs = self::getConfigs();
        return $d->format(($configs->date_format)?$configs->date_format:'d/m/Y');
    }


    public static function getModel($model){
        static $models;
		$model = strtolower($model);
        if(!isset($models[$model])){
            $CI = get_instance();
            $CI->load->add_package_path(APPPATH . 'modules/'.$model);
            $CI->load->model($model.'_model');
            $models[$model] = $CI->{$model.'_model'};
        }
        return $models[$model];
    }


    public static function formatDateDiff($date){
        $date = new DateTime($date);
        $difference = $date->diff(new DateTime(Checkmydrive::getDate()->toSql));
        if($difference->y){
            if($difference->y == 1) return 'A year ago';
            return $difference->y.' years ago';
        }
        if($difference->m){
            if($difference->m == 1) return 'A month ago';
            return $difference->m.' months ago';
        }
        if($difference->d){
            if($difference->d == 1) return 'Yesterday';
            if($difference->d == 7) return 'A week ago';
            return $difference->d.' days ago';
        }
        if($difference->h){
            if($difference->h == 1) return 'A hour ago';
            return $difference->h.' hours ago';
        }
        if($difference->i){
            if($difference->i == 1) return 'A minute ago';
            return $difference->i.' minutes ago';
        }
        return 'Last moment';
    }

    
    
    public static function download($file, $name){
        $file = str_replace(Checkmydrive::root(), FCPATH.'/', $file);
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }

    public static function userIsAdmin($uid = 0){
        return Checkmydrive::isManager($uid);
    }

    public static function checkRequire(){
        $configs = self::getConfigs();
        if(!$configs->admin_email) Checkmydrive::setMessage(Checkmydrive::_('CHECKMYDTIVE_MSG_ADMIN_EMAIL_NOT_CONFIG'), 'warning');
        if(!$configs->paypal_account) Checkmydrive::setMessage(Checkmydrive::_('CHECKMYDTIVE_MSG_PAYPAL_ACCOUNT_NOT_CONFIG'), 'warning');
        $user = Checkmydrive::getUser();
        
    }

    public static function checkGoogleDrive(){
        $user = Checkmydrive::getUser();
        if(!isset($user->params->use) ){
            echo Checkmydrive::_('<div class="alert alert-info"><i class="fa fa-warning"></i>Please Add Google Drive, in <a href="'.Checkmydrive::route('settings').'">Settings</a></div>');
        }
    }
    

    public static function getDefaultEmailTemplate($type = 'message'){
        $CI = get_instance();
        $CI->load->library('Emailtemplate');
        return CheckmydriveEmailTemplateHelper::getDefaultTemplate($type);
    }

    
    
}