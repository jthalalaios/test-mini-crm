<div class="container">
    <h2>{{ $title ?? 'Datatable' }}</h2>
    <table class="table table-bordered" id="{{ $tableId ?? 'datatable-table' }}">
        <thead>
            <tr>
                @foreach($columns as $col)
                    <th>
                        @if(isset($columnTranslations) && isset($columnTranslations[$col]))
                            {{ $columnTranslations[$col] }}
                        @else
                            {{ __("messages.$col") }}
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
    </table>
</div>

@push('scripts')
<!-- DataTables CSS/JS via CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    /* Center all table headers and cells */
    #{{ $tableId ?? 'datatable-table' }} th,
    #{{ $tableId ?? 'datatable-table' }} td {
        text-align: center !important;
        vertical-align: middle !important;
    }
    #{{ $tableId ?? 'datatable-table' }} tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    #{{ $tableId ?? 'datatable-table' }} tbody tr:hover {
        background-color: #e6f7ff;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.3em 0.8em;
        margin-left: 2px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        background: #f8f9fa;
        color: #333;
        transition: background 0.2s, color 0.2s;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #007bff;
        color: #fff !important;
        border: 1px solid #007bff;
    }
    .dataTables_filter input[type="search"] {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 0.3em 0.8em;
        margin-left: 0.5em;
    }
    .dataTables_length select {
        border-radius: 4px;
        border: 1px solid #ced4da;
        padding: 0.2em 0.5em;
        margin-left: 0.5em;
    }
</style>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
$(function() {
    var translations = {};
    @if(isset($columnTranslations))
        translations = @json($columnTranslations);
    @endif
    translations.no_image = @json(__('messages.no_image'));
    translations.logo = @json(__('messages.logo'));

    // Build columns array dynamically and set searchable from model
    var columns = [];
    @php
        $searchable = [];
        if (class_exists($model ?? '')) {
            $searchable = (new ($model ?? ''))->searchable_fields();
        }
    @endphp
    @foreach($columns as $col)
        @php $isSearchable = in_array($col, $searchable); @endphp
        @if($col === 'display_logo')
            columns.push({
                data: 'display_logo', name: 'display_logo', orderable: false, searchable: false, title: translations.logo,
                render: function(data) {
                    if (data) {
                        return '<img src="' + data + '" style="height:50px; width:auto; object-fit:contain;">';
                    }
                    return translations.no_image;
                }
            });
        @elseif($col === 'website')
            columns.push({
                data: 'website', name: 'website', title: translations.website, searchable: {{ $isSearchable ? 'true' : 'false' }},
                render: function(data) {
                    if (data) {
                        return '<a href="' + data + '" target="_blank">' + data + '</a>';
                    }
                    return '';
                }
            });
        @elseif($col === 'company_name')
            columns.push({
                data: 'company_name', name: 'company_name', title: translations.company_name || 'Company', searchable: {{ $isSearchable ? 'true' : 'false' }},
                render: function(data, type, row) {
                    if (data && row.company_id) {
                        var url = '/companies/' + row.company_id + '/edit';
                        return '<a href="' + url + '">' + data + '</a>';
                    }
                    return data || '';
                }
            });
        @elseif($col === 'created_at')
            columns.push({
                data: 'created_at', name: 'created_at', title: translations.created_at, searchable: {{ $isSearchable ? 'true' : 'false' }},
                render: function(data) {
                    if (data) {
                        return moment(data).format('YYYY-MM-DD HH:mm');
                    }
                    return '';
                }
            });
        @elseif($col === 'actions')
            columns.push({
                data: 'actions', name: 'actions', orderable: false, searchable: false, title: translations.actions
            });
        @else
            columns.push({ data: '{{ $col }}', name: '{{ $col }}', title: translations['{{ $col }}'] || '{{ $col }}', searchable: {!! $isSearchable ? 'true' : 'false' !!} });
        @endif
    @endforeach
    function getDatatablesLangUrl(locale) {
        switch (locale) {
            case 'el':
            case 'gr':
                return '//cdn.datatables.net/plug-ins/1.13.6/i18n/el.json';
            case 'en':
            default:
                return '//cdn.datatables.net/plug-ins/1.13.6/i18n/en-GB.json';
        }
    }

    function getSelectedLocale() {
        // Try to find the language selector in the navbar
        var select = document.querySelector('form#language-form select[name="locale"]');
        if (select) {
            return select.value;
        }
        return 'en';
    }

    function initDataTable() {
        var locale = getSelectedLocale();
        $('#{{ $tableId ?? 'datatable-table' }}').DataTable({
            processing: true,
            serverSide: true,
            columns: columns, // Ensure columns array is passed
            ajax: {
                url: '{{ $ajaxUrl }}',
                data: function(d) {
                    // Per-column search: only for searchable fields from model
                    var searchable = [];
                    @if (class_exists($model ?? ''))
                        searchable = @json((new ($model ?? ''))->searchable_fields());
                    @endif
                    if (d.columns && d.columns.length) {
                        d.columns.forEach(function(col) {
                            if (searchable.includes(col.data)) {
                                var val = '';
                                // Use column search if set, otherwise use global search
                                if (col.search && col.search.value) {
                                    val = col.search.value;
                                } else if (d.search && d.search.value) {
                                    val = d.search.value;
                                }
                                if (val) {
                                    d['filter[' + col.data + ']'] = val;
                                }
                            }
                        });
                    }
                    // Pagination
                    d.items = d.length;
                    d.page = Math.floor(d.start / d.length) + 1;

                    // Spatie-style multi-column sorting: sort[-name]&sort[email]
                    if (d.order && d.order.length > 0) {
                        d.sort = [];
                        d.order.forEach(function(ord) {
                            var colName = d.columns[ord.column].data;
                            var sortDir = ord.dir == 'desc' ? '-' : '';
                            d.sort.push(sortDir + colName);
                        });
                    }
                }
            },
            columns: columns,
            language: {
                url: getDatatablesLangUrl(locale)
            },
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10
        });
    }

    // Re-initialize DataTable on language change
    var langSelect = document.querySelector('form#language-form select[name="locale"]');
    if (langSelect) {
        langSelect.addEventListener('change', function() {
            var table = $('#{{ $tableId ?? 'datatable-table' }}').DataTable();
            table.destroy();
            setTimeout(initDataTable, 100); // Wait for DOM update
        });
    }

    initDataTable();
});
</script>
@endpush