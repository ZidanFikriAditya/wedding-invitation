<x-admin-layout>

<div class="row">
    <div class="col-lg-12">
      <div class="row">
        <div class="col-lg-4">
          <div class="card overflow-hidden">
            <div class="card-body p-4">
              <h5 class="card-title mb-9 fw-semibold">Hadir Di Acara</h5>
              <div class="row align-items-center">
                <div class="col-12">
                  <h4 class="fw-semibold mb-3">{{ $attend }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <!-- Yearly Breakup -->
          <div class="card overflow-hidden">
            <div class="card-body p-4">
              <h5 class="card-title mb-9 fw-semibold">Tidak Hadir</h5>
              <div class="row align-items-center">
                <div class="col-12">
                  <h4 class="fw-semibold mb-3">{{ $notAttend }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <!-- Yearly Breakup -->
          <div class="card overflow-hidden">
            <div class="card-body p-4">
              <h5 class="card-title mb-9 fw-semibold">Total Undangan</h5>
              <div class="row align-items-center">
                <div class="col-12">
                  <h4 class="fw-semibold mb-3">{{ $totalInvitation }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <x-slot name="scripts">
  <script src="{{ url('assets') }}/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="{{ url('assets') }}/libs/simplebar/dist/simplebar.js"></script>
  <script src="{{ url('assets') }}/js/dashboard.js"></script>
  </x-slot>
</x-admin-layout>