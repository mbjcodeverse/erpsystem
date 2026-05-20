<?php
class ControllerCategory{
	static public function ctrCreateCategory(){
		if(isset($_POST["txt-catdescription"])&&($_POST["trans_type"] == 'New')){

		   	$data = array("catdescription"=>$_POST["txt-catdescription"],
				          "categorycode"=>$_POST["num-categorycode"]);  

		   	$answer = (new ModelCategory)->mdlAddCategory($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Product category has been successfully saved!",
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

	static public function ctrEditCategory(){
		if(isset($_POST["txt-catdescription"])&&($_POST["trans_type"] == 'Update')){

		   	$data = array("id"=>$_POST["num-id"],
						  "catdescription"=>$_POST["txt-catdescription"],
				          "categorycode"=>$_POST["num-categorycode"]);
 
		   	$answer = (new ModelCategory)->mdlEditCategory($data);

		   	if($answer == "ok"){
				echo'<script>
	                swal.fire({
		                title: "Product category has been successfully updated!",
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

	static public function ctrShowCategory($item, $value){
		$answer = (new ModelCategory)->mdlShowCategory($item, $value);
		return $answer;
	}		

	static public function ctrShowCategoryList(){
		$answer = (new ModelCategory)->mdlShowCategoryList();
		return $answer;
	}
}
