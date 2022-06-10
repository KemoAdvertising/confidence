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
                            <li class="breadcrumb-item active" aria-current="page">Attributes</li>
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
                                            <th class="border-0 text-muted">Option Name</th>
                                            <th class="border-0 text-muted text-center">View attributes</th>
                                            <th class="border-0 text-muted text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    if (isset($options)) {  ?>
                                        <tbody>
                                            <?php $j = 1;
                                            for ($i = 0; $i < count($options); $i++) {
                                            ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $j++; ?></td>
                                                    <td class="text-left">
                                                        <?php echo $options[$i]['variant'] ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <a type="button" href="<?php echo base_url('Admin/attributesList/' . $options[$i]['variant_id']) ?>" class="btn waves-effect waves-light btn-primary" target="_blank">View Attributes</a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a type="button" href="<?php echo base_url('Admin/attributes/' . $options[$i]['variant_id']) ?>" class="btn waves-effect waves-light btn-info">Edit</a>
                                                        <button type="button" alt="<?php echo $options[$i]['variant_id']; ?>" class="btn waves-effect waves-light btn-danger btn-delete-option">Delete</button>
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
                            <h4>Add Attribute</h4>
                            <form action="<?php echo base_url('Admin/attributes' . $segment_url) ?>" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-form-label"><span class="text-danger">*</span>Option Name:</label>
                                            <input type="text" class="form-control form-control-lg" name="option_name" placeholder="Option Name" required="" value="<?php if (isset($edit_option)) {
                                                                                                                                                                        echo $edit_option[0]['variant'];
                                                                                                                                                                    } ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-form-label"><span class="text-danger">*</span>Option Type:</label>
                                            <select class="form-control" name="type" id="delivery">
                                                <option value="0" <?php if (isset($edit_option) && isset($edit_option[0]['type'])) {
                                                                        if ($edit_option[0]['type']  == 0) {
                                                                            echo 'selected';
                                                                        }
                                                                    } ?>>Option</option>
                                                <option value="1" <?php if (isset($edit_option) && isset($edit_option[0]['type'])) {
                                                                        if ($edit_option[0]['type']  == 1) {
                                                                            echo 'selected';
                                                                        }
                                                                    } ?>>Dropdown</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-secondary add_field_button">Add Attribute</button>
                                    </div>
                                </div>
                                <div class="input_fields_wrap">
                                    <?php if (isset($edit_attribute) && count($edit_attribute) > 0) {
                                        for ($j = 0; $j < count($edit_attribute); $j++) { ?>
                                            <div class="row">
                                                <button class="btn-delete-attribute btn btn-xs btn-danger" style="min-width: 155px;max-height: 35px;margin-top: 32px;" alt="<?php echo $edit_attribute[$j]['value_id']; ?>">Remove</button>
                                                <div class=" col-md-12">
                                                    <div class="form-group row">
                                                        <label class="col-form-label"><span class="text-danger">*</span>Attribute Name:</label>
                                                        <input type="text" class="form-control form-control-lg" name="attribute_name[]" placeholder="Attribute Name" required="" value="<?php if (isset($edit_attribute)) {
                                                                                                                                                                                            echo $edit_attribute[$j]['value'];
                                                                                                                                                                                        } ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group row">

                                                        <label class=" col-form-label"><span class="text-danger">*</span>Image</label>
                                                        <input type="file" class="form-control" name="attribute_image<?php echo $j; ?>">
                                                        <?php if (isset($edit_attribute)) {
                                                            if ($edit_attribute[$j]['variant_image']) { ?>
                                                                <a href="<?php echo base_url('assets/uploads/variants/' . $edit_attribute[$j]['variant_image']); ?>" target="_blank">View Image</a>
                                                        <?php }
                                                        } ?>
                                                    </div>
                                                </div>

                                            </div>
                                        <?php }
                                    } else { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-form-label"><span class="text-danger">*</span>Attribute Name:</label>
                                                    <input type="text" class="form-control form-control-lg" name="attribute_name[]" placeholder="Attribute Name" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-form-label"><span class="text-danger">*</span>Image</label>
                                                    <input type="file" class="form-control" name="attribute_image0">
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
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

                var max_fields = 100; //maximum input boxes allowed
                var wrapper = $(".input_fields_wrap"); //Fields wrapper
                var add_button = $(".add_field_button"); //Add button ID
                var count_x = "<?php echo (isset($edit_attribute) && count($edit_attribute) > 0) ? (count($edit_attribute)) : ("1"); ?>";
                var x = count_x; //initlal text box count




                $(add_button).click(function(e) { //on add input button click
                    e.preventDefault();
                    if (x < max_fields) { //max input box allowed
                        const division = `<div class="row"> 
                        <button class="remove_field btn btn-xs btn-danger" style="min-width: 155px;max-height: 35px;margin-top: 32px;">Remove</button>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-form-label"><span class="text-danger">*</span>Attribute Name:</label>
                                                    <input type="text" class="form-control form-control-lg" name="attribute_name[]" placeholder="Attribute Name" required="">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-form-label"><span class="text-danger">*</span>Image</label>
                                                    <input type="file" class="form-control" name="attribute_image${count_x}">
                                                </div>
                                            </div>
                                            
                                            </div>`;
                        x++; //text box increment
                        $(wrapper).append(division); //add input box
                    }
                });
                $(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
                    e.preventDefault();
                    $(this).parent('div').remove();
                    x--;
                })

                $("body").on('click', '.btn-delete-attribute', function() {
                    var id = $(this).attr("alt");
                    if (confirm("Are you sure you want to delete ?")) {
                        $.ajax({
                            url: "<?php echo base_url(); ?>Admin/attribute_option_delete",
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
                $("body").on('click', '.btn-delete-option', function() {
                    var id = $(this).attr("alt");
                    if (confirm("Are you sure you want to delete ?")) {
                        $.ajax({
                            url: "<?php echo base_url(); ?>Admin/attribute_delete",
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