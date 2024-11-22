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
      <li class="nav-heading">Programme Management</li>
      <!-- metric input -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('metrics.index') }}">
          <i class="bi bi-person-lines-fill"></i></i><span>Metric Input</span>
        </a>
      </li>
      <!-- assign scores -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('assign_scores.create') }}">
          <i class="bi bi-list-check"></i><span>Compute Scores</span>
        </a>
      </li>

      <!-- Reports -->
      <li class="nav-heading">Reports</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('reports.create_performance') }}">
          <i class="bi bi-table"></i><span>Team Performance</span>
        </a>
      </li>
      
      <li class="nav-heading">Account Settings</li>
      <!-- key programmes -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('programmes.index') }}">
          <i class="bi bi-tag"></i><span>Key Programmes</span>
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
        <a class="nav-link collapsed" href="{{ route('team_labels.index') }}">
          <i class="bi bi-people"></i><span>Team Assignment</span>
        </a>
      </li>
      <!-- user management -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('user_profiles.index') }}">
          <i class="bi bi-people"></i><span>Users</span>
        </a>
      </li>
    @endif

    @if (in_array(auth()->user()->user_type, ['captain', 'member']))
      <li class="nav-heading">Programme Management</li>
      <!-- metric input -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('metrics.index') }}">
          <i class="bi bi-person-lines-fill"></i></i><span>Metric Input</span>
        </a>
      </li>

      <!-- Reports -->
      <li class="nav-heading">Reports</li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('reports.create_performance') }}">
          <i class="bi bi-table"></i><span>Performance Report</span>
        </a>
      </li>

      @if (in_array(auth()->user()->user_type, ['captain']))
        <li class="nav-heading">Account Settings</li>
        <!-- teams -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('team_labels.index') }}">
            <i class="bi bi-people"></i><span>Team Assignment</span>
          </a>
        </li>
      @endif
    @endif
  </ul>
</aside>
