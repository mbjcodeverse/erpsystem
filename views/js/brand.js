$(function() {
   $('#tns-brandname').focus();

   $('#form-brand input[id^="tns"]').on("keypress", function (e) {
      return _helper.allChars(e) ? true : e.preventDefault();
   });  

   $("#btn-new").click(function(){
     $('#tns-brandname').val(''); 
     $('#num-brandcode').val('');

     $('#trans_type').val('New');
     $('#num-id').val('');
     $('#tns-brandname').focus();
   });

   $('.brandTable tbody').on('dblclick', 'tr', function () {
	  var idBrand = $(this).attr("idBrand");
	  var data = new FormData();
      data.append("idBrand", idBrand);
      $.ajax({
     	 url:"ajax/brand_get_record.ajax.php",
      	 method: "POST",
      	 data: data,
      	 cache: false,
      	 contentType: false,
      	 processData: false,
      	 dataType:"json",
      	 success:function(answer){
      	 	$("#num-id").val(answer["id"]);
      	 	$("#tns-brandname").val(answer["brandname"]);
      	 	$("#num-brandcode").val(answer["brandcode"]);

      	 	$("#trans_type").val("Update");
          $("#modal-search-brand").modal('hide');
      	}
      })
   }); 
});
