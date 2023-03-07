<?php
define( 'DB_NAME', 'data/db.txt' );
function seed() {
    $data           = array(
        array(
            'id'    => 1,
            'fname' => 'Kamal',
            'lname' => 'Ahmed',
            'roll'  => '11'
        ),
        array(
            'id'    => 2,
            'fname' => 'Jamal',
            'lname' => 'Ahmed',
            'roll'  => '12'
        ),
        array(
            'id'    => 3,
            'fname' => 'Ripon',
            'lname' => 'Ahmed',
            'roll'  => '9'
        ),
        array(
            'id'    => 4,
            'fname' => 'Nikhil',
            'lname' => 'Chandra',
            'roll'  => '8'
        ),
        array(
            'id'    => 5,
            'fname' => 'John',
            'lname' => 'Rozario',
            'roll'  => '7'
        ),
    );
    $serializedData = serialize( $data );
    file_put_contents( DB_NAME, $serializedData, LOCK_EX );
}

function generateReport() {
    $serialziedData = file_get_contents( DB_NAME );
    $students = unserialize( $serialziedData );
    ?>
<div class="table-responsive">
<table class="table table-hover">
<thead>
<tr>
<th>Name</th>
<th>Roll</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ( $students as $student ): ?>
<tr>
<td><?=$student['fname'] . ' ' . $student['lname']?></td>
<td><?=$student['roll']?></td>
<td>
<a href="index.php?task=edit&id=<?=$student['id']?>" class="btn btn-primary btn-sm">Edit</a>
<a href="index.php?task=delete&id=<?=$student['id']?>" class="btn btn-danger btn-sm delete">Delete</a>
</td>
</tr>
<?php endforeach;?>
</tbody>
</table>
</div>
<?php
}

function addStudent( $fname, $lname, $roll ) {
    $found          = false;
    $serialziedData = file_get_contents( DB_NAME );
    $students       = unserialize( $serialziedData );
    foreach ( $students as $_student ) {
        if ( $_student['roll'] == $roll ) {
            $found = true;
            break;
        }
    }
    if ( ! $found ) {
        $newId   = getNewId($students);
        $student = array(
            'id'    => $newId,
            'fname' => $fname,
            'lname' => $lname,
            'roll'  => $roll
        );
        array_push( $students, $student );
        $serializedData = serialize( $students );
        file_put_contents( DB_NAME, $serializedData, LOCK_EX );

        return true;
    }

    return false;
}

function getStudent( $id ) {
    $serialziedData = file_get_contents( DB_NAME );
    $students       = unserialize( $serialziedData );
    foreach ( $students as $student ) {
        if ( $student['id'] == $id ) {
            return $student;
        }
    }

    return false;
}

function updateStudent( $id, $fname, $lname, $roll ) {
    $found          = false;
    $serialziedData = file_get_contents( DB_NAME );
    $students       = unserialize( $serialziedData );
    foreach ( $students as $_student ) {
        if ( $_student['roll'] == $roll && $_student['id'] != $id ) {
            $found = true;
            break;
        }
    }
    if ( ! $found ) {
        $students[ $id - 1 ]['fname'] = $fname;
        $students[ $id - 1 ]['lname'] = $lname;
        $students[ $id - 1 ]['roll']  = $roll;
        $serializedData               = serialize( $students );
        file_put_contents( DB_NAME, $serializedData, LOCK_EX );

        return true;
    }

    return false;
}

function deleteStudent($id){
	$serialziedData = file_get_contents( DB_NAME );
	$students       = unserialize( $serialziedData );

	foreach ( $students as $offset=>$student ) {
		if ( $student['id'] == $id ) {
			unset($students[$offset]);
		}

	}
	$serializedData               = serialize( $students );
	file_put_contents( DB_NAME, $serializedData, LOCK_EX );
}

function printRaw(){
	$serialziedData = file_get_contents( DB_NAME );
	$students       = unserialize( $serialziedData );
	print_r($students);
}

function getNewId($students){
    $maxId = max(array_column($students,'id'));
    return $maxId+1;
}
?>
