<?php $this->load->view('common/header'); ?>
<?php $this->load->view('common/sidebar');
$segment = $this->uri->segment(3);
$segment_url = "/" . $segment;
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
                <a type="button" href="<?php echo base_url('Admin/attributes') ?>" class="btn waves-effect waves-light btn-danger">Add new</a>
            </div>
            <div class="col-7 align-self-center">
                <div class="d-flex align-items-center justify-content-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Variations</li>
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
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table no-wrap v-middle" id="datatable">
                                    <thead>
                                        <tr>
                                            <th class="border-0 text-muted text-center">ID </th>
                                            <th class="border-0 text-muted">Variant name</th>
                                            <th class="border-0 text-muted text-center">Variant SKU</th>
                                            <th class="border-0 text-muted text-center">price</th>
                                            <th class="border-0 text-muted text-center">Image</th>
                                            <th class="border-0 text-muted text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    if (isset($variations)) {  ?>
                                        <tbody>
                                            <?php $j = 1;
                                            for ($i = 0; $i < count($variations); $i++) {
                                            ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $j++; ?></td>
                                                    <td class="text-left">
                                                        <?php echo $variations[$i]['productVariantName'] ?>
                                                    </td>
                                                    <td class="text-left">
                                                        <?php echo $variations[$i]['sku'] ?>
                                                    </td>
                                                    <td class="text-left">
                                                        <?php echo $variations[$i]['price'] ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a type="button" href="<?php echo base_url('assets/uploads/variations/' . $variations[$i]['image']) ?>" target="_blank">View Image</a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a type="button" href="<?php echo base_url('Admin/variations/' . $variations[$i]['product_Variants_id']) ?>" class="btn waves-effect waves-light btn-info">Edit</a>
                                                        <button type="button" alt="<?php echo $variations[$i]['product_Variants_id']; ?>" class="btn waves-effect waves-light btn-danger btn-delete-variation">Delete</button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                            <?php if ($this->session->flashdata('error_msg')) {
                                echo '<div class="alert alert-danger">' . $this->session->flashdata('error_msg') . '</div>';
                            } ?>
                            <?php if (isset($error_msg) && $error_msg != '') {
                                echo '<div class="alert alert-danger">' . $error_msg . '</div>';
                            } ?>
                            <?php $this->session->userdata('post_data');
                            if ($this->session->flashdata('succ_msg')) {
                                echo '<div class="alert alert-success">' . $this->session->flashdata('succ_msg') . '</div>';
                            } ?>
                            <h4>Add Variations</h4>
                            <form action="<?php echo base_url('Admin/variations' . $segment_url) ?>" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-form-label"><span class="text-danger">*</span>Variant Name:</label>
                                            <input type="text" class="form-control form-control-lg" name="variant_name" placeholder="Variant Name" required="" value="<?php if (isset($edit_variation)) {
                                                                                                                                                                            echo $edit_variation[0]['productVariantName'];
                                                                                                                                                                        } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-form-label"><span class="text-danger">*</span>Variant Sku:</label>
                                            <input type="text" class="form-control form-control-lg" name="variant_sku" placeholder="Variant SKU" required="" value="<?php if (isset($edit_variation)) {
                                                                                                                                                                        echo $edit_variation[0]['sku'];
                                                                                                                                                                    } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-form-label"><span class="text-danger">*</span>Variant Price:</label>
                                            <input type="number" class="form-control form-control-lg" name="variant_price" placeholder="Variant Price" required="" value="<?php if (isset($edit_variation)) {
                                                                                                                                                                                echo $edit_variation[0]['price'];
                                                                                                                                                                            } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-form-label"><span class="text-danger">*</span>Image</label>
                                            <input type="file" class="form-control" name="variation_image">
                                            <?php if (isset($edit_variation)) {
                                                if ($edit_variation[0]['image']) { ?>
                                                    <a href="<?php echo base_url('assets/uploads/variations/' . $edit_variation[0]['image']); ?>" target="_blank">View Image</a>
                                            <?php }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:40px;">
                                    <div class="col-md-12 text-left">
                                        <h4>Add Variation Values</h4>
                                    </div>
                                </div>
                                <?php foreach ($options as $o => $option) { ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <label class="col-form-label"><span class="text-danger">*</span><?php echo $option['variant']; ?></label>
                                                <select class="form-control" name="variation_value[]" id="<?php echo $option['variant']; ?>">
                                                    <?php foreach ($attributes as $a => $attribute) {
                                                        if ($option['variant_id'] == $attribute['variant_id']) {
                                                    ?>
                                                            <option value="<?php echo $attribute['value_id'] ?>" <?php if (isset($edit_variation_value)) {
                                                                                                                        foreach ($edit_variation_value as $key => $value) {
                                                                                                                            if ($value['value_id'] == $attribute['value_id']) {
                                                                                                                                echo "selected";
                                                                                                                            }
                                                                                                                        }
                                                                                                                    } ?>><?php echo $attribute['value']; ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (isset($edit_variation_value)) {
                                    foreach ($edit_variation_value as $evv => $e_variation_value) {
                                ?>
                                        <input type="hidden" name="product_details_id[]" value="<?php echo $e_variation_value['product_detail_id']; ?>">
                                <?php }
                                } ?>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Column -->
        <?php $this->load->view('common/footer'); ?>

        <script>
            $(document).ready(function() {
                $('#datatable').DataTable({
                    responsive: true,
                    stateSave: true,
                });


                $("body").on('click', '.btn-delete-variation', function() {
                    var id = $(this).attr("alt");
                    if (confirm("Are you sure you want to delete ?")) {
                        $.ajax({
                            url: "<?php echo base_url(); ?>Admin/vairation_delete",
                            method: "POST",
                            data: {
                                id: id
                            },
                            success: function(data) {
                                alert(data);
                                location.reload();
                            }
                        })
                    } else {
                        return false;
                    }
                });

            });
        </script>