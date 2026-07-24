@extends('admin.layouts.master')

@section('main-section')

<div class="container">
    <div class="page-inner">

        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Programme Dashboard</h3>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">

            <div class="col-md-3">
                <div class="card card-stats card-primary">
                    <div class="card-body">
                        <p class="card-category">Total Programmes</p>
                        <h3 id="total_programmes">0</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-stats card-success">
                    <div class="card-body">
                        <p class="card-category">Participants</p>
                        <h3 id="total_participants">0</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-stats card-warning">
                    <div class="card-body">
                        <p class="card-category">Announced</p>
                        <h3 id="announced">0</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-stats card-danger">
                    <div class="card-body">
                        <p class="card-category">Cancelled</p>
                        <h3 id="cancelled">0</h3>
                    </div>
                </div>
            </div>

        </div>

        <!-- Charts -->
        <div class="row">

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Programme Status</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Programmes by PD</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="pdChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <!-- India Map -->
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Participants by State</h4>
                    </div>
                    <div class="card-body">
                        <div id="indiaMap" style="width:100%;height:600px;"></div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection

@section('script')

<script src="{{ asset('assets/chartjs/chart.umd.min.js') }}"></script>

<!-- amCharts -->
<script src="{{ asset('assets/amcharts/index.js') }}"></script>
<script src="{{ asset('assets/amcharts/map.js') }}"></script>
<script src="{{ asset('assets/amcharts/themes/Animated.js') }}"></script>
<script src="{{ asset('assets/amcharts/geodata/indiaLow.js') }}"></script>

<script>

let dashboardData = {

    total_programmes: 125,
    total_participants: 4560,

    programme_status: {
        Announced: 60,
        NotAnnounce: 30,
        Cancelled: 20,
        PostPond: 15
    },

    pd_wise: [
        {name:'PD-1', programs:25},
        {name:'PD-2', programs:40},
        {name:'PD-3', programs:35},
        {name:'PD-4', programs:25}
    ],

    state_wise: [
        {id:'IN-UP', value:850},
        {id:'IN-MH', value:650},
        {id:'IN-DL', value:450},
        {id:'IN-GJ', value:380},
        {id:'IN-RJ', value:300}
    ]
};

$('#total_programmes').text(dashboardData.total_programmes);
$('#total_participants').text(dashboardData.total_participants);
$('#announced').text(dashboardData.programme_status.Announced);
$('#cancelled').text(dashboardData.programme_status.Cancelled);

/* -------------------------
   Programme Status Chart
--------------------------*/

new Chart(
document.getElementById('statusChart'),
{
    type:'doughnut',
    data:{
        labels:[
            'Announced',
            'Not Announce',
            'Cancelled',
            'Postponed'
        ],
        datasets:[{
            data:[
                dashboardData.programme_status.Announced,
                dashboardData.programme_status.NotAnnounce,
                dashboardData.programme_status.Cancelled,
                dashboardData.programme_status.PostPond
            ]
        }]
    }
}
);

/* -------------------------
   PD Wise Chart
--------------------------*/

new Chart(
document.getElementById('pdChart'),
{
    type:'bar',
    data:{
        labels: dashboardData.pd_wise.map(item=>item.name),
        datasets:[{
            label:'Programmes',
            data: dashboardData.pd_wise.map(item=>item.programs)
        }]
    },
    options:{
        responsive:true
    }
}
);

/* -------------------------
   India Map
--------------------------*/

am5.ready(function() {

var root = am5.Root.new("indiaMap");

root.setThemes([
  am5themes_Animated.new(root)
]);

var chart = root.container.children.push(
  am5map.MapChart.new(root, {
    panX: "none",
    panY: "none",
    projection: am5map.geoMercator()
  })
);

var polygonSeries = chart.series.push(
  am5map.MapPolygonSeries.new(root, {
    geoJSON: am5geodata_indiaLow
  })
);

polygonSeries.mapPolygons.template.setAll({
    tooltipText: "{name}\nParticipants: {value}",
    interactive: true
});

polygonSeries.mapPolygons.template.states.create(
    "hover",
    {
        fill: am5.color(0x6771dc)
    }
);

polygonSeries.data.setAll(
    dashboardData.state_wise
);

});

</script>

@endsection