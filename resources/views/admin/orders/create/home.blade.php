@extends('admin.template')
@section('content')
<section class="basic-select2">
    <div class="row">
        <!-- Congratulations Card -->
        <div class="col-12">
            <div class="card-body">
                <div class="col-xl-12">
                    <div class="card-header d-flex justify-content-between mb-2 mt-0">
                        <div class="card-title mb-0 align-middle">
                            <h5 class="card-title mb-0">Seleccionar mesa</h5>
                        </div>
                        <a href="{{ route('admin.orders') }}" class="dt-button create-new btn btn-danger waves-effect waves-light">
                            <i class="me-sm-1" data-feather='arrow-left'></i> Volver
                        </a>
                    </div>

                    <div class="nav-align-top mb-2">
                      <ul id="list-rooms" class="nav nav-pills mb-3" role="tablist">
                        @foreach ($rooms as $room)
                        <li class="nav-item" role="presentation">
                          <button type="button" data-id="{{ $room["id"] }}" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-room-{{ $room->id }}" aria-controls="navs-pills-room-{{ $room->id }}" aria-selected="true">{{ $room->descripcion }}</button>
                        </li>
                        @endforeach
                      </ul>

                      <div id="wrapper_tables" class="tab-content" style="height: 70vh;  overflow-y: auto;"></div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
@include('admin.orders.create.js-home')
@endsection
