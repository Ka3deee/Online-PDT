<?php
include('../connect.php');
session_start();
if(isset($_POST['btnupdateuser'])){
	$uuser_id = $_POST['uuser_id'];
	$EEno = $_POST['uEEno'];
	$fname = $_POST['ufname'];
	$mname = $_POST['umname'];
	$lname = $_POST['ulname'];
	$pass = $_POST['upass'];
	$pass_string = "";
	if($pass != ""){
		$pass = md5($pass);
		$pass_string = ",`user_pass`='$pass'";
	}
	
	$storecode = $_SESSION['Storecode'];
    Try{	
        $conn->beginTransaction();	
        
        $update_query = "UPDATE `user_tbl` SET `user_EEno`='$EEno'".$pass_string.",`user_fname`='$fname',`user_mname`='$mname',`user_lname`='$lname' WHERE `user_id`='$uuser_id'"; 
        $conn->exec($update_query);
        $conn->commit();
		?><script>
		alert('Update Succesful!');
		window.location.href = '../usermaintenance.php';
		</script><?php
          
    }catch(Exception $exception){
        $conn->rollBack();      
        //echo $exception->getMessage();
		?><script>
		alert('An Error Occured While Updating User!');
		window.location.href = '../usermaintenance.php';
		</script><?php
    }
}
?>