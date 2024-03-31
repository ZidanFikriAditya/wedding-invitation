<x-admin-layout>
    <x-slot name="breadcrumbs" :route="[
        [
            'route' => 'admin.index',
            'name' => 'Dashboard'
        ],
        [
            'route' => 'admin.template-undangan.index',
            'name' => 'Template Undangan'
        ],
        [
            'route' => 'admin.template-undangan.create',
            'name' => 'Create'
        ]
    ]"></x-slot>

    <x-slot name="title">
        Create New Template
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.template-undangan.store') }}" method="POST" enctype="multipart/form-data">
                <div class="row align-items-end">
                    @csrf
                    <div class="col-md-6 mb-3">
                        <x-bootstrap-input type="text" name="title" label="Judul" placeholder="Judul..." required :value="old('title')" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-bootstrap-input type="file" name="upload_zip" label="Upload Template" id="upload_zip" required accept=".zip" />
                    </div>

                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            let indexLegend = 0

            @if (session('error'))
                showToast('danger', '{{ session('error') }}')
            @endif
        </script>
    </x-slot>
</x-admin-layout>