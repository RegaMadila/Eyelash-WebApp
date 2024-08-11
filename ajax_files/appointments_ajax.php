<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>


<?php
	

	if(isset($_POST['do']) && $_POST['do'] == "Cancel Appointment")
	{
		$appointment_id = $_POST['appointment_id'];
		$cancellation_reason =  test_input($_POST['cancellation_reason']);

        $stmt = $con->prepare("UPDATE appointments set canceled = 1, cancellation_reason = ? where appointment_id = ?");
        $stmt->execute(array($cancellation_reason, $appointment_id));    
	}

	if(isset($_POST['do']) && $_POST['do'] == "Update Service Name")
	{
		$appointment_id = $_POST['appointment_id'];
		$service_id = $_POST['service_id'];

        $stmt = $con->prepare("UPDATE services_booked set service_id = ? where appointment_id = ?");
        $stmt->execute([$service_id, $appointment_id]);
	}
	
?>