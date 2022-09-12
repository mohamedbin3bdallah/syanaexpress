<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Point extends CI_Controller
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
	** All Artist Points
	*/
    public function index()
    {
      if(isset($_SESSION['name']))
      {
          $data['points'] = $this->Api_model->getAllArtistPoints();
          $data['page'] = 'artist_points';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('points/index.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** All Point Transactions
	*/
    public function transactions($id)
    {
      if(isset($_SESSION['name']))
      {
		  $settings = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
          $name = ($settings->value == 'arabic')? 'name_ar':'name_en';
          $data['transactions'] = $this->Api_model->getPointTransactionsByArtist($id,$name);
          $data['page'] = 'artist_point_transactions';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('points/transactions.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** All Point Rewards
	*/
    public function pointRewards()
    {
      if(isset($_SESSION['name'])) 
      {
          $data['point_rewards'] = $this->Api_model->getPointRewards();
          $data['page'] = 'point_rewards';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('points/point_rewards.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Add Point Reward
	*/
    public function addPointReward()
    {
      if(isset($_SESSION['name'])) 
      {
		  $data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
          $data['countries']=  $this->Api_model->getAllData('countries');
		  $data['point_reward_types']=  $this->Api_model->getAllData('point_reward_types');
          $data['page'] = 'add_point_reward';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('points/add_point_reward.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Store Point Reward
	*/
    public function storePointReward()
    {
      if(isset($_SESSION['name'])) 
      {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('points_count', get_phrase('points_count') , 'trim|required|integer');
		  $this->form_validation->set_rules('rewarded_balance', get_phrase('rewarded_balance') , 'trim|required|numeric');
		  $this->form_validation->set_rules('country', get_phrase('country') , 'trim|required|integer');
		  $this->form_validation->set_rules('point_reward_type', get_phrase('type') , 'trim|required|integer');
		  $this->form_validation->set_rules('status', get_phrase('status') , 'trim|required|integer');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
            $data['countries']=  $this->Api_model->getAllData('countries');
		    $data['point_reward_types']=  $this->Api_model->getAllData('point_reward_types');
            $data['page'] = 'add_point_reward';
            $this->load->view('common/head.php');
            $this->load->view($this->sidebar, $data);
            $this->load->view('points/add_point_reward.php', $data);
            $this->load->view('common/footer.php');
          }
          else
          {
			$exist_record = $this->Api_model->getSingleRow('point_rewards',array('country_id'=>set_value('country'), 'point_reward_type_id'=>set_value('point_reward_type')));
			if($exist_record)
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'record_already_exist';
				redirect('Point/addPointReward');
			}
			else
			{
				$insert_id = $this->Api_model->insertGetId('point_rewards',array('country_id'=>set_value('country'), 'point_reward_type_id'=>set_value('point_reward_type'), 'points_count'=>set_value('points_count'), 'rewarded_balance'=>set_value('rewarded_balance'), 'status'=>set_value('status'), 'created_by'=>$_SESSION['id'], 'created_at'=>date("Y-m-d H:i:s")));
				if($insert_id)
				{
					$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
					redirect('Point/addPointReward');
				}
				else
				{
					$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
					redirect('Point/addPointReward');
				}
			}
          }
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Edit Point Reward
	*/
    public function editPointReward($id)
    {
      if(isset($_SESSION['name'])) 
      {
		  $data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
		  $data['point_reward'] = $this->Api_model->getSingleRow('point_rewards',array('id'=>$id));
          $data['countries']=  $this->Api_model->getAllData('countries');
		  $data['point_reward_types']=  $this->Api_model->getAllData('point_reward_types');
          $data['page'] = 'edit_point_reward';
          $this->load->view('common/head.php');
          $this->load->view($this->sidebar, $data);
          $this->load->view('points/edit_point_reward.php', $data);
          $this->load->view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Update Point Reward
	*/
    public function updatePointReward()
    {
      if(isset($_SESSION['name'])) 
      {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('points_count', get_phrase('points_count') , 'trim|required|integer');
		  $this->form_validation->set_rules('rewarded_balance', get_phrase('rewarded_balance') , 'trim|required|numeric');
		  $this->form_validation->set_rules('country', get_phrase('country') , 'trim|required|integer');
		  $this->form_validation->set_rules('point_reward_type', get_phrase('type') , 'trim|required|integer');
		  $this->form_validation->set_rules('status', get_phrase('status') , 'trim|required|integer');
		  $this->form_validation->set_rules('id', get_phrase('point_reward') , 'trim|required|integer');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
            $data['countries']=  $this->Api_model->getAllData('countries');
		    $data['point_reward_types']=  $this->Api_model->getAllData('point_reward_types');
            $data['page'] = 'edit_point_reward';
            $this->load->view('common/head.php');
            $this->load->view($this->sidebar, $data);
            $this->load->view('points/edit_point_reward.php', $data);
            $this->load->view('common/footer.php');
          }
          else
          {
			$exist_record = $this->Api_model->getSingleRow('point_rewards',array('country_id'=>set_value('country'), 'point_reward_type_id'=>set_value('point_reward_type')));
			if($exist_record && $exist_record->id != set_value('id'))
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'record_already_exist';
				redirect('Point/editPointReward/'.set_value('id'));
			}
			else
			{
				$this->Api_model->updateSingleRow('point_rewards',array('id'=>set_value('id')),array('country_id'=>set_value('country'), 'point_reward_type_id'=>set_value('point_reward_type'), 'points_count'=>set_value('points_count'), 'rewarded_balance'=>set_value('rewarded_balance'), 'status'=>set_value('status'), 'updated_by'=>$_SESSION['id'], 'updated_at'=>date("Y-m-d H:i:s")));
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
				redirect('Point/editPointReward/'.set_value('id'));
			}
          }
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Delete Point Reward
	*/
    public function deletePointReward($id)
    {
      if(isset($_SESSION['name'])) 
      {
		  $this->Api_model->deleteRecord(array('id'=>$id), 'point_rewards');
		  $_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
		  redirect('Point/pointRewards');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Change Status
	*/
    public function changeStatus($callBack,$table,$id,$status)
    {
      if(isset($_SESSION['name'])) 
      {
		  $this->Api_model->updateSingleRow($table, array('id'=>$id), array('status'=>$status, 'updated_by'=>$_SESSION['id'], 'updated_at'=>date("Y-m-d H:i:s")));
		  $_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
		  redirect('Point/'.$callBack);
      }
      else
      {
          redirect('');
      }
    }
}