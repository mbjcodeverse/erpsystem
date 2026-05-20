<?php
class ControllerBrand{
	static public function ctrCreateBrand(){
		if(isset($_POST["tns-brandname"])&&($_POST["trans_type"] == 'New')){

		   	$data = array("brandname"=>$_POST["tns-brandname"],
				          "brandcode"=>$_POST["num-brandcode"]);  

		   	$answer = (new ModelBrand)->mdlAddBrand($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Product brand has been successfully saved!",
		                type: "success",
		                showConfirmButton: true,
				        confirmButtonText: "Ok",
				        confirmButtonClass: "btn btn-light btn-lg",
				        allowOutsideClick: false
		                }).then(function(result){
								if (result.value) {
								  $("#btn-new").click();
				 				}
	                });
				</script>';
			}
		}
	}

	static public function ctrEditBrand(){
		if(isset($_POST["tns-brandname"])&&($_POST["trans_type"] == 'Update')){

		   	$data = array("id"=>$_POST["num-id"],
						  "brandname"=>$_POST["tns-brandname"],
				          "brandcode"=>$_POST["num-brandcode"]);
				          
		   	$answer = (new ModelBrand)->mdlEditBrand($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Product brand has been successfully updated!",
		                type: "success",
		                showConfirmButton: true,
				        confirmButtonText: "Ok",
				        confirmButtonClass: "btn btn-light btn-lg",
				        allowOutsideClick: false
		                }).then(function(result){
								if (result.value) {
								  $("#btn-new").click();
				 				}
	                });
				</script>';
			}
		}
	}

	static public function ctrShowBrand($item, $value){
		$answer = (new ModelBrand)->mdlShowBrand($item, $value);
		return $answer;
	}		

	static public function ctrShowBrandList(){
		$answer = (new ModelBrand)->mdlShowBrandList();
		return $answer;
	}
}
