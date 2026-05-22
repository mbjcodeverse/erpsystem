$(function() {
    if (!$.fn.DataTable.isDataTable('.employeeListTable')) {
        elst = $('.employeeListTable').DataTable({
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

    $(".datepicker").datepicker();
    $(".datepicker").datepicker("option", "dateFormat", "mm/dd/yy"); 

    $(".select").select2({
        minimumResultsForSearch: Infinity,
    });

    $(".select-search").select2();

    $("#btn-save").click(function () {
        let requiredFields = [
            { id: "#txt-lname", label: "Lastname" },
            { id: "#tns-fname", label: "Firstname" },
            { id: "#sel-position", label: "Designation" }
        ];

        let emptyFields = [];
        requiredFields.forEach(function (field) {
            let value = $(field.id).val();
            if (!value || value.trim() === '') {
                emptyFields.push(field.label);
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
                title: 'Do you want to save new employee details?',
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
                    saveEmployee();  
                }
            });
        }
    });    

    function saveEmployee(){
        $('#btn-save').prop('disabled', true);

        let trans_type = $("#trans_type").val();
        let empid = $("#txt-empid").val();
        let isactive = $('#chk-isactive').is(':checked') ? 1 : 0;
        let lname = $("#txt-lname").val();
        let fname = $("#tns-fname").val();
        let mi = $("#txt-mi").val();

        const emp_bday = $("#date-bday").val();
        const bday = emp_bday
            ? (() => {
                const [month, day, year] = emp_bday.split("/");
                return `${year}-${month.padStart(2, "0")}-${day.padStart(2, "0")}`;
            })()
            : '';

        let gender = $("#sel-gender").val();
        let address = $("#tns-address").val();
        let mobile = $("#num-mobile").val();
        let positioncode = $("#sel-position").val();
        let sssno = $("#num-sssno").val();
        let phino = $("#num-phino").val();
        let pagibig = $("#num-pagibig").val();
        let tin = $("#num-tin").val();
        let estatus = $("#sel-estatus").val();

        let employee_detail = new FormData();
        employee_detail.append("trans_type", trans_type);
        employee_detail.append("empid", empid);
        employee_detail.append("isactive", isactive);
        employee_detail.append("lname", lname);
        employee_detail.append("fname", fname);
        employee_detail.append("mi", mi);
        employee_detail.append("bday", bday);
        employee_detail.append("gender", gender);
        employee_detail.append("address", address);
        employee_detail.append("mobile", mobile);
        employee_detail.append("positioncode", positioncode);
        employee_detail.append("sssno", sssno);
        employee_detail.append("phino", phino);
        employee_detail.append("pagibig", pagibig);
        employee_detail.append("tin", tin);
        employee_detail.append("estatus", estatus);

        $.ajax({
            url:"ajax/employee_save_record.ajax.php",
            method: "POST",
            data: employee_detail,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"text",
            success:function(answer){
                let empid = answer;
                if (empid != 'error' && empid != 'existing'){
                  swal.fire({
                      title: 'Employee details successfully saved!',
                      type: 'success',
                      confirmButtonText: 'Got it',
                      customClass: {
                        confirmButton: 'btn btn-success waves-effect waves-light'
                      },
                      buttonsStyling: false
                  }).then(function (result) {
                      if (result.value) {
                          $('#btn-save').prop('disabled', false);
                          window.location = 'employees';
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

    $(".employeeListTable tbody").on("click", "button.btnEmployee", function(){
        $("#modal-search-employee").modal("hide");
        $("#trans_type").val("Update");
        let empid = $(this).attr("empid");
        let data = new FormData();
        data.append("empid", empid);
        $.ajax({
            url:"ajax/employee_get_record.ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(answer){
                $("#num-id").val(answer["id"]);
                $("#txt-empid").val(answer["empid"]);

                if (answer["isactive"] == '1'){
                    $("#chk-isactive").prop( "checked", true);
                    $("#chk-isactive").val('1');
                }else{
                    $("#chk-isactive").prop( "checked", false);
                    $("#chk-isactive").val('0');
                }
                
                $("#txt-lname").val(answer["lname"]);
                $("#tns-fname").val(answer["fname"]);
                $("#txt-mi").val(answer["mi"]);

                let bday = '';
                if (answer["bday"] != null && answer["bday"] !== '') {
                    let parts = answer["bday"].split("-");
                    if (parts.length === 3) {
                        bday = parts[1] + "/" + parts[2] + "/" + parts[0];
                    }
                }
                $("#date-bday").val(bday);

                $("#sel-gender").val(answer["gender"]).trigger('change');
                $("#tns-address").val(answer["address"]);
                $("#num-mobile").val(answer["mobile"]);
                $("#sel-position").val(answer["positioncode"]).trigger('change');
                $("#num-sssno").val(answer["sssno"]);
                $("#num-phino").val(answer["phino"]);
                $("#num-pagibig").val(answer["pagibig"]);
                $("#num-tin").val(answer["tin"]);
                $("#sel-estatus").val(answer["estatus"]).trigger('change');
            }
        })
    });

    $('#modal-search-employee').on('shown.bs.modal', function () {
        $.ajax({
            url: "ajax/employee_list.ajax.php",
            method: "POST",
            cache: false,
            dataType: "json",
            beforeSend: function () {
                elst.clear().draw();
            },

            success: function (answer) {
                elst.clear();
                for (var i = 0; i < answer.length; i++) {
                let ei = answer[i];
                let empid = ei.empid;
                let lname = ei.lname;
                let fname = ei.fname;
                let mi = ei.mi;
                let positiondesc = ei.positiondesc;
                let mobile = ei.mobile;
                let estatus = ei.estatus;

                let button =
                        "<button type='button' " +
                        "class='btn btn-outline btn-sm bg-green-400 border-green-400 text-green-400 btn-icon rounded-round border-2 ml-2 btnEmployee' " +
                        "empid='" + empid + "'>" +
                        "<i class='icon-pencil3'></i>" +
                        "</button>";

                elst.row.add([
                        empid,
                        lname,
                        fname,
                        mi,
                        positiondesc,
                        mobile,
                        estatus,
                        button
                ]);

                }
                elst.draw(false);
            }
        });   
    });
    
    var employees_access = $("#employees-access").val();
    if (employees_access == 'ViewOnly'){
        $('select').prop('disabled', true);
        $('input[type="checkbox"]').prop('disabled', true);

        $('input[type="text"]').prop('readonly', true);
        $('textarea').prop('readonly', true);

        $('button').prop('disabled', true);

        $('#btn-search').prop('disabled', false);
    }
});
