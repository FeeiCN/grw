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
		
          title: '', /*HEADING OF GRAPH*/
		  
          hAxis: {title: 'Years of charts',  titleTextStyle: {color: '#5c5c5c'}},
		  
		  legendTextStyle: {color:'#5c5c5c'}, /*FOR RIGHT TEXT*/
		  
		  colors: ['#6e8da7', '#74b6e2'], /*FOR GRAPH COLORS*/
		  
		  titleTextStyle: {color: '#5c5c5c', fontName:'arial', fontSize:'15'}, /*FOR TITLE COLOR , FONT AND FONT SIZE*/
          
		  titlePosition: 'out', /*YOU CAN USE "IN" , "OUT" , "RIGHT" , "BOTTOM" */
		  
		  backgroundColor: 'transparent', /*FOR BACKGROUND COLOR*/
		  
		  lineWidth: '5', /*WIDTH OF LINE*/
		  
		  pointSize: '10', /*SIZE OF POINT*/
		  
		  height:'420',
		  
		  chartArea:{width:"85%",height:"330",top:'30'},
		  
		  tooltip: {textStyle: {color: '#5c5c5c', fontSize:'15'}, showColorCode: true} /*TOOL TIP OPTIONS*/
		  
		  
		   /*is3D: true*/ /*FOR 3D Effect*/
		  
        };

        var chart = new google.visualization.AreaChart(document.getElementById('area-chart'));
		
		
        chart.draw(data, options);
		
      }
 
	  