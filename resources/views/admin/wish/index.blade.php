<x-admin-layout>
    <x-slot name="breadcrumbs" :route="[
        [
            'route' => 'admin.index',
            'name' => 'Dashboard'
        ],
        [
            'route' => 'admin.doa.index',
            'name' => 'Kirim Undangan'
        ]
    ]"></x-slot>

    <x-slot name="title">
        Template Undangan
    </x-slot>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:;" data-tab="datang">Datang</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="javascript:;" data-tab="tidak datang">Tidak Datang</a>
                        </li>
                      </ul>
                </div>
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-borders" id="table-letter-template" data-url="{{ route('admin.doa.index') }}">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pengirim</th>
                                    <th>Wishes</th>
                                    <th>Kehadiran</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-wishes" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Wish Detail</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row" id="body-edit-wishes">
                
            </div>
        </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="modal-delete-wishes" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Hapus Undangan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>Apakah anda yakin ingin menghapus doa ini?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-danget" onclick="deleteInvitation()">Hapus</button>
        </div>
        </div>
    </div>
    </div>

    <x-slot name="scripts">
        <script>
            let indexOfInvitation = 0;

            function initTable() {
                window.table = $('#table-letter-template').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: $('#table-letter-template').data('url'),
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}',
                            type: function () {
                                return $('.nav-link.active').data('tab');
                            }
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        { data: 'sender', name: 'sender' },
                        { data: 'wishes', name: 'wishes' },
                        { data: 'confirmation', name: 'confirmation' },
                        { data: 'action', name: 'action' }
                    ]
                });
            }

            $(document).ready(function() {
                initTable();

                $('#modal-edit-wishes').on('show.bs.modal', dataDetail)
                $('#modal-delete-wishes').on('show.bs.modal', function(e) {
                    const button = $(e.relatedTarget);
                    window.idInvitation = button.data('id');
                });
            });

            $('[data-tab]').on('click', function() {
                $('.nav-link').removeClass('active');
                $(this).addClass('active');

                window.table.ajax.reload();
            });

            function dataDetail (e) {
                const button = $(e.relatedTarget);
                const id = button.data('id');
                
                $('#body-edit-wishes').attr('data-url', '{{ route("admin.doa.show", ":id") }}'.replace(':id', id));

                $.get('{{ route("admin.doa.show", ":id") }}'.replace(":id", id), {}, function (response) {
                    $('#body-edit-wishes').html(response.data);
                })
            }

            function showAlert(message, type) {
                const alert = $('#alert-bootstrap');

                if (type == 'success') {
                    alert.addClass('alert-success');
                    alert.removeClass('alert-danger');

                    alert.find('strong').text('Success,');
                } else {
                    alert.addClass('alert-danger');
                    alert.removeClass('alert-success');
                    alert.find('strong').text('Error,');
                }

                alert.find('span').html('&nbsp;' + message);

                alert.fadeIn();
            }

            function deleteInvitation() {
                $.ajax({
                    url: '{{ route('admin.doa.destroy', ':id') }}'.replace(':id', window.idInvitation),
                    method: 'post',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status == 201 || response.status == 200) {
                            showAlert(response.message, 'success');
                        } else {
                            showAlert(response.message, 'error');                            
                        }

                        $('#modal-delete-wishes').modal('hide');
                        window.table.ajax.reload();
                    },
                    error: function(error) {
                        showAlert(error.responseJSON.message, 'error');
                    }
                })
            }

        </script>
    </x-slot>
</x-admin-layout>