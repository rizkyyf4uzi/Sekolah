@extends('layouts.admin')

@section('title', 'Buku Tamu - TK Harapan Bangsa 1')
@section('breadcrumb', 'Data Buku Tamu')

@section('content')
<div class="space-y-6">
    @livewire('admin.buku-tamu-index')
</div>

<script>
    function confirmDeleteBukuTamu(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data kunjungan akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6B46C1',
            cancelButtonColor: '#EF4444',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            borderRadius: '1.25rem'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = `/admin/bukutamu/${id}`;
                form.method = 'POST';
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        })
    }
</script>
@endsection

@push('scripts')
<script>
    function exportData() {
        Swal.fire({
            title: 'Export Data Buku Tamu',
            html: `
                <form id="exportForm">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Export Excel',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('exportForm');
                const params = new URLSearchParams(new FormData(form)).toString();
                window.location.href = '{{ route("admin.bukutamu.export") }}?' + params;
            }
        });
    }
</script>
@endpush