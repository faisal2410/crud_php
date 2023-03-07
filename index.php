<?php
require_once "inc/functions.php";
$info = '';
$task = $_GET['task'] ?? 'report';
$error = $_GET['error'] ?? '0';

if ( 'delete' == $task ) {
    $id = filter_input( INPUT_GET, 'id' );
    if ( $id > 0 ) {
        deleteStudent( $id );
        header( 'location:index.php?task=report' );
    }
}
if ( 'seed' == $task ) {
    seed();
    $info = "Seeding is complete";
}
$fname = '';
$lname = '';
$roll = '';
if ( isset( $_POST['submit'] ) ) {
    $fname = filter_input( INPUT_POST, 'fname', FILTER_SANITIZE_SPECIAL_CHARS );
    $lname = filter_input( INPUT_POST, 'lname', FILTER_SANITIZE_SPECIAL_CHARS );
    $roll = filter_input( INPUT_POST, 'roll', FILTER_SANITIZE_SPECIAL_CHARS );
    $id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS );
    if ( $id ) {
        //update the existing student
        if ( $fname != '' && $lname != '' && $roll != '' ) {
            $result = updateStudent( $id, $fname, $lname, $roll );
            if ( $result ) {
                header( 'location:index.php?task=report' );
            } else {
                $error = 1;
            }
        }
    } else {
        //add a new student
        if ( $fname != '' && $lname != '' && $roll != '' ) {
            $result = addStudent( $fname, $lname, $roll );
            if ( $result ) {
                header( 'location:index.php?task=report' );
            } else {
                $error = 1;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple Crud Project</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2>Simple CRUD Project</h2>
            <p>A sample project to perform CRUD operations using plain files and PHP</p>
            <?php include_once 'inc/templates/nav.php';?>
            <hr/>
            <?php
if ( $info != '' ) {
    echo "<p>{$info}</p>";
}
?>
        </div>
    </div>
    <?php if ( '1' == $error ): ?>
        <div class="row">
            <div class="col-12">
                <blockquote class="blockquote">
                    <p class="mb-0">Duplicate Roll Number</p>
                </blockquote>
            </div>
        </div>
    <?php endif;?>
    <?php if ( 'report' == $task ): ?>
        <div class="row">
            <div class="col-12">
                <?php generateReport();?>
            </div>
        </div>
    <?php endif;?>
    <?php if ( 'add' == $task ): ?>
        <div class="row">
          <div class="col-12">
   <form action="index.php?task=add" method="POST">
    <div class="form-group">
        <label for="fname">First Name</label>
        <input type="text" class="form-control" name="fname" id="fname" placeholder="Enter First Name">
    </div>
    <div class="form-group">
        <label for="lname">First Name</label>
        <input type="text" class="form-control" name="lname" id="lname" placeholder="Enter Last Name">
    </div>
    <div class="form-group">
        <label for="roll">Roll</label>
        <input type="number" class="form-control" name="roll" id="roll" placeholder="Enter Roll">
    </div>

    <button type="submit" class="btn btn-primary" name="submit">Save</button>
</form>
</div>
        </div>
          <?php endif;?>

           <?php
if ( 'edit' == $task ):
    $id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS );
    $student = getStudent( $id );
    if ( $student ):
    ?>
			            <div class="row">
			                <div class="column column-60 column-offset-20">
			                    <form method="POST">
			                        <input type="hidden" value="<?php echo $id ?>" name="id">
		                            <div class="form-group">
		                            <label for="fname">First Name</label>
			                        <input type="text" class="form-control" name="fname" id="fname" value="<?php echo $student['fname']; ?>">
		                            </div>
	                                <div class="form-group">
	                                <label for="lname">Last Name</label>
			                        <input type="text" class="form-control" name="lname" id="lname" value="<?php echo $student['lname']; ?>">
	                                </div>
                                    <div class="form-group">
                                     <label for="roll">Roll</label>
			                        <input type="number" class="form-control" name="roll" id="roll" value="<?php echo $student['roll']; ?>">
                                    </div>
			                      
			                        <button type="submit" class="btn btn-primary" name="submit">Update</button>
			                    </form>
			                </div>
			            </div>
			        <?php
endif;
endif;
?>
</div>
     </body>
</html>