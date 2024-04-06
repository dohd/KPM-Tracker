<div class="col-12">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Regional Activity Participant <span></span></h5>

      <!-- Column Chart -->
      <div id="regionalActivityParticipant"></div>

      <script>
        const maleDist = [];
        const femaleDist = [];
        const totalDist = [];
        const regionPts = @json(@$region_pts);
        regionPts.forEach(v => {
          maleDist.push(v['male_count']);
          femaleDist.push(v['female_count']);
          totalDist.push(v['total_count']);
        });
        const regionDist = @json(@$region_dist);

        document.addEventListener("DOMContentLoaded", () => {
          new ApexCharts(document.querySelector("#regionalActivityParticipant"), {
            series: [{
              name: 'Male',
              // data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 23, 30, 10],
              data: maleDist,
              }, 
              {
                name: 'Female',
                // data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 25, 15, 42],
                data: femaleDist,
              },
              {
                name: 'Total',
                // data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 25, 15, 42],
                data: totalDist,
              },
            ],
            colors: ['#6f42c1', '#d63384', '#e6b400'],
            chart: {
              type: 'bar',
              height: 350
            },
            plotOptions: {
              bar: {
                horizontal: true,
                columnWidth: '55%',
                endingShape: 'rounded'
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
              // categories: ['Kisumu', 'Kakamega', 'Nairobi', 'Mombasa', 'Kisii', 'Nyeri', 'Machakos', 'Meru', 'Malindi', 'Eldoret', 'Isiolo', 'Busia'],
              categories: regionDist,
            },
            yaxis: {
              title: {
                text: 'Regions'
              },
            },
            fill: {
              opacity: 1
            },
            tooltip: {
              y: {
                formatter: function(val) {
                  return val + " participants"
                }
              }
            }
          }).render();
        });
      </script>
      <!-- End Column Chart -->
    </div>
  </div>
</div>