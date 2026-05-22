<!-- Vertical form options -->
<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
  <div class="col-md-10 mx-auto">
  <form role="form" id="form-employee" method="POST" autocomplete="nope">
    <div class="card" style="border:1px solid rgba(255, 255, 255, 0.1);box-shadow: 10px 10px 30px rgba(0, 0, 0, 0.7); border-radius: 10px;">
      <!-- <div class="loader-transparent rounded"></div> -->
      <div class="card-header d-flex bg-transparent border-bottom" style="border:1px solid rgba(255, 255, 255, 0.3);box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5); border-radius: 10px;">
        <h5 class="card-title flex-grow-1 profile-header-title">EMPLOYEE INFORMATION</h5> 
        <input type="hidden" name="trans_type" id="trans_type" value="New" required>
        <input type="hidden" id="employees-access" name="employees-access" value="<?php echo $_SESSION["employees"];?>">
        <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
            <!-- <a class="list-icons-item" data-action="reload"></a> -->
            <a class="list-icons-item" data-action="remove"></a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
            <div class="col-sm-4 form-group">
                <label for="txt-lname">Lastname</label>
                <input type="text" class="form-control text-capitalize" id="txt-lname" name="txt-lname" autocomplete="nope" required>
            </div>

            <div class="col-sm-4 form-group">
                <label for="tns-fname">Firstname</label>
                <input type="text" class="form-control text-capitalize" id="tns-fname" name="tns-fname" autocomplete="nope" required>
            </div>

            <div class="col-sm-1 form-group">
                <label for="txt-mi">MI</label>
                <input type="text" class="form-control text-capitalize" id="txt-mi" maxlength='1' name="txt-mi" autocomplete="nope">
            </div>                                            

            <div class="col-md-1 form-group">
                <label class="d-block font-weight-semibold">Status</label>
                <div class="custom-control custom-checkbox custom-control-inline">
                  <input type="checkbox" class="custom-control-input" id="chk-isactive" name="chk-isactive" value="1" checked>
                  <label class="custom-control-label" for="chk-isactive">Active</label>
                </div>
            </div>

            <div class="col-sm-2 form-group">
                <label for="txt-empid">Emp Code</label>
                <input type="text" class="form-control profile-code" id="txt-empid" name="txt-empid" autocomplete="nope" required readonly="true">
            </div>
        </div>

        <div class="row">                  
            <div class="col-sm-2 form-group">
              <label for="date-bday">B-Day</label>
              <input type="text" class="form-control datepicker" data-mask="99/99/9999" placeholder="Pick a date&hellip;" id="date-bday" name="date-bday">
            </div>

            <div class="col-sm-2 form-group">
              <label for="sel-gender">Gender</label>
              <select data-placeholder="Select Gender" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-gender" name="sel-gender" required>
                <option></option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            
            <div class="col-sm-8 form-group">
                <label for="tns-address">Address</label>
                <input type="text" class="form-control" id="tns-address" name="tns-address" autocomplete="nope">
            </div>
        </div> 

        <div class="row">
            <div class="col-sm-4 form-group">
              <label for="sel-position">Designation</label>
              <select data-placeholder="< Select Position >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-position" name="sel-position" required>
              <!-- <select class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="sel-position" name="sel-position" required> -->
                <option value="" selected hidden disabled>&lt;&nbsp;Select Position&nbsp;&gt;</option>
                <?php
                    $position = (new ControllerEmployees)->ctrShowPosition();
                    foreach ($position as $key => $value) {
                      echo '<option value="'.$value["positioncode"].'">'.$value["positiondesc"].'</option>';
                    }
                 ?>
              </select>
            </div>

            <div class="col-sm-4 form-group">
                <label for="num-mobile">Mobile #</label>
                <input type="text" class="form-control" id="num-mobile" name="num-mobile" value="" autocomplete="nope">
            </div>

            <div class="col-sm-4 form-group">
              <label for="sel-estatus">Status</label>
              <select data-placeholder="Select Status" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-estatus" name="sel-estatus" required>
                <option></option>
                <option value="Regular">Regular</option>
                <option value="Probationary">Probationary</option>
                <option value="Contractual">Contractual</option>
              </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3 form-group">
                <label for="num-sssno">SSS ID</label>
                <input type="text" class="form-control" id="num-sssno" name="num-sssno" autocomplete="nope">
            </div>
            <div class="col-sm-3 form-group">
                <label for="num-phino">PhilHealth ID</label>
                <input type="text" class="form-control" id="num-phino" name="num-phino" autocomplete="nope">
            </div>  
            <div class="col-sm-3 form-group">
                <label for="num-pagibig">Pag-ibig ID</label>
                <input type="text" class="form-control" id="num-pagibig" name="num-pagibig" autocomplete="nope">
            </div>                                            
            <div class="col-sm-3 form-group">
                <label for="num-tin">TIN</label>
                <input type="text" class="form-control" id="num-tin" name="num-tin" autocomplete="nope">
            </div>
        </div> 
        <div class="clearfix">
          <span class="float-left">
          </span>

          <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
          <input type="hidden" id="num-id" name="num-id">

          <span class="float-right">
            <button type="button" class="btn btn-light btn-lg" id="btn-new" onClick="location.href='employees'"><i class="icon-file-text mr-2"></i> New</button>

            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-employee"><i class="icon-search4 mr-2"></i> Search</button>
           
            <button type="button" class="btn btn-light btn-lg" id="btn-save"><i class="icon-floppy-disk mr-2"></i> Save</button>
          </span>
        </div>     
      </div>  <!-- card body -->

    </div>
  </form>
  </div>
</div>

<!-- ============== Employee List ============ -->
<div id="modal-search-employee" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name" style="margin-top:-3px;"><i class="icon-menu7 mr-2"></i> &nbsp; EMPLOYEE LIST&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

        <div class="row" pb-0 style="margin:10px;margin-bottom: -10px;">
            <div class="col-sm-2 form-group" style="padding: 0px;padding-top:28px;padding-left:7px;margin:0;">
                <button type="button" class="btn btn-warning" name="btn-export" id="btn-export" style="float:right; margin-right:3px; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
                    <i class="icon-file-spreadsheet"></i>
                </button>
                <button type="button" class="btn btn-primary" name="btn-print-employees" id="btn-print-products" style="float:right; margin-right:6px; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
                    <i class="icon-printer"></i>
                </button>
            </div>    

            <div class="col-12" style="margin:10px;margin-top:-25px;">
              <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header employeeListTable" width="100%">
                  <thead>
                      <tr>
                      <th style="min-width: 130px;">Emp ID</th>
                      <th style="min-width: 160px;">Lastname</th>
                      <th style="min-width: 160px;">Firstname</th>
                      <th style="min-width: 25px;">MI</th>
                      <th style="min-width: 120px;">Designation</th>
                      <th style="min-width: 160px;">Mobile</th>
                      <th style="min-width: 130px;">Status</th>
                      <th style="max-width: 110px;">Act</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
            </div>
        </div>
    </div>
  </div>
</div>


<script src="views/js/employees.js?v=<?php echo filemtime('views/js/employees.js'); ?>"></script>

<!-- <script src="views/js/employees.js"></script> -->

