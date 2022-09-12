<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Image extends CI_Controller
{
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
	
	/*
	** Image Size
	*/
	public function imageSize()
	{
		if ($_FILES['icon']['size'] > 1024000)
		{
			$this->form_validation->set_message('imageSize', get_phrase('image_less_equal_1_mb'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/*
	** Image Type
	*/
	public function imageType()
	{
		if(!in_array(mime_content_type($_FILES['icon']['tmp_name']), array('image/jpeg', 'image/gif', 'image/png')))
		{
			$this->form_validation->set_message('imageType', get_phrase('uploaded_file_types'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	/*
	** Upload Image
	*/
	public function uploadimg($inputfilename,$image_director,$newname)
	{
		$file_extn = pathinfo($_FILES[$inputfilename]['name'], PATHINFO_EXTENSION);
		if(!is_dir($image_director)) $create_image_director = mkdir($image_director);
		$name = $newname.'.'.$file_extn;
		if(move_uploaded_file($_FILES[$inputfilename]["tmp_name"], $image_director.$name)) return $image_director.$name;
		else return 0;
	}
}