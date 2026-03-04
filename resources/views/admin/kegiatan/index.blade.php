@extends('layouts.admin')

@section('title', 'Kegiatan Sekolah')
@section('breadcrumb', 'Informasi Publik / Kegiatan Sekolah')

@section('content')
    <livewire:admin.kegiatan-index />

    <form id="delete-kegiatan-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
<script>
    function confirmDeleteKegiatan(id) {
        if (confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')) {
            const form = document.getElementById('delete-kegiatan-form');
            form.action = `/admin/kegiatan/${id}`;
            form.submit();
        }
    }
</script>
@endpush
