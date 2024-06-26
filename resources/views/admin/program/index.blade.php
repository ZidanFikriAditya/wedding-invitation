@php
    $auth = Auth::user();
@endphp

<x-admin-layout>
    <x-slot name="styles">
        <script src="/assets/js/helpers.js"></script>
    </x-slot>
    <x-slot name="breadcrumbs" :route="[
        ['name' => 'Dashboard', 'route' => 'admin.index'],
        ['name' => 'Edit Acara'],
    ]"></x-slot>

    <div class="card">
        <div class="card-header bg-white border-bottom">
            <h4 class="card-title">Edit Acara</h4>
        </div>
        <div class="card-body">
            @if ($msg = session()->get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ $msg }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($msg = session()->get('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ $msg }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('admin.acara.store') }}" method="POST" id="submit-form-program">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <x-bootstrap-input :value="old('name') ?? $auth->program?->name" name="name" placeholder="Name ..." type="text" label="Nama Acara" required />
                    </div>
                    <div class="col-md-4 mb-2">
                        <x-bootstrap-input :value="old('phone_number') ?? $auth->program?->phone_number" name="phone_number" placeholder="Nomor telepon..." type="text" label="Nomor HP" />
                    </div>
                    <div class="col-md-4 mb-2">
                        <x-bootstrap-input :value="old('address') ?? $auth->program?->address" name="address" placeholder="Alamat..." type="text" label="Alamat" />
                    </div>
                    <div class="col-md-12 mb-2">
                        <x-bootstrap-input :value="old('description') ?? $auth->program?->description" name="description" placeholder="Deskripsi..." type="textarea" label="Deskripsi" />
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Sosial Media</label>
                        <div id="media-sosial-wrapper">
                            @if (old('type_medsos') || $auth->program?->social_media)
                                @php
                                    $type_medsos = array_keys(old('type_medsos') ?? $auth->program?->social_media ?? []);
                                    $iteration = 0;
                                @endphp
                                @foreach ((old('type_medsos') ?? $auth->program?->social_media) ?? [] as $key => $item)
                                    <div class="row item-sosmed mb-3" id="item-sosmed-{{ $iteration }}">
                                        <div class="col-2 col-lg-1">
                                            <div onclick="showModalSosmed(`{{ $iteration }}`)" id="label-icon-medsos-{{ $iteration }}" class="btn btn-info"><i class="ti ti-{{ (isset(old('type_medsos')[$iteration]) || $key) ? 'brand-' . (old('type_medsos')[$iteration] ?? $key) : 'messages' }}"></i></div>
                                            <input type="hidden" name="type_medsos[]" id="icon-medsos-{{ $iteration }}" value="{{ (isset(old('type_medsos')[$iteration]) || $key) ? old('type_medsos')[$iteration] ?? $key : '' }}" required>
                                        </div>
                                        <div class="col-7 col-lg-9">
                                            <x-bootstrap-input name="social_media[]" placeholder="Sosial media..." value="{{ (isset(old('social_media')[$iteration]) || $item) ? old('social_media')[$iteration] ?? $item : '' }}" type="text" />
                                        </div>
                                        <div class="col-3 col-lg-2">
                                            <button type="button" class="btn btn-danger delete-button-sosmed" onclick="handleDeleteItem({{ $iteration }})">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary add-button-sosmed" style="display: {{ end($type_medsos) == $key ? 'inline' : 'none'; }}" onclick="handleAddItem({{ $iteration }})">
                                                <i class="ti ti-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    @php
                                        $iteration++;
                                    @endphp
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <x-bootstrap-input type="select" name="template_letter_id" label="Template Surat" :url="route('admin.select2.template-undangan')"  onchange="handleChangeSelectTemplate(event)">
                            @if (old('template_letter_id') || $auth->program?->templateLetter?->id)
                                <option value="{{ old('template_letter_id') ?? md5("--" . $auth->program?->templateLetter?->id . "--") }}">{{ old('template_letter_name') ?? $auth->program?->templateLetter?->title }}</option>
                            @endif
                        </x-bootstrap-input>
                        <input type="hidden" name="template_letter_name" value="{{ old('template_letter_name') }}">
                    </div>

                    <div class="col-12" id="utils-template-letter">

                    </div>
                </div>
            </form>
        </div>

        <div class="card-footer bg-white border-top text-end">
            <button type="submit" class="btn btn-primary" onclick="$('#submit-form-program').submit()">Simpan</button>
        </div>
    </div>

    <div class="modal fade" id="modal-select-sosmed" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Pilih Media Sosial</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="select-medsos" class="form-label">Media Sosial</label>
                <select id="select-media-social" class="form-control">
                    @foreach (\App\Http\Helpers\MedsosEnum::getMedsosList() as $item)
                        <option value="{{ $item }}">{{ \App\Http\Helpers\MedsosEnum::getMedsosName($item) }}</option>
                    @endforeach
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="btn-modal-medsos-save">Save changes</button>
            </div>
          </div>
        </div>
      </div>

    <x-slot name="scripts">
        <script>
            let iterationItem = parseInt('{{ count(old('type_medsos') ?? $auth->program?->social_media ?? []) }}');
            const others = @json($auth->program?->others)
            
            $(document).ready(function () {
                @if(old('template_letter_id') || $auth->program?->templateLetter?->id) 
                    $('#template_letter_id').trigger('change');
                @endif
            })

            function addMediaSosial () {
                let mediaSosialWrapper = $('#media-sosial-wrapper');
                let mediaSosial = `
                        <div class="row item-sosmed mb-3" id="item-sosmed-${iterationItem}">
                            <div class="col-2 col-lg-1">
                                <div onclick="showModalSosmed(\`${iterationItem}\`)" id="label-icon-medsos-${iterationItem}" class="btn btn-info"><i class="ti ti-messages"></i></div>
                                <input type="hidden" name="type_medsos[]" id="icon-medsos-${iterationItem}" required>
                            </div>
                            <div class="col-7 col-lg-9">
                                <x-bootstrap-input name="social_media[]" placeholder="Sosial media..." type="text" />
                            </div>
                            <div class="col-3 col-lg-2">
                                <button type="button" class="btn btn-danger delete-button-sosmed" onclick="handleDeleteItem(${iterationItem})">
                                    <i class="ti ti-trash"></i>
                                </button>
                                <button type="button" class="btn btn-primary add-button-sosmed" onclick="handleAddItem(${iterationItem})">
                                    <i class="ti ti-plus"></i>
                                </button>
                            </div>
                        </div>
                `;

                mediaSosialWrapper.append(mediaSosial);

                iterationItem++;
            }

            function handleAddItem (iteration) {
                $(`#item-sosmed-${iteration} .add-button-sosmed`).hide();
                addMediaSosial();
            }

            function showModalSosmed (iteration) {
                $('#modal-select-sosmed').modal('show');

                $('#select-media-social').val($(`#icon-medsos-${iteration}`).val());

                $('#btn-modal-medsos-save').off('click').on('click', function () {
                    let selectedMedsos = $('#select-media-social').val();
                    if (!selectedMedsos) {
                        $(`#label-icon-medsos-${iteration}`).html(`<i class="ti ti-messages"></i>`);
                    } else {
                        $(`#icon-medsos-${iteration}`).val(selectedMedsos);
                        $(`#label-icon-medsos-${iteration}`).html(`<i class="ti ti-brand-${selectedMedsos}"></i>`);
                        $('#modal-select-sosmed').modal('hide');
                    }
                })
            }

            function handleDeleteItem (iteration) {
                let iterationItem = 0;
                $(`.item-sosmed`).each(function (){
                    iterationItem++;
                })

                if (iterationItem == 1) {
                    alert('Tidak bisa menghapus item terakhir');
                    return;
                } else {
                    $(`#item-sosmed-${iteration}`).remove();

                    $('.add-button-sosmed').hide();

                    $(`.item-sosmed`).each(function (i, e){
                        if (i == (iterationItem - 2)) {
                            $(this).find('.add-button-sosmed').show();
                        }
                    })
                }

            }

            function handleChangeSelectTemplate(event) {
                const $this = $(event.target);
                const rootView = $('#utils-template-letter');

                // set text on oldValue
                $('input[name="template_letter_name"]').val($this.find('option:selected').text());
                
                if ($this.val()) {
                    const route = '{{ route('admin.acara.template-undangan', ':id') }}'.replace(':id', $this.val());
                    $.get(route, null, function (response) {
                        const {data, message, status} = response;

                        let html = '<table class="table table-striped mt-3">'
                        $.each(data?.legends, function(index, value) {
                            const valueLegend = others?.legends[value?.legend]

                            if (value?.type == 'image') {
                                html += `<tr><th>${value?.description}</th><td width="20" class="text-center">:</td><td>
                                    <x-bootstrap-input data-name="${value?.legend}" placeholder="${value?.legend}" type="file" accept="image/*" onchange="saveImage(event)" />
                                    <a href="{{ url('storage') }}/${valueLegend}" target="_blank" id="value_image_${value?.legend}" class="btn btn-primary btn-sm" style="${!valueLegend && 'display: none;'}">Lihat</a>
                                    <input type="hidden" name="legend_${value?.legend}" data-name="${value?.legend}" value="${valueLegend ?? null}" class="legends_input">
                                    </td></tr>`;
                            } else {
                                html += `<tr><th>${value?.description}</th><td width="20" class="text-center">:</td><td><x-bootstrap-input name="legend_${value?.legend}" data-name="${value?.legend}" class="legends_input" value="${valueLegend}" placeholder="${value?.legend}" type="${value?.type}" onchange="renderView()" /></td></tr>`;
                            }
                        })
                        html += '</table>';

                        rootView.html(html);

                        window.letterId = $this.val();

                        let url = '{{ route('admin.preview.html', ':id') }}?'.replace(':id', $this.val());
                        rootView.append(`<embed src="${url}" id="preview-html-letter" class="w-100" height="1000"></embed>`)

                        renderView();
                    })
                } else {
                    rootView.html('');
                }
            }

            function renderView() {
                let url = '{{ route('admin.preview.html', ':id') }}?'.replace(':id', window.letterId ?? '');
                $('.legends_input').each(function (index, value) {
                    url += `${$(this).attr('data-name')}=${$(this).val() ?? $(this).attr('data-name')}&`;
                })
                $('#preview-html-letter').attr('src', url);
            }

            function saveImage(event) {
                $('.data-errors').remove();
                const $this = $(event.target);
                const name = $this.attr('data-name');
                const file = $this[0].files[0];
                const formData = new FormData();
                formData.append('image', file);
                formData.append('legend', name);

                $.ajax({
                    url: '{{ route('admin.acara.save-image') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        const {data, message, status} = response;
                        if (status) {
                            $(`input[name="legend_${name}"]`).val(data?.path);
                            console.log(data?.path);
                            // $(`#value_image_${name}`).slideDown();
                            // $(`#value_image_${name}`).attr('href', `{{ url('storage') }}/${data?.path}`);
                            renderView();
                        } else {
                            alert(message);
                        }
                    },
                    error: function (err) {
                        $(`input[name="legend_${name}"]`).val('');
                        $(`input[name="legend_${name}"]`).after(`<div class="text-danger data-errors">${err?.responseJSON?.message}</div>`);
                    }
                })
            }

            if (iterationItem == 0) {
                addMediaSosial();
            }
        </script>
    </x-slot>
</x-admin-layout>
