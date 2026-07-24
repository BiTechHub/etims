@extends('admin.layouts.master')
@section('main-section')
<div class="container">
  <div class="page-inner">
    <div class="page-header">
      <h3 class="fw-bold mb-3">Registration List</h3>
      <ul class="breadcrumbs mb-3">
        <li class="nav-home">
          <a href="#">
            <i class="icon-home"></i>
          </a>  
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">Registration List</a>
        </li>
        <li class="separator">
          <i class="icon-arrow-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">View Registrations</a>
        </li>
      </ul>
    </div>
    <div class="row">
      
      <div class="card">
     <div class="card-body">
<div class="table-container table-responsive">
     <table class="table table-bordered table-hover">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Branch</th>
                            <th>State</th>
                            <th>Payment</th>
                             <th>Payment Status</th>
                            <th>Created At</th>
                            {{-- <th style="min-width:120px;">Action</th> --}}
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($users as $user)

                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->branch }}</td>
                            <td>{{ $user->state }}</td>

                            <td>
                                <span class="badge bg-success">
                                    {{ $user->payment }}
                                </span>
                            </td>
                              <td>

                            @if($user->payment_status=='Success')

                                <span class="badge bg-success">
                                    Success
                                </span>

                            @elseif($user->payment_status=='Pending')

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Failed
                                </span>

                            @endif

                        </td>


                            <td>{{ $user->created_at }}</td>

                            {{-- <td>
                                <a href="{{ url('/registration-list/' . $user->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    ✏ Edit
                                </a>

                                <form action="{{ url('/registration-list/' . $user->id) }}" 
                                      method="POST" 
                                      style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" 
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">
                                        🗑 Delete
                                    </button>
                                </form>
                            </td> --}}
                        </tr>

                        @empty

                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                No Registration Found
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>
</div>

     </div>

      </div>
    </div>
  </div>
</div>
@endsection
