<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Flutterwave extends CI_Controller
{
  /*For localhost*/
 
 // public $sidebar= 'common/sidebarlocal.php';
    /*For sever*/
  public $sidebar='common/sidebar.php';
    public function __construct()
    {
        parent::__construct();
        $this -> load -> library('session');
        $this -> load -> helper('form');
        $this -> load -> helper('url');
        $this -> load -> database();
        $this -> load->library('api');
        $this -> load -> library('form_validation');
        $this -> load -> model('Comman_model');
        $this -> load -> model('Api_model');
    }

    public function index()
    {
        $this -> load ->view('Flutterwave/index.php');
    }
 }   