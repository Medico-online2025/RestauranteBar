@extends('admin.template')
@section('content')
<div class="card">
    <!-- Pricing Plans -->
    <div class="pb-sm-5 pb-2 rounded-top">
      <div class="container py-5">
        <h2 class="text-center mb-2 mt-0 mt-md-4">Planes de precios</h2>
        <p class="text-center"> Te presentamos las opciones que tenemos que tenemos para ti. Elija un plan de suscripci&oacute;n que satisfaga sus necesidades. </p>
        <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 pb-5 pt-3 mb-0 mb-md-4">
          <label class="switch switch-primary ms-3 ms-sm-0 mt-2">
            <span class="switch-label">Mensual</span>
            <input type="checkbox" class="switch-input price-duration-toggler">
            <span class="switch-toggle-slider">
              <span class="switch-on"></span>
              <span class="switch-off"></span>
            </span>
            <span class="switch-label">Anual</span>
          </label>
          <div class="mt-n5 ms-n5 d-none d-sm-block">
            <i class="ti ti-corner-left-down ti-sm text-muted me-1 scaleX-n1-rtl"></i>
            <span class="badge badge-sm bg-label-primary">Ahorra hasta un 10%</span>
          </div>
        </div>
  
        <div class="row mx-0 gy-3 px-lg-5">
          <!-- Basic -->
          <div class="col-lg mb-md-0 mb-4">
            <div class="card border rounded shadow-none">
              <div class="card-body">
                <div class="my-3 pt-2 text-center">
                  <img src="{{ asset('assets/img/illustrations/page-pricing-basic.png') }}" alt="Basic Image" height="140">
                </div>
                <h3 class="card-title text-center text-capitalize mb-1">B&aacute;sico</h3>
                <div class="text-center">
                  <div class="d-flex justify-content-center">
                    <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">S/</sup>
                    <h1 class="display-4 mb-0 text-primary">60</h1>
                    <sub class="h6 pricing-duration mt-auto mb-2 text-muted fw-normal">/mes</sub>
                  </div>
                </div>
  
                <ul class="ps-3 my-4 pt-2">
                  <li class="mb-2">Facturador</li>
                  <li class="mb-2">Punto de venta</li>
                  <li class="mb-2">Compras a proveedor</li>
                  <li class="mb-2">Proformas</li>
                  <li class="mb-2">Control de stock e inventario</li>
                  <li class="mb-2">Apertura y cierre de caja</li>
                  <li class="mb-2">Reportes</li>
                  <li class="mb-0">1 usuario + 1 para contabilidad</li>
                </ul>
  
                <a target="_blank" class="btn btn-label-success d-grid w-100 waves-effect" href="https://api.whatsapp.com/send?phone=51950772205&text=Me interesa migrar a la suscripción Básica.">Ir con el administrador</a>
              </div>
            </div>
          </div>
  
          <!-- Pro -->
          <div class="col-lg mb-md-0 mb-4">
            <div class="card border shadow-none">
              <div class="card-body position-relative">
                <div class="my-3 pt-2 text-center">
                  <img src="{{ asset('assets/img/illustrations/page-pricing-standard.png') }}" alt="Standard Image" height="140">
                </div>
                <h3 class="card-title text-center text-capitalize mb-1">Standard</h3>
                <div class="text-center">
                  <div class="d-flex justify-content-center">
                    <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">S/</sup>
                    <h1 class="price-toggle price-yearly display-4 text-primary mb-0">65</h1>
                    <h1 class="price-toggle price-monthly display-4 text-primary mb-0 d-none">70</h1>
                    <sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal">/mes</sub>
                  </div>
                  <small class="price-yearly price-yearly-toggle text-muted">S/ 780.00 / a&ntilde;o</small>
                </div>
  
                <ul class="ps-3 my-4 pt-2">
                    <li class="mb-2">Facturador</li>
                    <li class="mb-2">Punto de venta</li>
                    <li class="mb-2">Compras a proveedor</li>
                    <li class="mb-2">Proformas</li>
                    <li class="mb-2">Control de stock e inventario</li>
                    <li class="mb-2">Apertura y cierre de caja</li>
                    <li class="mb-2">Reportes</li>
                    <li class="mb-2">2 usuarios + 1 para contabilidad</li>
                    <li class="mb-0">1 almac&eacute;n</li>
                </ul>
  
                <a target="_blank" class="btn btn-primary d-grid w-100 waves-effect waves-light" href="https://api.whatsapp.com/send?phone=51950772205&text=Me interesa migrar a la suscripción Standard.">Ir con el administrador</a>
              </div>
            </div>
          </div>
  
          <!-- Enterprise -->
          <div class="col-lg">
            <div class="card border rounded shadow-none">
              <div class="card-body">
  
                <div class="my-3 pt-2 text-center">
                  <img src="{{ asset('assets/img/illustrations/page-pricing-enterprise.png') }}" alt="Enterprise Image" height="140">
                </div>
                <h3 class="card-title text-center text-capitalize mb-1">Premiun</h3>
  
                <div class="text-center">
                  <div class="d-flex justify-content-center">
                    <sup class="h6 text-primary pricing-currency mt-3 mb-0 me-1">S/</sup>
                    <h1 class="price-toggle price-yearly display-4 text-primary mb-0">75</h1>
                    <h1 class="price-toggle price-monthly display-4 text-primary mb-0 d-none">80</h1>
                    <sub class="h6 pricing-duration mt-auto mb-2 fw-normal text-muted">/mes</sub>
                  </div>
                  <small class="price-yearly price-yearly-toggle text-muted">S/ 900 / a&ntilde;o</small>
                </div>
  
                <ul class="ps-3 my-4 pt-2">
                  <li class="mb-2">Facturador</li>
                  <li class="mb-2">Punto de venta</li>
                  <li class="mb-2">Compras a proveedor</li>
                  <li class="mb-2">Proformas</li>
                  <li class="mb-2">Control de stock e inventario</li>
                  <li class="mb-2">Apertura y cierre de caja</li>
                  <li class="mb-2">Reportes</li>
                  <li class="mb-2">4 usuarios + 1 para contabilidad</li>
                  <li class="mb-0">2 almacenes</li>
                </ul>
  
                <a target="_blank" class="btn btn-label-primary d-grid w-100 waves-effect" href="https://api.whatsapp.com/send?phone=51950772205&text=Me interesa migrar a la suscripción Premiun.">Ir con el administrador</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <section class="pricing-free-trial bg-label-primary">
        <div class="container">
            <div class="position-relative">
                <div class="d-flex justify-content-between flex-column-reverse flex-lg-row align-items-center py-4 px-5">
                    <div class="text-center text-lg-start mt-2 ms-3">
                        <h5 class="text-primary mb-1"><svg class="svg-inline--fa fa-tag fa-w-16" width="16" height="16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="tag" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M0 252.118V48C0 21.49 21.49 0 48 0h204.118a48 48 0 0 1 33.941 14.059l211.882 211.882c18.745 18.745 18.745 49.137 0 67.882L293.823 497.941c-18.745 18.745-49.137 18.745-67.882 0L14.059 286.059A48 48 0 0 1 0 252.118zM112 64c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48z"></path></svg> Si contrata cualquiera de nuestros planes de forma anual le brindaremos 1 mes adicional GRATIS.</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-py pricing-plans-comparison mt-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h4 class="mb-2">Elije un plan adecuado de acuerdo a las necesidades de su negocio</h4>
                </div>
            </div>
            <div class="row mx-4">
                <div class="col-12">
                    <div class="table-responsive border rounded">
                        <table class="table table-striped text-center mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="1">
                                        <p class="mb-1">Descripci&oacute;n</p>
                                    </th>
                                    <th colspan="3" scope="col">
                                        <p class="mb-1">Adicional</p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Usuario adicional</td>
                                    <td colspan="3">S/5.00</td>
                                </tr>
                                <tr>
                                    <td>Almac&eacute;n adicional</td>
                                    <td colspan="3">S/10.00</td>
                                </tr>
                                <tr>
                                    <td>Caja adicional</td>
                                    <td colspan="3">S/10.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>

    
    <!--/ Pricing Plans -->

    
  </div>
@endsection
@section('scripts')
<script>
    "use strict";
    document.addEventListener("DOMContentLoaded",function(e){
        {var c=document.querySelector(".price-duration-toggler"),t=[].slice.call(document.querySelectorAll(".price-monthly")),o=[].slice.call(document.querySelectorAll(".price-yearly"));function n(){c.checked?(o.map(function(e){e.classList.remove("d-none")}),t.map(function(e){e.classList.add("d-none")})):(o.map(function(e){e.classList.add("d-none")}),t.map(function(e){e.classList.remove("d-none")}))}n(),c.onchange=function(){n()}}
        });
</script>
@endsection
