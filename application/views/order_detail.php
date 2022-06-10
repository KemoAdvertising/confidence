<?php $this->load->view('common/header'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/libs/select2/dist/css/select2.min.css">
<link href="<?php echo base_url() ?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
<style>
    .form-control-lg,
    .input-group-lg>.form-control,
    .input-group-lg>.input-group-append>.btn,
    .input-group-lg>.input-group-append>.input-group-text,
    .input-group-lg>.input-group-prepend>.btn,
    .input-group-lg>.input-group-prepend>.input-group-text {
        padding: .5rem 1rem;
        font-size: 12px !important;
        line-height: 1.5;
        border-radius: 2px;
    }

    @page {
         size: A4 portrait; /* can use also 'landscape' for orientation */
         margin-top: 0.2in;
         margin-bottom: 0.88in;
         @bottom-center {
         content: counter(pages);
         }
         }
         @media print {
            body * {
                visibility: hidden;
            }
            #section-to-print, #section-to-print * {
                visibility: visible;
            }
            /* #section-to-print {
                position: absolute;
                left: 0;
                top: 0;
            } */
        }
</style>
<?php $this->load->view('common/sidebar'); ?>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <div class="d-flex align-items-center justify-content-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Items</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <div class="col-md-12 text-right">
            <button class="btn btn-primary mb-3" id="print">Print</button>
        </div>
        <div class="col-lg-12" id="section-to-print">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 ">
                            <label>Order ID:</label> <span><?php echo $order_detail[0]['order_id'] ?></span>
                        </div>
                        <div class="col-md-2 text-right">
                            <label>OTP:</label> <span><?php echo $order_detail[0]['otp']; ?></span>
                        </div>
                        <div class="col-md-4 text-right">
                            <label>Order Time:</label> <span><?php echo $order_detail[0]['created_date'] ?></span>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-lg-12">
                            <label>Delivery guy Details:</label>
                        </div>
                        <div class="col-lg-12">
                        <label>Delivery Guy Name:</label><span><?php if (!empty($delivery_guy_details)) {
                                                                        echo $delivery_guy_details[0]['user_name'];
                                                                    } ?></span><br />
                            <label>Delivery Guy Phone:</label><span><?php if (!empty($delivery_guy_details)) {
                                                                        echo $delivery_guy_details[0]['phone_no'];
                                                                    } ?></span><br />
                        </div>
                        
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            <label>Order Status:</label> <span>
                                <?php switch ($order_detail[0]['order_status']) {
                                    case 1:
                                        $order_status = "primary";
                                        $order_status_text = "Order Placed by user";
                                        break;
                                    case 2:
                                        $order_status = "info";
                                        $order_status_text = "Order Accepted by store";
                                        break;
                                    case 3:
                                        $order_status = "danger";
                                        $order_status_text = "Order Rejected";
                                        break;
                                    case 4:
                                        $order_status = "primary";
                                        $order_status_text = "Delivery Assigned";
                                        break;
                                    case 5:
                                        $order_status = "primary";
                                        $order_status_text = "Delivery Reached";
                                        break;
                                    case 6:
                                        $order_status = "primary";
                                        $order_status_text =  "Delivery Picked";
                                        break;
                                    case 7:
                                        $order_status = "success";
                                        $order_status_text = "Order Delivered";
                                        break;
                                    case 8:
                                        $order_status = "danger";
                                        $order_status_text = "Order Canceled";
                                        break;
                                    default:
                                        $order_stauts = "default";
                                        $order_status_text =  "Pending";
                                }

                                ?>
                                <span class="label label-<?php echo $order_status; ?> font-weight-100"><?php echo $order_status_text; ?></span>
                        </div>
                        <?php if($order_detail[0]['order_status'] == 3){ ?>
                            <div class="col-md-12">
                                <label>Reject Reason:</label> <span><?php echo $order_detail[0]['reject_reason']; ?></span>
                            </div>
                        <?php  } ?>
                    </div>
                    <hr>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <label>Store Name:</label> <span><?php echo $order_detail[0]['store_name']; ?></span><br />
                            <label>Store Phone:</label> <span><?php if(!empty($store_owner_details)){ echo $store_owner_details[0]['phone_no']; } ?></span><br />
                            <label>Store Address:</label> <span><?php echo $order_detail[0]['from_address']; ?></span>
                        </div>
                        <div class="col-lg-6">
                            <label>Name:</label> <span><?php echo $order_detail[0]['to_user_name'] ?></span><br/>
                            <label>Contact Number:</label> <span><?php echo $order_detail[0]['to_mobile_no'] ?></span><br/>
                            <label>Address:</label> <span><?php echo $order_detail[0]['to_address'] ?></span>
                        </div>

                    </div>
                    <hr>
                    <div class="row">

                        <div class="col-lg-12">
                            <label>Payment Status:</label><span><?php if ($order_detail[0]['payment_status'] == 0) {
                                                                    echo "Pending";
                                                                } else {
                                                                    echo  "Paid";
                                                                } ?></span>
                        </div>
                        <div class="col-lg-12">
                            <label>Payment Mode:</label><span><?php if ($order_detail[0]['payment_type_id'] == 1) {
                                                                    echo "COD";
                                                                } else {
                                                                    echo  "Online Payment";
                                                                } ?></span>
                        </div>
                        <!-- <div class="col-lg-12">
                <label>Comment/Suggestion:</label>
            </div> -->
                    </div>
                    <hr>
                    <?php for ($i = 0; $i < count($item_details); $i++) { ?>
                        <div class="row" style="
    background-color: #f7f8fb;
">
                            <div class="col-lg-3">
                                <label>Quantity : <b> <?php echo $item_details[$i]['quantity'] ?></b></label>
                            </div>
                            <div class="col-lg-6">
                                <label>Item Name : <b> <?php echo $item_details[$i]['name'] ?></b></label>
                            </div>
                            <div class="col-lg-3">
                                <label> Price :<b> <?php echo $item_details[$i]['price'] ?></b></label>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-10 text-right">
                            <h4><b>Coupon:</b></h4>
                        </div>
                        <div class="col-md-2 ">
                            <span><?php echo $order_detail[0]['coupon_code'] ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10 text-right">
                            <h4><b>Sub Total:</b></h4>
                        </div>
                        <div class="col-lg-2">
                            <span><span><?php echo $order_detail[0]['sub_total_price'] ?></span></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10 text-right">
                            <h4><b>Delivery Charge:</b></h4>
                        </div>
                        <div class="col-lg-2">
                            <span><?php echo $order_detail[0]['delivery_fee'] ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10 text-right">
                            <h4><b>Service Charge : </b></h4>
                        </div>
                        <div class="col-lg-2">
                            <span><?php echo $order_detail[0]['service_charge'] ?></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10 text-right">
                            <h4><b>Offer Discount : </b></h4>
                        </div>
                        <div class="col-lg-2">
                            <span><?php echo $order_detail[0]['offer_discount'] ?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-10 text-right">
                            <h4><b>Total Price :</b></h4>
                        </div>
                        <div class="col-lg-2">
                            <span><?php echo $order_detail[0]['total_price'] ?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12 text-right">
                        <a href="<?php echo base_url('Admin/orders?order=all') ?>" class="btn btn-default">Back</a>
                    </div>

                </div>
            </div>
            <!-- Column -->
            <?php $this->load->view('common/footer'); ?>

            <script type="text/javascript">
    $(document).on('click','#print',function(){
        window.print();
    })
</script>