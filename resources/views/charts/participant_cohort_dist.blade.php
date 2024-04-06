<div class="card">
  <div class="card-body">
    <h5 class="card-title">Participant Cohort Distribution</h5>

    <!-- Donut Chart -->
    <div id="participantCohortDistribution"></div>

    <script>
      const cohortDist = @json(@$cohort_dist);
      let psCohortDist = @json(@$ps_cohort_dist);
      psCohortDist = psCohortDist.map(v => v['count']*1);

      document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#participantCohortDistribution"), {
          // series: [44, 56, 12, 20],
          series: psCohortDist,
          chart: {
            height: 350,
            type: 'donut',
            toolbar: {
              show: true
            }
          },
          // labels: ['Youth', 'Students', 'Teachers', 'Patients'],
          labels: cohortDist,
        }).render();
      });
    </script>
  </div>
</div>