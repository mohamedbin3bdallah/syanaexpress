<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Place extends CI_Controller
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
	
	/*
	** Delete Record
	*/
    /*public function delete($callBack,$table,$id)
    {
      if(isset($_SESSION['name']))
      {
		  $this->Api_model->deleteRecord(array('id'=>$id), $table);
		  $_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
		  redirect('Place/'.$callBack);
      }
      else
      {
          redirect('');
      }
    }*/
	
	/*
	** Change Status
	*/
    public function changeStatus($callBack,$table,$id,$status)
    {
      if(isset($_SESSION['name']))
      {
		  $this->Api_model->updateSingleRow($table, array('id'=>$id), array('active'=>$status));
		  $_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
		  redirect('Place/'.$callBack);
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** All Countries
	*/
    public function countries()
    {
      if(isset($_SESSION['name']))
      {
          $data['countries'] = $this->Api_model->getAllData('countries');
          $data['page'] = 'countries';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('places/countries.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Add Country
	*/
    public function addCountry()
    {
      if(isset($_SESSION['name']))
      {
		  $data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
          $data['page'] = 'add_country';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('places/add_country.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Store Country
	*/
    public function storeCountry()
    {
      if(isset($_SESSION['name']))
      {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('name', get_phrase('english_name') , 'trim|required|max_length[255]|is_unique[countries.name]');
		  $this->form_validation->set_rules('name_ar', get_phrase('arabic_name') , 'trim|required|max_length[255]|is_unique[countries.name_ar]');
		  $this->form_validation->set_rules('iso', get_phrase('iso2') , 'trim|required|max_length[2]|is_unique[countries.iso]');
		  $this->form_validation->set_rules('iso3', get_phrase('iso3') , 'trim|required|max_length[3]|is_unique[countries.iso3]');
		  $this->form_validation->set_rules('numcode', get_phrase('num_code') , 'trim|required|numeric');
		  $this->form_validation->set_rules('phonecode', get_phrase('phone_code') , 'trim|required|numeric');
		  $this->form_validation->set_rules('active', get_phrase('active') , 'trim|required|integer');
		  $this->form_validation->set_rules('icon', get_phrase('icon') , 'callback_imageSize|callback_imageType');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
            $data['page'] = 'add_country';
            $this->load->view('common/head.php');
            $this->load->view($this->sidebar, $data);
            $this->load->view('places/add_country.php', $data);
            $this->load->view('common/footer.php');
          }
          else
          {			
			$icon = '';
			if($_FILES['icon']['tmp_name'])
			{
				$icon = $this->uploadimg('icon', $this->config->item('flags_folder'), set_value('iso3'));
			}
			
			$count = $this->Api_model->getCountWhere('countries', []);
			$insert_id = $this->Api_model->insertGetId('countries',array('name'=>set_value('name'), 'name_ar'=>set_value('name_ar'), 'iso'=>set_value('iso'), 'iso3'=>set_value('iso3'), 'numcode'=>set_value('numcode'), 'phonecode'=>set_value('phonecode'), 'sort'=>$count+1,'icon'=>$icon, 'active'=>set_value('active')));
			$this->rediret_after_action($insert_id, 'Place/addCountry');
          }
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Edit Country
	*/
    public function editCountry($id)
    {
      if(isset($_SESSION['name']))
      {
		  $data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
		  $data['country'] = $this->Api_model->getSingleRow('countries',array('id'=>$id));
          $data['page'] = 'edit_country';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('places/edit_country.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Update Country
	*/
    public function updateCountry($id)
    {
      if(isset($_SESSION['name']))
      {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('name', get_phrase('english_name') , 'trim|required|max_length[255]|callback_check_name');
		  $this->form_validation->set_rules('name_ar', get_phrase('arabic_name') , 'trim|required|max_length[255]|callback_check_name_ar');
		  $this->form_validation->set_rules('iso', get_phrase('iso2') , 'trim|required|max_length[2]|callback_check_iso');
		  $this->form_validation->set_rules('iso3', get_phrase('iso3') , 'trim|required|max_length[3]|callback_check_iso3');
		  $this->form_validation->set_rules('numcode', get_phrase('num_code') , 'trim|required|numeric');
		  $this->form_validation->set_rules('phonecode', get_phrase('phone_code') , 'trim|required|numeric');
		  $this->form_validation->set_rules('active', get_phrase('active') , 'trim|required|integer');
		  $this->form_validation->set_rules('id', 'id' , 'trim|required|integer');
		  if (!empty($_FILES['icon']['tmp_name']))
		  {
			$this->form_validation->set_rules('icon', get_phrase('icon') , 'callback_imageSize|callback_imageType');
		  }
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
			$data['country'] = $this->Api_model->getSingleRow('countries',array('id'=>$id));
            $data['page'] = 'add_country';
            $this->load->view('common/head.php');
            $this->load->view($this->sidebar, $data);
            $this->load->view('places/edit_country.php', $data);
            $this->load->view('common/footer.php');
          }
          else
          {			
			$data = array('name'=>set_value('name'), 'name_ar'=>set_value('name_ar'), 'iso'=>set_value('iso'), 'iso3'=>set_value('iso3'), 'numcode'=>set_value('numcode'), 'phonecode'=>set_value('phonecode'), 'active'=>set_value('active'));
			
			if($_FILES['icon']['tmp_name'])
			{
				$data['icon'] = $this->uploadimg('icon', $this->config->item('flags_folder'), set_value('iso3'));
				if($data['icon'] && file_exists(set_value('image'))) unlink(set_value('image'));
			}
			
			$updated = $this->Api_model->updateSingleRow('countries', array('id'=>set_value('id')), $data);
			$this->rediret_after_action($updated, 'Place/editCountry/'.set_value('id'));
          }
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** All Cities
	*/
    public function cities()
    {
      if(isset($_SESSION['name']))
      {
		  $settings = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
          $name = ($settings->value == 'arabic')? 'name_ar':'name';
		  $data['cities'] = $this->Api_model->getCities($name);
          $data['page'] = 'cities';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('places/cities.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Add City
	*/
    public function addCity()
    {
      if(isset($_SESSION['name']))
      {
		  $data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
		  $data['countries'] = $this->Api_model->getAllDataWhere(array('active'=>1), 'countries');
          $data['page'] = 'add_city';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('places/add_city.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Store City
	*/
    public function storeCity()
    {
      if(isset($_SESSION['name']))
      {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('name', get_phrase('english_name') , 'trim|required|max_length[255]|is_unique[cities.name]');
		  $this->form_validation->set_rules('name_ar', get_phrase('arabic_name') , 'trim|required|max_length[255]|is_unique[cities.name_ar]');
		  $this->form_validation->set_rules('active', get_phrase('active') , 'trim|required|integer');
		  $this->form_validation->set_rules('country', get_phrase('country') , 'trim|required|integer');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
			$data['countries'] = $this->Api_model->getAllDataWhere(array('active'=>1), 'countries');
            $data['page'] = 'add_city';
            $this->load->view('common/head.php');
            $this->load->view($this->sidebar, $data);
            $this->load->view('places/add_city.php', $data);
            $this->load->view('common/footer.php');
          }
          else
          {			
			$insert_id = $this->Api_model->insertGetId('cities',array('name'=>set_value('name'), 'name_ar'=>set_value('name_ar'), 'country_id'=>set_value('country'), 'active'=>set_value('active')));
			$this->rediret_after_action($insert_id, 'Place/addCity');
          }
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Edit City
	*/
    public function editCity($id)
    {
      if(isset($_SESSION['name']))
      {
		  $data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
		  $data['countries'] = $this->Api_model->getAllDataWhere(array('active'=>1), 'countries');
		  $data['city'] = $this->Api_model->getSingleRow('cities',array('id'=>$id));
          $data['page'] = 'edit_city';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('places/edit_city.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Update City
	*/
    public function updateCity($id)
    {
      if(isset($_SESSION['name']))
      {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('name', get_phrase('english_name') , 'trim|required|max_length[255]|callback_check_name');
		  $this->form_validation->set_rules('name_ar', get_phrase('arabic_name') , 'trim|required|max_length[255]|callback_check_name_ar');
		  $this->form_validation->set_rules('active', get_phrase('active') , 'trim|required|integer');
		  $this->form_validation->set_rules('id', 'id' , 'trim|required|integer');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
		    $data['countries'] = $this->Api_model->getAllDataWhere(array('active'=>1), 'countries');
		    $data['city'] = $this->Api_model->getSingleRow('cities',array('id'=>$id));
            $data['page'] = 'edit_city';
            $this->load->view('common/head.php');
            $this->load->view($this->sidebar, $data);
            $this->load->view('places/edit_city.php', $data);
            $this->load->view('common/footer.php');
          }
          else
          {			
			$data = array('name'=>set_value('name'), 'name_ar'=>set_value('name_ar'), 'country_id'=>set_value('country'), 'active'=>set_value('active'));
			
			$updated = $this->Api_model->updateSingleRow('cities', array('id'=>set_value('id')), $data);
			$this->rediret_after_action($updated, 'Place/editCity/'.set_value('id'));
          }
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Rediret After Action
	*/
	public function rediret_after_action($done, $page)
	{
		if($done)
		{				
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
			redirect($page);
		}
		else
		{
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
			redirect($page);
		}
	}
	
	/*
	** Check English Name
	*/
	function check_name($name)
	{
		$id = ($this->input->post('id'))? $this->input->post('id'):'';
		$result = $this->Api_model->getSingleRow($this->input->post('table'),array('id!='=>$id,'name'=>$name));
		if($result)
		{
			$this->form_validation->set_message('check_name', get_phrase('english_name_must_be_unique'));
			$response = false;
		}
		else
		{
			$response = true;
		}
		return $response;
	}
	
	/*
	** Check Arabic Name
	*/
	function check_name_ar($name)
	{
		$id = ($this->input->post('id'))? $this->input->post('id'):'';
		$result = $this->Api_model->getSingleRow($this->input->post('table'),array('id!='=>$id,'name_ar'=>$name));
		if($result)
		{
			$this->form_validation->set_message('check_name_ar', get_phrase('arabic_name_must_be_unique'));
			$response = false;
		}
		else
		{
			$response = true;
		}
		return $response;
	}
	
	/*
	** Check ISO
	*/
	function check_iso($name)
	{
		$id = ($this->input->post('id'))? $this->input->post('id'):'';
		$result = $this->Api_model->getSingleRow($this->input->post('table'),array('id!='=>$id,'iso'=>$name));
		if($result)
		{
			$this->form_validation->set_message('check_iso', get_phrase('iso2_must_be_unique'));
			$response = false;
		}
		else
		{
			$response = true;
		}
		return $response;
	}
	
	/*
	** Check ISO3
	*/
	function check_iso3($name)
	{
		$id = ($this->input->post('id'))? $this->input->post('id'):'';
		$result = $this->Api_model->getSingleRow($this->input->post('table'),array('id!='=>$id,'iso3'=>$name));
		if($result)
		{
			$this->form_validation->set_message('check_iso3', get_phrase('iso3_must_be_unique'));
			$response = false;
		}
		else
		{
			$response = true;
		}
		return $response;
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