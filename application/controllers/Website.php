<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends CI_Controller
{
  /*For localhost*/
 
 // public $sidebar= 'common/sidebarlocal.php';
    /*For sever*/
    
	public function __construct()
    {
        parent::__construct();
		$this->languages = array('en','ar');
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('api');
        $this->load->library('form_validation');
        $this->load->model('Comman_model');
        $this->load->model('Api_model');
    }
	
	public function page404($lang='en')
    {
		$data['page'] = '404';
		$data['lang'] = (in_array($lang,$this->languages))? $lang:'en';
		$this->lang->load('frontend', $data['lang']);
		$this->output->set_status_header('404');
		$this->load->view('website/header.php', $data);
		$this->load->view('website/404.php', $data);
		$this->load->view('website/footer.php', $data);
    }
	
	public function login($lang='en')
    {
		if(in_array($lang,$this->languages))
		{
			$data['page'] = 'login';
			$data['lang'] = $lang;
			$this->lang->load('frontend', $lang);
			$this->load->view('website/header.php', $data);
			$this->load->view('website/login.php');
			$this->load->view('website/footer.php', $data);
		}
		else redirect('404');
    }

    public function index($lang='en')
    {
		if(in_array($lang,$this->languages))
		{
			$data['page'] = '';
			$data['lang'] = $lang;
			$this->lang->load('frontend', $lang);
			//$this->config->set_item('language', $lang);
			$this->load->view('website/header.php', $data);
			//$this -> load -> view($this->sidebar, $data);
			$this->load->view('website/index.php', $data);
			$this->load->view('website/footer.php', $data);
		}
		else redirect('404');
    }
	
	public function hero($lang='en')
    {
		if(in_array($lang,$this->languages))
		{
			$categories = $this->Api_model->getAllDataWhere(['status'=>1], 'category');
			$array = [];
			foreach($categories as $key => $category)
			{
				if($category->parent_id)
				{
					$array[$category->parent_id]['children'][$key]['id'] = $category->id;
					$array[$category->parent_id]['children'][$key]['parent'] = $category->parent_id;
					$array[$category->parent_id]['children'][$key]['cat_name'] = $category->cat_name;
					$array[$category->parent_id]['children'][$key]['cat_name_ar'] = $category->cat_name_ar;
				}
				else
				{
					$array[$category->id]['id'] = $category->id;
					$array[$category->id]['parent'] = $category->parent_id;
					$array[$category->id]['cat_name'] = $category->cat_name;
					$array[$category->id]['cat_name_ar'] = $category->cat_name_ar;
				}
			}
		
			$data['categories'] = $array;
			$data['page'] = 'hero';
			$data['lang'] = $lang;
			$this->lang->load('frontend', $lang);
			$this->load->view('website/header.php', $data);
			$this->load->view('website/hero.php', $data);
			$this->load->view('website/footer.php', $data);
		}
		else redirect('404');
    }
	
	public function getcities()
	{
		$id = $_POST['id'];
		$lang = $_POST['lang'];
		$return = [];
		if($this->security->get_csrf_hash()) $return['token'] = $this->security->get_csrf_hash();
		
		$this->lang->load('frontend', $lang);
		$cities = $this->Api_model->getAllDataWhere(['country_id'=>$id, 'active'=>1], 'cities');
		
		$return['data'] = '<select name="city" class="form-control"><option value="">'.$this->lang->line('choosecity').'</option>';
		if(!empty($cities))
		{
			foreach($cities as $city)
			{
				$city->name = ($lang == 'ar')? $city->name_ar:$city->name;
				$return['data'] .= '<option value="'.$city->id.'">'.$city->name.'</option>';
			}
		}
		$return['data'] .= '</select>';
		echo json_encode($return);
	}
	
	public function register($lang='en', $string)
    {
		if(in_array($lang,$this->languages))
		{
			$data['countries'] = $this->Api_model->getAllDataWhere(['active'=>1], 'countries');
			$data['page'] = 'register';
			$data['lang'] = $lang;
			$data['artist'] = $string;
			$this->lang->load('frontend', $lang);
			$this->load->view('website/header.php', $data);
			$this->load->view('website/register.php', $data);
			$this->load->view('website/footer.php', $data);
		}
		else redirect('404');
    }
	
	public function categories($lang='en')
    {
		if(in_array($lang,$this->languages))
		{
			$data['page'] = 'categories';
			$data['lang'] = $lang;
			$this->lang->load('frontend', $lang);
			$this->load->view('website/header.php', $data);
			$this->load->view('website/categories.php');
			$this->load->view('website/footer.php', $data);
		}
		else redirect('404');
    }
	
	public function contact($lang='en')
    {
		if(in_array($lang,$this->languages))
		{
			$data['page'] = 'contact';
			$data['lang'] = $lang;
			$this->lang->load('frontend', $lang);
			$this->load->view('website/header.php', $data);
			$this->load->view('website/contact.php', $data);
			$this->load->view('website/footer.php', $data);
		}
		else redirect('404');
    }
	
	public function login_post($lang='en')
    {
		if(in_array($lang,$this->languages))
		{
		$this->config->set_item('language', $lang);
		$this->lang->load('frontend', $lang);
		$this->lang->load('form_validation', $lang);
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		$this->form_validation->set_rules('email', 'lang:emailaddress' , 'trim|required|max_length[255]|valid_email');
		$this->form_validation->set_rules('password', 'lang:password', 'trim|required|min_length[6]|max_length[255]');
		
		if ($this->form_validation->run() == FALSE)
        {
			$data['page'] = 'login';
			$data['lang'] = $lang;
			$this->lang->load('frontend', $lang);
			$this->load->view('website/header.php', $data);
			$this->load->view('website/login.php', $data);
			$this->load->view('website/footer.php', $data);
			
        }
        else
        {
			$user = $this->Api_model->getSingleRow('user',array('email_id'=>set_value('email'),'password'=>md5(set_value('password'))));
			if($user)
			{
				$this->session->set_userdata(array('name'=>$user->name, 'id'=>$user->user_id));
				if(isset($_SESSION['name']))
				{
					echo $_SESSION['id'].' - '.$_SESSION['name'];
				}
				else echo 0;
				
				/*$this->session->sess_destroy();
				if(isset($_SESSION['name']))
				{
					echo $_SESSION['id'].' - '.$_SESSION['name'];
				}
				else echo 0;*/
			}
        }
		}
		else redirect('404');
    }
	
	public function subscribe_post($lang='en')
    {
		if(in_array($lang,$this->languages))
		{
		$this->config->set_item('language', $lang);
		$this->lang->load('frontend', $lang);
		$this->lang->load('form_validation', $lang);
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		$this->form_validation->set_rules('emailsub', 'lang:emailaddress' , 'trim|required|max_length[255]|valid_email|is_unique[subscribers.email]');
		if ($this->form_validation->run() == FALSE)
        {
			$data['page'] = (!in_array(set_value('page'), ['hero','contact','categories']))? 'index':set_value('page');
			$data['lang'] = $lang;
			$this->lang->load('frontend', $lang);
			$this->load->view('website/header.php', $data);
			$this ->load->view('website/'.$data['page'].'.php', $data);
			$this ->load->view('website/footer.php', $data);
			
        }
        else
        {
			$page = (!in_array(set_value('page'), array('hero','contact','categories')))? '':set_value('page');
            $this->Api_model->insertGetId('subscribers',array('email'=>set_value('emailsub')));
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'emailaddedsuccessfullytosubscribers';
			redirect($lang.'/'.$page);
        }
		}
		else redirect('404');
    }
	
	public function hero_post($lang='en')
    {
		if(in_array($lang,$this->languages))
		{
		$this->config->set_item('language', $lang);
		$this->lang->load('frontend', $lang);
		$this->lang->load('form_validation', $lang);
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		$this->form_validation->set_rules('name', 'lang:name', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('mobile', 'lang:mobile', 'trim|required|max_length[10]|numeric|is_unique[user.mobile]');
		$this->form_validation->set_rules('categories[]', 'lang:categories', 'trim|required');
		
		if ($this->form_validation->run() == FALSE)
        {
			$categories = $this->Api_model->getAllDataWhere(['status'=>1], 'category');
			$array = [];
			foreach($categories as $key => $category)
			{
				if($category->parent_id)
				{
					$array[$category->parent_id]['children'][$key]['id'] = $category->id;
					$array[$category->parent_id]['children'][$key]['parent'] = $category->parent_id;
					$array[$category->parent_id]['children'][$key]['cat_name'] = $category->cat_name;
					$array[$category->parent_id]['children'][$key]['cat_name_ar'] = $category->cat_name_ar;
				}
				else
				{
					$array[$category->id]['id'] = $category->id;
					$array[$category->id]['parent'] = $category->parent_id;
					$array[$category->id]['cat_name'] = $category->cat_name;
					$array[$category->id]['cat_name_ar'] = $category->cat_name_ar;
				}
			}
		
			$data['categories'] = $array;			
			$data['page'] = 'hero';
			$data['lang'] = $lang;
			$this->lang->load('frontend', $lang);
			$this->load->view('website/header.php', $data);
			$this->load->view('website/hero.php', $data);
			$this->load->view('website/footer.php', $data);
			
        }
        else
        {
			$data = array('name'=>set_value('name'),'mobile'=>set_value('mobile'),'role'=>1,'status'=>0,'created_at'=>time(),'updated_at'=>time(),'referral_code'=>$this->api->random_num(6),'approval_status'=>1);
            $getUserId = $this->Api_model->insertGetId('user',$data);
            if($getUserId)
            {
                $url= base_url().'Webservice/userActive?user_id='.$getUserId;
                $msg='Thanks for signing up! Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below. Please click ' .$url;
              
                $this->send_email_new_by_msg(set_value('email'), "FabArtist Registration", $msg);
				$this->send_email_to_admin(set_value('email'),"FabArtist Registration",set_value('name')." just registered with us now with mobile number ".set_value('mobile'));
                $data1['user_id']= $getUserId;
                $data1['name']= set_value('name');
				$data1['category_id'] = implode(',', set_value('categories'));
                $data1['created_at']= time();
                $data1['updated_at']= time();
                $getArtistId = $this->Api_model->insertGetId('artist',$data1);
				if($getArtistId)
				{
					foreach(set_value('categories') as $category) { $this->Api_model->insertGetId('artist_category',array('artist_id'=>$getArtistId, 'category_id'=>$category)); }
					redirect($lang.'/register/'.$getArtistId);
				}
				else redirect($lang.'/hero');
            }
			else redirect($lang.'/hero');
        }
		}
		else redirect('404');
    }
	
	/*public function hero_post($lang='en')
    {
		if(in_array($lang,$this->languages))
		{
		$this->config->set_item('language', $lang);
		$this->lang->load('frontend', $lang);
		$this->lang->load('form_validation', $lang);
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		$this->form_validation->set_rules('name', 'lang:name', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('email', 'lang:emailaddress' , 'trim|required|max_length[255]|valid_email|is_unique[user.email_id]');
		$this->form_validation->set_rules('password', 'lang:password', 'trim|required|min_length[6]|max_length[255]');
		$this->form_validation->set_rules('country', 'lang:country', 'trim|required|integer');
		$this->form_validation->set_rules('city', 'lang:city', 'trim|required|integer');
		
		if ($this->form_validation->run() == FALSE)
        {
			$data['countries'] = $this->Api_model->getAllDataWhere(['active'=>1], 'countries');
			
			$data['page'] = 'hero';
			$data['lang'] = $lang;
			$this->lang->load('frontend', $lang);
			$this->load->view('website/header.php', $data);
			$this->load->view('website/hero.php', $data);
			$this->load->view('website/footer.php', $data);
			
        }
        else
        {
			$data = array('name'=>set_value('name'),'email_id'=>set_value('email'),'password'=>md5(set_value('password')),'country_id'=>set_value('country'),'city_id'=>set_value('city'),'role'=>1,'status'=>0,'created_at'=>time(),'updated_at'=>time(),'referral_code'=>$this->api->random_num(6),'approval_status'=>1);
            $getUserId = $this->Api_model->insertGetId('user',$data);
            if($getUserId)
            {
                $url= base_url().'Webservice/userActive?user_id='.$getUserId;
                $msg='Thanks for signing up! Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below. Please click ' .$url;
              
                $this->send_email_new_by_msg(set_value('email'), "FabArtist Registration", $msg);
				$this->send_email_to_admin(set_value('email'),"FabArtist Registration",set_value('name')." just registered with us now with email adress ".set_value('email'));
                $data1['user_id']= $getUserId;
                $data1['name']= set_value('name');
				$data1['country_id']= set_value('country');
				$data1['city_id']= set_value('city');
                $data1['created_at']= time();
                $data1['updated_at']= time();
                $getArtistId = $this->Api_model->insertGetId('artist',$data1);
				if($getArtistId) redirect($lang.'/register/'.$getArtistId);
				else redirect($lang.'/hero');
            }
			else redirect($lang.'/hero');
        }
		}
		else redirect('404');
    }*/
	
	public function register_post($lang='en')
    {
		if(in_array($lang,$this->languages))
		{
		$this->config->set_item('language', $lang);
		$this->lang->load('frontend', $lang);
		$this->lang->load('form_validation', $lang);
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		$this->form_validation->set_rules('country', 'lang:country', 'trim|integer');
		$this->form_validation->set_rules('city', 'lang:city' , 'trim|integer');
		$this->form_validation->set_rules('email', 'lang:emailaddress' , 'trim|max_length[255]|valid_email|is_unique[user.email_id]');
		$this->form_validation->set_rules('nationality', 'lang:nationality' , 'trim|integer');
		$this->form_validation->set_rules('password', 'lang:password', 'trim|min_length[6]|max_length[255]');
		$this->form_validation->set_rules('cnfpassword', 'lang:re-enterpassword', 'trim|matches[password]');
		$this->form_validation->set_rules('artist', 'lang:artist' , 'trim|required');
		$this->form_validation->set_rules('company', 'lang:companyname', 'trim|max_length[255]');
		if (empty($_FILES['commercial']['name']) && set_value('nationality') == 0)
		{
			$this->form_validation->set_rules('commercial', 'lang:commercial', 'required');
		}
		if (empty($_FILES['authorization']['name']) && set_value('nationality') == 0)
		{
			$this->form_validation->set_rules('authorization', 'lang:authorization', 'required');
		}
		if (empty($_FILES['identity']['name']))
		{
			$this->form_validation->set_rules('identity', 'lang:identity', 'required');
		}
		//$this->form_validation->set_rules('authorization', 'lang:authorization' , 'callback_imageSize_authorization|callback_imageType_authorization');

		if ($this->form_validation->run() == FALSE)
        {
			$data['countries'] = $this->Api_model->getAllDataWhere(['active'=>1], 'countries');
			$data['page'] = 'register';
			$data['lang'] = $lang;
			$data['artist'] = set_value('artist');
			$this->lang->load('frontend', $lang);
			$this->load->view('website/header.php', $data);
			$this->load->view('website/register.php', $data);
			$this->load->view('website/footer.php', $data);
			
        }
        else
        {
			$artist = $this->Api_model->getSingleRow('artist', array('id'=>set_value('artist')));
			if($artist)
			{
				$password = (set_value('password'))? MD5(set_value('password')):'';
				$this->Api_model->updateSingleRow('artist', array('id'=>$artist->id), array('country_id'=>set_value('country'), 'city_id'=>set_value('city'), 'nationality'=>set_value('nationality'), 'company'=>set_value('company'), 'updated_at'=>time()));
				$this->Api_model->updateSingleRow('user', array('user_id'=>$artist->user_id), array('email_id'=>set_value('email'), 'password'=>$password, 'country_id'=>set_value('country'), 'city_id'=>set_value('city'), 'updated_at'=>time()));
				
				$existCommercialAttach = $this->Api_model->getSingleRow('attachments', array('artist_id'=>$artist->id,'attachment_type_id'=>1));
				if(!$existCommercialAttach && $_FILES['commercial']['tmp_name'])
				{
					$commercial = $this->uploadimg('commercial', 'images/commercials/', mt_rand());
					if($commercial) $this->Api_model->insertGetId('attachments',array('artist_id'=>$artist->id,'attachment_type_id'=>1,'attachment'=>$commercial));
				}
				
				$existIdentityAttach = $this->Api_model->getSingleRow('attachments', array('artist_id'=>$artist->id,'attachment_type_id'=>2));
				if(!$existIdentityAttach && $_FILES['identity']['tmp_name'])
				{
					$identity = $this->uploadimg('identity', 'images/identitys/', mt_rand());
					if($identity) $this->Api_model->insertGetId('attachments',array('artist_id'=>$artist->id,'attachment_type_id'=>2,'attachment'=>$identity));
				}
				
				$existAuthorizationAttach = $this->Api_model->getSingleRow('attachments', array('artist_id'=>$artist->id,'attachment_type_id'=>3));
				if(!$existAuthorizationAttach && $_FILES['authorization']['tmp_name'])
				{
					$authorization = $this->uploadimg('authorization', 'images/authorizations/', mt_rand());
					if($authorization) $this->Api_model->insertGetId('attachments',array('artist_id'=>$artist->id,'attachment_type_id'=>3,'attachment'=>$authorization));
				}
				
				$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'wellcometohero';
				redirect($lang.'/hero');
			}
			else redirect($lang.'/register/'.set_value('artist'));
        }
		}
		else redirect('404');
    }
	
	public function contact_post($lang='en')
    {
		if(in_array($lang,$this->languages))
		{
		$this->config->set_item('language', $lang);
		$this->lang->load('frontend', $lang);
		$this->lang->load('form_validation', $lang);
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-dismissible fade in" role="alert" style="opacity:1;">', '</div>');
		$this->form_validation->set_rules('name', 'lang:name' , 'trim|required|max_length[255]');
		$this->form_validation->set_rules('email', 'lang:emailaddress' , 'trim|required|max_length[255]|valid_email');
		$this->form_validation->set_rules('message', 'lang:message' , 'trim|required');

		if ($this->form_validation->run() == FALSE)
        {
			$data['page'] = 'contact';
			$data['lang'] = $lang;
			$this->lang->load('frontend', $lang);
			$this->load->view('website/header.php', $data);
			$this->load->view('website/contact.php', $data);
			$this->load->view('website/footer.php', $data);
			
        }
        else
        {
			//$this->send_email(set_value('email'), set_value('name'), set_value('message'));
			$this->send_email_to_admin(set_value('email'), set_value('name'), set_value('message'));
			$_SESSION['time'] = time(); $_SESSION['modal'] = 'success'; $_SESSION['message'] = 'messagewassent';
            redirect($lang.'/contact');
        }
		}
		else redirect('404');
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

        $this->email->from($email_id, APP_NAME); 
        $this->email->to($from_email);
        $this->email->subject($subject); 

        $datas['msg']=$msg;
        $body = $this->load->view('main.php',$datas,TRUE);
        $this->email->message($body);

       $this->email->send();
    }
	
	public function send_email_to_admin($email_id,$subject,$msg)
        {
          $msgg = $this->Api_model->getSingleRow('msg_key', array('id'=>1));
          $authKey = $msgg->msg_key;
          $from= SENDER_EMAILL;
          // print_r($from);
          // print_r($authkey);
          // print_r($)

          //Prepare you post parameters
          $postData = array(
              'authkey' => $authKey,
              'to' => $from,
              'from' => $email_id,
              'subject' => $subject,
              'body' => $msg
          );
          
          //API URL
          $url="https://control.msg91.com/api/sendmail.php?authkey='$authKey'&to='$from'&from='$email_id'&body='$msg'&subject='$subject'";
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
	
	public function send_email_new_by_msg($email_id,$subject,$msg)
        {
          $msgg = $this->Api_model->getSingleRow('msg_key', array('id'=>1));
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
	
	public function imageSize_commercial()
	{
		if ($_FILES['commercial']['size'] > 1024000)
		{
			//$this->form_validation->set_message('imageSize_commercial', 'The image should be less or equal 1 MB');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function imageType_commercial()
	{
		//if (!in_array(strtoupper(pathinfo($_FILES['commercial']['name'], PATHINFO_EXTENSION)),array('JPG','JPEG','PNG','JIF','BMP','TIF')))
		if(!in_array(mime_content_type($_FILES['commercial']['tmp_name']), array('image/jpeg', 'image/gif', 'image/png')))
		{
			//$this->form_validation->set_message('imageType_commercial', 'The uploaded file should be one of these types : JPG,JPEG,PIG,JIF,BMP,TIF');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function imageSize_identity()
	{
		if ($_FILES['commercial']['size'] > 1024000)
		{
			//$this->form_validation->set_message('imageSize_identity', 'The image should be less or equal 1 MB');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function imageType_identity()
	{
		//if (!in_array(strtoupper(pathinfo($_FILES['commercial']['name'], PATHINFO_EXTENSION)),array('JPG','JPEG','PNG','JIF','BMP','TIF')))
		if(!in_array(mime_content_type($_FILES['commercial']['tmp_name']), array('image/jpeg', 'image/gif', 'image/png')))
		{
			//$this->form_validation->set_message('imageType_identity', 'The uploaded file should be one of these types : JPG,JPEG,PIG,JIF,BMP,TIF');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function imageSize_authorization()
	{
		if ($_FILES['commercial']['size'] > 1024000)
		{
			//$this->form_validation->set_message('imageSize_authorization', 'The image should be less or equal 1 MB');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function imageType_authorization()
	{
		//if (!in_array(strtoupper(pathinfo($_FILES['commercial']['name'], PATHINFO_EXTENSION)),array('JPG','JPEG','PNG','JIF','BMP','TIF')))
		if(!in_array(mime_content_type($_FILES['commercial']['tmp_name']), array('image/jpeg', 'image/gif', 'image/png')))
		{
			//$this->form_validation->set_message('imageType_authorization', 'The uploaded file should be one of these types : JPG,JPEG,PIG,JIF,BMP,TIF');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function uploadimg($inputfilename,$image_director,$newname)
	{
		$file_extn = pathinfo($_FILES[$inputfilename]['name'], PATHINFO_EXTENSION);
		if(!is_dir($image_director)) $create_image_director = mkdir($image_director);
		$name = $newname.'.'.$file_extn;
		if(move_uploaded_file($_FILES[$inputfilename]["tmp_name"], $image_director.$name)) return $image_director.$name;
		else return 0;
	}
}