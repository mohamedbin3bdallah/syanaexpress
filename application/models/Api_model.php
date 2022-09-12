<?php
    /**
    * 
    */
    class Api_model extends CI_Model
    {
        
        function __construct()
        {
            parent:: __construct();
        }

function getUserDetails($role){
 
    $response = array();
    // Select record
    $this->db->select('name,email_id,address,office_address,mobile,gender,city');
    $this->db->where('role=', $role);
    $q = $this->db->get('user');
    $response = $q->result_array();
    return $response;
  }
  
        public function getAllDataColumn($table, $columnName,$where)
        {
            $this->db->where($where);
            $this->db->select($columnName);
            $this->db->distinct();
            $query = $this->db->get($table);
            return $query->result();
        }

        public function getFilterData($table,$Data,$where)
        {
            if (!empty($Data))
             {
                foreach ($Data as $key => $value)
                {
                  if ($value) 
                  {
                    $this->db->where_in($key, $value);
                  }
                }
            }

            $this -> db -> where($where);
            $this->db->select("*");
            $this->db->distinct();
            $query = $this->db->get($table);
            return $query->result();
        }
        
        /*Get single row data*/
        public function getSingleRow($table, $condition)
        {
            $this->db->select('*');
            $this->db->from($table);
            $this->db->where($condition);
            $query = $this->db->get();
            return $query->row();       
        }

         /*Get single row data*/
        public function getSingleRowOrderBy($table, $condition)
        {
            $this->db->select('*');
            $this->db->from($table);
            $this->db->where($condition);
            $this->db->order_by('id', 'desc'); 
            $query = $this->db->get();
            return $query->row();       
        }

        /*Get All data with where clause*/
        public function getAllDataWhereLimit($where, $table,$page)
        {
            $this->db->where($where);
            $this->db->select("*");
            $this->db->from($table);
            $query = $this->db->get();          
            return $query->result();
        }
        
        public function getAllDataWhereLimitNew($where, $table,$page)
        {
            $this->db->where($where);
            $this->db->select("*");
            $this->db->from($table);
            $this->db->order_by('featured', 'asc'); 
            $query = $this->db->get();          
            return $query->result();
        }

        public function getAllCatWhereLimit($cat,$where,$table,$page)
        {
            $sql = "SELECT * FROM $table where (FIND_IN_SET($cat,category_id)) and (booking_flag = 0) and (is_online = 1) and (update_profile =1) order by created_at DESC";
            $query = $this->db->query($sql);  
            return $query->result();

            //$this->db->where_in("FIND_IN_SET('$cat',category_id) !=",$where);
            //$this->db->where($where);
            //$this->db->select("*");
            //$this->db->from($table);
            //$query = $this->db->get();          
            //return $query->result();
        }

         /*Get single row data*/
        public function getSingleRowCloumn($columnName,$table, $condition)
        {
            $this->db->select($columnName);
            $this->db->from($table);
            $this->db->where($condition);
            $query = $this->db->get();
            return $query->row();       
        }

        /*Insert and get last Id*/
        public function insertGetId($table,$data)
        {
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        }

        /*Check existing record*/
        function checkData($table, $condition, $columnName)
        {
            $this->db->select($columnName);
            $this->db->from($table);
            $this->db->like($condition);
            return $this->db->count_all_results();
        }   

         /*Get All data with where clause*/
        public function getAllDataDistinct($table)
        {   
            $this->db->distinct('user_id');
            $this->db->select('user_id');
            $this->db->from($table);
            $query = $this->db->get();          
            return $query->result();
        }
        
          /*Get no of records*/
        function getCountWhere($table, $condition)
        {
            $this->db->select("*");
            $this->db->from($table);
            $this->db->where($condition);
            return $this->db->count_all_results();
        }   

        /*Check existing record*/
        function getCount($table, $condition)
        {
            $this->db->select("*");
            $this->db->from($table);
            $this->db->where($condition);
            return $this->db->count_all_results();
        }   

         /*Get no of records*/
        function getMontlyUserCount()
        {
           $sql="SELECT  DATE_FORMAT(FROM_UNIXTIME(created_at), '%b') as month, Count(*) as count FROM user WHERE FROM_UNIXTIME(created_at) >= CURDATE() - INTERVAL 1 YEAR GROUP BY Month(FROM_UNIXTIME(created_at))";  
            $query = $this->db->query($sql);
            return $query->result_array();
        }  

        /*Get no of records*/
        function getMontlyRevenue()
        {
           $sql="SELECT  DATE_FORMAT(FROM_UNIXTIME(created_at), '%b') as month, sum(total_amount) as count FROM booking_invoice WHERE FROM_UNIXTIME(created_at) >= CURDATE() - INTERVAL 1 YEAR GROUP BY Month(FROM_UNIXTIME(created_at))";  
            $query = $this->db->query($sql);
            return $query->result_array();
        }  
        
         /*Get no of records*/
        function getMontlyRevenue1($user_id)
        {
           $sql="SELECT  DATE_FORMAT(FROM_UNIXTIME(created_at), '%d') as day, sum(total_amount) as count FROM booking_invoice WHERE artist_id = $user_id AND   FROM_UNIXTIME(created_at) >= CURDATE() - INTERVAL 1 Month GROUP BY DAY(FROM_UNIXTIME(created_at))";
            $query = $this->db->query($sql);
            return $query->result_array();
        }  
        
        public function getWeekSum($columnName,$where,$table)
        {   
            $this->db->select_sum($columnName);
            $this ->db -> where($where);
            $this->db->where('created_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()');
            $this ->db -> from($table);
            $query = $this->db -> get();
            return $query -> result();
        }
        
        function getCountAll($table)
        {
            $this->db->select("*");
            $this->db->from($table);
            return $this->db->count_all_results();
        }

         /*Get All data with Limit*/
        public function getAllDataLimit($table, $limit)
        {
            $this->db->select("*");
            $this->db->from($table);
            $this->db->order_by('id', 'desc');
            $this->db->limit($limit);
            $query = $this->db->get();          
            return $query->result();
        }

         /*Get All data with Limit*/
        public function getAllDataLimitWhere($table, $where, $limit)
        {
            $this->db->select("*");
            $this->db->from($table);
            $this->db->where($where);
            $this->db->order_by('id', 'desc');
            $this->db->limit($limit);
            $query = $this->db->get();          
            return $query->result();
        }

        /*Update any data*/
         public function updateSingleRow($table, $where, $data)
        {
            $this->db->where($where);
            $this->db->update($table, $data);
            if ($this->db->affected_rows() > 0)
            {
              return TRUE;
            }
            else
            {
              return FALSE;
            }
        }

        public function updateWhereIn($where, $where_in,$table,$data)
        {
            $this->db->where($where);
            $this->db->where_in('status', $where_in);
            $this->db->update($table, $data);

            if ($this->db->affected_rows() > 0)
            {
              return TRUE;
            }
            else
            {
              return FALSE;
            }
        }

         public function updateJob($table, $where, $status)
        {
            $this->db->where($where);
            $this->db->set('status', $status, FALSE);
            $this->db->update($table);
             if ($this->db->affected_rows() > 0)
            {
             
              return TRUE;
            }
            else
            {
             
              return FALSE;
            }
        }

        /*Add new data*/
        function insert($table,$data)
         {
            if($this->db->insert($table, $data))
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }

         }

          /*Get All data*/
        public function getAllDataNotWhere($table,$status)
        {
            $this->db->select("*");
            $this->db->from($table);
            $this->db->where('status !=', $status);
            $query = $this->db->get();          
            return $query->result();
        }

        /*Get All data*/
        public function getAllData($table)
        {
            $this->db->select("*");
            $this->db->from($table);
            $query = $this->db->get();          
            return $query->result();
        }
        
        public function getAllDataOrderBy($table, $where=1)
        {
            $this->db->where($where);
            $this->db->select("*");
            $this->db->from($table);
            $this->db->order_by('id', "DESC");
            $query = $this->db->get();          
            return $query->result();
        }

        /*Get All data with where clause*/
        public function getAllDataWhere($where, $table)
        {
            $this->db->where($where);
            $this->db->select("*");
            $this->db->from($table);
            $query = $this->db->get();          
            return $query->result();
        }
     public function getUser($user_id) 
      {
     $sql="select * from artist_wallet JOIN user on artist_wallet.artist_id=user.user_id WHERE  artist_wallet.artist_id='".$user_id."'";
     $query = $this->db->query($sql);       
     return $query->result();
     }

public function getUserDetail($user_id) 
      {
            $this->db->where('user_id='.$user_id.'');
            $this->db->select("email_id,mobile");
            $this->db->from('user');
            $query = $this->db->get();         
            return $query->result();
     }
     
        /*Get All data with where clause*/
        public function getAllDataWhereOrderTwo($where, $table)
        {
            $this->db->where($where);
            $this->db->select("*");
            $this->db->from($table);
            $this->db->order_by('flag ASC, created_at DESC'); 
            $query = $this->db->get();         
            return $query->result();
        }

        /*Get All data with where clause*/
        public function getAllDataWhereoderBy($where, $table)
        {
            $this->db->where($where);
            $this->db->select("*");
            $this->db->from($table);
            $this->db->order_by('created_at', "DESC");
            $query = $this->db->get();          
            return $query->result();
        }

        public function getAllDataWhereoderByJob($where, $table)
        {
            $this->db->where($where);
            $this->db->select("*");
            $this->db->from($table);
            $this->db->order_by('job_timestamp', "DESC");
            $query = $this->db->get();          
            return $query->result();
        }

        public function getAllCategoryByArtist($category)
        {
            
            $sql="SELECT * FROM `category` where status = 1 and id IN ($category) order by created_at DESC;";
            
            $query = $this->db->query($sql);
                  
            return $query->result();
        }

        public function getAllJobNotAppliedByArtist($artist_id,$category_id,$tag='all')
        {
            
            if($tag==1)
            {
                  $sql="SELECT * FROM `post_job` where status = 0 and category_id IN ($category_id) and job_date=CURDATE()and  job_id not in ( select job_id from applied_job where artist_id=$artist_id) order by created_at DESC;";
            }
            else if($tag==2)
            {
               
             $sql="SELECT * FROM `post_job` where status = 0 and category_id IN ($category_id) and job_date=CURDATE()+1 and job_id not in ( select job_id from applied_job where artist_id=$artist_id) order by created_at DESC;";
            }
            else if($tag==3)
            {
               
                  $sql="SELECT * FROM `post_job` where status = 0 and category_id IN ($category_id) and job_date>CURDATE()+1  and  job_id not in ( select job_id from applied_job where artist_id=$artist_id) order by created_at DESC;";
            }
            else
            {

               $sql="SELECT * FROM `post_job` where status = 0 and category_id IN ($category_id) and job_id not in ( select job_id from applied_job where artist_id=$artist_id) order by created_at DESC;";  
            }
            $query = $this->db->query($sql);
                  
            return $query->result();
        }
         /*Get All data with where clause*/
        public function getAllDataWhereAndOr($where, $whereOr, $table)
        {
            $this->db->where($where);
            $this->db->or_where($whereOr);
            $this->db->select("*");
            $this->db->from($table);
            $this->db->order_by("created_at", "desc");
            $query = $this->db->get();        
              
            return $query->result();
        }

        /*Get All data with where clause*/
        public function getAllDataWhereDistinct($where, $table)
        {   
            $this->db->distinct('user_id');
            $this->db->where($where);
            $this->db->select("user_id");
            $this->db->from($table);
            $query = $this->db->get();         
            return $query->result();
        }

         /*Get All data with where clause*/
        public function getAllDataWhereDistinctArtist($where, $table)
        {   
            $this->db->distinct('artist_id');
            $this->db->where($where);
            $this->db->select("artist_id");
            $this->db->from($table);
            $query = $this->db->get();         
            return $query->result();
        }

         // Count avarage 
        public function getAvgWhere($columnName, $table,$where)
        {
            $this->db->select_avg($columnName);
            $this->db->from($table);
            $this->db->where($where);
            $query =$this->db->get(); 
            return $query->result(); 
        }

         // Count avarage 
        public function getTotalWhere($table,$where)
        {
            $this->db->from($table);
            $this->db->where($where);
            $query =$this->db->get(); 
            return $query->num_rows(); 
        }

         // get sum 
        public function getSum($columnName, $table)
        {
            $this->db->select_sum($columnName);
            $this->db->from($table);
            $query =$this->db->get(); 
            return $query->row(); 
        }

        // get sum 
        public function getSumWhere($columnName, $table, $where)
        {
            $this->db->select_sum($columnName);
            $this->db->from($table);
            $this->db->where($where);
            $query =$this->db->get(); 
            return $query->row(); 
        }

                // get sum 
        public function getSumWhereIn($columnName, $table,$where, $where_in)
        {
            $this->db->select_sum($columnName);
            $this->db->from($table);
            $this->db->where($where);
            $this->db->where_in('payment_type', $where_in);
            $query =$this->db->get(); 
            return $query->row(); 
        }


        public function deleteRecord($where, $table)
        {
            $this->db-> where($where);
            $query = $this->db->delete($table);  
        } 
        
        public function getNearestData($lat,$lng,$table,$user_id)
        {
            $this->db->select("*, ( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance");
            $this->db->from($table); 
            $this->db->where('user_id !=', $user_id);
            $this->db->having('distance <= ', '1');                    
            $this->db->order_by('distance');                    
            $this->db->limit(1, 0);
            $query =$this->db->get(); 
            return $query->row(); 
        }

        public function getNearestDataWhere($lat,$lng,$table,$where,$user_id,$distance)
        {
            $this->db->select("*, ( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance");
            $this->db->from($table); 
            $this->db->where($where);
            $this->db->where('user_id !=', $user_id);
            $this->db->having('distance <= ', $distance);                    
            $this->db->order_by('distance');                    
            $this->db->limit(1, 0);
            $query =$this->db->get(); 
            return $query->row(); 
        }

        public function getNearestDataWhereResult($lat,$lng,$table,$where,$user_id,$distance)
        {
            $this->db->select("*, ( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance");
            $this->db->from($table); 
            $this->db->where($where);
            $this->db->where('user_id !=', $user_id);
            $this->db->having('distance <= ', $distance);                    
            $this->db->order_by('distance');                    
            $this->db->limit(1, 0);
            $query =$this->db->get(); 
            return $query->result(); 
        }

        public function getNearestDataResult($lat,$lng,$table,$user_id,$distance)
        {
            $this->db->select("*, ( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance");
            $this->db->from($table); 
            $this->db->where('user_id !=', $user_id);
            $this->db->having('distance <= ', $distance);                    
            $this->db->order_by('distance');                    
            $this->db->limit(1, 0);
            $query =$this->db->get(); 
            return $query->result(); 
        }

        public function getNearestDataWhereResultFiltter($lat,$lng,$table,$where,$user_id,$distance)
        {
            $this->db->select("*, ( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance");
            $this->db->from($table); 
            $this->db->where($where);
            $this->db->where('user_id !=', $user_id);
            $this->db->having('distance <= ', $distance);                    
            $this->db->order_by('distance');                    
            $query =$this->db->get(); 
            return $query->result(); 
        }

        public function getNearestDataResultFiltter($lat,$lng,$table,$user_id,$distance)
        {
            $this->db->select("*, ( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance");
            $this->db->from($table); 
            $this->db->where('user_id !=', $user_id);
            $this->db->having('distance <= ', $distance);                    
            $this->db->order_by('distance');                    
            $query =$this->db->get(); 
            return $query->result(); 
        }

        public function getWhereInStatus($table,$where,$columnName, $where_in)
        {
            $this->db->select('*');
            $this->db->from($table);
            $this->db->where($where);
            $this->db->where_in($columnName, $where_in);
            $this->db->order_by('id', 'desc');
            $query =$this->db->get(); 
            return $query->row();
        }

        public function getWhereInStatusResult($table,$where,$columnName, $where_in)
        {
            $this->db->select('*');
            $this->db->from($table);
            $this->db->where($where);
            $this->db->where_in($columnName, $where_in);
            $this->db->order_by('id', 'desc');
            $query =$this->db->get(); 
            return $query->result();
        }

         public function getWhereInStatusResultJob($table,$where,$columnName, $where_in)
        {
            $this->db->select('*');
            $this->db->from($table);
            $this->db->where($where);
            $this->db->where_in($columnName, $where_in);
            $this->db->order_by('aj_id', 'desc');
            $query =$this->db->get(); 
            return $query->result();
        }

         public function check_applied_job($artist_id, $job_id)
        {
            $this->db-> where('artist_id', $artist_id);
            $this->db-> where('job_id', $job_id);
            $this->db->select("*");
            $this->db->from('applied_job');
            $query = $this->db->get();
            return $query->result();   
        }


    public function add_favorites($data)
    {
        $str = $this->db->insert('favourite', $data);   
    }

    public function get_favorites($user_id)
    {   
        $this->db->where('user_id',$user_id);
        $this->db->select("*");
        $this->db->from('favourite');
        $query = $this->db->get();            
        return $query->result();
    }

    public function check_favorites($user_id,$artist_id)
    {   
        $this->db-> where('artist_id', $artist_id);
        $this->db-> where('user_id', $user_id);
        $this->db->select("*");
        $this->db->from('favourite');
        $query = $this->db->get();            
        return $query->result();
    } 

    public function remove_favorites($user_id, $artist_id)
    {
        $this->db-> where('artist_id', $artist_id);
        $this->db-> where('user_id', $user_id);
        $query = $this->db->delete('favourite');
    }
    
    function get_list_of_directories_and_files($dir = APPPATH, &$results = array())
                    {
                        $files = scandir($dir);
                        foreach ($files as $key => $value) {
                            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
                            if (!is_dir($path)) {
                                $results[] = $path;
                            } else if ($value != "." && $value != "..") {
                                $this->get_list_of_directories_and_files($path, $results);
                                $results[] = $path;
                            }
                        }
                        return $results;
                    }
                    
    function get_list_of_language_files($dir = APPPATH . '/language', &$results = array())
                    {
                        $files = scandir($dir);
                        foreach ($files as $key => $value) {
                            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
                            if (!is_dir($path)) {
                                $results[] = $path;
                            } else if ($value != "." && $value != "..") {
                                $this->get_list_of_directories_and_files($path, $results);
                                $results[] = $path;
                            }
                        }
                        return $results;
                    }
                    
    function get_all_languages()
                    {
                        $language_files = array();
                        $all_files = $this->get_list_of_language_files();
                        foreach ($all_files as $file) {
                            $info = pathinfo($file);
                            if (isset($info['extension']) && strtolower($info['extension']) == 'json') {
                                $file_name = explode('.json', $info['basename']);
                                array_push($language_files, $file_name[0]);
                            }
                        }
                        return $language_files;
                    }


        function getAllCategoriesByArtistCity($cityId, $parent_id=0)
        {
            if($parent_id > 0){
                $sql="select * from category 
                    WHERE parent_id='".$parent_id."' and status=1";
            }else{
                $sql="select distinct category.* from category 
                    JOIN artist_category on artist_category.category_id=category.id 
                    JOIN artist on artist_category.artist_id=artist.id 
                    JOIN user on artist.user_id=user.user_id 
                    WHERE  user.city_id='".$cityId."' and category.parent_id='".$parent_id."' and category.status=1";
            }

            $query = $this->db->query($sql);
            return $query->result();
        }


        function updateExistingArtistCategories(){
            $sql = "select id, category_id from artist";
            $query = $this->db->query($sql);
            $artists = $query->result();

            foreach ($artists as $artist){
                $categories = explode(',',$artist->category_id);
                foreach($categories as $category){

                    $data['artist_id']= $artist->id;
                    $data['category_id']= $category;
                    $str = $this->db->insert('artist_category', $data);

                }

            }
        }

        function getBookingOrders($userId=0){
            $where='';
            if($userId >0){
                $where = 'where b.user_id = '.$userId;
            }
            $sql = "select b.*, ua.name as artistName, ua.email_id as artistEmail, ua.mobile as artistMobile, u.name as userName, u.email_id as userEmail, u.mobile as userMobile,s.name_ar, s.name as statusName, i.flag as Paid, i.invoice_id, i.total_amount as invoice_amount, c.cat_name, c.cat_name_ar
                    from artist_booking b 
                    left join  artist a on a.id=b.artist_id
                    left join  category c on c.id=b.category_id
                    left join  user ua on ua.user_id=a.user_id
                    left join  booking_invoice i on i.booking_id=b.id
                    left join user u on u.user_id = b.user_id
                    left join booking_order_status s on s.id=b.status_id
                    $where
                    ";
            $query = $this->db->query($sql);
            $orders = $query->result();
            $allOrders = [];
            $i=0;
            foreach ($orders as $order){
                $allOrders[$i]['order']=$order;

                $sql = "select i.*, c.cat_name, c.cat_name_ar from booking_order_items i
                        inner join category c on c.id=i.category_id
                        where booking_order_id='$order->id'";
                $query = $this->db->query($sql);
                $orderItems = $query->result();
                $allOrders[$i]['items']=$orderItems;

                $i++;

            }

            return $allOrders;
        }

        public function getArtistByBookingCity($bookingId){
            $sql = "select t.*, u.name as userName, b.id as bookingId from artist t 
                    inner join user_addresses ua on t.city_id=ua.city_id
                    inner join artist_booking b on b.user_address_id = ua.id
                    inner join  user u on u.user_id=t.user_id
                    where b.id ='$bookingId' ";

            $query = $this->db->query($sql);
            $artists = $query->result();

            return $artists;
        }


        public function getArtistUser($artistId){
            $sql = "select u.* from artist t 
                    inner join  user u on u.user_id=t.user_id
                    where t.id ='$artistId' ";

            $query = $this->db->query($sql);
            $artists = $query->result();

            return $artists;

        }

        public function getUserData($condition){


            $this->db->select('u.*, c.name as country_name, c.name_ar as country_name_ar, s.name as city_name, s.name_ar as city_name_ar');
            $this->db->from('user u');
            $this->db->join('countries c', 'u.country_id=c.id', 'left');
            $this->db->join('cities s', 'u.city_id=s.id', 'left');
            $this->db->where($condition);
            $query = $this->db->get();

            return $query->row();

        }

        public function getBookingDetails($id){
            $this->db->select('b.*, s.name as status_name, s.name_ar as stutus_name_ar, c.cat_name, c.cat_name_ar, p.name as payment_name, p.name_ar as payment_name_ar, p.logo as payment_logo, ua.address,ua.building, ua.floor, ua.apartment, ua.landmark, ci.name as city_name, ci.name_ar as city_name_ar, u.name as cst_name, u.mobile, i.flag as Paid, i.invoice_id, i.total_amount as invoice_amount, u.name as cst_name, ua.city_id as city_id');
            $this->db->from('artist_booking b');
            $this->db->join('category c', 'b.category_id=c.id', 'left');
            $this->db->join('payment_methods p', 'b.payment_method_id=p.id', 'left');
            $this->db->join('user_addresses ua', 'b.user_address_id=ua.id', 'left');
            $this->db->join('cities ci', 'ua.city_id=ci.id', 'left');
            $this->db->join('booking_order_status s', 'b.status_id=s.id', 'left');
            $this->db->join('user u', 'b.user_id=u.user_id', 'left');
            $this->db->join('booking_invoice i', 'i.booking_id=b.id', 'left');
            $this->db->where('b.id', $id);
            $query = $this->db->get();
            $booking = $query->row();
            return $booking;
        }

        public function getBookingItems($id){
            $this->db->select('b.*, c.cat_name, c.cat_name_ar');
            $this->db->from('booking_order_items b');
            $this->db->join('category c', 'b.category_id=c.id', 'left');
            $this->db->where('b.booking_order_id', $id);
            $query = $this->db->get();
            $booking = $query->result();
            return $booking;
        }
		
		public function getArtistAttachs($id){
            $this->db->select('a.*,at.*, concat("'.base_url().'", a.attachment) as url');
            $this->db->from('attachments a');
            $this->db->join('attachment_types at', 'a.attachment_type_id = at.id', 'left');
            $this->db->where('a.artist_id', $id);
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
		
		/*
		** Get Artist Wallet Transactions
		*/
		public function getArtistWalletTransactions($condition,$select,$limit=0){
            $this->db->select($select);
            $this->db->from('artist_wallet_transactions');
            $this->db->join('artist_wallet_transaction_types', 'artist_wallet_transactions.artist_wallet_transaction_types_id = artist_wallet_transaction_types.id', 'left');
            $this->db->where($condition);
			$this->db->order_by('artist_wallet_transactions.created_at', 'desc');
			$this->db->limit($limit);
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
		
		/*
		** Get Artist Transfer Cash Requests
		*/
		public function getArtistTransferCashRequests(){
            $this->db->select('artist.name,user.email_id,artist_wallet.amount wallet,user.mobile,artist_wallet_transfer_cash_requests.id,artist_wallet_transfer_cash_requests.artist_id,artist_wallet_transfer_cash_requests.amount,artist_wallet_transfer_cash_requests.status,artist_wallet_transfer_cash_requests.comment,artist_wallet_transfer_cash_requests.created_at');
            $this->db->from('artist_wallet_transfer_cash_requests');
            $this->db->join('artist', 'artist_wallet_transfer_cash_requests.artist_id = artist.id', 'left');
			$this->db->join('artist_wallet', 'artist.id = artist_wallet.artist_id', 'left');
			$this->db->join('user', 'artist.user_id = user.user_id', 'left');
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
		
		/*
		** Get Artist Points
		*/
		public function getArtistPoints($id){
            $this->db->select('artist.name,user.email_id,artist_points.points,user.mobile');
            $this->db->from('artist_points');
            $this->db->join('artist', 'artist_points.artist_id = artist.id', 'left');
			$this->db->join('user', 'artist.user_id = user.user_id', 'left');
			$this->db->where(['artist_points.artist_id'=>$id]);
            $query = $this->db->get();
            $result = $query->row();
            return $result;
        }
		
		/*
		** Get Artist point Transactions
		*/
		public function getArtistPointTransactions($condition,$select,$limit=0){
            $this->db->select($select);
            $this->db->from('artist_point_transactions');
            $this->db->join('artist_point_transaction_types', 'artist_point_transactions.artist_point_transaction_types_id = artist_point_transaction_types.id', 'left');
            $this->db->where($condition);
			$this->db->order_by('artist_point_transactions.created_at', 'desc');
			$this->db->limit($limit);
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
		
		/*
		** Get Point Rewards
		*/
		public function getPointRewards($lang='en'){
			if($lang == 'en') $this->db->select('point_rewards.*,point_reward_types.name_'.$lang.' pointRewardType,countries.name country');
            else $this->db->select('point_rewards.*,point_reward_types.name_'.$lang.' pointRewardType,countries.name_'.$lang.' country');
            $this->db->from('point_rewards');
            $this->db->join('point_reward_types', 'point_rewards.point_reward_type_id = point_reward_types.id', 'left');
			$this->db->join('countries', 'point_rewards.country_id = countries.id', 'left');
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
		
		/*
		** Get Point Rewards BY Country
		*/
		public function getPointRewardsBYCountry($country,$lang='en'){
			$this->db->select('point_rewards.id, point_rewards.points_count,point_rewards.rewarded_balance,point_reward_types.name_ar, point_reward_types.name_en,cs.currency_symbol, cs.currency_name, cs.currency_name_ar, cs.code as currency_code');
            $this->db->from('point_rewards');
            $this->db->join('point_reward_types', 'point_rewards.point_reward_type_id = point_reward_types.id', 'left');
			$this->db->join('countries', 'point_rewards.country_id = countries.id', 'left');
			$this->db->join('currency_setting cs', 'cs.country_id = countries.id', 'left');
			$this->db->where(['point_rewards.country_id'=>$country,'point_rewards.status'=>1]);
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
		
		/*
		** Get Cities
		*/
		public function getCities($name){
			$this->db->select('cities.*,countries.'.$name.' country');
            $this->db->from('cities');
			$this->db->join('countries', 'cities.country_id = countries.id', 'left');
            $query = $this->db->get();
            $booking = $query->result();
            return $booking;
        }
		
		/*
		** Get Artist Wallets
		*/
		public function getArtistWallets(){
			$this->db->select('artist_wallet.*,artist.name');
            $this->db->from('artist_wallet');
			$this->db->join('artist', 'artist_wallet.artist_id = artist.id', 'left');
            $query = $this->db->get();
            $booking = $query->result();
            return $booking;
        }
		
		/*
		** Get Artist Wallet Transactions By Artist
		*/
		public function getWalletTransactionsByArtist($id,$name){
			$this->db->select('artist_wallet_transactions.*,artist_wallet_transaction_types.'.$name.' name');
            $this->db->from('artist_wallet_transactions');
			$this->db->join('artist_wallet_transaction_types', 'artist_wallet_transactions.artist_wallet_transaction_types_id = artist_wallet_transaction_types.id', 'left');
			$this->db->where(['artist_wallet_transactions.artist_id'=>$id]);
			$this->db->order_by('artist_wallet_transactions.created_at', 'desc');
            $query = $this->db->get();
            $booking = $query->result();
            return $booking;
        }
		
		/*
		** Get Artist Points
		*/
		public function getAllArtistPoints(){
			$this->db->select('artist_points.*,artist.name');
            $this->db->from('artist_points');
			$this->db->join('artist', 'artist_points.artist_id = artist.id', 'left');
            $query = $this->db->get();
            $booking = $query->result();
            return $booking;
        }
		
		/*
		** Get Artist Point Transactions By Artist
		*/
		public function getPointTransactionsByArtist($id,$name){
			$this->db->select('artist_point_transactions.*,artist_point_transaction_types.'.$name.' name');
            $this->db->from('artist_point_transactions');
			$this->db->join('artist_point_transaction_types', 'artist_point_transactions.artist_point_transaction_types_id = artist_point_transaction_types.id', 'left');
			$this->db->where(['artist_point_transactions.artist_id'=>$id]);
			$this->db->order_by('artist_point_transactions.created_at', 'desc');
            $query = $this->db->get();
            $booking = $query->result();
            return $booking;
        }
		
		/*
		** Get Artist Transfer Cash Request By Id
		*/
		public function getArtistTransferCashRequestById($id){
            $this->db->select('artist.name,artist_wallet.amount wallet,artist_wallet_transfer_cash_requests.id,artist_wallet_transfer_cash_requests.artist_id,artist_wallet_transfer_cash_requests.amount,artist_wallet_transfer_cash_requests.status,artist_wallet_transfer_cash_requests.comment,artist_wallet_transfer_cash_requests.created_at');
            $this->db->from('artist_wallet_transfer_cash_requests');
            $this->db->join('artist', 'artist_wallet_transfer_cash_requests.artist_id = artist.id', 'left');
			$this->db->join('artist_wallet', 'artist.id = artist_wallet.artist_id', 'left');
			$this->db->where(['artist_wallet_transfer_cash_requests.id'=>$id]);
            $query = $this->db->get();
            $result = $query->row();
            return $result;
        }
		
		/*
		** Get Countries
		*/
		public function getCountries($name){
			$this->db->select('countries.id id,countries.'.$name.' name,currency_setting.currency_'.$name.' currency');
            $this->db->from('countries');
			$this->db->join('currency_setting', 'countries.id = currency_setting.country_id', 'left');
			$this->db->where(['countries.active'=>1]);
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
		
		/*
		** Get Categories
		*/
		public function getCategories($name){
			$this->db->select('category.*,category_price.id id_price,category_price.price price,category_price.status status_price,currency_setting.currency_'.$name.' currency,countries.'.$name.' country');
            $this->db->from('category');
			$this->db->join('category_price', 'category.id = category_price.category_id', 'left');
			$this->db->join('countries', 'category_price.country_id = countries.id', 'left');
			$this->db->join('currency_setting', 'countries.id = currency_setting.country_id', 'left');
			$this->db->order_by('category.created_at', 'desc');
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
		
		/*
		** Get Non Price Countries
		*/
		public function getNonPriceCountries($name,$category){
			//$query = $this->db->query('select * from countries where id NOT IN (select country_id from category_price where category_id = 37)');
            //$query = $this->db->get();
			$this->db->select('countries.id id,countries.'.$name.' name,currency_setting.currency_'.$name.' currency');
            $this->db->from('countries');
			$this->db->join('currency_setting', 'countries.id = currency_setting.country_id', 'left');
			$this->db->join('category_price', 'countries.id = category_price.country_id', 'left');
			$this->db->where('countries.id NOT IN (select category_price.country_id from category_price where category_price.category_id = '.$category.')');
            $query = $this->db->get();
            $result = $query->result();
            return $result;
        }
		
		/*
		** Get Price By ID
		*/
		public function getPriceByID($id,$name){
			$this->db->select('category_price.*,currency_setting.currency_'.$name.' currency,countries.'.$name.' country');
            $this->db->from('category_price');
			$this->db->join('countries', 'category_price.country_id = countries.id', 'left');
			$this->db->join('currency_setting', 'countries.id = currency_setting.country_id', 'left');
			$this->db->where(['category_price.id'=>$id]);
            $query = $this->db->get();
            $result = $query->row();
            return $result;
        }


        public function updateBookingPrice($id){

            $query = $this->db->select_sum('(quantity * cost_per_item)', 'total')->where('booking_order_id ', $id)->from('booking_order_items')->get()->row();

            $condition['id']= $id;
            $data['price'] = $query->total;

            $updatData = $this->updateSingleRow('artist_booking', $condition, $data);


            return true;

        }


        public function getInvoiceDetails($condition){
            $this->db->select('i.*, u.name, u.email_id, u.address, u.mobile, c.code as currency_code, c.currency_symbol');
            $this->db->from('booking_invoice i');
            $this->db->join('user u', 'i.user_id=u.user_id');
            $this->db->join('currency_setting c', 'c.id=i.currency_type', 'left');
            $this->db->where($condition);
            $query = $this->db->get();
            $invoice = $query->row();
            return $invoice;
        }


        public function getArtistProfit($artist_id, $from, $to){
//		    print_r($to); die;
            $this->db->select_sum('artist_amount');
            $this->db->from('booking_invoice');
            $this->db->where('artist_id', $artist_id);
            if($from !=''){
                $this->db->where('created_at >=', $from);
            }
            if($to !=''){
                $this->db->where('created_at <=', $to);
            }
            $query = $this->db->get();
            $invoice = $query->row();
            return $invoice;
        }

        public function getPendingOrdersForArtist($artistId, $limit){
            $this->db->select('b.*, c.cat_name, c.cat_name_ar, c.details as cat_details,ua.city_id, ct.name as city_name, ct.name_ar as city_name_ar, ua.longitude, ua.latitude, ua.address, pm.name as payment_method_name, pm.name_ar as payment_method_name_ar, pm.logo as payment_method_logo_url, cur.currency_symbol, cur.currency_name, cur.currency_name_ar, b.user_id as user_id, u.name as cst_name');
            $this->db->from('artist_booking b');
            $this->db->join('category c', 'c.id = b.category_id' );
            $this->db->join('user_addresses ua', 'ua.id = b.user_address_id');
            $this->db->join('user u', 'u.user_id = b.user_id' );
            $this->db->join('artist_category ac', 'ac.category_id = b.category_id' );
            $this->db->join('payment_methods pm', 'pm.id = b.payment_method_id' );
            $this->db->join('cities ct', 'ct.id = ua.city_id');
            $this->db->join('user us', 'us.city_id = ua.city_id' );
            $this->db->join('artist a', 'a.user_id = us.user_id');
            $this->db->join('currency_setting cur', 'cur.country_id = b.country_id');
            $this->db->where('b.status_id', 0);
            $this->db->where('a.id', $artistId);
            $this->db->order_by('b.created_at', 'desc');
            $this->db->group_by('b.id');
            $this->db->limit($limit);
            $query = $this->db->get();
            $booking = $query->result();
            return $booking;
        }


        public function getArtistCurrency($artistId){
            $this->db->select(' cur.currency_symbol, cur.currency_name, cur.currency_name_ar');
            $this->db->from('artist a');
            $this->db->join('currency_setting cur', 'cur.country_id = a.country_id');
            $this->db->where('a.id', $artistId);
            $query = $this->db->get();
            $booking = $query->row();
            return $booking;
        }

        public function getArtistOrders($artistId, $limit){
            $this->db->select('b.*, c.cat_name, c.cat_name_ar, c.details as cat_details, ct.name as city_name, ct.name_ar as city_name_ar, ua.longitude, ua.latitude, ua.address');
            $this->db->from('artist_booking b');
            $this->db->join('category c', 'c.id = b.category_id' );
            $this->db->join('artist_category ac', 'ac.category_id = c.parent_id' );
            $this->db->join('user_addresses ua', 'ua.id = b.user_address_id');
            $this->db->join('cities ct', 'ct.id = ua.city_id');
            //$this->db->join('artist a', 'a.city_id = ua.city_id');
            $this->db->where('b.artist_id', $artistId);
            $this->db->order_by('b.created_at', 'desc');
            $this->db->group_by('b.id');
            $this->db->limit($limit);
            $query = $this->db->get();
            $booking = $query->result();
            return $booking;
        }




}