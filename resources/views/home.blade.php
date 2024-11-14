@extends('layouts.admin')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-lg border-light">
                        <div class="card-header ">
                            <h4 class="mb-0">Dashboard</h4>
                        </div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row">
                                <!-- Total Tickets Card -->
                                <div class="col-md-4 mb-4">
                                    <div class="card bg-primary text-white shadow-lg rounded">
                                        <div
                                            class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                                            <h3 class="card-title mb-2 display-4">{{ $totalTickets }}</h3>
                                            <p class="card-text">Total Tickets</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Open Tickets Card -->
                                <div class="col-md-4 mb-4">
                                    <div class="card bg-success text-white shadow-lg rounded">
                                        <div
                                            class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                                            <h3 class="card-title mb-2 display-4">{{ $openTickets }}</h3>
                                            <p class="card-text">Tickets Abiertos</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Closed Tickets Card -->
                                <div class="col-md-4 mb-4">
                                    <div class="card bg-danger text-white shadow-lg rounded">
                                        <div
                                            class="card-body d-flex flex-column justify-content-center align-items-center p-4">
                                            <h3 class="card-title mb-2 display-4">{{ $closedTickets }}</h3>
                                            <p class="card-text">Tickets Cerrados</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
@endsection
