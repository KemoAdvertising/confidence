<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_model extends CI_Model
{
    protected $user_table = 'tbl_user';

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
    public function user_login($user_email, $password)
    {
        $this->db->where('user_email', $user_email);
        $this->db->where('role_id', 4);
        $q = $this->db->get($this->user_table);

        if( $q->num_rows() ) 
        {
            $user_pass = $q->row('password');
            if(md5($password) === $user_pass) {
                return $q->row(); 
            }
            return FALSE;
        }else{
            return FALSE;
        }
    }


     function common_delete($params,$table)
        {
            
            $this->db->delete($table,$params);        
        }



    function common_insert($table,$data)
        {
              $this->db->insert($table,$data);
              return $this->db->insert_id();              
        }
    function get_data($data,$table)
        {
            return $this->db->get_where($table,$data)->result_array();
        }
    function common_join($paramSelect,$paramTable)
      {
          $sql_promo = "SELECT ".$paramSelect." FROM ".$paramTable;
          // echo $sql_promo;die;
          return $this->db->query($sql_promo)->result_array();
      }

   //function for common update data
    function common_update($id,$params,$table)
        {
            $this->db->where($id);
            $this->db->update($table,$params);        
        }

    function RandomString($length)
    {
        $keys = array_merge(range(0,9), range('a', 'z'),range('A','Z'));
        $key='';
        for($i=0; $i < $length; $i++)
        {
            $key .= $keys[array_rand($keys)];
        }
        return strtoupper($key);
    }

}
