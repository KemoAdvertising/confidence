<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;
// use Automattic\WooCommerce\HttpClient\HttpClientException;

class Woocommerce
{
    protected $options;
    function __construct()
    {
        // parent::__construct();
        $this->options = array(
            'debug'           => true,
            'return_as_array' => false,
            'validate_url'    => false,
            'timeout'         => 30,
            'ssl_verify'      => false,
        );
    }
    public function request()
    {
        $client = new Client('https://masara.ro/', 'ck_b2ad3d79d6fa073fc4db43f322a303ea362179e3', 'cs_6e0ca030c947cf04d705cf9e64db00a5bec61cf1', $this->options);
        return $client;
    }
    public function payment($amount, $token, $name, $address)
    {
        require_once('application/libraries/stripe-php/init.php');

        $stripeSecret = 'sk_test_hOBquhxE5k2DbZgF3CvAoXFF00aAmVVhA2';

        \Stripe\Stripe::setApiKey($stripeSecret);

        $stripe = \Stripe\charge::create([
            "amount" => $amount,
            "currency" => "usd",
            "source" => $token,
            "shipping" => [
                'name' => $name,
                'address' => $address,
            ],
            "description" => "This is from nicesnippets.com"
        ]);

        // after successfull payment, you can store payment related information into your database

        $data = array('success' => true, 'data' => $stripe);

        echo json_encode($data);
    }
}
