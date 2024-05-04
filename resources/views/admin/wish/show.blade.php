<div class="row">
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" value="{{ $wish->letterInvitation?->receiver_name }}" readonly>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label for="name">Dan</label>
            <input type="text" class="form-control" id="name2" value="{{ $wish->other_people ?? '-' }}" readonly>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label for="name">Wish</label>
            <textarea class="form-control" id="wish" rows="3" readonly>{{ $wish->wishes }}</textarea>
        </div>
    </div>
    <div class="col-md-12 mb-3">
        <div class="form-group">
            <label for="name">Kehadiran</label>
            <input type="text" class="form-control" id="name2" value="{{ ucfirst($wish->confirmation) }}" readonly>
        </div>
    </div>
</div>