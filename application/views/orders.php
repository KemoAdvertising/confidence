<?php $this->load->view('common/header'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/libs/select2/dist/css/select2.min.css">
<link href="<?php echo base_url() ?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/libs/daterangepicker/daterangepicker.css">
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
</style>
<?php $this->load->view('common/sidebar');

$params   = $_SERVER['QUERY_STRING'];
$status_view = "";
$status_add = "";
$status_edit = "";
$status_delete = "";
for($i=0; $i<count($check_menu_list); $i++){
      if($check_menu_list[$i]['menu_id'] == 6){
            $status_view =   $check_menu_list[$i]['status_view'];
            $status_add =   $check_menu_list[$i]['status_add']; 
            $status_edit =   $check_menu_list[$i]['status_edit'];  
            $status_delete =   $check_menu_list[$i]['status_delete']; 

      }

}
?>
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
            <div class="col-5 align-self-center">
                <!-- <h4 class="page-title">Total<span class="badge badge-primary badge-pill animated flipInX">31</span></h4> -->
            </div>
            <div class="col-7 align-self-center">
                <div class="d-flex align-items-center justify-content-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Orders</li>
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
        <div class="col-lg-12">
            <?php if($status_view == 1){ ?>
                <div class="row">
                    <div class="col-md-2">
                        <a href="<?php echo base_url('Admin/orders/?order=received'); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5>New Order</h5>
                                    <h4><strong><?php echo $placed_order; ?></strong></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('Admin/orders/?order=accepted'); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Accepted Order</h5>
                                    <h4><strong><?php echo $accepted_order; ?></strong></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('Admin/orders/?order=reject'); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Rejected Order</h5>
                                    <h4><strong><?php echo $rejected_order; ?></strong></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('Admin/orders/?order=assigned'); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Assigned Order</h5>
                                    <h4><strong><?php echo $assigned_order; ?></strong></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('Admin/orders/?order=reached'); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Reached Order</h5>
                                    <h4><strong><?php echo $reached_order; ?></strong></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('Admin/orders/?order=picked'); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Pickedup Order</h5>
                                    <h4><strong><?php echo $picked_order; ?></strong></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('Admin/orders/?order=completed'); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Completed Order</h5>
                                    <h4><strong><?php echo $delivered_order; ?></strong></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('Admin/orders/?order=cancel'); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Cancelled Order</h5>
                                    <h4><strong><?php echo $cancelled_order; ?></strong></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo base_url('Admin/orders/?order=all'); ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Total Order</h5>
                                    <h4><strong><?php echo $all_order; ?></strong></h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php } ?>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="<?php echo base_url('admin/orders/?' . $params) ?>" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" value="<?php $this->session->flashdata('from_date') ?>" name="from_date" class="form-control daterange" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-right mb-5">
                            <button style="background-color: #233242;" class="btn waves-effect waves-light btn-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Orders <i class="mdi mdi-chevron-down"></i></span>
                            </button>
                            <div class="dropdown-menu">
                                <div class="profile-dis scrollable">
                                    <a class="dropdown-item <?php if ($_GET['order'] == 'all') {
                                                                echo 'active';
                                                            } ?>" href="<?php echo base_url('Admin/orders/?order=all'); ?>">
                                        All Order</a>
                                    <a class="dropdown-item <?php if ($_GET['order'] == 'received') {
                                                                echo 'active';
                                                            } ?>" href="<?php echo base_url('Admin/orders/?order=received'); ?>">
                                        New Order</a>
                                    <a class="dropdown-item <?php if ($_GET['order'] == 'accepted') {
                                                                echo 'active';
                                                            } ?>" href="<?php echo base_url('Admin/orders/?order=accepted'); ?>">
                                        Accepted order</a>
                                    <a class="dropdown-item <?php if ($_GET['order'] == 'reject') {
                                                                echo 'active';
                                                            } ?>" href="<?php echo base_url('Admin/orders/?order=reject'); ?>">
                                        Rejected order</a>
                                    <a class="dropdown-item <?php if ($_GET['order'] == 'assigned') {
                                                                echo 'active';
                                                            } ?>" href="<?php echo base_url('Admin/orders/?order=assigned'); ?>">
                                        Assigned order</a>
                                    <a class="dropdown-item <?php if ($_GET['order'] == 'reached') {
                                                                echo 'active';
                                                            } ?>" href="<?php echo base_url('Admin/orders/?order=reached'); ?>">
                                        Reached Order</a>
                                    <a class="dropdown-item <?php if ($_GET['order'] == 'picked') {
                                                                echo 'active';
                                                            } ?>" href="<?php echo base_url('Admin/orders/?order=picked'); ?>">
                                        Picked Up</a>
                                    <a class="dropdown-item <?php if ($_GET['order'] == 'completed') {
                                                                echo 'active';
                                                            } ?>" href="<?php echo base_url('Admin/orders/?order=completed'); ?>">
                                        Completed</a>
                                    <a class="dropdown-item <?php if ($_GET['order'] == 'cancel') {
                                                                echo 'active';
                                                            } ?>" href="<?php echo base_url('Admin/orders/?order=cancel'); ?>">
                                        Cancelled order</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <?php if (isset($_GET['order'])) { ?>
                            <table class="table product-overview" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Order ID</th>
                                        <th>Store</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <?php $view = 0;
                                        if ($_GET['order'] == "cancel") {
                                            $view = 1;
                                        }
                                        if ($_GET['order'] == "completed") {
                                            $view = 1;
                                        } ?>
                                        <?php if ($view == 0) { ?>
                                        <th>Delivery assign</th>                                    
                                        <?php } ?>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($status_view == 1){ for ($i = 0; $i < count($order_list); $i++) { ?>
                                        <tr>
                                            <td><?php echo $order_list[$i]['user_name'] ?></td>
                                            <td><?php echo $order_list[$i]['order_id'] ?></td>
                                            <td><?php echo $order_list[$i]['store_name'] ?></td>
                                            <td><?php echo $order_list[$i]['total_price'] ?></td>
                                            <td><?php echo $order_list[$i]['created_date'] ?></td>
                                            <td> 
                                                    <?php
                                                    switch ($order_list[$i]['order_status']) {
                                                        case 1:
                                                            $order_status = "primary";
                                                            $order_status_text= "Order Placed by user";
                                                            break;
                                                        case 2:
                                                            $order_status = "info";
                                                            $order_status_text= "Order Accepted by store";
                                                            break;
                                                        case 3:
                                                            $order_status = "danger";
                                                            $order_status_text= "Order Rejected";
                                                            break;
                                                        case 4:
                                                            $order_status = "primary";
                                                            $order_status_text= "Delivery Assigned";
                                                            break;
                                                        case 5:
                                                            $order_status = "primary";
                                                            $order_status_text= "Delivery Reached";
                                                            break;
                                                        case 6:
                                                            $order_status = "primary";
                                                            $order_status_text=  "Delivery Picked";
                                                            break;
                                                        case 7:
                                                            $order_status = "success";
                                                            $order_status_text= "Order Delivered";
                                                            break;
                                                        case 8:
                                                            $order_status = "danger";
                                                            $order_status_text= "Order Canceled";
                                                            break;
                                                        default:
                                                            $order_stauts ="default";
                                                            $order_status_text =  "Pending";
                                                    }

                                                    ?>
                                                <span class="label label-<?php echo $order_status; ?> font-weight-100"><?php echo $order_status_text; ?></span> </td>
                                            <td>
                                            <?php if($status_edit == 1){ if ($view == 0 && ($order_list[$i]['order_status'] != 7 && $order_list[$i]['order_status'] != 8)) { ?>
                                                <select class="select2 form-control staff_name" alt="<?php echo $order_list[$i]['id']; ?>">
                                                    <?php if ($_GET['order'] == "received" || $_GET['order'] == "all") { ?>
                                                        <option value="" disabled selected>Select Delivery Guy</option>
                                                    <?php } else { ?>
                                                        <option value="" disabled selected>Reassign Delivery Guy</option>
                                                    <?php } ?>
                                                    <?php for ($s = 0; $s < count($staff_list); $s++) { ?>
                                                        <option value="<?php echo $staff_list[$s]['id']; ?>"><?php echo $staff_list[$s]['user_name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                            <?php }} ?>
                                            
                                            <td><?php if($status_view == 1){ ?><a href="<?php echo base_url('Admin/order_detail/' . $order_list[$i]['id']) ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="View">View <i class="fa fa-eye"></i></a> <?php } ?></td>
                                        </tr>
                                    <?php }} ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <?php $this->load->view('common/footer'); ?>


        <!--This page plugins -->
        <script src="<?php echo base_url() ?>assets/extra-libs/DataTables/datatables.min.js"></script>
        <script src="<?php echo base_url() ?>dist/js/pages/datatable/datatable-basic.init.js"></script>

        <script src="<?php echo base_url() ?>assets/libs/select2/dist/js/select2.full.min.js"></script>
        <script src="<?php echo base_url() ?>assets/libs/select2/dist/js/select2.min.js"></script>
        <script src="<?php echo base_url() ?>dist/js/pages/forms/select2/select2.init.js"></script>

        <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
        <script src="<?php echo base_url() ?>dist/js/pages/datatable/datatable-advanced.init.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/daterangepicker/daterangepicker.js"></script>
        <script>
            $('.daterange').daterangepicker();
        </script>
        <script>
            $(document).ready(function() {
                $('#datatable').DataTable({
                    responsive: true,
                    stateSave: true,
                    sorting: true,
                    dom: 'Bfrtip',
                    buttons: [
                        'csv', 'excel', 'pdf', 'print'
                    ]
                });
                $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
            });
            // $('form').conditionize({
            // selector: '[data-cond]'
            // });
            $("body").on('change', '.staff_name', function() {
                var id = $(this).attr("alt");
                var val = $(this).val();
                $.ajax({
                    url: "<?php echo base_url(); ?>admin/assign_delivery",
                    method: "POST",
                    data: {
                        order_id: id,
                        staff_id: val
                    },
                    success: function(data) {
                        alert(data);
                        location.reload();
                    }
                })
            });
        </script>