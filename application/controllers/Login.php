<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this -> load -> library('session');
        $this -> load -> helper('form');
        $this -> load -> helper('url');
        $this -> load -> database();
        $this -> load -> library('form_validation');
        $this -> load -> model('Comman_model');

    }

    public function index()
    {
        $this -> load ->view('login.php');
    }

    public function signup()
    {
        $this -> load -> view('common/head.php');
        $this -> load ->view('signup.php');
        $this -> load -> view('common/footer.php');
    }

    public function forgotpassword()
    {
        $this -> load -> view('common/head.php');
        $this->load ->view('forgotpassword.php');
        $this -> load -> view('common/footer.php');
    }

    public function process()  
    {  
       $this->load->dbutil();

        $prefs = array(     
            'format'      => 'zip',             
            'filename'    => 'my_db_backup.sql'
        );

        $backup =& $this->dbutil->backup($prefs); 

        $db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.zip';
        $save = './assets/images/gallery/'.$db_name;

        $this->load->helper('file');
        write_file($save, $backup); 


        $this->load->helper('download');
        force_download($db_name, $backup);
    }  
}