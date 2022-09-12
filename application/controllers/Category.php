<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller
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
	** Add Price
	*/
    public function addPrice($category_id)
    {
      if(isset($_SESSION['name']))
      {
		  $category = $this->Api_model->getSingleRow('category',array('parent_id !='=>0,'id'=>$category_id));
		  if($category)
		  {
			$name = (getSelectedLanguage() == 'arabic')? 'name_ar':'name';
			$data['countries'] = $this->Api_model->getNonPriceCountries($name,$category->id);
			$data['category'] = $category;
			$data['page'] = 'add_price';
			$this->load->view('common/head.php');
			$this->load->view($this->sidebar, $data);
			$this->load->view('categories/add_price.php', $data);
			$this->load->view('common/footer.php');
		  }
		  else
		  {
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'there_is_no_record';
			redirect('Admin/category');
		  }
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Store Price
	*/
    public function storePrice($category_id)
    {
      if(isset($_SESSION['name']))
      {
		$category = $this->Api_model->getSingleRow('category',array('parent_id !='=>0,'id'=>$category_id));
		if($category)
		{		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('country_price', get_phrase('country') , 'trim|required|integer');
		  $this->form_validation->set_rules('price', get_phrase('price') , 'trim|required|max_length[50]');
		  $this->form_validation->set_rules('status', get_phrase('status') , 'trim|required|integer');
		  if ($this->form_validation->run() == FALSE)
          {
			$name = (getSelectedLanguage() == 'arabic')? 'name_ar':'name';
			$data['countries'] = $this->Api_model->getNonPriceCountries($name,$category->id);
			$data['category'] = $category;
			$data['page'] = 'add_price';
			$this->load->view('common/head.php');
			$this->load->view($this->sidebar, $data);
			$this->load->view('categories/add_price.php', $data);
			$this->load->view('common/footer.php');
          }
          else
          {
			$insert_id = $this->Api_model->insertGetId('category_price',array('category_id'=>$category->id, 'country_id'=>set_value('country_price'), 'price'=>set_value('price'), 'status'=>set_value('status'), 'created_at'=>time()));
			if($insert_id)
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
				redirect('Admin/category');
			}
			else
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
				redirect('Admin/category');
			}
          }
		}
		else
		{
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'there_is_no_record';
			redirect('Admin/category');
		}
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Edit Price
	*/
    public function editPrice($price_id)
    {
      if(isset($_SESSION['name']))
      {
		  $name = (getSelectedLanguage() == 'arabic')? 'name_ar':'name';
		  $price = $this->Api_model->getPriceByID($price_id,$name);
		  if($price)
		  {
			$data['price'] = $price;
			$data['page'] = 'edit_price';
			$this->load->view('common/head.php');
			$this->load->view($this->sidebar, $data);
			$this->load->view('categories/edit_price.php', $data);
			$this->load->view('common/footer.php');
		  }
		  else
		  {
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'there_is_no_record';
			redirect('Admin/category');
		  }
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Update Price
	*/
    public function updatePrice($price_id)
    {
      if(isset($_SESSION['name']))
      {
		$name = (getSelectedLanguage() == 'arabic')? 'name_ar':'name';
		$price = $this->Api_model->getPriceByID($price_id,$name);
		if($price)
		{		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('price', get_phrase('price') , 'trim|required|max_length[50]');
		  $this->form_validation->set_rules('status', get_phrase('status') , 'trim|required|integer');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['price'] = $price;
			$data['page'] = 'edit_price';
			$this->load->view('common/head.php');
			$this->load->view($this->sidebar, $data);
			$this->load->view('categories/edit_price.php', $data);
			$this->load->view('common/footer.php');
          }
          else
          {
			$updated = $this->Api_model->updateSingleRow('category_price', array('id'=>$price->id), array('price'=>set_value('price'), 'status'=>set_value('status'), 'updated_at'=>time()));
			if($updated)
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
				redirect('Admin/category');
			}
			else
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
				redirect('Admin/category');
			}
          }
		}
		else
		{
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'there_is_no_record';
			redirect('Admin/category');
		}
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Delete Price
	*/
    public function deletePrice($id)
    {
      if(isset($_SESSION['name'])) 
      {
		  $this->Api_model->deleteRecord(array('id'=>$id), 'category_price');
		  $_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
		  redirect('Admin/category');
      }
      else
      {
          redirect('');
      }
    }
}