google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Task');
        data.addColumn('number', 'Hours per Day');
        data.addRows([
          ['Work',    11],
          ['Eat',      2],
          ['Commute',  2],
          ['Watch TV', 2],
          ['Sleep',    7]
        ]);

        var options = {
          title: '',
		  
		  colors: ['#6e8da7', '#74b6e2'], /*FOR GRAPH COLORS*/
		  
		  height:'400',
		  
		   backgroundColor: 'transparent', /*FOR BACKGROUND COLOR*/
		  
		  legendTextStyle: {color:'#5c5c5c'}, /*FOR RIGHT TEXT*/
		  
		  tooltip: {textStyle: {color: '#5c5c5c', fontSize:'15'}, showColorCode: true}, /*TOOL TIP OPTIONS*/
		  
		  titleTextStyle: {color: '#5c5c5c', fontName:'arial', fontSize:'15'}, /*FOR TITLE COLOR , FONT AND FONT SIZE*/
		  
		  chartArea:{top:'30',width:"82%",height:"320"}
		  
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie-chart'));
        chart.draw(data, options);
      }
