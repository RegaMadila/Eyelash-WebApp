<?php 
	session_start();

	//Check If user is already logged in
	if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4']))
	{
        //Page Title
        $pageTitle = 'Dashboard';

        //Includes
        include 'connect.php';
        include 'Includes/functions/functions.php'; 
        include 'Includes/templates/header.php';

?>
	<!-- Begin Page Content -->
	<div class="container-fluid">
		
		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-4">
			<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
			<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
				<i class="fas fa-download fa-sm text-white-50"></i>
				Generate Report
			</a>
		</div>

		<!-- Content Row -->
		<div class="row">

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-primary shadow h-100 py-2">
					<div class="card-body">
				  		<div class="row no-gutters align-items-center">
							<div class="col mr-2">
					  			<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
					  				Total Clients
					  			</div>
					  			<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo countItems("client_id","clients")?></div>
							</div>
							<div class="col-auto">
					  			<i class="bs bs-boy fa-2x text-gray-300"></i>
							</div>
				  		</div>
					</div>
			  	</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-success shadow h-100 py-2">
					<div class="card-body">
				  		<div class="row no-gutters align-items-center">
							<div class="col mr-2">
					  			<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
					  				Total Services
					  			</div>
					  			<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo countItems("service_id","services")?></div>
							</div>
							<div class="col-auto">
					  			<i class="bs bs-scissors-1 fa-2x text-gray-300"></i>
							</div>
				  		</div>
					</div>
			  	</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-info shadow h-100 py-2">
					<div class="card-body">
				  		<div class="row no-gutters align-items-center">
							<div class="col mr-2">
					  			<div class="text-xs font-weight-bold text-info text-uppercase mb-1">
					  				Employees
					  			</div>
					  			<div class="row no-gutters align-items-center">
									<div class="col-auto">
						  				<div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo countItems("employee_id","employees")?></div>
									</div>
					  			</div>
							</div>
							<div class="col-auto">
					  			<i class="bs bs-man fa-2x text-gray-300"></i>
							</div>
				  		</div>
					</div>
			  	</div>
			</div>

			<div class="col-xl-3 col-md-6 mb-4">
				<div class="card border-left-warning shadow h-100 py-2">
					<div class="card-body">
				  		<div class="row no-gutters align-items-center">
							<div class="col mr-2">
					  			<div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
					  				Appointments
					  			</div>
					  			<div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo countItems("appointment_id","appointments")?></div>
							</div>
							<div class="col-auto">
					  			<i class="fas fa-calendar fa-2x text-gray-300"></i>
							</div>
				 		</div>
					</div>
			  	</div>
			</div>
		</div>

		<!-- Appointment Tables -->
        <div class="card shadow mb-4">
            <div class="card-header tab" style="padding: 0px !important;background: #36b9cc!important">
            	<button class="tablinks active" onclick="openTab(event, 'Upcoming')">
            		Upcoming Bookings
            	</button>
                <button class="tablinks" onclick="openTab(event, 'All')">
                	All Bookings
                </button>
                <button class="tablinks" onclick="openTab(event, 'Canceled')">
                	Canceled Bookings
                </button>
            </div>
            <div class="card-body">
            	<div class="table-responsive">
                    <?php
                        $stmt = $con->prepare("SELECT ap.appointment_id as appointment_id, ap.start_time as start_time, ap.end_time_expected as end_time_expected, sc.category_name as service_category, CONCAT(c.first_name, ' ', c.last_name) as customer_name, CONCAT(e.first_name, ' ', e.last_name) as employee_name,sb.service_id as service_id, b.branch_name as branch_name, sb.categorybooked_id as service_category_id FROM appointments as ap LEFT JOIN services_booked as sb ON ap.appointment_id = sb.appointment_id LEFT JOIN service_categories as sc ON sb.categorybooked_id = sc.category_id LEFT JOIN clients as c ON ap.client_id = c.client_id LEFT JOIN employees as e ON ap.employee_id = e.employee_id LEFT JOIN branch as b ON ap.branch_id = b.branch_id where ap.canceled = 0 and start_time >= ? order by start_time;");
                        $stmt->execute(array(date('Y-m-d H:i:s')));
                        $rows = $stmt->fetchAll();
                        $count = $stmt->rowCount();
                    ?>
                	<table class="table table-bordered tabcontent" id="Upcoming" style="display:table" width="100%" cellspacing="0">
                  		<thead>
                                <tr>
                                    <th>
                                        Start Time
                                    </th>
                                    <th>
                                        End Time Expected
                                    </th>
                                    <th>
                                        Service Category
                                    </th>
                                    <th>
                                        Service Name
                                    </th>
                                    <th>
                                        Client
                                    </th>
                                    <th>
                                        Employee
                                    </th>
                                    <th>
                                        Branch
                                    </th>
                                    <th>
                                        Manage
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <!-- New foreach section -->
                            <?php if($count > 0) : ?>
                                <?php foreach($rows as $row): ?>
                                    <?php
                                        $stmt_services = $con->prepare('SELECT service_id, service_name FROM services WHERE services.category_id = ?');
                                        $stmt_services->execute([$row['service_category_id']]);
                                        $services = $stmt_services->fetchAll();
                                    ?>
                                    <tr>
                                        <td><?= $row['start_time'] ?></td>
                                        <td><?= $row['end_time_expected'] ?></td>
                                        <td><?= $row['service_category'] ?></td>
                                        <td style="min-width: 150px;">
                                            <select class="service-options" onchange="onSelectedServiceOption(event)" data-appointment-id="<?= $row['appointment_id'] ?>" style="width: 100%;">
                                                <option disabled <?= $row['service_id'] == 0 ? 'selected' : '' ?> selected value>Default</option>
                                                <?php foreach($services as $service): ?>
                                                    <option value="<?= $service['service_id'] ?>" <?= $row['service_id'] == $service['service_id'] ? 'selected' : '' ?>><?= $service['service_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><?= $row['customer_name'] ?></td>
                                        <td><?= $row['employee_name'] ?></td>
                                        <td><?= $row['branch_name'] ?></td>
                                        <td>
                                            <?php $cancel_data = "cancel_appointment_".$row["appointment_id"];  ?>
                                            <ul class="list-inline m-0">

                                                <!-- CANCEL BUTTON -->
                                    
                                                <li class="list-inline-item" data-toggle="tooltip" title="Cancel Appointment">
                                                    <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $cancel_data; ?>" data-placement="top">
                                                        <i class="fas fa-calendar-times"></i>
                                                    </button>

                                                    <!-- CANCEL MODAL -->
                                                    <div class="modal fade" id="<?php echo $cancel_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $cancel_data; ?>" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Cancel Appointment</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to cancel this appointment?</p>
                                                                    <div class="form-group">
                                                                        <label>Tell Us Why?</label>
                                                                        <textarea class="form-control" id=<?php echo "appointment_cancellation_reason_".$row['appointment_id'] ?>></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                                    <button type="button" data-id = "<?php echo $row['appointment_id']; ?>" class="btn btn-danger cancel_appointment_button">Yes, Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan='7' style='text-align:center;'>
                                        List of your upcoming bookings will be presented here
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <!-- End New foreach section -->

                        </tbody>
                	</table>

                    <?php
                        $stmt = $con->prepare("SELECT ap.appointment_id as appointment_id, ap.start_time as start_time, ap.end_time_expected as end_time_expected, sc.category_name as service_category, CONCAT(c.first_name, ' ', c.last_name) as customer_name, CONCAT(e.first_name, ' ', e.last_name) as employee_name,sb.service_id as service_id, b.branch_name as branch_name, sb.categorybooked_id as service_category_id, ss.service_name as service_name FROM appointments as ap LEFT JOIN services_booked as sb ON ap.appointment_id = sb.appointment_id LEFT JOIN service_categories as sc ON sb.categorybooked_id = sc.category_id LEFT JOIN clients as c ON ap.client_id = c.client_id LEFT JOIN employees as e ON ap.employee_id = e.employee_id LEFT JOIN branch as b ON ap.branch_id = b.branch_id LEFT JOIN services as ss ON sb.service_id = ss.service_id 
                        where start_time >= ? order by start_time;");
                        $stmt->execute(array(date('Y-m-d H:i:s')));
                        $rows = $stmt->fetchAll();
                        $count = $stmt->rowCount();
                    ?>
                	<table class="table table-bordered tabcontent" id="All" width="100%" cellspacing="0">
                  		<thead>
                            <tr>
                            <th>
                                        Start Time
                                    </th>
                                    <th>
                                        End Time Expected
                                    </th>
                                    <th>
                                        Service Category
                                    </th>
                                    <th>
                                        Service Name
                                    </th>
                                    <th>
                                        Client
                                    </th>
                                    <th>
                                        Employee
                                    </th>
                                    <th>
                                        Branch
                                    </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($count > 0) : ?>
                                <?php foreach($rows as $row): ?>
                                    <tr>
                                        <td><?= $row['start_time'] ?></td>
                                        <td><?= $row['end_time_expected'] ?></td>
                                        <td><?= $row['service_category'] ?></td>
                                        <td style="min-width: 150px;">
                                        <?= $row['service_name'] ?>
                                        </td>
                                        <td><?= $row['customer_name'] ?></td>
                                        <td><?= $row['employee_name'] ?></td>
                                        <td><?= $row['branch_name'] ?></td>
                                        <td>
                                            <?php $cancel_data = "cancel_appointment_".$row["appointment_id"];  ?>
                                            <ul class="list-inline m-0">

                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan='7' style='text-align:center;'>
                                        List of your upcoming bookings will be presented here
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                	</table>
                	<table class="table table-bordered tabcontent" id="Canceled" width="100%" cellspacing="0">
                  		<thead>
                            <tr>
                                <th>
                                    Start Time
                                </th>
                                <th>
                                    Client
                                </th>
                                <th>
                                    Cancellation Reason
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                                $stmt = $con->prepare("SELECT * 
                                                FROM appointments a , clients c
                                                where canceled = 1
                                                and a.client_id = c.client_id
                                                ");
                                $stmt->execute(array());
                                $rows = $stmt->fetchAll();
                                $count = $stmt->rowCount();

                                if($count == 0)
                                {

                                    echo "<tr>";
                                        echo "<td colspan='5' style='text-align:center;'>";
                                            echo "List of your canceled bookings will be presented here";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                else
                                {

                                    foreach($rows as $row)
                                    {
                                        echo "<tr>";
                                            echo "<td>";
                                                echo $row['start_time'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $row['last_name'];
                                            echo "</td>";
                                            echo "<td>";       
                                                echo $row['cancellation_reason'];            
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                }

                            ?>

                        </tbody>
                	</table>
              	</div>
            </div>
        </div>
	</div>
<?php
		//Include Footer
		include 'Includes/templates/footer.php';
	}
	else
    {
    	header('Location: login.php');
        exit();
    }

?>