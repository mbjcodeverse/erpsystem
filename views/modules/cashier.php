<!-- Vertical form options -->
<div class="container-fluid">
  <form class="cashier-form" method="POST" autocomplete="nope">
    <div class="row">
      <div class="col-md-6" style="padding-left: 5px;margin-top: 5px;padding-right: 5px;">
        <div class="card h-100">
          <div class="card-header d-flex bg-transparent border-bottom" style="margin:0;padding:0;padding-left:10px;padding-top: 12px;padding-right: 1px;padding-bottom: 3px;">
            <h4 class="card-title flex-grow-1 transaction-name" style="padding-top: 5px;">ORDER FORM</h4>
              <!-- Posted by -->
              <input type="hidden" name="tns-postedby" id="tns-postedby" value="<?php echo $_SESSION["empid"];?>">

              <!-- User Type -->
              <input type="hidden" name="user_type" id="user_type" value="<?php echo $_SESSION["utype"];?>">

              <!-- Branch Code -->
              <input type="hidden" name="branch_code" id="branch_code" value="<?php echo $_SESSION["branchcode"];?>">

              <!-- Branch Prefix -->
              <input type="hidden" name="prefix" id="prefix" value="">

              <!-- Order Code -->
              <input type="hidden" name="ordercode" id="ordercode" value="">

              <!-- Invno -->
              <input type="hidden" name="invno" id="invno" value="">

              <!-- Branch Name -->
              <input type="hidden" name="branch_name" id="branch_name" value="">  

              <!-- User ID -->
              <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION["userid"];?>">

              <!-- Grant Incentive -->
              <input type="hidden" name="grant_incentive" id="grant_incentive" value="">

              <!-- Transaction type (New / Update) -->
              <input type="hidden" name="trans_type" id="trans_type" value="New" required> 

              <!-- ============================================= -->

              <div class="col-sm-5 form-group" style="padding: 0px;padding-top:8px;padding-right:8px;margin:0;">
                <input type="text" class="form-control" id="tns-soldto" name="tns-soldto" value="" placeholder="Sold to">
              </div>

              <!-- Date -->
              <div class="col-sm-2 form-group" style="padding: 0px;padding-top:8px;padding-right:8px;margin:0;">
                <input type="text" class="form-control" id="date-sdate" name="date-sdate" value="" readonly>
              </div>
          </div>

          <div class="card-body" style="padding-bottom: 0;margin-bottom: 0;">
              <div class="row" style="padding: 0;margin-bottom: 0;">
                 <!-- <div class="table-responsive"> -->
                  <div class="table-responsive" style="min-height:65vh;max-height: clamp(65px,100vh,65vh);overflow: auto;padding-top: 0px;">
                <!-- <div class="table-responsive" style="min-height:59vh;max-height: clamp(65px,100vh,59vh);overflow: auto;padding-top: 0px;"> -->
                  <table class="table transaction-header-product-list" id="trans-table">
                    <thead class="sticky-top">
                      <tr>
                        <td width="50%">Item Description</td>
                        <td width="15%" style="text-align: right;">Qty</td>
                        <td width="15%" style="text-align: right;">Unit Price</td>
                        <td width="15%" style="text-align: right;">Amount</td>
                      </tr>                    
                    </thead>
                    <tbody class="enlisted_products" id="product_list">
                    </tbody>
                  </table>
                </div>
              </div>                          
            
              <input type="hidden" name="productList" id="productList">                
          </div>  <!-- card body -->
          
          <div class="card-footer" style="padding: 0;margin: 0;margin-bottom: 12px;padding-left: 10px;padding-right: 10px;">
            <div class="row">
             <table class="table transaction-footer" style="margin-left: 10px;margin-right: 10px;">
               <tbody>
              <tr>
                  <td style="width:14%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;padding-right:7px;">SELLER</td>
                  <td colspan="3" style="width:35%;padding:3px;">
                     <input type="text" class="form-control" id="tns-seller" name="tns-seller" autocomplete="nope" readonly="true">
                  </td>
                  <td class="overall_total" style="width:14%;font-size: 1.3em;text-align: right;font-weight: 400;padding-top: 3px;padding-bottom: 3px;padding-right:7px;">AMNT DUE</td>

                  <td style="width:16%;font-size: 1.3em;padding: 3px;;">
                      <input type="text" class="form-control" style="text-align: right;" id="num-amount" name="num-amount" autocomplete="nope" value="0.00" required readonly="true">
                  </td>
                </tr>

                <tr>
                  <td style="width:14%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;padding-right:7px;">VATABLE</td>

                  <td style="width:17%;padding: 3px;">
                      <input type="text" style="text-align: right;" class="form-control" id="num-vatable" name="num-vatable" autocomplete="nope" value="0.00" required readonly="true">
                  </td>

                  <td style="width:18%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;padding-right:7px;">VAT Excempt</td>

                  <td style="width:17%;padding: 3px;">
                      <input type="text" style="text-align: right;" class="form-control" id="num-excempt" name="num-excempt" autocomplete="nope" value="0.00" required readonly="true">
                  </td>      

                  <td class="discount_total" style="width:14%;font-size: 1.3em;text-align: right;font-weight: 400;padding-top: 3px;padding-bottom: 3px;padding-right: 7px;">DISCOUNT</td>

                  <td style="width:16%;font-size: 1.3em;padding: 3px;">
                      <input type="text" style="text-align: right;" class="form-control numeric" id="num-discount" name="num-discount" value="0.00" autocomplete="nope" readonly="true" disabled>
                  </td>
                </tr>

                <tr>
                  <td style="width:14%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;padding-right:7px;">VAT Amnt</td>
                  <td style="width:17%;padding: 3px;">
                      <input type="text" style="text-align: right;" class="form-control" id="num-vatamnt" name="num-vatamnt" autocomplete="nope" value="0.00" required readonly="true">
                  </td>

                  <td style="width:18%;font-size: 1.1em;padding-top: 3px;padding-bottom: 3px;text-align: right;padding-right:7px;">Zero-rated</td>

                  <td style="width:17%;padding: 3px;">
                      <input type="text" style="text-align: right;" class="form-control" id="num-zerorated" name="num-zerorated" autocomplete="nope" value="0.00" required readonly="true">
                  </td>                  

                  <td class="net_total" style="width:14%;font-size: 1.3em;text-align: right;font-weight: 400;padding-top: 3px;padding-bottom: 3px;padding-right: 7px;">NET DUE</td> 

                  <td style="width:16%;font-size: 1.3em;padding: 3px;">
                      <input type="text" style="text-align: right;" class="form-control" id="num-netamount" name="num-netamount" autocomplete="nope" value="0.00" required readonly="true">
                  </td>
                </tr>                

              </tbody>
             </table>
            </div>

          </div>  <!-- footer -->
       </div>     <!-- card -->
      </div>    

      <!-- PRODUCTS Table -->
      <div class="col-md-5" style="padding-left: 0px;margin-top: 5px;padding-right: 5px;">
        <div class="card h-100">
<!--           <div class="card-header header-elements-inline">
            <h5 class="card-title datatable-form-title">PRODUCT LISTING</h5> 
          </div> -->
            <table class="table table-bordered table-striped datatable-small-font profile-grid-header transactionProductsTable" style="font-size: 1.2em;">
              <thead>
                <tr>
                  <th>Item Description</th>
                  <th>Price</th>
                  <th>Prodid</th>  
                  <th>Ucost</th>
                  <th>Disprice</th>
                  <th>Minqty</th>
                  <th>Vatdesc</th>
                  <th>Barcode</th>
                  <th class="text-center" style="width:25px;">Act</th>
                </tr>
              </thead>
            </table>
        </div>   <!-- card -->
      </div>

      <div class="col-md-1" style="padding-left: 0px;margin-top: 5px;padding-right: 5px;">
        <div class="card h-100">
          <button type="button" class="btn btn-lg btn-outline bg-teal-400 text-teal-400 border-teal-400 border-2 btn-float" name="btn-new" id="btn-new" style="margin-bottom: 9px;font-size:1.5em;"><i class="icon-file-empty icon-2x"></i> <span>F3 - New</span></button>

          <button type="button" class="btn btn-lg btn-outline bg-teal-400 text-teal-400 border-teal-400 border-2 btn-float" name="btn-save" id="btn-save" style="margin-bottom: 9px;font-size:1.5em;" disabled><i class="icon-calculator2 icon-2x"></i> <span>F4 - Bill</span></button>

          <button type="button" class="btn btn-lg btn-outline bg-blue-400 text-blue-400 border-blue-400 border-2 btn-float" name="btn-override" id="btn-override" data-toggle="modal" data-target="#modal-override-order" style="margin-bottom: 9px;font-size:1.5em;" disabled><i class="icon-unlocked2 icon-2x"></i> <span>F8 - Pricing</span></button>

          <button type="button" class="btn btn-lg btn-outline bg-blue-400 text-blue-400 border-blue-400 border-2 btn-float" name="btn-reset" id="btn-reset" data-toggle="modal" data-target="#modal-reset-cashier" style="margin-bottom: 9px;font-size:1.5em;"><i class="icon-spinner9 icon-2x"></i> <span>F9 - Reset</span></button>

          <!-- <button type="button" class="btn btn-lg btn-outline bg-blue-400 text-blue-400 border-blue-400 border-2 btn-float" name="btn-quota" id="btn-quota" style="margin-bottom: 9px;font-size:1.5em;visibility: hidden;"><i class="icon-stackoverflow icon-2x"></i> <span>F9 - Quota</span></button>           -->
        </div>
      </div>

    </div>  <!-- row -->
  </form>
</div>

<!-- ============== Modal Form - OVERRIDE Order ============ -->
<div id="modal-override-order" class="modal allow-modal-drag" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;PRICE DISCOUNTING</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <div class="h-divider"  style="margin:0;padding:0;">
      </div>

      <div class="row" pb-0 style="margin:10px;margin-bottom: 0px;">
        <div class="col-sm-12 form-group">
          <label style="font-size: 1.2em;margin-top: 6px;">Enter Override Key</label>
          <input type="password" class="form-control border-teal border-1" id="tns-override" name="tns-override" value="">
        </div>
      </div>  

      <div class="btn-group btn-group-justified" style="margin:0;margin-left:21px;margin-right:21px;margin-bottom: 13px;">
        <div class="btn-group">
          <button type="button" class="btn btn-light btn-lg" id="btn-admin-direct-override" style="font-size: 1.3em;color:lightgreen;"><i class="icon-database-edit2 icon-2x"></i>&nbsp;&nbsp;Initiate Override Process</button>
        </div>
      </div> 

      <div class="h-divider"></div>

      <div class="row" pb-0 style="margin:10px;margin-bottom: 0px;">
        <div class="col-sm-12 form-group">
          <label style="font-size: 1.2em;margin-top: 6px;">State Your Reason</label>
          <textarea rows="3" cols="3" class="form-control border-teal border-1" id="tns-reason" name="tns-reason" value=""></textarea>
        </div>
      </div>        

      <div class="btn-group btn-group-justified" style="margin-left:21px;margin-right:21px;margin-bottom: 22px;margin-top: 0px;">
        <div class="btn-group">
          <button type="button" class="btn btn-light btn-lg" id="btn-override-request" style="font-size: 1.3em;color:orange;"><i class="icon-paperplane icon-2x"></i>&nbsp;&nbsp;Send Discount Request</button>
        </div>
      </div> 

    </div>
  </div>
</div>

<!-- ============== Modal Form - BILL Order ============ -->
<div id="modal-bill-order" class="modal allow-modal-drag" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;BILL OUT ORDER</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

      <div class="row" pb-0 style="margin:10px;margin-bottom: 0px;padding-bottom: 0;">
        <div class="col-sm-12 form-group">
          <label style="font-size: 2.3em;margin-top: 6px;color:lightgreen;">NET DUE AMOUNT</label>
          <input type="text" class="form-control border-teal border-1" id="sale-netamount" name="sale-netamount" value="" style="font-size: 3em;text-align:right;" readonly>
        </div>
      </div> 

      <div class="row" pb-0 style="margin:10px;margin-bottom: 0px;">
        <div class="col-sm-12 form-group">
          <label style="font-size: 2.3em;margin-top: 0;color:orange;">CASH TENDERED</label>
          <input type="text" class="form-control border-teal border-1 numeric" id="cash-tendered" name="cash-tendered" value="0.00" style="font-size: 3em;text-align:right;" readonly>
        </div>
      </div>

      <div class="row" pb-0 style="margin:10px;margin-bottom: 0px;">
        <div class="col-sm-12 form-group">
          <label style="font-size: 2.3em;margin-top: 0;color:#03bafc;">CHANGE AMOUNT</label>
          <input type="text" class="form-control border-teal border-1" id="change-amount" name="change-amount" value="0.00" style="font-size: 3em;text-align:right;" readonly>
        </div>
      </div>      

      <div class="h-divider"></div>

      <div class="btn-group btn-group-justified" style="margin-left:21px;margin-right:21px;margin-bottom: 22px;margin-top: 20px;">
        <div class="btn-group">
          <button type="button" class="btn btn-light btn-lg" id="btn-commit-sale" style="font-size: 1.7em;color:white;" disabled><i class="icon-database-edit2 icon-3x"></i>&nbsp;&nbsp;COMMIT TRANSACTION</button>
        </div>
      </div> 

    </div>
  </div>
</div>

<!-- ============== Modal Form - RESET Cashier ============ -->
<div id="modal-reset-cashier" class="modal allow-modal-drag" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name"><i class="icon-menu7 mr-2"></i> &nbsp;CASHIER RESET</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider"></div>
      <div class="row reset_content"></div> 

      <div class="row" pb-0 style="margin:10px;margin-bottom: 0px;">
        <div class="col-sm-12 form-group">
          <label style="font-size: 1.2em;margin-top: 6px;">Enter Override Key</label>
          <input type="password" class="form-control border-teal border-1" id="reset-override" name="reset-override" value="">
        </div>
      </div>  

      <div class="btn-group btn-group-justified" style="margin:0;margin-left:21px;margin-right:21px;margin-bottom: 13px;">
        <div class="btn-group">
          <button type="button" class="btn btn-light btn-lg" id="btn-admin-direct-reset" style="font-size: 1.3em;color:lightgreen;"><i class="icon-database-edit2 icon-2x"></i>&nbsp;&nbsp;Initiate Cashier Reset</button>
        </div>
      </div> 

    </div>
  </div>
</div>


<!-- <script src="views/js/cashier.js"></script> -->
<script src="views/js/cashier.js?v=<?php echo filemtime('views/js/cashier.js'); ?>"></script>


