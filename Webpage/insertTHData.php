<?php
header("Content-Type:text/html; charset=utf-8");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "thermometer";

$temperatureValue = $_GET["temperature"];
$humidityValue = $_GET["humidity"];

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
	die("Connection failed : " . $conn->connect_error);
}
$sql = "INSERT INTO th_data(temperature, humidity) VALUES ($temperatureValue, $humidityValue)";

echo "<br>";

if ($conn->query($sql) == TRUE){
	echo "新增完成";
} 
else{
echo "新增未完成 : " . $conn->connect_error;
}

$conn->close();
?>