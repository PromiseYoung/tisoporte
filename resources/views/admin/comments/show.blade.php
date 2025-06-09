@extends('layouts.admin')
@section('content')
    <div class="card shadow-sm rounded">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">{{ trans('global.show') }} {{ trans('cruds.comment.title') }}</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <tbody>
                        <tr>
                            <th>{{ trans('cruds.comment.fields.id') }}</th>
                            <td>{{ $comment->id }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.comment.fields.ticket') }}</th>
                            <td>{{ $comment->ticket->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.comment.fields.author_name') }}</th>
                            <td>{{ $comment->author->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.comment.fields.author_email') }}</th>
                            <td>{{ $comment->author_email }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.comment.fields.user') }}</th>
                            <td>{{ $comment->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>{{ trans('cruds.comment.fields.comment_text') }}</th>
                            <td>{!! $comment->comment_text !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg mt-3 rounded-pill">
                <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
@endsection
