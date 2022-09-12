<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
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
        $this -> load -> library('api');
        $this -> load -> library('form_validation');
        $this -> load -> model('Comman_model');
        $this -> load -> model('Api_model');
    }

    public function index()
    {
      redirect('Admin/home');
    }

    public function manage_language($param1 = '', $param2 = '', $param3 = ''){


    if ($param1 == 'add_language') {
      saveDefaultJSONFile(trimmer($this->input->post('language')));
      $this->session->set_flashdata('flash_message', get_phrase('language_added_successfully'));
      redirect(site_url('admin/manage_language'), 'refresh');
    }
    if ($param1 == 'add_phrase') {
      $new_phrase = get_phrase($this->input->post('phrase'));
      $this->session->set_flashdata('flash_message', $new_phrase.' '.get_phrase('has_been_added_successfully'));
      redirect(site_url('admin/manage_language'), 'refresh');
    }

    if ($param1 == 'edit_phrase') {
      $page_data['edit_profile'] = $param2;
    }

    if ($param1 == 'delete_language') {
      if (file_exists('application/language/'.$param2.'.json')) {
        unlink('application/language/'.$param2.'.json');
        $this->session->set_flashdata('flash_message', get_phrase('language_deleted_successfully'));
        redirect(site_url('admin/manage_language'), 'refresh');
      }
    }
    $data['languages']				= $this->Api_model->get_all_languages();
    $data['page']='manage_language';
    $this -> load -> view('common/head.php');
    $this -> load -> view($this->sidebar, $data);
    $this -> load ->view('manage_language.php', $data);
    $this -> load -> view('common/footer.php');
    
  }

  public function changelanguage($param1 = '') {
    if($param1 == 'ar') {
      $where = array('key'=>'language');
      $datas = array('value'=>'arabic');
      $update= $this->Api_model->updateSingleRow('settings', $where, $datas);
    }
    else {
      $where = array('key'=>'language');
      $datas = array('value'=>'english');
      $update= $this->Api_model->updateSingleRow('settings', $where, $datas);
    }
    redirect($_SERVER['HTTP_REFERER']);
  }
  
    public function home()
    {
        if(isset($_SESSION['name'])) 
        { 
            $data['artist']=$this->Api_model->getCountAll('artist');
            $data['user']=$this->Api_model->getCount('user', array('role'=>2));
            $data['total_revenue']=$this->Api_model->getSum('total_amount', 'booking_invoice');

            $getInvoice= $this->Api_model->getAllDataLimit('booking_invoice',5);

            $getInvoices = array();
            foreach ($getInvoice as $getInvoice)
            {
              $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$getInvoice->booking_id));

              $getInvoice->booking_time= $getBooking->booking_time;
              $getInvoice->booking_date= $getBooking->booking_date;

              $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$getInvoice->user_id));

              $getInvoice->userName= $getUser->name;
              $getInvoice->address= $getUser->address;

              if($getUser->image)
              {
                $getInvoice->userImage= base_url().$getUser->image;
              }
              else
              {
                $getInvoice->userImage= base_url().'assets/images/a.png';
              }

              $get_artists= $this->Api_model->getSingleRow('artist', array('user_id'=>$getInvoice->artist_id));

              $get_cat=$this->Api_model->getSingleRow('category', array('id'=>$get_artists->category_id));

              $getInvoice->ArtistName=$get_artists->name;
              $getInvoice->categoryName=$get_cat->cat_name;

              if($get_artists->image)
              {
                $getInvoice->artistImage= base_url().$get_artists->image;
              }
              else
              {
                $getInvoice->artistImage= base_url().'assets/images/a.png';
              }

              array_push($getInvoices, $getInvoice);
            }

            $tickets= array();
            $ticket=$this->Api_model->getAllDataLimit('ticket',5);
            foreach ($ticket as $ticket) 
            {
              $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$ticket->user_id));
              $ticket->userName= $getUser->name;
              $ticket->userImage= $getUser->image;
              array_push($tickets, $ticket);
            }
            $data['active_user']=$this->Api_model->getCount('user', array('status'=>1,));
            $data['deactive_user']=$this->Api_model->getCount('user', array('status'=>0,));

            $users= $this->Api_model->getAllData('user');
            if($users)
            {
              $data['monthly_user']=$this->Api_model->getMontlyUserCount();
              $data['monthly_revenue']=$this->Api_model->getMontlyRevenue();
            }
            else
            {
              $data['monthly_user']=array();
              $data['monthly_revenue']=array();
            }
            $currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
            $data['currency_type']= $currency_setting->currency_symbol;

            $data['page']='home';
            $data['tickets']=$tickets;
            $data['getInvoices']=$getInvoices;
            $this -> load -> view('common/head.php');
            $this -> load -> view($this->sidebar, $data);
            $this -> load ->view('dashboard.php', $data);
            $this -> load -> view('common/footer.php');
        }
        else
        {
            redirect('');
        }
    }


    public function exportCSV(){ 
       // file name 
       $role  = $_GET['role'];
       
       $usersData = $this->Api_model->getUserDetails($role);
    
     
       
      $file_name = 'export_users'.date('Ymd').'.csv';
      header("Content-Description: File Transfer");
      header("Content-Disposition: attachment; filename=$file_name");
      header("Content-Type: application/csv; charset=utf-8");
      $file = fopen('php://output', 'w');
      fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
      //fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
       $header = array("name","email_id","address","office_address","mobile","gender","city"); 
      fputcsv($file, $header);
      foreach ($usersData as $key => $value)
      {
          if($value['gender'] == 1) {
              $value['gender'] = 'male';
          }
          elseif($value['gender'] == 2) {
              $value['gender'] = 'female';
          }
        else {
            $value['gender'] = '';
        }
        fputcsv($file, $value);
      }
      fclose($file);
      exit;
      }
      
    
    public function exportBooking(){ 
       // file name 
       $getBooking= $this->Api_model->getAllData('artist_booking');
      $getBookings= array();
      $language = $this->db->get_where('settings' , array('key' => 'language'))->row()->value;
      foreach ($getBooking as $getBooking) 
      {
          $get_reviews=  $this->Api_model->getAllDataWhere(array('artist_id'=>$getBooking->artist_id,'status'=>1), 'rating');
          $review = array();
          foreach ($get_reviews as $get_reviews) 
          {
            $get_user = $this->Api_model->getSingleRow('user', array('user_id'=>$get_reviews->user_id));
            $get_reviews->name= $get_user->name;
             if($get_user->image)
            {
              $get_reviews->image= base_url().$get_user->image;
            }
            else
            {
              $get_reviews->image= base_url()."assets/images/image.png";
            }
            array_push($review, $get_reviews);
          }
            $getBooking->reviews=$review;

            $where=array('user_id'=>$getBooking->artist_id);
            $get_artists=$this->Api_model->getSingleRow('artist',$where);

            $get_cat=$this->Api_model->getSingleRow('category', array('id'=>$get_artists->category_id));
          if($get_artists->image)
          {
            $getBooking->artistImage=base_url().$get_artists->image;
          }
          else
          {
            $getBooking->artistImage=base_url()."assets/images/image.png";
          }
            if($language == 'arabic') {
                $getBooking->category_name=$get_cat->cat_name_ar;
            }
            else {
                $getBooking->category_name=$get_cat->cat_name;
            }
            
            $getBooking->artistName=$get_artists->name;
            $getBooking->artistLocation=$get_artists->location;

            $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$getBooking->user_id));
            $getBooking->userName= $getUser->name;
            $getBooking->address= $getUser->address;

            $where= array('artist_id'=>$getBooking->user_id, 'status'=>1);
            $ava_rating=$this->Api_model->getAvgWhere('rating', 'rating',$where);
          if($ava_rating[0]->rating==null)
          {
            $ava_rating[0]->rating="0";
          }
          $getBooking->ava_rating=$ava_rating[0]->rating;

          if($getBooking->start_time)
          {
            $getBooking->working_min= (float)round(abs($getBooking->start_time - time()) / 60,2);
          }
          else
          {
            $getBooking->working_min=0;
          }
          if($getUser->image)
          {
           $getBooking->userImage= base_url().$getUser->image;
          }
          else
          {
           $getBooking->userImage= base_url().'assets/images/image.png';
          }
          if(!empty($getBooking->service_id)) {
            $matches = preg_replace("/[^0-9]/","",$getBooking->service_id);
            $detail=$this->Api_model->getSingleRow('products', array('id'=>$matches));
            if(!empty($detail)){
              $getBooking->booking_deails = 'Service Detail: '.$detail->product_name;
            } 
            else {
              $getBooking->booking_deails = 'No Service Details';
            }
          }
          elseif(!empty($getBooking->job_id)) {
            $detail=$this->Api_model->getSingleRow('post_job', array('job_id'=>$getBooking->job_id)); 
            if(!empty($detail)){
              $getBooking->booking_deails = 'Job Detail: '.$detail->description;
            }
            else {
              $getBooking->booking_deails = 'No Job Details';
            } 
          }
          else {
            $getBooking->booking_deails = 'No Description Details';
          }
          array_push($getBookings, $getBooking);
      }
    

      $file_name = 'export_users'.date('Ymd').'.csv';
      header("Content-Description: File Transfer");
      header("Content-Disposition: attachment; filename=$file_name");
      header("Content-Type: application/csv; charset=utf-8");
      $file = fopen('php://output', 'w');
      fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
      //fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
      $header = array("user_name","user_details","artist_name","artist_details","category","detail","price", "time", "status"); 
      fputcsv($file, $header);
      foreach ($getBookings as $getBookings)
      {
        $value['user_name'] = $getBookings->userName;
        $userD = $this->Api_model->getUserDetail($getBookings->user_id); 
        $value['user_details'] = $userD[0]->email_id.' '.$userD[0]->mobile;
        $value['artist_name'] = $getBookings->artistName;
        $userD = $this->Api_model->getUserDetail($getBookings->artist_id); 
        $value['artist_details'] = $userD[0]->email_id.' '.$userD[0]->mobile;
        $value['category'] = $getBookings->category_name;
        $value['detail'] = $getBookings->detail;
        $value['price'] = $getBookings->price;
        $value['time'] = $getBookings->booking_date.' '.$getBookings->booking_time;
        if($getBookings->booking_flag==0) {
            $value['status'] = get_phrase('pending');
        }
        elseif($getBookings->booking_flag==1){
            $value['status'] = get_phrase('accepted');
        }
        elseif($getBookings->booking_flag==2){
            $value['status'] = get_phrase('decline');
        }
        elseif($getBookings->booking_flag==3){
            $value['status'] = get_phrase('running');
        }
        elseif($getBookings->booking_flag==4){
            $value['status'] = get_phrase('completed');
        }

        fputcsv($file, $value);
      }
      fclose($file);
      exit;
      }
  
  
      public function exportJob(){ 
       $get_jobs= $this->Api_model->getAllData('post_job');
       $job_list = array();
       $language = $this->db->get_where('settings' , array('key' => 'language'))->row()->value;
        foreach ($get_jobs as $get_jobs) 
        {
          $get_jobs->avtar= base_url().$get_jobs->avtar;
          $table= 'user';       
          $condition = array('user_id'=>$get_jobs->user_id);  
          $user = $this->Api_model->getSingleRow($table, $condition);  
          $user->image= base_url().$user->image;

          $table= 'category';       
          $condition = array('id'=>$get_jobs->category_id); 
          $cate = $this->Api_model->getSingleRow($table, $condition);  
            if($language == 'arabic') {
                $get_jobs->category_name=$cate->cat_name_ar;
            }
            else {
                $get_jobs->category_name=$cate->cat_name;
            }
          $commission_setting= $this->Api_model->getSingleRow('commission_setting',array('id'=>1));

          $get_jobs->commission_type = $commission_setting->commission_type;
          $get_jobs->flat_type = $commission_setting->flat_type;
          if($commission_setting->commission_type==0)
          {
            $get_jobs->category_price = $cate->price;
          }
          elseif($commission_setting->commission_type==1)
          {
            if($commission_setting->flat_type==2)
            {
              $get_jobs->category_price = $commission_setting->flat_amount;
            }
            elseif ($commission_setting->flat_type==1) 
            {
              $get_jobs->category_price = $commission_setting->flat_amount;
            }
          }
          $get_jobs->user_image = $user->image;
          $get_jobs->user_name = $user->name;
          $get_jobs->user_address = $user->address;
          $get_jobs->user_mobile = $user->mobile;
            
          array_push($job_list, $get_jobs);
        }
        
      $file_name = 'export_users'.date('Ymd').'.csv';
      header("Content-Description: File Transfer");
      header("Content-Disposition: attachment; filename=$file_name");
      header("Content-Type: application/csv; charset=utf-8");
      $file = fopen('php://output', 'w');
      fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
      //fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
      $header = array("user_name","title","detail","category","time","status"); 
      fputcsv($file, $header);
      foreach ($job_list as $job_list)
      {
         $value['user_name'] = $job_list->user_name;
         $value['title'] = $job_list->title;
         $value['description'] = $value['user_name'] =$job_list->description;
         $value['category_name'] = $job_list->category_name;
         $value['created_at'] = $job_list->created_at;
         if($job_list->status==0) {  
            $value['status'] = get_phrase('pending');
         } elseif($job_list->status==1) { 
            $value['status'] = get_phrase('confirm');
         } elseif($job_list->status==2) {
            $value['status'] = get_phrase('completed');
         } elseif($job_list->status==3) { 
            $value['status'] = get_phrase('rejected');
         } elseif($job_list->status==4) { 
             $value['status'] = get_phrase('deactivate');
         }
        fputcsv($file, $value);
      }
      fclose($file);
      exit;
      }
      
    public function exportInvoiceCSV(){ 
       // file name 
       //$role  = $_GET['role'];
       
        $getInvoice=$this->Api_model->getAllData('booking_invoice');
        $getInvoices = array();
        $detailInvoice = array();
        
       
      $file_name = 'export_users'.date('Ymd').'.csv';
      header("Content-Description: File Transfer");
      header("Content-Disposition: attachment; filename=$file_name");
      header("Content-Type: application/csv; charset=utf-8");
      $file = fopen('php://output', 'w');
      fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
      //fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
       $header = array("User name","Artist Name","Coupon Code","Working Minute","Commission Amount","Artist amount","Total Amount","payment type","Description","payment status"); 
      fputcsv($file, $header);
      
      
      foreach ($getInvoice as $getInvoice)
        {
          //$getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$getInvoice->booking_id));

          //$getInvoice->booking_time = $getBooking->booking_time;
          //$getInvoice->booking_date= $getBooking->booking_date;

          $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$getInvoice->user_id));
          $get_artists= $this->Api_model->getSingleRow('artist', array('user_id'=>$getInvoice->artist_id));
          $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$getInvoice->booking_id));
         
          $detailInvoice['username'] = $getUser->name;
          $detailInvoice['artist'] = $get_artists->name;
          $detailInvoice['coupon_code'] = $getInvoice->coupon_code;
          $detailInvoice['working_min'] = $getInvoice->working_min;
          $detailInvoice['commission_amount'] = $getInvoice->category_amount;
          $detailInvoice['artist_amount'] = $getInvoice->artist_amount;
          $detailInvoice['total_amount'] = $getInvoice->final_amount;
          if($getInvoice->payment_type == 1) {
              $getInvoice->payment_type = 'Cash';
          }
          elseif($getInvoice->payment_type == 0) {
              $getInvoice->payment_type = 'Online';
          }
          elseif($getInvoice->payment_type == 2) {
              $getInvoice->payment_type = 'Wallet';
          }
          else {
              $getInvoice->payment_type = '';
          }
          $detailInvoice['payment_type'] = $getInvoice->payment_type;
          if(!empty($getBooking->detail)) {
            $detailInvoice['description'] = $getBooking->detail;
          }
          else {
              if(!empty($getBooking->job_id)) {
                  $detailInvoice['description'] = $this->get_job_description($getBooking->job_id);
              }
            
          }
          $detailInvoice['payment_status'] = ($getInvoice->payment_status == 1) ? 'Paid' : 'Pending';
          fputcsv($file, $detailInvoice);
         
        }
      fclose($file);
      exit;
      }  

    public function warningUser()
    {
        if(isset($_SESSION['name']))
        {
            $user_id= $_GET['user_id'];
            $data['user_id']= $user_id;
            $data['created_at']= time();
            $getUserId=$this->Api_model->insertGetId('user_warning',$data);
            $totalWarning=$this->Api_model->getCountWhere('user_warning', array('user_id'=>$user_id));
            if($totalWarning==3)
            {
                $msg='Now you blocked by admin';
                $this->firebase_notification($user_id, "Warning" ,$msg,USER_BLOCK_NOTIFICATION);
                $where = array('user_id'=>$user_id);
                $datas = array('status'=>0);
                $update= $this->Api_model->updateSingleRow('user', $where, $datas);
            }
            redirect('Admin/warning');     
        }
        else
        {
            redirect();
        }  
    }


    public function jobs()
    {
       $get_jobs= $this->Api_model->getAllData('post_job');
       $job_list = array();
       $language = $this->db->get_where('settings' , array('key' => 'language'))->row()->value;
        foreach ($get_jobs as $get_jobs) 
        {
          $get_jobs->avtar= base_url().$get_jobs->avtar;
          $table= 'user';       
          $condition = array('user_id'=>$get_jobs->user_id);  
          $artist_booking = $this->Api_model->getSingleRow('artist_booking', array('job_id'=>$get_jobs->job_id));  
          $get_jobs->artist_price = $artist_booking->price;
          $user = $this->Api_model->getSingleRow($table, $condition);  
          $user->image= base_url().$user->image;

          $table= 'category';       
          $condition = array('id'=>$get_jobs->category_id); 
          $cate = $this->Api_model->getSingleRow($table, $condition);  
            if($language == 'arabic') {
                $get_jobs->category_name=$cate->cat_name_ar;
            }
            else {
                $get_jobs->category_name=$cate->cat_name;
            }
          $commission_setting= $this->Api_model->getSingleRow('commission_setting',array('id'=>1));

          $get_jobs->commission_type = $commission_setting->commission_type;
          $get_jobs->flat_type = $commission_setting->flat_type;
          if($commission_setting->commission_type==0)
          {
            $get_jobs->category_price = $cate->price;
          }
          elseif($commission_setting->commission_type==1)
          {
            if($commission_setting->flat_type==2)
            {
              $get_jobs->category_price = $commission_setting->flat_amount;
            }
            elseif ($commission_setting->flat_type==1) 
            {
              $get_jobs->category_price = $commission_setting->flat_amount;
            }
          }
          $get_jobs->user_image = $user->image;
          $get_jobs->user_name = $user->name;
          $get_jobs->user_address = $user->address;
          $get_jobs->user_mobile = $user->mobile;
            
          array_push($job_list, $get_jobs);
        }

        $data['job_list']= $job_list;
        $data['page']='jobs';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('post_job.php', $data);
        $this -> load -> view('common/footer.php');
    }


    public function ViewJobDetails()
    { 
       $job_id= $_GET['job_id'];

       $get_jobs= $this->Api_model->getAllDataWhere(array('job_id'=>$job_id),'applied_job');

        $job_list = array();
        foreach ($get_jobs as $get_jobs) 
        {
          $table= 'user';       
          $condition = array('user_id'=>$get_jobs->artist_id);  
          $user = $this->Api_model->getSingleRow($table, $condition);  
          $user->image= base_url().$user->image;

          $get_jobs->user_image = $user->image;
          $get_jobs->user_name = $user->name;
          $get_jobs->user_address = $user->address;
          $get_jobs->user_mobile = $user->mobile;
          $get_jobs->user_email = $user->email_id;
            
          array_push($job_list, $get_jobs);
        }
      
        $data['job_list']= $job_list;
        $data['page']='jobs';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('ViewJobDetails.php', $data);
        $this -> load -> view('common/footer.php');
    }
    
    public function all_revenue()
    {
      $cars = array(1, 2, 3);
      print_r($cars);
    }

    /*All Artists*/
     public function artists()
    {
        if(isset($_SESSION['name'])) 
        { 
$language = $this->db->get_where('settings' , array('key' => 'language'))->row()->value;
            $artist= $this->Api_model->getAllDataWhere(array('role'=>1), 'user');
            $artists= array();
            foreach ($artist as $artist) 
            {				
              $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$artist->user_id));
              $getArtist= $this->Api_model->getSingleRow('artist', array('user_id'=>$artist->user_id));
				
				$attachs= array();
				$attachs= $this->Api_model->getArtistAttachs($getArtist->id);
				$artist->attachs = $attachs;
				
				$artist->id = $getArtist->id;
				
              $categoryName = array();
            if(!empty($getArtist->category_id)) {
                $category_id = explode(',',$getArtist->category_id);
                foreach($category_id as $category) {
                 $get_cat=$this->Api_model->getSingleRow('category', array('id'=>$category));
                  if($language == 'arabic') {
                    $categoryName[]=$get_cat->cat_name_ar;
                  }
                  else {
                      $categoryName[]=$get_cat->cat_name;
                  }
                }
                $artist->categoryname = implode(',',$categoryName);
            }
              //$getArtist->created_at = $artist->created_at;
              if($getArtist)
              {
                $artist->is_artist=1;
                $artist->featured=$getArtist->featured;
              }
              else
              {
                $artist->is_artist=0;
                $artist->featured=0;
              }
              $wallent= $this->Api_model->getSingleRow('artist_wallet', array('artist_id'=>$artist->user_id));
              if($wallent)
              {
                $artist->amount=$wallent->amount;
              }
              else
              {
                $artist->amount=0;
              }
              $artist->email_id=$getUser->email_id;
              
              $artist->referral_code=$getUser->referral_code;
              $artist->user_referral_code=$getUser->user_referral_code;
              $artist->image=$getUser->image;
              $artist->bank_name=$getUser->bank_name;
              $artist->account_no=$getUser->account_no;
              $artist->ifsc_code=$getUser->ifsc_code;
              $artist->account_holder_name=$getUser->account_holder_name;
              $artist->status=$getUser->status;
              $artist->approval_status=$getUser->approval_status;
              array_push($artists, $artist);
            }

            $data['artist']= $artists;
            $data['page']='artists';
            $this -> load -> view('common/head.php');
            $this -> load -> view($this->sidebar, $data);
            $this -> load ->view('artist.php', $data);
            $this -> load -> view('common/footer.php');
        }
        else
        {
          redirect('');
        }
    }


    /*All Artists*/
    public function ViewTicket()
    {
      if(isset($_SESSION['name'])) 
      { 
        $ticket_id= $_GET['id'];
        $ticket= $this->Api_model->getSingleRow('ticket', array('id'=>$ticket_id));
       
          $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$ticket->user_id));

          $ticket->userName= $getUser->name;
          $ticket->userImage= $getUser->image;
  
          $data['ticket'] = $ticket;
          $ticket_comments=$this->Api_model->getAllDataWhere(array('ticket_id'=>$ticket_id), 'ticket_comments');
          $ticket_comment= array();
          foreach ($ticket_comments as $ticket_comments) 
          {
            if($ticket_comments->user_id !=0)
            {
              $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$ticket_comments->user_id));
              $ticket_comments->userName=$getUser->name;
            }
            else
            {
              $ticket_comments->userName="Admin";
            }
            array_push($ticket_comment, $ticket_comments);
          }

          $data['page']='ticket';
          $data['ticket_comment']=$ticket_comment;
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('ViewTicket.php', $data);
          $this -> load -> view('common/footer.php');
      }
      else
      {
        redirect('');
      }
    }

    public function addComment()
    {
       if(isset($_SESSION['name'])) 
        {
          $data['comment']= $this->input->post('comment', TRUE);
          $data['ticket_id']= $this->input->post('ticket_id', TRUE);
          $user_id= $this->input->post('user_id', TRUE);
          $data['role']= "Admin";
          $data['user_id']= 0;
          $data['created_at']=time();
          $title="Ticket Comment";
          $msg1=$data['comment'];

          $this->firebase_notification($user_id,$title,$msg1,TICKET_COMMENT_NOTIFICATION);
          $this->Api_model->insertGetId('ticket_comments',$data);
          redirect('Admin/ViewTicket?id='.$data['ticket_id']);
        }
        else
        {
          redirect('');
        }
    }
      /*All Artists*/
     public function ticket()
      {
        if(isset($_SESSION['name'])) 
        { 
          $tickets= array();
          $ticket=$this->Api_model->getAllDataOrderBy('ticket');
          foreach ($ticket as $ticket) 
          {
            $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$ticket->user_id));
            $ticket->userName= $getUser->name;
            $ticket->userImage= $getUser->image;
            array_push($tickets, $ticket);
          }

          $data['ticket']=$tickets;
          $data['page']='ticket';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('ticket.php', $data);
          $this -> load -> view('common/footer.php');
        }
        else
        {
          redirect('');
        }
      }
   
     /*All User*/
    public function user()
    {
        if(isset($_SESSION['name'])) 
        {
          $user= $this->Api_model->getAllDataWhere(array('role'=>2), 'user');
          $users= array();
          foreach ($user as $user) 
          {
            $wallent= $this->Api_model->getSingleRow('artist_wallet', array('artist_id'=>$user->user_id));
            if($wallent)
            {
              $user->amount=$wallent->amount;
            }
            else
            {
              $user->amount=0;
            }
            array_push($users, $user);  
          }

          $data['user']= array_reverse($users);
          $data['page']='user';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('user.php', $data);
          $this -> load -> view('common/footer.php');
        }
        else
        {
          redirect('');
        }
    }

     /*All Warning User*/
    public function warning()
    {
        if(isset($_SESSION['name'])) 
        {
            $User=$this->Api_model->getAllDataDistinct('user_warning');
            $users= array();
            foreach ($User as $User) 
            {
                $userMedia=$this->Api_model->getAllDataWhere(array('user_id'=>$User->user_id),'user_warning');
                $checkUser= $this->Api_model->getSingleRow('user', array('user_id'=>$User->user_id));
                $count= $this->Api_model->getCountWhere('user_warning', array('user_id'=>$User->user_id));
                $checkUser->count= $count;
                array_push($users, $checkUser);
            }

          $data['user']=$users;
          $data['page']='warningUser';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('warningUser.php', $data);
          $this -> load -> view('common/footer.php');
        }
        else
        {
          redirect('');
        }
    }
    

     /*All User*/
    public function broadcast_msg()
    {
      if(isset($_SESSION['name'])) 
      {
          $coupon= $this->Api_model->getAllDataWhere(array('type'=>"All"),'notifications');

          $data['coupon']= $coupon;
          $data['page']='broadcast';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load -> view('broadcast_msg.php', $data);
          $this -> load -> view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }

      /*All User*/
    public function setting()
    {
      if(isset($_SESSION['name'])) 
      {
        $this->get_setting_commission_view_data();
      }
      else
      {
        redirect('');
      }
    }

    public function app_setting()
    {
      if(isset($_SESSION['name'])) 
      {
        $this->get_setting_app_view_data();
      }
      else
      {
        redirect('');
      }
    }

    public function terms()
	{
		$this->showPage(1, 'terms');
    }

    public function editTerms()
    {
	  $this->editPage(1, 'terms');
    }

    public function policy()
	{
		$this->showPage(2, 'policy');
    }

    public function editPrivacy()
    {
		$this->editPage(2, 'policy');
    }
	
	public function showPage($id, $page)
	{
		if(isset($_SESSION['name'])) 
		{
			$data[$page]= $this->Api_model->getSingleRow('page',array('id'=>$id));
			$data['page']=$page;
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view($page.'.php', $data);
			$this -> load -> view('common/footer.php');
		}
		else
		{
			redirect('Admin/login');
		}
	}
	
	public function editPage($id, $page)
	{
		if(isset($_SESSION['name']))
        {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('title', get_phrase('title') , 'trim|required|max_length[255]');
		  $this->form_validation->set_rules('title_ar', get_phrase('title_arabic') , 'trim|required|max_length[255]');
		  $this->form_validation->set_rules('description', get_phrase('description') , 'required');
		  $this->form_validation->set_rules('description_ar', get_phrase('description_arabic') , 'required');
		  if($this->form_validation->run() == FALSE)
          {
			$data[$page]= $this->Api_model->getSingleRow('page',array('id'=>$id));
			$data['page']=$page;
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view($page.'.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {
			$data = array('title'=>set_value('title'), 'title_ar'=>set_value('title_ar'), 'description'=>set_value('description'), 'description_ar'=>set_value('description_ar'));
			$updated = $this->Api_model->updateSingleRow('page', array('id'=>$id), $data);
			$this->rediret_after_action($updated, 'Admin/'.$page);
		  }
		}
		else
		{
			redirect('');
		}
	}

    public function commissionSetting()
    {	  
		if(isset($_SESSION['name']))
        {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('commission_type', get_phrase('commission_type') , 'trim|required|integer|greater_than[0]');
		  if($this->input->post('commission_type', TRUE) == 1)
		  {
			$this->form_validation->set_rules('flat_amount', get_phrase('flat_amount'), 'trim|required|integer|max_length[50]');
			$this->form_validation->set_rules('flat_type', get_phrase('flat_type'), 'trim|required|integer');
		  }
		  if($this->form_validation->run() == FALSE)
          {
			$this->session->set_flashdata('addClass', 'addClass-A');
			$this->get_setting_commission_view_data();
          }
          else
          {
			$data = array('commission_type'=>set_value('commission_type'), 'updated_at'=>time());
			
			if(set_value('commission_type') == 1)
			{
				$data['flat_amount'] = set_value('flat_amount');
				$data['flat_type'] = set_value('flat_type');
			}
			
			$updated = $this->Api_model->updateSingleRow('commission_setting', array('id'=>1), $data);
			$this->rediret_after_action($updated, 'Admin/setting');
		  }
		}
		else
		{
			redirect('');
		}
    }
	
	public function set_discount()
    {
	  if(isset($_SESSION['name']))
        {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('discount', get_phrase('discount') , 'trim|required|integer|greater_than[-1]');
		  if($this->form_validation->run() == FALSE)
          {
			$this->session->set_flashdata('addClass', 'addClass-C');
			$this->get_setting_commission_view_data();
          }
          else
          {
			$updated = $this->Api_model->updateSingleRow('set_discount', array('id'=>1), array('discount'=>set_value('discount')));
			$this->rediret_after_action($updated, 'Admin/setting');
		  }
		}
		else
		{
			redirect('');
		}
    }


    public function currency_setting()
    {
		if(isset($_SESSION['name']))
        {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('currency', get_phrase('currency') , 'trim|required|integer|greater_than[0]');
		  if($this->form_validation->run() == FALSE)
          {
			$this->session->set_flashdata('addClass', 'addClass-B');
			$this->get_setting_commission_view_data();
          }
          else
          {
			$this->Api_model->updateSingleRow(CRYSET_TBL, array('status'=>1), array('status'=>0));
			$updated = $this->Api_model->updateSingleRow(CRYSET_TBL, array('id'=>set_value('currency')), array('status'=>1));
			$this->rediret_after_action($updated, 'Admin/setting');
		  }
		}
		else
		{
			redirect('');
		}
    }
	
	public function get_setting_commission_view_data()
	{
		$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
		$data['commission_setting']= $this->Api_model->getSingleRow('commission_setting', array('id'=>1));
		$data['set_discount']= $this->Api_model->getSingleRow('set_discount',array('id'=>1));
		
		$data['currency_setting']=$this->Api_model->getAllData(CRYSET_TBL);
		$currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
		$data['currency_type']= $currency_setting->currency_symbol;
		
		$data['page']='setting';
		$this -> load -> view('common/head.php');
		$this -> load -> view($this->sidebar, $data);
		$this -> load ->view('setting.php', $data);
		$this -> load -> view('common/footer.php');
	}

    
    public function firebaseSetting()
    {
		if(isset($_SESSION['name']))
        {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('artist_key', get_phrase('artist_key') , 'required');
		  $this->form_validation->set_rules('customer_key', get_phrase('customer_key'), 'required');
		  if($this->form_validation->run() == FALSE)
          {
			$this->session->set_flashdata('addClass', 'addClass-A');
			$this->get_setting_app_view_data();
          }
          else
          {
			$data = array('artist_key'=>set_value('artist_key'), 'customer_key'=>set_value('customer_key'));
			$updated = $this->Api_model->updateSingleRow('firebase_keys', array('id'=>1), $data);
			$this->rediret_after_action($updated, 'Admin/app_setting');
		  }
		}
		else
		{
			redirect('');
		}
    }

    public function StripSetting()
    {
		if(isset($_SESSION['name']))
        {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('strip_key', get_phrase('strip_key') , 'required');
		  $this->form_validation->set_rules('strip_publishable_key', get_phrase('strip_publishable_key'), 'required');
		  $this->form_validation->set_rules('strip_username', get_phrase('strip_username') , 'trim|required|max_length[100]');
		  $this->form_validation->set_rules('strip_password', get_phrase('strip_password') , 'trim|required|max_length[100]');
		  if($this->form_validation->run() == FALSE)
          {
			$this->session->set_flashdata('addClass', 'addClass-C');
			$this->get_setting_app_view_data();
          }
          else
          {
			$data = array('api_key'=>set_value('strip_key'), 'publishable_key'=>set_value('strip_publishable_key'), 'username'=>set_value('strip_username'), 'password'=>set_value('strip_password'));
			$updated = $this->Api_model->updateSingleRow('stripe_keys', array('id'=>1), $data);
			$this->rediret_after_action($updated, 'Admin/app_setting');
		  }
		}
		else
		{
			redirect('');
		}
    }

    public function referral_setting()
    {
		if(isset($_SESSION['name']))
        {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('referral_type', get_phrase('referral_type') , 'trim|required|integer|in_list[0,1]');
		  $this->form_validation->set_rules('no_of_usages', get_phrase('no_of_usages') , 'trim|required|integer|greater_than[0]');
		  $this->form_validation->set_rules('referral_amount', get_phrase('referral_amount') , 'trim|required|integer|greater_than[0]');
		  if($this->form_validation->run() == FALSE)
          {
			$this->session->set_flashdata('addClass', 'addClass-E');
			$this->get_setting_app_view_data();
          }
          else
          {
			$data = array('type'=>set_value('referral_type'), 'no_of_usages'=>set_value('no_of_usages'), 'amount'=>set_value('referral_amount'));
			$updated = $this->Api_model->updateSingleRow('referral_setting', array('id'=>1), $data);
			$this->rediret_after_action($updated, 'Admin/app_setting');
		  }
		}
		else
		{
			redirect('');
		}
    }

    public function smtpSetting()
    {
		if(isset($_SESSION['name']))
        {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('smtp_email', get_phrase('smtp_email') , 'trim|required|valid_email|max_length[150]');
		  $this->form_validation->set_rules('smtp_password', get_phrase('smtp_password') , 'trim|required|max_length[250]');
		  if($this->form_validation->run() == FALSE)
          {
			$this->session->set_flashdata('addClass', 'addClass-D');
			$this->get_setting_app_view_data();
          }
          else
          {
			$data = array('email_id'=>set_value('smtp_email'), 'password'=>set_value('smtp_password'));
			$updated = $this->Api_model->updateSingleRow('smtp_setting', array('id'=>1), $data);
			$this->rediret_after_action($updated, 'Admin/app_setting');
		  }
		}
		else
		{
			redirect('');
		}
    }

    public function KeySetting()
    {
		if(isset($_SESSION['name']))
        {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('sms_user', get_phrase('sms_user') , 'trim|required|valid_email|max_length[100]');
		  $this->form_validation->set_rules('sms_password', get_phrase('sms_password') , 'trim|required|max_length[100]');
		  $this->form_validation->set_rules('sms_sender', get_phrase('sms_sender') , 'trim|required|max_length[100]');
		  if($this->form_validation->run() == FALSE)
          {
			$this->session->set_flashdata('addClass', 'addClass-F');
			$this->get_setting_app_view_data();
          }
          else
          {
			$data = array('user_id'=>set_value('sms_user'), 'password'=>set_value('sms_password'), 'sender'=>set_value('sms_sender'));
			$updated = $this->Api_model->updateSingleRow('msg_key', array('id'=>1), $data);
			$this->rediret_after_action($updated, 'Admin/app_setting');
		  }
		}
		else
		{
			redirect('');
		}
    }
    public function paySetting()
    {
		if(isset($_SESSION['name']))
        {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('paypal_name', get_phrase('paypal_name') , 'required');
		  $this->form_validation->set_rules('paypal_username', get_phrase('paypal_username'), 'required');
		  $this->form_validation->set_rules('paypal_type', get_phrase('paypal_type') , 'trim|required|integer|in_list[1,2]');
		  if($this->form_validation->run() == FALSE)
          {
			$this->session->set_flashdata('addClass', 'addClass-B');
			$this->get_setting_app_view_data();
          }
          else
          {
			$data = array('name'=>set_value('paypal_name'), 'paypal_id'=>set_value('paypal_username'), 'type'=>set_value('paypal_type'));
			$updated = $this->Api_model->updateSingleRow('paypal_setting', array('id'=>1), $data);
			$this->rediret_after_action($updated, 'Admin/app_setting');
		  }
		}
		else
		{
			redirect('');
		}
    }
	
	public function get_setting_app_view_data()
	{
		$data['firebase_setting']= $this->Api_model->getSingleRow('firebase_keys',array('id'=>1));
        $data['referral_setting']= $this->Api_model->getSingleRow('referral_setting',array('id'=>1));
        $data['stripe_keys']= $this->Api_model->getSingleRow('stripe_keys',array('id'=>1));
        $data['smtp_setting']= $this->Api_model->getSingleRow('smtp_setting',array('id'=>1));
        $data['paypal_setting']= $this->Api_model->getSingleRow('paypal_setting',array('id'=>1));
        $data['key_setting']= $this->Api_model->getSingleRow('msg_key',array('id'=>1));
        $data['page']='app_setting';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('app_setting.php', $data);
        $this -> load -> view('common/footer.php');
	}
	
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
	
     /*All User*/
     public function coupon()
    {
      if(isset($_SESSION['name'])) 
      {
          $coupon= $this->Api_model->getAllData('discount_coupon');
          $currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
          $data['currency_type']= $currency_setting->currency_symbol;

          $data['coupon']= $coupon;
          $data['page']='coupon';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('coupon.php', $data);
          $this -> load -> view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }

     /*All User*/
     public function addArtist()
    {
      if(isset($_SESSION['name'])) 
      {
          $data['page']='artist';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('addArtist.php', $data);
          $this -> load -> view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }


    public function send_email($email_id, $subject, $msg)
    {   
        $smtp_setting=$this->Api_model->getSingleRow('smtp_setting', array('id'=>1));
        $from_email = $smtp_setting->email_id;
        $password = $smtp_setting->password;

        $this->load->library('email'); 
         $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => $from_email,
            'smtp_pass' => $password,
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1'
        );

        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $this->email->from($from_email, APP_NAME); 
        $this->email->to($email_id);
        $this->email->subject($subject); 

        $datas['msg']=$msg;
        $body = $this->load->view('main.php',$datas,TRUE);
        $this->email->message($body);

       $this->email->send();
    }
    
       public function send_email_new_by_msg($email_id,$subject,$msg)
        {
          $msgg=$this->Api_model->getSingleRow('msg_key', array('id'=>1));
          $authKey = $msgg->msg_key;
          $from= SENDER_EMAILL;
          // print_r($from);
          // print_r($authkey);
          // print_r($)

          //Prepare you post parameters
          $postData = array(
              'authkey' => $authKey,
              'to' => $email_id,
              'from' => $from,
              'subject' => $subject,
              'body' => $msg
          );
          
          //API URL
          $url="https://control.msg91.com/api/sendmail.php?authkey='$authKey'&to='$email_id'&from='$from'&body='$msg'&subject='$subject'";
          // init the resource
          $ch = curl_init();
          curl_setopt_array($ch, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_POST => true,
              CURLOPT_POSTFIELDS => $postData
              //,CURLOPT_FOLLOWLOCATION => true
          ));

          //Ignore SSL certificate verification
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

          //get response
          $output = curl_exec($ch);

          //Print error if any
          if(curl_errno($ch))
          {
            echo 'error:' . curl_error($ch);
          }
          curl_close($ch);
    }

    public function send_invoice($email_id, $subject, $data)
    {
        $smtp_setting=$this->Api_model->getSingleRow('smtp_setting', array('id'=>1));
        $from_email = $smtp_setting->email_id;
        $password = $smtp_setting->password;

        $this->load->library('email'); 
         $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => $from_email,
            'smtp_pass' => $password,
            'mailtype'  => 'html',
            'charset'   => 'iso-8859-1'
        );

        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $this->email->from($from_email, APP_NAME); 
        $this->email->to($email_id);
        $this->email->subject($subject); 

        $body = $this->load->view('invoice_tmp.php',$data,TRUE);
        $this->email->message($body);

       $this->email->send();
    }

    public function addArtistAction()
    {
		if(isset($_SESSION['name']))
		{		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('name', get_phrase('name') , 'trim|required|max_length[50]');
		  $this->form_validation->set_rules('email_id', get_phrase('email') , 'trim|required|max_length[70]|is_unique[user.email_id]');
		  $this->form_validation->set_rules('password', get_phrase('password') , 'trim|required|min_length[6]|max_length[25]');
		  if($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
            $data['page']='artist';
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view('addArtist.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {
			$referral_code = $this->api->random_num(6);
			$insert_id = $this->Api_model->insertGetId('user',array('name'=>set_value('name'), 'email_id'=>set_value('email_id'), 'password'=>md5(set_value('password')), 'role'=>1, 'status'=>0, 'created_at'=>time(), 'updated_at'=>time(), 'referral_code'=>$referral_code, 'approval_status'=>1));
			if($insert_id)
			{
				$url= base_url().'Webservice/userActive?user_id='.$insert_id;
                $msg='Thanks for signing up! Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below. Please click ' .$url;
                $this->send_email_new_by_msg($email_id, "FabArtist Registration", $msg);
				
                $this->Api_model->insertGetId('artist',array('user_id'=>$insert_id, 'name'=>set_value('name'), 'created_at'=>time(), 'updated_at'=>time()));
				
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
				redirect('Admin/artists');
			}
			else
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
				redirect('Admin/artists');
			}
          }
		}
		else
		{
			redirect('');
		}
    }
	
	public function getcategories()
	{
		$parent = $_POST['parent'];
		$category = $_POST['category'];
		//$price = $_POST['price'];
		$return = [];
		if($this->security->get_csrf_hash()) $return['token'] = $this->security->get_csrf_hash();
		
		$name = (getSelectedLanguage() == 'arabic')? 'cat_name_ar':'cat_name';
		$categories = $this->Api_model->getAllDataWhere(['id!='=>$category, 'parent_id'=>0, 'status'=>1], 'category');
		
		$return['data'] = '<div class="form-group row"><label class="col-sm-3 col-form-label">'.get_phrase('parent').'</label><div class="col-sm-9"><select name="parent" class="form-control" required><option value="">'.get_phrase('select_category').'</option>';
		foreach ($categories as $category)
		{
			$selected = ($category->id == $parent)? 'selected':'';
			$return['data'] .= '<option value="'.$category->id.'" '.$selected.'>'.$category->$name.'</option>';
		}
		$return['data'] .= '</select></div></div>';
		echo json_encode($return);
		//echo '<div class="form-group row"><label class="col-sm-3 col-form-label">'.get_phrase('commission_rate').'</label><div class="col-sm-6"><input type="number" step="0.01" min="0.01" name="price" value="'.$price.'" class="form-control" required="" placeholder="'.get_phrase('commission_rate').'" /></div><div class="col-sm-3" style="text-align:right;" id="currency"></div></div>';
	}

     /*All User*/
     public function category()
    {
      if(isset($_SESSION['name'])) 
      {
          //$category= $this->Api_model->getAllData('category');
		  $name = (getSelectedLanguage() == 'arabic')? 'name_ar':'name';
		  
		  $categories= $this->Api_model->getCategories($name);
		  $data['count_countries'] = $this->Api_model->getCountWhere('countries', []);

          $currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
          $data['currency_type']= $currency_setting->currency_symbol;
		  
		  foreach($categories as $key => $cat)
		  {
			  $category[$cat->id]['id'] = $cat->id;
			  $category[$cat->id]['cat_name'] = $cat->cat_name;
			  $category[$cat->id]['cat_name_ar'] = $cat->cat_name_ar;
			  $category[$cat->id]['parent_id'] = $cat->parent_id;
			  $category[$cat->id]['created_at'] = $cat->created_at;
			  $category[$cat->id]['updated_at'] = $cat->updated_at;
			  $category[$cat->id]['image'] = $cat->image;
			  $category[$cat->id]['details'] = $cat->details;
			  $category[$cat->id]['status'] = $cat->status;
			  if($cat->price)
			  {
				$category[$cat->id]['prices'][$key]['id'] = $cat->id_price;
				$category[$cat->id]['prices'][$key]['price'] = $cat->price;
				$category[$cat->id]['prices'][$key]['country'] = $cat->country;
				$category[$cat->id]['prices'][$key]['currency'] = $cat->currency;
				$category[$cat->id]['prices'][$key]['status'] = $cat->status_price;
			  }
		  }
		  
          $data['categories']= $category;
          $data['page']='category';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('addCategory.php', $data);
          $this -> load -> view('common/footer.php');
      }
      else
      {
        sredirect('');
      }
    }

    public function editCategory()
    {
      if(isset($_SESSION['name'])) 
      {
        $id= $_GET['id'];
		$name = (getSelectedLanguage() == 'arabic')? 'name_ar':'name';
		//$data['countries'] = $this->Api_model->getCountries($name);
		$data['is_parent'] = $this->Api_model->getCountWhere('category', array('parent_id'=>$id));
        $data['category']= $this->Api_model->getSingleRow('category', array('id'=>$id));
        $data['page']='category';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('editCategory.php', $data);
        $this -> load -> view('common/footer.php');
      }
      else
      {
        redirect('');
      }
    }

    public function services()
    {
      if(isset($_SESSION['name'])) 
      {
          $services= $this->Api_model->getAllData('services');

          $currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
          $data['currency_type']= $currency_setting->currency_symbol;

          $data['services']= $services;
          $data['page']='services';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('addServices.php', $data);
          $this -> load -> view('common/footer.php');
      }
      else
      {
        sredirect('');
      }
    }

    public function editServices()
    {
      if(isset($_SESSION['name'])) 
      {
        $id= $_GET['id'];
        $data['services']= $this->Api_model->getSingleRow('services', array('id'=>$id));
        $data['page']='services';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('editServices.php', $data);
        $this -> load -> view('common/footer.php');
      }
      else
      {
        sredirect('');
      }
    }

     /*All User*/
    public function skills()
    {
      if(isset($_SESSION['name'])) 
      {
        $skills= $this->Api_model->getAllData('skills');
        $skill= array();
        foreach ($skills as $skills) 
        {
          $category= $this->Api_model->getSingleRow('category', array('id'=>$skills->cat_id));
          $skills->cat_name= $category->cat_name;
          array_push($skill, $skills);
        }
        $data['category']= $this->Api_model->getAllData('category');
        $data['skills']= $skill;
        $data['page']='skills';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('addSkills.php', $data);
        $this -> load -> view('common/footer.php');
      }
      else
      {
        sredirect('');
      }
    }

    public function editSkills()
    {
      if(isset($_SESSION['name'])) 
      {
        $id= $_GET['id'];
        $data['category']= $this->Api_model->getAllData('category');
        $data['skills']= $this->Api_model->getSingleRow('skills', array('id'=>$id));
        $data['page']='skills';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('editSkills.php', $data);
        $this -> load -> view('common/footer.php');
      }
      else
      {
        sredirect('');
      }
    }

    /*Add coupon*/
    public function addSkillsAction()
    {
        $data['skill']= $this->input->post('skill', TRUE);
        $data['cat_id']= $this->input->post('cat_id', TRUE);
        $data['created_at']=time();
        $data['updated_at']=time();

        $this->Api_model->insertGetId('skills',$data);
        redirect('Admin/skills');
    }


    /*Add coupon*/
    public function editSkillsAction()
    {
        $id= $this->input->post('id', TRUE);
        $data['skill']= $this->input->post('skill', TRUE);
        $data['cat_id']= $this->input->post('cat_id', TRUE);
        $data['updated_at']=time();

        $this->Api_model->updateSingleRow('skills', array('id'=>$id), $data);
        redirect('Admin/skills');
    }

    /*All Admin*/
    public function manager()
    {
      if(isset($_SESSION['name'])) 
      {
          $admin=$this->Api_model->getAllData('admin');
          $data['role']=$this->Api_model->getAllDataWhere(array('status'=>1), 'role');
          $data['admin']= $admin;
          $data['page']='admin';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('manager.php', $data);
          $this -> load -> view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }

    /*Request For Amount*/
    public function requestAmount()
    {
      if(isset($_SESSION['name'])) 
      {
          $wallet_request=$this->Api_model->getAllData('wallet_request');

          $wallet_requests = array();
          foreach ($wallet_request as $wallet_request) 
          {
            $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$wallet_request->artist_id));
            $wallet_request->email_id=$getUser->email_id;
            $wallet_request->name=$getUser->name;
            $paypal_setting=$this->Api_model->getSingleRow('paypal_setting',array('id'=>1));
            $wallet_request->paypal_id=$paypal_setting->paypal_id;
            array_push($wallet_requests, $wallet_request);

            $getCommission= $this->Api_model->getSingleRow('artist_wallet',array('artist_id'=>$wallet_request->artist_id));

            $onlineEarning=$this->Api_model->getSumWhere('artist_amount', IVC_TBL,array('artist_id'=>$wallet_request->artist_id,'payment_type'=>0));

            $offlineEarning=$this->Api_model->getSumWhere('artist_amount', IVC_TBL,array('artist_id'=>$wallet_request->artist_id,'payment_type'=>1));

            $onlineEarning=round($onlineEarning->artist_amount, 2);
            $cashEarning=round($offlineEarning->artist_amount, 2);

             $currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
            $wallet_request->currency_code=$currency_setting->code;
            if($getCommission)
            {
              $wallet_request->walletAmount= $getCommission->amount;
            }
            else
            {
              $wallet_request->walletAmount= $onlineEarning - $cashEarning;
            }
          }
          $data['wallet_requests']= $wallet_requests;
          $data['page']='requestAmount';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('requestAmount.php', $data);
          $this -> load -> view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }

      /*All User*/
    public function artistDetails()
    {
        if(isset($_SESSION['name'])) 
        {
             $user_id= $_GET['id'];
             
            $reqGet= $this->Api_model->getAllDataWhere(array('user_id'=>$user_id), 'wallet_history');
            
        
           
            $role= $_GET['role'];
            $artist_name= $_GET['artist_name'];
            $language = $this->db->get_where('settings' , array('key' => 'language'))->row()->value;
            $artist=$this->Api_model->getSingleRow('artist',array('user_id'=>$user_id));
            $artist->wallet_history = $reqGet;
            $categoryName = array();
            if(!empty($artist->category_id)) {
                $category_id = explode(',',$artist->category_id);
                foreach($category_id as $category) {
                 $get_cat=$this->Api_model->getSingleRow('category', array('id'=>$category));
                  if($language == 'arabic') {
                    $categoryName[]=$get_cat->cat_name_ar;
                  }
                  else {
                      $categoryName[]=$get_cat->cat_name;
                  }
                }
                $artist->categoryname = implode(',',$categoryName);
            }
            $user=$this->Api_model->getSingleRow('user',array('user_id'=>$user_id));
            $artist->image=$user->image;
            $artist->gender=$user->gender;
             $get_reviews=  $this->Api_model->getAllDataWhere(array('artist_id'=>$user_id), 'rating');
              // $data['get_wallet_amount']=  $this->Api_model->getAllDataWhere(array('artist_id'=>$user_id), 'artist_wallet');
             $data['get_wallet_amount']=  $this->Api_model->getUser($user_id);

            $review = array();
            foreach ($get_reviews as $get_reviews) {
              $get_user = $this->Api_model->getSingleRow('user', array('user_id'=>$get_reviews->user_id));
              $get_reviews->name= $get_user->name;
              $get_reviews->image= base_url()."assets/images/1520435084.png";

              array_push($review, $get_reviews);
            }

            $get_gallery=  $this->Api_model->getAllDataWhere(array('user_id'=>$user_id), 'gallery');

            $jobDone=$this->Api_model->getTotalWhere('artist_booking',array('artist_id'=>$user_id,'booking_flag'=>4));

            $data['total']=$this->Api_model->getTotalWhere('artist_booking',array('artist_id'=>$user_id));
            if($data['total']==0)
            {
                $data['percentages']=0;
            }
            else
            {
                $data['percentages']=($jobDone*100) / $data['total'];
            }
            
            $data['jobDone']=$jobDone;

            if($role==1)
            {      
              $where=array('artist_id'=>$user_id);   

              $get_appointment=$this->Api_model->getAllDataWhere($where,'artist_booking');

              $get_appointments = array();
              foreach ($get_appointment as $get_appointment) 
              {
                $get_user= $this->Api_model->getSingleRow('user', array('user_id'=>$get_appointment->user_id));

                $get_appointment->name= $get_user->name;
                $get_appointment->image= base_url().$get_user->image;
                $get_appointment->address= $get_user->address;

                array_push($get_appointments, $get_appointment);
              }
            }
            elseif($role==2)
            {
              $where=array('user_id'=>$user_id);

              $get_appointment=$this->Api_model->getAllDataWhere($where,'artist_booking');

              $get_appointments = array();
              foreach ($get_appointment as $get_appointment) 
              {
                $get_user= $this->Api_model->getSingleRow('artist', array('user_id'=>$get_appointment->artist_id));
                $get_user->image= base_url().$get_user->image;
                $get_appointment->name= $get_user->name;
                $get_appointment->image= base_url().$get_user->image;
                $get_appointment->address= $get_user->address;

                array_push($get_appointments, $get_appointment);
              } 
            }

            $where=array('user_id'=>$user_id);

            $get_products=$this->Api_model->getAllDataWhere($where,'products');

            $data['get_products']= $get_products;

            $where=array('artist_id'=>$user_id);

            $getInvoice=$this->Api_model->getAllDataWhere($where,'booking_invoice');

            $getInvoices = array();
            foreach ($getInvoice as $getInvoice)
            {
                $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$getInvoice->booking_id));
                if(!empty($getBooking->detail)) {
            $getInvoice->description = $getBooking->detail;
          }
          else {
              if(!empty($getBooking->job_id)) {
                  $getInvoice->description = $this->get_job_description($getBooking->job_id);
              }
            
          }
           $getInvoice->invoice_id = $getInvoice->invoice_id;
              $getInvoice->commission_amount = $getInvoice->category_amount;
              $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$getInvoice->booking_id));

              $getInvoice->booking_time= $getBooking->booking_time;
              $getInvoice->booking_date= $getBooking->booking_date;

              $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$getInvoice->user_id));

              $getInvoice->userName= $getUser->name;
              $getInvoice->address= $getUser->address;

              if($getUser->image)
              {
                $getInvoice->userImage= base_url().$getUser->image;
              }
              else
              {
                $getInvoice->userImage= base_url().'assets/images/a.png';
              }

              $get_artists= $this->Api_model->getSingleRow('artist', array('user_id'=>$getInvoice->artist_id));

              $get_cat=$this->Api_model->getSingleRow('category', array('id'=>$get_artists->category_id));

              $getInvoice->ArtistName=$get_artists->name;
              $getInvoice->categoryName=$get_cat->cat_name;

              if($get_artists->image)
              {
                $getInvoice->artistImage= base_url().$get_artists->image;
              }
              else
              {
                $getInvoice->artistImage= base_url().'assets/images/a.png';
              }
              array_push($getInvoices, $getInvoice);
            }

            $data['get_invoice']= $getInvoices;
            $data['get_appointments']= $get_appointments;
            $data['get_reviews']= $review;
            $data['get_gallery']= $get_gallery;
            $data['artist_name']= $artist_name;
            $data['user']= $artist;
            $data['page']='artist';
            $currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
            $data['currency_type']= $currency_setting->currency_symbol;

            $this -> load -> view('common/head.php');
            $this -> load -> view($this->sidebar, $data);
            $this -> load ->view('artistDetails.php', $data);
            $this -> load -> view('common/footer.php');
        }
        else
        {
            redirect('');
        }
      }

         public function deleteWarningUser()
        {
  
        $user_id= $_GET['user_id'];
        $this->Api_model->deleteRecord(array(
            'user_id' => $user_id
        ), 'user_warning');

        redirect('Admin/warning');
       }

    /*Add coupon*/
    public function addCategoryAction()
    {		
		if(isset($_SESSION['name']))
        {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('cat_name', get_phrase('name') , 'trim|required|max_length[50]|is_unique[category.cat_name]');
		  $this->form_validation->set_rules('cat_name_ar', get_phrase('name') , 'trim|required|max_length[50]|is_unique[category.cat_name_ar]');
		  $this->form_validation->set_rules('image', get_phrase('image'), 'callback_imageSize|callback_imageType');
		  if($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
            $data['page']='category';
			$this->load->view('common/head.php');
			$this->load->view($this->sidebar, $data);
			$this->load->view('addCategory.php', $data);
			$this->load->view('common/footer.php');
          }
          else
          {
			$image = '';
			$image = $this->uploadimg('image', 'uploads/', mt_rand());
			if($image)
			{
				$insert_id = $this->Api_model->insertGetId('category',array('cat_name'=>set_value('cat_name'), 'cat_name_ar'=>set_value('cat_name_ar'), 'parent_id'=>set_value('parent'), 'image'=>base_url().$image, 'price'=>0, 'created_at'=>time(), 'updated_at'=>time()));
				$this->rediret_after_action($insert_id, 'Admin/category');
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
			redirect('');
		}
    }

    /*Add coupon*/
    public function editCategoryAction($id)
    {
		if(isset($_SESSION['name']))
        {
		  $category = $this->Api_model->getSingleRow('category', array('id'=>$id));
		  if($category)
		  {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('id', 'id' , 'trim|required|integer|greater_than[0]');
		  $this->form_validation->set_rules('cat_name', get_phrase('name') , 'trim|required|max_length[50]|callback_check_cat_name');
		  $this->form_validation->set_rules('cat_name_ar', get_phrase('name') , 'trim|required|max_length[50]|callback_check_cat_name_ar');
		  if(!empty($_FILES['image']['tmp_name']))
		  {
			$this->form_validation->set_rules('image', get_phrase('image'), 'callback_imageSize|callback_imageType');
		  }
		  if($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
			$name = (getSelectedLanguage() == 'arabic')? 'name_ar':'name';
			$data['is_parent'] = $this->Api_model->getCountWhere('category', array('parent_id'=>$id));
			$data['category']= $category;
			$data['page']='category';
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view('editCategory.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {
			$data = array('cat_name'=>set_value('cat_name'), 'cat_name_ar'=>set_value('cat_name_ar'), 'parent_id'=>set_value('parent'), 'price'=>0, 'updated_at'=>time());
			
			$image = '';
			if(!empty($_FILES['image']['tmp_name']))
			{
				$image = $this->uploadimg('image', 'uploads/', mt_rand());
				if(file_exists('./'.substr($category->image, strlen(base_url())))) unlink('./'.substr($category->image, strlen(base_url())));
			}
			if($image) $data['image'] = base_url().$image;
			
			$updated = $this->Api_model->updateSingleRow('category', array('id'=>set_value('id')), $data);
			$this->rediret_after_action($updated, 'Admin/category');
		  }
		  }
		  else
		  {
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
			redirect('Admin/category');
		  }
		}
		else
		{
			redirect('');
		}
    }

    public function addServicesAction()
    {
		if(isset($_SESSION['name']))
        {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('serv_name', get_phrase('name') , 'trim|required|max_length[50]|is_unique[services.serv_name]');
		  $this->form_validation->set_rules('serv_name_ar', get_phrase('name') , 'trim|required|max_length[50]|is_unique[services.serv_name_ar]');
		  $this->form_validation->set_rules('image', get_phrase('image'), 'callback_imageSize|callback_imageType');
		  if($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
            $currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
			$data['currency_type']= $currency_setting->currency_symbol;
			$data['services']= $services;
			$data['page']='services';
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view('addServices.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {
			$image = '';
			$image = $this->uploadimg('image', 'uploads/', mt_rand());
			if($image)
			{
				$insert_id = $this->Api_model->insertGetId('services',array('serv_name'=>set_value('serv_name'), 'serv_name_ar'=>set_value('serv_name_ar'), 'image'=>base_url().$image, 'price'=>0, 'created_at'=>time(), 'updated_at'=>time()));
				$this->rediret_after_action($insert_id, 'Admin/services');
			}
			else
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
				redirect('Admin/services');
			}
          }
		}
		else
		{
			redirect('');
		}
    }

    public function editServicesAction($id)
    {
		if(isset($_SESSION['name']))
        {
		  $service = $this->Api_model->getSingleRow('services', array('id'=>$id));
		  if($service)
		  {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('id', 'id' , 'trim|required|integer|greater_than[0]');
		  $this->form_validation->set_rules('serv_name', get_phrase('name') , 'trim|required|max_length[50]|callback_check_serv_name');
		  $this->form_validation->set_rules('serv_name_ar', get_phrase('name') , 'trim|required|max_length[50]|callback_check_serv_name_ar');
		  if(!empty($_FILES['image']['tmp_name']))
		  {
			$this->form_validation->set_rules('image', get_phrase('image'), 'callback_imageSize|callback_imageType');
		  }
		  if($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
			$data['services']= $service;
			$data['page']='services';
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view('editServices.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {
			$data = array('serv_name'=>set_value('serv_name'), 'serv_name_ar'=>set_value('serv_name_ar'), 'price'=>0, 'updated_at'=>time());
			
			$image = '';
			if(!empty($_FILES['image']['tmp_name']))
			{
				$image = $this->uploadimg('image', 'uploads/', mt_rand());
				if(file_exists('./'.substr($service->image, strlen(base_url())))) unlink('./'.substr($service->image, strlen(base_url())));
			}
			if($image) $data['image'] = base_url().$image;
			
			$updated = $this->Api_model->updateSingleRow('services', array('id'=>set_value('id')), $data);
			$this->rediret_after_action($updated, 'Admin/services');
		  }
		  }
		  else
		  {
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
			redirect('Admin/services');
		  }
		}
		else
		{
			redirect('');
		}
    }
    
    /*Add coupon*/
    public function add_coupon()
    {
		if(isset($_SESSION['name']))
      {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('coupon_code', get_phrase('coupon_code') , 'trim|required|max_length[100]|is_unique[discount_coupon.coupon_code]');
		  $this->form_validation->set_rules('description', get_phrase('description') , 'required');
		  $this->form_validation->set_rules('discount_type', get_phrase('discount_type') , 'trim|required|integer');
		  $this->form_validation->set_rules('discount', get_phrase('discount') , 'trim|required|integer|max_length[10]|greater_than[0]');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
			$currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
			$data['currency_type']= $currency_setting->currency_symbol;
			$data['coupon']= $this->Api_model->getAllData('discount_coupon');
			$data['page']='coupon';
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view('coupon.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {
			$insert_id = $this->Api_model->insertGetId('discount_coupon',array('coupon_code'=>set_value('coupon_code'), 'description'=>set_value('description'), 'discount_type'=>set_value('discount_type'), 'discount'=>set_value('discount'), 'created_at'=>time(), 'updated_at'=>time()));
			$this->rediret_after_action($insert_id, 'Admin/coupon');
          }
      }
      else
      {
          redirect('');
      }
    }

    /*Add coupon*/
    public function add_broadcast()
    {
	  if(isset($_SESSION['name']))
      {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('title', get_phrase('title') , 'trim|required|max_length[250]');
		  $this->form_validation->set_rules('msg', get_phrase('message') , 'required');
		  $this->form_validation->set_rules('msg_for', get_phrase('message_for') , 'trim|required|integer');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
			$data['coupon']= $this->Api_model->getAllDataWhere(array('type'=>"All"),'notifications');
			$data['page']='broadcast';
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load -> view('broadcast_msg.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {
			$firebase_broadcast = $this->firebase_broadcast(set_value('msg_for'),set_value('title'),set_value('msg'),BRODCAST_NOTIFICATION);
			if($firebase_broadcast->success)
			{
				$insert_id = $this->Api_model->insertGetId('notifications',array('type'=>'ALL', 'title'=>set_value('title'), 'msg'=>set_value('msg'), 'created_at'=>time()));
				$this->rediret_after_action($insert_id, 'Admin/broadcast_msg');
			}
			else
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
				redirect('Admin/broadcast_msg');
			}
          }
      }
      else
      {
          redirect('');
      }
    }
    /*Firebase for notification*/
    public function firebase_broadcast($index,$title,$msg1,$type)
    {
     
   $firebaseKey=$this->Api_model->getSingleRow('firebase_keys',array('id'=>1));
   // $Topic_name = "/topics/Artist";
   if($index==1)
        {
            $API_ACCESS_KEY= $firebaseKey->customer_key;
            $Topic_name = TOPICS_FOR_CUSTOMERS;
        }
        else
        {
              $API_ACCESS_KEY= $firebaseKey->artist_key;
                $Topic_name = TOPICS_FOR_ARTISTS;
        }
         // $type="All";
          $msg = array
              (
                  'body'    => $msg1,
                  'title'   => $title,
                  'type'    =>  $type,
                  'icon'    => 'myicon',/*Default Icon*/
                  'sound'   =>  'mySound'/*Default sound*/
            );
          
          $fields = array
              (
                  'to'        => $Topic_name,
                  'notification'    => $msg
                 
              );  
      
          $headers = array
              (
                  'Authorization: key='.$API_ACCESS_KEY,
                  'Content-Type: application/json'
              );

          
          #Send Reponse To FireBase Server    
          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
          $result = curl_exec($ch );
          // print_r($result);
          // exit();
          curl_close( $ch );
		  return json_decode($result);
    }



     /*Add coupon*/
    public function add_manager()
    {
	  if(isset($_SESSION['name']))
      {
		  $roles = $this->Api_model->getAllDataWhere(array('status'=>1), 'role');
		  foreach($roles as $role) { $role_id[] = $role->id; }
		  $role_ids = implode(',', $role_id);
		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('name', get_phrase('name') , 'trim|required|max_length[50]|is_unique[admin.name]');
		  $this->form_validation->set_rules('email', get_phrase('email') , 'trim|required|valid_email|max_length[50]|is_unique[admin.email]');
		  $this->form_validation->set_rules('password', get_phrase('password') , 'trim|required|min_length[6]');
		  $this->form_validation->set_rules('role', get_phrase('role') , 'trim|required|integer|in_list['.$role_ids.']');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
			$data['role'] = $roles;
			$data['admin'] = $this->Api_model->getAllData('admin');
			$data['page']='admin';
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view('manager.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {
			$insert_id = $this->Api_model->insertGetId('admin',array('name'=>set_value('name'), 'email'=>set_value('email'), 'password'=>MD5(set_value('password')), 'role'=>set_value('role'), 'status'=>1, 'created_on'=>time(), 'updated_on'=>time()));
			if($insert_id)
			{
				$url = base_url();
				$msg = 'Thanks ! Your account has been created, you can login as Admin with the following credentials and perform  all functionality as an admin. please click here to go to Web Pannel. ' .$url;
				$this->send_email_new_by_msg(set_value('email'), "FabArtist Registration", $msg);
				
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
				redirect('Admin/manager');
			}
			else
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
				redirect('Admin/manager');
			}
          }
      }
      else
      {
          redirect('');
      }
    }
    
    public function role()
    {
      if(isset($_SESSION['name'])) 
      {
          $admin=$this->Api_model->getAllData('role');

          $data['admin']= $admin;
          $data['page']='admin';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('add_role.php', $data);
          $this -> load -> view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
    
    public function add_role()
    {		   
	  if(isset($_SESSION['name']))
      {		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('name', get_phrase('name') , 'trim|required|max_length[100]|is_unique[role.name]');
		  $this->form_validation->set_rules('permission[]', get_phrase('permission') , 'required');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
			$data['admin']= $this->Api_model->getAllData('role');
			$data['page']='admin';
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view('add_role.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {			
			$insert_id = $this->Api_model->insertGetId('role',array('name'=>set_value('name'), 'permission'=>implode(',', set_value('permission')), 'status'=>1, 'created_on'=>time(), 'updated_on'=>time()));
			$this->rediret_after_action($insert_id, 'Admin/role');
          }
      }
      else
      {
          redirect('');
      }
    }
    
    public function edit_role($id)
    {
	  if(isset($_SESSION['name']))
      {
		$role = $this->Api_model->getSingleRow('role',array('id'=>$id));
		if($role)
		{
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('name', get_phrase('name') , 'trim|required|max_length[100]|callback_check_role_name');
		  $this->form_validation->set_rules('permission[]', get_phrase('permission') , 'required');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
			$data['admin']= $this->Api_model->getSingleRow('role', array('id'=>$id));
			$data['page']='role';
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view('edit_role.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {			
			$updated = $this->Api_model->updateSingleRow('role', array('id'=>$id), array('name'=>set_value('name'), 'permission'=>implode(',', set_value('permission')), 'status'=>1, 'updated_on'=>time()));
			$this->rediret_after_action($updated, 'Admin/role');
          }
		}
		else
		{
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
			redirect('Admin/role');
		}
      }
      else
      {
          redirect('');
      }
    }

    /*All Admin*/
    public function editrole($id)
    {
      if(isset($_SESSION['name'])) 
      {
          $admin= $this->Api_model->getSingleRow('role', array('id'=>$id));
          //$permission = $this->db->get_where('role' , array('id' => $admin->role))->row()->permission;
          $data['admin']= $admin;
          //$data['permission'] = $permission;
          $data['page']='role';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('edit_role.php', $data);
          $this -> load -> view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
    
    public function change_status_role()
    {
        $id= $_GET['id'];
        $status= $_GET['status'];
        $where = array('id'=>$id);
        $data = array('status'=>$status);

        $update= $this->Api_model->updateSingleRow('role', $where, $data);
        redirect('Admin/role');      
    }
	
	/*
	** Check Role Name
	*/
	function check_role_name($name)
	{
		$id = ($this->input->post('id'))? $this->input->post('id'):'';
		$result = $this->Api_model->getSingleRow('role',array('id!='=>$id,'name'=>$name));
		if($result)
		{
			$this->form_validation->set_message('check_role_name', get_phrase('name_must_be_unique'));
			$response = false;
		}
		else
		{
			$response = true;
		}
		return $response;
	}
    
    /*Add coupon*/
    public function edit_manager($id)
    {		
	  if(isset($_SESSION['name']))
      {
		$admin = $this->Api_model->getSingleRow('admin',array('id'=>$id));
		if($admin)
		{
		  $roles = $this->Api_model->getAllDataWhere(array('status'=>1), 'role');
		  foreach($roles as $role) { $role_id[] = $role->id; }
		  $role_ids = implode(',', $role_id);
		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('name', get_phrase('name') , 'trim|required|max_length[50]|callback_check_manager_name');
		  $this->form_validation->set_rules('email', get_phrase('email') , 'trim|required|valid_email|max_length[50]|callback_check_manager_email');
		  $this->form_validation->set_rules('password', get_phrase('password') , 'trim|required|min_length[6]');
		  $this->form_validation->set_rules('role', get_phrase('role') , 'trim|required|integer|in_list['.$role_ids.']');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
			$data['role']=$this->Api_model->getAllDataWhere(array('status'=>1), 'role');
			$data['admin']= $this->Api_model->getSingleRow('admin', array('id'=>$id));
			$data['page']='manager';
			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view('editmanager.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {			
			$updated = $this->Api_model->updateSingleRow('admin', array('id'=>$id), array('name'=>set_value('name'), 'email'=>set_value('email'), 'password'=>MD5(set_value('password')), 'role'=>set_value('role'), 'status'=>1, 'updated_on'=>time()));
			$this->rediret_after_action($updated, 'Admin/manager');
          }
		}
		else
		{
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
			redirect('Admin/manager');
		}
      }
      else
      {
          redirect('');
      }
    }

    /*All Admin*/
    public function editmanager($id)
    {
      if(isset($_SESSION['name'])) 
      {
          $admin= $this->Api_model->getSingleRow('admin', array('id'=>$id));
          $data['role']=$this->Api_model->getAllDataWhere(array('status'=>1), 'role');
          $data['admin']= $admin;
          $data['page']='manager';
          $this -> load -> view('common/head.php');
          $this -> load -> view($this->sidebar, $data);
          $this -> load ->view('editmanager.php', $data);
          $this -> load -> view('common/footer.php');
      }
      else
      {
          redirect('');
      }
    }
	
	/*
	** Check Manager Name
	*/
	function check_manager_name($name)
	{
		$id = ($this->input->post('id'))? $this->input->post('id'):'';
		$result = $this->Api_model->getSingleRow('admin',array('id!='=>$id,'name'=>$name));
		if($result)
		{
			$this->form_validation->set_message('check_manager_name', get_phrase('name_must_be_unique'));
			$response = false;
		}
		else
		{
			$response = true;
		}
		return $response;
	}
	
	/*
	** Check Manager Email
	*/
	function check_manager_email($email)
	{
		$id = ($this->input->post('id'))? $this->input->post('id'):'';
		$result = $this->Api_model->getSingleRow('admin',array('id!='=>$id,'name'=>$email));
		if($result)
		{
			$this->form_validation->set_message('check_manager_email', get_phrase('email_must_be_unique'));
			$response = false;
		}
		else
		{
			$response = true;
		}
		return $response;
	}

    /*Change Status Invoice*/
    public function change_status_invoice()
    {
        $id= $_GET['id'];
        $status= $_GET['status'];
        $where = array('id'=>$id);
        $data = array('flag'=>$status,'payment_status'=>1);

        $getInvoice=$this->Api_model->getSingleRow('booking_invoice',array('id'=>$id));
    
        $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$getInvoice->booking_id));

        $getInvoice->booking_time= $getBooking->booking_time;
        $getInvoice->booking_date= $getBooking->booking_date;

        $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$getInvoice->user_id));

        $getInvoice->userName= $getUser->name;
        $getInvoice->userEmail= $getUser->email_id;
        $getInvoice->address= $getUser->address;

        $get_artists= $this->Api_model->getSingleRow('artist', array('user_id'=>$getInvoice->artist_id));
        $getArt= $this->Api_model->getSingleRow('user', array('user_id'=>$getInvoice->artist_id));

        $get_cat=$this->Api_model->getSingleRow('category', array('id'=>$get_artists->category_id));

        $getInvoice->ArtistName=$get_artists->name;
        $getInvoice->ArtistEmail=$getArt->email_id;
        $getInvoice->ArtistLocation=$get_artists->location;
        $getInvoice->categoryName=$get_cat->cat_name;

        $subject='Express Maintenance Invoice';
        $this->send_invoice($getInvoice->userEmail, $subject, $getInvoice);
        $this->send_invoice($getInvoice->ArtistEmail, $subject, $getInvoice);
        $update= $this->Api_model->updateSingleRow('booking_invoice', $where, $data);
        redirect('Admin/allInvoice');    
    }

     /*Change Status Artist*/
     public function change_status_rating()
    {
		$token = '';
		if($this->security->get_csrf_hash()) $token = $this->security->get_csrf_hash();
		
        $id=$this->input->post('id', TRUE);
        $rating_id=$this->input->post('rating_id', TRUE);

        $where = array('id'=>$rating_id);
        $data = array('status'=>$id);

        $update= $this->Api_model->updateSingleRow('rating', $where, $data);
		
		echo json_encode($token);
    }

     public function change_status_artist()
     {
		$token = '';
		if($this->security->get_csrf_hash()) $token = $this->security->get_csrf_hash();
		
        $id=$this->input->post('id', TRUE);
        $user_id=$this->input->post('user_id', TRUE);

        $where = array('user_id'=>$user_id);
        $data = array('status'=>$id);

        $update= $this->Api_model->updateSingleRow('user', $where, $data);
		
		echo json_encode($token);
    }

    public function change_status_category()
     {
		$token = '';
		if($this->security->get_csrf_hash()) $token = $this->security->get_csrf_hash();
		
        $id=$this->input->post('id', TRUE);
        $user_id=$this->input->post('user_id', TRUE);

        $where = array('id'=>$user_id);
        $data = array('status'=>$id);

        $update= $this->Api_model->updateSingleRow('category', $where, $data);
		
		echo json_encode($token);
    }

     /*Change Status Artist*/
    public function change_status_coupon()
    {
        $id= $_GET['id'];
        $status= $_GET['status'];
        $request= $_GET['request'];
        $where = array('id'=>$id);
        $data = array('status'=>$status);

        $update= $this->Api_model->updateSingleRow('discount_coupon', $where, $data);
        redirect('Admin/coupon');    
    }


      /*Change Status Artist*/
    public function change_status_wallet()
    {
      $id= $_GET['id'];
      $artist_id= $_GET['artist_id'];
      $where = array('id'=>$id);
      $data = array('status'=>1);

      $update= $this->Api_model->updateSingleRow('wallet_request', $where, $data);

      $getArt= $this->Api_model->getSingleRow('artist_wallet', array('artist_id'=>$artist_id));
      if($getArt)
      {
        $this->Api_model->updateSingleRow('artist_wallet', array('artist_id'=>$artist_id), array('amount'=>0));
      }
      redirect('Admin/requestAmount');    
    }

     /*Change Status Artist*/
     public function change_status_skills()
    {
        $id= $_GET['id'];
        $status= $_GET['status'];
        $request= $_GET['request'];
        $where = array('id'=>$id);
        $data = array('status'=>$status);

        $update= $this->Api_model->updateSingleRow('skills', $where, $data);
     
        redirect('Admin/skills');    
    }

    public function notifaction()
    {
        $data['user']= $this->Api_model->getAllData('user');
        $data['page']='notification';

        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('notification.php', $data);
        $this -> load -> view('common/footer.php');
    }

     /*All Appointment*/
     public function appointments()
    {
        $user_id= $_GET['id'];
        $role= $_GET['role'];
        $artist_name= $_GET['artist_name'];

      if($role==1)
      {      
        $where=array('artist_id'=>$user_id);   

          $get_appointment=$this->Api_model->getAllDataWhere($where,'artist_booking');

        $get_appointments = array();
        foreach ($get_appointment as $get_appointment) 
        {
          $get_user= $this->Api_model->getSingleRow('user', array('user_id'=>$get_appointment->user_id));

          $get_appointment->name= $get_user->name;
          $get_appointment->image= base_url().$get_user->image;
          $get_appointment->address= $get_user->address;

          array_push($get_appointments, $get_appointment);
        }

      }
      elseif($role==2)
      {
        $where=array('user_id'=>$user_id);

        $get_appointment=$this->Api_model->getAllDataWhere($where,'artist_booking');

            $get_appointments = array();
            foreach ($get_appointment as $get_appointment) 
            {
              $get_user= $this->Api_model->getSingleRow('artist', array('user_id'=>$get_appointment->artist_id));
              $get_user->image= base_url().$get_user->image;

              $get_appointment->name= $get_user->name;
              $get_appointment->image= base_url().$get_user->image;
              $get_appointment->address= $get_user->address;

              array_push($get_appointments, $get_appointment);
            } 
       }

        $data['get_appointments']= $get_appointments;
        $data['artist_name']= $artist_name;
        $data['page']='artist';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('appointments.php', $data);
        $this -> load -> view('common/footer.php');
    }

    /*Get All Products*/
    public function products()
    {
        $user_id= $_GET['id'];
        $role= $_GET['role'];
        $artist_name= $_GET['artist_name'];

        $where=array('user_id'=>$user_id);

        $get_products=$this->Api_model->getAllDataWhere($where,'products');

        $data['get_products']= $get_products;
        $data['artist_name']= $artist_name;
        $data['page']='artist';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('products.php', $data);
        $this -> load -> view('common/footer.php');
    }

    /*Get All Invoice*/
    public function invoice()
    {
        $user_id= $_GET['id'];
        $artist_id= $_GET['artist_id'];
        $artist_name= $_GET['artist_name'];

        $where=array('artist_id'=>$user_id);

        $getInvoice=$this->Api_model->getAllDataWhere($where,'booking_invoice');

        $getInvoices = array();
        foreach ($getInvoice as $getInvoice)
        {
          $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$getInvoice->booking_id));

          $getInvoice->booking_time= $getBooking->booking_time;
          $getInvoice->booking_date= $getBooking->booking_date;

          $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$getInvoice->user_id));

          $getInvoice->userName= $getUser->name;
          $getInvoice->address= $getUser->address;

          if($getUser->image)
          {
            $getInvoice->userImage= base_url().$getUser->image;
          }
          else
          {
            $getInvoice->userImage= base_url().'assets/images/a.png';
          }

          $get_artists= $this->Api_model->getSingleRow('artist', array('user_id'=>$getInvoice->artist_id));

          $get_cat=$this->Api_model->getSingleRow('category', array('id'=>$get_artists->category_id));

          $getInvoice->ArtistName=$get_artists->name;
          $getInvoice->categoryName=$get_cat->cat_name;

          if($get_artists->image)
          {
            $getInvoice->artistImage= base_url().$get_artists->image;
          }
          else
          {
            $getInvoice->artistImage= base_url().'assets/images/a.png';
          }

          array_push($getInvoices, $getInvoice);
        }


        $data['get_invoice']= $getInvoices;
        $data['artist_name']= $artist_name;
      
        $data['page']='artist';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('invoice.php', $data);
        $this -> load -> view('common/footer.php');
    }
    
    public function get_job_description($job_id) {
        return $this->db->get_where('post_job' , array('job_id' => $job_id))->row()->description;
    }

    public function allInvoice()
    {
        $getInvoice=$this->Api_model->getAllData('booking_invoice');
        $getInvoices = array();
        foreach ($getInvoice as $getInvoice)
        {
          $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$getInvoice->booking_id));

          $getInvoice->booking_time= $getBooking->booking_time;
          $getInvoice->booking_date= $getBooking->booking_date;
          if(!empty($getBooking->detail)) {
            $getInvoice->description = $getBooking->detail;
          }
          else {
              if(!empty($getBooking->job_id)) {
                  $getInvoice->description = $this->get_job_description($getBooking->job_id);
              }
            
          }
          $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$getInvoice->user_id));

          $getInvoice->userName= $getUser->name;
          $getInvoice->address= $getUser->address;

          if($getUser->image)
          {
            $getInvoice->userImage= base_url().$getUser->image;
          }
          else
          {
            $getInvoice->userImage= base_url().'assets/images/a.png';
          }

          $get_artists= $this->Api_model->getSingleRow('artist', array('user_id'=>$getInvoice->artist_id));

          $get_cat=$this->Api_model->getSingleRow('category', array('id'=>$get_artists->category_id));

          $getInvoice->ArtistName=$get_artists->name;
          $getInvoice->categoryName=$get_cat->cat_name;

          if($get_artists->image)
          {
            $getInvoice->artistImage= base_url().$get_artists->image;
          }
          else
          {
            $getInvoice->artistImage= base_url().'assets/images/a.png';
          }
          array_push($getInvoices, $getInvoice);
        }

        $data['a_amount']=$this->Api_model->getSum('artist_amount', 'booking_invoice');
        $data['c_amount']=$this->Api_model->getSum('category_amount', 'booking_invoice');
        $data['t_amount']=$this->Api_model->getSum('total_amount', 'booking_invoice');
        $data['p_amount']=$this->Api_model->getSumWhere('total_amount', 'booking_invoice', array('flag'=>1));
        $data['u_amount']=$this->Api_model->getSumWhere('total_amount', 'booking_invoice', array('flag'=>0));
        $currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1));
        $data['currency_type']= $currency_setting->currency_symbol;
        $data['getInvoices']= array_reverse($getInvoices);
        $data['page']='allInvoice';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('allInvoice.php', $data);
        $this -> load -> view('common/footer.php');
    }

    public function newBooking(){
        $getBookings= $this->Api_model->getBookingOrders();


        $data['getBookings']= $getBookings;
        $data['page']='allBooking';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('newAllBooking.php', $data);
        $this -> load -> view('common/footer.php');
    }

    public function editBookingRecord(){
        $booking_id= $_GET['id'];
        $bookingRecord = $this->Api_model->getBookingDetails($booking_id);
        $bookingItems = $this->Api_model->getBookingItems($booking_id);

        $subCategories=  $this->Api_model->getAllDataWhere(array('parent_id'=>$bookingRecord->category_id,'status'=>1), 'category');


        $data['bookingDetails']= $bookingRecord;
        $data['bookingItems']= $bookingItems;
        $data['subCategories']= $subCategories;
        $data['page']='editBooking';


        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('editBooking.php', $data);
        $this -> load -> view('common/footer.php');


    }

    public function deleteBookingItem(){
        $itemId= $_GET['itemId'];
        $bookingId= $_GET['bookingId'];

        $where['id']= $itemId;
        $deleteItem = $this->Api_model->deleteRecord($where, 'booking_order_items');

        $updateBookingPrice = $this->Api_model->updateBookingPrice($bookingId);

        redirect('Admin/editBookingRecord?id='.$bookingId);

    }

    public function saveAddBookingItem()
	{
	  if(isset($_SESSION['name']))
      {
		$category = $this->Api_model->getSingleRow('category',array('id'=>$this->input->post('sub_cat_id', TRUE)));
		$booking = $this->Api_model->getSingleRow('artist_booking',array('id'=>$this->input->post('booking_id', TRUE)));
		if($category and $booking)
		{
		  $roles = $this->Api_model->getAllDataWhere(array('status'=>1), 'role');
		  foreach($roles as $role) { $role_id[] = $role->id; }
		  $role_ids = implode(',', $role_id);
		  
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('booking_id', get_phrase('booking_id') , 'trim|required|integer|greater_than[0]');
		  $this->form_validation->set_rules('sub_cat_id', get_phrase('sub_category_id') , 'trim|required|integer|greater_than[0]');
		  $this->form_validation->set_rules('quantity', get_phrase('quantity') , 'trim|required|integer|greater_than[0]');
		  if ($this->form_validation->run() == FALSE)
          {
			$data['settings'] = $this->Api_model->getSingleRow('settings',array('key'=>'language'));
			$booking_id = $this->input->post('booking_id', TRUE);
			$bookingRecord = $this->Api_model->getBookingDetails($booking_id);
			$bookingItems = $this->Api_model->getBookingItems($booking_id);

			$subCategories=  $this->Api_model->getAllDataWhere(array('parent_id'=>$bookingRecord->category_id,'status'=>1), 'category');

			$data['bookingDetails'] = $bookingRecord;
			$data['bookingItems'] = $bookingItems;
			$data['subCategories'] = $subCategories;
			$data['page'] ='editBooking';

			$this -> load -> view('common/head.php');
			$this -> load -> view($this->sidebar, $data);
			$this -> load ->view('editBooking.php', $data);
			$this -> load -> view('common/footer.php');
          }
          else
          {
			$this->Api_model->updateBookingPrice(set_value('booking_id'));
		
			$insert_id = $this->Api_model->insertGetId('booking_order_items', array('booking_order_id'=>set_value('booking_id'), 'category_id'=>set_value('sub_cat_id'), 'quantity'=>set_value('quantity'), 'cost_per_item'=>$category->price, 'created_by'=>$this->session->id));
			$this->rediret_after_action($insert_id, 'Admin/editBookingRecord?id='.set_value('booking_id'));
          }
		}
		else
		{
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
			redirect('Admin/newBooking');
		}
      }
      else
      {
          redirect('');
      }

    }

    public function bookingArtistSelect(){
        $booking_id= $_GET['id'];
        $request= $_GET['request'];
        $artists = $this->Api_model->getArtistByBookingCity($booking_id);

        $data['artists']= $artists;
        $data['page']='selectArtistForBooking';

        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('selectArtistForBooking.php', $data);
        $this -> load -> view('common/footer.php');


    }
    

    public function saveBookingArtistSelect(){
        $lan = 'en';

        $booking_id= $_POST['booking_id'];
        $artist_id= $_POST['artist_id'];

        $data['artist_id']=$artist_id;
        $data['status_id']=1;
        $data['updated_at']=date('Y-m-d H:i:s');
        $data['updated_by']=$this->session->id;

        $updateBookingPrice = $this->Api_model->updateBookingPrice($booking_id);


        $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$booking_id, 'status_id'=> 0));
        if($getBooking) {

            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well


            $checkArtist= $this->Api_model->getSingleRow(ART_TBL, array('id'=>$artist_id));


            $updateBooking = $this->Api_model->updateSingleRow('artist_booking', array('id' => $booking_id), $data);

            $logdata['booking_order_id'] = $booking_id;
            $logdata['log_type_id'] = 2;
            $logdata['status_id'] = $data['status_id'];
            $logdata['details'] = "";
            $logdata['created_by'] = $this->session->id;
            $bookingOrder = $this->Api_model->insertGetId("booking_orders_logs",$logdata);

            $logdata['booking_order_id'] = $booking_id;
            $logdata['log_type_id'] = 4;
            $logdata['status_id'] = $data['status_id'];
            $logdata['details'] = $checkArtist->name;
            $logdata['created_by'] = $this->session->id;
            $bookingOrder = $this->Api_model->insertGetId("booking_orders_logs",$logdata);


            $checkUser= $this->Api_model->getSingleRow('user', array('user_id'=>$checkArtist->user_id));
            $msg=$checkUser->name.': accepted your appointment.';
            $this->firebase_notification($getBooking->user_id, "Accept Appointment" ,$msg,ACCEPT_BOOKING_ARTIST_NOTIFICATION);

            $this->db->trans_complete(); # Completing transaction

            if ($this->db->trans_status() === FALSE) {
                # Something went wrong.
                $this->db->trans_rollback();
                echo $this->translate('UNEXPECTED_ERR',$lan);
                exit;

            }
            else {
                # Everything is Perfect.
                # Committing data to the database.
                $this->db->trans_commit();
                echo $this->translate('ORDER_ADDED',$lan);

            }

            redirect('Admin/newBooking');


        }else{
            echo "Record not exists";
            exit;
        }

    }
    public function booking()
    {
      return $this->newBooking();


      $getBooking= $this->Api_model->getAllData('artist_booking');
      $getBookings= array();
      $language = $this->db->get_where('settings' , array('key' => 'language'))->row()->value;
      foreach ($getBooking as $getBooking) 
      {
          $get_reviews=  $this->Api_model->getAllDataWhere(array('artist_id'=>$getBooking->artist_id,'status'=>1), 'rating');
          $review = array();
          foreach ($get_reviews as $get_reviews) 
          {
            $get_user = $this->Api_model->getSingleRow('user', array('user_id'=>$get_reviews->user_id));
            $get_reviews->name= $get_user->name;
             if($get_user->image)
            {
              $get_reviews->image= base_url().$get_user->image;
            }
            else
            {
              $get_reviews->image= base_url()."assets/images/image.png";
            }
            array_push($review, $get_reviews);
          }
            $getBooking->reviews=$review;

            $where=array('user_id'=>$getBooking->artist_id);
            $get_artists=$this->Api_model->getSingleRow('artist',$where);

            $get_cat=$this->Api_model->getSingleRow('category', array('id'=>$getBooking->category_id));
          if($get_artists->image)
          {
            $getBooking->artistImage=base_url().$get_artists->image;
          }
          else
          {
            $getBooking->artistImage=base_url()."assets/images/image.png";
          }
            if($language == 'arabic') {
                $getBooking->category_name=$get_cat->cat_name_ar;
            }
            else {
                $getBooking->category_name=$get_cat->cat_name;
            }
            
            $getBooking->artistName=$get_artists->name;
            $getBooking->artistLocation=$get_artists->location;

            $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$getBooking->user_id));
            $getBooking->userName= $getUser->name;
            $getBooking->address= $getUser->address;

            $where= array('artist_id'=>$getBooking->user_id, 'status'=>1);
            $ava_rating=$this->Api_model->getAvgWhere('rating', 'rating',$where);
          if($ava_rating[0]->rating==null)
          {
            $ava_rating[0]->rating="0";
          }
          $getBooking->ava_rating=$ava_rating[0]->rating;

          if($getBooking->start_time)
          {
            $getBooking->working_min= (float)round(abs($getBooking->start_time - time()) / 60,2);
          }
          else
          {
            $getBooking->working_min=0;
          }
          if($getUser->image)
          {
           $getBooking->userImage= base_url().$getUser->image;
          }
          else
          {
           $getBooking->userImage= base_url().'assets/images/image.png';
          }
          if(!empty($getBooking->service_id)) {
            $matches = preg_replace("/[^0-9]/","",$getBooking->service_id);
            $detail=$this->Api_model->getSingleRow('products', array('id'=>$matches));
            if(!empty($detail)){
              $getBooking->booking_deails = 'Service Detail: '.$detail->product_name;
            }
            else {
              $getBooking->booking_deails = 'No Service Details';
            }
          }
          elseif(!empty($getBooking->job_id)) {
            $detail=$this->Api_model->getSingleRow('post_job', array('job_id'=>$getBooking->job_id));
            if(!empty($detail)){
              $getBooking->booking_deails = 'Job Detail: '.$detail->description;
            }
            else {
              $getBooking->booking_deails = 'No Job Details';
            }
          }
          else {
            $getBooking->booking_deails = 'No Description Details';
          }
          array_push($getBookings, $getBooking);
      }
       
        $data['getBookings']= $getBookings;
        $data['page']='allBooking';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('allBooking.php', $data);
        $this -> load -> view('common/footer.php');
    }

     public function accept_booking($booking_id)
    {

      $data['booking_flag'] =1;

      $updateBookingPrice = $this->Api_model->updateBookingPrice($booking_id);

      $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$booking_id));
      if($getBooking)
      {
        $updateBooking=$this->Api_model->updateSingleRow('artist_booking',array('id'=>$booking_id),$data);

        if($updateBooking)
        {
          $checkUser= $this->Api_model->getArtistUser($getBooking->artist_id);
          $msg=$checkUser->name.': accepted your appointment.';
          $this->firebase_notification($getBooking->user_id, "Accept Appointment" ,$msg,ACCEPT_BOOKING_ARTIST_NOTIFICATION);

          redirect('Admin/booking');
        }
        else
        {
          redirect('Admin/booking');
        }
      }
      else
        {
          redirect('Admin/booking');
        }
    }

     /*Start Booking*/
    public function start_booking($booking_id)
    {
      $data['status_id'] =3;
      $data['start_time'] =time();

        $updateBookingPrice = $this->Api_model->updateBookingPrice($booking_id);

        $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$booking_id));
      if($getBooking)
      {
        $updateBooking=$this->Api_model->updateSingleRow('artist_booking',array('id'=>$booking_id),$data);
        if($updateBooking)
        {
         $updateUser=$this->Api_model->updateSingleRow('artist',array('id'=>$getBooking->artist_id),array('booking_flag'=>1));

            $logdata['booking_order_id'] = $booking_id;
            $logdata['log_type_id'] = 2;
            $logdata['status_id'] = $data['status_id'];
            $logdata['details'] = "";
            $logdata['created_by'] = $this->session->id;
            $bookingOrder = $this->Api_model->insertGetId("booking_orders_logs",$logdata);


          $checkUser= $this->Api_model->getArtistUser($getBooking->artist_id);
          $msg='Your booking started successfully.';
          $this->firebase_notification($getBooking->user_id, "Start Appointment" ,$msg,START_BOOKING_ARTIST_NOTIFICATION);

          redirect('Admin/booking');
        }
        else
        {
          redirect('Admin/booking');
        }
      }
      else
      {
        redirect('Admin/booking');
      }
    }

     /*Complete Booking (End)*/
    public function end_booking($booking_id)
    {
      $data['status_id'] = 4;
      $data['end_time'] =time();

      $updateBookingPrice = $this->Api_model->updateBookingPrice($booking_id);

      $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$booking_id));
      //print_r($getBooking); die;
      if($getBooking)
      {
        $updateBooking=$this->Api_model->updateSingleRow('artist_booking',array('id'=>$booking_id),$data);
        if($updateBooking)
        {
          $artist_id=$getBooking->artist_id;
          $user_id=$getBooking->user_id;
          $updateUser=$this->Api_model->updateSingleRow('artist',array('id'=>$artist_id),array('booking_flag'=>0));

            $logdata['booking_order_id'] = $booking_id;
            $logdata['log_type_id'] = 2;
            $logdata['status_id'] = $data['status_id'];
            $logdata['details'] = "";
            $logdata['created_by'] = $this->session->id;
            $bookingOrder = $this->Api_model->insertGetId("booking_orders_logs",$logdata);


          $working_min= (float)round(abs($getBooking->start_time - $getBooking->end_time) / 60,2);
          $min_price = ($getBooking->price)/60;
          $getArtist= $this->Api_model->getSingleRow(ART_TBL, array('id'=>$artist_id));

          $countryDetails = $this->Api_model->getSingleRow('countries', array('id'=>$getBooking->country_id));


//          if($getArtist->artist_commission_type==1)
//          {
//            $f_amount =$getBooking->price;
//          }
//          else
//          {
//            $f_amount =$working_min*$min_price;
//          }

          $total_amount =$getBooking->price;
          $artist_amount = round(($total_amount * 0.90), 2);
          $tax_amount = round((($total_amount * $countryDetails->vat_value) /100), 2);
          $f_amount =$total_amount + $tax_amount;


          $commission_setting= $this->Api_model->getSingleRow('commission_setting',array('id'=>1));
          $datainvoice['commission_type']=$commission_setting->commission_type;
          $datainvoice['flat_type']=$commission_setting->flat_type;
//          if($commission_setting->commission_type==0)
//          {
//            $total_amount= $f_amount + $getBooking->category_price;
//            $datainvoice['category_amount']= $getBooking->category_price;
//          }
//          elseif($commission_setting->commission_type==1)
//          {
//            if($commission_setting->flat_type==2)
//            {
//              $total_amount= $f_amount + $commission_setting->flat_amount;
//              $datainvoice['category_amount']= $commission_setting->flat_amount;
//            }
//            elseif ($commission_setting->flat_type==1)
//            {
//              $total_amount= $f_amount + ($f_amount*$commission_setting->flat_amount)/100;
//              $datainvoice['category_amount']= ($f_amount*$commission_setting->flat_amount)/100;
//            }
//          }

          $datainvoice['artist_id']= $artist_id;
          $datainvoice['user_id']= $user_id;
          $datainvoice['invoice_id']= strtoupper($this->api->strongToken());
          $datainvoice['booking_id']= $booking_id;
          $datainvoice['working_min']= (float)round($working_min,2);
          $datainvoice['artist_amount']= $artist_amount;
          $datainvoice['category_amount']= $total_amount - $artist_amount;
          $datainvoice['tax']= $tax_amount;
          $datainvoice['total_amount']= $total_amount;
          $datainvoice['final_amount']= round($f_amount,2);
          $currency_setting= $this->Api_model->getSingleRow('currency_setting',array('status'=>1, 'country_id'=>$getBooking->country_id));
          $datainvoice['currency_type']= $currency_setting->id;
          $date= date('Y-m-d');
          $datainvoice['created_at']=time();
          $datainvoice['updated_at']=time();

          $invoiceId= $this->Api_model->insertGetId('booking_invoice', $datainvoice);

          $getUser= $this->Api_model->getSingleRow('user', array('user_id'=>$getBooking->user_id));
          $getBooking->userName= $getUser->name;
          $getBooking->address= $getUser->address;
          $getBooking->total_amount= $f_amount;
          $getBooking->working_min= (float)$working_min;

          if($getUser->image)
          {
            $getBooking->userImage= base_url().$getUser->image;
          }
          else
          {
            $getBooking->userImage= base_url().'assets/images/image.png';
          }


            //artist wallet
            $booking_invoice= $this->Api_model->getSingleRow('booking_invoice', array('id'=>$invoiceId));
            $getArt= $this->Api_model->getSingleRow('artist_wallet', array('artist_id'=>$getBooking->artist_id));
            if($getArt)
            {
                $artist_amount = $getArt->amount + $booking_invoice->artist_amount;
                $this->Api_model->updateSingleRow('artist_wallet', array('artist_id'=>$booking_invoice->artist_id), array('amount'=>$artist_amount));
            }else{
                $walletData1['artist_id'] = $booking_invoice->artist_id;
                $walletData1['amount'] = $booking_invoice->artist_amount;
                $walletRecord = $this->Api_model->insertGetId("artist_wallet",$walletData1);
            }

            $walletData['artist_id'] = $booking_invoice->artist_id;
            $walletData['artist_wallet_transaction_types_id'] = 1;
            $walletData['reference_id'] = $invoiceId;
            $walletData['created_by'] = null;
            $walletData['updated_by'] = null;
            $walletData['created_at'] = date('Y-m-d H:i:s');
            $walletData['updated_at'] = date('Y-m-d H:i:s');
            $walletData['amount'] = $booking_invoice->artist_amount;
            $walletTransactionRecord = $this->Api_model->insertGetId("artist_wallet_transactions",$walletData);


          $checkUser= $this->Api_model->getSingleRow('user', array('user_id'=>$getBooking->artist_id));
          $msg='Your booking end successfully.';
          $this->firebase_notification($getBooking->user_id, "End Appointment" ,$msg,END_BOOKING_ARTIST_NOTIFICATION);

          $dataNotification['user_id']= $getBooking->user_id;
          $dataNotification['title']= "End Appointment";
          $dataNotification['msg']= $msg;
          $dataNotification['type']= "Individual";
          $dataNotification['created_at']=time(); 
          $this->Api_model->insertGetId('notifications',$dataNotification);

          redirect('Admin/booking');
        }
        else
        {
          redirect('Admin/booking');
        }
      }
      else
      {
        redirect('Admin/booking');
      }
    }

    public function decline_booking()
    {
      $booking_id= $_GET['id'];
      $data['declined_by'] =$this->session->id;
      $data['decline_reason'] ="Decline by admin";
      $data['status_id'] =2;

      $getBooking= $this->Api_model->getSingleRow('artist_booking', array('id'=>$booking_id));
      if($getBooking)
      {
        $updateBooking=$this->Api_model->updateSingleRow('artist_booking',array('id'=>$booking_id),$data);
        if($updateBooking)
        {

            $logdata['booking_order_id'] = $booking_id;
            $logdata['log_type_id'] = 2;
            $logdata['status_id'] = $data['status_id'];
            $logdata['details'] = "";
            $logdata['created_by'] = $this->session->id;
            $bookingOrder = $this->Api_model->insertGetId("booking_orders_logs",$logdata);

          $checkUser= $this->Api_model->getSingleRow('user', array('user_id'=>$getBooking->artist_id));
          $msg=$checkUser->name.': is decline your appointment.';
          $this->firebase_notification($getBooking->user_id, "Decline Appointment" ,$msg,DECLINE_BOOKING_ARTIST_NOTIFICATION);

          $updateUser=$this->Api_model->updateSingleRow('artist',array('user_id'=>$getBooking->artist_id),array('booking_flag'=>0));
          redirect('Admin/booking');
        }
        else
        {
          redirect('Admin/booking');
        }
      }
      else
        {
          redirect('Admin/booking');
        }
    }

     /*Case 1 accept booking 2 start booking 3 end booking*/
    public function booking_operation()
    {
        $booking_id= $_GET['id'];
        $request= $_GET['request'];

        switch ($request) 
        {
          case 1:
             $this->accept_booking($booking_id);
          break;

          case 2:
              $this->start_booking($booking_id);
          break;

          case 3:
              $this->end_booking($booking_id);
          break;
          default:
           redirect('Admin/booking');
      }
    }

    /*Change Status Artist*/
     public function change_status_ticket()
     {
        $id= $_GET['id'];
        $status= $_GET['status'];
        $where = array('id'=>$id);
        $data = array('status'=>$status);

        $get_user=$this->Api_model->getSingleRow('ticket', array('id'=>$id));
        $user_id= $get_user->user_id;

        if($status==1)
        {
          $title="Ticket ".$id;
          $msg1="We are working on your issue";
          $this->firebase_notification($user_id,$title,$msg1,TICKET_STATUS_NOTIFICATION);

          $data1['user_id']= $get_user->user_id;
          $data1['title']= $title;
          $data1['msg']= $msg1;
          $data1['type']= "Individual";
          $data1['created_at']= time();
          $this->Api_model->insertGetId('notifications',$data1);
        }
        elseif($status==2)
        {
          $title="Ticket ".$id;
          $msg1="Your issue has been resolved successfully. Please view it. The ticket is closed now.";
          $data1['user_id']= $get_user->user_id;
          $data1['title']= $title;
          $data1['msg']= $msg1;
          $data1['type']= "Individual";
          $data1['created_at']= time();

          $this->Api_model->insertGetId('notifications',$data1);
          $this->firebase_notification($user_id,$title,$msg1,TICKET_STATUS_NOTIFICATION);
        }

        $update= $this->Api_model->updateSingleRow('ticket', $where, $data);
        redirect('Admin/ticket');      
     }

    /*Change Status Artist*/
     public function change_status_admin()
     {
        $id= $_GET['id'];
        $status= $_GET['status'];
        $where = array('id'=>$id);
        $data = array('status'=>$status);

        $update= $this->Api_model->updateSingleRow('admin', $where, $data);
        redirect('Admin/manager');      
     }

    /*Change Status Artist*/
    public function change_status_featured()
    {
        $id= $_GET['id'];
        $status= $_GET['status'];
        $request= $_GET['request'];
        $where = array('user_id'=>$id);
        $data = array('featured'=>$status);

        $update= $this->Api_model->updateSingleRow('artist', $where, $data);

        redirect('Admin/artists');      
    }

     /*Change Status Artist*/
    public function artist_approve()
    {
      $id= $_GET['id'];
      $where = array('user_id'=>$id);
      $data = array('approval_status'=>1);

      $update= $this->Api_model->updateSingleRow('user', $where, $data);
      redirect('Admin/artists');      
    }

   public function login()
    {
      $email= $this->input->post('email', TRUE);
      $password=md5($this->input->post('password', TRUE));

      $data['email']= $email;
      $data['password']= $password;
      $sess_array=array();
      $getdata=$this->Api_model->getSingleRow('admin',$data);
      $role = $this->Api_model->getSingleRow('role',array('id'=>$getdata->role));
      if($getdata)
      {           
        if($getdata->status==1)
        {
          $this->session->unset_userdata($sess_array);
          $sess_array = array(
           'name' => $getdata->name,
           'id' => $getdata->id,
           'role' => $role->name,
         );

         $this->session->set_userdata( $sess_array);
          $dataget['get_data'] =$getdata;
          $dataget['see_data'] =$sess_array;
          redirect('Admin/home');    
        }
        else
        {
          $this->session->set_flashdata('block', 'You action has been block. Contact to admin.');
            redirect('');
        }
      }
      else
      {
        $this->session->set_flashdata('msg', 'Please enter valid Username or Password');
        redirect('');
      }
    }

    /* View Artist Profile*/
    public function profile_artist()
    {
        $user_id= $_GET['id'];

        $artist=$this->Api_model->getSingleRow('artist',array('user_id'=>$user_id));

        $data['user']= $artist;
        $data['page']='artist';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('profile.php', $data);
        $this -> load -> view('common/footer.php');
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

    public function logout()
    {
      $this->session->sess_destroy();         
        redirect('./', 'refresh');
    }

    public function payufailure()
    {
      $datas['artist_id'] = $_GET['getUserId'];
      $datas['request_id'] = $_GET['getUserId'];
      $datas['amount'] = $_GET['getpost'];
      $datas['status'] = 0;
      $datas['created_at'] = time();
      $data['page']='artist';
      $this->Api_model->insertGetId('paymentHistory',$datas);

      $this->Api_model->updateSingleRow('wallet_request', array('id'=>$datas['request_id']), array('status'=>0));

      $this -> load -> view('common/head.php');
      $this -> load -> view($this->sidebar, $data);
      $this -> load ->view('payufailure.php', $data);
      $this -> load -> view('common/footer.php');
    }


    public function payusuccess()
    {
      $datas['user_id'] = $_GET['getUserId'];
      $request_id = $_GET['request_id'];
      $datas['amount'] = $_GET['getpost'];
      $datas['created_at'] = time();
      $data['page']='artist';
      $this->Api_model->insertGetId('paymentHistory',$datas);

      $this->Api_model->updateSingleRow('wallet_request', array('id'=>$request_id), array('status'=>1));
      $this->Api_model->updateSingleRow('artist_wallet', array('artist_id'=>$datas['user_id']), array('amount'=>0));
      $this -> load -> view('common/head.php');
      $this -> load -> view($this->sidebar, $data);
      $this -> load ->view('payusuccess.php', $data);
      $this -> load -> view('common/footer.php');
    }
    /*Firebase for notification*/
    public function firebase_notification($user_id,$title,$msg1,$type)
    {
     
      $get_data= $this->Api_model->getSingleRow('user',array('user_id'=>$user_id));

      if($get_data->device_token)
      {
        $firebaseKey=$this->Api_model->getSingleRow('firebase_keys',array('id'=>1));
    		if($get_data->role==1)
    		{
    		 $API_ACCESS_KEY= $firebaseKey->artist_key;
    		}
    		else
    		{
    		 $API_ACCESS_KEY= $firebaseKey->customer_key;
    		}

        $registrationIds =$get_data->device_token;

          $msg = array
              (
                  'body'    => $msg1,
                  'title'   => $title,
                  'type'   => $type,
                  'icon'    => 'myicon',/*Default Icon*/
                  'sound'   =>  'mySound'/*Default sound*/
            );
          $fields = array
              (
                  'to'        => $registrationIds,
                  'data'    => $msg,
                  'priority' => 10
              );       
      
          $headers = array
              (
                  'Authorization: key='.$API_ACCESS_KEY,
                  'Content-Type: application/json'
              );
          
          #Send Reponse To FireBase Server    
          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
          $result = curl_exec($ch );
          curl_close( $ch );
        }
        else
        {
            
        }
    }

    public function firebase()
    {
		$message = [];
		$return = [];
		$send = 0;
		if($this->security->get_csrf_hash()) $return['token'] = $this->security->get_csrf_hash();
  
      $type=ADMIN_NOTIFICATION;
      $mobile=$this->input->post('mobile');
      $title=$this->input->post('title');
      $msg1=$this->input->post('msg');

       if(!empty($mobile) and !empty($title) and strlen($title) <= 250 and !empty($msg1))
	   {
		$send = 1;
		for($i=0;$i<count($mobile);$i++)
        {
          $search = str_replace(' ', '', $mobile[$i]);

          if (!filter_var($search, FILTER_VALIDATE_EMAIL)) {
            $user = $this->db->where('mobile',$search)->get('user')->row();
          }
          else {
            $user = $this->db->where('email_id',$search)->get('user')->row();
          }
            $deviceToken = $user->device_token;
            //$mobile_sent = $mobile[$i];
            $title_sent = $title;
            $msg_sent = $msg1;

            if (!filter_var($search, FILTER_VALIDATE_EMAIL)) {
              $user=$this->Api_model->getSingleRow('user',array('mobile'=>$search));
            }
            else {
              $user=$this->Api_model->getSingleRow('user',array('email_id'=>$search));
            }
            

            $data['user_id']= $user->user_id;
            $data['title']= $title;
            $data['msg']= $msg1;
            $data['type']= "Individual";
            $data['created_at']= time();

             $firebaseKey=$this->Api_model->getSingleRow('firebase_keys',array('id'=>1));
	          if($user->role==1)
	          {
	             $API_ACCESS_KEY= $firebaseKey->artist_key;
	          }
	          else
	          {
	             $API_ACCESS_KEY= $firebaseKey->customer_key;
	          }

          $registrationIds =$user->device_token;

          $msg = array
              (
                  'body'    => $msg1,
                  'title'   => $title,
                  'type'   => $type,
                  'icon'    => 'myicon',/*Default Icon*/
                  'sound'   =>  'mySound'/*Default sound*/
            );
          $fields = array
              (
                  'to'        => $registrationIds,
                  'data'    => $msg,
                  'priority' => 10
              );       
      
          $headers = array
              (
                'Authorization: key='.$API_ACCESS_KEY,
                  'Content-Type: application/json'
              );
          
          #Send Reponse To FireBase Server    
          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
          $result = json_decode(curl_exec($ch));
		  if(!$result->success) $message[] = '<br>'.$user->name;
		  else $this->Api_model->insertGetId('notifications',$data);
          curl_close( $ch );           
        } 
	   }
        //return $result;
		if(!$send) { $return['success'] = 0; $message[] = '<br>Please enter Notifaction Title with 255 characters at most and Message'; }
		elseif(empty($message)) $return['success'] = 1;
		else $return['success'] = 0;
		$return['message'] = implode('', $message);
		echo json_encode($return);
    } 

    /**********************Packages*************************/

    /*Show Packages*/
    public function packages()
    {   
        $data['packages']=  $this->Api_model->getAllData('packages');
        $data['page']='packages';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('packages.php', $data);
        $this -> load -> view('common/footer.php');
    }

    public function add_packages()
    {   
        $data['page']='packages';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('add_packages.php', $data);
        $this -> load -> view('common/footer.php');
    }

    public function packageAction()
    {   
       $data['title']=$this->input->post('title');
       $data['description']=$this->input->post('description');
       $data['price']=$this->input->post('price');
       $data['subscription_type']=$this->input->post('type');
       $this->Api_model->insertGetId('packages',$data);
       redirect('Admin/packages','refresh');
    }    

     /*Change Status Artist*/
    public function change_status_package()
    {
      $id= $_GET['id'];
      $status= $_GET['status'];
      $where = array('id'=>$id);
      $data = array('status'=>$status);

      $update= $this->Api_model->updateSingleRow('packages', $where, $data);

      redirect('Admin/packages');    
    }

    public function edit_package($id)
    {   
        $data['get_package']=$this->Api_model->getSingleRow('packages', array('id'=>$id));
        $data['page']='packages';
        $this -> load -> view('common/head.php');
        $this -> load -> view($this->sidebar, $data);
        $this -> load ->view('edit_packages.php', $data);
        $this -> load -> view('common/footer.php');
    }

    public function editPackageAction()
    {   
       $data['title']=$this->input->post('title');
       $id=$this->input->post('id');
       $data['description']=$this->input->post('description');
       $data['price']=$this->input->post('price');
       $data['subscription_type']=$this->input->post('type');
       $this->Api_model->updateSingleRow('packages',array('id'=> $id),$data);
       redirect('Admin/packages','refresh');
    } 
    
    public function change_price()
	{		
		if(isset($_SESSION['name']))
        {
		  $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		  $this->form_validation->set_rules('invoice_id', 'id' , 'trim|required|integer');
		  $this->form_validation->set_rules('price', get_phrase('price') , 'trim|required|max_length[8]');
		  if($this->form_validation->run() == FALSE)
          {
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = get_phrase('price_must_be_8_digits');
			redirect('Admin/allInvoice?error=empty','refresh');
          }
          else
          {
			$commission_setting = $this->Api_model->getSingleRow('commission_setting',array('id'=>1));
			$commission_percentage = $commission_setting->flat_amount;
			
			$get_invoice = $this->Api_model->getSingleRow('booking_invoice', array('id'=>set_value('invoice_id')));
			if($get_invoice)
			{
				$price = set_value('price');
				$artist_amount= round($price, 2) - round(($price*$commission_setting->flat_amount)/100,2);
				$category_amount = $price - $artist_amount;
				
				$this->Api_model->updateSingleRow('booking_invoice', array('id'=>set_value('invoice_id')), array('total_amount'=>$price, 'artist_amount'=>$artist_amount, 'category_amount'=>$category_amount, 'final_amount'=>$price));
				$updated = $this->Api_model->updateSingleRow('artist_booking', array('id'=>$get_invoice->booking_id), array('price'=>$price));
				
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'action_done_successfully';
				redirect('Admin/allInvoice');
			}
			else
			{
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'failed'; $_SESSION['message'] = 'somthing_wrong_happened';
				redirect('Admin/allInvoice');
			}
		  }
		}
		else
		{
			redirect('');
		}
    }

    function translate($constant,$lan) {
        if($lan == 'ar') {
            $constant = $constant.'_AR';
        }
        return constant($constant);
    }
	
	/*
	** Check Category Name
	*/
	function check_cat_name($name)
	{
		$id = ($this->input->post('id'))? $this->input->post('id'):'';
		$result = $this->Api_model->getSingleRow('category',array('id!='=>$id,'cat_name'=>$name));
		if($result)
		{
			$this->form_validation->set_message('check_cat_name', get_phrase('this_field_must_be_unique'));
			$response = false;
		}
		else
		{
			$response = true;
		}
		return $response;
	}
	
	/*
	** Check Category Name AR
	*/
	function check_cat_name_ar($name)
	{
		$id = ($this->input->post('id'))? $this->input->post('id'):'';
		$result = $this->Api_model->getSingleRow('category',array('id!='=>$id,'cat_name_ar'=>$name));
		if($result)
		{
			$this->form_validation->set_message('check_cat_name_ar', get_phrase('this_field_must_be_unique'));
			$response = false;
		}
		else
		{
			$response = true;
		}
		return $response;
	}
	
	/*
	** Check Service Name
	*/
	function check_serv_name($name)
	{
		$id = ($this->input->post('id'))? $this->input->post('id'):'';
		$result = $this->Api_model->getSingleRow('services',array('id!='=>$id,'serv_name'=>$name));
		if($result)
		{
			$this->form_validation->set_message('check_serv_name', get_phrase('this_field_must_be_unique'));
			$response = false;
		}
		else
		{
			$response = true;
		}
		return $response;
	}
	
	/*
	** Check Service Name AR
	*/
	function check_serv_name_ar($name)
	{
		$id = ($this->input->post('id'))? $this->input->post('id'):'';
		$result = $this->Api_model->getSingleRow('services',array('id!='=>$id,'serv_name_ar'=>$name));
		if($result)
		{
			$this->form_validation->set_message('check_serv_name_ar', get_phrase('this_field_must_be_unique'));
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
		if ($_FILES['image']['size'] > 1024000)
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
		if(!in_array(mime_content_type($_FILES['image']['tmp_name']), array('image/jpeg', 'image/gif', 'image/png')))
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