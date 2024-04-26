@extends('admin.template')
@section('content')
<div class="row">
    <div class="col-sm-6 col-lg-3 mb-4">
      <div class="card card-border-shadow-warning">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <div class="avatar me-2">
              <span class="avatar-initial rounded bg-label-warning"><i class="fa fa-file-invoice"></i></span>
            </div>
            <h4 class="ms-1 mb-0">{{ count($facturas) }}</h4>
          </div>
          <p class="mb-1">Facturas</p>
          <p class="mb-0">
            <small class="text-muted">Pendientes de env&iacute;o</small>
          </p>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-4">
      <div class="card card-border-shadow-danger">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <div class="avatar me-2">
              <span class="avatar-initial rounded bg-label-danger"><i class="fa fa-file-alt"></i></span>
            </div>
            <h4 class="ms-1 mb-0">{{ count($boletas) }}</h4>
          </div>
          <p class="mb-1">Boletas</p>
          <p class="mb-0">
            <small class="text-muted">Pendientes de env&iacute;o</small>
          </p>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-4">
        <div class="card card-border-shadow-primary">
          <div class="card-body">
            <div class="d-flex align-items-center mb-2 pb-1">
              <div class="avatar me-2">
                <span class="avatar-initial rounded bg-label-primary"><i class="fa fa-boxes"></i></span>
              </div>
              <h4 class="ms-1 mb-0">{{ count($stock) }}</h4>
            </div>
            <p class="mb-1">Productos</p>
            <p class="mb-0">
              <small class="text-muted">Productos por agotarse</small>
            </p>
          </div>
        </div>
      </div>
    <div class="col-sm-6 col-lg-3 mb-4">
      <div class="card card-border-shadow-info">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2 pb-1">
            <div class="avatar me-2">
              <span class="avatar-initial rounded bg-label-info"><i class="fa fa-boxes"></i></span>
            </div>
            <h4 class="ms-1 mb-0">{{ count($expirations) }}</h4>
          </div>
          <p class="mb-1">Productos</p>
          <p class="mb-0">
            <small class="text-muted">Productos por vencer</small>
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection