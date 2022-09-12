<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* CodeIgniter
*
* An open source application development framework for PHP 5.1.6 or newer
*
* @package		CodeIgniter
* @author		ExpressionEngine Dev Team
* @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
* @license		http://codeigniter.com/user_guide/license.html
* @link		http://codeigniter.com
* @since		Version 1.0
* @filesource
*/


// This function helps us to get the translated phrase from the file. If it does not exist this function will save the phrase and by default it will have the same form as given

function profile_detail() {
      $CI =& get_instance();

      $CI->load->library('session');

      // This only returns the id does not set it.
      return $CI->session->userdata(); 
}

if ( ! function_exists('get_phrase'))
{
    function get_phrase($phrase = '') {
        $CI	=&	get_instance();
        $CI->load->database();
        
        $language_code = $CI->db->get_where('settings' , array('key' => 'language'))->row()->value;
   
        $key = strtolower(preg_replace('/\s+/', '_', $phrase));

        $langArray = openJSONFile($language_code);
        if (array_key_exists($key, $langArray)) {
        } else {
            $langArray[$key] = ucfirst(str_replace('_', ' ', $key));
            $jsonData = json_encode($langArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents(APPPATH.'language/'.$language_code.'.json', stripslashes($jsonData));
        }

        return $langArray[$key];
    }
}

    function getSelectedLanguage(){
        $CI	=&	get_instance();
        $CI->load->database();

        $language_code = $CI->db->get_where('settings' , array('key' => 'language'))->row()->value;
        //print_r($language_code);
        return $language_code;
    }
    function get_language_code($lan) {
        $array = array(
            'english' => 'en',
            'arabic' => 'ar',
            );
            return $array[$lan];
    }
    
    function get_translate($text,$key){
        $language = $_COOKIE['LANGUAGE'];
        if(!isset($language)) {
            $language = get_language_code('english');
        }
        if($language == 'en') {
            $value = $text[$key];
        }
        else {
            $value = $text[$key.'_'.$language];
            if(empty($value)) {
                $value = $text[$key];
            }
        }
        return $value;
    }

// This function helps us to decode the language json and return that array to us
if ( ! function_exists('openJSONFile'))
{
    function openJSONFile($code)
    {
        $jsonString = [];
        if (file_exists(APPPATH.'language/'.$code.'.json')) {
            $jsonString = file_get_contents(APPPATH.'language/'.$code.'.json');
            $jsonString = json_decode($jsonString, true);
        }
        return $jsonString;
    }
}

// This function helps us to create a new json file for new language
if ( ! function_exists('saveDefaultJSONFile'))
{
    function saveDefaultJSONFile($language_code){
        $language_code = strtolower($language_code);
        if(file_exists(APPPATH.'language/'.$language_code.'.json')){
            $newLangFile 	= APPPATH.'language/'.$language_code.'.json';
            $enLangFile   = APPPATH.'language/english.json';
            copy($enLangFile, $newLangFile);
        }else {
            $fp = fopen(APPPATH.'language/'.$language_code.'.json', 'w');
            $newLangFile = APPPATH.'language/'.$language_code.'.json';
            $enLangFile   = APPPATH.'language/english.json';
            copy($enLangFile, $newLangFile);
            fclose($fp);
        }
    }
}

// This function helps us to update a phrase inside the language file.
if ( ! function_exists('saveJSONFile'))
{
    function saveJSONFile($language_code, $updating_key, $updating_value){
        $jsonString = [];
        if(file_exists(APPPATH.'language/'.$language_code.'.json')){
            $jsonString = file_get_contents(APPPATH.'language/'.$language_code.'.json');
            $jsonString = json_decode($jsonString, true);
            $jsonString[$updating_key] = $updating_value;
        }else {
            $jsonString[$updating_key] = $updating_value;
        }
        $jsonData = json_encode($jsonString, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        file_put_contents(APPPATH.'language/'.$language_code.'.json', stripslashes($jsonData));
    }
}

function hide_menu($name) {
    $permission = '';
    $user_id = $_SESSION['id'];
    //die(print_r($user_id));
    $CI	=&	get_instance();
    $CI->load->database();
    $role = $CI->db->get_where('admin' , array('id' => $user_id))->row()->role;
    if($role == '0') {
        $permission = 'all';
    }
    else { 
        $permission = $CI->db->get_where('role' , array('id' => $role))->row()->permission;
    }
    if ($permission == 'all' || strpos($permission, $name) !== false) {
        return true;
    }
    else {
        return false;
    }
}

// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */
