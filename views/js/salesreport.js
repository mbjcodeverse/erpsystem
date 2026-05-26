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
            $("#btn-print-report").prop('disabled', false);
            $("#btn-export").prop('disabled', false);
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

                            for(var i = 0; i < answer.length; i++) {
                                var sales = answer[i];
                                var catdescription = sales.catdescription;
                                var total_qty = numberWithCommas(sales.total_qty);
                                var total_amount = numberWithCommas(sales.total_amount);
                                var total_cost = numberWithCommas(sales.total_cost);
                                var total_profit = numberWithCommas(sales.total_profit);
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
                }
                $('.sales_content').html(html.join('')); 
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
        var location = 'data:application/vnd.ms-excel;base64,';
        var excelTemplate = '<html> ' +
        '<head> ' +
        '<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/> ' +
        '</head> ' +
        '<body> ' +
        document.getElementById("collection_content").innerHTML +
        '</body> ' +
        '</html>'
        window.location.href = location + window.btoa(excelTemplate);
    }    
});    