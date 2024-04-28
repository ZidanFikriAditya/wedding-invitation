<div class="col-12 d-flex align-items-end items-invitation mb-3" id="item-invitation" style="gap: 5px;">
    <x-bootstrap-input name="name" placeholder="Nama ..." type="text" label="Nama" required :value="$model->receiver_name" />
    <span data-error="name"></span>
    <x-bootstrap-input name="number" placeholder="Number ..." type="text" label="Number Phone" required :value="$model->receiver_number" />
    <span data-error="number"></span>
</div>