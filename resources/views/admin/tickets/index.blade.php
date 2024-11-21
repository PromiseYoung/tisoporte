@extends('layouts.admin')
@section('content')
    @can('ticket_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.tickets.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.ticket.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.ticket.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table
                class=" table table-bordered table-hover table-sm dt-responsive nowrap ajaxTable datatable datatable-Ticket">
                <thead class="thead-dark">
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.priority') }}
                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.category') }}
                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.author_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.author_email') }}
                        </th>
                        <th>
                            {{ trans('cruds.ticket.fields.assigned_to_user') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
            </table>


        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            // Filtros HTML
            let filters = `
<form class="form-inline" id="filtersForm">
  <div class="form-group mx-2 mb-2">
    <select class="form-control" name="status">
      <option value="">Estados</option>
      @foreach ($statuses as $status)
        <option value="{{ $status->id }}"{{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="form-group mx-2 mb-2">
    <select class="form-control" name="priority">
      <option value="">Prioridades</option>
      @foreach ($priorities as $priority)
        <option value="{{ $priority->id }}"{{ request('priority') == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="form-group mx-2 mb-2">
    <select class="form-control" name="category">
      <option value="">Categorias</option>
      @foreach ($categories as $category)
        <option value="{{ $category->id }}"{{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
      @endforeach
    </select>
  </div>
</form>
`;

            let dtButtons = [];

            @can('ticket_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.tickets.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id;
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}');
                            return;
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
                            }).done(function() {
                                location.reload();
                            });
                        }
                    }
                };
                dtButtons.push(deleteButton);
            @endcan

            let searchParams = new URLSearchParams(window.location.search);
            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: {
                    url: "{{ route('admin.tickets.index') }}",
                    data: function(d) {
                        d.status = $('select[name="status"]').val();
                        d.priority = $('select[name="priority"]').val();
                        d.category = $('select[name="category"]').val();
                    }
                },
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title',
                        render: function(data, type, row) {
                            return '<a href="' + row.view_link + '">' + data + ' (' + row
                                .comments_count + ')</a>';
                        }
                    },
                    {
                        data: 'status_name',
                        name: 'status.name',
                        render: function(data, type, row) {
                            return '<span style="color:' + row.status_color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'priority_name',
                        name: 'priority.name',
                        render: function(data, type, row) {
                            return '<span style="color:' + row.priority_color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'category_name',
                        name: 'category.name',
                        render: function(data, type, row) {
                            return '<span style="color:' + row.category_color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'author_name',
                        name: 'author_name'
                    },
                    {
                        data: 'author_email',
                        name: 'author_email'
                    },
                    {
                        data: 'assigned_to_user_name',
                        name: 'assigned_to_user.name'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
                initComplete: function(settings, json) {
                    $(".dataTables_filter").after(filters);
                }
            };

            // Inicializa la DataTable
            let table = $('.datatable-Ticket').DataTable(dtOverrideGlobals);

            // Maneja el cambio en los select para recargar la tabla
            $('.card-body').on('change', 'select', function() {
                table.ajax.reload(); // Recarga la tabla con los nuevos parámetros
            });

            // Ajustar columnas en el cambio de pestaña
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                table.columns.adjust();
            });
        });
    </script>
@endsection
