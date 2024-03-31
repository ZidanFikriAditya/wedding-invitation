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
            <div class="row justify-content-between">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search..." id="search">
                    </div>
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
                    ],
                    searching: false,
                    paging: false,
                });
            });

            let timeout;
            $('#search').on('keyup', function () {
                clearTimeout(timeout);

                timeout = setTimeout(() => {
                    console.log('searching');
                    $('#table-letter-template').DataTable().search($(this).val()).draw();
                }, 500);
            });

            function handleDelete(id) {
                ZiApp.api({url: 'route("admin.template-undangan.destroy", ":id")'.replace(':id', id), method: 'delete'}, (res, status) => {
                    if (status) {
                        $('#table-letter-template').DataTable().ajax.reload();
                    }
                })
            }
        </script>
    </x-slot>
</x-admin-layout>