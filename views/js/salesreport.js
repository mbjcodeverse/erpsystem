$(function() {
    $(".select").select2({
        minimumResultsForSearch: Infinity,
    });

    $(".select-search").select2();

    $('#lst_date_range').data('daterangepicker').setStartDate(moment('2026-05-26'));
    $('#lst_date_range').data('daterangepicker').setEndDate(moment());

    $('#lst_date_range').daterangepicker({
        ranges:{
          'All'           : [moment('2026-05-26'), moment()],
          'Today'         : [moment(),moment()],
          'Yesterday'     : [moment().subtract(1,'days'), moment().subtract(1,'days')],
          'Last 7 Days'   : [moment().subtract(6,'days'), moment()],
          'This Month'    : [moment().startOf('month'), moment().endOf('month')]
        }
    });

    $("#lbl-lst-date-range").click(function(){
        $('#lst_date_range').data('daterangepicker').setStartDate(moment('2026-05-26'));
        $('#lst_date_range').data('daterangepicker').setEndDate(moment());
    });

    $("#lbl-lst-branchcode").click(function(){
        $("#lst-branchcode").val('').trigger('change');
    });

    $("#lbl-lst-categorycode").click(function(){
        $("#lst-categorycode").val('').trigger('change');
    });
    
    $("#lbl-lst-salemode").click(function(){
        $("#lst-salemode").val('').trigger('change');
    });

    $("#lbl-lst-status").click(function(){
        $("#lst-status").val('').trigger('change');
    }); 

    $('#lst-reptype, #lst-branchcode, #lst_date_range, #lst-categorycode, #lst-salemode, #lst-status').on("change", function() {
        $(".sales_content").empty();
        if ($('#lst-reptype').val() != ''){
            $("#btn-print-report").prop('disabled', true);
            $("#btn-export").prop('disabled', true);
            $("#btn-generate").prop('disabled', false);
        }
    });    
    
    $("#btn-generate").click(function(){
        let reptype = $("#lst-reptype").val();
        let branchcode = $("#lst-branchcode").val();
        var date_range = $("#lst_date_range").val();
        if (date_range != ''){
            var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
            var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
        } else {
            var start_date = '';
            var end_date = '';
        }
        let categorycode = $("#lst-categorycode").val();
        let salemode = $("#lst-salemode").val();
        let status = $("#lst-status").val();

        // alert(branchcode + ' ' + categorycode + ' ' + start_date + ' ' + end_date + ' ' + status + ' ' + salemode);
        
        var data = new FormData();
        data.append("reptype", reptype);
        data.append("branchcode", branchcode);
        data.append("start_date", start_date);
        data.append("end_date", end_date);
        data.append("categorycode", categorycode);
        data.append("salemode", salemode);
        data.append("status", status);
        
        $.ajax({
            url: "ajax/sales_report.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(answer) {
                $(".sales_content").empty();
                var html = [];
                if (reptype == 1){
                    // alert('1');
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                                html.push('<tr>');
                                    html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Cost</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Profit</th>');
                                html.push('</tr>');
                            html.push('</thead>');

                            for(let i = 0; i < answer.length; i++) {
                                let sales = answer[i];
                                let catdescription = sales.catdescription;
                                let total_qty = numberWithCommas3dec(sales.total_qty);
                                let total_amount = numberWithCommas(sales.total_amount);
                                let total_cost = numberWithCommas(sales.total_cost);
                                let total_profit = numberWithCommas(sales.total_profit);
                                // alert(total_amount);
                                html.push('<tr>');
                                    if (i == answer.length - 1){
                                        html.push('<td style="font-size:1.1em;font-weight:bold;border-top: 2px solid white;">OVERALL AMOUNT</td>');
                                        html.push('<td style="font-size:1.1em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_qty+'</td>');
                                        html.push('<td style="font-size:1.1em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_amount+'</td>');
                                        html.push('<td style="font-size:1.1em;font-weight:bold;text-align:right;border-top: 2px solid white;border-left: 2px solid white;color:#fc8677;">'+total_cost+'</td>');
                                        html.push('<td style="font-size:1.1em;font-weight:bold;text-align:right;border-top: 2px solid white;color:#0FFF50;">'+total_profit+'</td>');
                                    }else{
                                        html.push('<td>'+catdescription+'</td>');
                                        html.push('<td style="text-align:right;">'+total_qty+'</td>');
                                        html.push('<td style="text-align:right;">'+total_amount+'</td>');
                                        html.push('<td style="text-align:right;border-left: 2px solid white;color:#fc8677;">'+total_cost+'</td>');
                                        html.push('<td style="text-align:right;color:#0FFF50;">'+total_profit+'</td>');
                                    }
                                html.push('</tr>');
                            }   
                        html.push('</table>');
                  html.push('</div>');
                }else if (reptype == 2){
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                                html.push('<tr>');
                                    html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Category</th>');
                                    html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Product</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Amount</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Cost</th>');
                                    html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Profit</th>');
                                html.push('</tr>');
                            html.push('</thead>');

                            for(var i = 0; i < answer.length; i++) {
                                let sales = answer[i];
                                let catdescription = sales.catdescription;
                                let prodname = sales.prodname;

                                if (sales.prodname == null){
                                    prodname = '';
                                    catdescription = '';
                                }else{
                                    if (i == 0){
                                        var prev_catdescription = sales.catdescription;
                                    }else{
                                        var curr_catdescription = sales.catdescription;
                                        if (prev_catdescription == curr_catdescription){
                                            catdescription = '';
                                        }
                                            var prev_catdescription = curr_catdescription;
                                    }                 
                                }

                                let total_qty = numberWithCommas3dec(sales.total_qty);
                                let total_amount = numberWithCommas(sales.total_amount);
                                let total_cost = numberWithCommas(sales.total_cost);
                                let total_profit = numberWithCommas(sales.total_profit);

                                html.push('<tr>');
                                    html.push('<td>'+catdescription+'</td>');
                                    html.push('<td>'+prodname+'</td>');
                                    if (sales.prodname == null){
                                        html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_qty+'</td>');
                                        html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_amount+'</td>');
                                        html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;border-left: 2px solid white;color:#fc8677;">'+total_cost+'</td>');
                                        html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+total_profit+'</td>');
                                    }else{
                                        html.push('<td style="text-align:right;">'+total_qty+'</td>');
                                        html.push('<td style="text-align:right;">'+total_amount+'</td>');
                                        if (sales.total_profit > 0.00){
                                            html.push('<td style="text-align:right;color:#fc8677;border-left: 2px solid white;">'+total_cost+'</td>');
                                            html.push('<td style="text-align:right;color:#0FFF50;">'+total_profit+'</td>');
                                        }else{
                                            html.push('<td style="text-align:right;color:#0FFF50;border-left: 2px solid white;">'+total_cost+'</td>');
                                            html.push('<td style="text-align:right;color:#fc8677;">'+total_profit+'</td>');
                                        }
                                    }  
                                html.push('</tr>');
                            }
                        html.push('</table>');
                   html.push('</div>');          
                }else if (reptype == 3){
                    html.push('<div class="table-responsive" style="overflow-y: auto; max-height: 470px;">');
                        html.push('<table class="table mx-auto w-auto">');
                            html.push('<thead>');
                                html.push('<tr>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Date</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Invoice #</th>');
                                html.push('<th class="table_head_left_fixed" style="padding-top:8px;padding-bottom:8px;">Products</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Qty</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Price</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Total</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Cost</th>');
                                html.push('<th class="table_head_right_fixed" style="padding-top:8px;padding-bottom:8px;">Profit</th>');
                                html.push('</tr>');
                            html.push('</thead>');

                            for(let i = 0; i < answer.length; i++) {
                                let sales = answer[i];

                                let inv_date = sales.sdate;
                                let sdate = inv_date.substring(5, 7) + '/' + inv_date.substring(8, 10) + '/' + inv_date.substring(0, 4);

                                let invno = sales.invno;
                                let status = sales.status;
                                let prodname = sales.prodname;
                                let qty = numberWithCommas3dec(sales.qty);
                                let price = numberWithCommas(sales.uprice);
                                let tamount = numberWithCommas(sales.tamount);
                                let cost = numberWithCommas(sales.cost);
                                let profit = numberWithCommas(sales.profit);

                                if (prodname == null){
                                    invno = '';
                                    pdesc = '';
                                    price = '';
                                    sdate = '';
                                }else{
                                    if (i == 0){
                                        var prev_invno = sales.invno;
                                        var prev_sdate = sales.sdate;
                                    }else{
                                        var curr_invno = sales.invno;
                                        if (prev_invno == curr_invno){
                                            invno = '';
                                        }
                                        var prev_invno = curr_invno;
                                        var curr_sdate = sales.sdate;
                                        if (prev_sdate == curr_sdate){
                                            sdate = '';
                                        }
                                        var prev_sdate = curr_sdate;                    
                                    }                 
                                }

                                html.push('<tr>');
                                html.push('<td>'+sdate+'</td>');

                                if (status == 'Void'){
                                    html.push('<td style="color:orange;">'+invno+'</td>');
                                }else{
                                    html.push('<td>'+invno+'</td>');
                                }

                                if (i == answer.length - 1){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;">OVERALL AMOUNT</td>');
                                }else{
                                    if (prodname == null){
                                        html.push('<td style="text-align:right;"></td>');
                                    }else{
                                        html.push('<td>'+prodname+'</td>');
                                    }
                                }
                                
                                if (prodname == null){
                                    html.push('<td style="text-align:right;"></td>');
                                }else{
                                    html.push('<td style="text-align:right;">'+qty+'</td>');
                                } 

                                html.push('<td style="text-align:right;">'+price+'</td>');

                                if (prodname == null){
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;">'+tamount+'</td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;border-left: 2px solid white;color:#fc8677;">'+cost+'</td>');
                                    html.push('<td style="font-size:1.2em;font-weight:bold;text-align:right;border-top: 2px solid white;color:#0FFF50;">'+profit+'</td>');
                                }else{
                                    html.push('<td style="text-align:right;">'+tamount+'</td>');
                                    if (sales.profit > 0.00){
                                        html.push('<td style="text-align:right;color:#fc8677;border-left: 2px solid white;">'+cost+'</td>');
                                        html.push('<td style="text-align:right;color:#0FFF50;">'+profit+'</td>');
                                    }else{
                                        html.push('<td style="text-align:right;color:#fc8677;border-left: 2px solid white;">'+cost+'</td>');
                                        html.push('<td style="text-align:right;color:#fc8677;">'+profit+'</td>');
                                    }
                                }
                                html.push('</tr>'); 
                            }
                        html.push('</table>');
                   html.push('</div>'); 
                }

                $('.sales_content').html(html.join('')); 
                $("#btn-print-report").prop('disabled', true);
                $("#btn-export").prop('disabled', false);
            }
        });
    });

    $("#btn-export").click(function(){
        exportToExcel();
    });
    
    // $("#btn-print-report").click(function(){
    //     let reptype = $("#lst-reptype").val();
    //     let branchcode = $("#lst-branchcode").val();
    //     if (branchcode == null){
    //         branchcode = '';
    //     }
    //     let categorycode = $("#lst-categorycode").val();
    //     let status = 'Sold';
        
    //     var date_range = $("#lst_date_range").val();
    //     if (date_range != ''){
    //         var start_date = date_range.substring(6, 10) + '-' + date_range.substring(0, 2) + '-' + date_range.substring(3, 5);
    //         var end_date = date_range.substring(19, 23) + '-' + date_range.substring(13, 15) + '-' + date_range.substring(16, 18);
    //     } else {
    //         var start_date = '';
    //         var end_date = '';
    //     }
    //     let generatedby = $("#tns-generatedby").val();
        
    //     window.open("extensions/tcpdf/pdf/salesprint.php?reptype="+reptype+"&branchcode="+branchcode+"&categorycode="+categorycode+"&status="+status+"&start_date="+start_date+"&end_date="+end_date+"&generatedby="+generatedby, "_blank");
    // }); 

    function exportToExcel() {
        var table = document.querySelector(".sales_content table");
        if (!table) {
            alert("No report data to export.");
            return;
        }
        var wb = XLSX.utils.table_to_book(table, { sheet: "Sales Report" });
        XLSX.writeFile(wb, "SalesReport.xlsx");
    }
});    