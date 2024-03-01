<x-admin-layout>
    <x-slot name="breadcrumbs" :route="[
        [
            'route' => 'admin.index',
            'name' => 'Dashboard'
        ],
        [
            'route' => 'admin.template-undangan.index',
            'name' => 'Template Undangan'
        ]
    ]"></x-slot>

    <x-slot name="title">
        Template Undangan
    </x-slot>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mb-3">

                </div>
                <div class="col-md-4 mb-3 text-end">
                    <a class="btn btn-primary" href="{{ route('admin.template-undangan.create') }}">
                        Tambah Template
                    </a>
                </div>
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-borders" id="table-letter-template" data-url="{{ route('admin.template-undangan.data') }}">
                            <thead>
                                <tr>
                                    <th>Title Letter</th>
                                    <th>Pemilik</th>
                                    <th>Preview</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <x-slot name="scripts">
        <script src="/assets/js/helpers.js"></script>
        <script>
            $(document).ready(function() {
                ZiApp.DataTable({
                    element: $('#table-letter-template'),
                    columns: [
                        { data: 'title', name: 'title' },
                        { data: 'owner_name', name: 'owner.name' },
                        { data: 'action', name: 'action', searchable: false, orderable: false}
                    ]
                });
            });

            function handleDelete(id) {
                
            }
        </script>
    </x-slot>
</x-admin-layout>