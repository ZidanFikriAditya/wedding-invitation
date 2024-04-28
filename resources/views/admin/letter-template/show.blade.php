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
            'name' => 'Edit'
        ]
    ]"></x-slot>

    <x-slot name="title">
        Edit New Template
    </x-slot>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.template-undangan.update', md5("--$model->id--")) }}" method="POST" enctype="multipart/form-data">
                <div class="row">
                    @csrf
                    @method('PUT')
                    <div class="col-md-6 mb-3">
                        <x-bootstrap-input type="text" name="title" label="Judul" placeholder="Judul..." required :value="(old('title') ?? $model->title)" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-bootstrap-input type="file" name="upload_zip" label="Upload Template" id="upload_zip" accept=".zip" />
                        @if ($model->uploadTemplateLetter)
                            <span class="text-primary">Template sudah ter upload!</span>
                        @endif
                    </div>

                    <div class="col-md-12 mb-3">
                        <span class="fw-bolder text-dark">Legend</span>

                        <hr>

                        <div id="legends-section">
                            @if (old('legends') && count(old('legends')) > 0)
                                @foreach (old('legends') ?? [] as $key => $index)
                                    <div class="row" id="row_of_legend_{{ $index }}">
                                        <div class="col-md-4 mb-3">
                                            <input type="hidden" name="legends[]" value="{{ $index }}">
                                            <x-bootstrap-input type="text" name="legend_name[{{ $index }}]" label="Legend Name" placeholder="Legend..." onkeyup="handleChangeLegendItems(this, {{ $index }})" :value="(old('legend_name')[$index] ?? '')" />
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <x-bootstrap-input type="text" name="legend[{{ $index }}]" id="legend_{{ $index }}" label="Legend" placeholder="Legend..." style="background-color: #EBEBE4; color: #000;" readonly :value="(old('legend')[$index] ?? '')" />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="lagend_type_{{ $index }}" class="form-label">Type Legend <span class="text-info">*</span></label>
                                            <select name="legend_type[{{ $index }}]" id="legend_type_{{ $index }}" class="form-select">
                                                <option value="text" {{ (old('legend_type')[$index] ?? '') == 'text' ? 'selected' : '' }}>Text</option>
                                                <option value="image" {{ (old('legend_type')[$index] ?? '') == 'image' ? 'selected' : '' }}>Image</option>    
                                            </select>
                                        </div>
                                        <div class="col-md-1 mb-3 mt-0 mt-md-4">
                                            <button type="button" onclick="deleteLegends({{ $index }})" class="btn btn-danger button-delete-legends" style="{{ $index == 0 ? 'display: none;' : '' }}"><i class="ti ti-trash"></i></button>
                                        </div>
                                    </div>
                                @endforeach

                            @else
                                @foreach ($model->legends ?? [] as $index => $legend)
                                    <div class="row" id="row_of_legend_{{ $index }}">
                                        <div class="col-md-4 mb-3">
                                            <input type="hidden" name="legends[]" value="{{ $index }}">
                                            <x-bootstrap-input type="text" name="legend_name[{{ $index }}]" label="Legend Name" placeholder="Legend..." onkeyup="handleChangeLegendItems(this, {{ $index }})" :value="($model->legends[$index]->description)" />
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <x-bootstrap-input type="text" name="legend[{{ $index }}]" id="legend_{{ $index }}" label="Legend" placeholder="Legend..." style="background-color: #EBEBE4; color: #000;" readonly :value="($model->legends[$index]->legend)" />
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="lagend_type_{{ $index }}" class="form-label">Type Legend <span class="text-info">*</span></label>
                                            <select name="legend_type[{{ $index }}]" id="legend_type_{{ $index }}" class="form-select">
                                                <option value="text" {{ ($model->legends[$index]->type) == 'text' ? 'selected' : '' }}>Text</option>
                                                <option value="image" {{ ($model->legends[$index]->type) == 'image' ? 'selected' : '' }}>Image</option>    
                                            </select>
                                        </div>
                                        <div class="col-md-1 mb-3 mt-0 mt-md-4">
                                            <button type="button" onclick="deleteLegends({{ $index }})" class="btn btn-danger button-delete-legends" style="{{ $index == 0 ? 'display: none;' : '' }}"><i class="ti ti-trash"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        
                        <button type="button" class="btn btn-primary btn-sm" onclick="addLegends()">Add Legend</button>
                    </div>

                    <div class="col-md-12 mb-3">
                        <x-bootstrap-input type="textarea" name="body" label="Convert Body" rows="10" id="body_before" placeholder="Body Converter..." :value="$model->body" />
                    </div>

                    <div class="col-md-12 mb-3">
                        <button type="button" class="btn btn-info" onclick="handleChangeSummernote()">Generate Letter Template</button>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="fw-bold text-dark">Preview Template</div>
                        <div  id="preview-template">
                            <embed src="{{ route('admin.preview.html',  md5("--$model->id--")) }}" id="preview-html-letter" class="w-100" height="1000"></embed>
                        </div>
                        <input type="hidden" name="is_click_generate_template">
                    </div>


                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            @if (session('success'))
                showToast('success', '{{ session('success') }}')
            @endif

            let indexLegend = {{ old('legends') ? count(old('legends')) : $model->legends->count() }}
            
            $(document).ready(() => {
                
            })
            
            function handleChangeSummernote() {
                const contents = $('#body_before').val()
                const replaceStorage = contents.replaceAll('{storage}', '{{ asset("storage/" . $model->uploadTemplateLetter->path_template) }}').trim()
                
                showToast('success', 'Success generate letter template!')

                $.post('{{ route("admin.template.update-body",  md5("--$model->id--")) }}', {
                    body: replaceStorage,
                    _token: '{{ csrf_token() }}'
                }, (response) => {
                    let url = '{{ route('admin.preview.html',  md5("--$model->id--")) }}';
                    $('#preview-template').html(`<embed src="${url}" id="preview-html-letter" class="w-100" height="1000"></embed>`)
                })

                $('#body_before').val(replaceStorage)
            }

            function addLegends() {
                const legendsSection = $('#legends-section')

                const legend = `<div class="row" id="row_of_legend_${indexLegend}">
                    <div class="col-md-4 mb-3">
                        <input type="hidden" name="legends[]" value="${indexLegend}">
                        <x-bootstrap-input type="text" name="legend_name[${indexLegend}]" label="Legend Name" placeholder="Legend..." onkeyup="handleChangeLegendItems(this, ${indexLegend})" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <x-bootstrap-input type="text" name="legend[${indexLegend}]" id="legend_${indexLegend}" label="Legend" placeholder="Legend..." style="background-color: #EBEBE4; color: #000;" readonly />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="lagend_type_${indexLegend}" class="form-label">Type Legend <span class="text-info">*</span></label>
                        <select name="legend_type[${indexLegend}]" id="legend_type_${indexLegend}" class="form-select">
                            <option value="text">Text</option>
                            <option value="image">Image</option>    
                        </select>
                    </div>
                    <div class="col-md-1 mb-3 mt-0 mt-md-4">
                        <button type="button" onclick="deleteLegends(${indexLegend})" class="btn btn-danger button-delete-legends" style="display: none;"><i class="ti ti-trash"></i></button>
                    </div>
                    </div>
                `

                legendsSection.append(legend)

                let indexLegend2 = 0;

                $(`.button-delete-legends`).each(function () {
                    indexLegend2++;
                })

                if (indexLegend2 <= 1) {
                    $(`.button-delete-legends`).each(function () {
                        $(this).hide()
                    })                    
                } else {
                    $(`.button-delete-legends`).each(function () {
                        $(this).show()
                    })
                }

                indexLegend++
            }

            function deleteLegends(index) {
                $(`#row_of_legend_${index}`).remove()
                let indexLegend = 0;

                $(`.button-delete-legends`).each(function () {
                    indexLegend++;
                })

                if (indexLegend <= 1) {
                    $(`.button-delete-legends`).each(function () {
                        $(this).hide()
                    })                    
                } else {
                    $(`.button-delete-legends`).each(function () {
                        $(this).show()
                    })
                }

            }

            @if ($model->legends->count() == 0)
                addLegends()
            @endif

            function handleChangeLegendItems($this, index) {
                const legend = $(`#legend_${index}`)

                const legendModified = $($this).val().replaceAll(' ', '_').toLowerCase()

                legend.val(`{${legendModified}}`)
            }
        </script>
    </x-slot>
</x-admin-layout>