var plst;

$(function () {
    if (!$.fn.DataTable.isDataTable('.productListTable')) {
        plst = $('.productListTable').DataTable({
            deferRender: true,
            processing: true,
            autoWidth: false,
            responsive: true,
            scrollY: 360,
            scrollX: true,
            scrollCollapse: true,
            pageLength: 25,

            lengthMenu: [
                [25, 50],
                [25, 50]
            ],

            dom: '<"datatable-header"><"extra-row"><"datatable-scroll"t><"datatable-footer"fp>',

            columnDefs: [
                {
                    targets: '_all',
                    className: 'align-middle'
                }
            ],

            drawCallback: function () {
                $(".productListTable td").css({
                    "padding-top": "5px",
                    "padding-bottom": "5px"
                });
            },

            language: {
                loadingRecords: 'Please wait - loading...',
                processing:
                    '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i>',

                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',

                lengthMenu: '<span>Show:</span> _MENU_',

                paginate: {
                    first: 'First',
                    last: 'Last',
                    next:
                        $('html').attr('dir') == 'rtl'
                            ? '&larr;'
                            : '&rarr;',
                    previous:
                        $('html').attr('dir') == 'rtl'
                            ? '&rarr;'
                            : '&larr;'
                }
            },

            initComplete: function () {
                let api = this.api();
                setTimeout(function () {
                    api.columns.adjust();
                    if (api.responsive) {
                        api.responsive.recalc();
                    }
                    api.draw(false);
                }, 300);
            }
        });
    }

    $(".select").select2({
        minimumResultsForSearch: Infinity
    });

    $(".select-search").select2();

    $("#lbl-sel-brandcode").click(function () {
        $("#sel-brandcode").val('').trigger('change');
    });

    $("#lbl-lst-categorycode").click(function () {
        $("#lst-categorycode").val('').trigger('change');
    });

    $("#lbl-lst-brandcode").click(function () {
        $("#lst-brandcode").val('').trigger('change');
    });

    $("#lbl-lst-vatdesc").click(function () {
        $("#lst-vatdesc").val('').trigger('change');
    });

    $('#num-specs')
        .on('keypress', function (e) {
            let char = String.fromCharCode(e.which);
            if (/[0-9]/.test(char)) return;
            if (
                char === '.' &&
                $(this).val().indexOf('.') === -1
            ) return;
            e.preventDefault();
        })

        .on('input', function () {
            let val = $(this).val();
            val = val.replace(/[^0-9.]/g, '');
            let parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts.slice(1).join('');
            }
            $(this).val(val);
        });

    $('#num-ucost, #num-uprice').on(
        "change keyup",
        function () {
            let num_ucost = $('#num-ucost').val();
            let num_uprice = $('#num-uprice').val();
            let ucost =
                parseFloat(
                    num_ucost.replace(/,/g, "")
                ) || 0;
            let price =
                parseFloat(
                    num_uprice.replace(/,/g, "")
                ) || 0;
            let profit = 0;

            if (price > 0) {
                profit = price - ucost;
            }

            $('#num-profit').val(
                numberWithCommas(
                    profit.toFixed(2)
                )
            );
        }
    );

    $('#lst-categorycode, #lst-brandcode, #lst-vatdesc')
        .on("change", function () {
            let categorycode = $("#lst-categorycode").val();
            let brandcode = $("#lst-brandcode").val();
            let vatdesc = $("#lst-vatdesc").val();
            let data = new FormData();
            data.append("categorycode", categorycode);
            data.append("brandcode", brandcode);
            data.append("vatdesc",vatdesc);
            $.ajax({
                url: "ajax/product_search_list.ajax.php",
                method: "POST",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                beforeSend: function () {
                    plst.clear().draw();
                },

                success: function (answer) {
                    plst.clear();
                    for (var i = 0; i < answer.length; i++) {
                        let pl = answer[i];
                        let catdescription = pl.catdescription;
                        let prodid = pl.prodid;
                        let prodname = pl.prodname;
                        let uprice = pl.uprice;
                        let ucost = pl.ucost;
                        let profit = pl.profit;

                        let button =
                            "<button type='button' " +
                            "class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnProduct' " +
                            "prodid='" + prodid + "'>" +
                            "<i class='icon-pencil3'></i>" +
                            "</button>";

                        plst.row.add([
                            prodid,
                            catdescription,
                            prodname,
                            uprice,
                            ucost,
                            profit,
                            button
                        ]);

                    }
                    plst.draw(false);
                    setTimeout(function () {
                        plst.columns.adjust();
                        if (plst.responsive) {
                            plst.responsive.recalc();
                        }
                        // simulate header interaction
                        plst.draw(false);
                    }, 300);
                },

                error: function (xhr, status, error) {
                    console.log(error);
                }
            });
        });

    $("#lst-categorycode").val('').trigger('change');

    $('#modal-search-product').on('shown.bs.modal', function () {
        setTimeout(function () {
            $.fn.dataTable
                .tables({ visible: true, api: true })
                .columns.adjust()
                .responsive.recalc();
            plst.draw(false);
        }, 200);
    });

    $(".productListTable tbody").on("click", "button.btnProduct", function(){ 
        $("#modal-search-product").modal("hide");
        $("#trans_type").val("Update");
        var prodid = $(this).attr("prodid");
        var data = new FormData();
        data.append("prodid", prodid); 
        $.ajax({
            url:"ajax/product_get_record.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $("#sel-categorycode").val(answer["categorycode"]).trigger('change');
                $("#sel-brandcode").val(answer["brandcode"]).trigger('change');
                $("#chk-purchaseitem").prop("checked", answer["purchaseitem"] == 1);
                $("#chk-isactive").prop("checked", answer["isactive"] == 1);
                $("#txt-prodid").val(answer["prodid"]);
                $("#txt-barcode").val(answer["barcode"]);
                $("#txt-pdesc").val(answer["pdesc"]);
                $("#sel-sellunit").val(answer["sellunit"]).trigger('change');
                $("#num-specs").val(answer["specs"]);
                $("#sel-measure").val(answer["measure"]).trigger('change');
                $("#num-uprice").val(numberWithCommas(answer["uprice"]));
                $("#num-profit").val(numberWithCommas(answer["profit"]));
                $("#num-ucost").val(numberWithCommas(answer["ucost"]));
                $("#txt-abbr").val(answer["abbr"]);
                $("#sel-vatdesc").val(answer["vatdesc"]).trigger('change');
                $("#num-reorder").val(numberWithCommas(answer["reorder"]));
                $("#num-disprice").val(numberWithCommas(answer["disprice"]));
                $("#num-minqty").val(numberWithCommas(answer["minqty"]));
            }
        });    
    });  

    $("#btn-save").click(function () {
        let requiredFields = [
            { id: "#sel-categorycode", label: "Category" },
            { id: "#txt-pdesc", label: "Product Name" },
            { id: "#sel-sellunit", label: "Selling Unit" },
            { id: "#num-uprice", label: "Unit Price", numeric: true }
        ];

        let emptyFields = [];
        requiredFields.forEach(function (field) {
            let value = $(field.id).val();

            if (field.numeric) {
                let numericValue = parseFloat(value);
                if (!value || isNaN(numericValue) || numericValue <= 0) {
                    emptyFields.push(field.label);
                }
            } else {
                if (!value || value.trim() === '') {
                    emptyFields.push(field.label);
                }
            }
        });

        if (emptyFields.length > 0) {
            swal.fire({
                title: 'REQUIRED FIELDS MISSING',
                type: 'warning',  
                html: '<div style="text-align:left;margin-left:116px;font-size:1.1em;"><br>' +
                    '<p style="margin-left:0px;font-size:1.1em;">The following fields are required:</p>' +
                    '<ul style="margin-left:20px;font-size:1.1em;">' +
                    emptyFields.map(f => `<li>${f}</li>`).join('') +
                    '</ul></div>',
                confirmButtonText: 'Got it',
                customClass: {
                    confirmButton: 'btn btn-outline-danger btn-lg-custom'
                },
                buttonsStyling: false
            });
        } else {
            swal.fire({
                title: 'Do you want to save new product details?',
                type: 'question',  
                showCancelButton: true,
                confirmButtonText: 'Yes, Save it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-outline-success btn-lg-custom',
                    cancelButton: 'btn btn-outline-danger btn-lg-custom'
                },
                allowOutsideClick: false,
                buttonsStyling: false
            }).then(function (result) {
                if (result.value) {  
                    saveProduct();  
                }
            });
        }
    });    

    function saveProduct(){
        $('#btn-save').prop('disabled', true);

        let trans_type = $("#trans_type").val();
        let categorycode = $("#sel-categorycode").val();
        let brandcode = $("#sel-brandcode").val();
        let purchaseitem = $('#chk-purchaseitem').is(':checked') ? 1 : 0;
        let isactive = $('#chk-isactive').is(':checked') ? 1 : 0;
        let prodid = $("#txt-prodid").val();
        let barcode = $("#txt-barcode").val();
        let pdesc = $("#txt-pdesc").val();
        let sellunit = $("#sel-sellunit").val();
        let specs = $("#num-specs").val();
        let measure = $("#sel-measure").val();
        let uprice = removeComma($("#num-uprice").val());        
        let profit = removeComma($("#num-profit").val());        
        let ucost = removeComma($("#num-ucost").val());
        let abbr = $("#txt-abbr").val();
        let vatdesc = $("#sel-vatdesc").val();
        let reorder = removeComma($("#num-reorder").val());      
        let disprice = removeComma($("#num-disprice").val());       
        let minqty = removeComma($("#num-minqty").val());       

        let brandText = $("#sel-brandcode option:selected").text().trim();
        let prodname = [
            brandcode ? brandText : '',
            pdesc,
            specs ? specs + (measure ? ' ' + measure : '') : '',
            sellunit ? '(' + sellunit.toUpperCase() + ')' : ''
        ].filter(Boolean).join(' ');

        let product_detail = new FormData();
        product_detail.append("trans_type", trans_type);
        product_detail.append("categorycode", categorycode);
        product_detail.append("brandcode", brandcode);
        product_detail.append("purchaseitem", purchaseitem);
        product_detail.append("isactive", isactive);
        product_detail.append("prodid", prodid);
        product_detail.append("barcode", barcode);
        product_detail.append("pdesc", pdesc);
        product_detail.append("sellunit", sellunit);
        product_detail.append("specs", specs);
        product_detail.append("measure", measure);
        product_detail.append("uprice", uprice);
        product_detail.append("profit", profit);
        product_detail.append("ucost", ucost);
        product_detail.append("abbr", abbr);
        product_detail.append("vatdesc", vatdesc);
        product_detail.append("reorder", reorder);
        product_detail.append("disprice", disprice);
        product_detail.append("minqty", minqty);
        product_detail.append("prodname", prodname);

        $.ajax({
            url:"ajax/products_save_record.ajax.php",
            method: "POST",
            data: product_detail,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"text",
            success:function(answer){
                let productcode = answer;
                if (productcode != 'error' && productcode != 'existing'){
                  swal.fire({
                      title: 'Product details successfully saved!',
                      type: 'success',
                      confirmButtonText: 'Got it',
                      customClass: {
                        confirmButton: 'btn btn-success waves-effect waves-light'
                      },
                      buttonsStyling: false
                  }).then(function (result) {
                      if (result.value) {
                          $('#btn-save').prop('disabled', false);
                          window.location = 'products';
                      }
                  });
                }
            },
            error: function () {
                Swal.fire({
                    title: 'Oops. Something went wrong!',
                    type: 'error',
                    confirmButtonText: 'Got it',
                    customClass: {
                      confirmButton: 'btn btn-danger waves-effect waves-light'
                    },
                    buttonsStyling: false
                });
            }
        });
    }

    $("#btn-print-products").click(function(){
        let categorycode = $("#lst-categorycode").val();
        let brandcode = $("#lst-brandcode").val();
        let vatdesc = $("#lst-vatdesc").val();
        window.open("reports/productlist.php?categorycode="+categorycode+"&brandcode="+brandcode+"&vatdesc="+vatdesc, "_blank");
    });
});