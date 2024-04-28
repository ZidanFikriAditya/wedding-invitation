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
                    <a href="" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-import-excel">
                        <i class="fa fa-file"></i>Import Excel
                    </a>
                    <a class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#modal-add-invitation">
                        Tambah Ke Pengirim
                    </a>
                </div>
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-borders" id="table-letter-template" data-url="{{ route('admin.undangan.data') }}">
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

    <div class="modal fade" id="modal-import-excel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Import Excel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <button class="btn btn-success"><i class="ti ti-file"></i> Download Format</button>
                </div>
                <div class="col-12">
                    <x-bootstrap-input name="file" placeholder="Deskripsi..." type="file" label="Excel File" />
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="modal-add-invitation" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tambakan Undangan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-dismissible show" style="display: none" id="alert-bootstrap" role="alert">
                <strong>!</strong><span></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="row" id="body-add-invitation">
                
            </div>
            <div class="">
                <button class="btn btn-primary" onclick="addInvitations()">Tambah</button>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="submitInvitation()">Save changes</button>
        </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="modal-edit-invitation" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Undangan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-dismissible show" style="display: none" id="alert-bootstrap" role="alert">
                <strong>!</strong><span></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="row" id="body-edit-invitation">
                
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="submitInvitation('edit')">Save changes</button>
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
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        }
                    },
                    columns: [
                        { data: 'letter_number', name: 'letter_number' },
                        { data: 'receiver_name', name: 'receiver_name' },
                        { data: 'status', name: 'status' },
                        { data: 'action', name: 'action' }
                    ]
                });
            }

            $(document).ready(function() {
                initTable();

                $('#modal-edit-invitation').on('show.bs.modal', dataDetail)
            });

            function addInvitations() {
                const getBody = document.getElementById('body-add-invitation');
                let html = `
                    <div class="col-12 d-flex align-items-end items-invitation mb-3" id="item-invitation-${indexOfInvitation}" style="gap: 5px;">
                        <x-bootstrap-input name="name[${indexOfInvitation}]" placeholder="Nama ..." type="text" label="Nama ${indexOfInvitation+1}" required />
                        <x-bootstrap-input name="number[${indexOfInvitation}]" placeholder="Number ..." type="text" label="Number Phone ${indexOfInvitation+1}" required />
                        <button class="btn btn-danger" onclick="deleteItem(${indexOfInvitation})"><i class="ti ti-trash"></i></button>
                        <span data-error="name.${indexOfInvitation}"></span>
                    </div>
                `;

                const parser = new DOMParser();

                const parsedHtml = parser.parseFromString(html, 'text/html');

                getBody.appendChild(parsedHtml.body.firstChild);

                indexOfInvitation++;
            }

            function deleteItem(index) {
                const getItem = document.getElementById(`item-invitation-${index}`);

                getItem.remove();
            }

            function submitInvitation(type = 'add') {
                const form = type == 'add' ? document.getElementById('body-add-invitation') : document.getElementById('body-edit-invitation');
                const formData = new FormData();
                form.querySelectorAll('input').forEach((input) => {
                    formData.append(input.name, input.value);
                });
                formData.append('_token', '{{ csrf_token() }}');
                if (type == 'edit') {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: type == 'add' ? '{{ route('admin.undangan.store') }}' : form.getAttribute('data-url'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 201 || response.status == 200) {
                            showAlert(response.message, 'success');
                        } else {
                            showAlert(response.message, 'error');                            
                        }

                        if (type == 'edit') {
                            $('#modal-edit-invitation').modal('hide');
                        } else {
                            $('#modal-add-invitation').modal('hide');
                        }
                        window.table.ajax.reload();
                    },
                    error: function(error) {
                        showAlert(error.responseJSON.message, 'error');
                    }
                })
            }

            function dataDetail (e) {
                const button = $(e.relatedTarget);
                const id = button.data('id');
                
                $('#body-edit-invitation').attr('data-url', '{{ route("admin.undangan.update", ":id") }}'.replace(':id', id));

                $.get('{{ route("admin.undangan.show", ":id") }}'.replace(":id", id), {}, function (response) {
                    $('#body-edit-invitation').html(response.data);
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

            addInvitations();

        </script>
    </x-slot>
</x-admin-layout>