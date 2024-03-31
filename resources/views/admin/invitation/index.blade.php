<x-admin-layout>
    <x-slot name="breadcrumbs" :route="[
        [
            'route' => 'admin.index',
            'name' => 'Dashboard'
        ],
        [
            'route' => 'admin.undangan.index',
            'name' => 'Kirim Undangan'
        ]
    ]"></x-slot>

    <x-slot name="title">
        Template Undangan
    </x-slot>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-8 mb-3 text-end">
                    <a href="" class="btn btn-success">
                        <i class="fa fa-file"></i>Import Excel
                    </a>
                    <a class="btn btn-primary" href="{{ route('admin.template-undangan.create') }}">
                        Tambah Ke Pengirim
                    </a>
                </div>
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-borders" id="table-letter-template" data-url="{{ route('admin.template-undangan.data') }}">
                            <thead>
                                <tr>
                                    <th>Nomor Undangan</th>
                                    <th>Kepada</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>