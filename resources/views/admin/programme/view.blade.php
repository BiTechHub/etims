@extends('admin.layouts.master')

@section('content')
<main class="content">
    <style>
        body, html {
            overflow-x: hidden;
        }
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid transparent;
            border-radius: .25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
        }
        .me-2 {
            margin-right: .5rem!important;
        }
        .table th, .table td {
            word-break: break-word;
        }
    </style>

    <div class="container-fluid">
        <div class="main-body">

            <div class="card mb-3 shadow">
                <div class="card-body">
                    <h2 class="text-center"><b>Programme ID: {{ $programme->id }}</b></h2>
                </div>
            </div>

            <div class="row">
                {{-- First Column --}}
                <div class="col-lg-6 mb-3">
                    <div class="card p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Title:</th>
                                        <td>{{ $programme->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Agency Type:</th>
                                        <td>{{ $agencyName }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sponsor:</th>
                                        <td>{{ $sponsorName }}</td>
                                    </tr>
                                    <tr>
                                        <th>Group:</th>
                                        <td>{{ $groupName }}</td>
                                    </tr>
                                    <tr>
                                        <th>Department:</th>
                                        <td>{{ $departmentName }}</td>
                                    </tr>
                                    <tr>
                                        <th>Location:</th>
                                        <td>{{ $programme->location }}</td>
                                    </tr>
                                    <tr>
                                        <th>Venue:</th>
                                        <td>{{ $programme->venue }}</td>
                                    </tr>
                                    <tr>
                                        <th>Duration:</th>
                                        <td>{{ $programme->duration }}</td>
                                    </tr>
                                    <tr>
                                        <th>Financial Year:</th>
                                        <td>{{ $programme->financial_year }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Second Column --}}
                <div class="col-lg-6 mb-3">
                    <div class="card p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>From Date:</th>
                                        <td>{{ \Carbon\Carbon::parse($programme->from_date)->format('d-m-Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>To Date:</th>
                                        <td>{{ \Carbon\Carbon::parse($programme->to_date)->format('d-m-Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Session Start:</th>
                                        <td>{{ \Carbon\Carbon::parse($programme->session_start)->format('d-m-Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Session End:</th>
                                        <td>{{ \Carbon\Carbon::parse($programme->session_end)->format('d-m-Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Fee Structure:</th>
                                        <td>{{ $programme->fee_structure }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>{{ $programme->status }}</td>
                                    </tr>
                                    <tr>
                                        <th>Announced On:</th>
                                        <td>{{ \Carbon\Carbon::parse($programme->announced_on)->format('d-m-Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Nomination Date:</th>
                                        <td>{{ \Carbon\Carbon::parse($programme->last_nomination_date)->format('d-m-Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Strength:</th>
                                        <td>{{ $programme->strength }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Full Width Section --}}
                <div class="col-lg-12 mb-3">
                    <div class="card p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>Boarding Plan:</th>
                                        <td>{{ $programme->boarding_plan }}</td>
                                    </tr>
                                    <tr>
                                        <th>Max Discount Amount:</th>
                                        <td>{{ $programme->max_disc_amt }}</td>
                                    </tr>
                                    <tr>
                                        <th>Clientele Type:</th>
                                        <td>{{ $programme->clientele_type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Clientele:</th>
                                        <td>{{ $programme->clientele }}</td>
                                    </tr>
                                    <tr>
                                        <th>Claim Reference:</th>
                                        <td>{{ $programme->claim_ref }}</td>
                                    </tr>
                                    <tr>
                                        <th>Remarks:</th>
                                        <td>{{ $programme->remarks }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</main>
@endsection
