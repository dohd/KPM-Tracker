@extends('layouts.core')
@section('title', 'Dashboard')

@section('content')
<main>
  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ route('home') }}">Home</a></li>
      </ol>
    </nav>
  </div>
  <!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <!-- Welcome Card -->
          {{-- <div class="col-md-12 col-12">
            <div class="card info-card sales-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown" ><i class="bi bi-three-dots"></i></a>
              </div>
              <div class="card-body">
                <div class="m-5 text-center">
                  <h1>Welcome {{ auth()->user()->name }}</h1>
                  <h1 style="color: #4154f1">~ Key Performance Metric Dashboard ~</h1>
                </div>
              </div>
            </div>
          </div> --}}
          <!-- End Welcome Card -->

          <!-- Programmes Card -->
          <div class="col-md-4 col-12">
            <div class="card info-card sales-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown" ><i class="bi bi-three-dots"></i></a>
              </div>
              <div class="card-body">
                <h5 class="card-title">Programs <span></span></h5>
                <div class="d-flex align-items-center" style="height:50px">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <a href="{{ route('programmes.index') }}" style="color:inherit"><i class="bi bi-tag"></i></a>
                  </div>
                  <div class="ps-3">
                    <h6>{{ $numProgrammes }}</h6>
                    <span class="text-muted small pt-2 ps-1">Programs</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Programmes Card -->

          <!-- Teams Card -->
          <div class="col-md-4 col-12">
            <div class="card info-card sales-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown" ><i class="bi bi-three-dots"></i></a>
              </div>
              <div class="card-body">
                <h5 class="card-title">Teams <span></span></h5>
                <div class="d-flex align-items-center" style="height:50px">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <a href="{{ route('teams.index') }}" style="color:inherit"><i class="bi bi-people"></i></a>
                  </div>
                  <div class="ps-3">
                    <h6>{{ $numTeams }}</h6>
                    <span class="text-muted small pt-2 ps-1">Teams</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Teams Card -->

          <!-- Finance Contribution Card -->
          <div class="col-md-4 col-12">
            <div class="card info-card sales-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown" ><i class="bi bi-three-dots"></i></a>
              </div>
              <div class="card-body">
                <h5 class="card-title">Contributions<span></span></h5>
                <div class="d-flex align-items-center" style="height:50px">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <a href="{{ route('teams.index') }}" style="color:inherit"><i class="bi bi-people"></i></a>
                  </div>
                  <div class="ps-3">
                    <h6>{{ numberFormat($sumContributions) }}</h6>
                    <span class="text-muted small pt-2 ps-1">Contributions</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Finance Contribution Card -->
        </div>

        <div class="row">
          <!-- Total Scores Per Team -->
          <div class="col-md-6 col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Scores per Team<span></span></h5>
                <!-- Column Chart -->
                <div id="totalScoresPerTeam"></div>
                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    const rankedTeams = @json($rankedTeams);

                    const categories = rankedTeams.map(v => v.name);
                    const seriesData = rankedTeams.map(v => ({x: v.name, y: v.programme_score_total}));
  
                    const options = {
                      series: [
                        {
                          data: seriesData,
                        }
                      ],
                      chart: {
                        type: 'bar',
                        height: 500,
                      },
                      plotOptions: {
                        bar: {
                          horizontal: false,
                          columnWidth: '70%',
                          endingShape: 'rounded',
                          distributed: true, // varying colour columns
                        },
                      },
                      dataLabels: {
                        enabled: false
                      },
                      stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                      },
                      xaxis: {
                        categories: categories,
                      },
                      yaxis: {
                        title: {
                          text: 'Score',
                        }
                      },
                      fill: {
                        opacity: 1
                      },
                      tooltip: {
                        y: {
                          formatter: function(val) {
                            return val + " Points"
                          }
                        }
                      }
                    };
                    new ApexCharts(document.querySelector("#totalScoresPerTeam"), options).render();
                  });
                </script>
                <!-- End Column Chart -->
              </div>
            </div>
          </div>
          <!-- End Scores Per Team -->

          <!-- Scores Per Program-->
          <div class="col-md-6 col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Scores per Program<span></span></h5>
                <!-- Column Chart -->
                <div id="scoresPerProgramme"></div>
                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    const rankedTeams = @json($rankedTeams);

                    let programmes = {};
                    rankedTeams.forEach(({programme_scores}) => {
                      programme_scores.forEach(scoreItem => {
                        const key = scoreItem.programme_id;
                        const value = +scoreItem.total;
                        if (programmes[key]) {
                          programmes[key]['total'] += value;
                        } else {
                          programmes[key] = {id: key, name: scoreItem.programme.name, total: value};
                        }
                      });
                    });
                    programmes = Object.values(programmes);
                    const categories = programmes.map(v => v.name);
                    const seriesData = programmes.map(v => ({x: v.name, y: v.total}));
  
                    const options = {
                      series: [{
                          data: seriesData,
                      }],
                      chart: {
                        type: 'bar',
                        height: 500
                      },
                      plotOptions: {
                        bar: {
                          horizontal: true,
                          columnWidth: '70%',
                          endingShape: 'rounded',
                          distributed: true, // varying colour columns
                        },
                      },
                      dataLabels: {
                        enabled: false
                      },
                      stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                      },
                      xaxis: {
                        categories: categories,
                      },
                      yaxis: {
                        title: {
                          text: 'Program',
                        }
                      },
                      fill: {
                        opacity: 1
                      },
                      tooltip: {
                        y: {
                          formatter: function(val) {
                            return val + " Points"
                          }
                        }
                      }
                    };
                    new ApexCharts(document.querySelector("#scoresPerProgramme"), options).render();
                  });
                </script>
                <!-- End Column Chart -->
              </div>
            </div>
          </div>
          <!-- End Scores Per Program by Team -->
        </div>

        <div class="row">
          <!-- Team Size Per Month -->
          <div class="col-md-6 col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Team Composition<span></span></h5>
                <!-- Column Chart -->
                <div id="teamComposition"></div>
                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    const teams = @json($teams);

                    const categories = teams.map(v => v.name);
                    const localData = teams.map(v => v.local_size);
                    const diasporaData = teams.map(v => v.diaspora_size);
                    const totalData = teams.map(v => v.total);
  
                    const options = {
                      series: [
                        {
                          name: 'Local',
                          data: localData,
                        },
                        {
                          name: 'Diaspora',
                          data: diasporaData,
                        },
                        {
                          name: 'Total',
                          data: totalData,
                        },
                      ],
                      chart: {
                        type: 'bar',
                        height: 350,
                      },
                      plotOptions: {
                        bar: {
                          horizontal: false,
                          columnWidth: '70%',
                          endingShape: 'rounded',
                        },
                      },
                      dataLabels: {
                        enabled: false
                      },
                      stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                      },
                      xaxis: {
                        categories: categories,
                      },
                      yaxis: {
                        title: {
                          text: 'Size',
                        }
                      },
                      fill: {
                        opacity: 1
                      },
                      tooltip: {
                        y: {
                          formatter: function(val) {
                            return val + " Members"
                          }
                        }
                      }
                    };
                    new ApexCharts(document.querySelector("#teamComposition"), options).render();
                  });
                </script>
                <!-- End Column Chart -->
              </div>
            </div>
          </div>
          <!-- End Team Size Per Month -->

          <!-- Pledge & Mission Distribution -->
          <div class="col-md-6 col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Finance Actual & Mission<span></span></h5>
                <!-- Column Chart -->
                <div id="financePledgeAndMission"></div>
                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    const contributions = @json($contributions);
                    const categories = contributions.map(v => v.name);
                    const financeData = contributions.map(v => v.finance);
                    const missionData = contributions.map(v => v.mission);
                    const totalData = contributions.map(v => v.total);

                    const options = {
                      series: [
                        {
                          name: 'Finance',
                          data: financeData,
                        },
                        {
                          name: 'Mission',
                          data: missionData,
                        },
                        {
                          name: 'Total',
                          data: totalData,
                        },
                      ],
                      chart: {
                        type: 'bar',
                        height: 350,
                      },
                      plotOptions: {
                        bar: {
                          horizontal: false,
                          columnWidth: '70%',
                          endingShape: 'rounded',
                        },
                      },
                      dataLabels: {
                        enabled: false
                      },
                      stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                      },
                      colors: ['#FF5733', '#33FF57', '#3357FF'], // Custom color palette
                      xaxis: {
                        categories
                      },
                      yaxis: {
                        title: {
                          text: 'Contribution',
                        }
                      },
                      fill: {
                        opacity: 1
                      },
                      tooltip: {
                        y: {
                          formatter: function(val) {
                            return 'KES ' + accounting.formatNumber(val);
                          }
                        }
                      }
                    };
                    const chart = new ApexCharts(document.querySelector("#financePledgeAndMission"), options);
                    chart.render();
                  });

                </script>
                <!-- End Column Chart -->
              </div>
            </div>
          </div>
          <!-- End Pledge & Mission Distribution -->
        </div>
      </div>
    </div>
  </section>
</main>
@stop
