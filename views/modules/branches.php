<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">

  <div class="col-md-6 mx-auto">
  <form role="form" id="form-branch" method="POST" autocomplete="nope">
    <div class="card">
      <!-- <div class="loader-transparent rounded"></div> -->
      <div class="card-header d-flex bg-transparent border-bottom">
        <h5 class="card-title flex-grow-1 profile-header-title">BRANCH INFORMATION</h5> 
        <div class="header-elements">
          <div class="list-icons">
            <!-- <a class="list-icons-item" data-action="collapse"></a>
            <a class="list-icons-item" data-action="reload"></a> -->
            <a class="list-icons-item" data-action="remove"></a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>

        <div class="row">
            <div class="col-sm-6 form-group">
                <label for="txt-bname">Branch Name</label>
                <input type="text" class="form-control" id="txt-bname" name="txt-bname" autocomplete="nope" required>
            </div>

            <div class="col-sm-2 form-group">
                <label for="txt-prefix">Prefix Code</label>
                <input type="text" class="form-control" id="txt-prefix" name="txt-prefix" autocomplete="nope" required>
            </div>                                                               
            <div class="col-md-2 form-group">
                <label class="d-block font-weight-semibold">Status</label>
                <div class="custom-control custom-checkbox custom-control-inline">
                  <input type="checkbox" class="custom-control-input" id="chk-isactive" name="chk-isactive" value="1" checked>
                  <label class="custom-control-label" for="chk-isactive">Active</label>
                </div>
            </div>

            <div class="col-sm-2 form-group">
                <label for="txt-branchcode">Branch ID</label>
                <input type="text" class="form-control profile-code" id="txt-branchcode" name="txt-branchcode" readonly="true">
            </div>
        </div>

        <div class="row">                   
            <div class="col-sm-12 form-group">
                <label for="tns-baddress">Address</label>
                <input type="text" class="form-control" id="tns-baddress" name="tns-baddress" autocomplete="nope" required>
            </div>                         
        </div>        

        <div class="row">
            <div class="col-sm-4 form-group">
              <label for="sel-allowedtrans">Allowed Sales Transaction</label>
              <select data-placeholder="Transaction" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-allowedtrans" name="sel-allowedtrans" required>
                <option></option>
                <option value="Cash Only">Cash Only</option>
                <option value="Credit Only">Credit Only</option>
                <option value="Cash - Credit">Cash - Credit</option>
              </select>
            </div>
 
            <div class="col-sm-8 form-group">
                <label for="tns-bcontactnum">Contact #</label>
                <input type="text" class="form-control" id="tns-bcontactnum" name="tns-bcontactnum" autocomplete="nope">
            </div>                        
        </div>

        <div class="row">
            <div class="col-sm-4 form-group">
              <label for="sel-allowedtrans">Reset Detail</label>
              <select data-placeholder="Reset Info" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-resetdetail" name="sel-resetdetail" required>
                <option></option>
                <option value="By Product Category">By Product Category</option>
                <option value="By Product Name">By Product Name</option>
              </select>
            </div> 

            <div class="col-sm-8 form-group">
                <label for="tns-bdescription">Description</label>
                <input type="text" class="form-control" id="tns-bdescription" name="tns-bdescription" autocomplete="nope">
            </div>          
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <div class="custom-control custom-checkbox custom-control-inline">
                  <input type="checkbox" class="custom-control-input" id="chk-grantincentive" name="chk-grantincentive" value="1" checked>
                  <label class="custom-control-label" for="chk-grantincentive">Grant Seller Incentive</label>
                </div>
            </div>
        </div>                
 
        <div class="clearfix">
          <span class="float-left">
            
          </span>

          <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
          <input type="hidden" id="num-id" name="num-id">

          <span class="float-right">
            <button type="button" class="btn btn-light btn-lg" id="btn-new"><i class="icon-file-text mr-2"></i> New</button>
           
            <button type="submit" class="btn btn-light btn-lg"><i class="icon-floppy-disk mr-2"></i> Save</button>

            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-branch"><i class="icon-search4 mr-2"></i> Search</button>            
          </span>
        </div>     
      </div>  <!-- card body -->

    </div>
  </form>
  </div>
</div>

<div id="modal-search-branch" class="modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;BRANCH LIST</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <div class="modal-body">
        <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header branchTable" width="100%">
          <thead>
            <tr>
              <th>Branch Name</th>
              <th>Address</th>
              <th>Contact #</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- <script src="views/js/branches.js"></script> -->
<script src="views/js/branches.js?v=<?php echo filemtime('views/js/branches.js'); ?>"></script>

