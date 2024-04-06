<div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Monthly Activity Participant <span></span></h5>

        <!-- Column Chart -->
        <div id="monthlyActivityParticipant"></div>

        <script>
          const maleCount = [];
          const femaleCount = [];
          const totalCount = [];
          const monthlyPts = @json(@$monthly_pts);
          for (let i = 1; i < 13; i++) {
            let isChecked = false;
            monthlyPts.forEach(v => {
              if (v['month'] == i) {
                isChecked = true;
                maleCount.push(v['male_count']*1);
                femaleCount.push(v['female_count']*1);
                totalCount.push(v['total_count']*1);
              }
            });
            if (!isChecked) {
              maleCount.push(0);
              femaleCount.push(0);
              totalCount.push(0);
            }
          }

          document.addEventListener("DOMContentLoaded", () => {
            new ApexCharts(document.querySelector("#monthlyActivityParticipant"), {
              series: [{
                name: 'Male',
                // data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 23, 30, 10]
                data: maleCount,
                }, 
                {
                  name: 'Female',
                  // data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 25, 15, 42]
                  data: femaleCount,
                },
                {
                  name: 'Total',
                  // data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 25, 15, 42]
                  data: totalCount,
                },
              ],
              chart: {
                type: 'bar',
                height: 350
              },
              plotOptions: {
                bar: {
                  horizontal: false,
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
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
              },
              yaxis: {
                title: {
                  text: 'Range'
                }
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