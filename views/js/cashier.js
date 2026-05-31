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
                  search: '<span style="font-size:20px;font-weight:bold;color:#86f7a4;padding-top:3px;">[ F2 ] SEARCH:</span> _INPUT_',
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
    _gblBindNumericClasses('numeric');
    _gblBindNumericClasses3dec('numeric3');
    // Move around ordered items table using arrow keys
    navigateOrderGrid();
    getBranchPrefixCode();
    getBranchInfo();
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
          $("#btn-commit-sale").css({
                "border": "",
                "box-shadow": ""
            });
       }else{
          $("#btn-commit-sale").prop('disabled', false);
          $("#btn-commit-sale").css({
                "border": "3px solid lightgreen",
                "box-shadow": "0 0 6px lightgreen"
            });
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

    $(document).on('keydown', function (e) {
        if (e.key === "Escape" && $('#modal-bill-order').hasClass('show')) {
            $('#modal-bill-order').modal('hide');
        }
    });

    // ---------------- OVERRIDE PRICE --------------------------------------------------
    shortcut.add("F8",function() {
        if($('#btn-override').prop('disabled')){ 
        }else{
            $('#modal-override-order').modal('show');
        }
    });

    $('#modal-override-order').on('shown.bs.modal', function (e) {
        $('#tns-override').val('');
        $('#tns-override').focus();
    });  
    
    $("#btn-admin-direct-override").click(function(){
	    override_price();
    }); 

    $("#tns-override").keyup(function(event) {
        if (event.keyCode === 13) {
            override_price();
        }
    });
    // ----------------------------------------------------------------------------------

    // Increase width of datatable filtered box
    $(document).on('focus', 'div.dataTables_filter input', function () {
        this.style.border = '3px solid #7CFF7C';
        this.style.outline = 'none';
        this.style.opacity = '1.0';
        this.style.fontSize = '20px';
        this.style.boxShadow = '0 0 6px rgba(124, 255, 124, 0.6), 0 0 12px rgba(124, 255, 124, 0.3)';
        this.style.transition = 'all 0.2s ease-in-out';
    });

    $(document).on('blur', 'div.dataTables_filter input', function () {
        this.style.border = '1px solid #ccc';
        this.style.outline = 'none';
        this.style.boxShadow = 'none';
        this.style.opacity = '0.5';
    });

    $('.dataTables_filter input[type="search"]').css({'width':'350px','display':'inline-block'});

    reset_search();

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
            }else{
                swal.fire({
                    title: 'Barcode not found!',
                    type: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1500
                })
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
    // $(".cashier-form").on("keydown keypress blur focus", "input.qty,input.uprice", function(){
    //     let prodid = $(this).parent().parent().children(".qtyEntry").children().attr("prodid");

    //     let q = $(this).parent().parent().children(".qtyEntry").children().val();
    //     let quantity = q.replaceAll(",","");

    //     let p = $(this).parent().parent().children(".priceEntry").children().val();
    //     let price = p.replaceAll(",","");   

    //     // CHECK IF QUANTITY EXCEEDS 9999
    //     if (quantity > 9999.00) {
    //         swal.fire({
    //             title: 'Quantity exceeded, reverted input to zero!',
    //             type: 'info',
    //             allowOutsideClick: false,
    //             showConfirmButton: false,
    //             timer: 3000
    //         });
    //         quantity = 0.00;

    //         // SET INPUT VALUE TO 0.00
    //         $(this).parent().parent()
    //             .children(".qtyEntry")
    //             .children()
    //             .val("0.00");
    //     }

    //     let totalAmount = quantity * price;
        
    //     let productAmount = $(this).parent().parent().children(".totalAmount").children(".tamount");
    //     productAmount.val(numberWithCommas(totalAmount.toFixed(2)));

    //     _gblBindNumericClasses('numeric'); 

    //     addingTotalPrices();
    //     listProducts(); 
    // }); 

    $(".cashier-form").on("keydown keypress blur focus", "input.qty,input.uprice", function(){
        let prodid = $(this)
            .parent()
            .parent()
            .children(".qtyEntry")
            .children()
            .attr("prodid");

        // Quantity
        let q = $(this)
            .parent()
            .parent()
            .children(".qtyEntry")
            .children()
            .val();

        let quantity = parseFloat(q.replaceAll(",","")) || 0;

        // Current Price
        let p = $(this)
            .closest("tr")
            .find("input.uprice")
            .val();

        let price = parseFloat(p.replaceAll(",","")) || 0;

        // Discount Price
        let d = $(this)
            .closest("tr")
            .find("input.disprice")
            .val();

        let disprice = parseFloat(d.replaceAll(",","")) || 0;

        // Original Price
        let o = $(this)
            .closest("tr")
            .find("input.origprice")
            .val();

        let origprice = parseFloat(o.replaceAll(",","")) || 0;

        // Minimum Qty
        let m = $(this)
            .closest("tr")
            .find("input.minqty")
            .val();

        let minqty = parseFloat(m.replaceAll(",","")) || 0;

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
            $(this)
                .closest("tr")
                .find(".qty")
                .val("0.00");
        }

        // AUTO CHANGE PRICE
        if (quantity >= minqty && minqty > 0) {
            // Use discount/wholesale price
            $(this)
                .closest("tr")
                .find("input.uprice")
                .val(numberWithCommas(disprice.toFixed(2)))
                .css({
                    "background-color": "#c7520e",
                    "color": "white",
                    "border": "2px solid white"
                });

            price = disprice;

        } else {
            // Restore original price
            $(this)
                .closest("tr")
                .find("input.uprice")
                .val(numberWithCommas(origprice.toFixed(2)))
                .css({
                    "background-color": "#2e3547",
                    "color": "white",
                    "border": "1px solid rgba(255,255,255,0.4)"
                });
            price = origprice;
        }

        // COMPUTE TOTAL
        let totalAmount = quantity * price;

        let productAmount = $(this)
            .parent()
            .parent()
            .children(".totalAmount")
            .children(".tamount");

        productAmount.val(numberWithCommas(totalAmount.toFixed(2)));
        _gblBindNumericClasses('numeric');
        _gblBindNumericClasses3dec('numeric3');
        addingTotalPrices();
        listProducts();
    });

    // RIGHT CLICK QTY = VIEW WHOLESALE INFO
    $(".cashier-form").on("contextmenu", "input.qty", function(e){
        e.preventDefault();

        let $row = $(this).closest("tr");
        let prodname = $row.find(".prodname").val();
        let origprice = parseFloat($row.find(".origprice").val()) || 0;
        let minqty = parseFloat($row.find(".minqty").val()) || 0;
        let disprice = parseFloat($row.find(".disprice").val()) || 0;

        // ONLY SHOW IF MAY WHOLESALE
        if (minqty <= 0) {
            return;
        }

        swal.fire({
            title: 'WHOLESALE DETAILS',
            html:
                '<br><div style="text-align:center;font-size:16px;">' +
                    '<p>Product : ' + prodname + '</p>' +
                    '<p>Original Price : ₱ ' + numberWithCommas(origprice.toFixed(2)) + '</p>' +
                    '<p>Minimum Qty : ' + minqty + '</p>' +
                    '<p>Discount Price : ₱ ' + numberWithCommas(disprice.toFixed(2)) + '</p>' +
                '</div>',
            type: 'info',
            confirmButtonText: 'Close',
            confirmButtonClass: 'btn btn-outline-success',
            buttonsStyling: false
        });
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

    // ------------------ CASHIER RESET --------------------------------------------------------

    $('#modal-reset-cashier').on('shown.bs.modal', function (e) {
        $('#reset-override').val('');
        $('#reset-override').focus();
    }); 

    $('#modal-reset-cashier').on('shown.bs.modal', function (e) {
        $(".reset_content").empty();
        $('#reset-override').val('');

        let branchcode = $("#branch_code").val();
        let postedby = $("#tns-postedby").val();
        let reset_detail = $("#reset_detail").val();
        let sale_mode = 'Counter';

        let checkdata = new FormData();
        checkdata.append("branchcode", branchcode);
        checkdata.append("postedby", postedby);
        checkdata.append("reset_detail", reset_detail);
        checkdata.append("sale_mode", sale_mode);
        $.ajax({
            url:"ajax/sale_reset_preview.ajax.php",
            method: "POST",
            data: checkdata,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $(".reset_content").empty();
                var html = [];
                if (answer.length > 0){
                    if (reset_detail == 'By Product Category'){
                        html.push('<table class="table mx-auto w-auto" style="margin-top:20px;font-size:1.2em;border: 1px solid bisque;">');
                            html.push('<thead>');
                            html.push('<tr>');
                                html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">CATEGORY</th>');
                                html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">AMOUNT</th>');
                            html.push('</tr>');
                            html.push('</thead>');
                            
                            var total_amount = 0.00;
                            for(var i = 0; i < answer.length; i++) {
                                var reset = answer[i];
                                var catdescription = reset.catdescription;
                                var tamount = numberWithCommas(reset.tamount);
                                total_amount = total_amount + Number(reset.tamount);

                                html.push('<tr>');
                                    html.push('<td style="padding-top:4px;padding-bottom:4px;">'+catdescription+'</td>');
                                    html.push('<td style="padding-top:4px;padding-bottom:4px;text-align:right;">'+tamount+'</td>');
                                html.push('</tr>');
                            } 

                            html.push('<tr>');
                            html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;font-size:1.2em;">TOTAL SALES</th>');
                            html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;font-size:1.2em;color:greenyellow;">'+numberWithCommas(total_amount)+'</th>');
                            html.push('</tr>');
                        html.push('</table>');
                    }else{
                        html.push('<table class="table mx-auto w-auto" style="margin-top:20px;font-size:1.2em;border: 1px solid bisque;">');
                            html.push('<thead>');
                            html.push('<tr>');
                                html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">PRODUCT</th>');
                                html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">QTY</th>');
                                html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;">AMOUNT</th>');
                            html.push('</tr>');
                            html.push('</thead>');
                            
                            var total_amount = 0.00;
                            for(var i = 0; i < answer.length; i++) {
                                var reset = answer[i];
                                var prod_name = (reset.brandname != '') ? reset.brandname + ' ' + reset.prodname : reset.prodname;
                                var qty = numberWithCommas(reset.qty);
                                var tamount = numberWithCommas(reset.tamount);
                                total_amount = total_amount + Number(reset.tamount);

                                html.push('<tr>');
                                    html.push('<td style="padding-top:4px;padding-bottom:4px;">'+prod_name+'</td>');
                                    html.push('<td style="padding-top:4px;padding-bottom:4px;text-align:right;">'+qty+'</td>');
                                    html.push('<td style="padding-top:4px;padding-bottom:4px;text-align:right;">'+tamount+'</td>');
                                html.push('</tr>');
                            } 

                            html.push('<tr>');
                            html.push('<th colspan="2" style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;font-size:1.2em;">TOTAL SALES</th>');
                            html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid bisque;font-size:1.2em;color:greenyellow;">'+numberWithCommas(total_amount)+'</th>');
                            html.push('</tr>');
                        html.push('</table>');                
                    }    
                
                    $('.reset_content').html(html.join('')); 
                    $("#reset-override").prop('disabled', false);
                    $("#btn-admin-direct-reset").prop('disabled', false);
                    // $("#btn-reset-request").prop('disabled', false);
                    $('#reset-override').focus();
                }else{
                    html.push('<table class="table mx-auto w-auto" style="margin-top:20px;font-size:1.2em;">');
                        html.push('<thead>');
                            html.push('<tr>');
                                html.push('<th style="padding-top:6px;padding-bottom:6px;border: 1px solid #fa6f5c;color: #fa6f5c;">NO SALES FOUND TO RESET!</th>');
                            html.push('</tr>');
                        html.push('</thead>');
                    html.push('</table>');
                    $('.reset_content').html(html.join(''));
                                
                    $("#reset-override").prop('disabled', true);
                    $("#btn-admin-direct-reset").prop('disabled', true);
                    // $("#btn-reset-request").prop('disabled', true);
                }
            }
        });
    });  
   
    $("#btn-admin-direct-reset").click(function(){
        reset_cashier();
    });

    $('#reset-override').keypress(function(e) {
        if (e.which == 13) {
            $('#btn-admin-direct-reset').click();
        }
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
                    if(answer["overridekey"] === undefined){
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
                            buttonsStyling: false,
                            focusConfirm: true,
                            allowEnterKey: true
                            // onOpen: function() {
                            //     Swal.getConfirmButton().focus();
                            //     // setTimeout(function () {
                            //     //     Swal.getConfirmButton().focus();
                            //     // }, 50);
                            // }
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

                                                window.open("reports/resetprint.php?branchcode="+branchcode+"&cashierid="+cashierid+"&branch_name="+branch_name+"&resetcode="+resetcode+"&resetdetail="+resetdetail+"&resetype="+resetype, "_blank"); 

                                                // if (resetdetail == 'By Product Category'){
                                                //     alert(reset);
                                                //     window.open("reports/resetprint.php?branchcode="+branchcode+"&cashierid="+cashierid+"&branch_name="+branch_name+"&resetcode="+resetcode+"&resetdetail="+resetdetail+"&resetype="+resetype, "_blank"); 
                                                // }else{
                                                //     window.open("reports/resetprintbyproduct.php?branchcode="+branchcode+"&cashierid="+cashierid+"&branch_name="+branch_name+"&resetcode="+resetcode+"&resetdetail="+resetdetail+"&resetype="+resetype, "_blank");
                                                // }

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

    function getBranchInfo(){
        let branchcode =  $("#branch_code").val();
        let branch_code = new FormData();
        branch_code.append("branchcode", branchcode);
        $.ajax({
            url:"ajax/get_branch_name.ajax.php",   
            method: "POST",                
            data: branch_code,                    
            cache: false,                  
            contentType: false,            
            processData: false,            
            dataType:"json",               
            success:function(answer){
                $("#branch_name").val(answer["bname"]);
                $("#reset_detail").val(answer["resetdetail"]);
            }
        });      
    }

    function loadBranchProducts(){
        let branchcode = $("#branch_code").val();
        let product_list = new FormData();
        product_list.append("branchcode", branchcode);
        $.ajax({
            url: "ajax/branch_product_list.ajax.php",
            method: "POST",
            data: product_list,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(answer){
                // Clear table
                pl.clear();
                for(var i = 0; i < answer.length; i++) {

                    let prod = answer[i];

                    let prodid = prod.prodid;
                    let prodname = prod.prodname;
                    let barcode = prod.barcode;

                    let uprice = numberWithCommas(prod.uprice);
                    let ucost = numberWithCommas(prod.ucost);

                    let disprice = prod.disprice;
                    let minqty = parseFloat(prod.minqty);
                    let vatdesc = prod.vatdesc;

                    var button = `
                        <button type='button'
                            class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct recoverButton'
                            prodid='${prodid}'
                            prodname='${prodname}'
                            uprice='${uprice}'
                            ucost='${ucost}'
                            disprice='${disprice}'
                            minqty='${minqty}'
                            vatdesc='${vatdesc}'
                            barcode='${barcode}'>
                            <i class='icon-check'></i>
                        </button>
                    `;

                    // Add row only
                    let rowNode = pl.row.add([
                        prodname,
                        uprice,
                        prodid,
                        ucost,
                        disprice,
                        minqty,
                        vatdesc,
                        barcode,
                        button
                    ]).node();

                    // Change row color
                    if(minqty > 0){
                        $(rowNode).css({
                            "background-color": "rgba(255, 178, 99, 0.05)",
                            "color": "#36f569",
                            // "font-weight": "bold"
                        });
                    }
                }
                // Draw table ONCE
                pl.draw();
            }
        });
    }  

    // function loadBranchProducts(){
    //     let branchcode = $("#branch_code").val();
    //     let product_list = new FormData();
    //     product_list.append("branchcode", branchcode);
    //     $.ajax({
    //         url:"ajax/branch_product_list.ajax.php",
    //         method: "POST",
    //         data: product_list,
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         dataType:"json",
    //         success:function(answer){
    //             for(var i = 0; i < answer.length; i++) {
    //                 let prod = answer[i];
    //                 let prodid = prod.prodid;
    //                 let prodname = prod.prodname;
    //                 let barcode = prod.barcode;

    //                 let price_amount = prod.uprice;
    //                 let uprice = numberWithCommas(price_amount);

    //                 let ucost_amount = prod.ucost;
    //                 let ucost = numberWithCommas(ucost_amount);

    //                 let disprice = prod.disprice;
    //                 let minqty = prod.minqty;

    //                 let vatdesc = prod.vatdesc;

    //                 var button = "<td><button type='button' class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 addProduct recoverButton' prodid='"+prodid+"' prodname='"+prodname+"' uprice='"+uprice+"' ucost='"+ucost+"' disprice='"+disprice+"' minqty='"+minqty+"' vatdesc='"+vatdesc+"' barcode='"+barcode+"'><i class='icon-check'></i></button></td>";  
    //                 pl.row.add([prodname, uprice, prodid, ucost, disprice, minqty, vatdesc, barcode, button]); 
    //             }
    //             pl.draw();
    //         }
    //     });  	
    // }

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
        $("#invno").val('');
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
                if ((txt_qty == "0.000")||!(txt_qty)||(txt_uprice == "0.00")){  
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

                productList.push({"qty" : qty.toFixed(3),
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
                        '<input type="text" style="font-size:1.3em;padding:2px;" class="form-control prodname" prodid="'+prodid+'" name="addProduct" value="'+prodname+'" readonly required>'+
                    '</div>'+
                '</td>'+   

                '<td class="qtyEntry" width="15%" style="padding:2px;">'+
                    '<input type="text" style="font-size:1.3em;padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control qty numeric3" prodid="'+prodid+'" name="qty" value="1.000" required>'+
                '</td>' +

                '<td class="priceEntry" width="15%" style="padding:2px;">'+
                    '<input type="text" style="font-size:1.3em;padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control uprice numeric" prodid="'+prodid+'" name="uprice" value="'+uprice+'" disabled required>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control ucost" prodid="'+prodid+'" name="ucost" value="'+ucost+'" required disabled>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control disprice" prodid="'+prodid+'" name="disprice" value="'+disprice+'" required disabled>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control minqty" prodid="'+prodid+'" name="minqty" value="'+minqty+'" required disabled>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control vatdesc" prodid="'+prodid+'" name="vatdesc" value="'+vatdesc+'" required disabled>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control barcode" prodid="'+prodid+'" name="barcode" value="'+barcode+'" required disabled>'+
                    '<input type="hidden" style="padding:2px;padding-right:17px;text-align:right;color:transparent;text-shadow: 0 0 0 #ffffff;" class="form-control origprice" prodid="'+prodid+'" name="origprice" value="'+uprice+'" required disabled>'+
                '</td>' +   

                '<td class="totalAmount" width="15%" style="padding:2px;">'+
                    '<input type="text" style="font-size:1.3em;padding:2px;padding-right:17px;text-align:right;" class="form-control tamount" productPrice="'+uprice+'" name="tamount" value="0.00" readonly required>'+
                '</td>' +                                
        '</tr>');
    
        addingTotalPrices();
        listProducts();
        reset_search();
        // _gblBindNumericClasses('numeric');
    }

    function bill_order(){
        let prefix = $("#prefix").val();    
        let userid = $("#userid").val();    

        let format_sdate = $("#date-sdate").val().split("/");
        format_sdate = format_sdate[2] + "-" + format_sdate[0] + "-" + format_sdate[1];

        let branchcode = $("#branch_code").val(); 
        let sdate = format_sdate; 
        let stime = getCurrentTime();  // helper.js
        let salemode = 'Counter'; 
        let customercode = ''; 
        let soldto = $("#tns-soldto").val(); 
        let status = 'Sold'; 

        // alert(prefix + ' ' + userid + ' ' + branchcode + ' ' + sdate + ' ' + stime + ' ' + salemode + ' ' + sellerid);

        var txt_amount = $("#num-amount").val(); 
        var txt_discount = $("#num-discount").val(); 
        var txt_netamount = $("#num-netamount").val(); 

        var txt_vatable = $("#num-vatable").val(); 
        var txt_excempt = $("#num-excempt").val(); 
        var txt_vatamnt = $("#num-vatamnt").val(); 

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

            let invno = $("#invno").val();
            let cash_tendered = $("#cash-tendered").val();
            let change_amount = $("#change-amount").val();

            window.open(
                "reports/order_slip.php?invno=" + invno +
                "&cash_tendered=" + encodeURIComponent(cash_tendered) +
                "&change_amount=" + encodeURIComponent(change_amount),
                "_blank"
            );
            
            // window.open("reports/orderslip.php?invno="+invno+"&cash_tendered="+cash_tendered+"&change_amount="+change_amount, "_blank"); 
            
            // var printWindow = window.open("reports/orderslip.php?invno="+invno+"&cash_tendered="+cash_tendered+"&change_amount="+change_amount, "_blank"); 
            // printWindow.onload = function() {
            //     printWindow.print();
            //     setTimeout(function() {
            //         printWindow.close();
            //     }, 4000);
            // };

            // window.open(
            //     "reports/order_slip.php?invno=" + invno +
            //     "&cash_tendered=" + cash_tendered +
            //     "&change_amount=" + change_amount,
            //     "_blank"
            // );
            
            
            $('#modal-bill-order').modal('hide');
            initialize();
            }
        });
    } 

    function override_price(){ 
      // Focus input override textbox after sweetalert is closed
      $('#tns-override').focus();
      if ($("#tns-override").val() == ''){  // empty override key
         swal.fire({
            title: 'Cannot proceed, override key must be entered!',
            type: 'error',
            confirmButtonText: 'Got it',
            confirmButtonClass: 'btn btn-outline-success',
            allowOutsideClick: false,
            buttonsStyling: false
         }).then(function(result){
            if(result.value) {              
              $('#tns-override').focus();
            }
         });
        }else{
           let override_key = $("#tns-override").val();
           var override_sale = new FormData();
           override_sale.append("override_key", override_key);
           $.ajax({
              url:"ajax/get_override_key.ajax.php",
              method: "POST",
              data: override_sale,
              cache: false,
              contentType: false,
              processData: false,
              dataType:"json",
              success:function(answer){
                if(answer["overridekey"] === undefined){  // override key not found
                  swal.fire({
                    title: 'Unidentified override key!',
                    type: 'info',
                    confirmButtonText: 'Got it',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false,
                  }).then(function(result){
                    if(result.value) { 
                      $('#tns-override').val('');
                    }
                  });
                }else{    // Valid override key
                    swal.fire({
                        title: 'Do you want to OVERRIDE order transaction?',
                        text: 'You will not be able to revert this process.',
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Override it!',
                        cancelButtonText: 'Cancel!',
                        confirmButtonClass: 'btn btn-outline-success',
                        cancelButtonClass: 'btn btn-outline-danger',
                        allowOutsideClick: false,
                        buttonsStyling: false
                        }).then(function(result) {
                        if(result.value) {
                            $("#modal-override-order").modal('hide');
                            $("#btn-override").prop('disabled', true);
                            // Enable all price input control
                            $(".priceEntry *").prop('disabled', false);
                        }else if (result.dismiss === Swal.DismissReason.cancel){
                            $('#tns-override').val('');
                        }
                    });

                    // // -----------------------------------------------------------------------
                    // if(answer["price"] == 1){     // add this line only - price field in users table
                    //   swal.fire({
                    //     title: 'Do you want to OVERRIDE order transaction?',
                    //     text: 'You will not be able to revert this process.',
                    //     type: 'question',
                    //     showCancelButton: true,
                    //     confirmButtonText: 'Yes, Override it!',
                    //     cancelButtonText: 'Cancel!',
                    //     confirmButtonClass: 'btn btn-outline-success',
                    //     cancelButtonClass: 'btn btn-outline-danger',
                    //     allowOutsideClick: false,
                    //     buttonsStyling: false
                    //   }).then(function(result) {
                    //     if(result.value) {
                    //       $("#modal-override-order").modal('hide');
                    //       $("#btn-override").prop('disabled', true);
                    //       // Enable all price input control
                    //       $(".priceEntry *").prop('disabled', false);
                    //     }else if (result.dismiss === Swal.DismissReason.cancel){
                    //       $('#tns-override').val('');
                    //     }
                    //   });
                    // }else{    // add this block
                    //   swal.fire({
                    //     title: 'You are not authorize to override item price!',
                    //     type: 'info',
                    //     confirmButtonText: 'Got it',
                    //     confirmButtonClass: 'btn btn-outline-success',
                    //     allowOutsideClick: false,
                    //     buttonsStyling: false,
                    //   }).then(function(result){
                    //     if(result.value) { 
                    //       $('#tns-override').val('');
                    //     }
                    //   });
                    // }         // add  
                    // ----------------------------------------------------------------------- 
                }
              },
              error: function () {
                 alert("Oops. Something went wrong!");
              },
              complete: function () {
             }
           });
       } // if (override == '')  
   }
});    