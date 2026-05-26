$(function() {
    $(".select").select2({
        minimumResultsForSearch: Infinity
    });

    $(".select-search").select2();

    $('#txt-bname').focus();

    // $('#form-branch input[id^="txt"]').on("keypress", function (e) {
    //     return _helper.isString(e) ? true : e.preventDefault();
    // });

    // $('#form-branch input[id^="tns"]').on("keypress", function (e) {
    //     return _helper.allChars(e) ? true : e.preventDefault();
    // });   

   $("#btn-new").click(function(){
     $('#txt-bname').val('');

     $("#txt-prefix").prop('disabled', false);
     $('#txt-prefix').val(''); 

     $("#chk-isactive").prop( "checked", true); 
     $('#txt-branchcode').val(''); 
     $('#tns-baddress').val('');
     $('#tns-bdescription').val('');
     $('#sel-allowedtrans').val('').trigger('change');;
     $('#sel-resetdetail').val('').trigger('change');;
     $('#tns-bcontactnum').val('');
     $("#chk-grantincentive").prop( "checked", true);

     $('#trans_type').val('New');
     $('#num-id').val('');
     $('#txt-bname').focus();
   });  

   $('#txt-prefix').keyup(function(){
      $(this).val($(this).val().toUpperCase());
   });

   $("#txt-prefix").blur(function(){
      if (($("#txt-prefix").val().length != 2)&&($("#txt-prefix").val() != '')){
         swal.fire({
            title: 'Branch PREFIX must be equal to two character!',
            type: 'error',
            confirmButtonText: 'Got it!',
            confirmButtonClass: 'btn btn-outline-success',
            allowOutsideClick: false,
            buttonsStyling: false
         }).then(function(result){
            if(result.value) {              
              $("#txt-prefix").val('');
              $("#txt-prefix").focus();
            }
         })        
      }else if (($("#txt-prefix").val() != '')&&($('#trans_type').val() == 'New')){
         var prefix = $("#txt-prefix").val();
         var prefix_check = new FormData();
         prefix_check.append("prefix", prefix);
         $.ajax({
           url:"ajax/branch_check_prefix_duplicate.ajax.php",
           method: "POST",
           data: prefix_check,
           cache: false,
           contentType: false,
           processData: false,
           dataType:"json",
           success:function(answer){
              if (answer["prefix"] != null){
                 swal.fire({
                    title: 'Change entry, branch PREFIX already exist!',
                    type: 'info',
                    confirmButtonText: 'Got it!',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false
                 }).then(function(result){
                    if(result.value) {              
                      $("#txt-prefix").val('');
                      $("#txt-prefix").focus();
                    }
                 })
              }
           },
           error: function () {
              alert("Oops. Something went wrong!");
           },
           complete: function () {
           }
        })         
      }
   });

   // SAVE NEW BRANCH
   $("#form-branch").submit(function (e) {
      e.preventDefault();

      if ($('#trans_type').val() == 'New'){
          var title = "DO YOU WANT TO OPEN NEW BRANCH?";
          var text = "Products will be populated for this branch.";
      }else{
          var title = "Do you want to update branch details?";
          var text = "";
      }

      swal.fire({
          title: title,
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes, Save it!',
          cancelButtonText: 'Cancel!',
          confirmButtonClass: 'btn btn-outline-success',
          cancelButtonClass: 'btn btn-outline-danger',
          allowOutsideClick: false,
          buttonsStyling: false
      }).then(function(result) {
          if(result.value) {
            var trans_type = $("#trans_type").val();

            var id = $("#num-id").val();
            var prefix = $("#txt-prefix").val();

            if ($('#chk-isactive').prop('checked')){
              var isactive = "1";
            }else{
              var isactive = "0";
            }

            var bname = $("#txt-bname").val();
            var bdescription = $("#tns-bdescription").val();
            var baddress = $("#tns-baddress").val();
            var allowedtrans = $("#sel-allowedtrans").val();
            var resetdetail = $("#sel-resetdetail").val();
            var bcontactnum = $("#tns-bcontactnum").val();

            if ($('#chk-grantincentive').prop('checked')){
              var grantincentive = "1";
            }else{
              var grantincentive = "0";
            }             
                        
            var branch = new FormData();
            branch.append("trans_type", trans_type);

            branch.append("id", id);
            branch.append("prefix", prefix);
            branch.append("isactive", isactive);
            branch.append("bname", bname);
            branch.append("bdescription", bdescription);
            branch.append("baddress", baddress);
            branch.append("bcontactnum", bcontactnum);
            branch.append("allowedtrans", allowedtrans);
            branch.append("resetdetail", resetdetail);
            branch.append("grantincentive", grantincentive);

            // alert(prefix + ' ' + isactive + ' ' + bname + ' ' + bdescription + ' ' + baddress + ' ' + bcontactnum + ' ' + allowedtrans + ' ' + resetdetail + ' ' + grantincentive);

            $.ajax({
               url:"ajax/branch_save_record.ajax.php",
               method: "POST",
               data: branch,
               cache: false,
               contentType: false,
               processData: false,
               dataType:"text",
               success:function(answer){
                alert(answer);
               },
               error: function () {
                  alert("Oops. Something went wrong!");
               },
               complete: function () {
                 swal.fire({
                    title: 'Branch details successfully saved!',
                    type: 'success',
                    confirmButtonText: 'Got it!',
                    confirmButtonClass: 'btn btn-outline-success',
                    allowOutsideClick: false,
                    buttonsStyling: false
                 }).then(function(result){
                    if(result.value) {              
                      window.location = 'branches';
                    }
                 })
               }
            })
          }
      });
   });      

   $('.branchTable tbody').on('dblclick', 'tr', function () {
	    var idBranch = $(this).attr("idBranch");
	    var data = new FormData();
      data.append("idBranch", idBranch);
      $.ajax({
     	   url:"ajax/get_branch_record.ajax.php",
      	 method: "POST",
      	 data: data,
      	 cache: false,
      	 contentType: false,
      	 processData: false,
      	 dataType:"json",
      	 success:function(answer){
      	 	$("#num-id").val(answer["id"]);
      	 	$("#txt-branchcode").val(answer["branchcode"]);

            $("#txt-prefix").prop('disabled', true);
            $("#txt-prefix").val(answer["prefix"]);

      	 	if (answer["isactive"] == '1'){
      	 		$("#chk-isactive").prop( "checked", true);
      	 		$("#chk-isactive").val('1');
      	 	}else{
      	 		$("#chk-isactive").prop( "checked", false);
      	 		$("#chk-isactive").val('0');
      	 	} 

            if (answer["grantincentive"] == '1'){
               $("#chk-grantincentive").prop( "checked", true);
               $("#chk-grantincentive").val('1');
            }else{
               $("#chk-grantincentive").prop( "checked", false);
               $("#chk-grantincentive").val('0');
            }                    
      	 	
      	 	$("#txt-bname").val(answer["bname"]);
      	 	$("#tns-baddress").val(answer["baddress"]);
            $("#tns-bdescription").val(answer["bdescription"]);
            $("#sel-allowedtrans").val(answer["allowedtrans"]).trigger('change');
            $("#sel-resetdetail").val(answer["resetdetail"]).trigger('change');
            $("#tns-bcontactnum").val(answer["bcontactnum"]);

      	 	$("#trans_type").val("Update");
            $("#modal-search-branch").modal('hide');
      	}
      })
   }); 
});
