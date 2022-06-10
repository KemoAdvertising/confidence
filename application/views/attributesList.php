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

         </div>
         <div class="col-7 align-self-center">
            <div class="d-flex align-items-center justify-content-end">
               <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item">
                        <a href="#">Home</a>
                     </li>
                     <li class="breadcrumb-item active" aria-current="page">Attributes List</li>
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
      <div class="row">
         <div class="col-lg-12">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex align-items-center">
                     <div>
                        <h4 class="card-title">Attribute List <b><?php if (isset($attributes)) {
                                                                     echo $attributes[0]['variant'];
                                                                  } ?></b></h4>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table no-wrap v-middle" id="datatable">
                        <thead>
                           <tr>
                              <th class="border-0 text-muted text-center">ID </th>
                              <th class="border-0 text-muted">Attribute Name</th>
                              <th class="border-0 text-muted">Attribute Image</th>
                           </tr>
                        </thead>
                        <?php
                        if (isset($attributes)) {  ?>
                           <tbody>
                              <?php $j = 1;
                              for ($i = 0; $i < count($attributes); $i++) {
                              ?>
                                 <tr>
                                    <td class="text-center"><?php echo $j++; ?></td>
                                    <td class="text-left">
                                       <?php echo $attributes[$i]['value'] ?>
                                    </td>
                                    <td class="text-left">
                                       <a type="button" href="<?php echo base_url('assets/uploads/variants/' . $attributes[$i]['variant_image']) ?>" target="_blank">View Image</a>
                                    </td>
                                 </tr>
                              <?php } ?>
                           </tbody>
                        <?php } ?>
                        </tbody>
                     </table>
                  </div>

               </div>
            </div>
         </div>
      </div>
   </div>
   <?php $this->load->view('common/footer'); ?>
   <script type="text/javascript">
      $(document).ready(function() {
         $('#datatable').DataTable({
            responsive: true,
            stateSave: true,
            sorting: true
         });
      });
   </script>