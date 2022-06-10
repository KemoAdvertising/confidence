<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vendorweb_model extends CI_Model
{
    protected $user_table = 'tbl_user';
    protected $store_categorytable = 'tbl_store_category';

    /**
     * Use Registration
     * @param: {array} User Data
     */
    public function insert_user(array $data) {
        $this->db->insert($this->user_table, $data);
        return $this->db->insert_id();
    }

    /**
     * User Login
     * ----------------------------------
     * @param: username or email address
     * @param: password
     */
    public function Login($email,$password){
        $this->db->select('id,user_email,password,user_name');
        $this->db->from('tbl_user');
        $this->db->where('user_email',$email);
        $this->db->where('password',$password);
        $this->db->where('role_id',2);
        $this->db->where('status',1);
        $query=$this->db->get();
        return $query->result();
      }


      function common_insert($table, $data)
      {
            $this->db->insert($table,$data);
            return $this->db->insert_id();              
      }

      public function get_count() {
          return $this->db->count_all('tbl_store');
      }
      public function get_str_cat_count() {
          return $this->db->count_all($this->store_categorytable);
      }
      public function get_user_count() {
          return $this->db->count_all('tbl_user');
      }


      public function get_owner_count() {
          $this->db->select('*');
          $this->db->where('role_id', 2);
          return $this->db->count_all_results('tbl_user');
      }
      public function get_customers_count() {
          $this->db->select('*');
          $this->db->where('role_id', 2);
          return $this->db->count_all_results('tbl_user');
      }

      public function get_stores($limit, $start) {
          // $this->db->limit($limit, $start);
          $query = $this->db->get('tbl_store');
              return $query->result_array();
      }

      public function get_stores_category($limit, $start) {
          // $this->db->limit($limit, $start);
          $query = $this->db->get($this->store_categorytable);
  
          return $query->result_array();
      }



      public function get_all_user($limit, $start) {
          // $this->db->limit($limit, $start);
          $query = $this->db->get('tbl_user');
          return $query->result_array();
      }




      public function get_all_owners($limit, $start) {
          // $this->db->limit($limit, $start);
          $this->db->where('role_id', 2);
          $query = $this->db->get('tbl_user');
          return $query->result_array();
      }
      public function get_delivery_guy($limit, $start) {
          // $this->db->limit($limit, $start);
          $query = $this->db->get('delivery_guy_details');
          return $query->result_array();
      }
      public function get_all_customers($limit, $start) {
          // $this->db->limit($limit, $start);
          $this->db->where('role_id', 3);
          $query = $this->db->get('tbl_user');
          return $query->result_array();
      }



  function get_data($params,$table)
      {
          return $this->db->get_where($table,$params)->result_array();
      }
  function get_join_data($params,$table,$table1,$cond)
      {
          $this->db->join($table1,$cond,'inner');
          return $this->db->get_where($table,$params)->result_array();            
      }
function get_data1($params,$table,$fild)
      {
          $this->db->order_by($fild,"ASC");
          $this->db->select('*');
          return $this->db->get_where($table,$params)->result_array();
      } 
function common_join($paramSelect,$paramTable)
  {
      $sql_promo = "SELECT ".$paramSelect." FROM ".$paramTable;
      //echo $sql_promo;die; 
      return $this->db->query($sql_promo)->result_array();
  }
  //function for common update data
  function common_update($id,$params,$table)
      {
          $this->db->where($id);
          $this->db->update($table,$params);      
      }
  function common_delete($params,$table)
      {
          $this->db->delete($table,$params);       
      }

   
   // for uploads multiple images
 function uploadEditImage($files, $filename, $old_profile_image, $upload_path)
        {   
        if (!empty($files['name']) && $files['size'] > 0) 
        {
          $config['upload_path'] = $upload_path;
          $config['allowed_types'] = "gif|jpg|jpeg|png|php|dmg|zip|rar|doc|docx|xls|xlsx|ppt|pptx|csv|ods|odt|odp|pdf|rtf|sxc|sxi|txt|json";
          $config['file_name'] = random_string('numeric', 5).strtotime(date("Ymd his"));
          $config['max_size'] = '5048KB';
          $config['max_width']  = '20000';
          $config['max_height']  = '20000';
          $this->upload->initialize($config);

          if($this->upload->do_upload($filename))
          {
              $w = $this->upload->data();
              $uploaded_image = $w['file_name'];

              $config = array(
              'image_library'  => 'gd2',
              'new_image'      => "",
              'source_image'   => $upload_path.$w['file_name'],
              'create_thumb'   => false,    
              'width'          => "750",
              'height'         => "563",
              'maintain_ratio' => TRUE,
              );
              $this->load->library('image_lib'); // add library
              $this->image_lib->initialize($config);
              $this->image_lib->resize();
              return $uploaded_image;
          }
          else
          {
              return array('status' => false, 'error' => $this->upload->display_errors());
          }
      }
  }

}
