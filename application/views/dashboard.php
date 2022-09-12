 <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                     <img class="menu-icon" src="<?php echo base_url('/assets/images/menu_icons/money.png'); ?>" alt="menu icon" height="50">
                    </div>
                    <div class="float-right">
                      <p class="card-text text-right"><?php echo get_phrase('total_revenue'); ?></p>
                      <div class="fluid-container">
                        <h6 class="card-title font-weight-bold text-right mb-0"> <?php if (isset($total_revenue->total_amount)) {
                         ?><?php echo $currency_type; echo round($total_revenue->total_amount, 2); ?>
                       <?php } else { echo "0";} ?> </h6>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3">
                    <i class="mdi mdi-alert-octagon mr-1" aria-hidden="true"></i> <?php echo get_phrase('work_revenue'); ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <img class="menu-icon" src="<?php echo base_url('/assets/images/menu_icons/products.png'); ?>" alt="menu icon" height="50">
                    </div>
                    <div class="float-right">
                      <p class="card-text text-right"><?php echo get_phrase('products'); ?></p>
                      <div class="fluid-container">
                        <h6 class="card-title font-weight-bold text-right mb-0">5</h6>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3">
                    <i class="mdi mdi-bookmark-outline mr-1" aria-hidden="true"></i> <?php echo get_phrase('products_of_artist'); ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <img class="menu-icon" src="<?php echo base_url('/assets/images/menu_icons/busers.png'); ?>" alt="menu icon" height="50">
                    </div>
                    <div class="float-right">
                      <p class="card-text text-right"><?php echo get_phrase('users'); ?></p>
                      <div class="fluid-container">
                        <h6 class="card-title font-weight-bold text-right mb-0"><?php echo $user; ?></h6>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3">
                    <i class="mdi mdi-calendar mr-1" aria-hidden="true"></i><?php echo get_phrase('total_users'); ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                      <img class="menu-icon" src="<?php echo base_url('/assets/images/menu_icons/sartist.png'); ?>" alt="menu icon" height="50">
                    </div>
                    <div class="float-right">
                      <p class="card-text text-right"><?php echo get_phrase('artist'); ?></p>
                      <div class="fluid-container">
                        <h6 class="card-title font-weight-bold text-right mb-0"><?php echo $artist; ?></h6>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3">
                    <i class="mdi mdi-reload mr-1" aria-hidden="true"></i> <?php echo get_phrase('total_artists'); ?>
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title mb-4"><?php echo get_phrase('monthly_revenue_in'); ?> <?php echo $currency_type; ?></h5>
                  <canvas id="dashoard-area-chart" height="100px"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('users'); ?></h4>
                  <canvas id="barChart" style="height:230px"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('users_Active_Deactivated'); ?></h4>
                  <canvas id="pieChart" style="height:250px"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title mb-4"><?php echo get_phrase('recent_completed_work'); ?></h5>
                  <div class="table-responsive">
                    <table class="table center-aligned-table">
                      <thead>
                        <tr>
                          <th class="border-bottom-0"><?php echo get_phrase('s_no'); ?></th>
                          <th class="border-bottom-0"><?php echo get_phrase('artist_name'); ?></th>
                          <th class="border-bottom-0"><?php echo get_phrase('user_name'); ?></th>
                          <th class="border-bottom-0"><?php echo get_phrase('artist_service'); ?></th>
                          <th class="border-bottom-0"><?php echo get_phrase('date_Time'); ?></th>
                          <th class="border-bottom-0"><?php echo get_phrase('working_mintues'); ?></th>
                          <th class="border-bottom-0"><?php echo get_phrase('amount'); ?></th>
                          <th class="border-bottom-0"><?php echo get_phrase('discount'); ?></th>
                          <th class="border-bottom-0"><?php echo get_phrase('payment_status'); ?></th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $i=0; foreach ($getInvoices as $getInvoices) {
                        $i++;
                        ?>
                        <tr>
                          <td><?php echo $i; ?></td>
                          <td><?php echo ucfirst($getInvoices->ArtistName); ?></td>
                          <td><?php echo ucfirst($getInvoices->userName); ?></td>
                          <td><?php echo ucfirst($getInvoices->categoryName); ?></td>
                          <td><?php $originalDate = $getInvoices->booking_date; $newDate = date("M d, Y", strtotime($originalDate)); echo $newDate; ?> <?php echo $getInvoices->booking_time; ?></td>
                          <td><?php echo $getInvoices->working_min; ?></td>
                          <td><?php echo $currency_type; echo $getInvoices->final_amount; ?></td>
                          <td><?php echo $currency_type; echo $getInvoices->discount_amount; ?></td>
                          <td> <?php if($getInvoices->flag==0)
                           {
                             ?>
                           <label class="badge badge-warning"><?php echo get_phrase('pending'); ?></label>
                           <?php
                            }
                            elseif($getInvoices->flag==1) {
                               ?>
                           <label class="badge badge-teal"><?php echo get_phrase('paid'); ?></label>
                           <?php
                             } 
                             ?>
                          </td>
                        </tr>
                        <?php
                      } ?>                       
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
         <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title mb-4"><?php echo get_phrase('manage_tickets'); ?></h5>
                  <div class="fluid-container">
                     <?php foreach ($tickets as $ticket) { ?>
                    <div class="row ticket-card mt-3 pb-2">
                      <div class="col-1">
                        <img class="img-sm rounded-circle" src="<?php echo  base_url('/assets/images/faces-clipart/pic-1.png' ); ?>" alt="profile image">
                      </div>
                      <div class="ticket-details col-9">
                        <div class="d-flex" style="padding-bottom: 5px;">
                          <p class="text-primary font-weight-bold mr-2 mb-0 no-wrap"><?php echo $ticket->userName ?> :</p>
                          <p class="font-weight-medium mr-1 mb-0"><?php echo '#'.$ticket->id; ?></p>
                          <p class="font-weight-bold mb-0 ellipsis"></p>
                        </div>
                        <p class="text-small text-gray"><?php echo $ticket->reason; ?></p>
                        <div class="row text-muted d-flex">
                          <div class="col-6 d-flex">
                            <p class="mb-0 mr-2"><?php echo get_phrase('ticket_time'); ?></p>
                            <p class="Last-responded mr-2 mb-0"><?php echo date('M d, Y h:i A', $ticket->craeted_at); ?></p>
                          </div>
    
                        </div>
                      </div>
                      <div class="ticket-actions col-2">
                        <p class="badge badge-danger mx-auto mt-3"><?php if($ticket->status==0) { echo "Pending"; } ?></p> <?php if($ticket->status==2) { ?> <p class="badge badge-primary mx-auto mt-3"><?php echo "Close"; } ?></p>
                        <p class="badge badge-success mx-auto mt-3"><?php if($ticket->status==1) { echo "Solve"; } ?></p> 
                      </div>
                    </div>
                    <?php
                      }
                    ?> 
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    $(function() {
    'use strict';
    <?php
    $month='';
    $count='';
    $totaldata=count($monthly_user);
    foreach($monthly_user as  $data ) {
      
       $m= $data['month'];
       $c= $data['count'];
       $month.="'".$m."'".',';
       $count.="'".$c."'".',';   
    }
    $mi= rtrim($month,',');
    $co= rtrim($count,',');
?>
    var data = {
    labels: [<?php echo $mi; ?>],
    datasets: [{
      label: '# of Users',
      data: [<?php echo $co; ?>],
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
      borderWidth: 1
    }]
  };
 
  var options = {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    legend: {
      display: false
    },
    elements: {
      point: {
        radius: 0
      }
    }

  };
  var doughnutPieData = {
    datasets: [{
      data: [<?php echo $deactive_user; ?>, <?php echo $active_user;?>],
      backgroundColor: [
        'rgba(255, 99, 132, 0.5)',
        'rgba(54, 162, 235, 0.5)',
        'rgba(255, 206, 86, 0.5)',
        'rgba(75, 192, 192, 0.5)',
        'rgba(153, 102, 255, 0.5)',
        'rgba(255, 159, 64, 0.5)'
      ],
      borderColor: [
        'rgba(255,99,132,1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
      ],
    }],

    // These labels appear in the legend and in the tooltips when hovering different arcs
    labels: [
      'Deactivated Users',
      'Active Users',
    ]
  };
  var doughnutPieOptions = {
    responsive: true,
    animation: {
      animateScale: true,
      animateRotate: true
    }
  };

  var multiAreaOptions = {
    plugins: {
      filler: {
        propagate: true
      }
    },
    elements: {
      point: {
        radius: 0
      }
    },
    scales: {
      xAxes: [{
        gridLines: {
          display: false
        }
      }],
      yAxes: [{
        gridLines: {
          display: false
        }
      }]
    }
  }

  var scatterChartData = {
    datasets: [{
        label: 'First Dataset',
        data: [{
            x: -10,
            y: 0
          },
          {
            x: 0,
            y: 3
          },
          {
            x: -25,
            y: 5
          },
          {
            x: 40,
            y: 5
          }
        ],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)'
        ],
        borderColor: [
          'rgba(255,99,132,1)'
        ],
        borderWidth: 1
      },
      {
        label: 'Second Dataset',
        data: [{
            x: 10,
            y: 5
          },
          {
            x: 20,
            y: -30
          },
          {
            x: -25,
            y: 15
          },
          {
            x: -10,
            y: 5
          }
        ],
        backgroundColor: [
          'rgba(54, 162, 235, 0.2)',
        ],
        borderColor: [
          'rgba(54, 162, 235, 1)',
        ],
        borderWidth: 1
      }
    ]
  }

  var scatterChartOptions = {
    scales: {
      xAxes: [{
        type: 'linear',
        position: 'bottom'
      }]
    }
  }
  // Get context with jQuery - using jQuery's .get() method.
  if ($("#barChart").length) {
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: data,
      options: options
    });
  }

  if ($("#lineChart").length) {
    var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
    var lineChart = new Chart(lineChartCanvas, {
      type: 'line',
      data: data,
      options: options
    });
  }

  if ($("#linechart-multi").length) {
    var multiLineCanvas = $("#linechart-multi").get(0).getContext("2d");
    var lineChart = new Chart(multiLineCanvas, {
      type: 'line',
      data: multiLineData,
      options: options
    });
  }

  if ($("#areachart-multi").length) {
    var multiAreaCanvas = $("#areachart-multi").get(0).getContext("2d");
    var multiAreaChart = new Chart(multiAreaCanvas, {
      type: 'line',
      data: multiAreaData,
      options: multiAreaOptions
    });
  }

  if ($("#doughnutChart").length) {
    var doughnutChartCanvas = $("#doughnutChart").get(0).getContext("2d");
    var doughnutChart = new Chart(doughnutChartCanvas, {
      type: 'doughnut',
      data: doughnutPieData,
      options: doughnutPieOptions
    });
  }

  if ($("#pieChart").length) {
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: doughnutPieData,
      options: doughnutPieOptions
    });
  }

  if ($("#areaChart").length) {
    var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
    var areaChart = new Chart(areaChartCanvas, {
      type: 'line',
      data: areaData,
      options: areaOptions
    });
  }

  if ($("#scatterChart").length) {
    var scatterChartCanvas = $("#scatterChart").get(0).getContext("2d");
    var scatterChart = new Chart(scatterChartCanvas, {
      type: 'scatter',
      data: scatterChartData,
      options: scatterChartOptions
    });
  }

  if ($("#browserTrafficChart").length) {
    var doughnutChartCanvas = $("#browserTrafficChart").get(0).getContext("2d");
    var doughnutChart = new Chart(doughnutChartCanvas, {
      type: 'doughnut',
      data: browserTrafficData,
      options: doughnutPieOptions
    });
  }
});


 (function($) {
  'use strict';
  $(function() {

        <?php
    $month='';
    $count='';
    $totaldata=count($monthly_revenue);
    foreach($monthly_revenue as  $data ) {
      
       $m= $data['month'];
       $c= round($data['count'], 2);
       $month.="'".$m."'".',';
       $count.="'".$c."'".',';   
    }
    $mi= rtrim($month,',');
    $co= rtrim($count,',');
?>
    if ($('#dashoard-area-chart').length) {
      var lineChartCanvas = $("#dashoard-area-chart").get(0).getContext("2d");      
      var data = {
        labels: [<?php echo $mi; ?>],
        datasets: [{
            label: 'Profit',
            data: [<?php echo $co; ?>],
            backgroundColor: 'rgba(25, 145 ,235, 0.7)',
            borderColor: [
              'rgba(25, 145 ,235, 1)'
            ],
            borderWidth: 3,
            fill: true
          },
        ]
      };
      var options = {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            },
            gridLines: {
              display: true
            }
          }],
          xAxes: [{
            ticks: {
              beginAtZero: true
            },
            gridLines: {
              display: false
            }
          }]
        },
        legend: {
          display: false
        },
        elements: {
          point: {
            radius: 3
          }
        }
      };
      var lineChart = new Chart(lineChartCanvas, {
        type: 'line',
        data: data,
        options: options
      });
    }
  });
})(jQuery);
</script>