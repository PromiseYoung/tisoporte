@extends('layouts.admin')
@section('content')
    @can('category_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.categories.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.category.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card rounded-4 bg-white mb-4 border-0 shadow-sm table-responsive-lg table-hover border-success  border-2">
        <div
            class="card-header bg-success text-white fw-bold fs-5 d-flex align-items-center rounded-top-4 border-bottom border-success">
            {{ trans('cruds.category.title_singular') }} {{ trans('global.list') }}
        </div>
        <div class="card-body p-4 rounded-bottom-4 border-top border-success shadow-sm">
            <div class="table-responsive">
                <table
                    class="table table-hover table-striped table-bordered align-middle datatable datatable-Category shadow-sm rounded">
                    <thead class="table-success text-white">
                        <tr class="text-center align-middle">
                            <th scope="col">
                                <i class="fas fa-check-circle"></i>
                            </th>
                            <th scope="col">
                                {{ trans('cruds.category.fields.id') }}
                            </th>
                            <th scope="col">
                                {{ trans('cruds.category.fields.name') }}
                            </th>
                            <th scope="col">
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <th scope="col">
                                {{ trans('cruds.category.fields.color') }}
                            </th>
                            <th scope="col">
                                <i class="fas fa-cogs"></i> {{ __('Acciones') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $key => $category)
                            <tr data-entry-id="{{ $category->id }}">
                                <td class="text-center">

                                </td>
                                <td class="text-center">
                                    {{ $category->id ?? '' }}
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $category->name ?? '' }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $category->users->name ?? '' }}</span>
                                </td>
                                <td style="background-color:{{ $category->color ?? '#FFFFFF' }}; width: 60px;"></td>
                                <td class="text-center">
                                    @can('category_show')
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('admin.categories.show', $category->id) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan

                                    @can('category_edit')
                                        <a class="btn btn-sm btn-outline-info"
                                            href="{{ route('admin.categories.edit', $category->id) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    @can('category_delete')
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endcan
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
            @can('category_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.categories.massDestroy') }}",
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
            $('.datatable-Category:not(.ajaxTable)').DataTable({
                buttons: dtButtons,
                select: {
                    style: 'multi+shift',
                    selector: 'td:first-child'
                },
                columnDefs: [{
                        orderable: false,
                        className: 'select-checkbox',
                        targets: 0
                    },
                    {
                        orderable: false,
                        searchable: false,
                        targets: -1
                    }
                ]
            });
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })
    </script>
@endsection
