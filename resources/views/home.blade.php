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
          <!-- Programmes Card -->
          <div class="col-md-4 col-12">
            <div class="card info-card sales-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown" ><i class="bi bi-three-dots"></i></a>
              </div>
              <div class="card-body">
                <h5 class="card-title">Programs <span></span></h5>
                <div class="d-flex align-items-center">
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
                <div class="d-flex align-items-center">
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

          <!-- Members Card -->
          <div class="col-md-4 col-12">
            <div class="card info-card sales-card">
              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown" ><i class="bi bi-three-dots"></i></a>
              </div>
              <div class="card-body">
                <h5 class="card-title">Members <span></span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <a href="{{ route('teams.index') }}" style="color:inherit"><i class="bi bi-people"></i></a>
                  </div>
                  <div class="ps-3">
                    <h6>{{ $numMembers }}</h6>
                    <span class="text-muted small pt-2 ps-1">Members</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Members Card -->

          <!-- Welcome Card -->
          <div class="col-md-12 col-12">
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
          </div>
          <!-- End Welcome Card -->

        </div>
      </div>
    </div>
  </section>
</main>
@stop
