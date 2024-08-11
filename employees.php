<?php
    ob_start();
    session_start();

    //Page Title
    $pageTitle = 'Employees';

    //Includes
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    //Extra JS FILES
    echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";

    $stmt_service_categories = $con->prepare('SELECT * FROM service_categories');
    $stmt_service_categories->execute();
    $service_categories = $stmt_service_categories->fetchAll();

    $stmt_branches = $con->prepare('SELECT * FROM branch');
    $stmt_branches->execute();
    $branches = $stmt_branches->fetchAll();

    //Check If user is already logged in
    if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4']))
    {
?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
    
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Employees</h1>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-download fa-sm text-white-50"></i>
                    Generate Report
                </a>
            </div>

            <?php
                $do = '';

                if(isset($_GET['do']) && in_array($_GET['do'], array('Add','Edit')))
                {
                    $do = htmlspecialchars($_GET['do']);
                }
                else
                {
                    $do = 'Manage';
                }

                if($do == 'Manage')
                {
                    $stmt = $con->prepare("SELECT employees.*, branch.branch_name , GROUP_CONCAT(service_categories.category_name SEPARATOR ', ') as category_name FROM employees LEFT JOIN branch ON employees.branch_id = branch.branch_id LEFT JOIN employees_specialist ON employees.employee_id = employees_specialist.employee_id LEFT JOIN service_categories ON service_categories.category_id = employees_specialist.category_id GROUP BY employees.employee_id");
                    $stmt->execute();
                    $rows_employees = $stmt->fetchAll();

                    ?>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Employees</h6>
                            </div>
                            <div class="card-body">
                                
                                <!-- ADD NEW Employee BUTTON -->
                                <a href="employees.php?do=Add" class="btn btn-success btn-sm" style="margin-bottom: 10px;">
                                    <i class="fa fa-plus"></i> 
                                    Add Employee
                                </a>

                                <!-- Employees Table -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>    
                                                <th scope="col">Employee Name</th>
                                                <th scope="col">Workplace</th>
                                                <th scope="col">Specialist</th>
                                                <!-- <th scope="col">Phone Number</th> -->
                                                <!-- <th scope="col">E-mail</th> -->
                                                <th scope="col">Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($rows_employees as $employee)
                                                {
                                                    echo "<tr>";
                                                        echo "<td>";
                                                            echo $employee['first_name'] . ' ' . $employee['last_name'];
                                                        echo "</td>";
                                                        echo "<td>";
                                                            echo $employee['branch_name'];
                                                        echo "</td>";
                                                        echo "<td>";
                                                            echo $employee['category_name'];
                                                        echo "</td>";
                                                        // echo "<td>";
                                                        //     echo $employee['phone_number'];
                                                        // echo "</td>";
                                                        // echo "<td>";
                                                        //     echo $employee['email'];
                                                        // echo "</td>";
                                                        echo "<td>";
                                                            $delete_data = "delete_employee_".$employee["employee_id"];
                                                    ?>
                                                        <ul class="list-inline m-0">

                                                            <!-- EDIT BUTTON -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Edit">
                                                                <button class="btn btn-success btn-sm rounded-0">
                                                                    <a href="employees.php?do=Edit&employee_id=<?php echo $employee['employee_id']; ?>" style="color: white;">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                </button>
                                                            </li>

                                                            <!-- DELETE BUTTON -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Delete">
                                                                <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" data-placement="top"><i class="fa fa-trash"></i></button>

                                                                <!-- Delete Modal -->

                                                                <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Employee</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Are you sure you want to delete this employee?
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                                <button type="button" data-id = "<?php echo $employee['employee_id']; ?>" class="btn btn-danger delete_employee_bttn">Delete</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    <?php
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php
                }
                elseif($do == 'Add')
                {
                    ?>
                    
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Add New Employee</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="employees.php?do=Add">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="employee_fname">First Name</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['employee_fname']))?htmlspecialchars($_POST['employee_fname']):'' ?>" placeholder="First Name" name="employee_fname">
                                            <?php
                                                $flag_add_employee_form = 0;
                                                if(isset($_POST['add_new_employee']))
                                                {
                                                    if(empty(test_input($_POST['employee_fname'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                First name is required.
                                                            </div>
                                                        <?php

                                                        $flag_add_employee_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="employee_lname">Last Name</label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['employee_lname']))?htmlspecialchars($_POST['employee_lname']):'' ?>" placeholder="Last Name" name="employee_lname">
                                            <?php
                                                if(isset($_POST['add_new_employee']))
                                                {
                                                    if(empty(test_input($_POST['employee_lname'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Last name is required.
                                                            </div>
                                                        <?php

                                                        $flag_add_employee_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="employee_phone">Specialist</label>
                                            <?php foreach($service_categories as $service_category): ?>
                                                <div class="form-check">
                                                    <input id="employee-categ-<?= $service_category['category_id'] ?>" name="employee_category[]" class="form-check-input" type="checkbox" value="<?= $service_category['category_id'] ?>" <?= isset($_POST['employee_category']) ? in_array($service_category['category_id'], $_POST['employee_category']) ? 'checked' : '' : '' ?>>
                                                    <label style="margin-left: 20px; margin-top: 4px;" class="form-check-label" for="employee-categ-<?= $service_category['category_id'] ?>">
                                                        <?= $service_category['category_name'] ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                            <?php
                                                if(isset($_POST['add_new_employee']) && !isset($_POST['employee_category']))
                                                { ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Employee Specialist is required.
                                                            </div>
                                                        <?php
                                                    $flag_add_employee_form = 1;
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group"> 
                                            <label for="employee_email">Workplace</label>
                                            <select class="form-control" name="employee_branch">
                                                <?php foreach($branches as $branch): ?>
                                                <option value="<?= $branch['branch_id'] ?>" <?= isset($_POST['employee_branch']) ? $branch['branch_id'] == $_POST['employee_branch'] ? 'selected' : '' : '' ?>><?= $branch['branch_name']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php
                                                if(isset($_POST['add_new_employee']))
                                                {
                                                    if(empty(test_input($_POST['employee_branch'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Workplace is required.
                                                            </div>
                                                        <?php

                                                        $flag_add_employee_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- SUBMIT BUTTON -->

                                <button type="submit" name="add_new_employee" class="btn btn-primary">Add employee</button>

                            </form>

                            <?php

                                /*** ADD NEW EMPLOYEE ***/

                                if(isset($_POST['add_new_employee']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_add_employee_form == 0)
                                {
                                    $employee_fname = test_input($_POST['employee_fname']);
                                    $employee_lname = $_POST['employee_lname'];
                                    $employee_categories = $_POST['employee_category'];
                                    $employee_branch = test_input($_POST['employee_branch']);

                                    try
                                    {
                                        $con->beginTransaction();
                                        
                                        $stmt = $con->prepare("insert into employees(first_name,last_name,branch_id) values(?,?,?) ");
                                        $stmt->execute(array($employee_fname,$employee_lname,$employee_branch));
                                        $employee_id = $con->lastInsertId();

                                        foreach($employee_categories as $employee_category){
                                            $stmt_employee_specialist = $con->prepare('INSERT INTO employees_specialist(employee_id, category_id) VALUES(?,?)');
                                            $stmt_employee_specialist->execute([$employee_id, $employee_category]);
                                        }

                                        $con->commit();
                                        ?> 
                                            <!-- SUCCESS MESSAGE -->

                                            <script type="text/javascript">
                                                swal("New Employee","The new employee has been inserted successfully", "success").then((value) => 
                                                {
                                                    window.location.replace("employees.php");
                                                });
                                            </script>

                                        <?php

                                    }
                                    catch(Exception $e)
                                    {
                                        $con->rollBack();
                                        echo "<div class = 'alert alert-danger' style='margin:10px 0px;'>";
                                            echo 'Error occurred: ' .$e->getMessage();
                                        echo "</div>";
                                    }
                                    
                                }
                            ?>
                        </div>
                    </div>
                    <?php   
                }
                elseif($do == 'Edit')
                {
                    $employee_id = (isset($_GET['employee_id']) && is_numeric($_GET['employee_id']))?intval($_GET['employee_id']):0;

                    if($employee_id)
                    {
                        $stmt = $con->prepare("SELECT * FROM employees WHERE employee_id = ?");
                        $stmt->execute(array($employee_id));
                        $employee = $stmt->fetch();
                        $count = $stmt->rowCount();

                        $stmt_employee_specialist = $con->prepare('SELECT * FROM employees_specialist WHERE employee_id = ?');
                        $stmt_employee_specialist->execute([$employee_id]);
                        $selected_employee_specialist = array_map(fn($arr)=> $arr['category_id'], $stmt_employee_specialist->fetchAll());

                        if($count > 0)
                        {
                            ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Edit Employee</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="employees.php?do=Edit&employee_id=<?php echo $employee_id; ?>">
                                        <!-- Employee ID -->
                                        <input type="hidden" name="employee_id" value="<?php echo $employee['employee_id'];?>">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="employee_fname">First Name</label>
                                                    <input type="text" class="form-control" value="<?php echo $employee['first_name'] ?>" placeholder="First Name" name="employee_fname">
                                                    <?php
                                                        $flag_edit_employee_form = 0;
                                                        if(isset($_POST['edit_employee_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['employee_fname'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        First name is required.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_employee_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="employee_lname">Last Name</label>
                                                    <input type="text" class="form-control" value="<?php echo $employee['last_name'] ?>" placeholder="Last Name" name="employee_lname">
                                                    <?php
                                                        if(isset($_POST['edit_employee_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['employee_lname'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        Last name is required.
                                                                    </div>
                                                                <?php

                                                                $flag_edit_employee_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="employee_phone">Specialist</label>
                                                    <?php foreach($service_categories as $service_category): ?>
                                                        <div class="form-check">
                                                            <input id="employee-categ-<?= $service_category['category_id'] ?>" name="employee_category[]" class="form-check-input" type="checkbox" value="<?= $service_category['category_id'] ?>" <?= isset($selected_employee_specialist) ? in_array($service_category['category_id'], $selected_employee_specialist) ? 'checked' : '' : '' ?>>
                                                            <label style="margin-left: 20px; margin-top: 4px;" class="form-check-label" for="employee-categ-<?= $service_category['category_id'] ?>">
                                                                <?= $service_category['category_name'] ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                    <?php
                                                        if(isset($_POST['edit_employee_sbmt']))
                                                        { 
                                                            if(!isset($_POST['employee_category'])) { ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        Employee Specialist is required.
                                                                    </div>
                                                                <?php
                                                                $flag_add_employee_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"> 
                                                    <label for="employee_email">Workplace</label>
                                                    <select class="form-control" name="employee_branch">
                                                        <?php foreach($branches as $branch): ?>
                                                        <option value="<?= $branch['branch_id'] ?>" <?= isset($_POST['employee_branch']) ? $branch['branch_id'] == $_POST['employee_branch'] ? 'selected' : '' : '' ?>><?= $branch['branch_name']?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php
                                                        if(isset($_POST['edit_employee_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['employee_branch'])))
                                                            {
                                                                ?>
                                                                    <div class="invalid-feedback" style="display: block;">
                                                                        Workplace is required.
                                                                    </div>
                                                                <?php

                                                                $flag_add_employee_form = 1;
                                                            }
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SUBMIT BUTTON -->
                                        <button type="submit" name="edit_employee_sbmt" class="btn btn-primary">
                                            Edit employee
                                        </button>
                                    </form>
                                    <?php
                                        /*** EDIT EMPLOYEE ***/
                                        if(isset($_POST['edit_employee_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_edit_employee_form == 0 && isset($_POST['employee_category']))
                                        {
                                            $employee_fname = test_input($_POST['employee_fname']);
                                            $employee_lname = $_POST['employee_lname'];
                                            $employee_branch = test_input($_POST['employee_branch']);
                                            $employee_categories = $_POST['employee_category'];
                                            // $employee_phone = test_input($_POST['employee_phone']);
                                            // $employee_email = test_input($_POST['employee_email']);
                                            $employee_id = $_POST['employee_id'];

                                            try
                                            {
                                                $con->beginTransaction();
                                                $stmt = $con->prepare("update employees set first_name = ?, last_name = ?, branch_id = ? where employee_id = ? ");
                                                $stmt->execute(array($employee_fname,$employee_lname,$employee_branch,$employee_id));

                                                $stmt_reset_specialist = $con->prepare('DELETE FROM employees_specialist WHERE employee_id = ?');
                                                $stmt_reset_specialist->execute([$employee_id]);
                                                
                                                foreach($employee_categories as $employee_category){
                                                    $stmt_update_specialist = $con->prepare('INSERT INTO employees_specialist(employee_id, category_id) values(?, ?)');
                                                    $stmt_update_specialist->execute([$employee_id, $employee_category]);
                                                }
                                                $con->commit();
                                                ?> 
                                                    <!-- SUCCESS MESSAGE -->

                                                    <script type="text/javascript">
                                                        swal("Employee Updated","The employee has been updated successfully", "success").then((value) => 
                                                        {
                                                            window.location.replace("employees.php");
                                                        });
                                                    </script>

                                                <?php

                                            }
                                            catch(Exception $e)
                                            {
                                                $con->rollBack();
                                                echo "<div class = 'alert alert-danger' style='margin:10px 0px;'>";
                                                    echo 'Error occurred: ' .$e->getMessage();
                                                echo "</div>";
                                            }
                                            
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        else
                        {
                            header('Location: employees.php');
                            exit();
                        }
                    }
                    else
                    {
                        header('Location: employees.php');
                        exit();
                    }
                }
            ?>
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