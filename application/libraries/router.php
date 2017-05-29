<?php
/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */

class jkRoute{
    
    public static $db;
    

    public static function init(){
        if(!self::$db){
            include_once (SYSDIR . '/database/DB.php');
            include_once (APPPATH . '/libraries/Checkmydrive.php');
            self::$db = DB();
        }
    }
    
    public static function buildRoute($route){
        //self::init();
        $route["login"] = $route["admin"] = $route["superadmin"] = 'auth/login';
        $route["register"] = $route["business"] = 'auth/register';
        $route["register_affiliates"] = 'auth/register';
        $route["register_social"] = 'auth/register';
        $route["register_ads"] = 'auth/register';
        $route["logout"] = 'auth/logout';
        $route["renew"] = 'auth/renew';
        $route["send_again"] = 'auth/send_again';
        $route["subscriber"] = 'auth/subscriber';
        $route["subscriber/(:any)"] = 'auth/subscriber/$1';
        $route["forgot_password"] = 'auth/forgot_password';
        $route["subscription"] = 'auth/subscription';
        $route["activate/(:any)/(:any)"] = 'auth/activate/$1/$2';
        $route["reset_email"] = 'auth/reset_email';
        $route["reset_password/"] = 'auth/reset_password';
        $route["reset_password/(:any)/(:any)"] = 'auth/reset_password/$1/$2';
        $route["cronjob"] = 'auth/cronjob';
        return $route;
    }
    
}