class Root {
    DataTable ({element, formData, columns, ...other}) {
        $(element).DataTable({
            processing: true,
            serverSide: true,
            language: {
                noResults: "Data tidak ditemukan",
                processing: "Loading ...",
                search: "Mencari:",
            },
            ajax: {
                url: $(element).data('url'),
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                delay: 750,
            },
            columns: columns,
            ...other
        })
    }

    select2Search ({element, url, placeholder = null, allowClear = true, multiple = false, results = 'text', dropdownParent = null, data = null}) {
        const result = results.split(',');
        $(element).select2({
            placeholder: placeholder,
            allowClear: allowClear,
            multiple: multiple,
            dropdownParent: dropdownParent,
            ajax: {
                url: url,
                dataType: 'json',
                delay: 500,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1,
                        ...data
                    }
                },
                processResults: function(data, params) {
                    params.page = params.page || 10;

                    const results = data.data.map(item => {
                        return {
                            id: item.id,
                            text: result.map(key => item[key]).join(' | ')
                        }
                    }) ?? [];

                    return {
                        results: results,
                        pagination: {
                            more: (params.page * 10) < result.length
                        }
                    }
                },
                cache: true
            }
        });
    }

    api({url, method = 'GET', data}, callback) {
        const form = {
            url,
            method,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                callback(response, true)
            },
            error: function (error) {
                callback(error, false)
            }
        }

        if (data) {
            form.data = data
        }

        $.ajax(form)
    }
}

const ZiApp = new Root()
