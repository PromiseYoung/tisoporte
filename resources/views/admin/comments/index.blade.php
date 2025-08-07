@extends('layouts.admin')
@section('content')
    @can('comment_create')
        <div class="mb-3">
            <a class="btn btn-success btn-lg shadow-sm" href="{{ route('admin.comments.create') }}">
                <i class="fas fa-comment-dots me-2"></i>
                {{ trans('global.add') }} {{ trans('cruds.comment.title_singular') }}
            </a>
        </div>
    @endcan
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-comments me-2"></i>
                {{ trans('cruds.comment.title_singular') }} {{ trans('global.list') }}
            </h5>
        </div>

        <div class="card-body p-4 rounded-bottom-4 border-top border-success shadow-sm">
            <div class="table-responsive">
                <table
                    class="table table-hover table-striped align-middle shadow-sm rounded dt-responsive nowrap datatable datatable-Comment">
                    <thead class="table-dark">
                        <tr>
                            <th width="10">
                                <i class="fas fa-check-square"></i>
                            </th>
                            <th>
                                {{ trans('cruds.comment.fields.id') }}
                            </th>
                            <th>
                                {{ trans('cruds.comment.fields.ticket') }}
                            </th>
                            <th>
                                {{ trans('cruds.comment.fields.author_name')  }}
                            </th>
                            <th>
                                {{ trans('cruds.comment.fields.author_email') }}
                            </th>
                            <th>
                                {{ trans('cruds.comment.fields.user') }}
                            </th>
                            <th>
                                {{ trans('cruds.comment.fields.comment_text') }}
                            </th>
                            <th>
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @foreach ($comments as $key => $comment)
                            <tr data-entry-id="{{ $comment->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $comment->id ?? '' }}
                                </td>
                                <td class="text-nowrap">
                                    {{ $comment->ticket->title ?? '' }}
                                </td>
                                <td>
                                    {{ $comment->author_name ?? '' }}
                                </td>
                                <td class="text-muted">
                                    {{ $comment->author_email ?? '' }}
                                </td>
                                <td>
                                    {{ $comment->user->name ?? '' }}
                                </td>
                                <td class="text-start">
                                    {{ Str::limit($comment->comment_text, 50) }}
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex justify-content-center align-items-center gap-2">
                                        @can('comment_show')
                                            <a class="btn btn-sm btn-outline-primary"
                                                href="{{ route('admin.comments.show', $comment->id) }}" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('comment_edit')
                                            <a class="btn btn-sm btn-outline-info"
                                                href="{{ route('admin.comments.edit', $comment->id) }}" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('comment_delete')
                                            <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST"
                                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                class="d-inline">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('comment_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.comments.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            $('.datatable-Comment:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })
    </script>
@endsection
