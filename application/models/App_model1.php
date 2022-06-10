<?php defined('BASEPATH') or exit('No direct script access allowed');

class App_model extends CI_Model
{
    protected $user_table = 'tbl_user';


    /**
     * Use Registration
     * @param: {array} User Data
     */
    public function insert_user(array $data)
    {
        $this->db->insert($this->user_table, $data);
        return $this->db->insert_id();
    }

    /**
     * User Login
     * ----------------------------------
     * @param: username or email address
     * @param: password
     */
    public function user_login($phone_no, $password)
    {
        $this->db->where('phone_no', $phone_no);

        $q = $this->db->get($this->user_table);
        if ($q->num_rows()) {
            $user_pass = $q->row('password');
            $status = $q->row('status');
            if (md5($password) === $user_pass & $status == 1) {
                return $q->row();
            }
            return FALSE;
        } else {
            return FALSE;
        }
    }


    function common_delete($params, $table)
    {

        $this->db->delete($table, $params);
    }



    function common_insert($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    function get_data($data, $table)
    {
        return $this->db->get_where($table, $data)->result_array();
    }
    function common_join($paramSelect, $paramTable)
    {
        $sql_promo = "SELECT " . $paramSelect . " FROM " . $paramTable;
        // echo $sql_promo;die;
        return $this->db->query($sql_promo)->result_array();
    }

    //function for common update data
    function common_update($id, $params, $table)
    {
        $this->db->where($id);
        $this->db->update($table, $params);
    }

    function RandomString($length)
    {
        $keys = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $key = '';
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return strtoupper($key);
    }
    function getProductDetails()
    {
        $sql_promo = "SELECT * FROM products p inner join product_variants pv on p.product_id = pv.product_id 
        inner join product_details pd on pd.product_Variants_id = pv.product_Variants_id
        inner join variant_value vv on vv.value_id = pd.value_id
        inner join variants v on v.variant_id = vv.variant_id where pv.product_id = 1";
        // $sql_promo = "SELECT * FROM products p inner join  product_variants pv on p.product_id = pv.product_id where pv.product_id = 1";
        // echo $sql_promo;die;
        return $this->db->query($sql_promo)->result_array();
    }
    function getPriceDetails($str = "")
    {
        $objectStr = isset($str) && $str != "" ? " AND " . $str . " GROUP BY pv.sku" : '';
        $sql_promo = "SELECT * FROM products p inner join product_variants pv on p.product_id = pv.product_id 
        inner join product_details pd on pd.product_Variants_id = pv.product_Variants_id
        inner join variant_value vv on vv.value_id = pd.value_id
        inner join variants v on v.variant_id = vv.variant_id where pv.product_id = 1 " . $objectStr;
        // $sql_promo = "SELECT * FROM products p inner join  product_variants pv on p.product_id = pv.product_id where pv.product_id = 1";
        // echo $sql_promo;die;
        return $this->db->query($sql_promo)->result_array();
    }
    function getOptions($str = "")
    {
        $objectStr = isset($str) ? " AND " . $str : '';
        $sql_promo = "SELECT pd.* FROM product_details pd where pd.	product_Variants_id = " . $str;
        return $this->db->query($sql_promo)->result_array();
    }
    function getVariants()
    {
        $sql_promo = "SELECT * FROM variants";
        // echo $sql_promo;die;
        return $this->db->query($sql_promo)->result_array();
    }
}
