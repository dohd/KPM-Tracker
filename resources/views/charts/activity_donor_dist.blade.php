<div class="card">
  <div class="card-body">
    <h5 class="card-title">Donor Activity Distribution</h5>

    <!-- Donut Chart -->
    <div id="donorActivityDistribution"></div>

    <script>
      const donorDist = @json(@$donors_dist);
      let donorActivityDist = @json(@$donor_activity_dist);
      donorActivityDist = donorActivityDist.map(v => v['count']);

      document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#donorActivityDistribution"), {
          // series: [44, 56, 12, 20],
          series: donorActivityDist,
          chart: {
            height: 350,
            type: 'donut',
            toolbar: {
              show: true
            }
          },
          // labels: ['WHO', 'UNEP', 'County Gvt', 'National Gvt'],
          labels: donorDist,
        }).render();
      });
    </script>
  </div>
</div>