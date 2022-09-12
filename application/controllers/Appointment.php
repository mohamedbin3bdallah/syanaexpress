<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointment extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this -> load -> library('session');
        $this -> load -> helper('form');
        $this -> load -> helper('url');
        $this -> load-> library('image_lib');
        $this -> load->library('api');
        $this -> load -> database();
        $this -> load -> library('form_validation');
        $this -> load -> model('Comman_model');
        $this -> load -> model('Api_model');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }

	/*Book Appointment*/
    public function book_appointment()
    {
      $data['user_id'] = $this->input->post('user_id', TRUE);
      $data['artist_id'] = $this->input->post('artist_id', TRUE);
      $data['date_string']= $this->input->post('date_string', TRUE);
      $data['timezone']= $this->input->post('timezone', TRUE);
      $data['appointment_date'] = date('Y-m-d', strtotime($data['date_string']));
      $data['timing'] = date('h:i a', strtotime($data['date_string']));
      $data['created_at']=time();
      $data['updated_at']=time();
      $data['appointment_timestamp']=strtotime($data['date_string']);

      $this->checkUserStatus($data['user_id']);

      $appId = $this->Api_model->insertGetId(APP_TBL, $data);

      if($appId)
      {
        $checkUser= $this->Api_model->getSingleRow(USR_TBL, array('user_id'=>$data['user_id']));
        $msg=$checkUser->name.': booked you on'.$data['timing'];
        $this->firebase_notification($data['artist_id'], "Book Appointment" ,$msg,BOOK_ARTIST_NOTIFICATION);

        $this->api->api_message(1, BOOK_APP);
      }
      else
      {
        $this->api->api_message(0, TRY_AGAIN);
      }
    }

    /*Edit Appointment*/
    public function edit_appointment()
    {
      $role= $this->input->post('role', TRUE);
      $appointment_id= $this->input->post('appointment_id', TRUE);
      $data['user_id'] = $this->input->post('user_id', TRUE);
      $data['artist_id'] = $this->input->post('artist_id', TRUE);
      $data['date_string']= $this->input->post('date_string', TRUE);
      $data['timezone']= $this->input->post('timezone', TRUE);
      $data['appointment_date'] = date('Y-m-d', strtotime($data['date_string']));
      $data['timing'] = date('h:i a', strtotime($data['date_string']));
      $data['updated_at']=time();
      $data['appointment_timestamp']=strtotime($data['date_string']);

      $this->checkUserStatus($data['user_id']);

      $appId = $this->Api_model->getSingleRow(APP_TBL, array('id'=>$appointment_id));

      if($appId)
      {
        if($role==1)
        {
          $checkUser= $this->Api_model->getSingleRow(USR_TBL, array('user_id'=>$data['artist_id']));
          $msg=$checkUser->name.': has changed your booking.';
          $this->firebase_notification($data['user_id'], "Book Appointment" ,$msg,BOOK_ARTIST_NOTIFICATION);
        }
        else
        {
          $checkUser= $this->Api_model->getSingleRow(USR_TBL, array('user_id'=>$data['artist_id']));
          $msg=$checkUser->name.': has changed your booking.';
          $this->firebase_notification($data['user_id'], "Book Appointment" ,$msg,BOOK_ARTIST_NOTIFICATION);
        }

        $checkUser= $this->Api_model->updateSingleRow(APP_TBL, array('id'=>$appointment_id), $data);

        $this->api->api_message(1, BOOK_APP);
      }
      else
      {
        $this->api->api_message(0, TRY_AGAIN);
      }
    }

    /*Appointment Delete*/
    public function declineAppointment()
    {
      $appointment_id= $this->input->post('appointment_id', TRUE);
      $user_id= $this->input->post('user_id', TRUE);
      $this->checkUserStatus($user_id);

      $get_appointment = $this->Api_model->getSingleRow(APP_TBL, array('id'=>$appointment_id));
      if($get_appointment)
      {
        $this->Api_model->updateSingleRow(APP_TBL, array('id'=>$appointment_id), array('status'=>0));
        $this->api->api_message(1, APP_DECLINE);
      }
      else
      {
        $this->api->api_message(0, TRY_AGAIN);
      }
    }

    public function getAppointment()
    {
      $user_id= $this->input->post('user_id', TRUE);
      $role = $this->input->post('role', TRUE);

      $this->checkUserStatus($user_id);

      if($role==1)
      {      
        $where=array('artist_id'=>$user_id);   

          $get_appointment=$this->Api_model->getAllDataWhereoderBy($where,APP_TBL);

          if($get_appointment)
          {
            $get_appointments = array();
            foreach ($get_appointment as $get_appointment) 
            {
              $get_user= $this->Api_model->getSingleRow(USR_TBL, array('user_id'=>$get_appointment->user_id));

              $get_appointment->userName= $get_user->name;
              $get_appointment->userEmail= $get_user->email_id;
              $get_appointment->userMobile= $get_user->mobile;

              if($get_user->image)
              {
                $get_appointment->userImage= base_url().$get_user->image;
              }
              else
              {
                $get_appointment->userImage= base_url()."assets/images/image.png";
              }
              $get_appointment->userAddress= $get_user->office_address;

              $get_artist= $this->Api_model->getSingleRow(ART_TBL, array('user_id'=>$get_appointment->artist_id));
              $get_artistDetails= $this->Api_model->getSingleRow(USR_TBL, array('user_id'=>$get_appointment->artist_id));

              $get_cat=$this->Api_model->getSingleRow(CAT_TBL, array('id'=>$get_artist->category_id));
              $get_appointment->category_name=$get_cat->cat_name;
              $get_appointment->category_price=$get_cat->price;

              $get_appointment->artistName= $get_artist->name;
              $get_appointment->artistMobile= $get_artistDetails->mobile;
              $get_appointment->artistEmail= $get_artistDetails->email_id;

              $currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
              $get_appointment->currency_type=$currency_setting->currency_symbol;

              if($get_artistDetails->image)
              {
                $get_appointment->artistImage= base_url().$get_artistDetails->image;
              }
              else
              {
                $get_appointment->artistImage= base_url()."assets/images/image.png";
              }
              $get_appointment->artistAddress= $get_artist->location;

              array_push($get_appointments, $get_appointment);
            }
          
            $this->api->api_message_data(1, GET_APP,'data' , $get_appointments);
          }
          else
          {
            $this->api->api_message(0, "No appointment found.");
          }     
        }
        elseif($role==2)
        {
          $where=array('user_id'=>$user_id);

          $get_appointment=$this->Api_model->getAllDataWhere($where,APP_TBL);

          if($get_appointment)
          {
            $get_appointments = array();
            foreach ($get_appointment as $get_appointment) 
            {
              $get_artist= $this->Api_model->getSingleRow(ART_TBL, array('user_id'=>$get_appointment->artist_id));

              $get_artistDetails= $this->Api_model->getSingleRow(USR_TBL, array('user_id'=>$get_appointment->artist_id));
              $get_appointment->artistMobile= $get_artistDetails->mobile;
              $get_appointment->artistEmail= $get_artistDetails->email_id;

              $get_cat=$this->Api_model->getSingleRow(CAT_TBL, array('id'=>$get_artist->category_id));
              $get_appointment->category_name=$get_cat->cat_name;
              $get_appointment->category_price=$get_cat->price;

              if($get_artistDetails->image)
              {
                $get_appointment->artistImage= base_url().$get_artistDetails->image;
              }
              else
              {
                $get_appointment->artistImage= base_url()."assets/images/image.png";
              }

              $get_appointment->artistName= $get_artist->name;
              $get_appointment->artistAddress= $get_artist->location;
              $currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
              $get_appointment->currency_type=$currency_setting->currency_symbol;

              $get_user= $this->Api_model->getSingleRow(USR_TBL, array('user_id'=>$get_appointment->user_id));
              if($get_user->image)
              {
                $get_appointment->userImage= base_url().$get_user->image;
              }
              else
              {
                $get_appointment->userImage= base_url()."assets/images/image.png";
              }

              $get_appointment->userName= $get_user->name;
              $get_appointment->userAddress= $get_user->address;
              $get_appointment->userEmail= $get_user->email_id;
              $get_appointment->userMobile= $get_user->mobile;

              array_push($get_appointments, $get_appointment);
            }
            $this->api->api_message_data(1, GET_APP,'data' , $get_appointments);
          }
          else
          {
            $this->api->api_message(0, NOTFOUND);
          }     
        }
        else
        {
          $this->api->api_message(0, "Invalid Request");
        }
    }

     /*Start Job*/
	public function startAppointment()
	{
		$appointment_id = $this->input->post('appointment_id', TRUE);
		$data['user_id'] = $this->input->post('user_id', TRUE);
		$data['artist_id'] = $this->input->post('artist_id', TRUE);
		$data['price'] = $this->input->post('price', TRUE);
		$date_string= date('Y-m-d h:i a');
		$data['time_zone']= $this->input->post('timezone', TRUE);
		$data['booking_date'] = date('Y-m-d');
		$data['booking_time'] = date('h:i a');
		$data['start_time']=time();
		$data['created_at']=time();
		$data['booking_type']=1;
		$data['booking_flag']=3;
		$data['updated_at']=time();
		$data['booking_timestamp']=strtotime($date_string);

		$this->checkUserStatus($data['user_id']);
		$this->checkUserStatus($data['artist_id']);

		$checkJob= $this->Api_model->getSingleRow(APP_TBL, array('id'=>$appointment_id,'status'=>1,'artist_id'=>$data['artist_id']));
		if($checkJob)
		{
		  $data['job_id']= $appointment_id;
		  $this->Api_model->updateSingleRow(APP_TBL,array('id'=>$appointment_id),array('status'=>5));

		  $checkArtist= $this->Api_model->getSingleRow(ART_TBL, array('user_id'=>$data['artist_id'],'booking_flag'=>1));
		  if($checkArtist)
		  {
		    $this->api->api_message(0, "Artist Busy with another client. Please try after sometime.");
		    exit();
		  }
		  
		  $getArtist= $this->Api_model->getSingleRow(ART_TBL, array('user_id'=>$data['artist_id']));

		  $category_id= $getArtist->category_id;
		  $category= $this->Api_model->getSingleRow(CAT_TBL, array('id'=>$category_id));

		  $commission_setting= $this->Api_model->getSingleRow('commission_setting',array('id'=>1));

		  $data['commission_type']=$commission_setting->commission_type;
		  $data['flat_type']=$commission_setting->flat_type;
		  if($commission_setting->commission_type==0)
		  {
		    $data['category_price']= $category->price;
		  }
		  elseif($commission_setting->commission_type==1)
		  {
		    if($commission_setting->flat_type==2)
		    {
		      $data['category_price']= $commission_setting->flat_amount;
		    }
		    elseif ($commission_setting->flat_type==1) 
		    {
		      $data['category_price']= $commission_setting->flat_amount;
		    }
		  }
		  
		  $appId = $this->Api_model->insertGetId(ABK_TBL, $data);
		  if($appId)
		  {
		    $checkUser= $this->Api_model->getSingleRow(USR_TBL, array('user_id'=>$data['user_id']));
		    $msg=$checkUser->name.': start your job '.$date_string;
		    $this->firebase_notification($data['artist_id'], "Start Job" ,$msg,START_BOOKING_ARTIST_NOTIFICATION);

		    $dataNotification['user_id']= $data['artist_id'];
		    $dataNotification['title']= "Start Job";
		    $dataNotification['msg']= $msg;
		    $dataNotification['type']= "Individual";
		    $dataNotification['created_at']=time(); 
		    $this->Api_model->insertGetId(NTS_TBL,$dataNotification);

		    $updateUser=$this->Api_model->updateSingleRow(ART_TBL,array('user_id'=>$data['artist_id']),array('booking_flag'=>3));
		    $this->api->api_message(1, BOOK_APP);
		  }
		  else
		  {
		    $this->api->api_message(0, TRY_AGAIN);
		  }
		}
		else
		{
		  $this->api->api_message(0, "No Appointments found in starting state. Please try after sometime.");
		  exit();
		}  
	}
}  