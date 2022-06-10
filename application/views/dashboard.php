<?php $this->load->view('common/header'); ?>
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
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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
            <div class="row">
                <div class="col-lg-4">
                    <a href="<?php echo base_url() ?>Admin/attributes">
                        <div class="card">
                            <div class="card-body">
                                Add Attributes
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4">
                    <a href="<?php echo base_url() ?>Admin/variations">
                        <div class="card">
                            <div class="card-body">
                                Add Variation
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Column -->
        <?php $this->load->view('common/footer'); ?>