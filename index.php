<?php
include "connection.php";
$childnameErr=$childageErr=$childnumberErr=$parentnameErr=$parentageErr=$parentnumberErr="";
$valide= true;
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sqlp = "DELETE FROM parenttb WHERE id ='$id'"; 
    $sqlc ="DELETE FROM childtb WHERE pid ='$id'"; 

    $resultp = mysqli_query($conn, $sqlp);
    $resultc = mysqli_query($conn, $sqlc);
    if ($resultp&&$resultc) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

if(isset($_GET['update']) && isset($_GET['id'] )){
    if(isset($_POST ['update'])){
         
    $id = $_GET['id'];
    $childname=$_POST['chname'];
    $childage=$_POST['chage'];
    $childnumber=$_POST['chnumber'];
    $parentname=$_POST['prname'];
    $parentage=$_POST['prage'];
    $parentnumber=$_POST['prnumber'];
    $sqlp = "UPDATE parenttb SET pname='$parentname', page='$parentage', pnum='$parentnumber' WHERE id='$id'";
    $sqlc = "UPDATE childtb SET cname='$childname', cage='$childage', cnum='$childnumber' WHERE pid='$id'";
    $resultp = mysqli_query($conn, $sqlp);
    $resultc = mysqli_query($conn, $sqlc);
    if ($resultp&&$resultc) {
        echo "Record updated successfully";
       header("Location: index.php");
        exit(); 
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
    }
}


if(isset($_POST['submit'])){
    $childnameErr=$childageErr=$childnumberErr=$parentnameErr=$parentageErr=$parentnumberErr="";
    $valide= true;

    if(empty($_POST['chname'])){
        $childnameErr = "child name is required";
        $valide = false;
    }
    if(empty($_POST['chage'])){
        $childageErr = "child age is required";
        $valide = false;
    }

    if(empty($_POST['chnumber'])){
        $childnumberErr = "child number is required";
        $valide = false;
    }

    if(empty($_POST['prname'])){
        $parentnameErr = "parent name is required";
        $valide = false;
    }
    if(empty($_POST['prage'])){
        $parentageErr = "parent age is required";
        $valide = false;
    }

    if(empty($_POST['prnumber'])){
        $parentnumberErr = "parent number is required";
        $valide = false;
    }
    if($valide){
    $childage = $_POST['chage']; 
    $childnumber = $_POST['chnumber'];
    $parentname = $_POST['prname'];
    $parentage = $_POST['prage'];
    $parentnumber = $_POST['prnumber'];
    $childname = $_POST['chname'];
    
$sqlp = "INSERT INTO parenttb (pname, page, pnum) VALUES ('$parentname','$parentage','$parentnumber')";
$resultp = mysqli_query($conn, $sqlp);
if ($resultp) {    
    $pid = $conn ->insert_id; 
    $pid;
$sqlc = "INSERT INTO Childtb (pid,cname, cage, cnum) VALUES ('$pid','$childname','$childage','$childnumber')";
$resultc = mysqli_query($conn, $sqlc);
if($resultc){
    echo "data inserted";
}else{
    echo "error";
}
}else{
    echo "error";
}
}
}


$sql = "SELECT * FROM childtb INNER JOIN parenttb ON childtb.pid = parenttb.id";
$result = mysqli_query($conn, $sql);
if ($result) {
?>   <table>
        <tr>
            <th>ID</th>
            <th>Child Name</th>
            <th>Child Age</th>
            <th>Child Number</th>            
            <th>Parent Name</th>
            <th>Parent Age</th>
            <th>Parent Number</th>
            <th>Actions</th>
        </tr>
<?php
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
           <td><?php echo $row['id']; ?></td>
           <td><?php echo $row['cname']; ?> </td>
           <td><?php echo $row['cage']; ?></td>
           <td><?php echo $row['cnum']; ?></td>
           <td><?php echo $row['pname']; ?></td>
           <td><?php echo $row['page']; ?></td>
           <td><?php echo $row['pnum']; ?></td>
           <td><a href="?delete&id=<?php echo $row['id']; ?>"><button type="button">Delete</button></a> | <a href="?update&id=<?php echo $row['id']; ?>"> <button type="button">Update</button></a></td>

        </tr>
        <?php
    }
} else {
    echo "Error: " . mysqli_error($conn);
}  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .container{
            width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f2f2f2;
        }
        label{            
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 22px;
            color: #333;
        }
        input{
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"]{
            width: 50%;
            text-align: center;
            margin-left :25%;
            margin-top: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;         
        }
         input[type="button"]{
            width: 50%;
            text-align: center;
            margin-left :25%;
            margin-top: 10px;
            padding: 10px;
            background-color:rgb(235, 56, 12);
            color: white;
            border: none;         
        }
        
    </style>
    <title>relational db</title>
</head>
<body>
    <?php if(isset($_GET['update']) && isset($_GET['id'])){ 
        $id = $_GET['id'];
        $sqlu = "SELECT * FROM childtb INNER JOIN parenttb ON childtb.pid = parenttb.id WHERE childtb.pid = '$id'";
        $result = mysqli_query($conn, $sqlu);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $childname = $row['cname'];
            $childage = $row['cage'];
            $childnumber = $row['cnum'];
            $parentname = $row['pname'];
            $parentage = $row['page'];
            $parentnumber = $row['pnum'];
        } else {
            echo "Error: " . mysqli_error($conn);
        }  
        ?>
        
    <div class="container">
   <form action="<?php echo $_SERVER['PHP_SELF'] . '?update&id=' . $_GET['id']; ?>" method="post">

        <h1>Child Data:</h1>        
        <label for="chname">Child Name:</label><span style="color:red">* <?php echo $childnameErr;?></span>
        <input type="text" id="chname" name="chname" value="<?php echo $childname;?>" > <br>
        <label for="chage">Child age: </label><span style="color:red">* <?php echo $childageErr;?></span>
        <input type="text" id="chage" name="chage" value="<?php echo $childage; ?>"> <br>
        <label for="chnumber">Child number:</label> <span style="color:red">* <?php echo $childnumberErr;?></span>
        <input type="number" id="chnumber" name="chnumber"  value="<?php echo $childnumber;?>"> <br> 
        <h1>Parent Data:</h1>     
        <label for="prname"> Parent name: </label> <span style="color:red">* <?php echo $parentnameErr;?></span>
        <input type="text" id="prname" name="prname" value="<?php echo $parentname;?>" > <br>
        <label for="prage"> Parent age: </label> <span style="color:red">* <?php echo $parentageErr;?></span>
        <input type="text" id="prage" name="prage" value="<?php echo $parentage;?>" > <br>
        <label for="prnumber"> Parent number: </label> <span style="color:red">* <?php echo $parentnumberErr;?></span>
        <input type="number" id="prnumber" name="prnumber" value="<?php echo $parentnumber;?>" > <br>
        <input type="submit" value="Update" name="update">
        <input type="button" value="cancel" onclick="window.location.href='index.php'">
    </form>
    </div>
    <?php }else{ ?>
         <div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <h1>Child Data:</h1>        
        <label for="chname">Child Name:</label><span style="color:red">* <?php echo $childnameErr;?></span>
        <input type="text" id="chname" name="chname" > <br>
        <label for="chage">Child age: </label><span style="color:red">* <?php echo $childageErr;?></span>
        <input type="text" id="chage" name="chage" > <br>
        <label for="chnumber">Child number:</label> <span style="color:red">* <?php echo $childnumberErr;?></span>
        <input type="number" id="chnumber" name="chnumber" > <br> 
        <h1>Parent Data:</h1>     
        <label for="prname"> Parent name: </label> <span style="color:red">* <?php echo $parentnameErr;?></span>
        <input type="text" id="prname" name="prname" > <br>
        <label for="prage"> Parent age: </label> <span style="color:red">* <?php echo $parentageErr;?></span>
        <input type="text" id="prage" name="prage" > <br>
        <label for="prnumber"> Parent number: </label> <span style="color:red">* <?php echo $parentnumberErr;?></span>
        <input type="number" id="prnumber" name="prnumber" > <br>
        <input type="submit" value="Submit" name="submit">
    </form>
    </div>
    <?php } ?>
</body>
</html>