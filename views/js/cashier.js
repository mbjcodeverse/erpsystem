if (!$.fn.DataTable.isDataTable('.transactionProductsTable')) { 
  var pl = $('.transactionProductsTable').DataTable({
      processing: true,
      autoWidth: true,
      scrollY: '70vh',
      // scrollY: '64vh',
      keys: true,
      paging: false,
      dom: '<"datatable-header"f><"datatable-scroll"t><"datatable-footer"i>',
              language: {
                  loadingRecords: 'Loading products...',
                  processing: 'Loading products...',
                  emptyTable: 'Loading products...', // replaces "No data available in table"
                  search: '<span style="font-size:16px;font-weight:bold;color:#86f7a4;">[ F2 ] SEARCH:</span> _INPUT_',
                  searchPlaceholder: 'Type to filter...',
                  lengthMenu: '<span>Show:</span> _MENU_',
                  paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
              },

              createdRow: function(row, data, dataIndex) {
                  $('td', row).attr('style', 'font-size:18px;padding:4px 15px;');
              }
  }); 
}

$(function() {
    // Move around ordered items table using arrow keys
    navigateOrderGrid();
    getBranchPrefixCode();
    displayCurrentDate();
    loadBranchProducts();

    reset_search();

    // Hide Product List columns
    pl.column(2).visible(false);	// ProdID
    pl.column(3).visible(false);	// Ucost
    pl.column(4).visible(false);	// Disprice
    pl.column(5).visible(false);    // Minqty
    pl.column(6).visible(false);    // Vatdesc
    pl.column(7).visible(false);    // Barcode

    // Function Keys ------------------------------------------------------------
    shortcut.add("F2",function() {
        reset_search();
    });

    shortcut.add("F3",function() {
        new_order();
    });

    $("#btn-new").click(function(){
        new_order();
    });
   
    // ---------------- BILL Order -----------------------------------------------------
    shortcut.add("F4",function() {
        if($('#btn-bill').prop('disabled')){ 
        }else{
            $('#modal-bill-order').modal('show');
            $("#sale-netamount").val($("#num-netamount").val());
            $("#cash-tendered").val('0.00');
            $('#change-amount').val('0.00');
            $("#cash-tendered").focus(); 
        }
    }); 
    
    $("#btn-save").click(function(){
        $('#modal-bill-order').modal({backdrop: 'static', keyboard: false},'show');
        $("#sale-netamount").val($("#num-netamount").val());
        $("#cash-tendered").val('0.00');
        $('#change-amount').val('0.00');
        $("#cash-tendered").focus();
    });

    $('#cash-tendered').on("change keyup", function(){
       let num_amount = $('#sale-netamount').val();
       let num_tendered = $('#cash-tendered').val();
       let sale_netamount = parseFloat(num_amount.replaceAll(",",""));
       let cash_tendered = parseFloat(num_tendered.replaceAll(",",""));
       let result = cash_tendered - sale_netamount;

       if (cash_tendered < sale_netamount){
          $("#btn-commit-sale").prop('disabled', true);
       }else{
          $("#btn-commit-sale").prop('disabled', false);
       }

       if (result < 0){
          $('#change-amount').val('0.00');
       }else{
          $('#change-amount').val(numberWithCommas(result.toFixed(2)));
       }
    }); 
    
    $("#cash-tendered").keyup(function(event) {
        if (event.keyCode === 13) {
            if ($('#btn-commit-sale').prop('disabled')) {
                swal.fire({
                    title: 'Cannot bill out, insufficient cash!',
                    type: 'warning',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1500
                });
                $("#cash-tendered").val('0.00');
                $('#change-amount').val('0.00');
            }else{
                $("#btn-commit-sale").click();
            }
        }
    });

    $("#btn-commit-sale").click(function(){
        bill_order();
    });

    // ---------------- Override Sales --------------------------------------------------
    shortcut.add("F8",function() {
        if($('#btn-override').prop('disabled')){ 
        }else{
            $('#modal-override-order').modal('show');
        }
    });

    // Increase width of datatable filtered box
    $('.dataTables_filter input[type="search"]').css({'width':'350px','display':'inline-block'});

    var first_idx = 0;
    $('div.dataTables_filter input').on('keyup keypress',function (event) {
        // When typing on filter box - get index of first row of filtered datatable
        first_idx = pl.row({search:'applied'}).index();
        if (event.keyCode === 40) {                         // Down arrow key
            // alert(first_idx);
            $(this).blur();					                // leave focus on filter control
            pl.cell(':eq('+first_idx+')', 0).focus();	    // focus on first cell, 1 - 1st column
                                                            // focus not all filtered table                                                                            
        }
    });

    // Enlisting Products ---------------------------------------------------------------
    // Scan barcode in filter box...
    $('div.dataTables_filter input').on('keydown', function(e) {
        if (e.keyCode === 13) {             // Enter from barcode scanner
            let value = $(this).val().trim();
            let foundRow = null;
            pl.rows({ search: 'applied' }).every(function() {
                let data = this.data();
                let barcode = data[7];      // column 7 = barcode (you set this)
                if (barcode == value) {
                    foundRow = this;
                }
            });

            if (foundRow) {
                let data = foundRow.data();
                let prodname = data[0];
                let uprice   = data[1];
                let prodid   = data[2];
                let ucost    = data[3];
                let disprice = data[4];
                let minqty   = data[5];
                let vatdesc  = data[6];
                let barcode  = data[7];

                let $existing = $(".enlisted_products input.barcode[value='"+barcode+"']");

                if ($existing.length > 0) {
                    // increase qty
                    let $qty = $existing.closest("tr").find(".qty");
                    let qty = parseFloat($qty.val().replace(/,/g, "")) || 0;
                    qty += 1;

                    $qty.val(qty.toFixed(2));

                    $qty.trigger("blur");
                    $qty.trigger("keyup");

                    addingTotalPrices();
                    listProducts();
                } else {
                    appendProduct(prodid, prodname, uprice, ucost, disprice, minqty, vatdesc, barcode);
                    $('.qty').focus();
                }

                reset_search();
                $(this).val('');
            }
        }
    });

    // Button click..
    $(".transactionProductsTable tbody").on("click", "button.addProduct", function(){
        let prodid = $(this).attr("prodid");
        let prodname = $(this).attr("prodname");
        let uprice = $(this).attr("uprice");
        let ucost = $(this).attr("ucost");
        let disprice = $(this).attr("disprice");
        let minqty = $(this).attr("minqty");
        let vatdesc = $(this).attr("vatdesc");
        let barcode = $(this).attr("barcode");

        appendProduct(prodid, prodname, uprice, ucost, disprice, minqty, vatdesc, barcode);
        $('.qty').focus();
    });

    // Pressing ENTER on highlighted product...
    pl.on('key', function (e, datatable, key, cell, originalEvent) {
        if (key == 13){        // Using 13 for ENTER key, makes sweet alert dissapear instantly (conflict with key event)
            let idx = pl.row(cell.index().row).index();
            let prodname = pl.cell(idx, 0).data();
            let uprice = pl.cell(idx, 1).data();
            let prodid = pl.cell(idx, 2).data();			
            let ucost = pl.cell(idx, 3).data();			  
            let disprice = pl.cell(idx, 4).data();			
            let minqty = pl.cell(idx, 5).data();	
            let vatdesc = pl.cell(idx, 6).data();
            let barcode = pl.cell(idx, 7).data();

            // Check if product already enlisted
            if ($("button.recoverButton[prodid='"+prodid+"']").hasClass('enlisted')) {	
                swal.fire({
                    title: 'Product has already been enlisted!',
                    type: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1500
                })
                reset_search();
            }else{
                appendProduct(prodid, prodname, uprice, ucost, disprice, minqty, vatdesc, barcode);
                $('.qty').focus();  
            }    
        }
    }); 
    
    // Highlighting product - Arrow keys...
    pl.on('key-focus', function (e, datatable, cell) {
        $(pl.row(cell.index().row).node()).addClass('row_selected');		// see mycss.css
    });

    pl.on('key-blur', function (e, datatable, cell) {
        $(pl.row(cell.index().row).node()).removeClass('row_selected');	    // see mycss.css
    });

    // -------------------------------------------------------------------------------------
    
    // Total Amount = Qty * Price
    $(".cashier-form").on("keydown keypress blur focus", "input.qty,input.price", function(){
        let prodid = $(this).parent().parent().children(".qtyEntry").children().attr("prodid");

        let q = $(this).parent().parent().children(".qtyEntry").children().val();
        let quantity = q.replaceAll(",","");

        let p = $(this).parent().parent().children(".priceEntry").children().val();
        let price = p.replaceAll(",","");   

        // CHECK IF QUANTITY EXCEEDS 9999
        if (quantity > 9999.00) {
            swal.fire({
                title: 'Quantity exceeded, reverted input to zero!',
                type: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 3000
            });
            quantity = 0.00;

            // SET INPUT VALUE TO 0.00
            $(this).parent().parent()
                .children(".qtyEntry")
                .children()
                .val("0.00");
        }

        let totalAmount = quantity * price;
        
        let productAmount = $(this).parent().parent().children(".totalAmount").children(".tamount");
        productAmount.val(numberWithCommas(totalAmount.toFixed(2)));

        _gblBindNumericClasses('numeric'); 

        addingTotalPrices();
        listProducts(); 
    }); 

    // Whole sale...
    $(".cashier-form").on("blur", "input.price", function(){
        // Quantity
        let q = $(this).parent().parent().children(".qtyEntry").children().val();
        let quantity = q.replaceAll(",","");

        // Price
        let p = $(this).parent().parent().children(".priceEntry").children().val();
        let uprice = p.replaceAll(",","");   

        // Discount
        let d = $(this).closest("tr").find("input.disprice").val();
        let discount = d.replaceAll(",",""); 

        // Original Price
        let o = $(this).closest("tr").find("input.oprice").val();
        let oprice = o.replaceAll(",",""); 

        if ((oprice - price )> discount){
            $(this).parent().parent().children(".priceEntry").children().val(oprice);
            var totalAmount = quantity * oprice;
        
            var productAmount = $(this).parent().parent().children(".totalAmount").children(".tamount");
            productAmount.val(numberWithCommas(totalAmount.toFixed(2)));
            swal.fire({
                title: `Original price has been restored to Php ${oprice}<br>Allowable discount amount is Php ${discount} only.`,
                type: 'warning',
                confirmButtonText: 'Got it!',
                confirmButtonClass: 'btn btn-outline-danger',
                allowOutsideClick: false,
                buttonsStyling: false
            });
        }

        _gblBindNumericClasses('numeric'); 
        addingTotalPrices();
        listProducts(); 
    }); 

    // Removal of selected item ----------------------------------------------------------------
    var idRemoveProduct = [];
    localStorage.removeItem("removeProduct");
    $(".cashier-form").on("click", "button.removeProduct", function(){
        $(this).parent().parent().parent().parent().remove();

        var prodid = $(this).attr("prodid");

        if(localStorage.getItem("removeProduct") == null){
            idRemoveProduct = [];
        }else{
            idRemoveProduct.concat(localStorage.getItem("removeProduct"))
        }

        idRemoveProduct.push({"prodid":prodid});
        localStorage.setItem("removeProduct", JSON.stringify(idRemoveProduct));

        $("button.recoverButton[prodid='"+prodid+"']").removeClass('btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2 enlisted');
        $("button.recoverButton[prodid='"+prodid+"']").addClass('btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct');

        addingTotalPrices();
        listProducts();
        
        let a = document.getElementById("product_list");
        let rows = a.rows.length;
    });  

    // ------------------ Cashier Reset --------------------------------------------------------

    $("#btn-admin-direct-reset").click(function(){
       reset_cashier();
    });

    function reset_cashier(){
        $('#reset-override').focus();
        if ($("#reset-override").val() == ''){  
            swal.fire({
                title: 'Cannot reset, override key must be entered!',
                type: 'error',
                confirmButtonText: 'Got it',
                confirmButtonClass: 'btn btn-outline-warning',
                allowOutsideClick: false,
                buttonsStyling: false
            }).then(function(result){
                if(result.value) {              
                    $('#reset-override').focus();
                }
            });
        }else{
            let override_key = $("#reset-override").val();
            var reset_sale = new FormData();
            reset_sale.append("override_key", override_key);
            $.ajax({
                url:"ajax/get_override_key.ajax.php",
                method: "POST",
                data: reset_sale,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(answer){
                    if(answer["override"] === undefined){
                        swal.fire({
                            title: 'Cannot Reset, unidentified authorization code!',
                            type: 'error',
                            confirmButtonText: 'Got it',
                            confirmButtonClass: 'btn btn-outline-warning',
                            allowOutsideClick: false,
                            buttonsStyling: false,
                        }).then(function(result){
                            if(result.value) { 
                                $('#reset-override').val('');
                            }
                        });
                    }else{
                        swal.fire({
                            title: 'Do you want to RESET sales transaction?',
                            text: 'You will not be able to revert this process.',
                            type: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, Reset it!',
                            cancelButtonText: 'Cancel!',
                            confirmButtonClass: 'btn btn-outline-success',
                            cancelButtonClass: 'btn btn-outline-danger',
                            allowOutsideClick: false,
                            buttonsStyling: false
                        }).then(function(result) {
                            if(result.value) {
                                let branchcode = $("#branch_code").val();
                                let prefix = $("#prefix").val();
                                let branch_name = $("#branch_name").val();
                                let postedby = $("#tns-postedby").val();
                                let userid = $("#userid").val();
                                let user_type = $("#user_type").val();

                                let digityear = twodigityear();

                                let reset_prefix = "SR" + prefix + userid.substring(1, 4) + digityear;
                                let reset_count = new FormData();
                                reset_count.append("reset_prefix", reset_prefix);

                                $.ajax({
                                    url:"ajax/reset_check_count.ajax.php",
                                    method: "POST",
                                    data: reset_count,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    dataType:"json",
                                    success:function(answer){
                                        let reset_count = answer.length + 1;
                                        let reset_count_string = String(reset_count);
                                        let reset_count_length = reset_count_string.length;

                                        let prefix_num = '0';
                                        let reset_sequence = prefix_num.repeat(4 - reset_count_length) + reset_count;
                                        let resetcode = reset_prefix + reset_sequence;

                                        let resetby = $("#tns-postedby").val();
                                        let resetype = 'Counter';

                                        let reset_data = new FormData();
                                        reset_data.append("branchcode", branchcode);
                                        reset_data.append("resetcode", resetcode);
                                        reset_data.append("resetby", resetby);
                                        reset_data.append("resetype", resetype);
                                        $.ajax({
                                            url:"ajax/sale_reset.ajax.php",
                                            method: "POST",
                                            data: reset_data,
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            dataType:"text",
                                            success:function(answer){
                                                swal.fire({
                                                    title: 'Cashier reset successfully posted!',
                                                    type: 'success',
                                                    confirmButtonText: 'Got it',
                                                    confirmButtonClass: 'btn btn-outline-success',
                                                    allowOutsideClick: false,
                                                    buttonsStyling: false
                                                });

                                                let branchcode = $("#branch_code").val();
                                                let branch_name = $("#branch_name").val();
                                                let cashierid = $("#tns-postedby").val();
                                                let resetdetail = $("#reset_detail").val();

                                                if (resetdetail == 'By Product Category'){
                                                    window.open("reports/resetprint.php?branchcode="+branchcode+"&cashierid="+cashierid+"&branch_name="+branch_name+"&resetcode="+resetcode+"&resetdetail="+resetdetail+"&resetype="+resetype, "_blank"); 
                                                }else{
                                                    window.open("reports/resetprintbyproduct.php?branchcode="+branchcode+"&cashierid="+cashierid+"&branch_name="+branch_name+"&resetcode="+resetcode+"&resetdetail="+resetdetail+"&resetype="+resetype, "_blank");
                                                }

                                                $('#modal-reset-cashier').modal('hide');
                                                initialize();
                                            }
                                        });
                                    }
                                });     
                            }
                        });    
                    }
                }
            });
        }         
    } 

    // -----------------------------------------------------------------------------------------

    function displayCurrentDate(){
        let today = new Date();
        let mm = String(today.getMonth() + 1).padStart(2, '0');
        let dd = String(today.getDate()).padStart(2, '0');
        let yyyy = today.getFullYear();
        today = mm + '/' + dd + '/' + yyyy;
        $("#date-sdate").val(today);
    }

    function navigateOrderGrid(){
        $('#trans-table').keydown(function(e){
        var $table = $(this);
        var $active = $('input:focus,select:focus',$table);
        var $next = null;
        var focusableQuery = 'input:visible,select:visible,textarea:visible';
        var position = parseInt( $active.closest('td').index()) + 1;
        console.log('position :',position);
        switch(e.keyCode){
            case 37: // <Left>
                $next = $active.parent('td').prev().find(focusableQuery);   
                break;
            case 38: // <Up>                    
                $next = $active
                    .closest('tr')
                    .prev()                
                    .find('td:nth-child(' + position + ')')
                    .find(focusableQuery)
                ;
                
                break;
            case 39: // <Right>
                $next = $active.closest('td').next().find(focusableQuery);            
                break;
            case 40: // <Down>
                $next = $active
                    .closest('tr')
                    .next()                
                    .find('td:nth-child(' + position + ')')
                    .find(focusableQuery)
                ;
                break;
        }       
        if($next && $next.length)
        {        
            $next.focus();
        }
    });
    }

    function getBranchPrefixCode(){
        let branchcode =  $("#branch_code").val();
        let branch_code = new FormData();
        branch_code.append("branchcode", branchcode);
        $.ajax({
            url:"ajax/branch_get_prefix.ajax.php",
            method: "POST",
            data: branch_code,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $('#prefix').val(answer["prefix"]);
            }
        });
    }

    function loadBranchProducts(){
        let branchcode = $("#branch_code").val();
        let product_list = new FormData();
        product_list.append("branchcode", branchcode);
        $.ajax({
            url:"ajax/branch_product_list.ajax.php",
            method: "POST",
            data: product_list,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                for(var i = 0; i < answer.length; i++) {
                    let prod = answer[i];
                    let prodid = prod.prodid;
                    let prodname = prod.prodname;
                    let barcode = prod.barcode;

                    let price_amount = prod.uprice;
                    let uprice = numberWithCommas(price_amount);

                    let ucost_amount = prod.ucost;
                    let ucost = numberWithCommas(ucost_amount);

                    let disprice = prod.disprice;
                    let minqty = prod.minqty;

                    let vatdesc = prod.vatdesc;

                    var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct recoverButton' prodid='"+prodid+"' prodname='"+prodname+"' uprice='"+uprice+"' ucost='"+ucost+"' disprice='"+disprice+"' minqty='"+minqty+"' vatdesc='"+vatdesc+"' barcode='"+barcode+"'><i class='icon-check'></i></button></td>";  
                    pl.row.add([prodname, uprice, prodid, ucost, disprice, minqty, vatdesc, barcode, button]); 
                }
                pl.draw();
            }
        });  	
    }

    function new_order(){
        $('div.dataTables_filter input').focus();
        swal.fire({
            title: 'Do you want to create new order transaction?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Create!',
            cancelButtonText: 'No',
            confirmButtonClass: 'btn btn-outline-success',
            cancelButtonClass: 'btn btn-outline-danger',
            allowOutsideClick: false,
            buttonsStyling: false
        }).then(function(result) {
            if(result.value) {
                initialize();
            }
        });
    }
    
    function initialize(){
        $("#tns-soldto").val('');

        $(".enlisted_products").empty();
        $("#productList").val('');

        $("#num-amount").val('0.00');
        $("#num-discount").val('0.00');
        $("#num-netamount").val('0.00');

        $("#num-vatable").val('0.00');
        $("#num-excempt").val('0.00');
        $("#num-vatamnt").val('0.00'); 
        $("#num-zerorated").val('0.00');  
    
        $(".transactionProductsTable *").removeAttr('disabled');
        
        $("#btn-save").prop('disabled', true);
        $("#btn-override").prop('disabled', true);

        reset_search();

        $(".transactionProductsTable").DataTable().clear();
        pl.draw();
        loadBranchProducts();
    }    

    function addingTotalPrices(){
	    var priceItem = $(".tamount");
	    if (priceItem.length > 0){
	      var arrayAdditionPrice = [];  
	      for(var i = 0; i < priceItem.length; i++){
	         var num = $(priceItem[i]).val();
	         var total_amount = parseFloat(num.replaceAll(",",""));
	         arrayAdditionPrice.push(total_amount);
	      }

	      function additionArrayPrices(total, numberArray){
	        return total + numberArray;
	      }
	      var addingTotalPrice = arrayAdditionPrice.reduce(additionArrayPrices);

	      $("#num-amount").val(numberWithCommas(addingTotalPrice.toFixed(2)));
	      var netamount = addingTotalPrice.toFixed(2) - $('#num-discount').val();
	      $("#num-netamount").val(numberWithCommas(netamount.toFixed(2)));
	    }else{
	      $("#num-amount,#num-discount,#num-netamount").val('0.00');
	    }   
    }  

    function listProducts(){
        var productList = [];
        var description = $(".prodname");
        var quantity = $(".qty");
        var unitcost = $(".ucost");
        var priceamount = $(".uprice");
        var totalamount = $(".tamount");
        var vatdescription = $(".vatdesc");
        var orig_price = $(".uprice");

        var vat_excempt = 0.00;
        var hasZeroQty = false;
        var num_entries = description.length; 

        if (num_entries > 0){
            for(var i = 0; i < num_entries; i++){
                var txt_qty = $(quantity[i]).val();
                var txt_ucost = $(unitcost[i]).val();
                var txt_uprice = $(priceamount[i]).val();
                var txt_tamount = $(totalamount[i]).val();
                var vat_desc = $(vatdescription[i]).val();
                var txt_origprice = $(orig_price[i]).val();

                // Check if Qty or Price = 0.00
                if ((txt_qty == "0.00")||!(txt_qty)||(txt_uprice == "0.00")){  
                    var hasZeroQty = true;
                }

                //Remove commas on values
                var qty = parseFloat(txt_qty.replaceAll(",",""));
                var ucost = parseFloat(txt_ucost.replaceAll(",",""));
                var uprice = parseFloat(txt_uprice.replaceAll(",",""));
                var tamount = parseFloat(txt_tamount.replaceAll(",",""));
                var origprice = parseFloat(txt_origprice.replaceAll(",",""));

                if (vat_desc == 'VAT-Exempt'){
                    vat_excempt = vat_excempt + tamount;
                }

                productList.push({"qty" : qty.toFixed(2),
                                  "ucost" : ucost.toFixed(2),
                                  "uprice" : uprice.toFixed(2),
                                  "origprice" : origprice.toFixed(2),
                                  "tamount" : tamount.toFixed(2),
                                  "prodid" : $(description[i]).attr("prodid")})      
            }

            $("#productList").val(JSON.stringify(productList));
            $("#num-excempt").val(numberWithCommas(vat_excempt.toFixed(2)));

            // VATABLE
            var txt_amount = $("#num-amount").val();
            var amount = parseFloat(txt_amount.replaceAll(",",""));
            var vatable = (amount - vat_excempt) / 1.12; 
            $("#num-vatable").val(numberWithCommas(vatable.toFixed(2)));

            // VAT 12%
            var vat_amnt = (amount - vat_excempt) - vatable;
            $("#num-vatamnt").val(numberWithCommas(vat_amnt.toFixed(2))); 

            if (hasZeroQty){
                $("#btn-save").prop('disabled', true);
            }else{
                $("#btn-save").prop('disabled', false);
            }
            $("#btn-override").prop('disabled', false);
        }else{ 	
            $("#num-excempt").val('0.00');	
            $("#num-vatable").val('0.00');	
            $("#num-vatamnt").val('0.00'); 	
            $("#btn-save").prop('disabled', true);
            $("#btn-override").prop('disabled', true);
        }
    }      

    function reset_search(){
        $('div.dataTables_filter input').focus();
        $('div.dataTables_filter input').val('');
        $('.transactionProductsTable').DataTable().search("").draw();
        // Highlighted row dissapear in the table body after F2 Search is pressed
        // This is because of clicking the table row manually which result to Highlight
        pl.cell.blur();
    }

    function appendProduct(prodid, prodname, uprice, ucost, disprice, minqty, vatdesc, barcode) {
        $(".priceEntry *").prop('disabled', true);

        $("button.recoverButton[prodid='"+prodid+"']").removeClass("btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct");
        $("button.recoverButton[prodid='"+prodid+"']").addClass("btn btn-outline btn-sm bg-pink-400 border-pink-400 text-pink-400 btn-icon rounded-round border-2 ml-2 enlisted");
    
        $(".enlisted_products").append(
            '<tr>'+   
                '<td width="50%" style="padding:2px;">'+   
                    '<div class="input-group">'+
                        '<span style="padding:2px;" class="input-group-prepend"><button type="button" style="color:coral;" class="btn btn-sm btn-light removeProduct" prodid="'+prodid+'"><i class="icon-undo"></i></button></span>'+         
                        '<input type="text" style="padding:2px;" class="form-control prodname" prodid="'+prodid+'" name="addProduct" value="'+prodname+'" readonly required>'+
                    '</div>'+
                '</td>'+   

                '<td class="qtyEntry" width="15%" style="padding:2px;">'+
                    '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric" prodid="'+prodid+'" name="qty" value="1.00" required>'+
                '</td>' +

                '<td class="priceEntry" width="15%" style="padding:2px;">'+
                    '<input type="text" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control uprice numeric" prodid="'+prodid+'" name="uprice" value="'+uprice+'" disabled required>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control ucost" prodid="'+prodid+'" name="ucost" value="'+ucost+'" required disabled>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control disprice" prodid="'+prodid+'" name="disprice" value="'+disprice+'" required disabled>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control minqty" prodid="'+prodid+'" name="minqty" value="'+minqty+'" required disabled>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control vatdesc" prodid="'+prodid+'" name="vatdesc" value="'+vatdesc+'" required disabled>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control barcode" prodid="'+prodid+'" name="barcode" value="'+barcode+'" required disabled>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control origprice" prodid="'+prodid+'" name="origprice" value="'+uprice+'" required disabled>'+
                '</td>' +   

                '<td class="totalAmount" width="15%" style="padding:2px;">'+
                    '<input type="text" style="padding:2px;padding-right:17px;text-align:right;" class="form-control tamount" productPrice="'+uprice+'" name="tamount" value="0.00" readonly required>'+
                '</td>' +                                
        '</tr>');
    
        addingTotalPrices();
        listProducts();
        reset_search();
    }

    function bill_order(){
        let prefix = $("#prefix").val();    //
        let userid = $("#userid").val();    //

        let format_sdate = $("#date-sdate").val().split("/");
        format_sdate = format_sdate[2] + "-" + format_sdate[0] + "-" + format_sdate[1];

        let branchcode = $("#branch_code").val(); //
        let sdate = format_sdate; //
        let stime = getCurrentTime();  // helper.js
        let salemode = 'Counter'; //
        let customercode = ''; //
        let soldto = $("#tns-soldto").val(); //
        let status = 'Sold'; //

        // alert(prefix + ' ' + userid + ' ' + branchcode + ' ' + sdate + ' ' + stime + ' ' + salemode + ' ' + sellerid);

        var txt_amount = $("#num-amount").val(); //
        var txt_discount = $("#num-discount").val(); //
        var txt_netamount = $("#num-netamount").val(); //

        var txt_vatable = $("#num-vatable").val(); //
        var txt_excempt = $("#num-excempt").val(); //
        var txt_vatamnt = $("#num-vatamnt").val(); //

        var postedby = $("#tns-postedby").val();
        var productlist = $("#productList").val();

        // Remove comma on values
        var vatable = parseFloat(txt_vatable.replaceAll(",",""));
        var excempt= parseFloat(txt_excempt.replaceAll(",",""));
        var vatamnt= parseFloat(txt_vatamnt.replaceAll(",",""));

        var amount = parseFloat(txt_amount.replaceAll(",",""));
        var discount= parseFloat(txt_discount.replaceAll(",",""));
        var netamount= parseFloat(txt_netamount.replaceAll(",","")); 

        // alert(vatable + ' ' + excempt + ' ' + vatamnt + ' ' + amount + ' ' + discount + ' ' + netamount);           
        var sales = new FormData();
        sales.append("prefix", prefix);
        sales.append("userid", userid);

        sales.append("branchcode", branchcode);
        sales.append("sdate", sdate);
        sales.append("stime", stime);
        sales.append("salemode", salemode);
        sales.append("customercode", customercode);
        sales.append("soldto", soldto);
        sales.append("status", status);
        sales.append("vatable", vatable);
        sales.append("excempt", excempt);
        sales.append("vatamnt", vatamnt);            
        sales.append("amount", amount);
        sales.append("discount", discount);
        sales.append("netamount", netamount);
        sales.append("postedby", postedby);
        sales.append("productlist", productlist);            
        $.ajax({
            url:"ajax/sale_save_record.ajax.php",
            method: "POST",
            data: sales,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"text",
            async:false,       // prevent sales double entry
            success:function(answer){
                let invno = answer;
                $("#invno").val(invno);
            },
            error: function () {
                alert("Oops. Something went wrong!");
            },
            complete: function () {
                swal.fire({
                    title: 'Sales transaction successfully saved!',
                    type: 'success',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1500
                });

            // var invno = $("#invno").val();
            // var cash_tendered = $("#cash-tendered").val();
            // var change_amount = $("#change-amount").val();
            // //window.open("extensions/tcpdf/pdf/salereceipt.php?invno="+invno+"&cash_tendered="+cash_tendered+"&change_amount="+change_amount, "_blank"); 
            
            // var printWindow = window.open("extensions/tcpdf/pdf/salereceipt.php?invno="+invno+"&cash_tendered="+cash_tendered+"&change_amount="+change_amount, "_blank"); 
            // printWindow.onload = function() {
            //     printWindow.print();
            //     setTimeout(function() {
            //         printWindow.close();
            //     }, 5000);
            // };
            
            
            $('#modal-bill-order').modal('hide');
            initialize();
            }
        });
    } 
});    