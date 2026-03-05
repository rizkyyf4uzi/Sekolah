{{-- resources/views/admin/galeri/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Galeri Sekolah')

@section('content')
<livewire:admin.galeri-index />

{{-- Hidden Delete Form --}}
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus galeri ini? Semua gambar akan ikut terhapus.')) {
        const form = document.getElementById('delete-form');
        form.action = `/admin/galeri/${id}`;
        form.submit();
    }
}
</script>
@endpush
@endsection