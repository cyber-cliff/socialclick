function ClickByOs() {
var dtView = [
              ['Os', 'Click',{ role: 'style' }],
            ];	
	  
var data = google.visualization.arrayToDataTable();
var view = new google.visualization.DataView(data);
view.setColumns([0, 1,{calc: "stringify",sourceColumn: 1,type: "string",role: "annotation" },2]);
var option = {
	title:'Platforms',
	titlePosition:'out',
	titleTextStyle:{color:'#333',bold:true,fontSize:24},
	width:"100%", height:200,
	backgroundColor:'#f5f5f5',
	bar: {groupWidth: "90%"},
	legend: { position: "none" },
}
// Create and draw the visualization.
new google.visualization.BarChart(document.getElementById('platforms_chart')).draw(view,option );
}