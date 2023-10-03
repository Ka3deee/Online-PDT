<?php
include('../connect.php');
session_start();
if(isset($_POST['btnremoveuser'])){
	$duser_id = $_POST['duser_id'];
	$storecode = $_SESSION['Storecode'];
    Try{	
        $conn->beginTransaction();	
        
        $update_query = "UPDATE `user_tbl` SET `Active`= '0' WHERE `user_id`='$duser_id' and `store_code`='$storecode'"; 
        
        $conn->exec($update_query);
        $conn->commit();
		//echo $update_query;
		?><script>
		alert('User Removed!');
		window.location.href = '../usermaintenance.php';
		</script><?php
          
    }catch(Exception $exception){
        $conn->rollBack();      
        //echo $exception->getMessage();
		?><script>
		alert('An Error Occured While Removing User!');
		window.location.href = '../usermaintenance.php';
		</script><?php
    }
}
?>