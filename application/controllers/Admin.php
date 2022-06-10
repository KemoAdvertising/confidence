<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Admin_Model', 'Admin_model', TRUE);
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('string');
        $this->load->library("pagination");
    }

    public function index()
    {		
        if (!$this->session->userdata('id')) {
            redirect('Admin/login');
        }
        $this->load->view('dashboard');
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
            $login_id = $this->Admin_model->Login($email, $password);
            if ($login_id) {
                foreach ($login_id as $row)
                    $admin_data = array(
                        'id' => $row->id,
                        'user_name' => $row->user_name,
                        'password' => $row->password,
                        'status' => '1'
                    );

                $this->session->set_userdata('id', $admin_data);
                return redirect('Admin/index');
            } else {
                $this->session->set_flashdata('error_msg', 'Invalid Username/Password.');
                redirect('Admin/login');
            }
        }
        $this->load->view('login');
    }


    function attributes($id = "")
    {

        $table = 'variants';
        $fild = "variant_id";
        $params_ch = array();
        $show['options'] = $this->Admin_model->get_data($params_ch, $table, $fild);

        if ($id) {
            $table = 'variants';
            $fild = "variant_id";
            $params_ch = array('variant_id' => $id);
            $show['edit_option'] = $this->Admin_model->get_data($params_ch, $table, $fild);

            $table = 'variant_value';
            $fild = "variant_id";
            $params_ch = array('variant_id' => $id);
            $show['edit_attribute'] = $this->Admin_model->get_data($params_ch, $table, $fild);
        }
        $this->form_validation->set_rules('option_name', 'Option Name', 'required|trim');

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');

        if ($this->form_validation->run()) {
            $option_name = trim($this->input->post('option_name'));
            $option_type = trim($this->input->post('type'));
            $attribute_name = $this->input->post('attribute_name');

            $params_ch = array('variant' => $option_name);
            $table = 'variants';
            $fild = "id";
            $validate_name_active = $this->Admin_model->get_data($params_ch, $table, $fild);
            $last_id = "";

            $data  = array(
                'variant' => $option_name,
                'type' => $option_type,
            );
            $table = 'variants';
            if ($id) {
                $condition = array('variant_id' => $id);
                $this->Admin_model->common_update($condition, $data, $table);
                $last_id = $id;
            } else {
                if (!$validate_name_active) {
                    $last_id = $this->Admin_model->common_insert($table, $data);
                } else {
                    $this->session->set_flashdata('error_msg', 'Option already Exists');
                }
            }

            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "svg|gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);

            foreach ($attribute_name as $key => $value) {
                $params_ch = array('value' => trim($value), 'variant_id' => $last_id);
                $table = 'variant_value';
                $fild = "id";
                $validate_attribute_active = $this->Admin_model->get_data($params_ch, $table, $fild);
                if (!$validate_attribute_active && !$validate_name_active) {
                    if (isset($_FILES['attribute_image' . $key]['name']) && $last_id) {
                        $img_banner = $this->Admin_model->uploadEditImage($_FILES['attribute_image' . $key], "attribute_image" . $key, "neetoAddedimaepath", "./assets/uploads/variants");
                        $imgBanner = !is_array($img_banner) ? $img_banner : '';
                    }
                    $data  = array(
                        'variant_id' => $last_id,
                        'value' => trim($value),
                        'variant_image' => $imgBanner,
                    );
                    $table = 'variant_value';
                    $this->Admin_model->common_insert($table, $data);
                } else if ($id) {

                    if (isset($_FILES['attribute_image' . $key]['name']) && $_FILES['attribute_image' . $key]['name']) {
                        $img_banner = $this->Admin_model->uploadEditImage($_FILES['attribute_image' . $key], "attribute_image" . $key, "neetoAddedimaepath", "./assets/uploads/variants");
                        $imgBanner = !is_array($img_banner) ? $img_banner : '';
                    } else {
                        $imgBanner = $show['edit_attribute'][$key]['variant_image'];
                    }

                    if (isset($show['edit_attribute'][$key]['value_id'])) {
                        $data  = array(
                            'value' => trim($value),
                            'variant_image' => $imgBanner,
                        );
                        $table = 'variant_value';
                        $condition = array('value_id' => $show['edit_attribute'][$key]['value_id']);
                        $this->Admin_model->common_update($condition, $data, $table);
                    } else {
                        $data  = array(
                            'variant_id' => $last_id,
                            'value' => trim($value),
                            'variant_image' => $imgBanner,
                        );
                        $table = 'variant_value';
                        $this->Admin_model->common_insert($table, $data);
                    }
                }
            }
            // if ($id) {
            redirect('Admin/attributes');
            // } else {
            // redirect('Admin/attributes');
            // }
        }
        $this->load->view('attributes', $show);
    }
    function attributesList($id = "")
    {
        $show['attributes'] = $this->Admin_model->get_attributes($id);
        $this->load->view('attributesList', $show);
    }
    function variations($id = "")
    {
        $table = 'product_variants';
        $fild = "product_variants_id";
        $params_ch = array();
        $show['variations'] = $this->Admin_model->get_data($params_ch, $table, $fild);

        $table = 'variants';
        $fild = "variant_id";
        $params_ch = array();
        $show['options'] = $this->Admin_model->get_data($params_ch, $table, $fild);

        $table = 'variant_value';
        $fild = "variant_id";
        $params_ch = array();
        $show['attributes'] = $this->Admin_model->get_data($params_ch, $table, $fild);

        if ($id) {
            $table = 'product_variants';
            $fild = "product_variants_id";
            $params_ch = array('product_variants_id' => $id);
            $show['edit_variation'] = $this->Admin_model->get_data($params_ch, $table, $fild);

            $table = 'product_details';
            $fild = "product_detail_id";
            $params_ch = array('product_variants_id' => $id);
            $show['edit_variation_value'] = $this->Admin_model->get_data($params_ch, $table, $fild);
        }


        $this->form_validation->set_rules('variant_name', 'Variant Name', 'required|trim');
        $this->form_validation->set_rules('variant_sku', 'Variant Sku', 'required|trim');
        $this->form_validation->set_rules('variant_price', 'Variant Price', 'required|trim');

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');

        if ($this->form_validation->run()) {
            $config = [
                'upload_path'   => './uploads',
                'allowed_types' => "svg|gif|jpg|jpeg|png"
            ];
            $this->load->library('upload', $config);

            $variant_name = trim($this->input->post('variant_name'));
            $variant_sku = $this->input->post('variant_sku');
            $variant_price = $this->input->post('variant_price');
            $variation_value = $this->input->post('variation_value');
            $product_details_id = $this->input->post('product_details_id');

            $params_ch = array('sku' => $variant_sku);
            $table = 'product_variants';
            $fild = "id";
            $validate_name_active = $this->Admin_model->get_data($params_ch, $table, $fild);

            $last_id = "";
            $data  = array(
                'product_id' => 1,
                'productVariantName' => $variant_name,
                'sku' => $variant_sku,
                'price' => $variant_price,
            );
            $table = 'product_variants';
            if (isset($_FILES['variation_image']['name'])) {
                if ($_FILES['variation_image']['name']) {
                    $img_banner = $this->Admin_model->uploadEditImage($_FILES['variation_image'], "variation_image", "neetoAddedimaepath", "./assets/uploads/variations");
                    $imgBanner = !is_array($img_banner) ? $img_banner : '';
                } else {
                    if ($id) {
                        $imgBanner = $show['edit_variation'][0]['image'];
                    } else {
                        $imgBanner = "";
                    }
                }
            }
            if ($id) {
                $condition = array('product_Variants_id ' => $id);
                $data['image'] = $imgBanner;

                $this->Admin_model->common_update($condition, $data, $table);
                $last_id = $id;
            } else {
                if (!$validate_name_active) {

                    $data['image'] = $imgBanner;
                    $last_id = $this->Admin_model->common_insert($table, $data);
                } else {
                    $this->session->set_flashdata('error_msg', 'SKU already Exists');
                }
            }

            if ($last_id) {
                if ($id) {
                    foreach ($product_details_id as $d => $detail_id) {
                        $data  = array(
                            'value_id' => $variation_value[$d]
                        );
                        $table = 'product_details';
                        $condition = array('product_detail_id ' => $detail_id, 'product_Variants_id' => $last_id);
                        $this->Admin_model->common_update($condition, $data, $table);
                    }
                } else {
                    foreach ($variation_value as $vv => $value) {
                        $data  = array(
                            'product_Variants_id' => $last_id,
                            'value_id' => $value,
                        );
                        $table = 'product_details';
                        $this->Admin_model->common_insert($table, $data);
                    }
                }
            }



            // if ($id) {
            //     redirect('Admin/variations/' . $id);
            // } else {
            redirect('Admin/variations');
            // }
        }


        $this->load->view('variations', $show);
    }
    public function attribute_option_delete()
    {

        $id = $_POST['id'];
        if ($id) {
            $save  = array(
                'value_id' => $id
            );
            $table = 'variant_value';
            $this->Admin_model->common_delete($save, $table);
            echo "Attribute Deleted successfully";
            //print_r($save);die;
        }
    }
    public function attribute_delete()
    {

        $id = $_POST['id'];
        if ($id) {
            $save  = array(
                'variant_id' => $id
            );
            $table = 'variant_value';
            $this->Admin_model->common_delete($save, $table);

            $table2 = 'variants';
            $this->Admin_model->common_delete($save, $table2);
            echo "Attribute Deleted successfully";
            //print_r($save);die;
        }
    }
    public function vairation_delete()
    {

        $id = $_POST['id'];
        if ($id) {
            $save  = array(
                'product_Variants_id' => $id
            );
            $table = 'product_variants';
            $this->Admin_model->common_delete($save, $table);

            $table2 = 'product_details';
            $this->Admin_model->common_delete($save, $table2);
            echo "Attribute Deleted successfully";
            //print_r($save);die;
        }
    }
    //Insert Store
    function logout()
    {
        $this->session->unset_userdata('id');
        redirect('Admin/login');
    }
}
