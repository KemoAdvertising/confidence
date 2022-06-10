<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vendor extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Vendorweb_Model', 'Vendorweb_Model', TRUE);
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('string');
        $this->load->library("pagination");
    }

    public function index()
    {
        if (!$this->session->userdata('id')) {
            redirect('Vendor/login');
        }
        $this->load->view('vendor/dashboard');
    }


    public function login()
    {
        $AllPostData = $this->input->post();
        $this->form_validation->set_rules('email', 'enter email', 'required|trim');
        $this->form_validation->set_rules('password', 'enter password', 'required|min_length[3]');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $email    = $this->input->post('email');

            $password = md5($this->input->post('password'));

            $login_id = $this->Vendorweb_Model->Login($email, $password);

            if ($login_id) {
                foreach ($login_id as $row)
                    $Vendor_data = array('id' => $row->id,'user_email' => $row->user_email,'password' => $row->password,'user_name' => $row->user_name,);

                $this->session->set_userdata('id', $Vendor_data);
                return redirect('Vendor/index');
            } else {
                $this->session->set_flashdata('error_msg', 'Invalid Username/Password.');
                redirect('Vendor/login');
            }
        } else {
            $this->load->view('vendor/login');
        }
    }


    // function store_search(){

    // }
    //Insert Store
    public function stores()
    {
        if (!$this->session->userdata('id')) {
            redirect('Vendor/login');
        }
        $created_by = $this->session->userdata('id');

        // $paramSelect1 = "*";
        // $paramTable1 = " tbl_store where created_by=".$created_by['id'];
        // $show['store_list'] = $this->Vendorweb_Model->common_join($paramSelect1, $paramTable1);



        $getParams = "ts.*";
        $table = 'tbl_store as ts JOIN tbl_owners_store as tos ON tos.store_id = ts.store_id WHERE tos.owner_id=' . $created_by['id'];
        $show['store_list'] = $this->Vendorweb_Model->common_join($getParams, $table);

        $search_text = $this->input->post('search_text');
        if ($search_text) {

            $paramSelect1 = " ts.*";
            $paramTable1 = " tbl_store AS ts where ts.store_name LIKE '%" . $search_text . "%'";
            $show['stores'] = $this->Vendorweb_Model->common_join($paramSelect1, $paramTable1);
        }



        $paramselct = "COUNT(*) as totalstore";
        $paramtable  = 'tbl_store where status=1 and created_by =' . $created_by['id'];
        $show['store_count'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);


        $data         = array();
        $table        = 'tbl_store_category';
        $show['store_cat'] = $this->Vendorweb_Model->get_data($data, $table);


        $storecategory = $this->input->post('store_cat_id');
        //print_r($storecategory);die;

        $this->form_validation->set_rules('store_name', 'Enter Store name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['store_image']['name']) {
                $store_image = $this->Vendorweb_Model->uploadEditImage($_FILES['store_image'], "store_image", "neetoAddedimaepath", "./assets/uploads/store");
                $storecat_img = $store_image;
            } else {
                $storecat_img = "";
            }
            $data  = array(
                'created_by' => $created_by['id'],
                'store_name' => $this->input->post('store_name'),
                'description' => $this->input->post('description'),
                'store_image' => $storecat_img,
                'rating' => $this->input->post('rating'),
                'approx_delivery_time' => $this->input->post('approx_delivery_time'),
                'approx_price' => $this->input->post('approx_price'),
                'full_address' => $this->input->post('full_address'),
                'landmark' => $this->input->post('landmark'),
                'pin_code' => $this->input->post('pin_code'),
                'lattitude' => $this->input->post('lattitude'),
                'longitude' => $this->input->post('longitude'),
                'license_code' => $this->input->post('license_code'),
                'store_charge' => $this->input->post('store_charge'),
                'delivery_radius' => $this->input->post('delivery_radius'),
                'is_pure_veg' => $this->input->post('is_pure_veg'),
                'is_featured' => $this->input->post('is_featured'),
                'min_order_price' => $this->input->post('min_order_price'),
                'commission_rate' => $this->input->post('commission_rate'),
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s')
            );
            // echo"<pre>";
            // print_r($data);die;
            $table = 'tbl_store';
            $last_id = $this->Vendorweb_Model->common_insert($table, $data);
            if ($last_id) {
                for ($i = 0; $i < count($storecategory); $i++) {
                    $stor_cat = array(
                        'store_id' => $last_id,
                        'store_cat_id' => $storecategory[$i],
                    );
                    $table1 = 'tbl_categoryonstore';
                    $this->Vendorweb_Model->common_insert($table1, $stor_cat);
                }
            }
            $this->session->set_flashdata('succ_msg', 'Store Added Sucessfully.');
            redirect('Vendor/stores');
        } else {
            $this->load->view('vendor/stores', $show);
        }
    }
    //Store Update
    public function store_update($id)
    {

        if (!$this->session->userdata('id')) {
            redirect('Vendor/login');
        }

        $updated_by = $this->session->userdata('id');
        $data         = array('created_by' => $updated_by['id']);
        $table        = 'tbl_store_category';
        $show['store_cat'] = $this->Vendorweb_Model->get_data($data, $table);

        $data         = array('store_id' => $id);
        $table        = 'tbl_store';
        $show['edit_store'] = $this->Vendorweb_Model->get_data($data, $table);

        $data         = array('store_id' => $id);
        $table        = 'tbl_categoryonstore';
        $show['edit_storecat'] = $this->Vendorweb_Model->get_data($data, $table);


        $storecategory = $this->input->post('store_cat_id');

        $this->form_validation->set_rules('store_name', 'Enter Store name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['store_image']['name']) {
                $store_image = $this->Vendorweb_Model->uploadEditImage($_FILES['store_image'], "store_image", "neetoAddedimaepath", "./assets/uploads/store");
                $storecat_img = $store_image;
            } else {
                if ($id) {
                    $storecat_img = $show['edit_store'][0]['store_image'];
                }
            }
            $data  = array(
                'updated_by' => $updated_by['id'],
                'store_name' => $this->input->post('store_name'),
                'description' => $this->input->post('description'),
                'store_image' => $storecat_img,
                'rating' => $this->input->post('rating'),
                'approx_delivery_time' => $this->input->post('approx_delivery_time'),
                'approx_price' => $this->input->post('approx_price'),
                'full_address' => $this->input->post('full_address'),
                'landmark' => $this->input->post('landmark'),
                'pin_code' => $this->input->post('pin_code'),
                'lattitude' => $this->input->post('lattitude'),
                'longitude' => $this->input->post('longitude'),
                'license_code' => $this->input->post('license_code'),
                'store_charge' => $this->input->post('store_charge'),
                'delivery_radius' => $this->input->post('delivery_radius'),
                'is_pure_veg' => $this->input->post('is_pure_veg'),
                'is_featured' => $this->input->post('is_featured'),
                'min_order_price' => $this->input->post('min_order_price'),
                'commission_rate' => $this->input->post('commission_rate'),
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s')
            );
            $table = 'tbl_store';
            $condition = array('store_id' => $id);
            $this->Vendorweb_Model->common_update($condition, $data, $table);

            $data2        = array('store_id' => $id);
            $table2       = 'tbl_categoryonstore';
            $this->Vendorweb_Model->common_delete($data2, $table2);

            if ($id) {
                for ($i = 0; $i < count($storecategory); $i++) {
                    $data1 = array(
                        'store_id' => $id,
                        'store_cat_id' => $storecategory[$i]
                    );
                    $table1 = 'tbl_categoryonstore';
                    $this->Vendorweb_Model->common_insert($table1, $data1);
                }
            }

            $this->session->set_flashdata('succ_msg', 'Store Updated Sucessfully.');
            redirect('Vendor/store_update/' . $id);
        } else {

            $this->load->view('vendor/store_update', $show);
        }
    }
    public function off_store()
    {

        $id = $_POST['store_id'];
        if ($id) {
            $save  = array(
                'status' => 0
            );
            $condition = array('store_id' => $id,);
            $table = 'tbl_store';
            $this->Vendorweb_Model->common_update($condition, $save, $table);
            echo "Category Off";
            //print_r($save);die;
        }
    }
    public function on_store()
    {

        $id = $_POST['store_id'];
        if ($id) {
            $save  = array(
                'status' => 1
            );
            $condition = array('store_id' => $id,);
            $table = 'tbl_store';
            $this->Vendorweb_Model->common_update($condition, $save, $table);
            echo "Category On";
            //print_r($save);die;
        }
    }
    //store category insertion
    public function store_category($id = "")
    {
        if (!$this->session->userdata('id')) {
            redirect('Vendor/login');
        }
        $created_by = $this->session->userdata('id');

        $paramSelect1 = "*";
        $paramTable1 = " tbl_store_category where created_by = " . $created_by['id'];
        $show['store_cat_list'] = $this->Vendorweb_Model->common_join($paramSelect1, $paramTable1);



        $search_text = $this->input->post('search_text');
        if ($search_text) {
            $paramSelect1 = " ts.*";
            $paramTable1 = " tbl_store_category AS ts where ts.store_cat_name LIKE '%" . $search_text . "%'";
            $show['store_cat'] = $this->Vendorweb_Model->common_join($paramSelect1, $paramTable1);
        }


        $paramselct = "COUNT(*) as totalcategory";
        $paramtable  = 'tbl_store_category where status=1';
        $show['category_count'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);

        $show['edit_store_cat'] = '';

        // $data         = array();
        // $table        = 'tbl_store_category';
        // $show['store_cat_list'] = $this->Vendorweb_Model->get_data($data, $table);

        $this->form_validation->set_rules('store_cat_name', 'Enter Store Category name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['category_imgage']['name']) {
                $category_imgage = $this->Vendorweb_Model->uploadEditImage($_FILES['category_imgage'], "category_imgage", "neetoAddedimaepath", "./assets/uploads/storecategory");
                $storecat_img = $category_imgage;
            } else {

                $storecat_img = "";
            }
            $data  = array(
                'created_by' => $created_by['id'],
                'store_cat_name' => $this->input->post('store_cat_name'),
                'description' => $this->input->post('description'),
                'category_imgage' => $storecat_img,
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s')
            );
            $table = 'tbl_store_category';
            $this->Vendorweb_Model->common_insert($table, $data);
            $this->session->set_flashdata('succ_msg', 'Category Added Sucessfully.');
            redirect('Vendor/store_category');
        } else {
            $this->load->view('vendor/store-category', $show);
        }
    }


    public function off_store_cat()
    {

        $id = $_POST['store_cat_id'];
        if ($id) {
            $save  = array(
                'status' => 0
            );
            $condition = array('store_cat_id' => $id,);
            $table = 'tbl_store_category';
            $this->Vendorweb_Model->common_update($condition, $save, $table);
            echo "Category Off";
            //print_r($save);die;
        }
    }
    public function on_store_cat()
    {

        $id = $_POST['store_cat_id'];
        if ($id) {
            $save  = array(
                'status' => 1
            );
            $condition = array('store_cat_id' => $id,);
            $table = 'tbl_store_category';
            $this->Vendorweb_Model->common_update($condition, $save, $table);
            echo "Category On";
            //print_r($save);die;
        }
    }
    public function update_str_cat($id)
    {

        if (!$this->session->userdata('id')) {
            redirect('Vendor/login');
        }

        $updated_by = $this->session->userdata('id');
        //$data['marks']  = $this->welcome->get_marks($stu_id);
        if ($id) {
            $data = array(
                'store_cat_id' => $id
            );
            $table = 'tbl_store_category';
            $show['edit_store_cat'] = $this->Vendorweb_Model->get_data($data, $table);
        }
        $this->form_validation->set_rules('store_cat_name', 'Enter Product name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['category_imgage']['name']) {
                $category_imgage = $this->Vendorweb_Model->uploadEditImage($_FILES['category_imgage'], "category_imgage", "neetoAddedimaepath", "./assets/uploads/storecategory");
                $storecat_img = $category_imgage;
            } else {
                if ($id) {
                    $storecat_img = $show['edit_store_cat'][0]['category_imgage'];
                }
            }

            $data  = array(
                'updated_by' => $updated_by['id'],
                'store_cat_name' => $this->input->post('store_cat_name'),
                'description' => $this->input->post('description'),
                'category_imgage' => $storecat_img
            );
            $table = 'tbl_store_category';
            $condition = array('store_cat_id' => $id);
            $this->Vendorweb_Model->common_update($condition, $data, $table);
            $this->session->set_flashdata('succ_msg', 'Category Updated Sucessfully.');
            redirect('Vendor/update_str_cat/' . $id);
        } else {

            $this->load->view("vendor/update_storeCategory", $show);
        }
    }

    public function item_category($id)
    {

        if (!$this->session->userdata('id')) {
            redirect('Vendor/login');
        }


        $created_by = $this->session->userdata('id');

        $store = $this->input->post('store');

        $paramselct = "COUNT(*) as totalitem_category";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $id;
        $show['item_count'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);


        $data         = array('created_by' => $created_by['id']);
        $table        = 'tbl_store';
        $show['store_list'] = $this->Vendorweb_Model->get_data($data, $table);

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $id;
        $show['itemCat_list'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $id . ' AND  parent_category_id > 0';
        $show['itemCat_list_select'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);



        $this->form_validation->set_rules('category_name', 'Enter Product name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['image']['name']) {
                $category_imgage = $this->Vendorweb_Model->uploadEditImage($_FILES['image'], "image", "neetoAddedimaepath", "./assets/uploads/itemcategory");
                $itemcat_img = $category_imgage;
            } else {
                $itemcat_img = "";
            }
            $data  = array(
                'created_by' => $created_by['id'],
                'category_name' => $this->input->post('category_name'),
                'description' => $this->input->post('description'),
                'store_id' => $this->input->post('store_id'),
                'image' => $itemcat_img,
                'parent_category_id' => $this->input->post('parent_category_id'),
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s'),
            );
            //print_r($data);die;
            $table = 'tbl_item_category';
            $item_catid = $this->Vendorweb_Model->common_insert($table, $data);

            $this->session->set_flashdata('succ_msg', 'Category Added Sucessfully.');
            redirect('Vendor/item_category/' . $id);
        } else {
            $this->load->view('vendor/item-category', $show);
        }
    }
    public function parrent_data($store_id)
    {
        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $store_id . ' AND  parent_category_id = 0';
        $show = $this->Vendorweb_Model->common_join($paramselct, $paramtable);

        $output = '<option value="" disabled selected>Parent Category</option><option value="0">None</option>';

        foreach ($show as $row) {
            $output .= '<option value="' . $row["item_cat_id"] . '">' . $row["category_name"] . '</option>';
        }

        echo $output;
    }

    public function item_category_update($item_cat_id)
    {
        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and item_cat_id=' . $item_cat_id;
        $item_category_selected = $this->Vendorweb_Model->common_join($paramselct, $paramtable);
        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id = ' . $item_category_selected[0]['store_id'] . ' AND parent_category_id = 0 AND  item_cat_id !=' . $item_cat_id;
        $show = $this->Vendorweb_Model->common_join($paramselct, $paramtable);

        $output = '<option value="" disabled selected>Parent Category</option><option value="0">None</option>';

        foreach ($show as $row) {
            if ($row["item_cat_id"] == $item_category_selected[0]['parent_category_id']) {
                $output .= '<option value="' . $row["item_cat_id"] . '" selected>' . $row["category_name"] . '</option>';
            } else {
                $output .= '<option value="' . $row["item_cat_id"] . '">' . $row["category_name"] . '</option>';
            }
        }

        echo $output;
    }
    public function item_update_data($item_id)
    {
        $paramselct = "tic.*,ti.store_id";
        $paramtable  = 'tbl_item AS ti join tbl_item_cat as tic ON tic.item_id = ti.item_id  where status=1 and ti.item_id=' . $item_id;
        $item_category_selected = $this->Vendorweb_Model->common_join($paramselct, $paramtable);

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $item_category_selected[0]['store_id'] . ' AND  parent_category_id > 0';
        $show = $this->Vendorweb_Model->common_join($paramselct, $paramtable);

        $output = '<option value="" disabled>Item Category</option>';

        foreach ($show as $row) {
            for ($i = 0; $i < count($item_category_selected); $i++) {
                if ($row["item_cat_id"] == $item_category_selected[$i]['item_subcat_id']) {
                    $output .= '<option value="' . $row["item_cat_id"] . '" selected>' . $row["category_name"] . '</option>';
                } else {
                    $output .= '<option value="' . $row["item_cat_id"] . '">' . $row["category_name"] . '</option>';
                }
            }
        }

        echo $output;
    }
    public function item_parent_data($store_id)
    {

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $store_id . ' AND  parent_category_id > 0';
        $show['itemCat_list'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);
        print_r(json_encode($show));
        die;
    }
    public function parent_data($store_id)
    {
        // echo $output;
        $params_ch = array("store_id" => $store_id);
        $table = 'tbl_item_category';
        $fild = "store_id";
        $view_toc['item_cat_list'] = $this->Vendorweb_Model->get_data1($params_ch, $table, $fild);
        // print_r($view_toc);die;
        print_r(json_encode($view_toc));
        die;
        //print_r($save);die;
    }

    public function off_item_cat()
    {

        $id = $_POST['item_cat_id'];
        if ($id) {
            $save  = array(
                'status' => 0
            );
            $condition = array('item_cat_id' => $id,);
            $table = 'tbl_item_category';
            $this->Vendorweb_Model->common_update($condition, $save, $table);
            echo "Category Off";
            //print_r($save);die;
        }
    }
    public function on_item_cat()
    {

        $id = $_POST['item_cat_id'];
        if ($id) {
            $save  = array(
                'status' => 1
            );
            $condition = array('item_cat_id' => $id,);
            $table = 'tbl_item_category';
            $this->Vendorweb_Model->common_update($condition, $save, $table);
            echo "Category On";
            //print_r($save);die;
        }
    }
    function update_item_category($id)
    {
        if (!$this->session->userdata('id')) {
            redirect('Vendor/login');
        }

        $updated_by = $this->session->userdata('id');
        $store = $this->input->post('store');

        $data         = array();
        $table        = 'tbl_store';
        $show['store_list'] = $this->Vendorweb_Model->get_data($data, $table);

        $data         = array('item_cat_id' => $id);
        $table        = 'tbl_item_category';
        $show['itemCat_edit'] = $this->Vendorweb_Model->get_data($data, $table);


        $this->form_validation->set_rules('category_name', 'Enter Category', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            // print_r($_FILES['category_imgage']['name']);die;
            if ($_FILES['image']['name']) {
                $category_imgage = $this->Vendorweb_Model->uploadEditImage($_FILES['image'], "image", "neetoAddedimaepath", "./assets/uploads/itemcategory");
                $itemcat_img = $category_imgage;
            } else {
                if ($id) {
                    $itemcat_img = $show['itemCat_edit'][0]['image'];
                }
            }
            $data  = array(
                'updated_by' => $updated_by['id'],
                'category_name' => $this->input->post('category_name'),
                'description' => $this->input->post('description'),
                'store_id' => $this->input->post('store_id'),
                'image' => $itemcat_img,
                'parent_category_id' => $this->input->post('parent_category_id'),
            );
            $condition = array('item_cat_id' => $id);
            $table = 'tbl_item_category';
            $this->Vendorweb_Model->common_update($condition, $data, $table);


            $this->session->set_flashdata('succ_msg', 'Item Category Updated Sucessfully.');
            redirect('Vendor/update_item_category/' . $id);
        } else {
            $this->load->view('vendor/update_item_category', $show);
        }
    }



    public function all_items($id)
    {

        if (!$this->session->userdata('id')) {
            redirect('Vendor/login');
        }

        $created_by = $this->session->userdata('id');

        $paramselct = "COUNT(*) as totalitem";
        $paramtable  = 'tbl_item where status=1 and created_by = "' . $created_by['id'] . '" and store_id=' . $id;
        $show['item_count'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);


        $search_text = $this->input->post('search_text');
        if ($search_text) {


            $paramSelect1 = " ts.*";
            $paramTable1 = " tbl_store AS ts where ts.store_name LIKE '%" . $search_text . "%'";
            $show['stores'] = $this->Vendorweb_Model->common_join($paramSelect1, $paramTable1);
        }



        $data         = array('store_id' => $id);
        $table        = 'tbl_store';
        $show['store_list'] = $this->Vendorweb_Model->get_data($data, $table);

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $id . ' AND  parent_category_id > 0';
        $show['itemCat_list'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);

        $item_cat_id = $this->uri->segment(4);
        $paramselct1 = "ti.*";
        $paramtable1  = 'tbl_item as ti JOIN tbl_item_cat as tic ON ti.item_id=tic.item_id where ti.store_id=' . $id . ' AND tic.item_subcat_id=' . $item_cat_id;
        $show['item_list'] = $this->Vendorweb_Model->common_join($paramselct1, $paramtable1);

        $itemCat = $this->input->post('item_cat_id');

        $this->form_validation->set_rules('item_name', 'Enter Product name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');
            if ($_FILES['item_image']['name']) {
                $item_image = $this->Vendorweb_Model->uploadEditImage($_FILES['item_image'], "item_image", "neetoAddedimaepath", "./assets/uploads/item");
                $itemImage = $item_image;
            }
            $data  = array(
                'created_by' => $created_by['id'],
                'item_name' => $this->input->post('item_name'),
                'store_id' => $this->input->post('store_id'),
                'item_type' => $this->input->post('item_type'),
                'description' => $this->input->post('description'),
                'item_image' => $itemImage,
                'price_type' => $this->input->post('price_type'),
                'Is_recommended' => $this->input->post('Is_recommended'),
                'itemQty' => $this->input->post('attribute'),
                'price' => $this->input->post('price'),
                'item_status' => "false",
                'status' => 1,
                'created_date' => date('Y-m-d H:i:s'),
            );
            $table = 'tbl_item';
            $item_id = $this->Vendorweb_Model->common_insert($table, $data);

            if ($item_id) {
                for ($i = 0; $i < count($itemCat); $i++) {
                    $data1 = array(
                        'item_id' => $item_id,
                        'item_subcat_id' => $itemCat[$i]
                    );
                    $table1 = 'tbl_item_cat';
                    $this->Vendorweb_Model->common_insert($table1, $data1);
                }
            }
            $this->session->set_flashdata('succ_msg', 'Item Sucessfully.');
            redirect('Vendor/all_items/' . $id . '/' . $item_cat_id);
        } else {
            $this->load->view('vendor/all-items', $show);
        }
    }

    public function update_item($id)
    {

        if (!$this->session->userdata('id')) {
            redirect('Vendor/login');
        }


        $data         = array('item_id' => $id);
        $table        = 'tbl_item';
        $show['item_edit'] = $this->Vendorweb_Model->get_data($data, $table);

        $paramselct = "*";
        $paramtable  = 'tbl_item_category where status=1 and store_id=' . $show['item_edit'][0]['store_id'] . ' AND  parent_category_id > 0';
        $show['itemCat_list'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);

        $data         = array();
        $table        = 'tbl_store';
        $show['store_list'] = $this->Vendorweb_Model->get_data($data, $table);

        $data         = array('item_id' => $id);
        $table        = 'tbl_item_cat';
        $show['itemCat_edit'] = $this->Vendorweb_Model->get_data($data, $table);

        $created_by = $this->session->userdata('id');

        $itemCat = $this->input->post('item_cat_id');

        $this->form_validation->set_rules('item_name', 'Enter Product name', 'required|trim');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);
            $this->load->library('form_validation');

            if ($_FILES['item_image']['name']) {
                $item_image = $this->Vendorweb_Model->uploadEditImage($_FILES['item_image'], "item_image", "neetoAddedimaepath", "./assets/uploads/item");
                $itemImage = $item_image;
            } else {
                if ($id) {
                    $itemImage = $show['item_edit'][0]['item_image'];
                }
            }
            $data  = array(
                'updated_by' => $created_by['id'],
                'store_id' => $this->input->post('store_id'),
                'item_name' => $this->input->post('item_name'),
                'item_type' => $this->input->post('item_type'),
                'description' => $this->input->post('description'),
                'item_image' => $itemImage,
                'price_type' => $this->input->post('price_type'),
                'Is_recommended' => $this->input->post('Is_recommended'),
                'itemQty' => $this->input->post('itemQty'),
                'price' => $this->input->post('price'),
            );
            $condition = array('item_id' => $id);
            $table = 'tbl_item';
            $this->Vendorweb_Model->common_update($condition, $data, $table);

            $data2        = array('item_id' => $id);
            $table2       = 'tbl_item_cat';
            $this->Vendorweb_Model->common_delete($data2, $table2);

            if ($id) {
                for ($i = 0; $i < count($itemCat); $i++) {
                    $data1 = array(
                        'item_id' => $id,
                        'item_subcat_id' => $itemCat[$i]
                    );
                    $table1 = 'tbl_item_cat';
                    $this->Vendorweb_Model->common_insert($table1, $data1);
                }
            }
            $this->session->set_flashdata('succ_msg', 'Item Updated Sucessfully.');
            redirect('Vendor/update_item/' . $id);
        } else {

            $this->load->view('vendor/update_item', $show);
        }
    }

    public function off_item()
    {

        $id = $_POST['item_id'];
        if ($id) {
            $save  = array(
                'status' => 0
            );
            $condition = array('item_id' => $id,);
            $table = 'tbl_item';
            $this->Vendorweb_Model->common_update($condition, $save, $table);
            echo "Category Off";
            //print_r($save);die;
        }
    }
    public function on_item()
    {

        $id = $_POST['item_id'];
        if ($id) {
            $save  = array(
                'status' => 1
            );
            $condition = array('item_id' => $id,);
            $table = 'tbl_item';
            $this->Vendorweb_Model->common_update($condition, $save, $table);
            echo "Category On";
            //print_r($save);die;
        }
    }

    public function owners_store()
    {

        $id = $_POST['store_id'];
        if ($id) {
            $save  = array(
                'store_id' => $id
            );
            $table = 'tbl_owners_store';
            $this->Vendorweb_Model->common_delete($save, $table);
            echo "Store Deleted successfully";
            //print_r($save);die;
        }
    }




    public function orders()
    {

        $created_by = $this->session->userdata('id');

        $str = " WHERE order_status = 7";
        if (isset($_GET['order'])) {
            if ($_GET['order'] == "accepted") {
                $str = " WHERE order_status = 2";
            } else if ($_GET['order'] == "ongoing") {
                $str = " WHERE order_status BETWEEN 4 AND 7";
            } elseif ($_GET['order'] == "reject") {
                $str = " WHERE order_status = 3";
            } elseif ($_GET['order'] == "completed") {
                $str = " WHERE order_status = 7";
            } elseif($_GET['order'] == "cancel"){
                $str = " WHERE order_status = 8";
            }else{
                $str = " WHERE order_status > 2";
            }
        }
        $paramselct = "tu.user_name,tbo.*,tua.address,ts.store_name,tos.*";
        $paramtable  = 'tbl_order as tbo join tbl_user as tu on tbo.user_id=tu.id left join tbl_user_address as tua on tbo.address_id=tua.address_id join tbl_store as ts on tbo.store_id=ts.store_id JOIN tbl_owners_store as tos ON tos.store_id = ts.store_id' . $str.' and tos.owner_id='.$created_by['id'];
        $show['order_list'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);

       //echo"<pre>";
     //print_r($paramselct.$paramtable);die;
        $paramselct1 = "*";
        $paramtable1  = 'tbl_user WHERE role_id = 4';
        $show['staff_list'] = $this->Vendorweb_Model->common_join($paramselct1, $paramtable1);

        $this->load->view('vendor/orders', $show);
    }

    public function order_detail($id)
    {

        $paramselct = "tu.id,tu.user_name,tu.user_email,tu.phone_no,tbo.*,tua.address,ts.store_name";
        $paramtable  = 'tbl_order as tbo join tbl_user as tu on tbo.user_id=tu.id left join tbl_user_address as tua on tbo.address_id=tua.address_id join tbl_store as ts on tbo.store_id=ts.store_id where tbo.id=' . $id;
        $show['order_detail'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);


        $paramselct = "*";
        $paramtable  = 'tbl_order_items where order_id=' . $id;
        $show['item_details'] = $this->Vendorweb_Model->common_join($paramselct, $paramtable);
        //     echo"<pre>";
        // print_r($show['item_details']);die;
        $this->load->view('vendor/order_detail', $show);
    }


    function reports()
    {
        // Load Authorization Token Library
        if (!$this->session->userdata('id')) {
            redirect('Vendor/login');
        }
        $store_id = $this->input->post('store_id');
        $from_date = $this->input->post('from_date');

        $order_status = $this->input->post('order_status');
        $this->form_validation->set_rules('store_id', 'Select Store', 'required|trim');
        $this->form_validation->set_rules('order_status', 'Select Order Status', 'required');
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
        $save = array();

        $paramStore ="ts.*";
        $tableStore = "tbl_store AS ts JOIN `tbl_owners_store` AS tos ON ts.store_id = tos.store_id WHERE ts.status = 1 AND tos.owner_id=".$this->session->userdata('id')['id'];
        $save['store_list'] = $this->Vendorweb_Model->common_join($paramStore, $tableStore);
//         echo"<pre>";
//  print_r($save['store_list'] );die;

        $data         = array('role_id'=>4);
        $table        = 'tbl_user';
        $save['delivery_staff'] = $this->Vendorweb_Model->get_data($data, $table);

        if ($this->form_validation->run()) {

            $this->session->set_flashdata('store_id',$store_id);
            $this->session->set_flashdata('order_status',$order_status);
            $this->session->set_flashdata('from_date',$from_date);
// print_r($orde);die;
            $dates = explode('-',$from_date);

            $order_stat = 'AND tor.order_status=' . $order_status;
            if($order_status == 4){
                $order_stat = 'AND tor.order_status BETWEEN 4 AND 7';
            }
            $getParams = "tor.*,tu.user_name,tu.user_email,tu.phone_no,tua.latitude as to_latitude,tua.longitude to_longitude,ts.lattitude as from_lattitude,ts.longitude as from_longitude,tua.address as to_address,ts.full_address as from_address,ts.store_name,tpt.name as payment_mode_name";
            // $table = 'tbl_order AS tor LEFT JOIN tbl_user_address as tua ON tua.address_id = tor.address_id LEFT JOIN tbl_store AS ts ON ts.store_id = tor.store_id WHERE tor.order_status = 2';
            $table = 'tbl_order AS tor LEFT JOIN tbl_delivery_log AS tdl ON tdl.order_id = tor.id LEFT JOIN tbl_user as tu ON tu.id = tor.user_id LEFT JOIN tbl_user_address as tua ON tua.address_id = tor.address_id LEFT JOIN tbl_store AS ts ON ts.store_id = tor.store_id LEFT JOIN tbl_payment_type AS tpt ON tpt.payment_type_id = tor.payment_type_id WHERE tor.store_id=' . $store_id . ' AND tor.created_date BETWEEN "' . date('Y-m-d',strtotime($dates[0])) . '" AND "' . date('Y-m-d',strtotime($dates[1]. " +1 day")). '"'.$order_stat ;

//    print_r($table);die;
            // TODO make it 7 again
            $save['report_detals'] = $this->Vendorweb_Model->common_join($getParams, $table);


            $data         = array();
            $table        = 'tbl_order_items';
            $save['items'] = $this->Vendorweb_Model->get_data($data, $table);
            // print_r($save['report_detals']);die;
            if (isset($data)) {
                
                // store owner detaisl

                // $save = $data;
                // for ($i = 0; $i < count($data); $i++) {
                //     $paramSelect2 = "tu.*";
                //     $paramTable2 = "tbl_order AS tor JOIN `tbl_owners_store` as tos ON tor.store_id=tos.store_id JOIN tbl_user AS tu ON tu.id = tos.owner_id WHERE tor.id =" . $data[$i]['id'];
                //     $data2 = $this->Vendor_model->common_join($paramSelect2, $paramTable2);
                //     $save[$i]['store_phone_num'] = $data2[0]['phone_no'];

                //     $paramSelect1 = " toi.*";
                //     $paramTable1 = " tbl_order_items AS toi where toi.order_id = " . $data[$i]['id'];
                //     $data1 = $this->Vendor_model->common_join($paramSelect1, $paramTable1);
                //     $save[$i]['items'] = $data1;
                // }
            }
        }
        $this->load->view('vendor/report', $save);
    }

    public function earning()
    {

        $data         = array();
        $table        = 'tbl_store';
        $show['store_list'] = $this->Vendorweb_Model->get_data($data, $table);

        $this->load->view('vendor/earning');
    }


    function logout()
    {
        $this->session->unset_userdata('id');
        redirect('Vendor/login');
    }
}
