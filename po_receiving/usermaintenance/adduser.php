<?php
include('../connect.php');
session_start();
if(isset($_POST['btnsaveuser'])){
	$EEno = $_POST['EEno'];
	$fname = $_POST['fname'];
	$mname = $_POST['mname'];
	$lname = $_POST['lname'];
	$pass = $_POST['pass'];
	$pass = md5($pass);
	$storecode = $_SESSION['Storecode'];
    Try{	
        $conn->beginTransaction();	
		
		//check user if already exist
		$getduplicate = "SELECT * FROM `user_tbl` WHERE user_EEno='$EEno' and Active != 0"; 
        $rs = $conn->query($getduplicate);
		if($rs->rowCount() > 0){
			?><script>
			alert('EE Number Already Exist');
			window.location.href = '../usermaintenance.php';
			</script><?php
			exit;
		}
        $insert_query = "INSERT INTO `user_tbl`(`user_EEno`, `user_pass`, `user_fname`, `user_mname`, `user_lname`, `store_code`, `Active`) VALUES ('$EEno','$pass','$fname','$mname','$lname','$storecode','1')"; 
        
        $conn->exec($insert_query);
        $conn->commit();
		?><script>
		alert('User Saved!');
		window.location.href = '../usermaintenance.php';
		</script><?php
          
    }catch(Exception $exception){
        $conn->rollBack();      
        //echo $exception->getMessage();
		?><script>
		alert('An Error Occured While Saving User!');
		window.location.href = '../usermaintenance.php';
		</script><?php
    }
}
?>