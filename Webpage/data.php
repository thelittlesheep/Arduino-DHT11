<?php
	header("Content-Type:text/html; charset=utf-8");

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "thermometer";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if($conn->connect_error){
		die("Connection failed : " . $conn->connect_error);
	}
	
	$sql = "SELECT * FROM `th_data` ORDER BY `id` DESC";
	$query = mysqli_query($conn,$sql);
	$result = mysqli_fetch_assoc($query);

	$h = $result["temperature"];
	$t = $result["humidity"];

	// echo $result["temperature"];
	// echo $result["humidity"];

	echo json_encode($result);

// if ($conn->query($sql) == TRUE){
// 	echo "新增完成";
// } 
// else{
// echo "新增未完成 : " . $conn->connect_error;
// }

$conn->close();
?>