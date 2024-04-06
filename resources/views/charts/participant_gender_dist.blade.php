<div class="card">
  <div class="card-body">
    <h5 class="card-title">Participant Gender Distribution</h5>

    <!-- Pie Chart -->
    <div id="participantGenderDistribution"></div>

    <script>
      let genderGroupDist = @json(@$gender_group_dist);
      genderGroupDist = genderGroupDist.map(v => v*1);
      genderGroupLabel = @json(@$gender_group_label);
      
      document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#participantGenderDistribution"), {
          // series: [44, 60],
          series: genderGroupDist,
          chart: {
            height: 350,
            type: 'pie',
            toolbar: {
              show: true
            }
          },
          // labels: ['Under 18', 'Btwn 18 & 35', 'Above 35']
          labels: genderGroupLabel,
        }).render();
      });
    </script>
    <!-- End Pie Chart -->
  </div>
</div>