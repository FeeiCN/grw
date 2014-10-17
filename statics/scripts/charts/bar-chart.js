google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Year');
        data.addColumn('number', 'Sales');
        data.addColumn('number', 'Expenses');
        data.addRows([
          ['2004', 1000, 400],
          ['2005', 1170, 460],
          ['2006', 660, 1120],
          ['2007', 1030, 540]
        ]);

        var options = {
          title: '',
		  
		   colors: ['#6e8da7', '#74b6e2'], /*FOR GRAPH COLORS*/
		   
		   height:'420', 
		   
		   backgroundColor: 'transparent', /*FOR BACKGROUND COLOR*/
		   
		   hAxis: {title: 'Years of charts',  titleTextStyle: {color: '#5c5c5c'}},
		   
		   legendTextStyle: {color:'#5c5c5c'}, /*FOR RIGHT TEXT*/
		  
		   chartArea:{width:"85%",height:"330",top:'30'},
		   
		   tooltip: {textStyle: {color: '#5c5c5c', fontSize:'15'}, showColorCode: true}, /*TOOL TIP OPTIONS*/
		   
		   titleTextStyle: {color: '#5c5c5c', fontName:'arial', fontSize:'15'}, /*FOR TITLE COLOR , FONT AND FONT SIZE*/
		   
		   
		   legendTextStyle: {color:'#5c5c5c'} /*FOR RIGHT TEXT*/
		   

        };

        var chart = new google.visualization.ColumnChart(document.getElementById('bars-chart'));
        chart.draw(data, options);
      }
