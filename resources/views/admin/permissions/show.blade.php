@extends('layouts.admin')
@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">{{ trans('global.show') }} {{ trans('cruds.permission.title') }}</h5>
        </div>

        <div class="card-body">
            <div class="mb-4">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th class="w-25">
                                {{ trans('cruds.permission.fields.id') }}
                            </th>
                            <td>
                                {{ $permission->id }}
                            </td>
                        </tr>
                        <tr>
                            <th class="w-25">
                                {{ trans('cruds.permission.fields.title') }}
                            </th>
                            <td>
                                {{ $permission->title }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <a style="margin-top:20px;" class="btn btn-outline-primary btn-sm" href="{{ url()->previous() }}">
                    <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
                </a>
            </div>

            <div class="tab-content">

            </div>
        </div>
    </div>
@endsection
