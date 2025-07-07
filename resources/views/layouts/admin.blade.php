<!DOCTYPE html>
<html>
<script>
    // Aplica inmediatamente la clase de tema sin esperar al DOM
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        document.documentElement.classList.add('dark'); // Opcional si usas Tailwind
    }
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="x-dns-prefetch-control" content="off">

    <title>{{ trans('panel.site_title') }}</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/@coreui/coreui@2.1.16/dist/css/coreui.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    @yield('styles')
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed pace-done sidebar-lg-show">
    <header class="app-header navbar">
        <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">
            <span class="navbar-brand-full">{{ trans('panel.site_title') }}</span>
            <span class="navbar-brand-minimized">{{ trans('panel.site_title') }}</span>
        </a>
        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
            <span class="navbar-toggler-icon"></span>
        </button>
        @auth
            <ul class="nav-item ml-auto">
                <ul class="text-center">
                    <h5 class="fw-bold text-primary mb-0 fs-md-4 fs-5">BIENVENIDO. </h5>
                    <h5 class="text-success mb-0 fs-md-4 fs-5">{{ Auth::user()->name }}</h5>
                </ul>
            </ul>
        @endauth
        <ul class="nav navbar-nav ml-auto d-flex justify-content-center align-items-center">
            @if (count(config('panel.available_languages', [])) > 1)
                <li class="nav-item dropdown d-md-down-none">
                    <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                        aria-expanded="false">
                        {{ strtoupper(app()->getLocale()) }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @foreach (config('panel.available_languages') as $langLocale => $langName)
                            <a class="dropdown-item"
                                href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }}
                                ({{ $langName }})
                            </a>
                        @endforeach
                    </div>
                </li>
            @endif
            {{-- BOTON DE MOOD DARK-LIGHT --}}
            <li class="nav-item">
                <button id="theme-toggle" class="btn btn-light border-0 shadow-sm rounded-circle">
                    <i id="theme-icon" class="fa-solid fa-sun"></i>
                </button>
            </li>
        </ul>
    </header>
    <div class="app-body">
        @include('partials.menu')
        <main class="main">
            <div style="padding-top: 20px" class="container-fluid">
                @if (session('message'))
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                        </div>
                    </div>
                @endif
                @if ($errors->count() > 0)
                    <div class="alert alert-danger">
                        <ul class="list-unstyled">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.2.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/@coreui/coreui@2.1.16/dist/js/coreui.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        $(function() {
            let logo = "{{ asset('logo/load.png') }}";
            let copyButtonTrans = '{{ trans('global.datatables.copy') }}';
            let csvButtonTrans = '{{ trans('global.datatables.csv') }}';
            let excelButtonTrans = '{{ trans('global.datatables.excel') }}';
            let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}';
            let printButtonTrans = '{{ trans('global.datatables.print') }}';
            let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}';

            let languages = {
                'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
            };

            $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, {
                className: 'btn'
            });

            $.extend(true, $.fn.dataTable.defaults, {
                stateSave: true,
                language: {
                    url: languages['{{ app()->getLocale() }}']
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
                ],
                select: {
                    style: 'multi+shift',
                    selector: 'td:first-child'
                },
                order: [],
                scrollX: true,
                pageLength: 100,
                dom: 'lBfrtip<"actions">',
                buttons: [{
                        extend: 'copy',
                        className: 'btn btn-primary',
                        text: '<i class="fas fa-copy"></i> ' + copyButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-success',
                        text: '<i class="fas fa-file-csv"></i> ' + csvButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(csv) {
                            const title = 'Logistica y Administracion';
                            const header = `"${title}"\n\n`;
                            csv = header + csv;
                            return csv;
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-success',
                        text: '<i class="fas fa-file-excel"></i> ' + excelButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            var logo =
                                'data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo/load.png'))) }}';

                            // Add logo at the top
                            var rows = sheet.getElementsByTagName('row');
                            var newRow = sheet.createElement('row');
                            newRow.setAttribute('r', 1);
                            var newCell = sheet.createElement('c');
                            newCell.setAttribute('t', 'inlineStr');
                            newCell.setAttribute('r', 'A1');
                            var is = sheet.createElement('is');
                            var t = sheet.createElement('t');
                            t.textContent = 'Logistica y Administracion';
                            is.appendChild(t);
                            newCell.appendChild(is);
                            newRow.appendChild(newCell);
                            sheet.getElementsByTagName('sheetData')[0].insertBefore(newRow, rows[
                                0]);

                            // Style cells
                            $('row c[r]', sheet).attr('s',
                                '42'); // Apply a style index for better formatting
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger',
                        text: '<i class="fas fa-file-pdf"></i> ' + pdfButtonTrans,
                        download: 'open',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        title: 'Logistica y Administracion',
                        customize: function(doc) {
                            doc.content.splice(0, 0, {
                                alignment: 'center',
                                margin: [0, 0, 0, 20],
                                image: 'data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo/load.png'))) }}',
                                width: 120
                            });
                            doc.styles.tableHeader = {
                                fillColor: '#f2f2f2',
                                color: '#333',
                                alignment: 'center',
                                bold: true,
                                fontSize: 10
                            };
                            doc.styles.tableBodyEven = {
                                fillColor: '#f9f9f9'
                            };
                            doc.styles.tableBodyOdd = {
                                fillColor: '#ffffff'
                            };
                            doc.styles.title = {
                                alignment: 'center',
                                fontSize: 14,
                                bold: true,
                                margin: [0, 0, 0, 10]
                            };
                            doc.styles.defaultStyle = {
                                fontSize: 9,
                                alignment: 'center'
                            };
                        },
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(doc) {
                            doc.styles.tableHeader = {
                                alignment: 'center',
                                bold: true,
                                fontSize: 10,
                                color: '#333',
                                fillColor: '#f2f2f2'
                            };
                            doc.styles.tableBodyEven = {
                                alignment: 'center',
                                fillColor: '#f9f9f9'
                            };
                            doc.styles.tableBodyOdd = {
                                alignment: 'center',
                                fillColor: '#ffffff'
                            };
                            doc.styles.defaultStyle = {
                                alignment: 'center',
                                fontSize: 9
                            };
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-warning',
                        text: '<i class="fas fa-print"></i> ' + printButtonTrans,
                        title: '',
                        customize: function(win) {
                            $(win.document.body)
                                .css({
                                    'font-family': 'Arial, sans-serif',
                                    'font-size': '10pt',
                                    'line-height': '1.6',
                                    'margin': '20px'
                                })
                                .prepend(
                                    '<h1 style="text-align:center; font-size: 18pt; margin-bottom: 20px;">Logistica y Administracion</h1>'
                                )
                                .prepend('<img src="' + logo +
                                    '" style="display:block; margin: 0 auto 20px; width:120px;"/>'
                                );

                            $(win.document.body).find('table').css({
                                'border-collapse': 'collapse',
                                'width': '100%',
                                'margin-top': '20px'
                            }).find('th').css({
                                'background-color': '#f2f2f2',
                                'color': '#333',
                                'border': '1px solid #ddd',
                                'padding': '10px',
                                'text-align': 'center'
                            });

                            $(win.document.body).find('table').find('td').css({
                                'border': '1px solid #ddd',
                                'padding': '8px',
                                'text-align': 'center'
                            });
                        },
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'colvis',
                        className: 'btn btn-secondary',
                        text: '<i class="fas fa-columns"></i> ' + colvisButtonTrans
                    }
                ]
            });

            $.fn.dataTable.ext.classes.sPageButton = '';
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleButton = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');

            const lightModeColors = {
                background: '#ffffff',
                text: '#000000',
                buttonBackground: '#f0f0f0',
                buttonText: '#000000'
            };

            const darkModeColors = {
                background: '#23282c',
                text: '#e0e0e0',
                buttonBackground: '#23282c',
                buttonText: '#e0e0e0'
            };

            function applyColors(mode) {
                const colors = mode === 'dark' ? darkModeColors : lightModeColors;
                document.body.style.backgroundColor = colors.background;
                document.body.style.color = colors.text;
                themeToggleButton.style.backgroundColor = colors.buttonBackground;
                themeToggleButton.style.color = colors.buttonText;
            }

            // Inicializar modo
            const savedTheme = localStorage.getItem('theme') || 'light';

            // Asegura que se aplique la clase correctamente
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
            }

            // Aplicar ícono y colores
            themeIcon.classList.toggle('fa-moon', savedTheme === 'dark');
            themeIcon.classList.toggle('fa-sun', savedTheme !== 'dark');
            applyColors(savedTheme);

            // Botón toggle
            themeToggleButton.addEventListener('click', () => {
                const isDarkMode = document.body.classList.toggle('dark-mode');
                const currentMode = isDarkMode ? 'dark' : 'light';

                themeIcon.classList.toggle('fa-moon', isDarkMode);
                themeIcon.classList.toggle('fa-sun', !isDarkMode);
                localStorage.setItem('theme', currentMode);
                applyColors(currentMode);
            });
        });
    </script>

    @yield('scripts')
</body>

</html>
