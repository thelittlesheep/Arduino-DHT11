<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IoT期末專案</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<div class="container" id="output"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<div style="width:600px;">
  <canvas id="TemperatureChart"></canvas>
  <canvas id="HumidityChart"></canvas>
</div>
<script>
	// y 軸的顯示
	var yAxis= [];
	// 資料集合，之後只要更新這個就好了。
	var tempdatas=[];
	var humidatas=[];
	var ctx = document.getElementById('TemperatureChart').getContext('2d');
	var ctx2 = document.getElementById('HumidityChart').getContext('2d');
	var temperatureChart = new Chart(ctx, {
		type: 'line',
		data: {
		labels:yAxis,
		datasets: [{
			label: '溫度資料',
			data: tempdatas,
			backgroundColor: "rgba(0,148,255,0.6)"
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						suggestedMax: 40,
						beginAtZero: true
					}
				}]
			}
		}
	});
	var humidityChart = new Chart(ctx2, {
		type: 'line',
		data: {
		labels:yAxis,
		datasets: [{
			label: '濕度資料',
			data: humidatas,
			backgroundColor: "rgba(0,148,255,0.6)"
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						suggestedMax: 100,
						beginAtZero: true
					}
				}]
			}
		}
	});

	function getData(){
		$.ajax({
			type: 'POST',
			dataType:'json',
			url: 'data.php',
			success: function(data){
				var list = [];
				var time = data.datetime;
				var temp = data.temperature;
				var humi = data.humidity;
				list.push(time);
				list.push(temp);
				list.push(humi);
				appendData(time,temp,humi);
	        }
	     });
	}
	function appendData(TIME,T,H)
	{

		var datetime = TIME;
		var time = datetime.split(" ");
		var temperature = T;
		var humidity = H;
		document.getElementById("datetime").innerHTML = "最新一筆資料新增時間: " + datetime;
		document.getElementById("temp").innerHTML = "最新溫度: " + temperature;
		document.getElementById("humi").innerHTML = "最新濕度: " + humidity;
	    //超過10 個，就把最早進來的刪掉
	    if(yAxis.length>10){
	        yAxis.shift();
	        tempdatas.shift();
	        humidatas.shift();
	    }

	    //推入y 軸新的資料 
	    yAxis.push(time[1]);
	    
	    //推入一筆亂數進資料
	    // datas.push(Math.floor(Math.random() *100) + 1);
	    tempdatas.push(T);
	    humidatas.push(H);
	    //更新線圖
	    temperatureChart.update();
	    humidityChart.update();
	}

	//每秒做一次
	setInterval(getData,3000);
	// setInterval(appendData,1000);
</script>
<p id="datetime"></p>
<p id="temp"></p>
<p id="humi"></p>
</head>