<div class="pagetitle">
  <div class="row">
    <div class="col-6">
      <h1>Team Management</h1>
    </div>
    <div class="col-6">
      @if (auth()->user()->user_type == 'chair')
        <a href="{{ route('teams.create') }}" class="btn btn-primary float-end ms-1"><i class="bi bi-plus-circle"></i> Create</a>
      @endif
      <a href="{{ route('teams.index') }}" class="btn btn-secondary float-end"><i class="bi bi-card-list"></i> List</a>
    </div>
  </div>

  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
      <li class="breadcrumb-item active"><a href="{{ route('teams.index') }}">Team</a></li>
    </ol>
  </nav>
</div>