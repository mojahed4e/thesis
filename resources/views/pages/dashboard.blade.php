@extends('layouts.app', ['activePage' => 'dashboard', 'menuParent' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')
<style>
.highcharts-figure, .highcharts-data-table table {
  min-width: 310px; 
  max-width: 800px;
  margin: 1em auto;
}

#container {
  height: 400px;
}

.highcharts-data-table table {
	font-family: Verdana, sans-serif;
	border-collapse: collapse;
	border: 1px solid #EBEBEB;
	margin: 10px auto;
	text-align: center;
	width: 100%;
	max-width: 500px;
}
.highcharts-data-table caption {
  padding: 1em 0;
  font-size: 1.2em;
  color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
  padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
  padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
  background: #f8f8f8;
}
.highcharts-data-table tr:hover {
  background: #f1f7ff;
}
</style>
<div class="content bodybg">
  <div class="content">
    <div class="container-fluid"> 
		<div class="row view_word">
			<div class="col-md-4 pt-4">
				Overview
			</div>
		</div> 
		<div class="row">
			&nbsp;	
		</div>
      <div class="row">
        <div class="col-md-4">
			<h5 class="card-header card_header1 ">Thesis Overview</h5>
			<div class="card-body cardbg">
				<p class="card-text" id="websiteViewsChart"></p>			
			</div>		  
        </div>
        <div class="col-md-4">          
			<h5 class="card-header card_header2 ">Progress Overview</h5>
			<div class="card-body cardbg">
				<p class="card-text" id="dailySalesChart"></p>
    
			</div>
        </div>
        <div class="col-md-4">          
			<h5 class="card-header card_header3 ">Assigned & Completed</h5>
			<div class="card-body cardbg">
				<p class="card-text" id="completedTasksChart"></p>
    
			</div>		  
        </div>
      </div>           
    </div>
  </div>
</div>
@endsection

@push('js')
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>
	<script src="https://code.highcharts.com/modules/export-data.js"></script>
	<script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <script>
	vGraphData = {!! json_encode($graphdata) !!};
	vSuperDeails = {!! json_encode($supervisor) !!};
	
	Highcharts.chart('websiteViewsChart', {
		chart: {
			type: 'column'
		},
		title: {
			text: '',
			Padding: 10
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [
				'Total', 'Allocated', 'Completed'
			],
			crosshair: true
		},
		yAxis: {
			min: 0,
			allowDecimals: false,
			title: {
				text: 'Total'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px;">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' 
								+'<td style="padding:0"><b>{point.y}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			allowDecimals: false,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.1,
				borderWidth: 0
			}
		},
		series: [{		  
			name: 'Thesis ',
			data: [vGraphData[0].total,vGraphData[0].allocated,vGraphData[0].completed]
		}]
	});
	
	Highcharts.chart('dailySalesChart', {
		chart: {
			type: 'column'
		},
		title: {
			text: '',
			Padding: 10
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [
				'Requested', 'Term I', 'Term II'
			],
			crosshair: true
		},
		yAxis: {
			min: 0,
			allowDecimals: false,
			title: {
				text: 'Total'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' 
								+'<td style="padding:0"><b>{point.y}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			allowDecimals: false,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.1,
				borderWidth: 0
			}
		},
		series: [
			{		  
				name: 'Thesis Progress',
				data: [vGraphData[1].termone,vGraphData[1].termtwo,vGraphData[1].termthree]
			}			
		]
	});	
	Highcharts.chart('completedTasksChart', {
		chart: {
			type: 'column'
		},
		title: {
			text: '',
			Padding: 10
		},
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: vSuperDeails.name,			
			crosshair: true
		},
		yAxis: {
			min: 0,
			allowDecimals: false,
			title: {
				text: 'Total'
			}
		},
		tooltip: {
			headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' 
								+'<td style="padding:0"><b>{point.y}</b></td></tr>',
			footerFormat: '</table>',
			shared: true,
			allowDecimals: false,
			useHTML: true
		},
		plotOptions: {
			column: {
				pointPadding: 0.1,
				borderWidth: 0
			}
		},
		series: [
			{		  
				name: 'Submitted',
				data: vSuperDeails.submited
			},
			{		  
				name: 'Assigned',
				data: vSuperDeails.assigned
			},
			{		  
				name: 'Completed',
				data: vSuperDeails.completed
			}
		]
	});
	$(document).ready(function () {
		$('.highcharts-credits').hide();  
		$('.highcharts-table-caption').hide();
		//$('.highcharts-a11y-proxy-button').hide();  		
    });
  </script>
@endpush