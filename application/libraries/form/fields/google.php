<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */


class JFormFieldGoogle extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'google';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
            $html = '';
            $user = Checkmydrive::getUser();
            $cGoogle = $cDropbox  = '';
            if(isset($user->params->use) && $user->params->use == 'dropbox') $cDropbox = 'selected';
            if(isset($user->params->use) && $user->params->use == 'google') $cGoogle = 'selected';
            $html .= '<div class="authFiles">';
            $debug = false;
            $html .= $this->getGoogle($cGoogle);
            $html .= '</div>';
            return $html;
	}
        
        public function getDropbox($class = ''){
            $user = Checkmydrive::getUser();
            $AuthUrl = $login = false;
            $dropbox = JDropbox::getDropbox();
            $AuthUrl = $dropbox->getAuthHelper()->getAuthUrl(Checkmydrive::root());
            $info = JDropbox::getInfo();
            ob_start();
            ?>
            <div class="dropboxFile <?php echo $class; ?>">
                
                <?php if($AuthUrl){ ?>
                    <script type="text/javascript">
                            jQuery(function($){
                                    $('.loginDropbox').click(function(){
                                        var d =  $('.dropboxFile');
                                        d.find('.profile span.fName').html('...');
                                        d.find('.profile span.fEmail').html('...');
                                        var  $auth_window = window.open($(this).attr('data'), "ServiceAssociate", 'width=800,height=600'),
                                            auth_poll = null;
                                            auth_poll = setInterval(function() {
                                            if ($auth_window.closed) {
                                                clearInterval(auth_poll);
                                                $.ajax({
                                                    url: "<?php echo Checkmydrive::route('settings/getDropboxInfo'); ?>",
                                                    dataType: 'json',
                                                    success: function(data){ 
                                                        if(data.email){
                                                            $('.loginDropbox').html('Login Successfull');
                                                            d.addClass('selected');
                                                            $('.googleDrive').hide();
                                                            d.find('.profile span.fName').html(data.name);
                                                            d.find('.profile span.fEmail').html(data.email);
                                                            d.find('.profile').fadeIn();
                                                        } else $('.loginDropbox').html('Login Fail');
                                                    }
                                                });
                                            }
                                        }, 100);
                                    })
                            });
                    </script> 
                    <a class="loginDropbox" href="javascript:void(0)" data="<?php echo $AuthUrl; ?>" >Add Dropbox</a>
                <?php } else{ ?>
                    <a class="logoutDropbox" href="javascript:void(0)" data="<?php echo $AuthUrl; ?>" >Logined</a>
                <?php } ?>
                <div class="profile <?php echo @$info->class; ?>">
                    <fieldset>
                        <legend><?php echo Checkmydrive::_('Dropbox Info'); ?></legend>
                        <p><span><?php echo Checkmydrive::_('Name'); ?>: </span><span class="fName"><?php echo @$info->name; ?></span></p>
                        <p><span><?php echo Checkmydrive::_('Email'); ?>: </span><span class="fEmail"><?php echo @$info->email; ?></span></p>
                    </fieldset>
                </div>
            </div>
            <?php
            return ob_get_clean();
        }
        
        
        public function getGoogle($class = ''){
            $user = Checkmydrive::getUser();
            $client = Google::getClient();
            $AuthUrl = $client->createAuthUrl();
            $info = Google::getInfo();
            ob_start();
                ?>
                <div class="googleDrive <?php echo $class; ?>">
                    

                    <?php if($AuthUrl && empty($user->params->google->token)){ ?>
                        <script type="text/javascript">
                                jQuery(function($){
                                        $('.loginGoogle').click(function(){
                                            var g =  $('.googleDrive');
                                            g.find('.profile span.fName').html('...');
                                            g.find('.profile span.fEmail').html('...');
                                            var  $auth_window = window.open($(this).attr('data'), "ServiceAssociate", 'width=800,height=600'),
                                                auth_poll = null;
                                                auth_poll = setInterval(function() {
                                                if ($auth_window.closed) {
                                                    clearInterval(auth_poll);
                                                    $.ajax({
                                                        url: "<?php echo Checkmydrive::route('settings/getGoogleInfo'); ?>",
                                                        dataType: 'json',
                                                        success: function(data){ 
                                                            if(data.email){
                                                                $('.loginGoogle').html('Login Successfull');
                                                                g.addClass('selected');
                                                                //$('.dropboxFile').hide();
                                                                g.find('.profile span.fName').html(data.name);
                                                                g.find('.profile span.fEmail').html(data.email);
                                                                g.find('.profile').fadeIn();
                                                            } else $('.loginGoogle').html('Login Fail');
                                                        }
                                                    });
                                                }
                                            }, 100);
                                        })
                                });
                        </script> 
                        <a class="loginGoogle" href="javascript:void(0)" data="<?php echo $AuthUrl; ?>" >Add GoogleDrive</a>
                    <?php }else { ?>
						<a class="loginGoogle" href="javascript:void(0)" >Added GoogleDrive</a>
					<?php } ?>
                    <div class="profile <?php echo $info->class; ?>">
                        <fieldset>
                            <legend><?php echo Checkmydrive::_('Google Info'); ?></legend>
                            <p><span><?php echo Checkmydrive::_('Name'); ?>: </span><span class="fName"><?php echo @$info->name; ?></span></p>
                            <p><span><?php echo Checkmydrive::_('Email'); ?>: </span><span class="fEmail"><?php echo @$info->email; ?></span></p>
                        </fieldset>
                    </div>
                </div>
                <?php
            return ob_get_clean();
        }
}