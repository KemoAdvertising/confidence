<?php $this->load->view('common/header');
$segment="";
$segment2 = "";
 if($this->uri->segment(3)){
        $segment=$this->uri->segment(3);
        $segment2=$this->uri->segment(4);
    }
    $status_view = "";
    $status_add = "";
    $status_edit = "";
    $status_delete = "";
    for($i=0; $i<count($check_menu_list); $i++){
          if($check_menu_list[$i]['menu_id'] == 4){
                $status_view =   $check_menu_list[$i]['status_view'];
                $status_add =   $check_menu_list[$i]['status_add']; 
                $status_edit =   $check_menu_list[$i]['status_edit'];  
                $status_delete =   $check_menu_list[$i]['status_delete']; 
    
          }
    
    }
 ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/libs/select2/dist/css/select2.min.css">
<style>
   .form-control-lg, .input-group-lg>.form-control, .input-group-lg>.input-group-append>.btn, .input-group-lg>.input-group-append>.input-group-text, .input-group-lg>.input-group-prepend>.btn, .input-group-lg>.input-group-prepend>.input-group-text {
   padding: .5rem 1rem;
   font-size: 12px !important;
   line-height: 1.5;
   border-radius: 2px;
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
      <div class="col-5 align-self-center">
         <h4 class="page-title">Total <span class="badge badge-primary badge-pill animated flipInX"><?php if($item_count){ echo count($item_list);} ?></span></h4>
      </div>
      <div class="col-7 align-self-center">
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
<div class="button-box" style="text-align: right;">
<?php if($status_add == 1){ ?>
   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Add New Item</button>
<?php } ?>
</div>
<br>
<div class="input-group">
   <input type="text" class="form-control" placeholder="Search">
   <div class="input-group-append">
      <button class="btn btn-info" type="button">Go!</button>
   </div>
</div>
<br>
<div class="row">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-body">
            <div class="d-flex align-items-center">
               <div>
                  <h4 class="card-title">Stores</h4>
               </div>
            </div>
            <div class="table-responsive">
               <table class="table no-wrap v-middle" id="datatable">
                  <thead>
                     <tr>
                        <th class="border-0 text-muted text-center">ID  </th>
                        <th class="border-0 text-muted">Image</th>
                        <th class="border-0 text-muted">Name</th>
                        <th class="border-0 text-muted">Item's Store</th>
                        <th class="border-0 text-muted">Item's Category</th>
                        <th class="border-0 text-muted">Price</th>
                        <th class="border-0 text-muted">Type</th>
                        <th class="border-0 text-muted text-center">Created At</th>
                        <th class="border-0 text-muted text-center"></th>
                     </tr>
                  </thead>
                  <tbody>
                    <?php if($status_view == 1){ $j=1; for ($i=0; $i <count($item_list) ; $i++) { ?>
                     <tr>
                        <td class="text-center"><?php echo $j++; ?></td>
                        <td>
                           <div class="d-flex no-block align-items-center">
                              <div class="m-r-10"><img src="<?php echo base_url().'assets/uploads/item/'.$item_list[$i]['item_image'] ?>" alt="user" class="rounded-circle" width="45"></div>
                           </div>
                        </td>
                        <td><?php echo $item_list[$i]['item_name'] ?></td>
                        <td><?php echo $item_list[$i]['itemQty'] ?></td>
                        <td></td>
                         <td><?php echo $item_list[$i]['price'] ?></td>
                        <td><?php echo $item_list[$i]['price_type'] ?></td>

                        <td class="text-center"><?php echo $item_list[$i]['created_date'] ?></td>
                        <td class="font-medium text-center">    
                        <?php if($status_edit == 1){ ?>
                        <a href="<?php echo base_url().'Admin/update_item/'.$item_list[$i]['item_id'] ?>" class="btn waves-effect waves-light btn-danger">Edit</a>
                         <?php  if($item_list[$i]['status']==1){ ?>
                              <button type="button" class="btn waves-effect waves-light btn-info off" alt="<?php echo $item_list[$i]['item_id'] ?>">On</button>
                              <?php }else{ ?>
                              <button type="button" class="btn waves-effect waves-light btn-danger on" alt="<?php echo $item_list[$i]['item_id'] ?>">Off</button>
                              <?php }} ?>
                        </td>
                     </tr>
                 <?php }} ?>
                  </tbody>
               </table>
               </div>
               
            </div>
         </div>
      </div>
   </div>
</div>
<?php $this->load->view('common/footer'); ?>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">

         <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel1">New Item</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
            <form action="<?php echo base_url('Admin/all_items/'.$segment.'/'.$segment2) ?>" method="POST" enctype="multipart/form-data">
               
                            <div class="form-group row">
                  <label class="col-lg-3 col-form-label"><span class="text-danger">*</span>Stores:</label>
                  <div class="col-lg-9">
                     <select class="select2 form-control" name="store_id"  style="height: 36px;width: 100%;">
                       <?php for ($i=0; $i <count($store_list) ; $i++) { ?>
                           <option value="<?php echo $store_list[$i]['store_id'] ?>" <?php if($store_list[$i]['store_id']==$segment){ echo "selected"; } ?>><?php echo $store_list[$i]['store_name'] ?></option>
                       <?php } ?>
                   </select> 
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-lg-3 col-form-label"><span class="text-danger">*</span>Item Name:</label>
                  <div class="col-lg-9">
                     <input type="text" class="form-control form-control-lg" name="item_name" placeholder="Item Name" required="">
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-lg-3 col-form-label"><span class="text-danger">*</span>Description:</label>
                  <div class="col-lg-9">
                     <input type="text" class="form-control form-control-lg" name="description" placeholder="Item Short Description" required="">
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-lg-3 col-form-label"><span class="text-danger">*</span>Item Category:</label>
                  <div class="col-lg-9">
                     <select class="select2 form-control" name="item_cat_id[]" multiple="multiple" style="height: 36px;width: 100%;">
                        <?php for ($i=0; $i <count($itemCat_list) ; $i++) { ?>
                        <option value="<?php echo $itemCat_list[$i]['item_cat_id'] ?>"><?php echo $itemCat_list[$i]['category_name'] ?></option>
                    <?php } ?>
                     </select>
                  </div>
               </div>
               <div class="form-group">
                  <label>Image:</label>
                  <input type="file" class="form-control" name="item_image">
               </div>
               <div class="form-group row">
                  <label class="col-lg-3 col-form-label"><span class="text-danger">*</span>Item type:</label>
                  <div class="col-lg-9">
                     <select class=" form-control"  name="item_type" style="height: 36px;width: 100%;">
                        <option value="0">Not Applicable</option>
                        <option value="1">Veg</option>
                        <option value="2">Non Veg</option>
                     </select>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-lg-3 col-form-label"><span class="text-danger">*</span>Price type:</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" placeholder="Price type" name="price_type">
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-lg-3 col-form-label"><span class="text-danger">*</span>Attributs:</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control" name="attribute" placeholder="Kg/gm">
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-lg-3 col-form-label"><span class="text-danger">*</span>Price:</label>
                  <div class="col-lg-9">
                     <input type="text" class="form-control form-control-lg" name="price" placeholder="Price" required="">
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-lg-3 col-form-label">Is Recomended?</label>
                  <div class="col-lg-9">
                     <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="Is_recommended"  value="1" id="customCheck">
                        <label class="custom-control-label" for="customCheck"></label>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
         </div>
            </form>
         </div>
         
      </div>
   </div>
</div>
<!-- /.modal -->
<div class="modal fade" id="csv" tabindex="-1" role="dialog" aria-labelledby="csv">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel1">CSV Bulk Upload for Stores</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
            <div class="form-group">
               <label><span class="text-danger">*</span>CSV File:</label>                                    <input type="file" class="form-control">
            </div>
            <div class="text-left">
               <button type="button" class="btn btn-primary" id="downloadSampleRestaurantCsv">
               Download Sample CSV
               <i class="icon-file-download ml-1"></i>
               </button>
            </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Upload</button>
         </div>
      </div>
   </div>
</div>

<!-- /.modal -->

<script src="https://unpkg.com/conditionize@1/dist/conditionize.min.js"></script>
<!-- This Page JS -->
<script src="<?php echo base_url() ?>assets/libs/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url() ?>assets/libs/select2/dist/js/select2.min.js"></script>
<script src="<?php echo base_url() ?>dist/js/pages/forms/select2/select2.init.js"></script>
<!--This page JavaScript -->
<script src="<?php echo base_url() ?>assets/libs/jquery.repeater/jquery.repeater.min.js"></script>
<script src="<?php echo base_url() ?>assets/extra-libs/jquery.repeater/repeater-init.js"></script>
<script src="<?php echo base_url() ?>assets/extra-libs/jquery.repeater/dff.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
      $('#datatable').DataTable({
         responsive: true,
         stateSave: true,
         sorting:true
      });
   });
</script>
<script>
   $('form').conditionize({
   selector: '[data-cond]'
   });
</script>
<script type="text/javascript">
   $(document).ready(function() {
      $(".off").click(function() {
         var id = $(this).attr("alt");
            $.ajax({
               url: "<?php echo base_url(); ?>Admin/off_item",
               method: "POST",
               data: {
                  item_id: id
               },
               success: function(data) {
                  location.reload();
               }
            })
      });
      $(".on").click(function() {
         var id = $(this).attr("alt");
            $.ajax({
               url: "<?php echo base_url(); ?>Admin/on_item",
               method: "POST",
               data: {
                  item_id: id
               },
               success: function(data) {
                  location.reload();
               }
            })
      });
   });
</script>