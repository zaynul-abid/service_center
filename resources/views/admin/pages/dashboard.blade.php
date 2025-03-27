@extends('backend.layouts.app')
@section('title', 'Admin-Dashboard')

@section('navbar')
@include('admin.partials.navbar')
@endsection

@section('content')

<main>
  <div class="container-fluid px-4">
      <h1 class="mt-4"> Admin Dashboard</h1>
      <ol class="breadcrumb mb-4">
          <li class="breadcrumb-item active">Dashboard</li>
      </ol>
      <div class="row">
          <div class="col-xl-3 col-md-6">
              <div class="card bg-primary text-white mb-4">
                  <div class="card-body" style="padding: 2rem; font-size: 1.25rem;">Employees
                      <div style="font-size: 2rem; font-weight: bold;">{{$employeeCount}}</div>

                  </div>
              </div>
          </div>
          <div class="col-xl-3 col-md-6">
              <div class="card bg-warning text-white mb-4">
                  <div class="card-body" style="padding: 2rem; font-size: 1.25rem;">Services
                      <div style="font-size: 2rem; font-weight: bold;">{{$serviceCount}}</div>
                  </div>
              </div>
          </div>

          <div class="col-xl-3 col-md-6">
              <div class="card bg-danger text-white mb-4">
                  <div class="card-body" style="padding: 2rem; font-size: 1.25rem;">Pending Services
                      <div style="font-size: 2rem; font-weight: bold;">{{$pendingServiceCount}}</div>
                  </div>
              </div>
          </div>
          <div class="col-xl-3 col-md-6">
              <div class="card bg-success text-white mb-4">
                  <div class="card-body" style="padding: 2rem; font-size: 1.25rem;">Completed Service
                      <div style="font-size: 2rem; font-weight: bold;">{{$completedServiceCount}}</div>

                  </div>
              </div>
          </div>
      </div>



           <div class="card mb-4">
          <div class="card-header">
              <i class="fas fa-users me-1"></i> Users List
          </div>


          <div class="card-body">


              <table id="datatablesSimple" class="table table-bordered">
                  <thead class="table-dark">
                      <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Company</th>
                          <th>Role</th>

                      </tr>
                  </thead>
                  <tfoot>
                      <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Company</th>
                          <th>Role</th>
                      </tr>
                  </tfoot>
                  <tbody>
                      @forelse($users as $user)
                          <tr>
                              <td>{{ $user->id }}</td>
                              <td>{{ $user->name }}</td>
                              <td>{{ $user->email }}</td>
                              <td>{{ $user->company->company_name ?? 'N/A' }}</td>
                              <td>
                                  <span class="badge

                                      @if($user->usertype === 'admin') bg-warning
                                      @elseif($user->usertype === 'employee') bg-success
                                      @else bg-secondary
                                      @endif">
                                      {{ strtoupper($user->usertype) }}
                                  </span>
                              </td>

                          </tr>
                      @empty
                          <tr>
                              <td colspan="7" class="text-center">No Users Found</td>
                          </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>
      </div>
       <div class="row">
          <div class="col-xl-6">
              <div class="card mb-4">
                  <div class="card-header">
                      <i class="fas fa-chart-area me-1"></i>
                      Area Chart Example
                  </div>
                  <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
              </div>
          </div>
          <div class="col-xl-6">
              <div class="card mb-4">
                  <div class="card-header">
                      <i class="fas fa-chart-bar me-1"></i>
                      Bar Chart Example
                  </div>
                  <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
              </div>
          </div>
      </div>

  </div>
</main>


@endsection
