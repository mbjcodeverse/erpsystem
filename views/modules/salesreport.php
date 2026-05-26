<!-- Vertical form options -->
<div class="container-fluid">
  <form class="sales-report-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-12" style="padding-left: 12px;margin-top: 13px;">
        <div class="card h-95">
          <div class="card-header d-flex bg-transparent border-bottom" style="padding-top: 12px;padding-right: 1px;padding-bottom:10px;">
              <!-- Branch Code -->
              <input type="hidden" name="branch_code" id="branch_code" value="<?php echo $_SESSION["branchcode"];?>">
              <input type="hidden" name="generatedby" id="generatedby" value="<?php echo $_SESSION["empid"];?>">

              <!-- User Type -->
              <input type="hidden" name="user_type" id="user_type" value="<?php echo $_SESSION["utype"];?>">

              <h1 style="margin-bottom:0;margin-top:0;">SALES REPORT</h1>
            
              <div class="col-sm-8 form-group" style="padding: 0px;padding-top:8px;margin:0;">
              </div>

              <!-- <div class="col-sm-2 form-group" style="padding: 0px;padding-top:1px;padding-left:7px;margin:0;float:right;"> -->
              <div class="ms-auto d-flex align-items-right" style="gap:3px;">
                <button type="button" class="btn btn-warning" disabled name="btn-export" id="btn-export" style="float:left; margin-right:3px; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
                    <i class="icon-file-spreadsheet"></i>
                </button>
                <button type="button" class="btn btn-primary" disabled name="btn-print-report" id="btn-print-report" style="float:left; margin-right:6px; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
                    <i class="icon-printer"></i>
                </button>
                <button type="button" class="btn btn-success" disabled name="btn-generate" id="btn-generate" style="float:left; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
                    <i class="icon-pencil3"></i>
                    Gen
                </button>                
              </div>
          </div>

          <div class="card-body" style="padding-bottom: 0;margin: 0;padding-top: 5px;">
              <div class="row" style="padding: 0;margin-bottom: 0;">

                <div class="col-sm-3 form-group">
                  <label for="lst-reptype">Report Type</label>
                  <select data-placeholder="< Select Report Type >" class="form-control select" data-container-css-class="bg-indigo-400" data-fouc id="lst-reptype" name="lst-reptype" required>
                    <option></option>
                    <optgroup label="GENERIC INFO">
                      <option value="1">Overall Sales Category</option>
                      <option value="2">Category + Product Description</option>
                      <option value="3">Sales Sequence Details</option>
                    <!-- <optgroup label="BRANCH SALES COMPARISON">   
                      <option value="8">Monthly Sales Matrix</option>  
                      <option value="9">Daily Sales Plot</option> -->
                  </select>
                </div>

                <div class="col-sm-2 form-group">
                  <label for="lst-branchcode" id="lbl-lst-branchcode" style="color:aqua;">= &gt; Branch</label>
                  <select data-placeholder="< Select Branch >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="lst-branchcode" name="lst-branchcode">
                    <option></option>
                    <?php
                        $branches = (new ControllerBranch)->ctrShowBranchList();
                        foreach ($branches as $key => $value) {
                          echo '<option value="'.$value["branchcode"].'">'.$value["bname"].'</option>';
                        }
                    ?>
                 </select>
                </div> 

                <div class="col-sm-3 form-group">
                  <div class="form-group">
                    <label>Date Range</label>
                    <div class="input-group">
                      <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-calendar22"></i></span>
                      </span>
                      <input type="text" class="form-control daterange-basic" id="lst_date_range" name="lst_date_range" required> 
                    </div>
                  </div>
                </div>

                <div class="col-sm-2 form-group">
                  <label for="lst-categorycode" id="lbl-lst-categorycode" style="color:aqua;">= &gt; Category</label>
                  <select data-placeholder="< Select Category >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" id="lst-categorycode" name="lst-categorycode">
                    <option></option>
                    <?php
                        $category = (new ControllerCategory)->ctrShowCategoryList();
                        foreach ($category as $key => $value) {
                          echo '<option value="'.$value["categorycode"].'">'.$value["catdescription"].'</option>';
                        }
                     ?>
                  </select>
                </div> 

                <div class="col-sm-1 form-group">
                  <label for="lst-salemode" id="lbl-lst-salemode" style="color:aqua;">= &gt; Mode</label>
                  <select data-placeholder="< Select Mode >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-salemode" name="lst-salemode" required>
                    <option></option>
                    <option value="Counter" selected>Counter</option>
                    <!-- <option value="Cash">Cash</option>
                    <option value="Credit">Credit</option> -->
                  </select>
                </div>            

                <div class="col-sm-1 form-group">
                  <label for="lst-status" id="lbl-lst-status" style="color:aqua;">= &gt; Status</label>
                  <select data-placeholder="< Select Status >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-status" name="lst-status" required>
                    <option></option>
                    <option value="Sold" selected>Sold</option>
                    <option value="Void">Void</option>
                  </select>
                </div>
              </div>                                        
          </div>  <!-- card body -->



          <hr style="margin:0;padding: 0;padding-bottom: 24px;">

          <div class="progress-container" style="width: 100%; display: none;">
            <label>Generating report...</label>
            <progress id="progress-bar" value="0" max="100" style="width: 100%; height: 20px;"></progress>
            <span id="progress-text"></span>
          </div>
          
          <!-- <div class="spinner-book" style="display: none; justify-content: center; align-items: center; margin: 0 auto;">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
          </div> -->

          <div class="row sales_content" id="sales_content" style="min-height:62vh;">

          </div> 
          
          <div class="card-footer" style="padding-top: 0;margin-top: 0;">
          </div>  <!-- footer -->
       </div>     <!-- card -->
      </div>

    </div>  <!-- row -->
  </form>
</div>

<script src="views/js/salesreport.js?v=<?php echo filemtime('views/js/salesreport.js'); ?>"></script>


