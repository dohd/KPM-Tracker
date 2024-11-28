<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('home') }}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <!-- End Dashboard Nav -->

    @if (auth()->user()->user_type == 'chair')
      <li class="nav-heading">Metrics & Scores</li>
      <!-- metric input -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('metrics.index') }}">
          <i class="bi bi-list-check"></i><span>Metrics</span>
        </a>
      </li>
      <!-- assign scores -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('assign_scores.create') }}">
          <i class="bi bi-calculator"></i><span>Scores</span>
        </a>
      </li>
      <!-- key programmes -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('programmes.index') }}">
          <i class="bi bi-tag"></i><span>Programs</span>
        </a>
      </li>
      <!-- score cards -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('score_cards.index') }}">
          <i class="bi bi-kanban"></i><span>Rating Scale</span>
        </a>
      </li>
      <!-- teams -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('teams.index') }}">
          <i class="bi bi-people"></i><span>Teams</span>
        </a>
      </li>

      <!-- Reports -->
      <li class="nav-heading">Report Center</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('reports.metric_summary') }}">
          <i class="bi bi-circle"></i><span>Metric Summary</span>
        </a>
        {{-- <a class="nav-link collapsed" href="{{ route('reports.team_summary_performance') }}">
          <i class="bi bi-circle"></i><span>Team Performance Report Card</span>
        </a> --}}
        <a class="nav-link collapsed" href="{{ route('reports.team_summary_performance') }}">
          <i class="bi bi-circle"></i><span>Performance Summary</span>
        </a>
        <a class="nav-link collapsed" href="{{ route('reports.team_size_summary') }}">
          <i class="bi bi-circle"></i><span>Team Size Summary</span>
        </a>
        <a class="nav-link collapsed" href="{{ route('reports.monthly_pledge_vs_mission') }}">
          <i class="bi bi-circle"></i><span>Monthly Pledge Vs Mission</span>
        </a>
        {{-- <a class="nav-link collapsed" href="{{ route('reports.team_summary_performance') }}">
          <i class="bi bi-circle"></i><span>Program per Team Financial Report</span>
        </a> --}}
        {{-- <a class="nav-link collapsed" href="{{ route('reports.team_summary_performance') }}">
          <i class="bi bi-circle"></i><span>Team Pledge Vs. Actual Amount Report</span>
        </a> --}}
        {{-- <a class="nav-link collapsed" href="{{ route('reports.team_summary_performance') }}">
          <i class="bi bi-circle"></i><span>Max Score Vs. Actual per Team-Program</span>
        </a> --}}
      </li>
      
      <li class="nav-heading">Settings</li>
      <!-- user management -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('user_profiles.index') }}">
          <i class="bi bi-person-lines-fill"></i><span>Users</span>
        </a>
      </li>
      <!-- settings -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('config.general_settings') }}">
          <i class="bi bi-gear-fill"></i><span>General</span>
        </a>
      </li>
    @endif

    
    <!-- captain and member menus -->
    @if (in_array(auth()->user()->user_type, ['captain', 'member']))
      <li class="nav-heading">Metrics & Scores</li>
      <!-- metric input -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('metrics.index') }}">
          <i class="bi bi-list-check"></i><span>Metrics</span>
        </a>
      </li>
      @if (in_array(auth()->user()->user_type, ['captain']))
        <!-- teams -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('teams.index') }}">
            <i class="bi bi-people"></i><span>Teams</span>
          </a>
        </li>
      @endif

      <!-- Reports -->
      {{-- <li class="nav-heading">Reports</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('reports.team_summary_performance') }}">
          <i class="bi bi-circle"></i><span>Team Performance Summary</span>
        </a>
      </li> --}}
    @endif
  </ul>
</aside>
