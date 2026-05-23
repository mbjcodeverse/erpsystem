<div class="row align-items-center h-100" style="margin:0;margin-top: 13px;">
    <div class="col-md-10 mx-auto">
        <form class="product-form" id="product-form" method="POST" autocomplete="nope">
            <div class="card card-shadow-effect">
                <div class="card-header d-flex bg-transparent border-bottom card-header-effect">
                    <!-- Posted by -->
                    <input type="hidden" name="postedby" id="postedby" value="<?php echo $_SESSION["empid"];?>">
                    <h5 class="card-title flex-grow-1" style="color:lightblue;font-size: 2em;">PRODUCT MASTER LIST</h5> 
                    <div class="header-elements">
                        <div class="list-icons">
                            <!-- <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a> -->
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Category -->
                        <div class="col-sm-3 form-group">
                            <label for="sel-categorycode">Category</label>
                            <select data-placeholder="< Select Category >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-categorycode" name="sel-categorycode" required>
                                <option></option>
                                <?php
                                    $category = (new ControllerCategory)->ctrShowCategoryList();
                                    foreach ($category as $key => $value) {
                                        echo '<option value="'.$value["categorycode"].'">'.$value["catdescription"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <!-- Brand -->
                        <div class="col-sm-3 form-group">
                            <label for="sel-brandcode" id="lbl-sel-brandcode" style="color:aqua;">= &gt; Brand</label>
                            <select data-placeholder="< Select Brand >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-brandcode" name="sel-brandcode">
                                <option></option>
                                <?php
                                    $brands = (new ControllerBrand)->ctrShowBrandList();
                                    foreach ($brands as $key => $value) {
                                        echo '<option value="'.$value["brandcode"].'">'.$value["brandname"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <!-- Purchase Item -->
                        <div class="col-md-2 form-group">
                            <label class="d-block font-weight-semibold">Desc</label>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="chk-purchaseitem" name="chk-purchaseitem" value="1" checked>
                                <label class="custom-control-label" for="chk-purchaseitem">Purchase Item</label>
                            </div>
                        </div>                                                                   

                        <!-- Status -->
                        <div class="col-md-1 form-group">
                            <label class="d-block font-weight-semibold">Status</label>
                            <div class="custom-control custom-checkbox custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="chk-isactive" name="chk-isactive" value="1" checked>
                                <label class="custom-control-label" for="chk-isactive">Active</label>
                            </div>
                        </div>

                        <div class="col-sm-1 form-group"></div>            

                        <!-- Product ID -->
                        <div class="col-sm-2 form-group">
                            <label for="txt-prodid">Prod ID</label>
                            <input type="text" class="form-control profile-code" id="txt-prodid" name="txt-prodid" autocomplete="nope" required readonly="true">
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-3 form-group">
                            <label for="txt-barcode">Bar Code</label>
                            <input type="text" class="form-control" id="txt-barcode" name="txt-barcode" autocomplete="nope">
                        </div> 
                        
                        <div class="col-sm-4 form-group">
                            <label for="txt-pdesc">Product Name</label>
                            <input type="text" class="form-control" id="txt-pdesc" name="txt-pdesc" autocomplete="nope" required>
                        </div>

                        <div class="col-sm-2 form-group">
                            <label for="sel-sellunit">( Selling Unit )</label>
                            <select data-placeholder="< Select SKU >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-sellunit" name="sel-sellunit" required>
                                <option></option>
                                <?php
                                    $meas1 = (new ControllerMeasure)->ctrShowAllMeasure();
                                    foreach ($meas1 as $key => $value) {
                                        echo '<option value="'.$value["mdesc"].'">'.$value["mexpound"].'</option>';
                                    }
                                ?>
                            </select>  
                        </div> 

                        <div class="col-sm-1 form-group">
                            <label for="num-specs">Specs</label>
                            <input type="text" class="form-control" id="num-specs" name="num-specs" autocomplete="nope">
                        </div>

                        <div class="col-sm-2 form-group">
                            <label for="sel-measure">Measure</label>
                            <select data-placeholder="< Select SKU >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-measure" name="sel-measure">
                                <option></option>
                                <?php
                                    $meas1 = (new ControllerMeasure)->ctrShowAllMeasure();
                                    foreach ($meas1 as $key => $value) {
                                        echo '<option value="'.$value["mdesc"].'">'.$value["mexpound"].'</option>';
                                    }
                                ?>
                            </select>  
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-sm-2 form-group">
                            <label for="num-uprice" id="lbl-num-uprice" style="color:aqua;">= &gt; Unit Price</label>
                            <input type="text" class="form-control border-teal border-1 numeric" id="num-uprice" name="num-uprice" value="0.00" style="font-size: 1em;text-align:right;" readonly required>
                        </div>

                        <div class="col-sm-2 form-group">
                            <label for="num-profit">Profit</label>
                            <input type="text" class="form-control" id="num-profit" name="num-profit" value="0.00" autocomplete="nope" disabled>
                        </div>

                        <div class="col-sm-2 form-group">
                            <label for="num-ucost" id="lbl-num-ucost" style="color:aqua;">= &gt; Unit Cost</label>
                            <input type="text" class="form-control border-teal border-1 numeric" id="num-ucost" name="num-ucost" value="0.00" style="font-size: 1em;text-align:right;" readonly>
                        </div>

                        <div class="col-sm-2 form-group">
                            <label for="txt-abbr">Abbreviation</label>
                            <input type="text" class="form-control" id="txt-abbr" name="txt-abbr" autocomplete="nope">
                        </div>

                        <div class="col-sm-2 form-group">
                            <label for="sel-vatdesc">VAT Desc</label>
                            <select data-placeholder="< Select Desc >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="sel-vatdesc" name="sel-vatdesc" required>
                                <option></option>
                                <option value="Vatable" selected>Vatable</option>
                                <option value="VAT-Exempt">VAT-Exempt</option>
                                <option value="Zero-Rated">Zero-Rated</option>
                            </select>
                        </div>

                        <div class="col-sm-2 form-group">
                            <label for="num-reorder" id="lbl-num-reorder" style="color:#b9fcb1;">= &gt; Re-order</label>
                            <input type="text" class="form-control border-teal border-1 numeric" id="num-reorder" name="num-reorder" value="0.00" style="font-size: 1em;text-align:right;" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2 form-group">
                            <label for="num-disprice" id="lbl-num-disprice" style="color:#b9fcb1;">= &gt; Disc Price</label>
                            <input type="text" class="form-control border-teal border-1 numeric" id="num-disprice" name="num-disprice" value="0.00" style="font-size: 1em;text-align:right;" readonly>
                        </div>

                        <div class="col-sm-2 form-group">
                            <label for="num-minqty" id="lbl-num-minqty" style="color:#b9fcb1;">= &gt; Min Qty</label>
                            <input type="text" class="form-control border-teal border-1 numeric" id="num-minqty" name="num-minqty" value="0.00" style="font-size: 1em;text-align:right;" readonly>
                        </div>

                        <div class="col-sm-8 form-group">
                            <label for="txt-remarks">Remarks</label>
                            <input type="text" class="form-control" id="txt-remarks" name="txt-remarks" autocomplete="nope">
                        </div>
                    </div>

                    <div class="clearfix">
                        <span class="float-left">
                        </span>

                        <input type="text" name="trans_type" id="trans_type" value="New" style="visibility:hidden;" required>
                        <input type="hidden" id="num-id" name="num-id">

                        <span class="float-right">
                            <button type="button" class="btn btn-light btn-lg" id="btn-new" onClick="location.href='products'"><i class="icon-file-text mr-2"></i> New</button>

                            <button type="button" class="btn btn-light btn-lg" id="btn-search" data-toggle="modal" data-target="#modal-search-product"><i class="icon-search4 mr-2"></i> Search</button>
                        
                            <button type="button" class="btn btn-light btn-lg" id="btn-save"><i class="icon-floppy-disk mr-2"></i> Save</button>
                        </span>
                    </div>     
                </div>

            </div>
        </form>
    </div>
</div>

<!-- ============== Product List ============ -->
<div id="modal-search-product" class="modal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content" style="background-color: #343f53;">
      <div class="modal-header">
        <h5 class="modal-title profile-name" style="margin-top:-3px;"><i class="icon-menu7 mr-2"></i> &nbsp; PRODUCT MASTER LIST&nbsp;&nbsp;&nbsp;&nbsp;</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="h-divider">
      </div>

        <div class="row" pb-0 style="margin:10px;margin-bottom: -10px;">
          <!-- <div class="row" pb-0 style="margin:10px;margin-bottom: -25px;">   -->
            <div class="col-sm-4 form-group">
                <label for="lst-categorycode" id="lbl-lst-categorycode" style="color:aqua;">= &gt; Category</label>
                <select data-placeholder="< Select Category >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-categorycode" name="lst-categorycode" required>
                    <option></option>
                    <?php
                        $category = (new ControllerCategory)->ctrShowCategoryList();
                        foreach ($category as $key => $value) {
                            echo '<option value="'.$value["categorycode"].'">'.$value["catdescription"].'</option>';
                        }
                    ?>
                </select>
            </div>        

            <div class="col-sm-2 form-group">
                <label for="lst-brandcode" id="lbl-lst-brandcode" style="color:aqua;">= &gt; Brand</label>
                <select data-placeholder="< Select Brand >" class="form-control select-search" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-brandcode" name="lst-brandcode">
                    <option></option>
                    <?php
                        $brands = (new ControllerBrand)->ctrShowBrandList();
                        foreach ($brands as $key => $value) {
                            echo '<option value="'.$value["brandcode"].'">'.$value["brandname"].'</option>';
                        }
                    ?>
                </select>
            </div>          

            <div class="col-sm-2 form-group">
            </div>

            <div class="col-sm-2 form-group">
                <label for="lst-vatdesc" id="lbl-lst-vatdesc" style="color:aqua;">= &gt; VAT Desc</label>
                <select data-placeholder="< Select Phase >" class="form-control select" data-container-css-class="border-secondary" data-dropdown-css-class="border-secondary" data-fouc id="lst-vatdesc" name="lst-vatdesc" required>
                    <option></option>
                    <option value="Vatable">Vatable</option>
                    <option value="VAT-Exempt">VAT-Exempt</option>
                    <option value="Zero-Rated">Zero-Rated</option>
                </select>
            </div>

            <div class="col-sm-2 form-group" style="padding: 0px;padding-top:28px;padding-left:7px;margin:0;">
                <button type="button" class="btn btn-warning" name="btn-export" id="btn-export" style="float:right; margin-right:3px; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
                    <i class="icon-file-spreadsheet"></i>
                </button>
                <button type="button" class="btn btn-primary" name="btn-print-products" id="btn-print-products" style="float:right; margin-right:6px; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
                    <i class="icon-printer"></i>
                </button>
                <!-- <button type="button" class="btn btn-success" disabled name="btn-generate" id="btn-generate" style="float:left; text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);">
                    <i class="icon-pencil3"></i>
                    Gen
                </button>                 -->
            </div>    

            <div class="col-12" style="margin:10px;margin-top:-25px;">
            <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header productListTable" width="100%">
            <!-- <div class="row" pb-0 style="margin:10px;margin-top:-25px;">
            <table class="table table-hover table-bordered table-striped datatable-small-font profile-grid-header productListTable" width="100%"> -->
                <thead>
                    <tr>
                    <th style="min-width: 130px;">Prod ID</th>
                    <th style="min-width: 160px;">Category</th>
                    <th style="min-width: 300px;">Product Name</th>
                    <th style="min-width: 60px;">Unit Price</th>
                    <th style="min-width: 60px;">Unit Cost</th>
                    <th style="min-width: 60px;">Profit</th>
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


<script src="views/js/products.js?v=<?php echo filemtime('views/js/products.js'); ?>"></script>