<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class User extends \Restserver\Libraries\REST_Controller
{
    public function __construct()
    {
        parent::__construct($config = 'rest');
        // Load User Model
        $this->load->model('App_model', 'UserModel');
        // header("Access-Control-Allow-Methods': 'GET,PUT,POST,DELETE");
        // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Content-Type: application/x-www-form-urlencoded');
        $this->load->helper('url');
        // Load Authorization Token Library
        $this->load->library('Authorization_Token');
        $this->load->library('woocommerce');
        $this->woo = $this->woocommerce->request();


        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            die();
        }
        $this->load->library('form_validation');
    }

    /**
     * User Register
     * --------------------------
     * @param: user_name
     * @param: username
     * @param: email
     * @param: password
     * --------------------------
     * @method : POST
     * @link : api/user/register
     */
    public function User_register_post()
    {

        $generator = "1357902468";
        $result = "";

        for ($i = 1; $i <= 4; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }

        //Multiple mobiles numbers separated by comma
        $mobileNumber = $this->input->post('phone_no', TRUE);
        header("Access-Control-Allow-Origin: *");

        # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation
        $this->form_validation->set_rules('user_name', 'Full Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules(
            'phone_no',
            'Phone No',
            'trim|required|is_unique[tbl_user.phone_no]|max_length[20]',
            array('is_unique' => 'This %s already exists please enter another phone no')
        );
        $this->form_validation->set_rules(
            'email',
            'Email',
            'trim|required|valid_email|max_length[80]|is_unique[tbl_user.user_email]',
            array('is_unique' => 'This %s already exists please enter another email address')
        );
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[100]');
        if ($this->form_validation->run() == FALSE) {
            // Form Validation Errors
            $message = array(
                'status' => true,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );

            $this->response($message, REST_Controller::HTTP_OK);
        } else {
            $insert_data = [
                'user_name' => $this->input->post('user_name', TRUE),
                'user_email' => $this->input->post('email', TRUE),
                'phone_no' => $this->input->post('phone_no', TRUE),
                'password' => md5($this->input->post('password', TRUE)),
                'created_date' => date('Y-m-d H:i:s'),
                'otp' => $result,
                'role_id' => 3,
                'status' => 0
            ];

            // Insert User in Database
            $output = $this->UserModel->insert_user($insert_data);

            $authKey = "243561AqwILtge5bc9e7a2";


            //Sender ID,While using route4 sender id should be 6 characters long.
            // $senderId = "BchOfc";
            $senderId = "RWMEAT";

            //Your message to send, Add URL encoding here.
            $message = urlencode("your OTP is " . $result);

            //Define route 
            $route = "4";
            //Prepare you post parameters
            $postData = array(
                'authkey' => $authKey,
                'mobiles' => $mobileNumber,
                'message' => $message,
                'sender' => $senderId,
                'route' => $route
            );
            //API URL
            $url = "http://api.msg91.com/api/sendhttp.php";

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
            $outputs = curl_exec($ch);
            //Print error if any
            if (curl_errno($ch)) {
                echo 'error:' . curl_error($ch);
            }
            curl_close($ch);








            if ($output > 0 and !empty($output)) {
                // Success 200 Code Send
                $message = [
                    'status' => true,
                    'message' => "Otp sent on Mobile No"
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                // Error
                $message = [
                    'status' => FALSE,
                    'message' => "Not Register Your Account."
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
    public function otp_validt_post()
    {

        $data = array('otp' => $this->input->post('otp'));
        $table = "tbl_user";
        $output = $this->UserModel->get_data($data, $table);
        if ($output) {
            $insert_data = array(
                'status' => 1
            );
            $condition = array('otp' => $this->input->post('otp'));
            $table1 = "tbl_user";
            $this->UserModel->common_update($condition, $insert_data, $table1);
            $message = [
                'status' => TRUE,
                'message' => "Registration Successfully"
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        } else {
            $message = array(
                'status' => false,
                'message' => "Otp Not Valid"
            );
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }




    /**
     * User Login API
     * --------------------
     * @param: username or email
     * @param: password
     * --------------------------
     * @method : POST
     * @link: api/user/login
     */
    public function login_post()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type:application/x-www-form-urlencoded");
        # XSS Filtering (https://www.codeigniter.com/user_guide/libraries/security.html)
        $_POST = $this->security->xss_clean($_POST);

        # Form Validation
        $this->form_validation->set_rules('phone_no', 'Phone No', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[100]');
        if ($this->form_validation->run() == FALSE) {
            // Form Validation Errors
            $message = array(
                'status' => false,
                'error' => $this->form_validation->error_array(),
                'message' => validation_errors()
            );

            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        } else {
            // Load Login Function
            $output = $this->UserModel->user_login($this->input->post('phone_no'), $this->input->post('password'));

            if (!empty($output) and $output != FALSE) {
                // Load Authorization Token Library
                $this->load->library('Authorization_Token');

                // Generate Token
                $token_data['id'] = $output->id;
                $token_data['user_name'] = $output->user_name;
                $token_data['user_email'] = $output->user_email;
                $token_data['phone_no'] = $output->phone_no;
                $token_data['created_date'] = $output->created_date;
                $token_data['updated_date'] = $output->updated_date;
                $token_data['time'] = time();
                $token_data['status'] = 1;

                $user_token = $this->authorization_token->generateToken($token_data);

                $return_data = [
                    'user_id' => $output->id,
                    'user_name' => $output->user_name,
                    'user_email' => $output->user_email,
                    'phone_no' => $output->phone_no,
                    'created_date' => $output->created_date,
                    'wallet_ballance' => $output->wallet_balance,
                    'token' => $user_token,
                    'razorpay_key' => 'rzp_live_70q7JaSie8K56a'
                ];

                $notification_token = $this->input->post('notification_token');
                if ($notification_token) {
                    $insert_data = array(
                        'user_token' => $notification_token,
                    );
                    $table_user = "tbl_user";
                    $condition = array('id' => $output->id);
                    $output = $this->UserModel->common_update($condition, $insert_data, $table_user);
                }
                // Login Success
                $message = [
                    'status' => true,
                    'data' => $return_data,
                    'message' => "User login successful"
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                // Login Error
                $message = [
                    'status' => FALSE,
                    'message' => "Invalid Username or Password"
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    function productDetails_get()
    {

        header("Access-Control-Allow-Origin: *");
        // Load Authorization Token Library
        // $this->load->library('Authorization_Token');
        /**
         * User Token Validation
         */
        // $is_valid_token = $this->authorization_token->validateToken();
        // if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) {

        $variants = $this->UserModel->getVariants();
        $data = $this->UserModel->getProductDetails();

        // print_r($data);
        // die;
        $dataArray = [];

        foreach ($variants as $key => $var) {
            $dataArray[$var['variant_id']]['step'] = $var['variant'];
            $dataArray[$var['variant_id']]['value'] = $var['variant'];
            $dataArray[$var['variant_id']]['title'] = $var['variant'];
            $optionCount = 0;
            foreach ($data as $key2 => $value) {
                if ($var['variant_id'] == $value['variant_id']) {
                    if ($optionCount == 0) {
                        $dataArray[$var['variant_id']]['default'] = $value['value_id'];
                    }
                    $dataArray[$var['variant_id']]['options'][$optionCount]['id'] = $value['value_id'];
                    $dataArray[$var['variant_id']]['options'][$optionCount]['image'] = "https://s3-eu-west-1.amazonaws.com/studio-henk-live/assets/Images/configurator/shape-new/configurator_symbol_tabletop_rectangular.jpg";
                    $dataArray[$var['variant_id']]['options'][$optionCount]['text'] = $value['value'];
                    $optionCount++;
                }
            }
        }


        // }        
        if (isset($data)) {
            $message = ['status' => TRUE, 'message' => 'productdetails', 'data' => $dataArray];
        } else {
            $message = ['status' => False, 'message' => 'No data'];
        }
        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201)

    }
    function productPrice_post()
    {

        // $options = [1, 4];
        $post_Array_data =  json_decode(file_get_contents("php://input"), true);
        if (!empty($post_Array_data) && isset($post_Array_data['option'])) {
            $optionStr = "";
            foreach ($post_Array_data['option'] as $key => $item) {
                $orStr = $key > 0 ? " OR " : '';
                $optionStr .=  $orStr . ' pd.value_id=' .  $item;
            }
            $variants = $this->UserModel->getVariants();
            $skuData = $this->UserModel->getPriceDetails();
            $data = $this->UserModel->getPriceDetails($optionStr);
            $dataOption = [];

            if (!empty($data)) {
                $dataOption = $this->UserModel->getOptions($data[0]['product_Variants_id']);
            }
            // print_r($data);
            // die;
            $dataArray = [];
            foreach ($variants as $key => $var) {
                $dataArray[$var['variant_id']]['step'] = $var['variant'];
                $dataArray[$var['variant_id']]['value'] = $var['variant'];
                $dataArray[$var['variant_id']]['title'] = $var['variant'];
                $dataArray[$var['variant_id']]['dropdown'] = $var['type'] == 1 ? true : false;
                $optionCount = 0;
                $dataArrayOption = [];
                foreach ($skuData as $key2 => $value) {
                    // if ($value['value_id'] == $var['value_id'])
                    if ($var['variant_id'] == $value['variant_id']) {
                        if ($optionCount == 0) {
                            $option =  $value['value_id'];
                        }
                        $flag = 0;
                        if (!empty($dataOption)) {
                            foreach ($dataOption as $opt) {
                                if ($value['value_id'] == $opt['value_id']) {
                                    $flag = 1;
                                    $option = $opt['value_id'];
                                    break;
                                }
                            }
                        }
                        if (!in_array($value['value_id'], $dataArrayOption)) {
                            $dataArrayOption[$optionCount] = $value['value_id'];
                            $option = $flag == 1 && in_array($value['value_id'], $post_Array_data['option'])  ? $value['value_id'] : $option;
                            $dataArray[$var['variant_id']]['default'] = $option;
                            $dataArray[$var['variant_id']]['options'][$optionCount]['id'] = $value['value_id'];
                            $dataArray[$var['variant_id']]['options'][$optionCount]['image'] = base_url('assets/uploads/variants/' . $value['variant_image']);
                            $dataArray[$var['variant_id']]['options'][$optionCount]['text'] = $value['value'];
                            $optionCount++;
                        }
                    }
                }
            }
            $dataArray['sku'] = $data[0]['sku'];
            $dataArray['product_Variants_id'] = $data[0]['product_Variants_id'];
            $dataArray['productVariantName'] = $data[0]['productVariantName'];
            $dataArray['price'] = $data[0]['price'];
            // $dataArray[$var['variant_id']]['productVariantImage'] = $data[0]['productVariantImage'];
            $dataArray['productVariantImage'] = base_url('assets/uploads/variations/' . $data[0]['image']);
            $message = ['status' => TRUE, 'message' => 'productdetails', 'data' => $dataArray];
            $httpResponse = REST_Controller::HTTP_CREATED;
        } else {
            $message = ['status' => False, 'message' => 'No data'];
            $httpResponse = REST_Controller::HTTP_CREATED;
        }





        // $data = [
        //     "newAmount" => "Rs 1940",
        //     "material" => "OAK",
        //     "edgeFinish" => "Standered",
        //     "image" => [
        //         [
        //             "id" => 1,
        //             "img" => "https://s3-eu-west-1.amazonaws.com/studio-henk-live/assets/Images/configurator/shape-new/configurator_symbol_tabletop_rectangular.jpg",
        //         ],
        //         [
        //             "id" => 2,
        //             "img" => "https://s3-eu-west-1.amazonaws.com/studio-henk-live/assets/Images/configurator/shape-new/configurator_symbol_tabletop_oval.jpg",
        //         ],
        //     ],
        // ];
        $this->set_response($message, $httpResponse); // CREATED (201)
    }
    public function order_post()
    {
        $post_Array_data =  json_decode(file_get_contents("php://input"), true);

        $data = [
            'line_items' => [
                [
                    'product_id' => 56179,
                    // 'sku' => $post_Array_data['sku'],
                    'quantity' => $post_Array_data['quantity'],
                    'price' => $post_Array_data['price']
                ]
            ]
        ];
        $data['billing'] = $post_Array_data['billing'];
        $data['shipping'] = $post_Array_data['shipping'];
        $data['set_paid'] = $post_Array_data['set_paid'];

        $message = "";
        $dataArray = [];
        foreach ($post_Array_data['variation'] as $key => $value) {
            $dataArray[$value['variation_name']] = $value['variation_attribute'];
        }
        $data1 = [
            'note' => json_encode($dataArray)
        ];
        // print_r(json_encode($dataArray));
        // die;
        try {
            $order_id = $this->woo->post('orders', $data);
            $this->woo->post('orders/' . $order_id->id . '/notes', $data1);
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        if ($message) {
            $message = ['status' => False, 'message' => $message];
            $httpResponse = REST_Controller::HTTP_CREATED;
        } else {
            $message = ['status' => TRUE, 'message' => 'Order Submitted succesfully'];
            $httpResponse = REST_Controller::HTTP_CREATED;
        }
        $this->set_response($message, $httpResponse); // CREATED (201)
    }
    public function payment_post()
    {
        $post_Array_data =  json_decode(file_get_contents("php://input"), true);


        $amount = $post_Array_data['amount'];
        $token = $post_Array_data['token'];
        $name = $post_Array_data['name'];
        $address = $post_Array_data['address'];

        try {
            $stripe_data = $this->woocommerce->payment($amount, $token, $name, $address);
            $message = $stripe_data;
        } catch (Exception $e) {
            $message = $e->getMessage();
        }

        if ($message) {
            $httpResponse = REST_Controller::HTTP_CREATED;
        } else {
            $message = ['status' => TRUE, 'message' => 'Payment Details'];
            $httpResponse = REST_Controller::HTTP_CREATED;
        }
        $this->set_response($message, $httpResponse); // CREATED (201)
    }
}
