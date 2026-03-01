<div class="datatable-actions d-flex justify-content-center align-items-center" style="gap: 0.5em;">
    <a href="{{ $edit }}" class="btn btn-sm btn-warning mx-1" data-toggle="tooltip" title="{{ __('messages.edit') }}">
        <i class="fas fa-pen"></i>
    </a>
    <form action="{{ $delete }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger mx-1" data-toggle="tooltip" title="{{ __('messages.delete') }}" onclick="return confirm('{{ $confirmMessage ?? __('messages.delete_confirm') }}')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
@push('scripts')
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
