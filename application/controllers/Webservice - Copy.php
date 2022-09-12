<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Webservice extends CI_Controller
{
    public function __construct()
    {

        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('image_lib');
        $this->load->library('api');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->model('Comman_model');
        $this->load->model('Api_model');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    }

    /*commit*/

    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    function getlanguage()
    {
        foreach ($this->getallheaders() as $name => $value) {
            if ($name == 'Language') {
                $lang = $value;
            }
        }
        if (!empty($lang)) {
            return $lang;
        } else {
            return 'en';
        }

    }

    function translate($constant, $lan)
    {
        if ($lan == 'ar') {
            $constant = $constant . '_AR';
        }
        return constant($constant);
    }

    public function privacy()
    {
        $this->load->helper('form');
        $data['privacy'] = $this->Api_model->getSingleRow('page', array('id' => 2));
        $this->load->view('pages/privacy', $data);
    }

    public function term()
    {
        $data['term'] = $this->Api_model->getSingleRow('page', array('id' => 1));
        $this->load->helper('form');
        $this->load->view('pages/term', $data);
    }

    public function index()
    {
        $getUserId = 1;
        echo $url = base_url() . 'Webservice/userActive?getUserId=' . $getUserId;
    }

    /*Check User Session*/
    public function checkUserKey($user_key, $user_id)
    {
        $lan = $this->getlanguage();
        $getUser = $this->Api_model->getSingleRow('user_session', array('user_id' => $user_id, 'user_key' => $user_key));
        if (!$getUser) {
            if ($lan == 'en') {
                $IN_USER = IN_USER;
            } elseif ($lan == 'ar') {
                $IN_USER = IN_USER_AR;
            }
            $this->api->api_message(3, $IN_USER);
            exit();
        }
    }

    /*Check User Session*/
    public function checkUserStatus($user_id)
    {
        $lan = $this->getlanguage();
        if (!empty($user_id)) {
            $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $user_id));

            if ($getUser) {
                if ($getUser->status == 0) {
                    $this->api->api_message(3, $this->translate('NOT_ACT', $lan));
                    exit();
                }
            } else {
                $this->api->api_message(3, $this->translate('USER_NOT_FOUND', $lan));
                exit();
            }
        }
    }


    /*Get Neareast Artist*/
    public function getNearestArtist()
    {
        $lan = $this->getlanguage();
        $latitude = $this->input->post('latitude', TRUE);
        $longitude = $this->input->post('longitude', TRUE);
        $category_id = $this->input->post('category_id', TRUE);
        $user_id = $this->input->post('user_id', TRUE);

        $this->checkUserStatus($user_id);

        if ($category_id) {
            $get_artists = $this->Api_model->getNearestDataWhere($latitude, $longitude, ART_TBL, array('category_id' => $category_id), $user_id, 1);
        } else {
            $get_artists = $this->Api_model->getNearestData($latitude, $longitude, ART_TBL, $user_id);
        }

        if ($get_artists->price == '' || $get_artists->category_id == 0 || $get_artists->name == '') {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
            die();
        }

        if ($get_artists) {
            $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_artists->user_id, 'status' => 0));
            if ($getUser) {
                $artist_id = $get_artists->user_id;
                $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));
                $get_artists->image = base_url() . $get_artists->image;
                $get_artists->category_name = $get_cat->cat_name;

                $where = array('artist_id' => $artist_id);
                $ava_rating = $this->Api_model->getAvgWhere('rating', 'rating', $where);
                if ($ava_rating[0]->rating == null) {
                    $ava_rating[0]->rating = "0";
                }
                $get_artists->ava_rating = round($ava_rating[0]->rating, 1);

                $skills = json_decode($get_artists->skills);
                $skill = array();
                if (!empty($skills)) {
                    foreach ($skills as $skills) {
                        $get_skills = $this->Api_model->getSingleRow('skills', array('id' => $skills));
                        array_push($skill, $get_skills);
                    }
                    $get_artists->skills = $skill;
                } else {
                    $get_artists->skills = array();
                }

                $get_products = $this->Api_model->getAllDataWhere(array('user_id' => $get_artists->user_id), 'products');
                $products = array();
                foreach ($get_products as $get_products) {
                    $get_products->product_image = base_url() . $get_products->product_image;
                    array_push($products, $get_products);
                }
                $get_artists->products = $products;

                $get_reviews = $this->Api_model->getAllDataWhere(array('artist_id' => $artist_id, 'status' => 1), 'rating');
                $review = array();
                foreach ($get_reviews as $get_reviews) {
                    $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_reviews->user_id));
                    $get_reviews->name = $get_user->name;
                    if ($get_user->image) {
                        $get_reviews->image = base_url() . $get_user->image;
                    } else {
                        $get_reviews->image = base_url() . "assets/images/image.png";
                    }

                    array_push($review, $get_reviews);
                }
                $get_artists->reviews = $review;

                $get_gallery = $this->Api_model->getAllDataWhere(array('user_id' => $artist_id), GLY_TBL);
                $gallery = array();
                foreach ($get_gallery as $get_gallery) {

                    $get_gallery->image = base_url() . $get_gallery->image;
                    array_push($gallery, $get_gallery);
                }
                $get_artists->gallery = $gallery;

                $get_qualifications = $this->Api_model->getAllDataWhere(array('user_id' => $artist_id), 'qualifications');
                $get_artists->qualifications = $get_qualifications;
                $get_artists->jobDone = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist_id, 'booking_flag' => 4));
                $get_artists->totalJob = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist_id));
                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $get_artists->currency_type = $currency_setting->currency_symbol;
                if ($get_artists->totalJob == 0) {
                    $get_artists->completePercentages = 0;
                } else {
                    $get_artists->completePercentages = round(($get_artists->jobDone * 100) / $get_artists->totalJob);
                }
                $get_artists->banner_image = base_url() . $get_artists->banner_image;
                $this->api->api_message_data(1, $this->translate('ALL_SKILLS', $lan), 'data', $get_artists);
            } else {
                $this->api->api_message(0, $this->translate('NO_DATA', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    public function DublicateUser()
    {
        $lan = $this->getlanguage();
        $table = USR_TBL;
        $result = array();
        $email_id = $this->input->post('email_id', TRUE);
        $mobile = $this->input->post('mobile', TRUE);
        if (!empty($email_id)) {
            $get_user = $this->Api_model->getSingleRow($table, array('email_id' => $email_id));
            if ($get_user) {
                $result['email'] = 1;
            }
        }
        if (!empty($mobile)) {
            $mobile = MOBILE_CODE . $mobile;
            $get_mobile_user = $this->Api_model->getSingleRow($table, array('mobile' => $mobile));
            if (!empty($get_mobile_user)) {
                $result['mobile'] = 1;
            }
        }
        if ($result) {
            $this->api->api_message(0, $result);
        } else {
            echo json_encode(array('success' => true));
        }
    }

    /*SignUp User*/
    public function SignUp()
    {

//        print_r($_POST);
//        die;
        $lan = $this->getlanguage();
        $table = USR_TBL;
        $name = $this->input->post('name', TRUE);
        $email_id = $this->input->post('email_id', TRUE);
        $mobile = $this->input->post('mobile', TRUE);
        $password = $this->input->post('password', TRUE);
        $userRole = $this->input->post('role', TRUE);
        $device_id = $this->input->post('device_id', TRUE);
        $device_token = $this->input->post('device_token', TRUE);
        $device_type = $this->input->post('device_type', TRUE);
        $use_code = $this->input->post('referral_code', TRUE);
        $phonecode = $this->input->post('phoneCode', TRUE);
        $country_id = $this->input->post('country_id', TRUE);
        $city_id = $this->input->post('city_id', TRUE);

        $nationality = $this->input->post('nationality', TRUE);
        $company = $this->input->post('company', TRUE);
        $commercial = $this->input->post('commercial', TRUE);
        $identity = $this->input->post('identity', TRUE);
        $authorization = $this->input->post('authorization', TRUE);
        $services = $this->input->post('services', TRUE);

        $get_mobile_user = '';


        if (empty($country_id) || empty($city_id)) {
            $this->api->api_message(0, $this->translate('LOCATION_REQUIRED', $lan));
            exit();
        }

        if (!empty($mobile)) {
            $get_mobile_user = $this->Api_model->getSingleRow($table, array('mobile' => $mobile));
            if (!empty($get_mobile_user)) {
                $this->api->api_message(0, $this->translate('MOBILE_EXIST', $lan));
                exit();
            }
        } else {
            $this->api->api_message(0, $this->translate('EMPTY_MOBILE', $lan));
            exit();
        }
        if ($use_code) {
            $getCode = $this->Api_model->getSingleRow(USR_TBL, array('referral_code' => $use_code));
            if (!$getCode) {
                $this->api->api_message(0, $this->translate('ENTER_VALID_COUPON_CODE', $lan));
                exit();
            }
        }

        if ($userRole == 1) {
            $userStatus = 1;
            $approval_status = 0;
        } else {
            $userStatus = 1;
            $approval_status = 1;
        }

        $created_at = time();
        $updated_at = time();
        $condition = array('email_id' => $email_id);
        $columnName = 'email_id';
        $referral_code = $this->api->random_num(6);
        if ($use_code) {
            $data = array('name' => $name, 'email_id' => $email_id, 'country_id' => $country_id, 'city_id' => $city_id, 'mobile' => $mobile, 'password' => md5($password), 'role' => $userRole, 'status' => $userStatus, 'created_at' => $created_at, 'updated_at' => $updated_at, 'referral_code' => $referral_code, 'approval_status' => $approval_status, 'user_referral_code' => $use_code, 'device_token' => $device_token, 'device_id' => $device_id, 'device_type' => $device_type, 'phonecode' => $phonecode);
        } else {
            $data = array('name' => $name, 'email_id' => $email_id, 'country_id' => $country_id, 'city_id' => $city_id, 'mobile' => $mobile, 'password' => md5($password), 'role' => $userRole, 'status' => $userStatus, 'created_at' => $created_at, 'updated_at' => $updated_at, 'referral_code' => $referral_code, 'approval_status' => $approval_status, 'device_token' => $device_token, 'device_id' => $device_id, 'device_type' => $device_type, 'phonecode' => $phonecode);
        }
        if (!$get_mobile_user) {
            if ($userRole == 1) {
                if (!empty($email_id)) {
                    $get_user = $this->Api_model->getSingleRow($table, array('email_id' => $email_id));
                    if ($get_user) {
                        $this->api->api_message(0, $this->translate('EMAIL_EXIST', $lan));
                        exit();
                    }
                }
            } else {
                if (!empty($email_id)) {
                    $get_user = $this->Api_model->getSingleRow($table, array('email_id' => $email_id));
                    if ($get_user) {
                        $this->api->api_message(0, $this->translate('EMAIL_EXIST', $lan));
                        exit();
                    }
                }
            }
            $getUserId = $this->Api_model->insertGetId($table, $data);
            if ($getUserId) {
                if ($use_code) {
                    $this->checkUserCode($use_code, $getUserId);
                }

                $datatag = 'data';
                $get_user['user_data'] = $this->Api_model->getSingleRow($table, array('user_id' => $getUserId));
                unset($get_user['user_data']->password);

                // handle artist logic
                if ($userRole == 1) {
                    $data1['user_id'] = $getUserId;
                    $data1['name'] = $name;
                    $data1['company'] = $company;
                    $data1['nationality'] = $nationality;
                    $data1['category_id'] = implode(',', $services);
                    $data1['created_at'] = time();
                    $data1['updated_at'] = time();
                    $getArtistId = $this->Api_model->insertGetId('artist', $data1);
                    if ($getArtistId) {
                        foreach ($services as $service) {
                            $this->Api_model->insertGetId('artist_category', array('artist_id' => $getArtistId, 'category_id' => $service));
                        }
                    }

                    if (isset($_FILES['commercial'])) {
                        $commercial = $this->uploadimg('commercial', 'images/commercials/', mt_rand());
//                      print_r($commercial);
//                      die;
                        if ($commercial) $this->Api_model->insertGetId('attachments', array('artist_id' => $getArtistId, 'attachment_type_id' => 1, 'attachment' => $commercial));
                    }

                    if (isset($_FILES['identity'])) {
                        $identity = $this->uploadimg('identity', 'images/identitys/', mt_rand());
                        if ($identity) $this->Api_model->insertGetId('attachments', array('artist_id' => $getArtistId, 'attachment_type_id' => 2, 'attachment' => $identity));
                    }

                    if (isset($_FILES['authorization'])) {
                        $authorization = $this->uploadimg('authorization', 'images/authorizations/', mt_rand());
                        if ($authorization) $this->Api_model->insertGetId('attachments', array('artist_id' => $getArtistId, 'attachment_type_id' => 3, 'attachment' => $authorization));
                    }

                    $get_user['attachmnet'] = $this->Api_model->getArtistAttachs($getArtistId);

                }


                $this->api->api_message_data(1, $this->translate('USERRAGISTER', $lan), $datatag, $get_user);
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } elseif ($get_mobile_user->status == 0) {

            exit();
        }
    }

    public function SignUp1()
    {
        $lan = $this->getlanguage();
        $table = USR_TBL;
        $name = $this->input->get('name', TRUE);
        $email_id = $this->input->get('email_id', TRUE);
        $mobile = $this->input->get('mobile', TRUE);
        $password = $this->input->get('password', TRUE);
        $userRole = $this->input->get('role', TRUE);
        $device_id = $this->input->get('device_id', TRUE);
        $device_token = $this->input->get('device_token', TRUE);
        $device_type = $this->input->get('device_type', TRUE);
        $use_code = $this->input->get('referral_code', TRUE);
        $phonecode = $this->input->get('phoneCode', TRUE);
        $get_mobile_user = '';
        if (!empty($mobile)) {
            $get_mobile_user = $this->Api_model->getSingleRow($table, array('mobile' => $mobile));
            if (!empty($get_mobile_user)) {
                $this->api->api_message(0, $this->translate('MOBILE_EXIST', $lan));
                exit();
            }
        } else {
            $this->api->api_message(0, $this->translate('EMPTY_MOBILE', $lan));
            exit();
        }
        if ($use_code) {
            $getCode = $this->Api_model->getSingleRow(USR_TBL, array('referral_code' => $use_code));
            if (!$getCode) {
                $this->api->api_message(0, $this->translate('ENTER_VALID_COUPON_CODE', $lan));
                exit();
            }
        }

        if ($userRole == 1) {
            $userStatus = 1;
            $approval_status = 0;
        } else {
            $userStatus = 1;
            $approval_status = 1;
        }

        $created_at = time();
        $updated_at = time();
        $condition = array('email_id' => $email_id);
        $columnName = 'email_id';
        $referral_code = $this->api->random_num(6);
        if ($use_code) {
            $data = array('name' => $name, 'email_id' => $email_id, 'mobile' => $mobile, 'password' => md5($password), 'role' => $userRole, 'status' => $userStatus, 'created_at' => $created_at, 'updated_at' => $updated_at, 'referral_code' => $referral_code, 'approval_status' => $approval_status, 'user_referral_code' => $use_code, 'device_token' => $device_token, 'device_id' => $device_id, 'device_type' => $device_type, 'phonecode' => $phonecode);
        } else {
            $data = array('name' => $name, 'email_id' => $email_id, 'mobile' => $mobile, 'password' => md5($password), 'role' => $userRole, 'status' => $userStatus, 'created_at' => $created_at, 'updated_at' => $updated_at, 'referral_code' => $referral_code, 'approval_status' => $approval_status, 'device_token' => $device_token, 'device_id' => $device_id, 'device_type' => $device_type, 'phonecode' => $phonecode);
        }
        if (!$get_mobile_user) {
            if ($userRole == 1) {
                if (!empty($email_id)) {
                    $get_user = $this->Api_model->getSingleRow($table, array('email_id' => $email_id));
                    if ($get_user) {
                        $this->api->api_message(0, $this->translate('EMAIL_EXIST', $lan));
                        exit();
                    }
                }
            } else {
                if (!empty($email_id)) {
                    $get_user = $this->Api_model->getSingleRow($table, array('email_id' => $email_id));
                    if ($get_user) {
                        $this->api->api_message(0, $this->translate('EMAIL_EXIST', $lan));
                        exit();
                    }
                }
            }
            $getUserId = $this->Api_model->insertGetId($table, $data);
            if ($getUserId) {
                if ($use_code) {
                    $this->checkUserCode($use_code, $getUserId);
                }

                $datatag = 'data';
                $get_user = $this->Api_model->getSingleRow($table, array('user_id' => $getUserId));
                $this->api->api_message_data(1, $this->translate('USERRAGISTER', $lan), $datatag, $get_user);
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } elseif ($get_mobile_user->status == 0) {

            exit();
        }
    }


    // create user address
    public function createUserAddress()
    {
        foreach ($this->getallheaders() as $name => $value) {
            if ($name == 'Language') {
                $lang = $value;
            }
        }
        $user_id = $this->input->post('user_id', TRUE);
        $longitude = $this->input->post('longitude', TRUE);
        $latitude = $this->input->post('latitude', TRUE);
        $city_id = $this->input->post('city_id', TRUE);
        $address = $this->input->post('address', TRUE);
        $building = $this->input->post('building', TRUE);
        $floor = $this->input->post('floor', TRUE);
        $apartment = $this->input->post('apartment', TRUE);
        $landmark = $this->input->post('landmark', TRUE);


        if (empty($user_id) || empty($longitude) || empty($latitude) || empty($city_id) || empty($address) || empty($building)) {
            if ($lang == 'ar') {
                $this->api->api_message(0, VALIDATION_ERR_AR);
            } else {
                $this->api->api_message(0, VALIDATION_ERR);
            }
            exit();
        }

        $data['user_id'] = $user_id;
        $data['longitude'] = $longitude;
        $data['latitude'] = $latitude;
        $data['city_id'] = $city_id;
        $data['address'] = $address;
        $data['building'] = $building;
        $data['floor'] = $floor;
        $data['apartment'] = $apartment;
        $data['landmark'] = $landmark;
        $data['created_by'] = $user_id;

        $record = $this->Api_model->insertGetId("user_addresses", $data);

        if ($record) {
            if ($lang == 'ar') {
                $this->api->api_message_data(1, ADDRESS_ADDED_AR, 'data', $record);
            } else {
                $this->api->api_message_data(1, ADDRESS_ADDED, 'data', $record);
            }

        } else {
            if ($lang == 'ar') {
                $this->api->api_message(0, UNEXPECTED_ERRـAR);
            } else {
                $this->api->api_message(0, UNEXPECTED_ERR);
            }

        }

    }


    public function getUserAddresses()
    {

        foreach ($this->getallheaders() as $name => $value) {
            if ($name == 'Language') {
                $lang = $value;
            }
        }

        $user_id = $this->input->post('user_id', TRUE);

        if (empty($user_id)) {
            if ($lang == 'ar') {
                $this->api->api_message(0, VALIDATION_ERR_AR);
            } else {
                $this->api->api_message(0, VALIDATION_ERR);
            }
            exit();
        }

        $getAddresses = $this->Api_model->getAllDataOrderBy("user_addresses", array('user_id' => $user_id));

        $this->api->api_message_data(1, USER_ADDRESSES, 'data', $getAddresses);

    }


    public function checkUserCode($use_code, $user_id)
    {
        $lan = $this->getlanguage();
        $referral_setting = $this->Api_model->getSingleRow("referral_setting", array('id' => 1));
        $amount = $referral_setting->amount;
        if ($referral_setting->type == 1) {

            $data['referral_code'] = $use_code;
            $data['user_id'] = $user_id;
            $this->Api_model->insertGetId("referral_usages", $data);

            $getCode = $this->Api_model->getAllDataWhere(array('referral_code' => $use_code), "referral_usages");
            if (count($getCode) <= $referral_setting->no_of_usages) {
                $updateUser = $this->Api_model->updateSingleRow("referral_usages", array('redeem' => 0, 'referral_code' => $use_code), array('redeem' => 1));

                $getUser = $this->Api_model->getSingleRow(USR_TBL, array('referral_code' => $use_code));

                $getUser1 = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $user_id));

                $getWallent = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getUser->user_id));
                $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $getUser1->user_id, 'amount' => $amount));
                $this->Api_model->insertGetId('wallet_history', array('user_id' => $getUser1->user_id, 'order_id' => time(), 'invoice_id' => time(), 'amount' => $amount, 'created_at' => time()));

                if ($getWallent) {
                    $this->Api_model->insertGetId('wallet_history', array('user_id' => $getUser->user_id, 'order_id' => time(), 'invoice_id' => time(), 'amount' => $amount, 'created_at' => time()));
                    $amount = $getWallent->amount + $amount;
                    $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getUser->user_id), array('amount' => $amount));

                    $this->Api_model->insertGetId('wallet_history', array('user_id' => $getUser1->user_id, 'order_id' => time(), 'invoice_id' => time(), 'amount' => $amount, 'created_at' => time()));

                } else {
                    $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $getUser->user_id, 'amount' => $amount));
                    $this->Api_model->insertGetId('wallet_history', array('user_id' => $getUser->user_id, 'order_id' => time(), 'invoice_id' => time(), 'amount' => $amount, 'created_at' => time()));
                }

                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $currency_type = $currency_setting->currency_symbol;
                $msg = $currency_type . ' ' . $amount . ' credit in your wallet by referral code.';
                $this->firebase_notification($getUser->user_id, "Wallet", $msg, WALLET_NOTIFICATION);
            }

        }
    }

    /*Use Sign in*/
    public function signIn()
    {
        $lan = $this->getlanguage();
        $email_id = $this->input->post('email_id', TRUE);
        $password = $this->input->post('password', TRUE);

        $role = $this->input->post('role', TRUE);
        $datadevice['device_id'] = $this->input->post('device_id', TRUE);
        $datadevice['device_token'] = $this->input->post('device_token', TRUE);
        $datadevice['device_type'] = $this->input->post('device_type', TRUE);
        $table = USR_TBL;
        if (filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
            $condition = array('email_id' => $email_id, 'role' => $role);
        } else {
            $email_id = $email_id;
            $condition = array('mobile' => $email_id, 'role' => $role);
        }

        $chkUser = $this->Api_model->getUserData($condition);

        if (!$chkUser) {
            $this->api->api_message(0, $this->translate('USER_NOT_FOUND', $lan));
            exit();
        }

        if ($chkUser->password != md5($password)) {
            $this->api->api_message(0, $this->translate('PASS_NT_MTCH', $lan));
            exit();
        }

        if ($chkUser->status != 1) {
            $this->api->api_message(0, $this->translate('NOT_ACTIVE', $lan));
            exit();
        }

        /*if($chkUser->approval_status !=1)
        {
          $this->api->api_message(0, 'Hey, Please wait for the admin approval.');
          exit();
        }*/

        $user_id = $chkUser->user_id;
        $name = $chkUser->name;
        $email_id = $chkUser->email_id;
        $role = $chkUser->role;
        $chkUser->artist_id = null;

        if ($chkUser) {
            if ($role == 1) {
                $checkArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $user_id));
                if ($checkArtist) {
                    $chkUser->is_profile = 1;
                    if ($chkUser->image) {
                        $chkUser->image = base_url() . $chkUser->image;
                    }
                    $chkUser->artist_id=$checkArtist->id;
                } else {
                    $chkUser->is_profile = 0;
                }
            } else {
                $chkUser->is_profile = 1;
                if ($chkUser->image) {
                    $chkUser->image = base_url() . $chkUser->image;
                }
            }

            if ($chkUser->i_card) {
                $chkUser->i_card = base_url() . $chkUser->i_card;
            } else {
                $chkUser->i_card = base_url() . "assets/images/image.png";
            }

            $chkUser->device_id = $datadevice['device_id'];
            $chkUser->device_type = $datadevice['device_type'];
            $chkUser->device_token = $datadevice['device_token'];

            unset($chkUser->password);


            $where = array('email_id' => $email_id);
            $updateUser = $this->Api_model->updateSingleRow(USR_TBL, $where, $datadevice);

            $datatag = 'data';
            $this->api->api_message_data(1, $this->translate('LOGINSUCCESSFULL', $lan), $datatag, $chkUser);
        } else {
            $this->api->api_message(0, $this->translate('LOGINFAIL', $lan));
        }
    }

    /*Get all Category*/
    public function getAllCaegory()
    {
        //book_artist$updateCategories=$this->Api_model->updateExistingArtistCategories();

        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $parent_id = $this->input->post('parent_id', TRUE);
        $city_id = $this->input->post('city_id', TRUE);


        if (empty($user_id)) {
            $user_id = 1870;
        }
        if (empty($parent_id)) {
            $parent_id = 0;
        }

        $conditions = array();
        $conditions['status'] = 1;
        $conditions['parent_id'] = $parent_id;
        if (!empty($city_id)) {
            $conditions['city_id'] = $city_id;
        } else {
            $this->api->api_message(0, $this->translate('VALIDATION_ERR', $lan));
            exit;
        }

        $this->checkUserStatus($user_id);

        $city = $this->Api_model->getSingleRow('cities', array('id' => $city_id));

        if (!$city) {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
            exit;
        }
        $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1, 'country_id' => $city->country_id));


        $get_cat = $this->Api_model->getAllCategoriesByArtistCity($city_id, $parent_id);

        if ($get_cat) {
            $get_cats = array();
            foreach ($get_cat as $get_cat) {

                $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));
                if ($commission_setting->commission_type == 0) {
                    $get_cat->price = $get_cat->price;
                } elseif ($commission_setting->commission_type == 1) {
                    if ($commission_setting->flat_type == 2) {
                        $get_cat->price = $commission_setting->flat_amount;
                    } elseif ($commission_setting->flat_type == 1) {
                        $get_cat->price = $commission_setting->flat_amount;
                    }
                }

                $get_cat->currency_symbol = $currency_setting->currency_symbol;
                $get_cat->currency_name = $currency_setting->currency_name;
                $get_cat->currency_name_ar = $currency_setting->currency_name_ar;

                array_push($get_cats, $get_cat);
            }
            $this->api->api_message_data_cat(10, 1, $this->translate('ALL_CAT', $lan), 'data', $get_cats);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*get Skills by Category*/
    public function getSkillsByCategory()
    {
        $lan = $this->getlanguage();
        $cat_id = $this->input->post('cat_id', TRUE);
        $user_id = $this->input->post('user_id', TRUE);

        $this->checkUserStatus($user_id);

        $where = array('cat_id' => $cat_id, 'status' => 1);

        $get_skills = $this->Api_model->getAllDataWhere($where, 'skills');

        if ($get_skills) {
            $this->api->api_message_data(1, $this->translate('ALL_SKILLS', $lan), 'data', $get_skills);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }

    }

    /*Add gallery for artist*/
    public function addGallery()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id');
        $image = $this->input->post('image');

        $this->checkUserStatus($user_id);

        $this->load->library('upload');

        $config['image_library'] = 'gd2';
        $config['upload_path'] = './assets/images/gallery/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 10000;
        $config['file_name'] = time();
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 250;
        $config['height'] = 250;
        $this->upload->initialize($config);
        $galleryImage = "";
        if ($this->upload->do_upload('image') && $this->load->library('image_lib', $config)) {
            $galleryImage = 'assets/images/gallery/' . $this->upload->data('file_name');
        } else {
            //  echo $this->upload->display_errors();
        }

        $data['user_id'] = $user_id;
        $data['image'] = $galleryImage;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $getId = $this->Api_model->insertGetId(GLY_TBL, $data);
        if ($getId) {
            $this->api->api_message(1, $this->translate('ADD_GALLERY', $lan));
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*Add gallery for artist*/
    public function addDocuments()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id');
        $document = $this->input->post('document');

        $this->checkUserStatus($user_id);

        $this->load->library('upload');

        $config['image_library'] = 'gd2';
        $config['upload_path'] = './assets/images/documents/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 10000;
        $config['file_name'] = time();
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 250;
        $config['height'] = 250;
        $this->upload->initialize($config);
        $galleryImage = "";
        if ($this->upload->do_upload('document') && $this->load->library('image_lib', $config)) {
            $galleryImage = 'assets/images/gallery/' . $this->upload->data('file_name');
        } else {
            //  echo $this->upload->display_errors();
        }

        $data['user_id'] = $user_id;
        $data['document'] = $galleryImage;
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $getId = $this->Api_model->insertGetId(GLY_TBL, $data);
        if ($getId) {
            $this->api->api_message(1, $this->translate('ADD_GALLERY', $lan));
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    public function send_email($email_id, $subject, $msg)
    {

        $this->load->library('email');

        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.mail.us-east-1.awsapps.com',
            'smtp_port' => 465,
            'smtp_user' => 'mail@expmaint.com',
            'smtp_pass' => 'EXP@123maint',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1'
        );

        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $this->email->from('mail@expmaint.com', APP_NAME);
        $this->email->to($email_id);
        $this->email->subject($subject);

        $datas['msg'] = $msg;
        $body = $this->load->view('main.php', $datas, TRUE);
        $this->email->message($body);

        $this->email->send();
    }


    public function send_otp()
    {
        $lan = $this->getlanguage();
        $phonecode = $this->input->post('phoneCode', TRUE);
        $mobile = $this->input->post('mobile', TRUE);
        if (!empty($mobile)) {
            $mobile = $phonecode . $mobile;
        } else {
            $this->api->api_message(0, $this->translate('MOBILE_EMPTY', $lan));
            die();
        }
        $msg = $this->input->post('message', TRUE);
        $subject = '';
        $send = $this->send_mobile_sms($mobile, $msg);
        $this->api->api_message(0, $this->translate('OTP_SEND', $lan));
    }

    public function msg_notification($mobile, $msg)
    {
        $send = $this->send_mobile_sms($mobile, $msg);
    }

    public function msgCurl($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function send_mobile_sms($mobile, $msg)
    {
        $msgg = $this->Api_model->getSingleRow('msg_key', array('id' => 1));
        $userid = $msgg->user_id;
        $password = $msgg->password;
        $sender = $msgg->sender;
        //$from= SENDER_EMAILL;
        //Prepare you post parameters

        $postData = [
            'sender' => $sender,
            'msg' => $msg,
            'to' => $mobile,
            'userid' => $userid,
            'password' => $password,
            'encoding' => 'UTF8',
        ];

        //API URL
        $url = "http://api.unifonic.com/wrapper/sendSMS.php";
        // init the resource
        $sendmsg = $this->msgCurl($url, $postData);
        return $sendmsg;
    }

    public function testMail()
    {
        $email_id = "somendrakm@gmail.com";
        $subject = 'this is test';
        $msg = 'this is test message';
        $this->send_email_new_by_msg($email_id, $subject, $msg);

    }

    public function send_email_new_by_msg($email_id, $subject, $msg)
    {

        $this->load->library('email');

        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.mail.us-east-1.awsapps.com',
            'smtp_port' => 465,
            'smtp_user' => 'mail@expmaint.com',
            'smtp_pass' => 'EXP@123maint',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1'
        );

        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $this->email->from('mail@expmaint.com', APP_NAME);
        $this->email->to($email_id);
        $this->email->subject($subject);

        $datas['msg'] = $msg;
        $body = $this->load->view('main.php', $datas, TRUE);
        $this->email->message($body);

        $this->email->send();
    }


    /*Get all Category*/
    public function getAllArtists()
    {
        $lan = $this->getlanguage();
        $latitude_app = $this->input->post('latitude');
        $longitude_app = $this->input->post('longitude');
        $category_id = $this->input->post('category_id');
        $distance = $this->input->post('distance');
        $user_id = $this->input->post('user_id');
        $page = $this->input->post('page');

        $page = isset($page) ? $page : 1;

        $this->checkUserStatus($user_id);

        if ($category_id) {
            $where = array('update_profile' => 1, 'booking_flag' => 0, 'is_online' => 1);

            $artist = $this->Api_model->getAllCatWhereLimit($category_id, $where, ART_TBL, $page);
        } else {
            $artist = $this->Api_model->getAllDataWhereLimitNew(array('update_profile' => 1, 'booking_flag' => 0, 'is_online' => 1), ART_TBL, $page);
        }

        function distance($lat1, $lon1, $lat2, $lon2)
        {

            try {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);
                return ($miles * 1.609344);
            } catch (Exception $e) {
                return (0.0);
            }
        }


        if ($artist) {
            $artists = array();
            foreach ($artist as $artist) {
                $artist_wallet = $this->Api_model->getSingleRow("artist_wallet", array('artist_id' => $artist->user_id));

                if ($artist->name != '' || $artist->category_id != 0) {

                    $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $artist->user_id));
                    if ($getUser->status == 1 && $getUser->approval_status == 1) {
                        $jobDone = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist->user_id, 'booking_flag' => 4));
                        $artist->total = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist->user_id,));
                        if ($artist->total == 0) {
                            $artist->percentages = 0;
                        } else {
                            $artist->percentages = round(($jobDone * 100) / $artist->total);
                        }
                        $artist->jobDone = $jobDone;

                        $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $artist->category_id));
                        $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $artist->user_id));
                        if ($getUser->image) {
                            $artist->image = base_url() . $getUser->image;
                        } else {
                            $artist->image = base_url() . "assets/images/image.png";
                        }
                        if ($get_cat) {
                            $artist->category_name = $get_cat->cat_name;//
                            $artist->category_name_ar = $get_cat->cat_name_ar;//
                        } else {
                            $artist->category_name = $this->translate('OTP_SEND', $lan);//
                        }


                        $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                        $artist->currency_type = $currency_setting->currency_symbol;
                        $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));
                        $artist->commission_type = $commission_setting->commission_type;
                        $artist->flat_type = $commission_setting->flat_type;
                        $artist->banner_image = base_url() . $artist->banner_image;
                        if ($commission_setting->commission_type == 0) {
                            $artist->category_price = $get_cat->price;
                        } elseif ($commission_setting->commission_type == 1) {
                            if ($commission_setting->flat_type == 2) {
                                $artist->category_price = $commission_setting->flat_amount;
                            } elseif ($commission_setting->flat_type == 1) {
                                $artist->category_price = $commission_setting->flat_amount;
                            }
                        }

                        $distance = distance($latitude_app, $longitude_app, $artist->live_lat, $artist->live_long);

                        $distance = round($distance);
                        $distance_str = "$distance";
                        $artist->distance = $distance_str;

                        $where = array('artist_id' => $artist->user_id);
                        $ava_rating = $this->Api_model->getAvgWhere('rating', 'rating', $where);
                        if ($ava_rating[0]->rating == null) {
                            $ava_rating[0]->rating = "0";
                        }
                        $artist->ava_rating = round($ava_rating[0]->rating, 2);
                        $check_fav = $this->Api_model->check_favorites($user_id, $artist->user_id);
                        $artist->fav_status = $check_fav ? "1" : "0";
                        if ($distance < 75) {
                            array_push($artists, $artist);
                        }
                    }
                }
            }

            usort($artists, function ($a, $b) {
                if ($a->distance == $b->distance) return 0;
                return ($a->distance < $b->distance) ? -1 : 1;
            });

            if (!empty($artists)) {
                $this->api->api_message_data(1, $this->translate('ALL_ARTISTS', $lan), 'data', $artists);
            } else {
                $this->api->api_message(0, $this->translate('NO_DATA', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    public function getApprovalStatus()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $this->checkUserStatus($user_id);

        $where = array('user_id' => $user_id);
        $get_artists = $this->Api_model->getSingleRow(USR_TBL, $where);

        $this->api->api_message_data(1, $this->translate('GET_APPROVAL_STATUS', $lan), 'approval_status', $get_artists->approval_status);
    }

    /*get profile by user_id*/
    public function getArtistByid()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $user_id = $this->input->post('user_id', TRUE);

        $this->checkUserStatus($user_id);

        $where = array('user_id' => $artist_id);
        $get_artists = $this->Api_model->getSingleRow(ART_TBL, $where);
        if ($get_artists) {
            $get_catname = array();
            $get_catname_ar = array();
            $get_cats = $this->Api_model->getAllCategoryByArtist($get_artists->category_id);

            //$get_cat=$this->Api_model->getSingleRow(CAT_TBL, array('id'=>$get_artists->category_id));
            if (!empty($get_cats)) {
                foreach ($get_cats as $get_cat) {
                    $get_catname[] = $get_cat->cat_name;
                    $get_catname_ar[] = $get_cat->cat_name_ar;
                }
            }
            $get_catname = implode(', ', $get_catname);
            $get_catname_ar = implode(', ', $get_catname_ar);
            $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_artists->user_id));
            if ($getUser->image) {
                $get_artists->image = base_url() . $getUser->image;
            } else {
                $get_artists->image = "";
            }
            $get_artists->category_name = $get_catname;
            $get_artists->category_name_ar = $get_catname_ar;
            $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
            $get_artists->currency_type = $currency_setting->currency_symbol;

            $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));
            $get_artists->commission_type = $commission_setting->commission_type;
            $get_artists->flat_type = $commission_setting->flat_type;
            if ($commission_setting->commission_type == 0) {
                $get_artists->category_price = $get_cat->price;
            } elseif ($commission_setting->commission_type == 1) {
                if ($commission_setting->flat_type == 2) {
                    $get_artists->category_price = $commission_setting->flat_amount;
                } elseif ($commission_setting->flat_type == 1) {
                    $get_artists->category_price = $commission_setting->flat_amount;
                }
            }

            $where = array('artist_id' => $artist_id, 'status' => 1);
            $ava_rating = $this->Api_model->getAvgWhere('rating', 'rating', $where);
            if ($ava_rating[0]->rating == null) {
                $ava_rating[0]->rating = "0";
            }
            $get_artists->ava_rating = round($ava_rating[0]->rating, 1);

            $skills = json_decode($get_artists->skills);
            $skill = array();
            if (!empty($skills)) {
                foreach ($skills as $skills) {
                    $get_skills = $this->Api_model->getSingleRow('skills', array('id' => $skills));
                    array_push($skill, $get_skills);
                }
            }

            $get_artists->skills = $skill;
            $get_products = $this->Api_model->getAllDataWhere(array('user_id' => $get_artists->user_id), 'products');

            $products = array();
            foreach ($get_products as $get_products) {
                $get_products->product_image = base_url() . $get_products->product_image;
                $get_products->currency_type = $currency_setting->currency_symbol;
                array_push($products, $get_products);
            }
            $get_artists->products = $products;

            $get_reviews = $this->Api_model->getAllDataWhere(array('artist_id' => $artist_id, 'status' => 1), 'rating');
            $review = array();
            foreach ($get_reviews as $get_reviews) {

                $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_reviews->user_id));
                $get_reviews->name = $get_user->name;
                if ($get_user->image) {
                    $get_reviews->image = base_url() . $get_user->image;
                } else {
                    $get_reviews->image = base_url() . "assets/images/image.png";
                }
                array_push($review, $get_reviews);
            }
            $get_artists->reviews = $review;

            $get_gallery = $this->Api_model->getAllDataWhere(array('user_id' => $artist_id), GLY_TBL);

            $gallery = array();
            foreach ($get_gallery as $get_gallery) {

                $get_gallery->image = base_url() . $get_gallery->image;
                array_push($gallery, $get_gallery);
            }
            $get_artists->gallery = $gallery;

            $get_qualifications = $this->Api_model->getAllDataWhere(array('user_id' => $artist_id), 'qualifications');

            $get_artists->qualifications = $get_qualifications;

            $artist_bookings = array();
            $artist_booking1 = array();
            $artist_booking = $this->Api_model->getAllDataLimitWhere(ABK_TBL, array('artist_id' => $artist_id, 'booking_flag' => 4), 7);
            foreach ($artist_booking as $artist_booking) {
                $rat = $this->Api_model->getSingleRow('rating', array('booking_id' => $artist_booking->id, 'status' => 1));
                if ($rat) {
                    $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $rat->user_id));
                    $artist_booking1['username'] = $get_user->name;
                    if ($get_user->image) {
                        $artist_booking1['userImage'] = base_url() . $get_user->image;
                    } else {
                        $artist_booking1['userImage'] = base_url() . "assets/images/image.png";
                    }
                    $artist_booking1['rating'] = $rat->rating;
                    $artist_booking1['comment'] = $rat->comment;
                    $artist_booking1['ratingDate'] = $rat->created_at;
                } else {
                    $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $artist_booking->user_id));
                    $artist_booking1['username'] = $get_user->name;
                    if ($get_user->image) {
                        $artist_booking1['userImage'] = base_url() . $get_user->image;
                    } else {
                        $artist_booking1['userImage'] = base_url() . "assets/images/image.png";
                    }
                    $artist_booking1['rating'] = "0";
                    $artist_booking1['comment'] = "";
                    $artist_booking1['ratingDate'] = $artist_booking->created_at;
                }

                $artist_booking1['price'] = $artist_booking->price;
                $artist_booking1['currency_type'] = $currency_setting->currency_symbol;
                $artist_booking1['booking_time'] = $artist_booking->booking_time;
                $artist_booking1['booking_date'] = $artist_booking->booking_date;
                array_push($artist_bookings, $artist_booking1);
            }
            $get_artists->artist_booking = $artist_bookings;

            $earning = $this->Api_model->getSumWhere('total_amount', IVC_TBL, array('artist_id' => $artist_id));

            $get_artists->earning = round($earning->total_amount, 2);

            $get_artists->jobDone = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist_id, 'booking_flag' => 4));

            $get_artists->totalJob = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist_id));

            if ($get_artists->totalJob == 0) {
                $get_artists->completePercentages = 0;
            } else {
                $get_artists->completePercentages = round(($get_artists->jobDone * 100) / $get_artists->totalJob);
            }
            $check_fav = $this->Api_model->check_favorites($user_id, $artist_id);
            $get_artists->fav_status = $check_fav ? "1" : "0";
            $get_artists->banner_image = base_url() . $get_artists->banner_image;
            $this->api->api_message_data(1, $this->translate('GET_ARTIST_DETAIL', $lan), 'data', $get_artists);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*Update artist personal info*/
    public function generateTicket()
    {
        $lan = $this->getlanguage();
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['reason'] = $this->input->post('reason', TRUE);
        $data['craeted_at'] = time();

        $this->checkUserStatus($data['user_id']);

        $ticketId = $this->Api_model->insertGetId('ticket', $data);
        if ($ticketId) {
            $datas['ticket_id'] = $ticketId;
            $datas['comment'] = $this->input->post('description', TRUE);
            $datas['user_id'] = $data['user_id'];
            $datas['role'] = 1;
            $datas['created_at'] = time();

            $this->Api_model->insertGetId('ticket_comments', $datas);
            $this->api->api_message(1, $this->translate('NO_DATA', $lan));
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*get Ticket*/
    public function getMyTicket()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $this->checkUserStatus($user_id);

        $get_ticket = $this->Api_model->getAllDataWhere(array('user_id' => $user_id), 'ticket');
        if ($get_ticket) {
            $this->api->api_message_data(1, $this->translate('GET_MY_TICKETS', $lan), 'my_ticket', $get_ticket);
        } else {
            $this->api->api_message(0, $this->translate('NOT_YET_ANY_TICKETS', $lan));
        }
    }

    /*Add ticket Comments*/
    public function addTicketComments()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $this->checkUserStatus($user_id);

        $data['ticket_id'] = $this->input->post('ticket_id', TRUE);
        $data['comment'] = $this->input->post('comment', TRUE);
        $data['user_id'] = $user_id;
        $data['role'] = 1;
        $data['created_at'] = time();

        $ticketId = $this->Api_model->insertGetId('ticket_comments', $data);
        if ($ticketId) {
            $this->api->api_message(1, $this->translate('THANKS_FOR_THE_REVIEW', $lan));
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*get Ticket Comments*/
    public function getTicketComments()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $ticket_id = $this->input->post('ticket_id', TRUE);
        $this->checkUserStatus($user_id);

        $ticket_comments = $this->Api_model->getAllDataWhere(array('ticket_id' => $ticket_id), 'ticket_comments');

        $ticket_comment = array();
        foreach ($ticket_comments as $ticket_comments) {
            if ($ticket_comments->user_id != 0) {
                $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $ticket_comments->user_id));
                $ticket_comments->userName = $getUser->name;
            } else {
                $ticket_comments->userName = "Admin";
            }
            array_push($ticket_comment, $ticket_comments);
        }

        if ($ticket_comments) {
            $this->api->api_message_data(1, $this->translate('GET_TICKET_COMMENTS', $lan), 'ticket_comment', $ticket_comment);
        } else {
            $this->api->api_message(0, $this->translate('NOT_YET_ANY_TICKETS', $lan));
        }
    }

    /*get conversation*/
    public function getNotifications()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $this->checkUserStatus($user_id);

        $get_notifications = $this->Api_model->getAllDataWhereAndOr(array('user_id' => $user_id), array('type' => "All"), NTS_TBL);
        if ($get_notifications) {
            $this->api->api_message_data(1, $this->translate('GET_MY_NOTIFICATIONS', $lan), 'my_notifications', $get_notifications);
        } else {
            $this->api->api_message(0, $this->translate('NOT_YET_ANY_NOTIFICATIONS', $lan));
        }
    }

    public function deleteNotification()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $notification_id = $this->input->post('notification_id', TRUE);


        $deleteNotification = $this->Api_model->deleteRecord(array('id'=>$notification_id , 'user_id' => $user_id), NTS_TBL);

        $this->api->api_message(1, $this->translate('RECROD_DELETED', $lan));

    }

    /*Update artist personal info*/
    public function deleteProfileImage()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);

        $this->checkUserStatus($user_id);

        $updateUser = $this->Api_model->updateSingleRow(ART_TBL, array('user_id' => $user_id), array('image' => ''));

        $this->api->api_message(1, $this->translate('PROFILE_IMG_DELETE', $lan));
    }

    public function artistImage()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $image = $this->input->post('image', TRUE);

        $this->load->library('upload');

        $config['image_library'] = 'gd2';
        $config['upload_path'] = './assets/images/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 10000;
        $config['file_name'] = time();
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 250;
        $config['height'] = 250;
        $this->upload->initialize($config);
        $updateduserimage = "";
        if ($this->upload->do_upload('image') && $this->load->library('image_lib', $config)) {
            $updateduserimage = 'assets/images/' . $this->upload->data('file_name');
        } else {
            //  echo $this->upload->display_errors();
        }

        $check_user = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $user_id));
        if ($check_user) {
            $data['image'] = $updateduserimage;

            $where = array('user_id' => $user_id);
            $updateUser = $this->Api_model->updateSingleRow(ART_TBL, $where, $data);
            if ($updateUser) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $user_id));

                $checkartist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $user_id));

                if ($checkartist->image) {
                    $checkUser->image = base_url() . $checkartist->image;
                } else {
                    $checkUser->image = base_url() . 'assets/images/image.png';
                }
                $this->api->api_message_data(1, $this->translate('ARTIST_UPDATE', $lan), 'data', $checkUser);
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } else {

            $this->load->library('upload');

            $config['image_library'] = 'gd2';
            $config['upload_path'] = './assets/images/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 10000;
            $config['file_name'] = time();
            $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 250;
            $config['height'] = 250;
            $this->upload->initialize($config);
            $updateduserimage = "";
            if ($this->upload->do_upload('image') && $this->load->library('image_lib', $config)) {
                $updateduserimage = 'assets/images/' . $this->upload->data('file_name');
            } else {
                //  echo $this->upload->display_errors();
            }

            $data['user_id'] = $user_id;
            if ($updateduserimage) {
                $data['image'] = $updateduserimage;
            }

            $getUserId = $this->Api_model->insertGetId(ART_TBL, $data);

            if ($getUserId) {

                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $user_id));

                $checkartist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $user_id));

                if ($checkartist->image) {
                    $checkUser->image = base_url() . $checkartist->image;
                } else {
                    $checkUser->image = base_url() . 'assets/images/image.png';
                }

                $this->api->api_message_data(1, $this->translate('ARTIST_UPDATE', $lan), 'data', $checkUser);
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        }
    }

    /*Update artist personal info*/
    public function artistPrsonalInfo()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $name = $this->input->post('name', TRUE);
        $gender = $this->input->post('gender', TRUE);
        $city = $this->input->post('city', TRUE);
        $country = $this->input->post('country', TRUE);
        $preference = $this->input->post('preference', TRUE);
        $category_id = $this->input->post('category_id', TRUE);
        $about_us = $this->input->post('about_us', TRUE);
        $description = $this->input->post('description', TRUE);
        $bio = $this->input->post('bio', TRUE);
        $location = $this->input->post('location', TRUE);
        $image = $this->input->post('image', TRUE);
        $longitude = $this->input->post('longitude', TRUE);
        $latitude = $this->input->post('latitude', TRUE);
        $skills = $this->input->post('skills', TRUE);
        $price = $this->input->post('price', TRUE);
        $video_url = $this->input->post('video_url', TRUE);
        $banner_image = $this->input->post('banner_image', TRUE);


        $this->checkUserStatus($user_id);

        $check_user = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $user_id));
        $this->load->library('upload');
        $config['image_library'] = 'gd2';
        $config['upload_path'] = './assets/images/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 10000;
        $config['file_name'] = time();
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 250;
        $config['height'] = 250;
        $this->upload->initialize($config);
        $update_banner_image = "";
        if ($this->upload->do_upload('banner_image') && $this->load->library('image_lib', $config)) {
            $update_banner_image = 'assets/images/' . $this->upload->data('file_name');
        }

        $this->load->library('upload');
        $config['image_library'] = 'gd2';
        $config['upload_path'] = './assets/images/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 10000;
        $config['file_name'] = time();
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 250;
        $config['height'] = 250;
        $this->upload->initialize($config);
        $updateduserimage = "";
        if ($this->upload->do_upload('image') && $this->load->library('image_lib', $config)) {
            $updateduserimage = 'assets/images/' . $this->upload->data('file_name');
        }


        if ($check_user) {
            $where = array('user_id' => $user_id);
            $data['name'] = isset($name) ? $name : $check_user->name;

            $this->Api_model->updateSingleRow(USR_TBL, $where, $data);

            $data['gender'] = isset($gender) ? $gender : $check_user->gender;
            $data['city'] = isset($city) ? $city : $check_user->city;
            $data['country'] = isset($country) ? $country : $check_user->country;
            $data['preference'] = isset($preference) ? $preference : $check_user->preference;
            $data['price'] = isset($price) ? $price : $check_user->price;
            $data['skills'] = isset($skills) ? $skills : $check_user->skills;
            $data['about_us'] = isset($about_us) ? $about_us : $check_user->about_us;
            $data['longitude'] = isset($longitude) ? $longitude : $check_user->longitude;
            $data['latitude'] = isset($latitude) ? $latitude : $check_user->latitude;
            $data['description'] = isset($description) ? $description : $check_user->description;
            $data['video_url'] = isset($video_url) ? $video_url : $check_user->video_url;
            if ($updateduserimage) {
                $data['image'] = $updateduserimage;
            }
            if ($update_banner_image) {
                $data['banner_image'] = $update_banner_image;
            }

            $data['category_id'] = isset($category_id) ? $category_id : $check_user->category_id;
            $data['bio'] = isset($bio) ? $bio : $check_user->bio;
            $data['updated_at'] = time();
            $data['update_profile'] = 1;
            $data['location'] = isset($location) ? $location : $check_user->location;
            $updateUser = $this->Api_model->updateSingleRow(ART_TBL, $where, $data);
            if ($updateUser) {
                $where = array('user_id' => $user_id);
                $get_artists = $this->Api_model->getSingleRow(ART_TBL, $where);
                $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));

                if (!empty($get_cat)) {
                    $get_artists->category_name = $get_cat->cat_name;
                    $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));
                    if ($commission_setting->commission_type == 0) {
                        $get_artists->category_price = $get_cat->price;
                    } elseif ($commission_setting->commission_type == 1) {
                        if ($commission_setting->flat_type == 2) {
                            $get_artists->category_price = $commission_setting->flat_amount;
                        } elseif ($commission_setting->flat_type == 1) {
                            $get_artists->category_price = $commission_setting->flat_amount;
                        }
                    }
                } else {
                    $get_artists->category_name = "";
                    $get_artists->category_price = "";
                }

                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $get_artists->currency_type = $currency_setting->currency_symbol;

                $checkArtist = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_artists->user_id));

                if ($checkArtist->image) {
                    $get_artists->image = base_url() . $checkArtist->image;
                } else {
                    $get_artists->image = base_url() . '/assets/images/image.png';
                }

                $where = array('artist_id' => $get_artists->user_id);
                $ava_rating = $this->Api_model->getAvgWhere('rating', 'rating', $where);
                if ($ava_rating[0]->rating == null) {
                    $ava_rating[0]->rating = "0";
                }
                $get_artists->ava_rating = round($ava_rating[0]->rating, 1);

                $skills = json_decode($get_artists->skills);
                $skill = array();
                if (!empty($skills)) {
                    foreach ($skills as $skills) {
                        $get_skills = $this->Api_model->getSingleRow('skills', array('id' => $skills));
                        array_push($skill, $get_skills);
                    }
                    $get_artists->skills = $skill;
                } else {
                    $get_artists->skills = array();
                }

                $get_products = $this->Api_model->getAllDataWhere(array('user_id' => $user_id), 'products');

                $products = array();
                foreach ($get_products as $get_products) {
                    $get_products->product_image = base_url() . $get_products->product_image;
                    array_push($products, $get_products);
                }
                $get_artists->products = $products;

                $get_reviews = $this->Api_model->getAllDataWhere(array('artist_id' => $user_id, 'status' => 1), 'rating');
                $review = array();
                foreach ($get_reviews as $get_reviews) {
                    $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_reviews->user_id));
                    $get_reviews->name = $get_user->name;

                    if ($get_user->image) {
                        $get_reviews->image = base_url() . $get_user->image;
                    } else {
                        $get_reviews->image = base_url() . '/assets/images/image.png';
                    }

                    array_push($review, $get_reviews);
                }
                $get_artists->reviews = $review;

                $get_qualifications = $this->Api_model->getAllDataWhere(array('user_id' => $user_id), 'qualifications');

                $get_artists->qualifications = $get_qualifications;

                $get_gallery = $this->Api_model->getAllDataWhere(array('user_id' => $user_id), GLY_TBL);

                $gallery = array();
                foreach ($get_gallery as $get_gallery) {

                    $get_gallery->image = base_url() . $get_gallery->image;
                    array_push($gallery, $get_gallery);
                }
                $get_artists->gallery = $gallery;

                $earning = $this->Api_model->getSumWhere('total_amount', IVC_TBL, array('artist_id' => $user_id));

                $get_artists->earning = round($earning->total_amount, 2);

                $get_artists->jobDone = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $user_id, 'booking_flag' => 4));

                $get_artists->totalJob = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $user_id));

                if ($get_artists->totalJob == 0) {
                    $get_artists->completePercentages = 0;
                } else {
                    $get_artists->completePercentages = round(($get_artists->jobDone * 100) / $get_artists->totalJob);
                }

                $artist_booking = $this->Api_model->getAllDataLimitWhere(ABK_TBL, array('artist_id' => $user_id, 'booking_flag' => 4), 7);
                $artist_bookings = array();
                foreach ($artist_booking as $artist_booking) {
                    $rat = $this->Api_model->getSingleRow('rating', array('booking_id' => $artist_booking->id, 'status' => 1));
                    if ($rat) {
                        $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $rat->user_id));
                        $artist_booking1['username'] = $get_user->name;
                        if ($get_user->image) {
                            $artist_booking1['userImage'] = base_url() . $get_user->image;
                        } else {
                            $artist_booking1['userImage'] = base_url() . "assets/images/image.png";
                        }
                        $artist_booking1['rating'] = $rat->rating;
                        $artist_booking1['comment'] = $rat->comment;
                        $artist_booking1['ratingDate'] = $rat->created_at;
                    } else {
                        $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $artist_booking->user_id));
                        $artist_booking1['username'] = $get_user->name;
                        if ($get_user->image) {
                            $artist_booking1['userImage'] = base_url() . $get_user->image;
                        } else {
                            $artist_booking1['userImage'] = base_url() . "assets/images/image.png";
                        }
                        $artist_booking1['rating'] = "0";
                        $artist_booking1['comment'] = "";
                        $artist_booking1['ratingDate'] = $artist_booking->created_at;
                    }

                    $artist_booking1['price'] = $artist_booking->price;
                    $artist_booking1['booking_time'] = $artist_booking->booking_time;
                    $artist_booking1['booking_date'] = $artist_booking->booking_date;

                    array_push($artist_bookings, $artist_booking1);
                }
                $get_artists->artist_booking = $artist_bookings;
                $get_artists->banner_image = base_url() . $get_artists->banner_image;
                $this->api->api_message_data(1, $this->translate('ARTIST_UPDATE', $lan), 'data', $get_artists);
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } else {
            $this->load->library('upload');

            $config['image_library'] = 'gd2';
            $config['upload_path'] = './assets/images/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 10000;
            $config['file_name'] = time();
            $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 250;
            $config['height'] = 250;
            $this->upload->initialize($config);
            $updateduserimage = "";
            if ($this->upload->do_upload('image') && $this->load->library('image_lib', $config)) {
                $updateduserimage = 'assets/images/' . $this->upload->data('file_name');
            } else {
                //  echo $this->upload->display_errors();
            }

            $data['user_id'] = $user_id;
            $data['name'] = isset($name) ? $name : "";
            if ($updateduserimage) {
                $data['image'] = $updateduserimage;
            }
            $this->Api_model->updateSingleRow(USR_TBL, array('user_id' => $user_id), $data);
            if ($update_banner_image) {
                $data['banner_image'] = $update_banner_image;
            }

            $data['created_at'] = time();
            $data['update_profile'] = 1;
            $data['updated_at'] = time();
            $data['gender'] = isset($gender) ? $gender : "";
            $data['city'] = isset($city) ? $city : "";
            $data['country'] = isset($country) ? $country : "";
            $data['preference'] = isset($preference) ? $preference : "";
            $data['about_us'] = isset($about_us) ? $about_us : "";
            $data['price'] = isset($price) ? $price : "";
            $data['skills'] = isset($skills) ? $skills : "";
            $data['longitude'] = isset($longitude) ? $longitude : "75.897542";
            $data['latitude'] = isset($latitude) ? $latitude : "22.749753";
            $data['category_id'] = isset($category_id) ? $category_id : "";
            $data['description'] = isset($description) ? $description : "";
            $data['bio'] = isset($bio) ? $bio : "";
            $data['location'] = isset($location) ? $location : "";

            $getUserId = $this->Api_model->insertGetId(ART_TBL, $data);

            if ($getUserId) {
                $where = array('user_id' => $user_id);
                $get_artists = $this->Api_model->getSingleRow(ART_TBL, $where);

                $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));
                $checkArtist = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_artists->user_id));

                if ($checkArtist->image) {
                    $get_artists->image = base_url() . $checkArtist->image;
                } else {
                    $get_artists->image = base_url() . '/assets/images/image.png';
                }

                if (!empty($get_cat)) {
                    $get_artists->category_name = $get_cat->cat_name;
                    $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));
                    if ($commission_setting->commission_type == 0) {
                        $get_artists->category_price = $get_cat->price;
                    } elseif ($commission_setting->commission_type == 1) {
                        if ($commission_setting->flat_type == 2) {
                            $get_artists->category_price = $commission_setting->flat_amount;
                        } elseif ($commission_setting->flat_type == 1) {
                            $get_artists->category_price = $commission_setting->flat_amount;
                        }
                    }
                } else {
                    $get_artists->category_name = "";
                    $get_artists->category_price = "";
                }

                $where = array('artist_id' => $user_id);
                $ava_rating = $this->Api_model->getAvgWhere('rating', 'rating', $where);
                if ($ava_rating[0]->rating == null) {
                    $ava_rating[0]->rating = "0";
                }
                $get_artists->ava_rating = round($ava_rating[0]->rating, 1);

                $skills = json_decode($get_artists->skills);
                $skill = array();
                if (!empty($skills)) {
                    foreach ($skills as $skills) {
                        $get_skills = $this->Api_model->getSingleRow('skills', array('id' => $skills));
                        array_push($skill, $get_skills);
                    }
                    $get_artists->skills = $skill;
                } else {
                    $get_artists->skills = array();
                }

                $get_products = $this->Api_model->getAllDataWhere(array('user_id' => $user_id), 'products');

                $products = array();
                foreach ($get_products as $get_products) {
                    $get_products->product_image = base_url() . $get_products->product_image;
                    array_push($products, $get_products);
                }
                $get_artists->products = $products;

                $get_reviews = $this->Api_model->getAllDataWhere(array('artist_id' => $user_id), 'rating');
                $review = array();
                foreach ($get_reviews as $get_reviews) {

                    $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $user_id));
                    $get_reviews->name = $get_user->name;
                    if ($get_user->image) {
                        $get_reviews->image = base_url() . $get_user->image;
                    } else {
                        $get_reviews->image = base_url() . '/assets/images/image.png';
                    }
                    array_push($review, $get_reviews);
                }
                $get_artists->reviews = $review;

                $get_qualifications = $this->Api_model->getAllDataWhere(array('user_id' => $user_id), 'qualifications');

                $get_artists->qualifications = $get_qualifications;

                $get_gallery = $this->Api_model->getAllDataWhere(array('user_id' => $user_id), GLY_TBL);

                $gallery = array();
                foreach ($get_gallery as $get_gallery) {

                    $get_gallery->image = base_url() . $get_gallery->image;
                    array_push($gallery, $get_gallery);
                }
                $get_artists->gallery = $gallery;

                $earning = $this->Api_model->getSumWhere('total_amount', IVC_TBL, array('artist_id' => $user_id));

                $get_artists->earning = round($earning->total_amount, 2);

                $get_artists->jobDone = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $user_id, 'booking_flag' => 4));

                $get_artists->totalJob = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $user_id));

                if ($get_artists->totalJob == 0) {
                    $get_artists->completePercentages = 0;
                } else {
                    $get_artists->completePercentages = round(($get_artists->jobDone * 100) / $get_artists->totalJob);
                }

                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $get_artists->currency_type = $currency_setting->currency_symbol;

                $artist_booking = $this->Api_model->getAllDataLimitWhere(ABK_TBL, array('artist_id' => $user_id, 'booking_flag' => 4), 7);
                $artist_bookings = array();
                foreach ($artist_booking as $artist_booking) {
                    $rat = $this->Api_model->getSingleRow('rating', array('booking_id' => $artist_booking->id, 'status' => 1));
                    if ($rat) {
                        $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $rat->user_id));
                        $artist_booking1['username'] = $get_user->name;
                        if ($get_user->image) {
                            $artist_booking1['userImage'] = base_url() . $get_user->image;
                        } else {
                            $artist_booking1['userImage'] = base_url() . "assets/images/image.png";
                        }
                        $artist_booking1['rating'] = $rat->rating;
                        $artist_booking1['comment'] = $rat->comment;
                        $artist_booking1['ratingDate'] = $rat->created_at;
                    } else {
                        $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $artist_booking->user_id));
                        $artist_booking1['username'] = $get_user->name;
                        if ($get_user->image) {
                            $artist_booking1['userImage'] = base_url() . $get_user->image;
                        } else {
                            $artist_booking1['userImage'] = base_url() . "assets/images/image.png";
                        }
                        $artist_booking1['rating'] = "0";
                        $artist_booking1['comment'] = "";
                        $artist_booking1['ratingDate'] = $artist_booking->created_at;
                    }

                    $artist_booking1['price'] = $artist_booking->price;
                    $artist_booking1['booking_time'] = $artist_booking->booking_time;
                    $artist_booking1['booking_date'] = $artist_booking->booking_date;
                    array_push($artist_bookings, $artist_booking1);
                }
                $get_artists->artist_booking = $artist_bookings;
                $get_artists->banner_image = base_url() . $get_artists->banner_image;
                $this->api->api_message_data(1, $this->translate('ARTIST_UPDATE', $lan), 'data', $get_artists);
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        }
    }

    /*Add product by artist*/
    public function addProduct()
    {
        $lan = $this->getlanguage();
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['product_name'] = $this->input->post('product_name', TRUE);
        $product_image = $this->input->post('product_image', TRUE);
        $data['price'] = $this->input->post('price', TRUE);
        $this->checkUserStatus($data['user_id']);


        $this->load->library('upload');

        $config['image_library'] = 'gd2';
        $config['upload_path'] = './assets/images/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 10000;
        $config['file_name'] = time();
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 250;
        $config['height'] = 250;
        $this->upload->initialize($config);
        //$pruductImage="";
        if ($this->upload->do_upload('product_image') && $this->load->library('image_lib', $config)) {
            $pruductImage = 'assets/images/' . $this->upload->data('file_name');
        } else {
            $pruductImage = 'assets/images/fixer.jpg';
        }


        if ($pruductImage) {
            $data['product_image'] = $pruductImage;
        }
        $data['created_at'] = time();
        $data['updated_at'] = time();

        $productId = $this->Api_model->insertGetId('products', $data);

        if ($productId) {
            $this->api->api_message(1, $this->translate('PRODUCT_ADD', $lan));
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }


    /*Add Qualification by artist*/
    public function addQualification()
    {
        $lan = $this->getlanguage();
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['title'] = $this->input->post('title', TRUE);
        $data['description'] = $this->input->post('description', TRUE);

        $this->checkUserStatus($data['user_id']);

        $data['created_at'] = time();
        $data['updated_at'] = time();

        $productId = $this->Api_model->insertGetId('qualifications', $data);

        if ($productId) {
            $this->api->api_message(1, $this->translate('QUALIFICATION_ADD', $lan));
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    /*Add Rating for artist*/
    public function addRating()
    {
        $lan = $this->getlanguage();
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['booking_id'] = $this->input->post('booking_id', TRUE);
        $data['artist_id'] = $this->input->post('artist_id', TRUE);
        $data['rating'] = $this->input->post('rating', TRUE);
        $data['comment'] = $this->input->post('comment', TRUE);

        $this->checkUserStatus($data['user_id']);
        $this->checkUserStatus($data['artist_id']);

        $data['created_at'] = time();

        $get_rating = $this->Api_model->getSingleRow('rating', array('user_id' => $data['user_id'], 'artist_id' => $data['artist_id'], 'booking_id' => $data['booking_id']));
        if ($get_rating) {
            $this->api->api_message(1, $this->translate('YOU_ALREADY_GIVE', $lan));
        } else {
            $productId = $this->Api_model->insertGetId('rating', $data);
            if ($productId) {
                $this->api->api_message(1, $this->translate('ADD_COMMENT', $lan));
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        }
    }

    public function getToken()
    {
        echo $this->api->strongToken(6);
    }

    function get_client_ip()
    {
        $ipaddress = 'test';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function updatePassword()
    {
        $code = $this->input->post('code', TRUE);
        $password1 = $this->input->post('password1', TRUE);
        $password2 = $this->input->post('password2', TRUE);
        if (!empty($password1) and !empty($password2)) {
            if ($password1 == $password2) {
                $condition = array('password' => md5($password1), 'reset' => $this->get_client_ip());
                $updatePassword = $this->Api_model->updateSingleRow(USR_TBL, array('reset' => $code), $condition);
                $data['success'] = 'Password Updated';
            } else {
                $data['error'] = 'Password does not match';
            }
        } else {
            $data['error'] = "Password field can't be empty";
        }
        $this->redirect(base_url() . 'Webservice/verifyMail?code=' . $code . '');
    }

    public function verifyMail()
    {
        $code = $_GET['code'];
        if (isset($code)) {
            $condition = array('reset' => $code);
            $checkcode = $this->Api_model->getSingleRow(USR_TBL, $condition);
            if ($checkcode) {
                $data['heading'] = 'Reset Your Password Now';
                $data['password'] = true;
                $recover = $this->input->post('recover', TRUE);
                if (!empty($recover)) {
                    $password1 = $this->input->post('password1', TRUE);
                    $password2 = $this->input->post('password2', TRUE);
                    if (!empty($password1) and !empty($password2)) {
                        if ($password1 == $password2) {
                            $condition = array('password' => md5($password1), 'reset' => $this->get_client_ip());
                            $updatePassword = $this->Api_model->updateSingleRow(USR_TBL, array('reset' => $code), $condition);
                            $data['success'] = 'Password Updated';
                        } else {
                            $data['error'] = 'Password does not match';
                        }
                    } else {
                        $data['error'] = "Password field can't be empty";
                    }
                }
            } else {
                $data['heading'] = "Something is wrong try again sometime.";
                $data['password'] = false;
            }

            $data['title'] = 'Reset Password - Express Maintenance';
            $this->load->view('verifyUser.php', $data);
        } else {
            echo "404 Page Not Found";
            die();
        }

    }

    /*Forget PAssword*/
    public function forgotPassword()
    {
        $lan = $this->getlanguage();
        $email_id = $this->input->post('email_id', TRUE);

        if (filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
            $condition = array('email_id' => $email_id);
        } else {
            $condition = array('mobile' => $email_id);
        }

        $checkEmail = $this->Api_model->getSingleRow(USR_TBL, $condition);
        if ($checkEmail) {
            $password = $this->api->strongToken(6);
            if (filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
                $msg = 'Dear ' . $checkEmail->name . ',<br>
              You recently requested a password reset for your account. To complete the process, click the link below.
              <br> <a href="' . base_url() . 'Webservice/verifyMail/?code=' . $password . '">Reset now</a>';
                $this->send_email_new_by_msg($email_id, PWD_SUB, $msg);
            } else {
                $msg = 'For reset password click the link below: ' . base_url() . 'Webservice/verifyMail/?code=' . $password . '';
                $this->msg_notification($checkEmail->phonecode . $email_id, $msg);
            }
            $data = array('reset' => $password);
            $updatePassword = $this->Api_model->updateSingleRow(USR_TBL, $condition, $data);

            $this->api->api_message(1, $this->translate('FOUND', $lan));
        } else {
            $this->api->api_message(0, $this->translate('NOTFOUND', $lan));
        }
    }

    public function userActive()
    {
        $user_id = $_GET['user_id'];
        $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $user_id));

        if ($get_user) {
            $where = array('user_id' => $get_user->user_id);
            $data = array('status' => 1);
            $update = $this->Api_model->updateSingleRow(USR_TBL, $where, $data);

            $this->load->view('activeUser.php');
        }
    }

    public function onlineOffline()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $is_online = $this->input->post('is_online', TRUE);
        $update = $this->Api_model->updateSingleRow(ART_TBL, array('user_id' => $user_id), array('is_online' => $is_online));
        if ($is_online == 1) {
            $this->api->api_message(1, $this->translate('ARTIST_ONLINE', $lan));
        } elseif ($is_online == 0) {
            $this->api->api_message(1, $this->translate('ARTIST_OFFLINE', $lan));
        }
    }

    /*Edit User Profile */
    public function editPersonalInfo()
    {
        $lan = $this->getlanguage();
        $name = $this->input->post('name', TRUE);
        $email_id = $this->input->post('email_id', TRUE);
        $user_id = $this->input->post('user_id', TRUE);
        $city = $this->input->post('city_id', TRUE);
        $country = $this->input->post('country_id', TRUE);
        $address = $this->input->post('address', TRUE);
        $latitude = $this->input->post('latitude', TRUE);
        $longitude = $this->input->post('longitude', TRUE);
        $gender = $this->input->post('gender', TRUE);
        $mobile = $this->input->post('mobile', TRUE);
        $office_address = $this->input->post('office_address', TRUE);
        $image = $this->input->post('image', TRUE);
        $i_card = $this->input->post('i_card', TRUE);
        $password = $this->input->post('password', TRUE);
        $new_password = $this->input->post('new_password', TRUE);
        $account_holder_name = $this->input->post('account_holder_name', TRUE);
        $bank_name = $this->input->post('bank_name', TRUE);
        $account_no = $this->input->post('account_no', TRUE);
        $ifsc_code = $this->input->post('ifsc_code', TRUE);

        $this->checkUserStatus($user_id);

        $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $user_id));
        if ($checkUser) {
            if ($email_id != $checkUser->email_id) {
                $checkEmail = $this->Api_model->getSingleRow(USR_TBL, array('email_id' => $email_id));

                if ($checkEmail) {
                    $this->api->api_message(0, $this->translate('EMAIL_EXIST', $lan));
                    exit();
                }
            }

            if ($password) {
                $checkPass = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $user_id, 'password' => md5($password)));

                if ($checkPass) {
                    $where = array('user_id' => $user_id);
                    $data['password'] = md5($new_password);
                    // $updateUser =$this->Api_model->updateSingleRow(USR_TBL, $where, $data);

                    $this->api->api_message(0, $this->translate('EDITSUCCESSFULL', $lan));
                } else {
                    $this->api->api_message(0, $this->translate('OLD_PASS', $lan));
                }
                exit();
            }

            $this->load->library('upload');

            $config['image_library'] = 'gd2';
            $config['upload_path'] = './assets/images/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 100000;
            $config['file_name'] = time();
            $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 250;
            $config['height'] = 250;
            $this->upload->initialize($config);
            $ProfileImage = "";
            if ($this->upload->do_upload('image') && $this->load->library('image_lib', $config)) {
                $ProfileImage = 'assets/images/' . $this->upload->data('file_name');
            } else {
                //  echo $this->upload->display_errors();
            }


            $this->load->library('upload');

            $config['image_library'] = 'gd2';
            $config['upload_path'] = './assets/images/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 100000;
            $config['file_name'] = time();
            $config['create_thumb'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 250;
            $config['height'] = 250;
            $this->upload->initialize($config);
            $CardImage = "";
            if ($this->upload->do_upload('i_card') && $this->load->library('image_lib', $config)) {
                $CardImage = 'assets/images/' . $this->upload->data('file_name');
            } else {
                //  echo $this->upload->display_errors();
            }

            $where = array('user_id' => $user_id);
            $data['name'] = isset($name) ? $name : $checkUser->name;
            $this->Api_model->updateSingleRow(ART_TBL, array('user_id' => $user_id), $data);

            if ($ProfileImage) {
                $data['image'] = $ProfileImage;
            }

            if ($CardImage) {
                $data['i_card'] = $CardImage;
            }
            $data['latitude'] = isset($latitude) ? $latitude : $checkUser->latitude;
            $data['ifsc_code'] = isset($ifsc_code) ? $ifsc_code : $checkUser->ifsc_code;
            $data['account_no'] = isset($account_no) ? $account_no : $checkUser->account_no;
            $data['bank_name'] = isset($bank_name) ? $bank_name : $checkUser->bank_name;
            $data['account_holder_name'] = isset($account_holder_name) ? $account_holder_name : $checkUser->account_holder_name;
            $data['longitude'] = isset($longitude) ? $longitude : $checkUser->longitude;
            $data['gender'] = isset($gender) ? $gender : $checkUser->gender;
            $data['mobile'] = isset($mobile) ? $mobile : $checkUser->mobile;
            $data['office_address'] = isset($office_address) ? $office_address : $checkUser->office_address;
            $data['email_id'] = isset($email_id) ? $email_id : $checkUser->email_id;
            $data['address'] = isset($address) ? $address : $checkUser->address;
            $data['city_id'] = isset($city) ? $city : $checkUser->city;
            $data['country_id'] = isset($country) ? $country : $checkUser->country;

            $updateUser = $this->Api_model->updateSingleRow(USR_TBL, $where, $data);
            $checkUser = $this->Api_model->getUserData(array('user_id' => $user_id));
            $user_id = $checkUser->user_id;
            $role = $checkUser->role;

            if ($checkUser->i_card) {
                $checkUser->i_card = base_url() . $checkUser->i_card;
            } else {
                $checkUser->i_card = base_url() . "assets/images/image.png";
            }
            if ($role == 1) {
                if ($checkUser->image) {
                    $checkUser->image = base_url() . $checkUser->image;
                } else {
                    $checkUser->image = base_url() . "assets/images/image.png";
                }
            } else {
                if ($checkUser->image) {
                    $checkUser->image = base_url() . $checkUser->image;
                } else {
                    $checkUser->image = base_url() . "assets/images/image.png";
                }
            }

            $checkArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $user_id));
            if ($checkArtist) {
                $checkUser->is_profile = 1;
            } else {
                $checkUser->is_profile = 0;
            }
            $this->api->api_message_data(1, $this->translate('EDITSUCCESSFULL', $lan), 'data', $checkUser);
        } else {
            $this->api->api_message(0, $this->translate('NOTAVAILABLE', $lan));
        }
    }

    /*Update or Create artist*/
    public function artistProfile()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $name = $this->input->post('name', TRUE);
        $category_id = $this->input->post('category_id', TRUE);
        $description = $this->input->post('description', TRUE);
        $about_us = $this->input->post('about_us', TRUE);
        $qualification = $this->input->post('qualification', TRUE);
        $skills = $this->input->post('skills', TRUE);
        $job_done = $this->input->post('job_done', TRUE);
        $hire_rate = $this->input->post('hire_rate', TRUE);
        $bio = $this->input->post('bio', TRUE);
        $longitude = $this->input->post('longitude', TRUE);
        $latitude = $this->input->post('latitude', TRUE);
        $created_at = time();
        $updated_at = time();
        $address = $this->input->post('address', TRUE);

        $this->checkUserStatus($user_id);

        $data = array(
            'user_id' => $user_id,
            'name' => $name,
            'category_id' => $category_id,
            'description' => $description,
            'about_us' => $about_us,
            'qualification' => $qualification,
            'skills' => $skills,
            'job_done' => $job_done,
            'hire_rate' => $hire_rate,
            'bio' => $bio,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
            'address' => $address
        );
        $table = ART_TBL;
        $columnName = 'user_id';
        $condition = array('user_id' => $user_id);
        $checkArtist = $this->Api_model->checkData($table, $condition, $columnName);
        if ($checkArtist == 1) {
            $this->api->api_message(0, $this->translate('FOUND', $lan));
            $addArtist = $this->Api_model->insert($table, $data);
        } else {
            $this->api->api_message(0, $this->translate('USERNOTFOND', $lan));
        }
    }

    public function book_artist()
    {
        $lan = $this->getlanguage();
        $data['latitude'] = $this->input->post('latitude', TRUE);
        $data['longitude'] = $this->input->post('longitude', TRUE);
        $data['address'] = $this->input->post('address', TRUE);
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['artist_id'] = $this->input->post('artist_id', TRUE);
        $date_string = $this->input->post('date_string', TRUE);
        $data['time_zone'] = $this->input->post('timezone', TRUE);
        $data['booking_date'] = date('Y-m-d', strtotime($date_string));
        $data['booking_time'] = date('h:i a', strtotime($date_string));
        //date_default_timezone_set("Asia/Riyadh");
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $data['booking_timestamp'] = strtotime($date_string);
        $service_id = $this->input->post('service_id', TRUE);
        $data['detail'] = $this->input->post('detail', TRUE);

        if (isset($service_id)) {
            $services = json_decode($service_id);
            $price = 0;
            $description = "";
            foreach ($services as $services) {
                $service = $this->Api_model->getSingleRow('products', array('id' => $services));
                $price += $service->price;
                $description .= $service->product_name . " (" . $service->price . "), ";
            }
            $data['price'] = $price;
            $description = substr($description, 0, -2);
            $data['description'] = $description;
            $data['service_id'] = $service_id;
            $data['booking_type'] = 3;
        } else {
            $price = $this->input->post('price', TRUE);
            $getArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $data['artist_id']));
            $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
            $currency = $currency_setting->currency_symbol;
            if ($getArtist->artist_commission_type == 0) {
                $data['description'] = "You will paid hourly. " . $currency . " " . $price . " per hour.";
                $data['description_ar'] = "You will paid hourly. " . $currency . " " . $price . " per hour.";
            } else {
                $data['description'] = "You will paid " . $currency . " " . $price . " for this booking.";
                $data['description_ar'] = "You will paid " . $currency . " " . $price . " for this booking.";
            }
            $data['price'] = $price;
        }
        $this->checkUserStatus($data['user_id']);
        $checkArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $data['artist_id'], 'booking_flag' => 1));
        if ($checkArtist) {
            $this->api->api_message(0, $this->translate('ARTIST_BUSY_ANOTHER_CLIENT', $lan));
            exit();
        }

        $getArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $data['artist_id']));
        $category_id = $getArtist->category_id;
        $category = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $category_id));
        $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));
        $data['commission_type'] = $commission_setting->commission_type;
        $data['flat_type'] = $commission_setting->flat_type;
        if ($commission_setting->commission_type == 1) {
            if ($commission_setting->flat_type == 1) {
                $data['category_price'] = $commission_setting->flat_amount;
            }
        }

        $appId = $this->Api_model->insertGetId(ABK_TBL, $data);
        if ($appId) {
            $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $data['user_id']));
            $msg = $checkUser->name . ': ' . $this->translate('BOOKED_YOU_ON', $lan) . ' ' . $date_string;
            $this->firebase_notification($data['artist_id'], $this->translate('BOOK_APPOINTMENT', $lan), $msg, BOOK_ARTIST_NOTIFICATION);

            $dataNotification['user_id'] = $data['artist_id'];
            $dataNotification['title'] = $this->translate('BOOK_APPOINTMENT', $lan);
            $dataNotification['msg'] = $msg;
            $dataNotification['type'] = "Individual";
            $dataNotification['created_at'] = time();
            $this->Api_model->insertGetId(NTS_TBL, $dataNotification);

            //$updateUser=$this->Api_model->updateSingleRow(ART_TBL,array('user_id'=>$data['artist_id']),array('booking_flag'=>1));
            $this->api->api_message(1, $this->translate('BOOK_APP', $lan));
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    /*Decline Booking*/
    public function decline_booking()
    {
        $lan = $this->getlanguage();
        $booking_id = $this->input->post('booking_id', TRUE);
        $user_id = $this->input->post('user_id', TRUE);
        $data['decline_by'] = $this->input->post('decline_by', TRUE);
        $data['decline_reason'] = $this->input->post('decline_reason', TRUE);
        $data['booking_flag'] = 2;

        $this->checkUserStatus($user_id);

        $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('id' => $booking_id));
        if ($getBooking) {
            $updateBooking = $this->Api_model->updateSingleRow(ABK_TBL, array('id' => $booking_id), $data);

            if ($updateBooking) {
                if ($data['decline_by'] == 1) {
                    $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->artist_id));
                    $msg = $checkUser->name . ': ' . $this->translate('IS_DECLINE_YOUR_APPOINTMENT', $lan);
                    $this->firebase_notification($getBooking->user_id, $this->translate('DECLINE_APPOINTMENT', $lan), $msg, DECLINE_BOOKING_ARTIST_NOTIFICATION);
                } else {
                    $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->user_id));
                    $msg = $checkUser->name . ': ' . $this->translate('IS_DECLINE_YOUR_APPOINTMENT', $lan);
                    $this->firebase_notification($getBooking->artist_id, $this->translate('DECLINE_APPOINTMENT', $lan), $msg, DECLINE_BOOKING_ARTIST_NOTIFICATION);
                }

                $updateUser = $this->Api_model->updateSingleRow(ART_TBL, array('user_id' => $getBooking->artist_id), array('booking_flag' => 0));
                $this->api->api_message(1, $this->translate('BOOKING_DECLINE', $lan));
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }


    /*Case 1 accept booking 2 start booking 3 end booking*/
    public function booking_operation()
    {
        $lan = $this->getlanguage();
        $request = $this->input->post('request', TRUE);
        $booking_id = $this->input->post('booking_id', TRUE);
        $user_id = $this->input->post('user_id', TRUE);
        $this->checkUserStatus($user_id);
        switch ($request) {
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
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    /*Accept Booking*/
    public function accept_booking($booking_id)
    {
        $lan = $this->getlanguage();
        $data['booking_flag'] = 1;

        $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('id' => $booking_id));
        if ($getBooking) {
            $updateBooking = $this->Api_model->updateSingleRow(ABK_TBL, array('id' => $booking_id), $data);
            if ($updateBooking) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->artist_id));
                $msg = $checkUser->name . ': ' . $this->translate('HAS_ACCEPTED_YOUR_APPOINTMENT', $lan);
                $this->firebase_notification($getBooking->user_id, "Booking", $msg, ACCEPT_BOOKING_ARTIST_NOTIFICATION);
                $this->api->api_message(1, $this->translate('BOOKING_ACCEPTED', $lan));
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    /*Start Booking*/
    public function start_booking($booking_id)
    {
        $lan = $this->getlanguage();
        $data['booking_flag'] = 3;
        $data['start_time'] = time();

        $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('id' => $booking_id));
        if ($getBooking) {
            $checkArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $getBooking->artist_id, 'booking_flag' => 1));
            if ($getBooking->booking_type == 2) {
                if ($checkArtist) {
                    $this->api->api_message(0, $this->translate('ARTIST_BUSY_ANOTHER_CLIENT', $lan));
                    exit();
                }
            }

            $updateBooking = $this->Api_model->updateSingleRow(ABK_TBL, array('id' => $booking_id), $data);
            if ($updateBooking) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->artist_id));
                $msg = $this->translate('YOUR_BOOKING_STARTED_SUCCESSFULLY', $lan);
                $this->firebase_notification($getBooking->user_id, $this->translate('START_BOOKING', $lan), $msg, START_BOOKING_ARTIST_NOTIFICATION);
                $this->api->api_message(1, $this->translate('BOOKING_STARTED_SUCCESSFULLY', $lan));
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    public function deleteQualification()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $id = $this->input->post('qualification_id', TRUE);
        $this->Api_model->deleteRecord(array('id' => $id), 'qualifications');
        $this->api->api_message(1, $this->translate('QUALIFICATION_DELETE', $lan));
    }

    public function deleteProduct()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $id = $this->input->post('product_id', TRUE);
        $this->Api_model->deleteRecord(array('id' => $id), 'products');
        $this->api->api_message(1, $this->translate('PRODUCT_DELETE', $lan));
    }

    /*Update Qualification by artist*/
    public function updateQualification()
    {
        $lan = $this->getlanguage();
        $id = $this->input->post('qualification_id', TRUE);
        $data['title'] = $this->input->post('title', TRUE);
        $data['description'] = $this->input->post('description', TRUE);
        $get_qualifications = $this->Api_model->getSingleRow('qualifications', array('id' => $id));
        $updateUser = $this->Api_model->updateSingleRow('qualifications', array('id' => $id), $data);
        $this->api->api_message(1, $this->translate('QUALIFICATION_UPDATE', $lan));
    }

    public function deleteGallery()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $id = $this->input->post('id', TRUE);
        $this->Api_model->deleteRecord(array('id' => $id), 'gallery');
        $this->api->api_message(1, $this->translate('GALLERY_IMG_DELETE', $lan));
    }

    /*Complete Booking (End)*/
    public function end_booking($booking_id)
    {
        $lan = $this->getlanguage();
        $data['booking_flag'] = 4;
        $data['end_time'] = time();
        $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('id' => $booking_id));
        if ($getBooking) {
            if ($getBooking->booking_type == 2) {
                $this->Api_model->updateSingleRow(AJB_TBL, array('job_id' => $getBooking->job_id, 'artist_id' => $getBooking->artist_id), array('status' => 2));
            }
            if ($getBooking->booking_type == 1) {
                $this->Api_model->updateSingleRow(APP_TBL, array('id' => $getBooking->job_id), array('status' => 3));
            }

            $updateBooking = $this->Api_model->updateSingleRow(ABK_TBL, array('id' => $booking_id), $data);
            $artist_id = $getBooking->artist_id;
            $user_id = $getBooking->user_id;
            $updateUser = $this->Api_model->updateSingleRow(ART_TBL, array('user_id' => $artist_id), array('booking_flag' => 0));
            $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('id' => $booking_id));
            $working_min = (float)round(abs($getBooking->start_time - $getBooking->end_time) / 60, 2);
            $min_price = ($getBooking->price) / 60;
            $getArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $artist_id));
            if ($getArtist->artist_commission_type == 1 || $getBooking->booking_type == 2 || $getBooking->booking_type == 3) {
                $f_amount = $getBooking->price;
            } else {
                $f_amount = $working_min * $min_price;
            }
            $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));
            $datainvoice['commission_type'] = $commission_setting->commission_type;
            $datainvoice['flat_type'] = $commission_setting->flat_type;
            if ($commission_setting->commission_type == 0) {
                $total_amount = $f_amount;
                $datainvoice['category_amount'] = $getBooking->category_price;
            } elseif ($commission_setting->commission_type == 1) {
                if ($commission_setting->flat_type == 2) {
                    $total_amount = $f_amount;
                    $datainvoice['category_amount'] = round(($f_amount * $commission_setting->flat_amount) / 100, 2);
                } elseif ($commission_setting->flat_type == 1) {
                    $total_amount = $f_amount;
                    $datainvoice['category_amount'] = round(($f_amount * $commission_setting->flat_amount) / 100, 2);
                }
            }
            $datainvoice['artist_id'] = $artist_id;
            $artist_amount = round($f_amount, 2) - round(($f_amount * $commission_setting->flat_amount) / 100, 2);
            $datainvoice['artist_amount'] = $artist_amount;
            $getUserCode = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->user_id));
            $getArtistCode = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $artist_id));
            $datainvoice['invoice_id'] = strtoupper($this->api->strongToken());
            $datainvoice['total_amount'] = round($total_amount, 2);
            $datainvoice['final_amount'] = round($total_amount, 2);
            $datainvoice['user_id'] = $user_id;
            $datainvoice['booking_id'] = $booking_id;
            $datainvoice['working_min'] = (float)round($working_min, 2);
            $datainvoice['tax'] = 0;
            $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
            $datainvoice['currency_type'] = $currency_setting->currency_symbol;
            $date = date('Y-m-d');
            $datainvoice['created_at'] = time();
            $datainvoice['updated_at'] = time();
            $invoiceId = $this->Api_model->insertGetId(IVC_TBL, $datainvoice);
            $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->user_id));
            $getBooking->userName = $getUser->name;
            $getBooking->address = $getUser->address;
            $getBooking->total_amount = $total_amount;
            $getBooking->working_min = (float)$working_min;
            $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
            $getBooking->currency_type = $currency_setting->currency_symbol;
            if ($getUser->image) {
                $getBooking->userImage = base_url() . $getUser->image;
            } else {
                $getBooking->userImage = base_url() . 'assets/images/image.png';
            }
            $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->artist_id));
            $msg = 'Your booking end successfully.';
            $this->firebase_notification($getBooking->user_id, $this->translate('END_BOOKING', $lan), $msg, END_BOOKING_ARTIST_NOTIFICATION);
            $dataNotification['user_id'] = $getBooking->user_id;
            $dataNotification['title'] = "End Appointment";
            $dataNotification['msg'] = $msg;
            $dataNotification['type'] = "Individual";
            $dataNotification['created_at'] = time();
            $this->Api_model->insertGetId(NTS_TBL, $dataNotification);
            $this->api->api_message_data(1, $this->translate('BOOKING_END', $lan), 'data', $getBooking);
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    /*Check coupon code*/
    public function checkCoupon()
    {
        $lan = $this->getlanguage();
        $coupon_code = $this->input->post('coupon_code', TRUE);
        $user_id = $this->input->post('user_id', TRUE);
        $invoice_id = $this->input->post('invoice_id', TRUE);

        $this->checkUserStatus($user_id);

        $getCoupon = $this->Api_model->getSingleRow(DCP_TBL, array('coupon_code' => $coupon_code, 'status' => 1));
        if ($getCoupon) {
            $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('invoice_id' => $invoice_id));
            $total_amount = $getInvoice->total_amount;
            $discount = $getCoupon->discount;
            $discount_type = $getCoupon->discount_type;
            if ($discount_type == 1) {
                $precentage = ($total_amount * $discount) / 100;
                $final_amount = $total_amount - round($precentage, 2);
            } else {
                $final_amount = $total_amount - $discount;
            }
            if ($final_amount < 0) {
                $final_amount = 0;
                $discount = $total_amount;
            }
            $this->api->api_message_data_four(1, 'final_amount', $final_amount, $this->translate('APPLIED_SUCCESSFULLY', $lan), "discount_amount", $discount);
        } else {
            $this->api->api_message(0, $this->translate('COUPON_NOT_VLID', $lan));
        }
    }

    /*make payment*/
    public function makePayment()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $payment_type = $this->input->post('payment_type', TRUE);
        $coupon_code = $this->input->post('coupon_code', TRUE);
        $invoice_id = $this->input->post('invoice_id', TRUE);
        $final_amount = $this->input->post('final_amount', TRUE);
        $payment_status = $this->input->post('payment_status', TRUE);
        $payment_type = $this->input->post('payment_type', TRUE);
        $discount_amount = $this->input->post('discount_amount', TRUE);

        $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
        $currency_type = $currency_setting->currency_symbol;

        $this->checkUserStatus($user_id);
        $getCoupon = $this->Api_model->getSingleRow(DCP_TBL, array('coupon_code' => $coupon_code));
        $resStr = strtolower($coupon_code);
        if ($resStr == 'online' || $resStr == 'yaasa' && $payment_type == 1) {
            $this->api->api_message(0, "هذا الكوبن صالح لدفع الاونلاين فقط");
            exit();
        }

        if ($getCoupon) {
            if ($getCoupon) {
                if ($payment_status == 1) {
                    if (isset($payment_type)) {
                        $this->Api_model->updateSingleRow(IVC_TBL, array('invoice_id' => $invoice_id), array('final_amount' => $final_amount, 'coupon_code' => $coupon_code, 'flag' => 1, 'payment_status' => $payment_status, "payment_type" => $payment_type, "discount_amount" => $discount_amount));
                    } else {
                        $this->Api_model->updateSingleRow(IVC_TBL, array('invoice_id' => $invoice_id), array('final_amount' => $final_amount, 'coupon_code' => $coupon_code, 'flag' => 1, 'payment_status' => $payment_status, "discount_amount" => $discount_amount));
                    }

                    $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('invoice_id' => $invoice_id));
                    $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('id' => $getInvoice->booking_id));
                    $getInvoice->booking_time = $getBooking->booking_time;
                    $getInvoice->booking_date = $getBooking->booking_date;
                    $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->user_id));
                    $getInvoice->userName = $getUser->name;
                    $getInvoice->userEmail = $getUser->email_id;
                    $getInvoice->address = $getUser->address;
                    $get_artists = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $getInvoice->artist_id));
                    $getArt = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->artist_id));
                    $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));
                    $getInvoice->ArtistName = $get_artists->name;
                    $getInvoice->ArtistEmail = $getArt->email_id;
                    $getInvoice->ArtistLocation = $get_artists->location;
                    $getInvoice->categoryName = $get_cat->cat_name;
                    // $getInvoice->discount=$get_cat->cat_name;
                    $subject = IVE_SUB;
                    /*
              Here customer can use email template for sending email. We are using free services which will not work

            $this->send_invoice($getInvoice->userEmail, $subject, $getInvoice);
            $this->send_invoice($getInvoice->ArtistEmail, $subject, $getInvoice);
            */

                    $getCommission = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->artist_id));
                    if ($getCommission) {
                        if ($payment_type == 1) {
                            $amount = $getCommission->amount - $getInvoice->category_amount;
                            $amount_wallet = $getInvoice->category_amount;
                            $status = 1;
                            $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        } else {
                            $amount = $getCommission->amount + $getInvoice->artist_amount;
                            $amount_wallet = $getInvoice->artist_amount;
                            $status = 0;

                            $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        }
                        $data_send = array(
                            'invoice_id' => $getInvoice->invoice_id,
                            'user_id' => $getInvoice->artist_id,
                            'amount' => $amount_wallet,
                            'currency_type' => $currency_type,
                            'type' => 2,
                            'status' => $status,
                            'description' => "Booking invoice",
                            'created_at' => time(),
                            'order_id' => time()
                        );
                        $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                        $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->artist_id), array('amount' => $amount));
                    } else {
                        if ($payment_type == 1) {
                            $amount = -$getInvoice->category_amount;
                            $status = 1;

                            $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        } else {
                            $amount = $getInvoice->artist_amount;
                            $status = 0;

                            $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        }
                        $data_send = array(
                            'invoice_id' => $getInvoice->invoice_id,
                            'user_id' => $getInvoice->artist_id,
                            'amount' => $amount,
                            'currency_type' => $currency_type,
                            'type' => 2,
                            'status' => $status,
                            'description' => "Booking invoice",
                            'created_at' => time(),
                            'order_id' => time()
                        );
                        $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                        $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $getInvoice->artist_id, 'amount' => $amount));
                    }
                    if ($payment_type == 2) {
                        $getWallet = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->user_id));
                        if ($getWallet) {
                            $data_send = array(
                                'invoice_id' => $getInvoice->invoice_id,
                                'user_id' => $getInvoice->user_id,
                                'amount' => $final_amount,
                                'currency_type' => $currency_type,
                                'type' => 2,
                                'status' => 1,
                                'description' => "Booking invoice",
                                'created_at' => time(),
                                'order_id' => time()
                            );
                            $msg = $currency_type . ' ' . $final_amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->user_id, "Wallet", $msg, WALLET_NOTIFICATION);
                            $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                            $amount = $getWallet->amount - $final_amount;
                            $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->user_id), array('amount' => $amount));
                        }
                    }
                    $this->api->api_message(1, $this->translate('PAYMENT_CONFIRM', $lan));
                } else {
                    $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('invoice_id' => $invoice_id));
                    $getCommission = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->artist_id));
                    if ($getCommission) {
                        if ($payment_type == 1) {
                            $amount = $getCommission->amount - $getInvoice->category_amount;
                            $amount_wallet = $getInvoice->category_amount;
                            $status = 1;

                            $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        } else {
                            $amount = $getCommission->amount + $getInvoice->artist_amount;
                            $amount_wallet = $getInvoice->artist_amount;
                            $status = 0;

                            $msg = '$ ' . $amount_wallet . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        }
                        $data_send = array(
                            'invoice_id' => $getInvoice->invoice_id,
                            'user_id' => $getInvoice->artist_id,
                            'amount' => $amount_wallet,
                            'currency_type' => $currency_type,
                            'type' => 2,
                            'status' => $status,
                            'description' => "Booking invoice",
                            'created_at' => time(),
                            'order_id' => time()
                        );
                        $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                        $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->artist_id), array('amount' => $amount));
                    } else {
                        if ($payment_type == 1) {
                            $amount = -$getInvoice->category_amount;

                            $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        } else {
                            $amount = $getInvoice->artist_amount;
                            $status = 0;

                            $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        }
                        $data_send = array(
                            'invoice_id' => $getInvoice->invoice_id,
                            'user_id' => $getInvoice->artist_id,
                            'amount' => $amount,
                            'currency_type' => $currency_type,
                            'type' => 2,
                            'status' => $status,
                            'description' => "Booking invoice",
                            'created_at' => time(),
                            'order_id' => time()
                        );
                        $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                        $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $getInvoice->artist_id, 'amount' => $amount));
                    }
                    $this->Api_model->updateSingleRow(IVC_TBL, array('invoice_id' => $invoice_id), array('final_amount' => $final_amount, 'coupon_code' => $coupon_code, 'payment_status' => $payment_status, "discount_amount" => $discount_amount));
                    if ($payment_status == 3) {
                        if ($payment_type == 2) {
                            $getWallet = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->user_id));
                            if ($getWallet) {
                                $data_send = array(
                                    'invoice_id' => $getInvoice->invoice_id,
                                    'user_id' => $getInvoice->user_id,
                                    'amount' => $final_amount,
                                    'currency_type' => $currency_type,
                                    'type' => 2,
                                    'status' => 1,
                                    'description' => "Booking invoice",
                                    'created_at' => time(),
                                    'order_id' => time()
                                );

                                $msg = $currency_type . ' ' . $final_amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                                $this->firebase_notification($getInvoice->user_id, "Wallet", $msg, WALLET_NOTIFICATION);

                                $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                                $amount = $getWallet->amount - $final_amount;
                                $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->user_id), array('amount' => $amount));
                            }
                        }
                        $this->api->api_message(1, $this->translate('INITIATE_PAYMENT', $lan));
                    } else {
                        $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
                    }
                }
            } else {
                $this->api->api_message(0, $this->translate('COUPON_NOT_VLID', $lan));
            }
        } else {
            if ($payment_status == 1) {
                $this->Api_model->updateSingleRow(IVC_TBL, array('invoice_id' => $invoice_id), array('final_amount' => $final_amount, 'coupon_code' => $coupon_code, 'flag' => 1, 'payment_status' => $payment_status, 'payment_type' => $payment_type, "discount_amount" => $discount_amount));

                $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('invoice_id' => $invoice_id));
                $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('id' => $getInvoice->booking_id));
                $getInvoice->booking_time = $getBooking->booking_time;
                $getInvoice->booking_date = $getBooking->booking_date;
                $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->user_id));
                $getInvoice->userName = $getUser->name;
                $getInvoice->userEmail = $getUser->email_id;
                $getInvoice->address = $getUser->address;
                $get_artists = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $getInvoice->artist_id));
                $getArt = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->artist_id));
                $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));

                $getCommission = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->artist_id));
                if ($getCommission) {
                    if ($payment_type == 1) {
                        $amount = $getCommission->amount - $getInvoice->category_amount;
                        $amount_wallet = $getInvoice->category_amount;
                        $status = 1;

                        $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    } else {
                        $amount = $getCommission->amount + $getInvoice->artist_amount;
                        $amount_wallet = $getInvoice->artist_amount;
                        $status = 0;

                        $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    }
                    $data_send = array(
                        'invoice_id' => $getInvoice->invoice_id,
                        'user_id' => $getInvoice->artist_id,
                        'amount' => $amount_wallet,
                        'currency_type' => $currency_type,
                        'type' => 2,
                        'status' => $status,
                        'description' => "Booking invoice",
                        'created_at' => time(),
                        'order_id' => time()
                    );
                    $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                    $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->artist_id), array('amount' => $amount));
                } else {
                    if ($payment_type == 1) {
                        $amount = -$getInvoice->category_amount;
                        $status = 1;

                        $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    } else {
                        $amount = $getInvoice->artist_amount;
                        $status = 0;

                        $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    }
                    $data_send = array(
                        'invoice_id' => $getInvoice->invoice_id,
                        'user_id' => $getInvoice->artist_id,
                        'amount' => $amount,
                        'currency_type' => $currency_type,
                        'type' => 2,
                        'status' => $status,
                        'description' => "Booking invoice",
                        'created_at' => time(),
                        'order_id' => time()
                    );
                    $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                    $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $getInvoice->artist_id, 'amount' => $amount));
                }

                $getInvoice->ArtistName = $get_artists->name;
                $getInvoice->ArtistEmail = $getArt->email_id;
                $getInvoice->ArtistLocation = $get_artists->location;
                $getInvoice->categoryName = $get_cat->cat_name;
                $subject = IVE_SUB;
                $this->send_invoice($getInvoice->userEmail, $subject, $getInvoice);
                $this->send_invoice($getInvoice->ArtistEmail, $subject, $getInvoice);

                if ($payment_type == 2) {
                    $getWallet = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->user_id));
                    if ($getWallet) {
                        $data_send = array(
                            'invoice_id' => $getInvoice->invoice_id,
                            'user_id' => $getInvoice->user_id,
                            'amount' => $final_amount,
                            'currency_type' => $currency_type,
                            'type' => 2,
                            'status' => 1,
                            'description' => "Booking invoice",
                            'created_at' => time(),
                            'order_id' => time()
                        );

                        $msg = $currency_type . ' ' . $final_amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->user_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                        $amount = $getWallet->amount - $final_amount;
                        $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->user_id), array('amount' => $amount));
                    }
                }

                $this->api->api_message(1, $this->translate('PAYMENT_CONFIRM', $lan));
            } else {
                $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('invoice_id' => $invoice_id));
                $getCommission = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->artist_id));
                if ($getCommission) {
                    if ($payment_type == 1) {
                        $amount = $getCommission->amount - $getInvoice->category_amount;
                        $amount_wallet = $getInvoice->category_amount;
                        $status = 1;

                        $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    } else {
                        $amount = $getCommission->amount + $getInvoice->artist_amount;
                        $amount_wallet = $getInvoice->artist_amount;
                        $status = 0;

                        $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg);
                    }
                    $data_send = array(
                        'invoice_id' => $getInvoice->invoice_id,
                        'user_id' => $getInvoice->artist_id,
                        'amount' => $amount_wallet,
                        'currency_type' => $currency_type,
                        'type' => 2,
                        'status' => $status,
                        'description' => "Booking invoice",
                        'created_at' => time(),
                        'order_id' => time()
                    );
                    $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                    $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->artist_id), array('amount' => $amount));
                } else {
                    if ($payment_type == 1) {
                        $amount = -$getInvoice->category_amount;
                        $status = 1;

                        $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    } else {
                        $amount = $getInvoice->artist_amount;
                        $status = 0;

                        $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    }
                    $data_send = array(
                        'invoice_id' => $getInvoice->invoice_id,
                        'user_id' => $getInvoice->artist_id,
                        'amount' => $amount,
                        'currency_type' => $currency_type,
                        'type' => 2,
                        'status' => $status,
                        'description' => "Booking invoice",
                        'created_at' => time(),
                        'order_id' => time()
                    );
                    $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                    $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $getInvoice->artist_id, 'amount' => $amount));
                }
                $this->Api_model->updateSingleRow(IVC_TBL, array('invoice_id' => $invoice_id), array('final_amount' => $final_amount, 'coupon_code' => $coupon_code, 'payment_status' => $payment_status, "discount_amount" => $discount_amount));
                if ($payment_status == 3) {
                    if ($payment_type == 2) {
                        $getWallet = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->user_id));
                        if ($getWallet) {
                            $data_send = array(
                                'invoice_id' => $getInvoice->invoice_id,
                                'user_id' => $getInvoice->user_id,
                                'amount' => $final_amount,
                                'currency_type' => $currency_type,
                                'type' => 2,
                                'status' => 1,
                                'description' => "Booking invoice",
                                'created_at' => time(),
                                'order_id' => time()
                            );

                            $msg = $currency_type . ' ' . $final_amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->user_id, "Wallet", $msg, WALLET_NOTIFICATION);
                            $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                            $amount = $getWallet->amount - $final_amount;
                            $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->user_id), array('amount' => $amount));
                        }
                    }
                    $this->api->api_message(1, $this->translate('INITIATE_PAYMENT', $lan));
                } else {
                    $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
                }
            }
        }
    }

    /*Send Email Invoice*/
    public function send_invoice($email_id, $subject, $data)
    {

        $this->load->library('email');

        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.mail.us-east-1.awsapps.com',
            'smtp_port' => 465,
            'smtp_user' => 'mail@expmaint.com',
            'smtp_pass' => 'EXP@123maint',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1'
        );

        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        $this->email->from('mail@expmaint.com', APP_NAME);
        $this->email->to($email_id);
        $this->email->subject($subject);

        $datas['msg'] = $msg;

        $body = $this->load->view('invoice_tmp.php', $data, TRUE);
        $this->email->message($body);
        $this->email->send();
    }

    /*Get My last Booking*/
    public function updateMyServicePrice()
    {

        $artist_id = $this->input->post('artist_id', TRUE);
        $price = $this->input->post('price', TRUE);
        $getBooking = $this->Api_model->getWhereInStatus(ABK_TBL, array('artist_id' => $artist_id), 'booking_flag', array(1));
        $description = $getBooking->description;
        $description = str_replace($getBooking->price, $price, $description);
        $update = $this->Api_model->updateSingleRow(ABK_TBL, array('id' => $getBooking->id), array('description' => $description, 'price' => $price));
        $this->api->api_message(0, $description);
    }

    public function getMyCurrentBooking()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);

        $this->checkUserStatus($artist_id);
        $getBooking = $this->Api_model->getWhereInStatus(ABK_TBL, array('artist_id' => $artist_id), 'booking_flag', array(1));


        if (empty($getBooking)) {
            $getBooking = $this->Api_model->getWhereInStatus(ABK_TBL, array('artist_id' => $artist_id), 'booking_flag', array(3));
        }
        if ($getBooking) {
            $get_reviews = $this->Api_model->getAllDataWhere(array('artist_id' => $getBooking->artist_id, 'status' => 1), 'rating');
            $review = array();
            foreach ($get_reviews as $get_reviews) {
                $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_reviews->user_id));
                $get_reviews->name = $get_user->name;
                if ($get_user->image) {
                    $get_reviews->image = base_url() . $get_user->image;
                } else {
                    $get_reviews->image = base_url() . "assets/images/image.png";
                }

                array_push($review, $get_reviews);
            }
            $getBooking->reviews = $review;

            $where = array('user_id' => $getBooking->artist_id);
            $get_artists = $this->Api_model->getSingleRow(ART_TBL, $where);
            $getAdetails = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->artist_id));
            $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));
            if ($getAdetails->image) {
                $getBooking->artistImage = base_url() . $getAdetails->image;
            } else {
                $getBooking->artistImage = base_url() . "assets/images/image.png";
            }
            $getBooking->category_name = $get_cat->cat_name;
            $getBooking->artistName = $get_artists->name;
            $getBooking->artistLocation = $get_artists->location;

            $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->user_id));
            $getBooking->userName = $getUser->name;
            $phonecode = $getUser->phonecode;
            if (empty($phonecode)) {
                $phonecode = '966';
            }
            $getBooking->userMobile = $phonecode . $getUser->mobile;
            $getBooking->userEmail = $getUser->email_id;
            $getBooking->c_latitude = $getUser->latitude;
            $getBooking->c_longitude = $getUser->longitude;
            $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
            $getBooking->currency_type = $currency_setting->currency_symbol;

            $where = array('artist_id' => $artist_id, 'status' => 1);
            $ava_rating = $this->Api_model->getAvgWhere('rating', 'rating', $where);
            if ($ava_rating[0]->rating == null) {
                $ava_rating[0]->rating = "0";
            }
            $getBooking->ava_rating = round($ava_rating[0]->rating, 1);

            if ($getBooking->start_time) {
                $getBooking->working_min = (float)round(abs($getBooking->start_time - time()) / 60, 2);
            } else {
                $getBooking->working_min = 0;
            }

            if ($getUser->image) {
                $getBooking->userImage = base_url() . $getUser->image;
            } else {
                $getBooking->userImage = base_url() . 'assets/images/image.png';
            }
            $getBooking->description_ar = sprintf("قيمة هذا الطلب %s ر.س", $getBooking->price);
            $this->api->api_message_data(1, $this->translate('CURRENT_BOOKING', $lan), 'data', $getBooking);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    public function getAllBookingArtist()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $booking_flag = $this->input->post('booking_flag', TRUE);
        //0. Pending 1. accept 2.decline 3. in_process 4. completed//

        $this->checkUserStatus($artist_id);
        $getBooking = $this->Api_model->getAllDataWhere(array('artist_id' => $artist_id, 'booking_flag' => $booking_flag), ABK_TBL);
        if ($getBooking) {
            $getBookings = array();
            foreach ($getBooking as $getBooking) {
                $get_reviews = $this->Api_model->getAllDataWhere(array('artist_id' => $getBooking->artist_id, 'status' => 1), 'rating');
                $review = array();
                foreach ($get_reviews as $get_reviews) {
                    $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_reviews->user_id));
                    $get_reviews->name = $get_user->name;
                    if ($get_user->image) {
                        $get_reviews->image = base_url() . $get_user->image;
                    } else {
                        $get_reviews->image = base_url() . "assets/images/image.png";
                    }
                    array_push($review, $get_reviews);
                }
                $getBooking->reviews = $review;

                $where = array('user_id' => $getBooking->artist_id);
                $get_artists = $this->Api_model->getSingleRow(ART_TBL, $where);
                $getAdetails = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->artist_id));
                $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));
                if ($getAdetails->image) {
                    $getBooking->artistImage = base_url() . $getAdetails->image;
                } else {
                    $getBooking->artistImage = base_url() . "assets/images/image.png";
                }
                $getBooking->category_name = $get_cat->cat_name;
                $getBooking->artistName = $get_artists->name;
                $getBooking->artistLocation = $get_artists->location;
                $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->user_id));
                $getBooking->userName = $getUser->name;
                $getBooking->detail = $getBooking->detail;
                $getBooking->userMobile = $getUser->mobile;
                $getBooking->userEmail = $getUser->email_id;
                $getBooking->c_latitude = $getUser->latitude;
                $getBooking->c_longitude = $getUser->longitude;
                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $getBooking->currency_type = $currency_setting->currency_symbol;

                $where = array('artist_id' => $artist_id, 'status' => 1);
                $ava_rating = $this->Api_model->getAvgWhere('rating', 'rating', $where);
                if ($ava_rating[0]->rating == null) {
                    $ava_rating[0]->rating = "0";
                }
                $getBooking->ava_rating = round($ava_rating[0]->rating, 1);

                if ($getBooking->start_time) {
                    $getBooking->working_min = (float)round(abs($getBooking->start_time - time()) / 60, 2);
                } else {
                    $getBooking->working_min = 0;
                }
                if ($getUser->image) {
                    $getBooking->userImage = base_url() . $getUser->image;
                } else {
                    $getBooking->userImage = base_url() . 'assets/images/image.png';
                }
                $getBooking->description_ar = sprintf("قيمة هذا الطلب %s ر.س", $getBooking->price);


                array_push($getBookings, $getBooking);
            }

            $this->api->api_message_data(1, $this->translate('CURRENT_BOOKING', $lan), 'data', $getBookings);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*Get My last Booking*/
    public function getMyCurrentBookingUser()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $this->checkUserStatus($user_id);
        $getBooking = $this->Api_model->getWhereInStatusResult(ABK_TBL, array('user_id' => $user_id), 'booking_flag', array(0, 1, 3, 4));
        if ($getBooking) {
            $getBookings = array();
            foreach ($getBooking as $getBooking) {
                $get_reviews = $this->Api_model->getAllDataWhere(array('artist_id' => $getBooking->artist_id, 'status' => 1), 'rating');
                $review = array();
                foreach ($get_reviews as $get_reviews) {
                    $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_reviews->user_id));
                    $get_reviews->name = $get_user->name;
                    if ($get_user->image) {
                        $get_reviews->image = base_url() . $get_user->image;
                    } else {
                        $get_reviews->image = base_url() . "assets/images/image.png";
                    }
                    array_push($review, $get_reviews);
                }
                $getBooking->reviews = $review;

                $where = array('user_id' => $getBooking->artist_id);
                $get_artists = $this->Api_model->getSingleRow(ART_TBL, $where);
                $getAdetails = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->artist_id));
                $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));
                if ($getAdetails->image) {
                    $getBooking->artistImage = base_url() . $getAdetails->image;
                } else {
                    $getBooking->artistImage = base_url() . "assets/images/image.png";
                }

                $getBooking->category_name = $get_cat->cat_name;
                $getBooking->category_name_ar = $get_cat->cat_name_ar;
                $getBooking->artistName = $get_artists->name;
                $getBooking->artist_commission_type = $get_artists->artist_commission_type;
                $phonecode = $getAdetails->phonecode;
                if (empty($phonecode)) {
                    $phonecode = '966';
                }
                $getBooking->artistMobile = $phonecode . $getAdetails->mobile;
                $getBooking->artistEmail = $getAdetails->email_id;
                $getBooking->artistLocation = $get_artists->location;
                $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->user_id));
                $getBooking->userName = $getUser->name;
                $getBooking->address = $getUser->address;
                $getBooking->jobDone = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $getBooking->artist_id, 'booking_flag' => 4));
                $get_artists->totalJob = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $getBooking->artist_id));
                if ($get_artists->totalJob == 0) {
                    $getBooking->completePercentages = 0;
                } else {
                    $getBooking->completePercentages = round(($getBooking->jobDone * 100) / $get_artists->totalJob);
                }

                $where = array('artist_id' => $getBooking->user_id, 'status' => 1);
                $ava_rating = $this->Api_model->getAvgWhere('rating', 'rating', $where);
                if ($ava_rating[0]->rating == null) {
                    $ava_rating[0]->rating = "0";
                }
                $getBooking->ava_rating = round($ava_rating[0]->rating, 1);
                if ($getBooking->start_time) {
                    if ($getBooking->end_time) {
                        $getBooking->working_min = (int)round(abs($getBooking->start_time - $getBooking->end_time) / 60, 2);
                    } else {
                        $getBooking->working_min = (float)round(abs($getBooking->start_time - time()) / 60, 2);
                    }
                } else {
                    $getBooking->working_min = 0;
                }
                if ($getUser->image) {
                    $getBooking->userImage = base_url() . $getUser->image;
                } else {
                    $getBooking->userImage = base_url() . 'assets/images/image.png';
                }
                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $getBooking->currency_type = $currency_setting->currency_symbol;
                $booking_id = $getBooking->id;
                $getPrice = $this->Api_model->getSingleRow(IVC_TBL, array('booking_id' => $booking_id, 'flag' => 0));
                if ($getPrice) {
                    $getBooking->total_amount = $getPrice->total_amount;
                    $getBooking->invoice_id = $getPrice->invoice_id;
                    $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                    $getBooking->currency_type = $currency_setting->currency_symbol;
                } else {
                    $getBooking->total_amount = 0;
                    $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                    $getBooking->currency_type = $currency_setting->currency_symbol;
                    $getBooking->invoice_id = "";
                }

                $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('booking_id' => $getBooking->id));
                if ($getInvoice) {
                    $getInvoice->booking_date = $getBooking->booking_date;
                    $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->user_id));

                    $getInvoice->userName = $getUser->name;
                    $getInvoice->address = $getUser->address;

                    if ($getUser->image) {
                        $getInvoice->userImage = base_url() . $getUser->image;
                    } else {
                        $getInvoice->userImage = base_url() . 'assets/images/image.png';
                    }

                    $get_artists = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $getInvoice->artist_id));
                    $getAdetails = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->artist_id));
                    $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));
                    $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                    $getInvoice->currency_type = $currency_setting->currency_symbol;

                    $getInvoice->ArtistName = $get_artists->name;
                    $getInvoice->categoryName = $get_cat->cat_name;
                    $getInvoice->categoryName_ar = $get_cat->cat_name_ar;
                    if ($getAdetails->image) {
                        $getInvoice->artistImage = base_url() . $getAdetails->image;
                    } else {
                        $getInvoice->artistImage = base_url() . 'assets/images/image.png';
                    }
                    $getBooking->invoice = $getInvoice;
                }
                $getBooking->description_ar = sprintf("قيمة هذا الطلب %s ر.س", $getBooking->price);
                array_push($getBookings, $getBooking);
            }
            $this->api->api_message_data(1, $this->translate('CURRENT_BOOKING', $lan), 'data', $getBookings);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*get my earning*/
    public function myEarning()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id');
        $date = date('Y-m-d');
        $today = strtotime($date);

        $this->checkUserStatus($artist_id);

        $data = array();
        $todatDay = date('D', $today);

        $todayDay = $this->Api_model->getSumWhere('total_amount', IVC_TBL, array('artist_id' => $artist_id, 'created_at' => $today));
        if ($todayDay->total_amount == null) {
            $todayDay->total_amount = "0";
        }
        $todayDay->day = $todatDay;
        $data[] = $todayDay;

        $secondDay = date('Y-m-d', (strtotime('-1 day', strtotime($date))));
        $secondDayTime = strtotime($secondDay);
        $secondDayName = date('D', $secondDayTime);

        $second = $this->Api_model->getSumWhere('total_amount', IVC_TBL, array('artist_id' => $artist_id, 'created_at' => $secondDayTime));
        if ($second->total_amount == null) {
            $second->total_amount = "0";
        }
        $second->day = $secondDayName;
        $data[] = $second;

        $thirdDay = date('Y-m-d', (strtotime('-2 day', strtotime($date))));
        $thirdDayTime = strtotime($thirdDay);
        $thirdDayName = date('D', $thirdDayTime);

        $third = $this->Api_model->getSumWhere('total_amount', IVC_TBL, array('artist_id' => $artist_id, 'created_at' => $thirdDayTime));
        $third->day = $thirdDayName;
        if ($third->total_amount == null) {
            $third->total_amount = "0";
        }
        $data[] = $third;

        $fourDay = date('Y-m-d', (strtotime('-3 day', strtotime($date))));
        $fourDayTime = strtotime($fourDay);
        $fourDayName = date('D', $fourDayTime);

        $four = $this->Api_model->getSumWhere('total_amount', IVC_TBL, array('artist_id' => $artist_id, 'created_at' => $fourDayTime));
        if ($four->total_amount == null) {
            $four->total_amount = "0";
        }
        $four->day = $fourDayName;
        $data[] = $four;

        $fiveDay = date('Y-m-d', (strtotime('-4 day', strtotime($date))));
        $fiveDayTime = strtotime($fiveDay);
        $fiveDayName = date('D', $fiveDayTime);

        $five = $this->Api_model->getSumWhere('total_amount', IVC_TBL, array('artist_id' => $artist_id, 'created_at' => $fiveDayTime));
        if ($five->total_amount == null) {
            $five->total_amount = "0";
        }
        $five->day = $fiveDayName;
        $data[] = $five;

        $sixDay = date('Y-m-d', (strtotime('-5 day', strtotime($date))));
        $sixDayTime = strtotime($sixDay);
        $sixDayName = date('D', $sixDayTime);

        $six = $this->Api_model->getSumWhere('total_amount', IVC_TBL, array('artist_id' => $artist_id, 'created_at' => $sixDayTime));
        if ($six->total_amount == null) {
            $six->total_amount = "0";
        }
        $six->day = $sixDayName;
        $data[] = $six;

        $sevenDay = date('Y-m-d', (strtotime('-6 day', strtotime($date))));
        $sevenDayTime = strtotime($sevenDay);
        $sevenDayName = date('D', $sevenDayTime);

        $seven = $this->Api_model->getSumWhere('total_amount', IVC_TBL, array('artist_id' => $artist_id, 'created_at' => $sevenDayTime));
        if ($seven->total_amount == null) {
            $seven->total_amount = "0";
        }
        $seven->day = $sevenDayName;
        $data[] = $seven;

        $onlineEarning = $this->Api_model->getSumWhere('artist_amount', IVC_TBL, array('artist_id' => $artist_id, 'payment_type' => 0));
        $onlineCommission = $this->Api_model->getSumWhere('category_amount', IVC_TBL, array('artist_id' => $artist_id, 'payment_type' => 0));
        $offlineEarning = $this->Api_model->getSumWhere('artist_amount', IVC_TBL, array('artist_id' => $artist_id, 'payment_type' => 1));
        $offlineCommission = $this->Api_model->getSumWhere('category_amount', IVC_TBL, array('artist_id' => $artist_id, 'payment_type' => 1));
        $earning = $this->Api_model->getSumWhereIn('artist_amount', IVC_TBL, array('artist_id' => $artist_id), array(0, 1));
        $data['onlineEarning'] = round($onlineEarning->artist_amount, 2);
        $data['offlineEarning'] = round($offlineEarning->artist_amount, 2);
        $getCommission = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $artist_id));
        if ($getCommission) {
            $data['walletAmount'] = $getCommission->amount;
        } else {
            $data['walletAmount'] = $data['onlineEarning'] - $data['offlineEarning'];
        }

        $data['earning'] = round($earning->artist_amount, 2);
        $data['jobDone'] = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist_id, 'booking_flag' => 4));
        $data['totalJob'] = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist_id));
        $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
        $data['currency_symbol'] = $currency_setting->currency_symbol;
        if ($data['totalJob'] == 0) {
            $data['completePercentages'] = 0;
        } else {
            $data['completePercentages'] = round(($data['jobDone'] * 100) / $data['totalJob'], 2);
        }
        $this->api->api_message_data(1, $this->translate('GET_MY_EARNING', $lan), 'data', $data);
    }


    public function myEarning1()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id');
        $date = date('Y-m-d');
        $today = strtotime($date);

        $this->checkUserStatus($artist_id);

        $data = array();
        $todatDay = date('D', $today);

        $earningData = $this->Api_model->getMontlyRevenue1($artist_id);

        $data['chartData'] = $earningData;

        $onlineEarning = $this->Api_model->getSumWhere('artist_amount', IVC_TBL, array('artist_id' => $artist_id, 'payment_type' => 0));

        $onlineCommission = $this->Api_model->getSumWhere('category_amount', IVC_TBL, array('artist_id' => $artist_id, 'payment_type' => 0));

        $offlineEarning = $this->Api_model->getSumWhere('artist_amount', IVC_TBL, array('artist_id' => $artist_id, 'payment_type' => 1));

        $offlineCommission = $this->Api_model->getSumWhere('category_amount', IVC_TBL, array('artist_id' => $artist_id, 'payment_type' => 1));

        $earning = $this->Api_model->getSumWhere('artist_amount', IVC_TBL, array('artist_id' => $artist_id));

        $data['onlineEarning'] = round($onlineEarning->artist_amount, 2);
        $data['cashEarning'] = round($offlineEarning->artist_amount, 2);

        $getCommission = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $artist_id));

        if ($getCommission) {
            $data['walletAmount'] = $getCommission->amount;
        } else {
            $data['walletAmount'] = $data['onlineEarning'] - $data['offlineEarning'];
        }

        $data['totalEarning'] = round($earning->artist_amount, 2);

        $data['jobDone'] = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist_id, 'booking_flag' => 4));

        $data['totalJob'] = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist_id));

        $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
        $data['currency_symbol'] = $currency_setting->currency_symbol;

        if ($data['totalJob'] == 0) {
            $data['completePercentages'] = 0;
        } else {
            $data['completePercentages'] = round(($data['jobDone'] * 100) / $data['totalJob'], 2);
        }

        $this->api->api_message_data(1, $this->translate('GET_MY_EARNING', $lan), 'data', $data);
    }

    public function get_job_description($job_id)
    {
        return $this->db->get_where('post_job', array('job_id' => $job_id))->row()->description;
    }

    /*Get My Invoice*/
    public function getMyInvoice()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $role = $this->input->post('role', TRUE);

        $this->checkUserStatus($user_id);

        if ($role == 1) {
            $where = array('artist_id' => $user_id);
        } elseif ($role == 2) {
            $where = array('user_id' => $user_id);
        }

        $getInvoice = $this->Api_model->getAllDataWhereOrderTwo($where, IVC_TBL);
        if ($getInvoice) {
            $getInvoices = array();
            foreach ($getInvoice as $getInvoice) {
                $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('id' => $getInvoice->booking_id));

                $getInvoice->booking_date = $getBooking->booking_date;
                //$getInvoice->detail= $getBooking->detail;
                if (!empty($getBooking->detail)) {
                    if (!empty($getBooking->service_id)) {
                        $description = "";
                        $services = json_decode($getBooking->service_id);
                        if (!empty($services)) {
                            foreach ($services as $services) {
                                $service = $this->Api_model->getSingleRow('products', array('id' => $services));
                                //$price += $service->price;
                                $description .= $service->product_name . " (" . $service->price . "), ";
                            }
                        }
                    }
                    $getInvoice->detail = $getBooking->detail . $this->translate('FOR_SERVICE', $lan) . $description;
                } else {

                    if (!empty($getBooking->job_id)) {

                        $getInvoice->detail = $this->get_job_description($getBooking->job_id);
                    }

                }
                $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->user_id));

                $getInvoice->userName = $getUser->name;
                $getInvoice->address = $getUser->address;

                if ($getUser->image) {
                    $getInvoice->userImage = base_url() . $getUser->image;
                } else {
                    $getInvoice->userImage = base_url() . 'assets/images/image.png';
                }

                $get_artists = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $getInvoice->artist_id));
                $getAdetails = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->artist_id));
                $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));
                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $getInvoice->currency_type = $currency_setting->currency_symbol;

                $getInvoice->ArtistName = $get_artists->name;
                $getInvoice->categoryName = $get_cat->cat_name;
                $getInvoice->categoryNameAR = $get_cat->cat_name_ar;
                if ($getAdetails->image) {
                    $getInvoice->artistImage = base_url() . $getAdetails->image;
                } else {
                    $getInvoice->artistImage = base_url() . 'assets/images/image.png';
                }
                array_push($getInvoices, $getInvoice);
            }
            $this->api->api_message_data(1, $this->translate('MY_INVOICE', $lan), 'data', $getInvoices);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*Get My Invoice*/
    public function getMyHistory()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $this->checkUserStatus($artist_id);
        $where = array('artist_id' => $artist_id);

        $getInvoice = $this->Api_model->getAllDataWhereOrderTwo($where, IVC_TBL);
        if ($getInvoice) {
            $getInvoices = array();
            foreach ($getInvoice as $getInvoice) {
                $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('id' => $getInvoice->booking_id));

                $getInvoice->booking_time = $getBooking->booking_time;
                $getInvoice->booking_date = $getBooking->booking_date;

                $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->user_id));
                $getInvoice->userName = $getUser->name;
                $getInvoice->address = $getUser->address;
                if ($getUser->image) {
                    $getInvoice->userImage = base_url() . $getUser->image;
                } else {
                    $getInvoice->userImage = base_url() . 'assets/images/image.png';
                }

                $get_artists = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $getInvoice->artist_id));
                $getAdetails = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->artist_id));
                $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));
                $getInvoice->ArtistName = $get_artists->name;
                $getInvoice->categoryName = $get_cat->cat_name;

                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $getInvoice->currency_type = $currency_setting->currency_symbol;
                if ($getAdetails->image) {
                    $getInvoice->artistImage = base_url() . $getAdetails->image;
                } else {
                    $getInvoice->artistImage = base_url() . 'assets/images/image.png';
                }
                array_push($getInvoices, $getInvoice);
            }
            $this->api->api_message_data(1, $this->translate('MY_INVOICE', $lan), 'data', array_reverse($getInvoices));
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*Confirm Payment*/
    public function confirm_payment()
    {
        $lan = $this->getlanguage();
        $invoice_id = $this->input->post('invoice_id', TRUE);
        $booking_id = $this->input->post('booking_id', TRUE);

        $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('invoice_id' => $invoice_id, 'booking_id' => $booking_id));
        if ($getInvoice) {
            $updateUser = $this->Api_model->updateSingleRow(IVC_TBL, array('booking_id' => $booking_id), array('flag' => 1));

            $this->api->api_message(1, $this->translate('PAYMENT_CONFIRM', $lan));
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    public function strongID()
    {
        $id = $this->api->strongToken();
        echo $id;
    }

    /*Add to Cart*/
    public function addTocart()
    {
        $lan = $this->getlanguage();
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['product_id'] = $this->input->post('product_id', TRUE);
        $data['quantity'] = $this->input->post('quantity', TRUE);

        $this->checkUserStatus($data['user_id']);

        $data['created_at'] = time();
        $data['updated_at'] = time();
        $getId = $this->Api_model->insertGetId('product_basket', $data);

        if ($getId) {
            $this->api->api_message(1, $this->translate('PRODUCT_ADD_CART', $lan));
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*Get My Cart*/
    public function getMyCart()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);

        $this->checkUserStatus($user_id);

        $get_cart = $this->Api_model->getAllDataWhere(array('user_id' => $user_id), 'product_basket');
        if ($get_cart) {
            $get_carts = array();
            foreach ($get_cart as $get_cart) {
                $product_id = $get_cart->product_id;
                $product = $this->Api_model->getSingleRow('products', array('id' => $product_id));
                $quantity = $get_cart->quantity;
                $price = $product->price;

                $get_cart->product_name = $product->product_name;
                $get_cart->p_rate = $product->price;
                $get_cart->product_image = $this->config->base_url() . $product->product_image;
                $get_cart->product_total_price = $price * $quantity;
                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $get_cart->currency_type = $currency_setting->currency_symbol;
                array_push($get_carts, $get_cart);
            }
            $this->api->api_message_data(1, $this->translate('GET_MY_CART', $lan), 'my_cart', $get_carts);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*update Cart Quantity*/
    public function updateCartQuantity()
    {
        $lan = $this->getlanguage();
        $basket_id = $this->input->post('basket_id', TRUE);
        $quantity = $this->input->post('quantity', TRUE);
        $user_id = $this->input->post('user_id', TRUE);

        $this->checkUserStatus($user_id);

        $table = 'product_basket';
        $condition = array('id' => $basket_id, 'user_id' => $user_id);

        $check_basket = $this->Api_model->updateSingleRow('product_basket', array('id' => $basket_id), array('quantity' => $quantity));
        if ($check_basket) {
            $this->api->api_message(1, $this->translate('CART_UPDATE', $lan));
        } else {
            $this->api->api_message(0, $this->translate('NOT_RESPONDING', $lan));
        }
    }

    /*Remove Product from Cart*/
    public function remove_product_cart()
    {
        $lan = $this->getlanguage();
        $basket_id = $this->input->post('basket_id', TRUE);
        $user_id = $this->input->post('user_id', TRUE);

        $this->checkUserStatus($user_id);

        $this->Api_model->deleteRecord(array('id' => $basket_id, 'user_id' => $user_id), 'product_basket');
        $this->api->api_message(1, $this->translate('REMOVE_CART', $lan));
    }

    /*Get My referral Code*/
    public function getMyReferralCode()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);

        $this->checkUserStatus($user_id);

        $code = $this->Api_model->getSingleRowCloumn('referral_code', USR_TBL, array('user_id' => $user_id));
        if ($code) {
            $userCode['code'] = $code->referral_code;
            $userCode['description'] = $this->translate('COUPON_TEXT', $lan);

            $this->api->api_message_data(1, $this->translate('GET_MY_REFERRAL_CODE', $lan), 'my_referral_code', $userCode);
        } else {
            $this->api->api_message(0, $this->translate('NOT_RESPONDING', $lan));
        }
    }


    /*Send message (Chat)*/
    public function sendmsg()
    {
        $lan = $this->getlanguage();
        $image = $this->input->post('image', TRUE);
        $chat_type = $this->input->post('chat_type', TRUE);
        if (isset($chat_type)) {
            $data['chat_type'] = $this->input->post('chat_type', TRUE);
        } else {
            $data['chat_type'] = '1';
        }
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['artist_id'] = $this->input->post('artist_id', TRUE);
        $data['message'] = $this->input->post('message', TRUE);
        $data['send_by'] = $this->input->post('send_by', TRUE);
        $data['sender_name'] = $this->input->post('sender_name', TRUE);
        $data['date'] = time();
        $data['send_at'] = time();

        $this->load->library('upload');

        $config['image_library'] = 'gd2';
        $config['upload_path'] = './assets/images/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 10000;
        $config['file_name'] = time();
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 250;
        $config['height'] = 250;
        $this->upload->initialize($config);
        $updateduserimage = "";
        if ($this->upload->do_upload('image') && $this->load->library('image_lib', $config)) {
            $updateduserimage = 'assets/images/' . $this->upload->data('file_name');
        } else {
            //  echo $this->upload->display_errors();
        }

        if ($updateduserimage) {
            $data['image'] = $updateduserimage;
        }

        $chatId = $this->Api_model->insertGetId(CHT_TBL, $data);

        if ($chatId) {
            if ($data['send_by'] == $data['artist_id']) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $data['artist_id']));
                $msg = $checkUser->name . ':' . $data['message'];
                $this->firebase_notification($data['user_id'], "Chat", $msg, CHAT_NOTIFICATION);
            } elseif ($data['send_by'] == $data['user_id']) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $data['user_id']));
                $msg = $checkUser->name . ':' . $data['message'];

                $this->firebase_notification($data['artist_id'], "Chat", $msg, CHAT_NOTIFICATION);
            }
            $get_chat = $this->Api_model->getAllDataWhere(array('user_id' => $data['user_id'], 'artist_id' => $data['artist_id']), CHT_TBL);

            $get_chats = array();
            foreach ($get_chat as $get_chat) {
                if ($get_chat->chat_type == 2) {
                    $get_chat->image = base_url() . $get_chat->image;
                }

                array_push($get_chats, $get_chat);
            }

            $this->api->api_message_data(1, $this->translate('MSG_SENT_SUCCESS', $lan), 'my_chat', $get_chats);
        } else {
            $this->api->api_message(0, $this->translate('NOT_RESPONDING', $lan));
        }
    }

    /*get conversation*/
    public function getChat()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $artist_id = $this->input->post('artist_id', TRUE);

        $this->checkUserStatus($user_id);

        $get_chat = $this->Api_model->getAllDataWhere(array('user_id' => $user_id, 'artist_id' => $artist_id), CHT_TBL);
        if ($get_chat) {
            $get_chats = array();
            foreach ($get_chat as $get_chat) {
                if ($get_chat->chat_type == 2) {
                    $get_chat->image = base_url() . $get_chat->image;
                }

                array_push($get_chats, $get_chat);
            }

            $this->api->api_message_data(1, $this->translate('GET_CONVER', $lan), 'my_chat', $get_chats);
        } else {
            $this->api->api_message(0, $this->translate('NOT_CONVER', $lan));
        }
    }

    /*get conversation*/
    public function getChatHistoryForArtist()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);

        $this->checkUserStatus($artist_id);
        $where = array('artist_id' => $artist_id);
        $get_users = $this->Api_model->getAllDataWhereDistinct($where, CHT_TBL);
        if ($get_users) {
            $chats = array();
            foreach ($get_users as $get_users) {
                $chat = $this->Api_model->getSingleRowOrderBy(CHT_TBL, array('artist_id' => $artist_id, 'user_id' => $get_users->user_id));
                $user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_users->user_id));
                $chat->userName = $user->name;
                if ($user->image) {
                    $chat->userImage = base_url() . $user->image;
                } else {
                    $chat->userImage = base_url() . "assets/images/image.png";
                }

                array_push($chats, $chat);
            }

            if (count($chats) > 1) {
                array_multisort(array_column($chats, 'send_at'), SORT_DESC, $chats);
            }

            $this->api->api_message_data(1, $this->translate('GET_CAHT', $lan), 'my_chat', $chats);
        } else {
            $this->api->api_message(0, $this->translate('NOT_CONVER', $lan));
        }
    }

    /*get conversation*/
    public function getChatHistoryForUser()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $this->checkUserStatus($user_id);
        $where = array('user_id' => $user_id);
        $get_users = $this->Api_model->getAllDataWhereDistinctArtist($where, CHT_TBL);

        if ($get_users) {
            $chats = array();
            foreach ($get_users as $get_users) {
                $chat = $this->Api_model->getSingleRowOrderBy(CHT_TBL, array('artist_id' => $get_users->artist_id, 'user_id' => $user_id));
                $getAdetails = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_users->artist_id));
                $user = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $get_users->artist_id));
                $chat->artistName = $user->name;
                if ($getAdetails->image) {
                    $chat->artistImage = base_url() . $getAdetails->image;
                } else {
                    $chat->artistImage = base_url() . "assets/images/image.png";
                }
                array_push($chats, $chat);
            }

            if (count($chats) > 1) {
                array_multisort(array_column($chats, 'send_at'), SORT_DESC, $chats);
            }

            $this->api->api_message_data(1, $this->translate('GET_CAHT', $lan), 'my_chat', $chats);
        } else {
            $this->api->api_message(0, $this->translate('NOT_CONVER', $lan));
        }
    }

    public function firebase()
    {
        $lan = $this->getlanguage();
        $mobile = $this->input->post('mobile');
        $title = $this->input->post('title');
        $msg = $this->input->post('msg');
        $firebaseKey = $this->Api_model->getSingleRow('firebase_keys', array('id' => 1));


        for ($i = 0; $i < count($mobile); $i++) {
            $sendto = str_replace(' ', '', $mobile[$i]);

            if (filter_var($sendto, FILTER_VALIDATE_EMAIL)) {

                $user = $this->db->where('email', $sendto)->get('users')->row();
            } else {
                $user = $this->db->where('mobile', $sendto)->get('users')->row();
            }

            if ($user->role == 1) {
                $API_ACCESS_KEY = $firebaseKey->artist_key;
            } else {
                $API_ACCESS_KEY = $firebaseKey->customer_key;
            }

            $deviceToken = $user->device_token;
            $mobile_sent = $sendto;
            $title_sent = $title;
            $msg_sent = $msg;

            $ch = curl_init("https://fcm.googleapis.com/fcm/send");

            //Creating the notification array.
            $notification = array('title' => $title_sent, 'text' => $msg_sent);
            $msg = array
            (
                'body' => $msg_sent,
                'title' => $title_sent,
                'type' => $type,
                'icon' => 'myicon',
                'sound' => 'mySound'
            );
            //This array contains, the token and the notification. The 'to' attribute stores the token.
            $arrayToSend = array('to' => $deviceToken, 'notification' => $notification);
            //Generating JSON encoded string form the above array.
            $json = json_encode($arrayToSend);

            if ($user->device_type == "ios" || $user->device_type == "IOS") {
                $fields = array(
                    'to' => $deviceToken,
                    'notification' => $msg,
                    'priority' => 'high'
                );
            } else {
                $fields = array(
                    'to' => $deviceToken,
                    'data' => $msg
                );
            }
            $headers = array
            (
                'Authorization: key=' . $API_ACCESS_KEY,
                'Content-Type: application/json'
            );
            #Send Reponse To FireBase Server
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        }
        return $result;
    }

    /*Firebase for notification*/
    public function firebase_notification($user_id, $title, $msg1, $type)
    {
        $lan = $this->getlanguage();
        $get_data = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $user_id));

        if ($get_data->device_token) {
            $firebaseKey = $this->Api_model->getSingleRow('firebase_keys', array('id' => 1));
            if ($get_data->role == 1) {
                $API_ACCESS_KEY = $firebaseKey->artist_key;
            } else {
                $API_ACCESS_KEY = $firebaseKey->customer_key;
            }

            $registrationIds = $get_data->device_token;
            $msg = array
            (
                'body' => $msg1,
                'title' => $title,
                'type' => $type,
                'icon' => 'myicon',/*Default Icon*/
                'sound' => 'mySound'/*Default sound*/
            );
            if ($get_data->device_type == "ios" || $get_data->device_type == "IOS") {
                $fields = array(
                    'to' => $registrationIds,
                    'notification' => $msg
                );
            } else {
                $fields = array(
                    'to' => $registrationIds,
                    'data' => $msg
                );
            }
            $headers = array
            (
                'Authorization: key=' . $API_ACCESS_KEY,
                'Content-Type: application/json'
            );
            #Send Reponse To FireBase Server
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }

    public function firebase_notification_test()
    {
        $lan = $this->getlanguage();
        $get_data = $this->Api_model->getSingleRow(USR_TBL, array(
            'user_id' => 1
        ));

        if ($get_data->device_token) {
            $firebaseKey = $this->Api_model->getSingleRow('firebase_keys', array(
                'id' => 1
            ));
            if ($get_data->role == 1) {
                $API_ACCESS_KEY = $firebaseKey->artist_key;
            } else {
                $API_ACCESS_KEY = $firebaseKey->customer_key;
            }

            $registrationIds = $get_data->device_token;
            $msg = array(
                'body' => "esyfugsdfg",
                'title' => "sdufgdsf",
                'type' => "sdfgdg",
                'icon' => 'myicon',
                /*Default Icon*/
                'sound' => 'mySound'
                /*Default sound*/
            );
            if ($get_data->device_type == "ios" || $get_data->device_type == "IOS") {
                $fields = array(
                    'to' => $registrationIds,
                    'notification' => $msg
                );
            } else {
                $fields = array(
                    'to' => $registrationIds,
                    'data' => $msg
                );
            }

            $headers = array(
                'Authorization: key=' . $API_ACCESS_KEY,
                'Content-Type: application/json'
            );
            #Send Reponse To FireBase Server
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            print_r($result);
            curl_close($ch);
        }
    }

    /*Artist Logout*/
    public function artistLogout()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('artist_id', TRUE);

        $this->checkUserStatus($user_id);
        $update = $this->Api_model->updateSingleRow(ART_TBL, array('user_id' => $user_id), array('is_online' => 0));
        $this->api->api_message(1, $this->translate('LOGOUTARTIST', $lan));
    }

    /*Artist Location Update*/
    public function checkArtistProfile()
    {
        $lan = $this->getlanguage();
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $errors = array_values($this->form_validation->error_array());
            $this->api->api_message(0, $errors[0]);
        } else {
            $user_id = $this->input->post('user_id', TRUE);
            $this->checkUserStatus($user_id);
            $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $user_id));
            $checkArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $user_id));
            if ($checkArtist) {
                $is_profile = 1;
            } else {
                $is_profile = 0;
            }

            $this->api->api_message_data_four(1, 'is_profile', $is_profile, $this->translate('GET_MY_PROF_STATUS', $lan), "approval_status", $getUser->approval_status);
        }
    }

    /*Artist Location Update*/
    public function updateLocation()
    {
        $lan = $this->getlanguage();
        $this->form_validation->set_rules('longitude', 'longitude', 'trim|required');
        $this->form_validation->set_rules('latitude', 'latitude', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $errors = array_values($this->form_validation->error_array());
            //$this->response(["success" => false, "message" =>$errors[0]]);
            $this->api->api_message(0, $errors[0]);
        } else {
            $user_id = $this->input->post('user_id', TRUE);
            $role = $this->input->post('role', TRUE);
            $longitude = $this->input->post('longitude', TRUE);
            $latitude = $this->input->post('latitude', TRUE);

            $this->checkUserStatus($user_id);
            if ($role == 1) {
                $table = ART_TBL;
            } elseif ($role == 2) {
                $table = USR_TBL;

                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $user_id));
                if (!$checkUser->latitude) {
                    $update = $this->Api_model->updateSingleRow($table, array('user_id' => $user_id), array('latitude' => $latitude, 'longitude' => $longitude));
                }
            }

            $update = $this->Api_model->updateSingleRow($table, array('user_id' => $user_id), array('live_lat' => $latitude, 'live_long' => $longitude));
            $this->api->api_message(1, $this->translate('LOCATION_UPDT', $lan));
        }
    }

    /*Added By Varun*/
    function post_job_new()
    {
        $lan = $this->getlanguage();
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $price = $this->input->post('price', TRUE);
        $data['title'] = $this->input->post('title', TRUE);
        $data['description'] = $this->input->post('description', TRUE);
        $data['category_id'] = $this->input->post('category_id', TRUE);
        $data['address'] = $this->input->post('address', TRUE);
        $data['lati'] = $this->input->post('lati', TRUE);
        $data['longi'] = $this->input->post('longi', TRUE);
        $avtar = $this->input->post('avtar', TRUE);
        $date_string = $this->input->post('job_date', TRUE);
        $data['job_date'] = date('Y-m-d', strtotime($date_string));
        $data['time'] = date('h:i a', strtotime($date_string));
        $data['job_timestamp'] = strtotime($date_string);
        $this->checkUserStatus($data['user_id']);

        if (isset($price)) {
            $data['price'] = $price;
        }

        $job_id = $this->api->random_string('alnum', 6);
        $data['job_id'] = strtoupper($job_id);
        $table = 'post_job';

        $this->load->library('upload');

        $config['image_library'] = 'gd2';
        $config['upload_path'] = './assets/images/';
        $config['allowed_types'] = '*';
        $config['max_size'] = 100000;
        $config['file_name'] = time();
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 250;
        $config['height'] = 250;
        $this->upload->initialize($config);
        $ProfileImage = "";
        if ($this->upload->do_upload('avtar') && $this->load->library('image_lib', $config)) {
            $ProfileImage = 'assets/images/' . $this->upload->data('file_name');
        }

        if ($ProfileImage) {
            $data['avtar'] = $ProfileImage;
        }
        $getArtists = $this->Api_model->getAllDataWhere(array('category_id' => $data['category_id']), ART_TBL);
        foreach ($getArtists as $getArtists) {
            $msg = $this->translate('NEW_JOB_AVAILABLE', $lan);
            $this->firebase_notification($getArtists->user_id, "Booking", $msg, JOB_NOTIFICATION);
        }

        $user_id = $this->Api_model->insertGetId($table, $data);
        if ($user_id) {
            $this->api->api_message(1, $this->translate('JOB_ADD', $lan));
        } else {
            $this->api->api_message(0, $this->translate('NOT_ADDED', $lan));
        }
    }

    function edit_post_job()
    {
        $lan = $this->getlanguage();
        $job_id = $this->input->post('job_id', TRUE);
        $title = $this->input->post('title', TRUE);
        $price = $this->input->post('price', TRUE);
        $description = $this->input->post('description', TRUE);
        $category_id = $this->input->post('category_id', TRUE);
        $address = $this->input->post('address', TRUE);
        $lati = $this->input->post('lati', TRUE);
        $longi = $this->input->post('longi', TRUE);
        $avtar = $this->input->post('avtar', TRUE);

        $table = 'post_job';
        $condition = array('job_id' => $job_id);

        if ($job_id != '' || $job_id != NULL) {

            $check_job = $this->Api_model->getSingleRow($table, $condition);
            if ($check_job) {
                $this->load->library('upload');
                $config['image_library'] = 'gd2';
                $config['upload_path'] = './assets/images/';
                $config['allowed_types'] = '*';
                $config['max_size'] = 100000;
                $config['file_name'] = time();
                $config['create_thumb'] = TRUE;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 250;
                $config['height'] = 250;
                $this->upload->initialize($config);
                $profileimage = "";
                if ($this->upload->do_upload('avtar')) {
                    $profileimage = 'assets/images/' . $this->upload->data('file_name');
                } else {

                }
                if ($profileimage) {
                    $data['avtar'] = $profileimage;
                }

                $data['title'] = isset($title) ? $title : $check_job->title;
                $data['price'] = isset($price) ? $price : $check_job->price;
                $data['description'] = isset($description) ? $description : $check_job->description;
                $data['category_id'] = isset($category_id) ? $category_id : $check_job->category_id;
                $data['address'] = isset($address) ? $address : $check_job->address;
                $data['lati'] = isset($lati) ? $lati : $check_job->lati;
                $data['longi'] = isset($longi) ? $longi : $check_job->longi;
                $data['updated_at'] = date('Y-m-d H:i:s');

                $this->Api_model->updateSingleRow($table, array('job_id' => $job_id), $data);

                $getappliedArtist = $this->Api_model->getAllDataWhere(array('job_id' => $job_id), AJB_TBL);
                if ($getappliedArtist) {
                    foreach ($getappliedArtist as $getappliedArtist) {
                        $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getappliedArtist->artist_id));
                        $msg = $job_id . ': has updated. Please check the changes.';
                        $this->firebase_notification($checkUser->user_id, "Booking", $msg, JOB_NOTIFICATION);
                    }
                }
                $this->api->api_message(1, $this->translate('JOB_UPDT', $lan));
            } else {
                $this->api->api_message(0, $this->translate('USER_NOT_REGISTER', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('JOB_ID_NOT_AVAILABLE', $lan));
        }
    }

    public function get_all_job()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $getArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $artist_id));
        $category_id = $getArtist->category_id;

        $get_jobs = $this->Api_model->getAllJobNotAppliedByArtist($artist_id, $category_id);

        if (empty($get_jobs)) {
            $this->api->api_message(0, $this->translate('NO_JOB_AVAILABLE', $lan));
        } else {

            $job_list = array();
            foreach ($get_jobs as $get_jobs) {
                $get_jobs->avtar = base_url() . $get_jobs->avtar;
                $table = 'user';
                $condition = array('user_id' => $get_jobs->user_id);
                $user = $this->Api_model->getSingleRow($table, $condition);
                $user->image = base_url() . $user->image;

                $table = 'category';
                $condition = array('id' => $get_jobs->category_id);
                $cate = $this->Api_model->getSingleRow($table, $condition);
                $get_jobs->category_name = $cate->cat_name;
                $get_jobs->category_name_ar = $cate->cat_name_ar;
                $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));

                $get_jobs->commission_type = $commission_setting->commission_type;
                $get_jobs->flat_type = $commission_setting->flat_type;
                if ($commission_setting->commission_type == 0) {
                    $get_jobs->category_price = $cate->price;
                } elseif ($commission_setting->commission_type == 1) {
                    if ($commission_setting->flat_type == 2) {
                        $get_jobs->category_price = $commission_setting->flat_amount;
                    } elseif ($commission_setting->flat_type == 1) {
                        $get_jobs->category_price = $commission_setting->flat_amount;
                    }
                }

                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $get_jobs->currency_symbol = $currency_setting->currency_symbol;

                $get_jobs->user_image = $user->image;
                $get_jobs->user_name = $user->name;
                $get_jobs->user_address = $user->address;
                $get_jobs->user_email = $user->email_id;
                $get_jobs->user_mobile = $user->mobile;

                array_push($job_list, $get_jobs);
            }
            $this->api->api_message_data(1, $this->translate('ALL_JOBS_FOUND', $lan), 'data', $job_list);
        }
    }

    public function get_all_job_user()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $get_jobs = $this->Api_model->getAllDataWhereoderByJob(array('user_id' => $user_id, 'status !=' => '4'), 'post_job');
        if ($get_jobs) {
            $job_list = array();
            foreach ($get_jobs as $get_jobs) {
                $get_jobs->avtar = base_url() . $get_jobs->avtar;

                $job = $this->Api_model->getWhereInStatusResultJob('applied_job', array('job_id' => $get_jobs->job_id), 'status', array(0, 1, 5));

                if ($job) {
                    $get_jobs->is_edit = 0;
                } else {
                    $get_jobs->is_edit = 1;
                }

                $table = 'category';
                $condition = array('id' => $get_jobs->category_id);
                $cate = $this->Api_model->getSingleRow($table, $condition);
                $get_jobs->category_name = $cate->cat_name;
                $get_jobs->category_name_ar = $cate->cat_name_ar;
                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $get_no_job = $this->Api_model->getCount('applied_job', array('user_id' => $user_id, 'job_id' => $get_jobs->job_id));
                $get_jobs->applied_job = $get_no_job;
                $get_jobs->currency_symbol = $currency_setting->currency_symbol;
                $get_jobs->category_price = $cate->price;
                array_push($job_list, $get_jobs);
            }
            $this->api->api_message_data(1, $this->translate('ALL_JOBS_FOUND', $lan), 'data', $job_list);
        } else {
            $this->api->api_message(0, $this->translate('NO_JOBS_AVAILABLE', $lan));
        }
    }

    public function applied_job()
    {
        $lan = $this->getlanguage();
        $price = $this->input->post('price', TRUE);
        $user_id = $this->input->post('user_id', TRUE);
        $artist_id = $this->input->post('artist_id', TRUE);
        $job_id = $this->input->post('job_id', TRUE);
        $description = $this->input->post('description', TRUE);

        $table = 'applied_job';
        if (!$check = $this->Api_model->check_applied_job($artist_id, $job_id)) {
            $checkArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $artist_id));
            $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $artist_id));
            if (!$checkArtist) {
                $this->api->api_message(0, $this->translate('PLZ_UP_PRF', $lan));
                exit();
            }
            if ($checkUser->approval_status != 1) {
                $this->api->api_message(0, $this->translate('UNDER_REVIEW', $lan));
                exit();
            }

            if (isset($price)) {
                $data['price'] = $price;
            }
            $data['user_id'] = $user_id;
            $data['artist_id'] = $artist_id;
            $data['job_id'] = $job_id;
            $data['description'] = $description;

            $id = $this->Api_model->insertGetId($table, $data);
            if ($id) {
                $msg = 'Hey, ' . $checkUser->name . ' applied on your job.';
                $this->firebase_notification($user_id, "Job", $msg, JOB_APPLY_NOTIFICATION);

                $this->api->api_message(1, $this->translate('JOB_APPLIED_SUCCESSFULLY', $lan));
            } else {
                $this->api->api_message(0, $this->translate('FAILED', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('ALLREADY_APPLIED', $lan));
        }
    }

    public function get_applied_job_by_id()
    {
        $lan = $this->getlanguage();
        $job_id = $this->input->post('job_id', TRUE);
        $get_jobs = $this->Api_model->getAllDataWhereoderBy(array('job_id' => $job_id), 'applied_job');

        if (empty($get_jobs)) {
            $this->api->api_message(0, $this->translate('NO_JOBS_AVAILABLE', $lan));
        } else {

            $job_list = array();
            foreach ($get_jobs as $get_jobs) {
                $table = 'user';
                $condition = array('user_id' => $get_jobs->artist_id);
                $user = $this->Api_model->getSingleRow("artist", $condition);
                $artist = $this->Api_model->getSingleRow($table, $condition);

                $condition_post_job = array('job_id' => $job_id);
                $post_jobs_get = $this->Api_model->getSingleRow("post_job", $condition_post_job);
                $get_jobs->job_date = $post_jobs_get->job_date;
                $get_jobs->time = $post_jobs_get->time;
                $get_jobs->job_timestamp = $post_jobs_get->job_timestamp;


                if ($artist->image) {
                    $get_jobs->artist_image = base_url() . $artist->image;
                } else {
                    $get_jobs->artist_image = base_url() . "assets/images/image.png";
                }

                $get_jobs->artist_name = $user->name;

                $table = 'category';
                $condition = array('id' => $user->category_id);
                $cate = $this->Api_model->getSingleRow($table, $condition);
                $get_jobs->category_name = $cate->cat_name;
                $get_jobs->category_name_ar = $cate->cat_name_ar;
                $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));
                $get_jobs->commission_type = $commission_setting->commission_type;
                $get_jobs->flat_type = $commission_setting->flat_type;
                if ($commission_setting->commission_type == 0) {
                    $get_jobs->category_price = $cate->price;
                } elseif ($commission_setting->commission_type == 1) {
                    if ($commission_setting->flat_type == 2) {
                        $get_jobs->category_price = $commission_setting->flat_amount;
                    } elseif ($commission_setting->flat_type == 1) {
                        $get_jobs->category_price = $commission_setting->flat_amount;
                    }
                }

                $table1 = 'user';
                $condition = array('user_id' => $get_jobs->artist_id);
                $artist = $this->Api_model->getSingleRow($table1, $condition);
                $getArtist = $this->Api_model->getSingleRow("artist", $condition);

                $get_jobs->artist_address = $getArtist->location;
                $get_jobs->artist_mobile = $artist->mobile;
                $get_jobs->artist_email = $artist->email_id;

                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $get_jobs->currency_symbol = $currency_setting->currency_symbol;
                $ava_rating = $this->Api_model->getAvgWhere('rating', 'rating', array('artist_id' => $get_jobs->artist_id));
                if ($ava_rating[0]->rating == null) {
                    $ava_rating[0]->rating = "0";
                }
                $get_jobs->ava_rating = round($ava_rating[0]->rating, 2);
                array_push($job_list, $get_jobs);
            }
            $this->api->api_message_data(1, $this->translate('ALL_JOBS_FOUND', $lan), 'data', $job_list);
        }
    }

    public function job_status_user()
    {
        $lan = $this->getlanguage();
        $aj_id = $this->input->post('aj_id', TRUE);
        $status = $this->input->post('status', TRUE);
        $job_id = $this->input->post('job_id', TRUE);

        $table = 'applied_job';
        $condition = array('aj_id' => $aj_id);

        $job = $this->Api_model->getSingleRow($table, $condition);

        if ($status == '1') {
            $update = $this->Api_model->updateSingleRow('applied_job', array('aj_id' => $aj_id), array('status' => $status));

            $this->api->api_message(1, $this->translate('JOB_CONFIRM_SUCCESSFULLY', $lan));
            $this->firebase_notification($job->artist_id, 'Job Status', $this->translate('YOUR_REQUEST_IS_CONFIRM', $lan), JOB_NOTIFICATION);
        } elseif ($status == '2') {
            if ($job->status == '1') {
                $update = $this->Api_model->updateSingleRow('applied_job', array('aj_id' => $aj_id), array('status' => $status));
                $update1 = $this->Api_model->updateJob('applied_job', array('aj_id !=' => $aj_id, 'job_id' => $job_id), '3');
                $this->Api_model->updateSingleRow('post_job', array('job_id' => $job->job_id), array('status' => $status));
                $this->api->api_message(1, $this->translate('JOB_COMPLETE_SUCCESSFULLY', $lan));
                $this->firebase_notification($job->artist_id, 'Job Status', 'Job completed.', JOB_NOTIFICATION);
            } else {
                $this->api->api_message(0, $this->translate('CONFIRM_JOB', $lan));
            }
        } elseif ($status == '3') {
            if ($job->status != '2') {
                $update = $this->Api_model->updateSingleRow('applied_job', array('aj_id' => $aj_id), array('status' => $status));
                $this->api->api_message(1, $this->translate('JOB_REJECTED', $lan));
                $this->firebase_notification($job->artist_id, 'Job Status', 'Job Rejected.', JOB_NOTIFICATION);
            } else {
                $this->api->api_message(0, $this->translate('FAILED', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('FAILED', $lan));
        }
    }

    public function get_applied_job_artist()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $get_jobs = $this->Api_model->getAllDataWhereoderBy(array('artist_id' => $artist_id, 'status !=' => '4'), 'applied_job');
        if (empty($get_jobs)) {
            $this->api->api_message(0, $this->translate('NO_JOBS_AVAILABLE', $lan));
        } else {
            $job_list = array();
            foreach ($get_jobs as $get_jobs) {

                $table = 'user';
                $condition = array('user_id' => $get_jobs->user_id);
                $user = $this->Api_model->getSingleRow($table, $condition);
                $job = $this->Api_model->getSingleRow('post_job', array('job_id' => $get_jobs->job_id));
                $cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $job->category_id));
                $user->image = base_url() . $user->image;
                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $get_jobs->currency_symbol = $currency_setting->currency_symbol;
                $get_jobs->category_name = $cat->cat_name;
                $get_jobs->category_name_ar = $get_cat->cat_name_ar;
                $get_jobs->user_image = $user->image;
                $get_jobs->user_name = $user->name;
                $get_jobs->user_address = $job->address;
                $get_jobs->title = $job->title;
                $get_jobs->job_date = $job->job_date;
                $get_jobs->time = $job->time;
                $get_jobs->job_timestamp = $job->job_timestamp;
                $get_jobs->user_mobile = $user->mobile;
                $get_jobs->user_email = $user->email_id;

                array_push($job_list, $get_jobs);
            }
            $this->api->api_message_data(1, $this->translate('APPLIED_JOBS_FOUND', $lan), 'data', $job_list);
        }
    }

    public function job_status_artist()
    {
        $lan = $this->getlanguage();
        $aj_id = $this->input->post('aj_id', TRUE);
        $status = $this->input->post('status', TRUE);

        $table = 'applied_job';
        $condition = array('aj_id' => $aj_id);

        $job = $this->Api_model->getSingleRow($table, $condition);

        if ($status == '3') {
            if ($job->status != '2') {
                $update = $this->Api_model->updateSingleRow('applied_job', array('aj_id' => $aj_id), array('status' => $status));
                $this->Api_model->updateSingleRow('post_job', array('job_id' => $job->job_id), array('status' => '0'));
                $this->api->api_message(1, $this->translate('JOB_REJECTED', $lan));
                $this->firebase_notification($job->artist_id, 'Job Status', 'Job Rejected.', JOB_NOTIFICATION);
            } else {
                $this->api->api_message(0, $this->translate('FAILED', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('FAILED', $lan));
        }
    }

    public function deletejob()
    {
        $lan = $this->getlanguage();
        $job_id = $this->input->post('job_id', TRUE);
        $status = $this->input->post('status', TRUE);

        $job = $this->Api_model->getWhereInStatusResultJob('applied_job', array('job_id' => $job_id), 'status', array(5));
        if (empty($job)) {

            $getappliedArtist = $this->Api_model->getAllDataWhere(array('job_id' => $job_id), AJB_TBL);
            if ($getappliedArtist) {

                $this->Api_model->updateWhereIn(array('job_id' => $job_id), array(0, 1), 'applied_job', array('status' => 4));

                $this->Api_model->updateSingleRow('post_job', array('job_id' => $job_id), array('status' => $status));

                foreach ($getappliedArtist as $getappliedArtist) {
                    $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getappliedArtist->artist_id));
                    $msg = $job_id . ': has deleted by user.';
                    $this->firebase_notification($checkUser->user_id, "Booking", $msg, DELETE_JOB_NOTIFICATION);
                }
            }

            $this->Api_model->updateSingleRow('post_job', array('job_id' => $job_id), array('status' => $status));
            $this->api->api_message(1, $this->translate('JOB_DELETE', $lan));
        } else {
            $this->api->api_message(0, $this->translate('CURRENT_ARTIST_WORK', $lan));
        }
    }

    /*Case 1 accept booking 2 start booking 3 end booking*/
    public function appointment_operation()
    {
        $lan = $this->getlanguage();
        $request = $this->input->post('request', TRUE);
        $appointment_id = $this->input->post('appointment_id', TRUE);
        $user_id = $this->input->post('user_id', TRUE);

        $this->checkUserStatus($user_id);

        switch ($request) {
            case 1:
                $this->accept_appointment($appointment_id);
                break;

            case 2:
                $this->reject_appointment($appointment_id);
                break;

            case 3:
                $this->complete_appointment($appointment_id);
                break;
            case 4:
                $this->decline_appointment($appointment_id);
                break;
            default:
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    /*Accept Booking*/
    public function accept_appointment($appointment_id)
    {
        $lan = $this->getlanguage();
        $data['status'] = 1;

        $getBooking = $this->Api_model->getSingleRow(APP_TBL, array('id' => $appointment_id));
        if ($getBooking) {
            $updateBooking = $this->Api_model->updateSingleRow(APP_TBL, array('id' => $appointment_id), $data);

            if ($updateBooking) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->artist_id));
                $msg = $checkUser->name . ': has accepted your appointment.';
                $this->firebase_notification($getBooking->user_id, "Appointment", $msg, ACCEPT_BOOKING_ARTIST_NOTIFICATION);

                $this->api->api_message(1, $this->translate('APPOINTMENT_ACCEPTED_SUCCESSFULLY', $lan));
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    /*Reject Booking*/
    public function reject_appointment($appointment_id)
    {
        $lan = $this->getlanguage();
        $data['status'] = 2;

        $getBooking = $this->Api_model->getSingleRow(APP_TBL, array('id' => $appointment_id));
        if ($getBooking) {
            $updateBooking = $this->Api_model->updateSingleRow(APP_TBL, array('id' => $appointment_id), $data);

            if ($updateBooking) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->artist_id));
                $msg = $checkUser->name . ': ' . $this->translate('HAS_REJECTED_YOUR_APPOINTMENT', $lan);
                $this->firebase_notification($getBooking->user_id, "Appointment", $msg, DECLINE_BOOKING_ARTIST_NOTIFICATION);

                $this->api->api_message(1, $this->translate('APPOINTMENT_REJECT', $lan));
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    /*Reject Booking*/
    public function complete_appointment($appointment_id)
    {
        $lan = $this->getlanguage();
        $data['status'] = 3;

        $getBooking = $this->Api_model->getSingleRow(APP_TBL, array('id' => $appointment_id));
        if ($getBooking) {
            $updateBooking = $this->Api_model->updateSingleRow(APP_TBL, array('id' => $appointment_id), $data);
            if ($updateBooking) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->artist_id));
                $msg = $checkUser->name . ': has completed your appointment.';
                $this->firebase_notification($getBooking->user_id, "Appointment", $msg, END_BOOKING_ARTIST_NOTIFICATION);

                $this->api->api_message(1, $this->translate('APPOINTMENT_COMPLET', $lan));
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }


    /*Reject Booking*/
    public function decline_appointment($appointment_id)
    {
        $lan = $this->getlanguage();
        $data['status'] = 4;

        $getBooking = $this->Api_model->getSingleRow(APP_TBL, array('id' => $appointment_id));
        if ($getBooking) {
            $updateBooking = $this->Api_model->updateSingleRow(APP_TBL, array('id' => $appointment_id), $data);

            if ($updateBooking) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getBooking->artist_id));
                $msg = $checkUser->name . ': ' . $this->translate('HAS_DECLINE_YOUR_APPOINTMENT', $lan);
                $this->firebase_notification($getBooking->user_id, "Appointment", $msg, DECLINE_BOOKING_ARTIST_NOTIFICATION);

                $this->api->api_message(1, $this->translate('APPOINTMENT_DECLINE', $lan));
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }


    public function add_favorites()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $artist_id = $this->input->post('artist_id', TRUE);
        $data['user_id'] = $user_id;
        $data['artist_id'] = $artist_id;

        if ($this->Api_model->check_favorites($user_id, $artist_id)) {
            $this->api->api_message(0, $this->translate('ALREADY_FAVORITES', $lan));
        } else {
            $this->Api_model->add_favorites($data);
            $this->api->api_message(1, $this->translate('ADD_FAVORITES_SUCCESSFULLY', $lan));
        }
    }

    public function remove_favorites()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $artist_id = $this->input->post('artist_id', TRUE);
        $this->Api_model->remove_favorites($user_id, $artist_id);
        $this->api->api_message(1, $this->translate('REMOVE_FAVORITES_SUCCESSFULLY', $lan));
    }

    public function getLocationArtist()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $role = '1';

        $checkUser = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $artist_id));
        $user_latlongs = array();
        if ($checkUser) {
            $user_latlongs['lati'] = $checkUser->live_lat;
            $user_latlongs['longi'] = $checkUser->live_long;

            $this->api->api_message_data(1, 'Lat Longs', 'data', $user_latlongs);
        } else {
            $this->api->api_message(0, $this->translate($this->translate('FAILED', $lan), $lan));
        }
    }

    public function changeCommissionArtist()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $data['artist_commission_type'] = 1;
        $this->checkUserStatus($artist_id);

        $checkUser = $this->Api_model->getSingleRow('user', array('user_id' => $artist_id));

        if ($checkUser) {
            $updateUser = $this->Api_model->updateSingleRow(ART_TBL, array('user_id' => $artist_id), $data);
            $this->api->api_message(1, $this->translate('COMMISSION_TYPE_CHANGE', $lan));
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    public function getCurrencyType()
    {
        $lan = $this->getlanguage();
        $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
        $data['currency_type'] = $currency_setting->currency_symbol;
        $this->api->api_message_data(1, $this->translate('GET_CURRENCY_TYPE', $lan), 'currency_type', $data);
    }


    /*Start Job*/
    public function startJob()
    {
        $lan = $this->getlanguage();
        $job_id = $this->input->post('job_id', TRUE);
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['artist_id'] = $this->input->post('artist_id', TRUE);
        $data['price'] = $this->input->post('price', TRUE);
        $date_string = date('Y-m-d h:i a');
        $data['time_zone'] = $this->input->post('timezone', TRUE);
        $data['booking_date'] = date('Y-m-d');
        $data['booking_time'] = date('h:i a');
        $data['start_time'] = time();
        $data['created_at'] = time();
        $data['booking_type'] = 2;
        $data['booking_flag'] = 3;
        $data['updated_at'] = time();
        $data['booking_timestamp'] = strtotime($date_string);

        $this->checkUserStatus($data['user_id']);
        $this->checkUserStatus($data['artist_id']);

        $checkArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $data['artist_id'], 'booking_flag' => 1));

        if ($checkArtist) {
            $this->api->api_message(0, $this->translate('ARTIST_BUSY_ANOTHER_CLIENT', $lan));
            exit();
        }

        $checkJob = $this->Api_model->getSingleRow(AJB_TBL, array('job_id' => $job_id, 'status' => 1, 'artist_id' => $data['artist_id']));
        if ($checkJob) {
            $data['job_id'] = $job_id;
            $this->Api_model->updateSingleRow(AJB_TBL, array('job_id' => $job_id, 'artist_id' => $data['artist_id']), array('status' => 5));

            $checkArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $data['artist_id'], 'booking_flag' => 1));
            if ($checkArtist) {
                $this->api->api_message(0, $this->translate('ARTIST_BUSY_ANOTHER_CLIENT', $lan));
                exit();
            }

            $getArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $data['artist_id']));

            $category_id = $getArtist->category_id;
            $category = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $category_id));

            $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));

            $data['commission_type'] = $commission_setting->commission_type;
            $data['flat_type'] = $commission_setting->flat_type;
            if ($commission_setting->commission_type == 0) {
                $data['category_price'] = $category->price;
            } elseif ($commission_setting->commission_type == 1) {
                if ($commission_setting->flat_type == 2) {
                    $data['category_price'] = $commission_setting->flat_amount;
                } elseif ($commission_setting->flat_type == 1) {
                    $data['category_price'] = $commission_setting->flat_amount;
                }
            }

            $getJob = $this->Api_model->getSingleRow('post_job', array('job_id' => $job_id));
            $data['address'] = $getJob->address;
            $data['latitude'] = $getJob->lati;
            $data['longitude'] = $getJob->longi;

            $appId = $this->Api_model->insertGetId(ABK_TBL, $data);
            if ($appId) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $data['artist_id']));
                $msg = $checkUser->name . ': start your job ' . $date_string;
                $this->firebase_notification($data['user_id'], "Start Job", $msg, START_BOOKING_ARTIST_NOTIFICATION);

                $dataNotification['user_id'] = $data['user_id'];
                $dataNotification['title'] = "Start Job";
                $dataNotification['msg'] = $msg;
                $dataNotification['type'] = "Individual";
                $dataNotification['created_at'] = time();
                $this->Api_model->insertGetId(NTS_TBL, $dataNotification);

                $updateUser = $this->Api_model->updateSingleRow(ART_TBL, array('user_id' => $data['artist_id']), array('booking_flag' => 1));
                $this->api->api_message(1, $this->translate('BOOK_APP', $lan));
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('NO_JOB_FOUNT', $lan));
            exit();
        }
    }

    /*Mark as complete*/
    public function jobComplete()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $job_id = $this->input->post('job_id', TRUE);

        $this->checkUserStatus($user_id);

        $job = $this->Api_model->getWhereInStatusResultJob('applied_job', array('job_id' => $job_id), 'status', array(5));
        if (empty($job)) {
            $jobs = $this->Api_model->getWhereInStatusResultJob('applied_job', array('job_id' => $job_id), 'status', array(0, 1));
            foreach ($jobs as $jobs) {
                $this->Api_model->updateSingleRow('applied_job', array('aj_id' => $jobs->aj_id), array('status' => 3));
            }
            $this->Api_model->updateSingleRow('post_job', array('job_id' => $job_id), array('status' => 2));
            $this->api->api_message(1, $this->translate('JOB_FINISHED', $lan));
        } else {
            $this->api->api_message(0, $this->translate('CURRENT_ARTIST_WORK', $lan));
        }
    }

    /*Request For Wallet Amount*/
    public function walletRequest()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);

        $this->checkUserStatus($user_id);

        $data['artist_id'] = $user_id;
        $data['created_at'] = time();
        $reqGet = $this->Api_model->getSingleRow("wallet_request", array('artist_id' => $user_id, 'status' => 0));
        if ($reqGet) {
            $this->api->api_message(0, $this->translate('YOUR_REQUEST_PENDING', $lan));
            exit();
        }

        $id = $this->Api_model->insertGetId("wallet_request", $data);
        if ($id) {
            $this->api->api_message(1, $this->translate('THNX_FOR_REQUEST', $lan));
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    public function tapProcessPayment($user_id, $coupon_code, $invoice_id, $final_amount, $payment_status, $payment_type, $discount_amount)
    {
        $lan = $this->getlanguage();
        $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
        $currency_type = $currency_setting->currency_symbol;

        $this->checkUserStatus($user_id);
        $getCoupon = $this->Api_model->getSingleRow(DCP_TBL, array('coupon_code' => $coupon_code));
        if ($getCoupon) {
            if ($getCoupon) {
                if ($payment_status == 1) {
                    if (isset($payment_type)) {
                        $this->Api_model->updateSingleRow(IVC_TBL, array('invoice_id' => $invoice_id), array('final_amount' => $final_amount, 'coupon_code' => $coupon_code, 'flag' => 1, 'payment_status' => $payment_status, "payment_type" => $payment_type, "discount_amount" => $discount_amount));
                    } else {
                        $this->Api_model->updateSingleRow(IVC_TBL, array('invoice_id' => $invoice_id), array('final_amount' => $final_amount, 'coupon_code' => $coupon_code, 'flag' => 1, 'payment_status' => $payment_status, "discount_amount" => $discount_amount));
                    }

                    $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('invoice_id' => $invoice_id));
                    $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('id' => $getInvoice->booking_id));
                    $getInvoice->booking_time = $getBooking->booking_time;
                    $getInvoice->booking_date = $getBooking->booking_date;
                    $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->user_id));
                    $getInvoice->userName = $getUser->name;
                    $getInvoice->userEmail = $getUser->email_id;
                    $getInvoice->address = $getUser->address;
                    $get_artists = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $getInvoice->artist_id));
                    $getArt = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->artist_id));
                    $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));
                    $getInvoice->ArtistName = $get_artists->name;
                    $getInvoice->ArtistEmail = $getArt->email_id;
                    $getInvoice->ArtistLocation = $get_artists->location;
                    $getInvoice->categoryName = $get_cat->cat_name;
                    // $getInvoice->discount=$get_cat->cat_name;
                    $subject = IVE_SUB;
                    /*
              Here customer can use email template for sending email. We are using free services which will not work

            $this->send_invoice($getInvoice->userEmail, $subject, $getInvoice);
            $this->send_invoice($getInvoice->ArtistEmail, $subject, $getInvoice);
            */

                    $getCommission = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->artist_id));
                    if ($getCommission) {
                        if ($payment_type == 1) {
                            $amount = $getCommission->amount - $getInvoice->category_amount;
                            $amount_wallet = $getInvoice->category_amount;
                            $status = 1;
                            $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        } else {
                            $amount = $getCommission->amount + $getInvoice->artist_amount;
                            $amount_wallet = $getInvoice->artist_amount;
                            $status = 0;

                            $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        }
                        $data_send = array(
                            'invoice_id' => $getInvoice->invoice_id,
                            'user_id' => $getInvoice->artist_id,
                            'amount' => $amount_wallet,
                            'currency_type' => $currency_type,
                            'type' => 2,
                            'status' => $status,
                            'description' => "Booking invoice",
                            'created_at' => time(),
                            'order_id' => time()
                        );
                        $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                        $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->artist_id), array('amount' => $amount));
                    } else {
                        if ($payment_type == 1) {
                            $amount = -$getInvoice->category_amount;
                            $status = 1;

                            $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        } else {
                            $amount = $getInvoice->artist_amount;
                            $status = 0;

                            $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        }
                        $data_send = array(
                            'invoice_id' => $getInvoice->invoice_id,
                            'user_id' => $getInvoice->artist_id,
                            'amount' => $amount,
                            'currency_type' => $currency_type,
                            'type' => 2,
                            'status' => $status,
                            'description' => "Booking invoice",
                            'created_at' => time(),
                            'order_id' => time()
                        );
                        $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                        $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $getInvoice->artist_id, 'amount' => $amount));
                    }
                    if ($payment_type == 2) {
                        $getWallet = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->user_id));
                        if ($getWallet) {
                            $data_send = array(
                                'invoice_id' => $getInvoice->invoice_id,
                                'user_id' => $getInvoice->user_id,
                                'amount' => $final_amount,
                                'currency_type' => $currency_type,
                                'type' => 2,
                                'status' => 1,
                                'description' => "Booking invoice",
                                'created_at' => time(),
                                'order_id' => time()
                            );
                            $msg = $currency_type . ' ' . $final_amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->user_id, "Wallet", $msg, WALLET_NOTIFICATION);
                            $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                            $amount = $getWallet->amount - $final_amount;
                            $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->user_id), array('amount' => $amount));
                        }
                    }
                    //$this->api->api_message(1, $this->translate('PAYMENT_CONFIRM',$lan));
                } else {
                    $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('invoice_id' => $invoice_id));
                    $getCommission = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->artist_id));
                    if ($getCommission) {
                        if ($payment_type == 1) {
                            $amount = $getCommission->amount - $getInvoice->category_amount;
                            $amount_wallet = $getInvoice->category_amount;
                            $status = 1;

                            $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        } else {
                            $amount = $getCommission->amount + $getInvoice->artist_amount;
                            $amount_wallet = $getInvoice->artist_amount;
                            $status = 0;

                            $msg = '$ ' . $amount_wallet . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        }
                        $data_send = array(
                            'invoice_id' => $getInvoice->invoice_id,
                            'user_id' => $getInvoice->artist_id,
                            'amount' => $amount_wallet,
                            'currency_type' => $currency_type,
                            'type' => 2,
                            'status' => $status,
                            'description' => "Booking invoice",
                            'created_at' => time(),
                            'order_id' => time()
                        );
                        $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                        $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->artist_id), array('amount' => $amount));
                    } else {
                        if ($payment_type == 1) {
                            $amount = -$getInvoice->category_amount;

                            $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        } else {
                            $amount = $getInvoice->artist_amount;
                            $status = 0;

                            $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        }
                        $data_send = array(
                            'invoice_id' => $getInvoice->invoice_id,
                            'user_id' => $getInvoice->artist_id,
                            'amount' => $amount,
                            'currency_type' => $currency_type,
                            'type' => 2,
                            'status' => $status,
                            'description' => "Booking invoice",
                            'created_at' => time(),
                            'order_id' => time()
                        );
                        $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                        $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $getInvoice->artist_id, 'amount' => $amount));
                    }
                    $this->Api_model->updateSingleRow(IVC_TBL, array('invoice_id' => $invoice_id), array('final_amount' => $final_amount, 'coupon_code' => $coupon_code, 'payment_status' => $payment_status, "discount_amount" => $discount_amount));
                    if ($payment_status == 3) {
                        if ($payment_type == 2) {
                            $getWallet = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->user_id));
                            if ($getWallet) {
                                $data_send = array(
                                    'invoice_id' => $getInvoice->invoice_id,
                                    'user_id' => $getInvoice->user_id,
                                    'amount' => $final_amount,
                                    'currency_type' => $currency_type,
                                    'type' => 2,
                                    'status' => 1,
                                    'description' => "Booking invoice",
                                    'created_at' => time(),
                                    'order_id' => time()
                                );

                                $msg = $currency_type . ' ' . $final_amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                                $this->firebase_notification($getInvoice->user_id, "Wallet", $msg, WALLET_NOTIFICATION);

                                $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                                $amount = $getWallet->amount - $final_amount;
                                $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->user_id), array('amount' => $amount));
                            }
                        }
                        $this->api->api_message(1, $this->translate('INITIATE_PAYMENT', $lan));
                    } else {
                        $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
                    }
                }
            } else {
                $this->api->api_message(0, $this->translate('COUPON_NOT_VLID', $lan));
            }
        } else {
            if ($payment_status == 1) {
                $this->Api_model->updateSingleRow(IVC_TBL, array('invoice_id' => $invoice_id), array('final_amount' => $final_amount, 'coupon_code' => $coupon_code, 'flag' => 1, 'payment_status' => $payment_status, 'payment_type' => $payment_type, "discount_amount" => $discount_amount));

                $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('invoice_id' => $invoice_id));
                $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('id' => $getInvoice->booking_id));
                $getInvoice->booking_time = $getBooking->booking_time;
                $getInvoice->booking_date = $getBooking->booking_date;
                $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->user_id));
                $getInvoice->userName = $getUser->name;
                $getInvoice->userEmail = $getUser->email_id;
                $getInvoice->address = $getUser->address;
                $get_artists = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $getInvoice->artist_id));
                $getArt = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->artist_id));
                $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));

                $getCommission = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->artist_id));
                if ($getCommission) {
                    if ($payment_type == 1) {
                        $amount = $getCommission->amount - $getInvoice->category_amount;
                        $amount_wallet = $getInvoice->category_amount;
                        $status = 1;

                        $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    } else {
                        $amount = $getCommission->amount + $getInvoice->artist_amount;
                        $amount_wallet = $getInvoice->artist_amount;
                        $status = 0;

                        $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    }
                    $data_send = array(
                        'invoice_id' => $getInvoice->invoice_id,
                        'user_id' => $getInvoice->artist_id,
                        'amount' => $amount_wallet,
                        'currency_type' => $currency_type,
                        'type' => 2,
                        'status' => $status,
                        'description' => "Booking invoice",
                        'created_at' => time(),
                        'order_id' => time()
                    );
                    $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                    $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->artist_id), array('amount' => $amount));
                } else {
                    if ($payment_type == 1) {
                        $amount = -$getInvoice->category_amount;
                        $status = 1;

                        $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    } else {
                        $amount = $getInvoice->artist_amount;
                        $status = 0;

                        $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    }
                    $data_send = array(
                        'invoice_id' => $getInvoice->invoice_id,
                        'user_id' => $getInvoice->artist_id,
                        'amount' => $amount,
                        'currency_type' => $currency_type,
                        'type' => 2,
                        'status' => $status,
                        'description' => "Booking invoice",
                        'created_at' => time(),
                        'order_id' => time()
                    );
                    $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                    $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $getInvoice->artist_id, 'amount' => $amount));
                }

                $getInvoice->ArtistName = $get_artists->name;
                $getInvoice->ArtistEmail = $getArt->email_id;
                $getInvoice->ArtistLocation = $get_artists->location;
                $getInvoice->categoryName = $get_cat->cat_name;
                $subject = IVE_SUB;
                $this->send_invoice($getInvoice->userEmail, $subject, $getInvoice);
                $this->send_invoice($getInvoice->ArtistEmail, $subject, $getInvoice);

                if ($payment_type == 2) {
                    $getWallet = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->user_id));
                    if ($getWallet) {
                        $data_send = array(
                            'invoice_id' => $getInvoice->invoice_id,
                            'user_id' => $getInvoice->user_id,
                            'amount' => $final_amount,
                            'currency_type' => $currency_type,
                            'type' => 2,
                            'status' => 1,
                            'description' => "Booking invoice",
                            'created_at' => time(),
                            'order_id' => time()
                        );

                        $msg = $currency_type . ' ' . $final_amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->user_id, "Wallet", $msg, WALLET_NOTIFICATION);
                        $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                        $amount = $getWallet->amount - $final_amount;
                        $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->user_id), array('amount' => $amount));
                    }
                }

                //$this->api->api_message(1, PAYMENT_CONFIRM);
            } else {
                $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('invoice_id' => $invoice_id));
                $getCommission = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->artist_id));
                if ($getCommission) {
                    if ($payment_type == 1) {
                        $amount = $getCommission->amount - $getInvoice->category_amount;
                        $amount_wallet = $getInvoice->category_amount;
                        $status = 1;

                        $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    } else {
                        $amount = $getCommission->amount + $getInvoice->artist_amount;
                        $amount_wallet = $getInvoice->artist_amount;
                        $status = 0;

                        $msg = $currency_type . ' ' . $amount_wallet . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg);
                    }
                    $data_send = array(
                        'invoice_id' => $getInvoice->invoice_id,
                        'user_id' => $getInvoice->artist_id,
                        'amount' => $amount_wallet,
                        'currency_type' => $currency_type,
                        'type' => 2,
                        'status' => $status,
                        'description' => "Booking invoice",
                        'created_at' => time(),
                        'order_id' => time()
                    );
                    $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                    $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->artist_id), array('amount' => $amount));
                } else {
                    if ($payment_type == 1) {
                        $amount = -$getInvoice->category_amount;
                        $status = 1;

                        $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    } else {
                        $amount = $getInvoice->artist_amount;
                        $status = 0;

                        $msg = $currency_type . ' ' . $amount . ' ' . $this->translate('CREDIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                        $this->firebase_notification($getInvoice->artist_id, "Wallet", $msg, WALLET_NOTIFICATION);
                    }
                    $data_send = array(
                        'invoice_id' => $getInvoice->invoice_id,
                        'user_id' => $getInvoice->artist_id,
                        'amount' => $amount,
                        'currency_type' => $currency_type,
                        'type' => 2,
                        'status' => $status,
                        'description' => "Booking invoice",
                        'created_at' => time(),
                        'order_id' => time()
                    );
                    $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                    $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $getInvoice->artist_id, 'amount' => $amount));
                }
                $this->Api_model->updateSingleRow(IVC_TBL, array('invoice_id' => $invoice_id), array('final_amount' => $final_amount, 'coupon_code' => $coupon_code, 'payment_status' => $payment_status, "discount_amount" => $discount_amount));
                if ($payment_status == 3) {
                    if ($payment_type == 2) {
                        $getWallet = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $getInvoice->user_id));
                        if ($getWallet) {
                            $data_send = array(
                                'invoice_id' => $getInvoice->invoice_id,
                                'user_id' => $getInvoice->user_id,
                                'amount' => $final_amount,
                                'currency_type' => $currency_type,
                                'type' => 2,
                                'status' => 1,
                                'description' => "Booking invoice",
                                'created_at' => time(),
                                'order_id' => time()
                            );

                            $msg = $currency_type . ' ' . $final_amount . ' ' . $this->translate('DEBIT_IN_YOUR_WALLET_BY_BOOKING', $lan);
                            $this->firebase_notification($getInvoice->user_id, "Wallet", $msg, WALLET_NOTIFICATION);
                            $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
                            $amount = $getWallet->amount - $final_amount;
                            $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $getInvoice->user_id), array('amount' => $amount));
                        }
                    }
                    $this->api->api_message(1, $this->translate('INITIATE_PAYMENT', $lan));
                } else {
                    $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
                }
            }
        }
    }

    public function getReturnUrl()
    {
        return base_url() . 'Webservice/paytap/';
    }

    public function getReturnUrlWallet()
    {
        return base_url() . 'Webservice/paytapwallet/';
    }

    public function tapsuccess()
    {
        $this->load->view('payusuccess1');
    }

    public function tapfail()
    {
        $this->load->view('payufailure1');
    }

    public function handlePurchaseData($data)
    {
        if (!empty($data['coupon_code'])) {
            $url = $this->getReturnUrl() . '?c=' . $data['orderid'] . '&coupon_code=' . $data['coupon_code'] . '&discount_amount=' . $data['discount_amount'];
        } else {
            $url = $this->getReturnUrl() . '?c=' . $data['orderid'];
        }
        $paycompany_args = array();
        $paycompany_args['MEID'] = $data['merchant_id'];
        $paycompany_args['UName'] = $data['username'];
        $paycompany_args['PWD'] = $data['password'];
        $paycompany_args['ItemName1'] = 'Order ID : ' . $data['orderid'];
        $paycompany_args['ItemQty1'] = '1';
        $paycompany_args['OrdID'] = $data['orderid'];
        $paycompany_args['ItemPrice1'] = (float)$data['price'];
        $paycompany_args['CurrencyCode'] = 'SAR';
        $paycompany_args['CstFName'] = $data['name'];
        $paycompany_args['CstEmail'] = $data['email'];
        $paycompany_args['CstMobile'] = $data['mobile'];
        $paycompany_args['ReturnURL'] = $url;
        return $paycompany_args;
    }


    public function handlePurchaseWallet($data)
    {
        $url = $this->getReturnUrlWallet() . '?c=' . $data['orderid'];
        $paycompany_args = array();
        $paycompany_args['MEID'] = $data['merchant_id'];
        $paycompany_args['UName'] = $data['username'];
        $paycompany_args['PWD'] = $data['password'];
        $paycompany_args['ItemName1'] = 'Order ID : ' . $data['orderid'];
        $paycompany_args['ItemQty1'] = '1';
        $paycompany_args['OrdID'] = $data['orderid'];
        $paycompany_args['ItemPrice1'] = (float)$data['price'];
        $paycompany_args['CurrencyCode'] = 'SAR';
        $paycompany_args['CstFName'] = $data['name'];
        $paycompany_args['CstEmail'] = $data['email'];
        $paycompany_args['CstMobile'] = $data['mobile'];
        $paycompany_args['ReturnURL'] = $url;
        return $paycompany_args;
    }


    function redirect($url)
    {
        header("location: " . $url);
    }

    public function tapProcessWalletPayment($user_id, $ref, $amount)
    {
        $getUser = $this->Api_model->getSingleRow('user', array('user_id' => $user_id));
        $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
        $currency_type = $currency_setting->currency_symbol;
        // this line will be reached if no error was thrown above
        $data_send = array(
            'invoice_id' => $ref,
            'user_id' => $user_id,
            'amount' => $amount,
            'currency_type' => $currency_type,
            'type' => 1,
            'status' => 0,
            'description' => "Add money to wallet",
            'created_at' => time(),
            'order_id' => time()
        );
        $getUserId = $this->Api_model->insertGetId('wallet_history', $data_send);
        $getWallent = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $user_id));
        if ($getWallent) {
            $amount = $getWallent->amount + $amount;
            $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $user_id), array('amount' => $amount));
        } else {
            $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $user_id, 'amount' => $amount));
        }
    }

    public function paytapwallet()
    {
        $getKey = $this->Api_model->getSingleRow('stripe_keys', array('id' => 1));
        if (isset($_GET['c'])) {
            $user_id = $_GET['c'];
            $ref = $_REQUEST['ref'];
            $result = $_REQUEST['result'];
            $trackid = $_REQUEST['trackid'];
            $hash = $_REQUEST['hash'];
            $amount = $_GET['amt'];
            $APIKey = $getKey->api_key;
            $MerchantID = $getKey->publishable_key;

            $compare_string = 'x_account_id' . $MerchantID . 'x_ref' . $ref . 'x_result' . $result . 'x_referenceid' . $trackid . '';
            $compare_hash1 = hash_hmac('sha256', $compare_string, $APIKey);
            $compare_hash2 = $hash;
            if ($compare_hash1 == $compare_hash2) {
                $this->tapProcessWalletPayment($user_id, $ref, $amount);
                $this->redirect(base_url() . 'Webservice/tapsuccess');
            } else {
                $this->redirect(base_url() . 'Webservice/tapfail');
            }
        } else {
            $user_id = $this->input->get('userId');
            $user = $this->Api_model->getSingleRow('user', array('user_id' => $user_id));
            $amount = $this->input->get('amount');


            $data['orderid'] = $user_id;
            $data['amount'] = $amount;
            $data['api_key'] = $getKey->api_key;
            $data['coupon_code'] = '';
            $data['merchant_id'] = $getKey->publishable_key;
            $data['username'] = $getKey->username;
            $data['password'] = $getKey->password;
            $data['price'] = $amount;
            $data['name'] = $user->name;
            $data['email'] = $user->email_id;
            $data['mobile'] = MOBILE_CODE . $user->mobile;
            $query = $this->handlePurchaseWallet($data);

            if ($test === true) {
                $checkout_url_sandbox = 'http://live.gotapnow.com/webpay.aspx';
            } else {
                $checkout_url_sandbox = 'https://www.gotapnow.com/webpay.aspx';
            }
            $twoco_args = http_build_query($query, '', '&');
            $url = $checkout_url_sandbox . "?" . $twoco_args;
            //$this->load->view('paytap', $result);

            ob_start();
            $this->redirect($url);
            echo 'Please wait...';
            die();
            ob_end_flush();
        }
    }

    public function paytap()
    {
        $getKey = $this->Api_model->getSingleRow('stripe_keys', array('id' => 1));
        if (isset($_GET['c'])) {

            $invoice_id = $_GET['c'];
            $invoice_data = $this->Api_model->getSingleRow('booking_invoice', array('invoice_id' => $invoice_id));
            $user_id = $invoice_data->user_id;
            $payment_status = 1;
            $payment_type = 0;

            $ref = $_REQUEST['ref'];
            $result = $_REQUEST['result'];
            $trackid = $_REQUEST['trackid'];
            $hash = $_REQUEST['hash'];
            $final_amount = $_GET['amt'];
            $coupon_code = ($_GET['coupon_code']) ? $_GET['coupon_code'] : '';
            $discount_amount = ($_GET['discount_amount']) ? $_GET['discount_amount'] : '0';
            $APIKey = $getKey->api_key;
            $MerchantID = $getKey->publishable_key;

            $compare_string = 'x_account_id' . $MerchantID . 'x_ref' . $ref . 'x_result' . $result . 'x_referenceid' . $trackid . '';
            $compare_hash1 = hash_hmac('sha256', $compare_string, $APIKey);
            $compare_hash2 = $hash;
            if ($compare_hash1 == $compare_hash2) {

                $test = $this->tapProcessPayment($user_id, $coupon_code, $invoice_id, $final_amount, $payment_status, $payment_type, $discount_amount);

                $this->redirect(base_url() . 'Webservice/tapsuccess');
            } else {
                $this->redirect(base_url() . 'Webservice/tapfail');
            }
        } else {

            $user_id = $this->input->get('userId');
            $user = $this->Api_model->getSingleRow('user', array('user_id' => $user_id));
            $amount = $this->input->get('amount');
            $orderid = $this->input->get('invoice_id');
            $coupon_code = $this->input->post('coupon_code', TRUE);
            $discount_amount = $this->input->post('discount_amount', TRUE);

            $data['orderid'] = $orderid;
            $data['amount'] = $amount;
            $data['coupon_code'] = $coupon_code;
            $data['discount_amount'] = $discount_amount;
            $data['api_key'] = $getKey->api_key;
            $data['merchant_id'] = $getKey->publishable_key;
            $data['username'] = $getKey->username;
            $data['password'] = $getKey->password;
            $data['price'] = $amount;
            $data['name'] = $user->name;
            $data['email'] = $user->email_id;
            $data['mobile'] = $user->mobile;
            $query = $this->handlePurchaseData($data);
            $test = false;
            if ($test === true) {
                $checkout_url_sandbox = 'http://live.gotapnow.com/webpay.aspx';
            } else {
                $checkout_url_sandbox = 'https://www.gotapnow.com/webpay.aspx';
            }
            $twoco_args = http_build_query($query, '', '&');
            $url = $checkout_url_sandbox . "?" . $twoco_args;
            //$this->load->view('paytap', $result);
            //
            ob_start();
            $this->redirect($url);
            echo 'Please wait...';
            die();
            ob_end_flush();
            //$this->redirect($url);
        }
    }

    /*PayPal Payment gateway*/
    public function paypal()
    {
        $paypal_setting = $this->Api_model->getSingleRow('paypal_setting', array('id' => 1));
        $pkf_name = "Booking Invoice";
        $paypal_id = $paypal_setting->paypal_id;
        $amount = $this->input->get('amount');
        $userId = $this->input->get('userId');
        $pkgId = $this->input->get('invoice_id');
        $data = array('pkgName' => $pkf_name, 'amount' => $amount, 'userId' => $userId, 'pkgId' => $pkgId, 'paypal_id' => $paypal_id);
        $this->load->view('paypal', $data);
    }

    public function paypalWallent()
    {
        $paypal_setting = $this->Api_model->getSingleRow('paypal_setting', array('id' => 1));
        $pkf_name = "Add Money";
        $paypal_id = $paypal_setting->paypal_id;
        $amount = $this->input->get('amount');
        $userId = $this->input->get('userId');
        $pkgId = time();
        $data = array('pkgName' => $pkf_name, 'amount' => $amount, 'userId' => $userId, 'pkgId' => $pkgId, 'paypal_id' => $paypal_id);
        $this->load->view('paypal', $data);
    }


    /*Get My favourite*/
    public function getMyFavourite()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $longitude_app = $this->input->post('longitude_app', TRUE);
        $latitude_app = $this->input->post('latitude_app', TRUE);
        $this->checkUserStatus($user_id);

        function distance($lat1, $lon1, $lat2, $lon2)
        {
            try {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                return ($miles * 1.609344);
            } catch (Exception $e) {
                return (0.0);
            }
        }

        $getFavourite = $this->Api_model->getAllDataWhere(array('user_id' => $user_id), 'favourite');
        if ($getFavourite) {
            $artists = array();
            foreach ($getFavourite as $getFavourite) {
                $artist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $getFavourite->artist_id));

                $jobDone = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist->user_id, 'booking_flag' => 4));

                $artist->total = $this->Api_model->getTotalWhere(ABK_TBL, array('artist_id' => $artist->user_id,));
                if ($artist->total == 0) {
                    $artist->percentages = 0;
                } else {
                    $artist->percentages = round(($jobDone * 100) / $artist->total);
                }
                $artist->jobDone = $jobDone;

                $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $artist->category_id));
                $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $artist->user_id));
                if ($getUser->image) {
                    $artist->image = base_url() . $getUser->image;
                } else {
                    $artist->image = base_url() . "assets/images/image.png";
                }
                if ($get_cat) {
                    $artist->category_name = $get_cat->cat_name;//
                } else {
                    $artist->category_name = "No Category";//
                }

                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $artist->currency_type = $currency_setting->currency_symbol;
                $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));
                $artist->commission_type = $commission_setting->commission_type;
                $artist->flat_type = $commission_setting->flat_type;
                if ($commission_setting->commission_type == 0) {
                    $artist->category_price = $get_cat->price;
                } elseif ($commission_setting->commission_type == 1) {
                    if ($commission_setting->flat_type == 2) {
                        $artist->category_price = $commission_setting->flat_amount;
                    } elseif ($commission_setting->flat_type == 1) {
                        $artist->category_price = $commission_setting->flat_amount;
                    }
                }

                $distance = distance($latitude_app, $longitude_app, $artist->latitude, $artist->longitude);
                $distance = round($distance);
                $distance_str = "$distance";
                $artist->distance = $distance_str;
                $where = array('artist_id' => $artist->user_id);
                $ava_rating = $this->Api_model->getAvgWhere('rating', 'rating', $where);
                if ($ava_rating[0]->rating == null) {
                    $ava_rating[0]->rating = "0";
                }
                $artist->ava_rating = round($ava_rating[0]->rating, 2);

                $check_fav = $this->Api_model->check_favorites($user_id, $artist->user_id);
                $artist->fav_status = $check_fav ? "1" : "0";
                array_push($artists, $artist);
            }

            usort($artists, function ($a, $b) {
                if ($a->distance == $b->distance) return 0;
                return ($a->distance < $b->distance) ? -1 : 1;
            });
            $this->api->api_message_data(1, $this->translate('ALL_ARTISTS', $lan), 'data', $artists);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /***************************************Subscription****************************/

    /*Subscription*/
    public function getAllPackages()
    {
        $lan = $this->getlanguage();
        $packages = $this->Api_model->getAllDataWhere(array('status' => 1), 'packages');
        if (empty($packages)) {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        } else {
            $package = array();
            foreach ($packages as $packages) {
                if ($packages->subscription_type == 0) {
                    $packages->subscription_name = FREE;
                } elseif ($packages->subscription_type == 1) {
                    $packages->subscription_name = MONTHLY;
                } elseif ($packages->subscription_type == 2) {
                    $packages->subscription_name = QUARTERLY;
                } elseif ($packages->subscription_type == 3) {
                    $packages->subscription_name = HALFYEARLY;
                } elseif ($packages->subscription_type == 4) {
                    $packages->subscription_name = YEARLY;
                }

                $currency = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $packages->currency_type = $currency->currency_symbol;
                array_push($package, $packages);
            }
            $this->api->api_message_data(1, $this->translate('ALL_PACKAGES', $lan), 'packages', $package);
        }
    }

    public function subscription()
    {
        $lan = $this->getlanguage();
        $this->form_validation->set_rules('order_id', 'order_id', 'required');
        $this->form_validation->set_rules('txn_id', 'txn_id', 'required');
        $this->form_validation->set_rules('package_id', 'package_id', 'required');
        $this->form_validation->set_rules('user_id', 'user_id', 'required');
        if ($this->form_validation->run() == false) {
            $this->api->api_message(0, $this->translate('ALL_FIELD_MANDATORY', $lan));
            exit();
        }
        $user_id = $this->input->post('user_id', TRUE);
        $chkUser = $this->Api_model->getSingleRow('user', array('user_id' => $user_id));
        if (!$chkUser) {
            $this->api->api_message(0, $this->translate('USER_NOT_FOUND', $lan));
            exit();
        }

        $package_id = $this->input->post('package_id', TRUE);
        $txn_id = $this->input->post('txn_id', TRUE);
        $order_id = $this->input->post('order_id', TRUE);
        $chkPackage = $this->Api_model->getSingleRow('packages', array('id' => $package_id));
        if (!$chkPackage) {
            $this->api->api_message(0, $this->translate('PKG_NOT', $lan));
            exit();
        }

        $date = date('Y-m-d');
        $current_date = strtotime($date);

        $get_package = $this->Api_model->getSingleRow('packages', array('id' => $package_id));
        if (!$get_package) {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
            exit();
        }
        $price = $get_package->price;
        $subscription_type = $get_package->subscription_type;
        $currency = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
        $currency_type = $currency->currency_symbol;

        $data['user_id'] = $user_id;
        $data['txn_id'] = $txn_id;
        $data['order_id'] = $order_id;
        $data['subscription_start_date'] = $current_date;
        $data['price'] = $price;
        $data['subscription_type'] = $subscription_type;
        $data['currency_type'] = $currency_type;
        $dataupdate = $this->Api_model->getSingleRow('user_subscription', array('user_id' => $user_id));

        if ($dataupdate) {
            $check_end_date = $dataupdate->subscription_end_date;

            if ($current_date >= $check_end_date) {
                if ($subscription_type == 1) {
                    $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 30, date('Y'))) . "\n";
                    $data['subscription_end_date'] = strtotime($end_date);
                } elseif ($subscription_type == 3) {
                    $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 180, date('Y'))) . "\n";
                    $data['subscription_end_date'] = strtotime($end_date);
                } elseif ($subscription_type == 2) {
                    $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 90, date('Y'))) . "\n";
                    $data['subscription_end_date'] = strtotime($end_date);
                } elseif ($subscription_type == 0) {
                    $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 30, date('Y'))) . "\n";
                    $data['subscription_end_date'] = strtotime($end_date);
                } elseif ($subscription_type == 4) {
                    $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 365, date('Y'))) . "\n";
                    $data['subscription_end_date'] = strtotime($end_date);
                }
                $this->Api_model->updateSingleRow('user_subscription', array('user_id' => $user_id), $data);
                $this->Api_model->insertGetId('subscription_history', $data);
                $this->api->api_message(1, $this->translate('SUB_SUCCESS', $lan));
            } else {
                if ($subscription_type == 1) {
                    $no_of_days = 30;
                    $end_date = strtotime('+' . $no_of_days . ' days', $check_end_date);
                    $data['subscription_end_date'] = $end_date;
                } elseif ($subscription_type == 3) {
                    $no_of_days = 180;
                    $end_date = strtotime('+' . $no_of_days . ' days', $check_end_date);
                    $data['subscription_end_date'] = $end_date;
                } elseif ($subscription_type == 2) {
                    $no_of_days = 90;
                    $end_date = strtotime('+' . $no_of_days . ' days', $check_end_date);
                    $data['subscription_end_date'] = $end_date;
                } elseif ($subscription_type == 0) {
                    $no_of_days = 30;
                    $end_date = strtotime('+' . $no_of_days . ' days', $check_end_date);
                    $data['subscription_end_date'] = $end_date;
                } elseif ($subscription_type == 4) {
                    $no_of_days = 365;
                    $end_date = strtotime('+' . $no_of_days . ' days', $check_end_date);
                    $data['subscription_end_date'] = $end_date;
                }
                $this->Api_model->updateSingleRow('user_subscription', array('user_id' => $user_id), $data);
                $this->Api_model->insertGetId('subscription_history', $data);
                $this->api->api_message(1, $this->translate('SUB_SUCCESS', $lan));
            }
        } else {
            if ($subscription_type == 1) {
                $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 30, date('Y'))) . "\n";
                $data['subscription_end_date'] = strtotime($end_date);
            } elseif ($subscription_type == 3) {
                $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 180, date('Y'))) . "\n";
                $data['subscription_end_date'] = strtotime($end_date);
            } elseif ($subscription_type == 2) {
                $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 90, date('Y'))) . "\n";
                $data['subscription_end_date'] = strtotime($end_date);
            } elseif ($subscription_type == 0) {
                $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 30, date('Y'))) . "\n";
                $data['subscription_end_date'] = strtotime($end_date);
            } elseif ($subscription_type == 4) {
                $end_date = date('Y-m-d', mktime(date('h'), date('i'), date('s'), date('m'), date('d') + 365, date('Y'))) . "\n";
                $data['subscription_end_date'] = strtotime($end_date);
            }
            $this->Api_model->insertGetId('user_subscription', $data);
            $this->Api_model->insertGetId('subscription_history', $data);

            $this->api->api_message(1, $this->translate('SUB_SUCCESS', $lan));
        }
    }


    public function get_my_subscription_history()
    {
        $lan = $this->getlanguage();
        $this->form_validation->set_rules('user_id', 'user_id', 'required');
        if ($this->form_validation->run() == false) {
            $this->api->api_message(0, $this->translate('ALL_FIELD_MANDATORY', $lan));
            exit();
        }
        $user_id = $this->input->post('user_id', TRUE);
        $chkUser = $this->Api_model->getSingleRow('user', array('user_id' => $user_id));
        if (!$chkUser) {
            $this->api->api_message(0, $this->translate('USER_NOT_FOUND', $lan));
            exit();
        }

        $get_my_subscription = $this->Api_model->getAllDataWhere(array('user_id' => $user_id), 'subscription_history');

        if ($get_my_subscription) {
            $get_my_subscriptions = array();
            foreach ($get_my_subscription as $get_my_subscription) {
                if ($get_my_subscription->subscription_type == 0) {
                    $get_my_subscription->subscription_name = FREE;
                } elseif ($get_my_subscription->subscription_type == 1) {
                    $get_my_subscription->subscription_name = MONTHLY;
                } elseif ($get_my_subscription->subscription_type == 2) {
                    $get_my_subscription->subscription_name = QUARTERLY;
                } elseif ($get_my_subscription->subscription_type == 3) {
                    $get_my_subscription->subscription_name = HALFYEARLY;
                } elseif ($get_my_subscription->subscription_type == 4) {
                    $get_my_subscription->subscription_name = YEARLY;
                }

                array_push($get_my_subscriptions, $get_my_subscription);
            }
            $this->api->api_message_data(1, $this->translate('SUB_HISTORY', $lan), 'my_subscription_history', $get_my_subscriptions);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    public function get_my_subscription()
    {
        $lan = $this->getlanguage();
        $this->form_validation->set_rules('user_id', 'user_id', 'required');
        if ($this->form_validation->run() == false) {
            $this->api->api_message(0, $this->translate('ALL_FIELD_MANDATORY', $lan));
            exit();
        }
        $user_id = $this->input->post('user_id', TRUE);
        $chkUser = $this->Api_model->getSingleRow('user', array('user_id' => $user_id));
        if (!$chkUser) {
            $this->api->api_message(0, $this->translate('USER_NOT_FOUND', $lan));
            exit();
        }

        $get_my_subscription = $this->Api_model->getSingleRow('user_subscription', array('user_id' => $user_id));
        if ($get_my_subscription) {
            $date = date('Y-m-d');
            $current_date = strtotime($date);
            $end_date = $get_my_subscription->subscription_end_date;
            $get_title = $this->Api_model->getSingleRow('packages', array('subscription_type' => $get_my_subscription->subscription_type));
            $get_my_subscription->subscription_title = $get_title->title;
            $datediff = $end_date - time();
            $get_my_subscription->days = round($datediff / (60 * 60 * 24));
            if ($get_my_subscription->subscription_type == 0) {
                $get_my_subscription->subscription_name = FREE;
            } elseif ($get_my_subscription->subscription_type == 1) {
                $get_my_subscription->subscription_name = MONTHLY;
            } elseif ($get_my_subscription->subscription_type == 2) {
                $get_my_subscription->subscription_name = QUARTERLY;
            } elseif ($get_my_subscription->subscription_type == 3) {
                $get_my_subscription->subscription_name = HALFYEARLY;
            } elseif ($get_my_subscription->subscription_type == 4) {
                $get_my_subscription->subscription_name = YEARLY;
            }

            if ($current_date > $end_date) {
                $this->api->api_message(0, $this->translate('NO_DATA', $lan));
            } else {
                $this->api->api_message_data(1, $this->translate('MY_SUB', $lan), 'my_subscription', $get_my_subscription);
            }
        } else {
            $this->api->api_message(0, $this->translate('NOT_SUB', $lan));
        }
    }

    /*get Job invoice*/
    public function getJobInvoice()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $job_id = $this->input->post('job_id', TRUE);

        $getBooking = $this->Api_model->getSingleRow(ABK_TBL, array('artist_id' => $artist_id, 'job_id' => $job_id));
        if ($getBooking) {
            $getInvoice = $this->Api_model->getSingleRow(IVC_TBL, array('booking_id' => $getBooking->id));
            if ($getInvoice) {
                $getUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->user_id));

                if ($getUser->image) {
                    $getInvoice->userImage = base_url() . $getUser->image;
                } else {
                    $getInvoice->userImage = base_url() . "assets/images/image.png";
                }

                $getInvoice->userName = $getUser->name;

                $getArtist = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $getInvoice->artist_id));
                $get_artists = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $getInvoice->artist_id));

                $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artists->category_id));
                $getInvoice->category_name = $get_cat->cat_name;
                $getInvoice->category_name_ar = $get_cat->cat_name_ar;
                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $getInvoice->currency_type = $currency_setting->currency_symbol;
                if ($getArtist->image) {
                    $getInvoice->artistImage = base_url() . $getArtist->image;
                } else {
                    $getInvoice->artistImage = base_url() . "assets/images/image.png";
                }

                $getInvoice->artistName = $getArtist->name;

                $this->api->api_message_data(1, $this->translate('MY_INVOICE', $lan), 'data', $getInvoice);
            } else {
                $this->api->api_message(0, $this->translate('NO_DATA', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*fill paypal Details*/
    public function fillPaypal()
    {
        $lan = $this->getlanguage();
        $data['artist_id'] = $this->input->post('artist_id', TRUE);
        $data['business_name'] = $this->input->post('business_name', TRUE);
        $data['paypal_id'] = $this->input->post('paypal_id', TRUE);
        $this->checkUserStatus($data['artist_id']);

        $getUserId = $this->Api_model->insertGetId(PYL_DTS_TBL, $data);
        if ($getUserId) {
            $this->api->api_message(1, $this->translate('DTL_UPLD', $lan));
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    /*get My payPal Details*/
    public function getPaypalDetails()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $getArtist = $this->Api_model->getSingleRow(PYL_DTS_TBL, array('artist_id' => $artist_id));
        if ($getArtist) {
            $this->api->api_message_data(1, $this->translate('GET_MYPAY', $lan), 'my_paypal_details', $getArtist);
        } else {
            $this->api->api_message(0, $this->translate('FILL_PAY', $lan));
        }
    }

    /*get My points*/
    public function getMyPoints()
    {
        $lan = $this->getlanguage();
        $this->form_validation->set_rules('user_id', 'user_id', 'required');
        if ($this->form_validation->run() == false) {
            $this->api->api_message(0, $this->translate('ALL_FIELD_MANDATORY', $lan));
            exit();
        }
        $user_id = $this->input->post('user_id', TRUE);
        $chkUser = $this->Api_model->getSingleRow('user', array('user_id' => $user_id));
        if (!$chkUser) {
            $this->api->api_message(0, $this->translate($this->translate('USER_NOT_FOUND', $lan), $lan));
            exit();
        }

        $getCode = $this->Api_model->getSingleRow('referral_points', array('user_id' => $user_id));
        if ($getCode) {
            $this->api->api_message_data(1, $this->translate('GET_MY_POINTS', $lan), 'data', $getCode);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    public function razor()
    {
        $lan = $this->getlanguage();
        $amount = $this->input->get('amount');
        $userId = $this->input->get('userId');
        $invoiceId = $this->input->get('invoiceId');

        $chkUser = $this->Api_model->getSingleRow('user', array('user_id' => $userId));

        $data = array('invoiceId' => $invoiceId, 'userName' => $chkUser->name, 'email' => $chkUser->email_id, 'amount' => $amount, 'userId' => $userId, 'address' => $chkUser->address);
        $this->load->view('razorpay', $data);

    }

    public function razorPayment()
    {
        $data['razorpay_payment_id'] = $this->input->post('razorpay_payment_id');
        $data['amount'] = $this->input->post('amount');
        $data['userId'] = $this->input->post('userId');
        $data['invoiceId'] = $this->input->post('invoiceId');

        $id = $this->Api_model->insertGetId('razorPayment', $data);

    }

    public function payusuccess()
    {
        $this->load->view('payusuccess');
    }

    public function payufailure()
    {
        $this->load->view('payufailure');
    }


    public function addMoney()
    {
        $lan = $this->getlanguage();
        $this->form_validation->set_rules('user_id', 'user_id', 'required');
        $this->form_validation->set_rules('amount', 'amount', 'required');
        if ($this->form_validation->run() == false) {
            $this->api->api_message(0, $this->translate('ALL_FIELD_MANDATORY', $lan));
            exit();
        }
        $user_id = $this->input->post('user_id', TRUE);
        $amount = $this->input->post('amount', TRUE);
        $txn_id = $this->api->strongToken(6);
        $order_id = $this->api->strongToken(6);
        $chkUser = $this->Api_model->getSingleRow('user', array('user_id' => $user_id));
        if (!$chkUser) {
            $this->api->api_message(0, $this->translate('USER_NOT_FOUND', $lan));
            exit();
        }

        $getWallent = $this->Api_model->getSingleRow('artist_wallet', array('artist_id' => $user_id));
        if ($getWallent) {
            $this->Api_model->insertGetId('wallet_history', array('user_id' => $user_id, 'order_id' => $order_id, 'invoice_id' => $txn_id, 'amount' => $amount, 'created_at' => time()));
            $amount = $getWallent->amount + $amount;
            $updateUser = $this->Api_model->updateSingleRow("artist_wallet", array('artist_id' => $user_id), array('amount' => $amount));
            $this->api->api_message(1, $this->translate('AMOUNT_ADD', $lan));
            exit();
        } else {
            $this->Api_model->insertGetId('artist_wallet', array('artist_id' => $user_id, 'amount' => $amount));
            $this->Api_model->insertGetId('wallet_history', array('user_id' => $user_id, 'order_id' => $order_id, 'invoice_id' => $txn_id, 'amount' => $amount, 'created_at' => time()));
            $this->api->api_message(1, $this->translate('AMOUNT_ADD', $lan));
            exit();
        }
    }

    /*Wallet Amount*/
    public function getWallet()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $this->checkUserStatus($user_id);
        $reqGet = $this->Api_model->getSingleRow("artist_wallet", array('artist_id' => $user_id));
        $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
        if ($reqGet) {
            $data['amount'] = $reqGet->amount;
            $data['currency_type'] = $currency_setting->currency_symbol;
            $this->api->api_message_data(1, $this->translate('WALLET', $lan), 'data', $data);
            exit();
        } else {
            $data['amount'] = "0";
            $data['currency_type'] = $currency_setting->currency_symbol;
            $this->api->api_message_data(1, $this->translate('WALLET', $lan), 'data', $data);
            exit();
        }
    }

    /*Wallet Amount*/
    public function getWalletHistory()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $status = $this->input->post('status', TRUE);
        $this->checkUserStatus($user_id);
        if (isset($status)) {
            $wallet_history = $this->Api_model->getAllDataWhereoderBy(array('user_id' => $user_id, 'status' => $status), 'wallet_history');
        } else {
            $wallet_history = $this->Api_model->getAllDataWhereoderBy(array('user_id' => $user_id), 'wallet_history');
        }

        $i = 0;
        foreach ($wallet_history as $wallet_histor) {
            if ($wallet_histor->description == 'Add money to wallet') {
                $wallet_history[$i]->description_ar = 'رصيد مضاف للمحفظة';
            } elseif ($wallet_histor->description == 'Booking invoice') {
                $wallet_history[$i]->description_ar = 'قيمة الفاتورة';
            } elseif ($wallet_histor->description == 'Add money') {
                $wallet_history[$i]->description_ar = 'اضافة رصيد';
            }
            $i++;
        }

        if ($wallet_history) {
            $this->api->api_message_data(1, $this->translate('WALLET_HISTORY', $lan), 'data', $wallet_history);
            exit();
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    public function getFilterItem()
    {
        $lan = $this->getlanguage();
        $data = array();
        $data['city'] = $this->Api_model->getAllDataColumn('artist', 'city', 'city != ""');
        $data['price'] = $this->Api_model->getAllDataColumn('artist', 'price', 'price != ""');
        $data['country'] = $this->Api_model->getAllDataColumn('artist', 'country', 'country != ""');
        $this->api->api_message_data(1, $this->translate('GET_FILTER_ITEMS', $lan), 'data', $data);
    }

    public function get_all_job_filter()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $tag = $this->input->post('tag', TRUE);
        $get_jobs = $this->Api_model->getAllJobNotAppliedByArtist($artist_id, $tag);

        if (empty($get_jobs)) {
            $this->api->api_message(0, $this->translate('NO_JOBS_AVAILABLE', $lan));
        } else {

            $job_list = array();
            foreach ($get_jobs as $get_jobs) {
                $get_jobs->avtar = base_url() . $get_jobs->avtar;
                $table = 'user';
                $condition = array('user_id' => $get_jobs->user_id);
                $user = $this->Api_model->getSingleRow($table, $condition);
                $user->image = base_url() . $user->image;

                $table = 'category';
                $condition = array('id' => $get_jobs->category_id);
                $cate = $this->Api_model->getSingleRow($table, $condition);
                if ($cate) {
                    $get_jobs->category_name = $cate->cat_name;
                } else {
                    $get_jobs->category_name = '';
                }
                $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));

                $get_jobs->commission_type = $commission_setting->commission_type;
                $get_jobs->flat_type = $commission_setting->flat_type;
                if ($commission_setting->commission_type == 0) {
                    $get_jobs->category_price = $cate->price;
                } elseif ($commission_setting->commission_type == 1) {
                    if ($commission_setting->flat_type == 2) {
                        $get_jobs->category_price = $commission_setting->flat_amount;
                    } elseif ($commission_setting->flat_type == 1) {
                        $get_jobs->category_price = $commission_setting->flat_amount;
                    }
                }

                $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                $get_jobs->currency_symbol = $currency_setting->currency_symbol;

                $get_jobs->user_image = $user->image;
                $get_jobs->user_name = $user->name;
                $get_jobs->user_address = $user->address;
                $get_jobs->user_mobile = $user->mobile;

                array_push($job_list, $get_jobs);
            }
            $this->api->api_message_data(1, $this->translate('ALL_JOBS_FOUND', $lan), 'data', $job_list);
        }
    }

    public function book_appointment()
    {
        $lan = $this->getlanguage();
        $data['latitude'] = $this->input->post('latitude', TRUE);
        $data['longitude'] = $this->input->post('longitude', TRUE);
        $data['address'] = $this->input->post('address', TRUE);
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['artist_id'] = $this->input->post('artist_id', TRUE);
        $data['date_string'] = $this->input->post('date_string', TRUE);
        $data['timezone'] = $this->input->post('timezone', TRUE);
        $data['appointment_date'] = date('Y-m-d', strtotime($data['date_string']));
        $data['timing'] = date('h:i a', strtotime($data['date_string']));
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $data['appointment_timestamp'] = strtotime($data['date_string']);

        $this->checkUserStatus($data['user_id']);

        $appId = $this->Api_model->insertGetId(APP_TBL, $data);

        if ($appId) {
            $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $data['user_id']));
            $msg = $checkUser->name . ': booked you on' . $data['timing'];
            $this->firebase_notification($data['artist_id'], $this->translate('BOOK_APPOINTMENT', $lan), $msg, BOOK_ARTIST_NOTIFICATION);

            $this->api->api_message(1, $this->translate('BOOK_APP', $lan));
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    /*Edit Appointment*/
    public function edit_appointment()
    {
        $lan = $this->getlanguage();
        $role = $this->input->post('role', TRUE);
        $appointment_id = $this->input->post('appointment_id', TRUE);
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['artist_id'] = $this->input->post('artist_id', TRUE);
        $data['date_string'] = $this->input->post('date_string', TRUE);
        $data['timezone'] = $this->input->post('timezone', TRUE);
        $data['appointment_date'] = date('Y-m-d', strtotime($data['date_string']));
        $data['timing'] = date('h:i a', strtotime($data['date_string']));
        $data['updated_at'] = time();
        $data['appointment_timestamp'] = strtotime($data['date_string']);

        $this->checkUserStatus($data['user_id']);

        $appId = $this->Api_model->getSingleRow(APP_TBL, array('id' => $appointment_id));

        if ($appId) {
            if ($role == 1) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $data['artist_id']));
                $msg = $checkUser->name . ': ' . $this->translate('HAS_CHANGED_YOUR_BOOKING', $lan);
                $this->firebase_notification($data['user_id'], $this->translate('BOOK_APPOINTMENT', $lan), $msg, BOOK_ARTIST_NOTIFICATION);
            } else {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $data['artist_id']));
                $msg = $checkUser->name . ': ' . $this->translate('HAS_CHANGED_YOUR_BOOKING', $lan);
                $this->firebase_notification($data['user_id'], $this->translate('BOOK_APPOINTMENT', $lan), $msg, BOOK_ARTIST_NOTIFICATION);
            }

            $checkUser = $this->Api_model->updateSingleRow(APP_TBL, array('id' => $appointment_id), $data);

            $this->api->api_message(1, $this->translate('BOOK_APP', $lan));
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    /*Appointment Delete*/
    public function declineAppointment()
    {
        $lan = $this->getlanguage();
        $appointment_id = $this->input->post('appointment_id', TRUE);
        $user_id = $this->input->post('user_id', TRUE);
        $this->checkUserStatus($user_id);

        $get_appointment = $this->Api_model->getSingleRow(APP_TBL, array('id' => $appointment_id));
        if ($get_appointment) {
            $this->Api_model->updateSingleRow(APP_TBL, array('id' => $appointment_id), array('status' => 0));
            $this->api->api_message(1, $this->translate('APP_DECLINE', $lan));
        } else {
            $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
        }
    }

    public function getAppointment()
    {
        $lan = $this->getlanguage();
        $user_id = $this->input->post('user_id', TRUE);
        $role = $this->input->post('role', TRUE);

        $this->checkUserStatus($user_id);

        if ($role == 1) {
            $where = array('artist_id' => $user_id);

            $get_appointment = $this->Api_model->getAllDataWhereoderBy($where, APP_TBL);

            if ($get_appointment) {
                $get_appointments = array();
                foreach ($get_appointment as $get_appointment) {
                    $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_appointment->user_id));

                    $get_appointment->userName = $get_user->name;
                    $get_appointment->userEmail = $get_user->email_id;
                    $get_appointment->userMobile = $get_user->mobile;

                    if ($get_user->image) {
                        $get_appointment->userImage = base_url() . $get_user->image;
                    } else {
                        $get_appointment->userImage = base_url() . "assets/images/image.png";
                    }
                    $get_appointment->userAddress = $get_user->office_address;

                    $get_artist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $get_appointment->artist_id));
                    $get_artistDetails = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_appointment->artist_id));

                    $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artist->category_id));
                    $get_appointment->category_name = $get_cat->cat_name;
                    $get_appointment->category_price = $get_cat->price;

                    $get_appointment->artistName = $get_artist->name;
                    $get_appointment->artistMobile = $get_artistDetails->mobile;
                    $get_appointment->artistEmail = $get_artistDetails->email_id;

                    $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                    $get_appointment->currency_type = $currency_setting->currency_symbol;

                    if ($get_artistDetails->image) {
                        $get_appointment->artistImage = base_url() . $get_artistDetails->image;
                    } else {
                        $get_appointment->artistImage = base_url() . "assets/images/image.png";
                    }
                    $get_appointment->artistAddress = $get_artist->location;

                    array_push($get_appointments, $get_appointment);
                }

                $this->api->api_message_data(1, $this->translate('GET_APP', $lan), 'data', $get_appointments);
            } else {
                $this->api->api_message(0, $this->translate('NO_APPOINTMENT_FOUND', $lan));
            }
        } elseif ($role == 2) {
            $where = array('user_id' => $user_id);

            $get_appointment = $this->Api_model->getAllDataWhere($where, APP_TBL);

            if ($get_appointment) {
                $get_appointments = array();
                foreach ($get_appointment as $get_appointment) {
                    $get_artist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $get_appointment->artist_id));

                    $get_artistDetails = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_appointment->artist_id));
                    $get_appointment->artistMobile = $get_artistDetails->mobile;
                    $get_appointment->artistEmail = $get_artistDetails->email_id;

                    $get_cat = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $get_artist->category_id));
                    $get_appointment->category_name = $get_cat->cat_name;
                    $get_appointment->category_price = $get_cat->price;

                    if ($get_artistDetails->image) {
                        $get_appointment->artistImage = base_url() . $get_artistDetails->image;
                    } else {
                        $get_appointment->artistImage = base_url() . "assets/images/image.png";
                    }

                    $get_appointment->artistName = $get_artist->name;
                    $get_appointment->artistAddress = $get_artist->location;
                    $currency_setting = $this->Api_model->getSingleRow('currency_setting', array('status' => 1));
                    $get_appointment->currency_type = $currency_setting->currency_symbol;

                    $get_user = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $get_appointment->user_id));
                    if ($get_user->image) {
                        $get_appointment->userImage = base_url() . $get_user->image;
                    } else {
                        $get_appointment->userImage = base_url() . "assets/images/image.png";
                    }

                    $get_appointment->userName = $get_user->name;
                    $get_appointment->userAddress = $get_user->address;
                    $get_appointment->userEmail = $get_user->email_id;
                    $get_appointment->userMobile = $get_user->mobile;

                    array_push($get_appointments, $get_appointment);
                }
                $this->api->api_message_data(1, $this->translate('GET_APP', $lan), 'data', $get_appointments);
            } else {
                $this->api->api_message(0, $this->translate('NOTFOUND', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('INVALID_REQUEST', $lan));
        }
    }

    /*Start Job*/
    public function startAppointment()
    {
        $lan = $this->getlanguage();
        $appointment_id = $this->input->post('appointment_id', TRUE);
        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['artist_id'] = $this->input->post('artist_id', TRUE);
        $data['price'] = $this->input->post('price', TRUE);
        $date_string = date('Y-m-d h:i a');
        $data['time_zone'] = $this->input->post('timezone', TRUE);
        $data['booking_date'] = date('Y-m-d');
        $data['booking_time'] = date('h:i a');
        $data['start_time'] = time();
        $data['created_at'] = time();
        $data['booking_type'] = 1;
        $data['booking_flag'] = 3;
        $data['updated_at'] = time();
        $data['booking_timestamp'] = strtotime($date_string);

        $this->checkUserStatus($data['user_id']);
        $this->checkUserStatus($data['artist_id']);

        $checkJob = $this->Api_model->getSingleRow(APP_TBL, array('id' => $appointment_id, 'status' => 1, 'artist_id' => $data['artist_id']));
        if ($checkJob) {
            $data['job_id'] = $appointment_id;
            $data['latitude'] = $checkJob->latitude;
            $data['longitude'] = $checkJob->longitude;
            $data['address'] = $checkJob->address;
            $this->Api_model->updateSingleRow(APP_TBL, array('id' => $appointment_id), array('status' => 5));

            $checkArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $data['artist_id'], 'booking_flag' => 1));
            if ($checkArtist) {
                $this->api->api_message(0, $this->translate('ARTIST_BUSY_ANOTHER_CLIENT', $lan));
                exit();
            }

            $getArtist = $this->Api_model->getSingleRow(ART_TBL, array('user_id' => $data['artist_id']));

            $category_id = $getArtist->category_id;
            $category = $this->Api_model->getSingleRow(CAT_TBL, array('id' => $category_id));

            $commission_setting = $this->Api_model->getSingleRow('commission_setting', array('id' => 1));

            $data['commission_type'] = $commission_setting->commission_type;
            $data['flat_type'] = $commission_setting->flat_type;
            if ($commission_setting->commission_type == 0) {
                $data['category_price'] = $category->price;
            } elseif ($commission_setting->commission_type == 1) {
                if ($commission_setting->flat_type == 2) {
                    $data['category_price'] = $commission_setting->flat_amount;
                } elseif ($commission_setting->flat_type == 1) {
                    $data['category_price'] = $commission_setting->flat_amount;
                }
            }

            $appId = $this->Api_model->insertGetId(ABK_TBL, $data);
            if ($appId) {
                $checkUser = $this->Api_model->getSingleRow(USR_TBL, array('user_id' => $data['user_id']));
                $msg = $checkUser->name . ': start your job ' . $date_string;
                $this->firebase_notification($data['artist_id'], "Start Job", $msg, START_BOOKING_ARTIST_NOTIFICATION);

                $dataNotification['user_id'] = $data['artist_id'];
                $dataNotification['title'] = "Start Job";
                $dataNotification['msg'] = $msg;
                $dataNotification['type'] = "Individual";
                $dataNotification['created_at'] = time();
                $this->Api_model->insertGetId(NTS_TBL, $dataNotification);

                $updateUser = $this->Api_model->updateSingleRow(ART_TBL, array('user_id' => $data['artist_id']), array('booking_flag' => 3));
                $this->api->api_message(1, $this->translate('BOOK_APP', $lan));
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('NO_APPOINT_FOUND_STARTING', $lan));
            exit();
        }
    }


    public function sendPushNotification()
    {
        // Insert your Secret API Key here
        $apiKey = '3391de266f1dbcaad44087742a97ea806a608e297d1c2953e3dc2881567f29bd';
        $data = array('message' => 'Hello World! test', 'title' => "Booking");
        $options = array(
            'notification' => array(
                'badge' => 1,
                'sound' => 'ping.aiff',
                'body' => "Hello World \xE2\x9C\x8C"
            )
        );
        // Default post data to provided options or empty array
        $post = $options ?: array();


        // Set notification payload and recipients
        $post['to'] = "304620c5b3d6500ae932d3";
        $post['data'] = $data;

        // Set Content-Type header since we're sending JSON
        $headers = array(
            'Content-Type: application/json'
        );

        // Initialize curl handle
        $ch = curl_init();

        // Set URL to Pushy endpoint
        curl_setopt($ch, CURLOPT_URL, 'https://api.pushy.me/push?api_key=' . $apiKey);

        // Set request method to POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Set our custom headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Get the response back as string instead of printing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set post data as JSON
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post, JSON_UNESCAPED_UNICODE));

        // Actually send the push
        $result = curl_exec($ch);

        // Display errors
        if (curl_errno($ch)) {
            echo curl_error($ch);
        }

        // Close curl handle
        curl_close($ch);

        // Debug API response
        echo $result;
    }


    /*Get all Countries */
    public function getAllCountires()
    {
        $lan = $this->getlanguage();

        $get_cnt = $this->Api_model->getAllDataWhere(array('active' => 1), CNT_TBL);

        if ($get_cnt) {

            $this->api->api_message_data(1, $this->translate('ALL_CNT', $lan), 'data', $get_cnt);

        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }


    /*Get all Cities under specific Country */
    public function getAllCitiesByCountry()
    {
        $lan = $this->getlanguage();
        $country_id = $this->input->post('country_id', TRUE);

        if (!$country_id) {
            $this->api->api_message(0, $this->translate('VALIDATION_ERR', $lan));
        } else {
            $get_cities = $this->Api_model->getAllDataWhere(array('active' => 1, 'country_id' => $country_id), CITIES_TBL);

            if ($get_cities) {
                $this->api->api_message_data(1, $this->translate('ALL_CITIES', $lan), 'data', $get_cities);

            } else {
                $this->api->api_message(0, $this->translate('NO_DATA', $lan));
            }
        }

    }

    public function getPaymentMethods()
    {
        $lan = $this->getlanguage();
        $country_id = $this->input->post('country_id', TRUE);

        if (!$country_id) {
            $this->api->api_message(0, $this->translate('VALIDATION_ERR', $lan));
        } else {
            $paymentMethods = $this->Api_model->getAllDataWhere(array('active' => 1, 'country_id' => $country_id), 'payment_methods');

            if ($paymentMethods) {
                $this->api->api_message_data(1, $this->translate('ALL_Payment_Methods', $lan), 'data', $paymentMethods);

            } else {
                $this->api->api_message(0, $this->translate('NO_DATA', $lan));
            }
        }
    }


    public function submitOrder()
    {
        $lan = $this->getlanguage();

        $data['user_id'] = $this->input->post('user_id', TRUE);
        $data['country_id'] = $this->input->post('country_id', TRUE);
        $data['category_id'] = $this->input->post('category_id', TRUE);
        $data['user_address_id'] = $this->input->post('user_address_id', TRUE);
        $data['booking_date'] = $this->input->post('booking_date', TRUE);
        $data['booking_time'] = $this->input->post('booking_time', TRUE);
        $data['payment_method_id'] = $this->input->post('payment_method_id', TRUE);
        $data['price'] = $this->input->post('total_amount', TRUE);
        $data['details'] = $this->input->post('details', TRUE);


        $data['notes'] = "";
        $data['flat_type'] = 1;
        $data['commission_type'] = 1;
        $data['status_id'] = 0;
        $data['created_by'] = $data['user_id'];


        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well


        $bookingOrder = $this->Api_model->insertGetId("artist_booking", $data);

        $data['order_items'] = $this->input->post('order_items', TRUE);

        foreach ($data['order_items'] as $item) {

            $itemData['booking_order_id'] = $bookingOrder;
            $itemData['category_id'] = $item['sub_category_id'];
            $itemData['quantity'] = $item['quantity'];
            $itemData['cost_per_item'] = $item['cost_per_item'];
            $itemData['created_by'] = $data['user_id'];
            $this->Api_model->insertGetId("booking_order_items", $itemData);

        }

        $logdata['booking_order_id'] = $bookingOrder;
        $logdata['log_type_id'] = 1;
        $logdata['status_id'] = $data['status_id'];
        $logdata['details'] = $data['details'];
        $logdata['created_by'] = $data['user_id'];
        $this->Api_model->insertGetId("booking_orders_logs", $logdata);

        $updateBookingPrice = $this->Api_model->updateBookingPrice($bookingOrder);


        $this->db->trans_complete(); # Completing transaction

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            $this->api->api_message(0, $this->translate('UNEXPECTED_ERR', $lan));

        } else {
            # Everything is Perfect.
            # Committing data to the database.
            $this->db->trans_commit();
            $this->api->api_message(1, $this->translate('ORDER_ADDED', $lan));

        }


    }


    public function getOrderList()
    {
        $lan = $this->getlanguage();

        $user_id = $this->input->post('user_id', TRUE);

        $bookingOrders = $this->Api_model->getBookingOrders($user_id);

        if ($bookingOrders) {
            $this->api->api_message_data(1, $this->translate('All_Booking_Orders', $lan), 'data', $bookingOrders);

        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }

    }


    public function getOrderDetails()
    {
        $lan = $this->getlanguage();

        $order_id = $this->input->post('order_id', TRUE);

        $bookingOrders['details'] = $this->Api_model->getBookingDetails($order_id);
        $bookingOrders['items'] = $this->Api_model->getBookingItems($order_id);

        if ($bookingOrders) {
            $this->api->api_message_data(1, $this->translate('All_Booking_Orders', $lan), 'data', $bookingOrders);

        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }

    }


    public function getLastUnPaidInvoice()
    {
        $lan = $this->getlanguage();

        $user_id = $this->input->post('user_id', TRUE);

        $conditions['user_id'] = $user_id;
        $conditions['flag'] = 0;

        $invoices = $this->Api_model->getSingleRow('booking_invoice', $conditions);

        if ($invoices) {
            $this->api->api_message_data(1, $this->translate('ALL_DATA', $lan), 'data', $invoices);

        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }

    }

    /*
	** Get Artist Wallet
	*/
    public function getArtistWallet()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);

        $condition = ['artist_id' => $artist_id];

        $artist_wallet = $this->Api_model->getSingleRow('artist_wallet', $condition);

        if ($artist_wallet) {
            $this->api->api_message_data(1, $this->translate('ALL_DATA', $lan), 'data', $artist_wallet);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*
	** Get Artist Wallet Transactions
	*/
    public function getArtistWalletTransactions()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $limit = $this->input->post('limit', TRUE);

        $condition = ['artist_wallet_transactions.artist_id' => $artist_id];
        $select = 'artist_wallet_transactions.amount,artist_wallet_transaction_types.name_' . $lan . ' name';

        $artist_wallet_transactions = $this->Api_model->getArtistWalletTransactions($condition, $select, $limit);

        if ($artist_wallet_transactions) {
            $this->api->api_message_data(1, $this->translate('ALL_DATA', $lan), 'data', $artist_wallet_transactions);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*
	** Add Artist Wallet Transfer Cash Request
	*/
    public function addArtistWalletTransferCashRequest()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $amount = $this->input->post('amount', TRUE);

        $condition = ['artist_id' => $artist_id];

        $artist_wallet = $this->Api_model->getSingleRow('artist_wallet', $condition);

        if ($artist_wallet) {
            if ($amount <= $artist_wallet->amount) {
                $artist = $this->Api_model->getSingleRow('artist', ['id' => $artist_id]);
                $getId = $this->Api_model->insertGetId('artist_wallet_transfer_cash_requests', ['artist_id' => $artist_id, 'amount' => $amount, 'status' => 0, 'created_by' => $artist->user_id, 'created_at' => date("Y-m-d H:i:s")]);
                if ($getId) {
                    $this->api->api_message(1, $this->translate('RECORD_ADDED_SUCCESSFULLY', $lan));
                } else {
                    $this->api->api_message(0, $this->translate('SOMTHING_WRONG_HAPPENED', $lan));
                }
            } else {
                $this->api->api_message(0, $this->translate('AMOUNT_LARGER_THAN_WALLET', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('NO_ARTIST_WALLET_RECORD', $lan));
        }
    }

    /*
	** Get Artist Points
	*/
    public function getArtistLoyaltyPoints()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);

        $condition = ['artist_id' => $artist_id];

        $artist_points = $this->Api_model->getArtistPoints($artist_id);

        if ($artist_points) {
            $this->api->api_message_data(1, $this->translate('ALL_DATA', $lan), 'data', $artist_points);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }

    /*
	** Get Artist Points Transactions
	*/
    public function getArtistLoyaltyPointTransactions()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $limit = $this->input->post('limit', TRUE);

        $condition = ['artist_point_transactions.artist_id' => $artist_id];
        $select = 'artist_point_transactions.points,artist_point_transaction_types.name_' . $lan . ' name';

        $artist_point_transactions = $this->Api_model->getArtistPointTransactions($condition, $select, $limit);

        if ($artist_point_transactions) {
            $this->api->api_message_data(1, $this->translate('ALL_DATA', $lan), 'data', $artist_point_transactions);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }
	
	/*
	** Get Country Loyalty Point Rewards
	*/
    public function getCountryLoyaltyPointRewards()
    {
        $lan = $this->getlanguage();
        $country_id = $this->input->post('country_id', TRUE);

        //$condition = ['artist_id' => $artist_id];

        $point_rewards = $this->Api_model->getPointRewardsBYCountry($country_id,$lan);

        if ($point_rewards) {
            $this->api->api_message_data(1, $this->translate('ALL_DATA', $lan), 'data', $point_rewards);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }
	
	/*
	** Redeem Artist Loyalty Points
	*/
    public function redeemArtistLoyaltyPoints()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $point_reward_id = $this->input->post('point_reward_id', TRUE);

        $artist = $this->Api_model->getSingleRow('artist', ['id'=>$artist_id]);
		$artist_points = $this->Api_model->getSingleRow('artist_points', ['artist_id'=>$artist_id]);
		$point_reward = $this->Api_model->getSingleRow('point_rewards', ['id'=>$point_reward_id]);

        if ($artist_points) {
            if ($point_reward->points_count <= $artist_points->points) {
				$this->Api_model->updateSingleRow('artist_points',['id'=>$artist_points->id],['points'=>$artist_points->points - $point_reward->points_count,'updated_at'=>date("Y-m-d H:i:s")]);
				$artist_wallet = $this->Api_model->getSingleRow('artist_wallet', ['artist_id'=>$artist_id]);
				if($artist_wallet) {
					$this->Api_model->updateSingleRow('artist_wallet',['id'=>$artist_wallet->id],['amount'=>$artist_wallet->amount + $point_reward->rewarded_balance]);
				} else {
					$this->Api_model->insertGetId('artist_wallet', ['artist_id'=>$artist_id, 'amount'=>$point_reward->rewarded_balance]);
				}
				
				$this->Api_model->insertGetId('artist_wallet_transactions', ['artist_id' => $artist_id, 'artist_wallet_transaction_types_id' => 2, 'amount'=>$point_reward->rewarded_balance, 'created_by' => $artist->user_id, 'created_at' => date("Y-m-d H:i:s")]);
				$this->Api_model->insertGetId('artist_point_transactions', ['artist_id' => $artist_id, 'artist_point_transaction_types_id' => 3, 'points'=>-$point_reward->points_count, 'created_by' => $artist->user_id, 'created_at' => date("Y-m-d H:i:s")]);
                
				$this->api->api_message(0, $this->translate('ACTION_DONE_SUCCESSFULLY', $lan));
            } else {
                $this->api->api_message(0, $this->translate('REWARD_LARGER_THAN_ARTIST_POINTS', $lan));
            }
        } else {
            $this->api->api_message(0, $this->translate('NO_ARTIST_POINTS_RECORD', $lan));
        }
    }


    public function uploadimg($inputfilename, $image_director, $newname)
    {
        $file_extn = pathinfo($_FILES[$inputfilename]['name'], PATHINFO_EXTENSION);
        if ($file_extn != "jpg" && $file_extn != "png" && $file_extn != "jpeg" && $file_extn != "gif") {
            return 0;
        }

        if (!is_dir($image_director)) $create_image_director = mkdir($image_director);
        $name = $newname . '.' . $file_extn;
        if (move_uploaded_file($_FILES[$inputfilename]["tmp_name"], $image_director . $name)) return $image_director . $name;
        else return 0 ;
    }


    public function getArtistProfit()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $from = $this->input->post('from_date', TRUE);
        $to = $this->input->post('to_date', TRUE);


        $artist_wallet_transactions['profit'] = $this->Api_model->getArtistProfit($artist_id, strtotime($from), strtotime($to));
        $artist_wallet_transactions['currency'] = $this->Api_model->getArtistCurrency($artist_id);


        if ($artist_wallet_transactions) {
            $this->api->api_message_data(1, $this->translate('ALL_DATA', $lan), 'data', $artist_wallet_transactions);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }
    }


    public function getArtistPendingBookings()
    {
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $limit = $this->input->post('limit', TRUE);

        $bookings = $this->Api_model->getPendingOrdersForArtist($artist_id, $limit);

        if ($bookings) {
            $this->api->api_message_data(1, $this->translate('ALL_DATA', $lan), 'data', $bookings);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }


    }


    public function assignArtistToBookingOrder()
    {
        $lan = $this->getlanguage();
        $booking_id = $this->input->post('booking_id', TRUE);
        $artist_id = $this->input->post('artist_id', TRUE);

        $checkArtist = $this->Api_model->getSingleRow(ART_TBL, array('id' => $artist_id));
        $getBooking = $this->Api_model->getSingleRow('artist_booking', array('id' => $booking_id, 'status_id' => 0));

        if (!$getBooking || !$checkArtist) {
            return $this->api->api_message(0, $this->translate('INVALID_REQUEST', $lan));
        }

        $data['artist_id'] = $artist_id;
        $data['status_id'] = 1;
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $checkArtist->user_id;

        $updateBookingPrice = $this->Api_model->updateBookingPrice($booking_id);


        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(FALSE); # See Note 01. If you wish can remove as well


        $updateBooking = $this->Api_model->updateSingleRow('artist_booking', array('id' => $booking_id), $data);

        $logdata['booking_order_id'] = $booking_id;
        $logdata['log_type_id'] = 2;
        $logdata['status_id'] = $data['status_id'];
        $logdata['details'] = "";
        $logdata['created_by'] = $checkArtist->user_id;
        $bookingOrder = $this->Api_model->insertGetId("booking_orders_logs", $logdata);

        $logdata['booking_order_id'] = $booking_id;
        $logdata['log_type_id'] = 4;
        $logdata['status_id'] = $data['status_id'];
        $logdata['details'] = $checkArtist->name;
        $logdata['created_by'] = $checkArtist->user_id;
        $bookingOrder = $this->Api_model->insertGetId("booking_orders_logs", $logdata);


        $checkUser = $this->Api_model->getSingleRow('user', array('user_id' => $checkArtist->user_id));
        $msg = $checkUser->name . ': accepted your appointment.';
        $this->firebase_notification($getBooking->user_id, "Accept Appointment", $msg, ACCEPT_BOOKING_ARTIST_NOTIFICATION);

        $this->db->trans_complete(); # Completing transaction

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            return $this->api->api_message(0, $this->translate('FAILED', $lan));

        } else {
            # Everything is Perfect.
            # Committing data to the database.
            $this->db->trans_commit();
            return $this->api->api_message(1, $this->translate('JOB_APPLIED_SUCCESSFULLY', $lan));


        }


    }

    public function getBookingOrderDetails (){
        $lan = $this->getlanguage();
        $booking_id = $this->input->post('booking_id', TRUE);
        $artist_id = $this->input->post('artist_id', TRUE);

        $getBooking = $this->Api_model->getSingleRow('artist_booking', array('id' => $booking_id, 'artist_id' => $artist_id));

        if (!$getBooking) {
            return $this->api->api_message(0, $this->translate('INVALID_REQUEST', $lan));
        }

        $bookingRecord = $this->Api_model->getBookingDetails($booking_id);
        $bookingItems = $this->Api_model->getBookingItems($booking_id);

        $data['booking']=$bookingRecord;
        $data['booking_items']=$bookingItems;

        $this->api->api_message_data(1, $this->translate('ALL_DATA', $lan), 'data', $data);



    }

    public function getArtistOrders(){
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $limit = $this->input->post('limit', TRUE);

        $bookings = $this->Api_model->getArtistOrders($artist_id, $limit);

        if ($bookings) {
            $this->api->api_message_data(1, $this->translate('ALL_DATA', $lan), 'data', $bookings);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }

    }

    public function addServiceToOrder(){
        $lan = $this->getlanguage();
        $artist_id = $this->input->post('artist_id', TRUE);
        $category_id= $this->input->post('category_id', TRUE);
        $quantity= $this->input->post('quantity', TRUE);
        $cost_per_item= $this->input->post('cost_per_item', TRUE);
        $order_id= $this->input->post('order_id', TRUE);

        $booking = $this->Api_model->getSingleRow('artist_booking', array('id' => $order_id, 'artist_id' => $artist_id));
        $category = $this->Api_model->getSingleRow('category', array('id' => $category_id, 'parent_id >' => 0));

        if ($booking && $category) {


            $data['booking_order_id']= $order_id;
            $data['category_id ']= $category_id;
            $data['quantity']= $quantity;
            $data['cost_per_item']= $cost_per_item;
            $data['created_by']= $artist_id;



            $booking_item = $this->Api_model->insertGetId('booking_order_items', $data);

            $updateBookingPrice = $this->Api_model->updateBookingPrice($order_id);

            $bookingRecord = $this->Api_model->getBookingDetails($order_id);
            $bookingItems = $this->Api_model->getBookingItems($order_id);

            $bookingData['booking']=$bookingRecord;
            $bookingData['booking_items']=$bookingItems;


            $this->api->api_message_data(1, $this->translate('ALL_DATA', $lan), 'data', $bookingData);
        } else {
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
        }

    }


    public function editProfileData()
    {

//        print_r($_POST);
//        die;
        $lan = $this->getlanguage();
        $table = USR_TBL;
        $name = $this->input->post('name', TRUE);
        $email_id = $this->input->post('email_id', TRUE);
        $mobile = $this->input->post('mobile', TRUE);
        $password = $this->input->post('password', TRUE);
        $device_id = $this->input->post('device_id', TRUE);
        $device_token = $this->input->post('device_token', TRUE);
        $device_type = $this->input->post('device_type', TRUE);
        $use_code = $this->input->post('referral_code', TRUE);
        $phonecode = $this->input->post('phoneCode', TRUE);
        $country_id = $this->input->post('country_id', TRUE);
        $city_id = $this->input->post('city_id', TRUE);
        $iban = $this->input->post('iban', TRUE);

        $nationality = $this->input->post('nationality', TRUE);
        $company = $this->input->post('company', TRUE);
        $commercial = $this->input->post('commercial', TRUE);
        $identity = $this->input->post('identity', TRUE);
        $authorization = $this->input->post('authorization', TRUE);
        $services = $this->input->post('services', TRUE);
        $user_id = $this->input->post('user_id', TRUE);

        $get_mobile_user = '';

        $userRecord = $this->Api_model->getSingleRow('user', array('user_id' => $user_id));

        if(!$userRecord){
            $this->api->api_message(0, $this->translate('NO_DATA', $lan));
            exit();
        }

        $userRole = $userRecord->role;

        if (empty($country_id) || empty($city_id)) {
            $this->api->api_message(0, $this->translate('LOCATION_REQUIRED', $lan));
            exit();
        }

        if (!empty($mobile)) {
            $get_mobile_user = $this->Api_model->getSingleRow($table, array('mobile' => $mobile, 'user_id !=' =>$user_id));
            if (!empty($get_mobile_user)) {
                $this->api->api_message(0, $this->translate('MOBILE_EXIST', $lan));
                exit();
            }
        } else {
            $this->api->api_message(0, $this->translate('EMPTY_MOBILE', $lan));
            exit();
        }
        if ($use_code) {
            $getCode = $this->Api_model->getSingleRow(USR_TBL, array('referral_code' => $use_code));
            if (!$getCode) {
                $this->api->api_message(0, $this->translate('ENTER_VALID_COUPON_CODE', $lan));
                exit();
            }
        }

        if ($userRole == 1) {
            $userStatus = 1;
            $approval_status = 0;
        } else {
            $userStatus = 1;
            $approval_status = 1;
        }

        $created_at = time();
        $updated_at = time();
        $condition = array('email_id' => $email_id);
        $columnName = 'email_id';
        $referral_code = $this->api->random_num(6);
        if ($use_code) {
            $data = array('name' => $name, 'email_id' => $email_id, 'country_id' => $country_id, 'city_id' => $city_id, 'mobile' => $mobile, 'password' => md5($password), 'role' => $userRole, 'status' => $userStatus, 'created_at' => $created_at, 'updated_at' => $updated_at, 'referral_code' => $referral_code, 'approval_status' => $approval_status, 'user_referral_code' => $use_code, 'device_token' => $device_token, 'device_id' => $device_id, 'device_type' => $device_type, 'phonecode' => $phonecode);
        } else {
            $data = array('name' => $name, 'email_id' => $email_id, 'country_id' => $country_id, 'city_id' => $city_id, 'mobile' => $mobile, 'password' => md5($password), 'role' => $userRole, 'status' => $userStatus, 'created_at' => $created_at, 'updated_at' => $updated_at, 'referral_code' => $referral_code, 'approval_status' => $approval_status, 'device_token' => $device_token, 'device_id' => $device_id, 'device_type' => $device_type, 'phonecode' => $phonecode);
        }
            if ($userRole == 1) {
                if (!empty($email_id)) {
                    $get_user = $this->Api_model->getSingleRow($table, array('email_id' => $email_id, 'user_id !=' =>$user_id));
                    if ($get_user) {
                        $this->api->api_message(0, $this->translate('EMAIL_EXIST', $lan));
                        exit();
                    }
                }
            } else {
                if (!empty($email_id)) {
                    $get_user = $this->Api_model->getSingleRow($table, array('email_id' => $email_id, 'user_id !=' =>$user_id));
                    if ($get_user) {
                        $this->api->api_message(0, $this->translate('EMAIL_EXIST', $lan));
                        exit();
                    }
                }
            }
            $updateRecord = $this->Api_model->updateSingleRow($table, array('user_id' =>$user_id) ,$data);
            if ($updateRecord) {


                $datatag = 'data';
                $get_user['user_data'] = $this->Api_model->getSingleRow($table, array('user_id' => $user_id));
                unset($get_user['user_data']->password);

                // handle artist logic
                if ($userRole == 1) {

                    $get_artist = $this->Api_model->getSingleRow('artist', array('user_id' =>$user_id));


                    $data1['user_id'] = $user_id;
                    $data1['name'] = $name;
                    $data1['company'] = $company;
                    $data1['iban'] = $iban;
                    $data1['nationality'] = $nationality;
                    $data1['category_id'] = implode(',', $services);
                    $data1['created_at'] = time();
                    $data1['updated_at'] = time();

                    $updateArtistRecord = $this->Api_model->updateSingleRow('artist', array('id' =>$get_artist->id) ,$data1);

                    if ($updateArtistRecord) {
                        $this->Api_model->deleteRecord(array('artist_id'=> $get_artist->id), 'artist_category');

                        foreach ($services as $service) {
                            $this->Api_model->insertGetId('artist_category', array('artist_id' => $get_artist->id, 'category_id' => $service));
                        }
                    }

                    if (isset($_FILES['commercial'])) {
                        $commercial = $this->uploadimg('commercial', 'images/commercials/', mt_rand());
//                      print_r($commercial);
//                      die;
                        if ($commercial) {
                            $this->Api_model->deleteRecord(array('artist_id' => $get_artist->id, 'attachment_type_id' => 1), 'attachments');
                            $this->Api_model->insertGetId('attachments', array('artist_id' => $get_artist->id, 'attachment_type_id' => 1, 'attachment' => $commercial));

                        }
                    }

                    if (isset($_FILES['identity'])) {
                        $identity = $this->uploadimg('identity', 'images/identitys/', mt_rand());
                        if ($identity) {
                            $this->Api_model->deleteRecord(array('artist_id' => $get_artist->id, 'attachment_type_id' => 2), 'attachments');
                            $this->Api_model->insertGetId('attachments', array('artist_id' => $get_artist->id, 'attachment_type_id' => 2, 'attachment' => $identity));
                        }
                    }

                    if (isset($_FILES['authorization'])) {
                        $authorization = $this->uploadimg('authorization', 'images/authorizations/', mt_rand());
                        if ($authorization) {
                            $this->Api_model->deleteRecord(array('artist_id' => $get_artist->id, 'attachment_type_id' => 3), 'attachments');
                            $this->Api_model->insertGetId('attachments', array('artist_id' => $get_artist->id, 'attachment_type_id' => 3, 'attachment' => $authorization));
                        }
                    }

                    $get_user['attachmnet'] = $this->Api_model->getArtistAttachs($get_artist->id);

                }


                $this->api->api_message_data(1, $this->translate('USERRAGISTER', $lan), $datatag, $get_user);
            } else {
                $this->api->api_message(0, $this->translate('TRY_AGAIN', $lan));
            }

    }
}
