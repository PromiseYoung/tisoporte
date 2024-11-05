@extends('layouts.admin')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-light">
                        <div class="card-header">
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
                                <div class="col-md-4">
                                    <div class="card text-white bg-primary shadow-sm">
                                        <div class="card-body d-flex flex-column justify-content-center text-center">
                                            <h3 class="card-title mb-0">{{ $totalTickets }}</h3>
                                            <p class="card-text mb-0">Total Tickets</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card text-white bg-success shadow-sm">
                                        <div class="card-body d-flex flex-column justify-content-center text-center">
                                            <h3 class="card-title mb-0">{{ $openTickets }}</h3>
                                            <p class="card-text mb-0">Tickets Abiertos</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card text-white bg-danger shadow-sm">
                                        <div class="card-body d-flex flex-column justify-content-center text-center">
                                            <h3 class="card-title mb-0">{{ $closedTickets }}</h3>
                                            <p class="card-text mb-0">Tickets Cerrados</p>
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
