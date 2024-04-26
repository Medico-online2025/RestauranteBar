@extends('admin.template')
@section('content')
<section id="landingFAQ" class="section-py bg-body landing-faq">
    <div class="container">
      <div class="text-center mb-3 pb-1">
        <span class="badge bg-label-primary">FAQ</span>
      </div>
      <h3 class="text-center mb-1">Preguntas <span class="section-title">frecuentes</span></h3>
      <p class="text-center mb-5 pb-3">Explore estas preguntas frecuentes para encontrar respuestas a las preguntas más frecuentes.</p>
      <div class="row gy-5">
        <div class="col-lg-5">
          <div class="text-center">
            <img src="{{ asset('assets/img/illustrations/page-misc-under-maintenance.png') }}" alt="faq girl with logos" class="faq-image" width="85%">
          </div>
        </div>
        <div class="col-lg-7">
          <div class="accordion" id="accordionExample">
            <div class="card accordion-item active">
              <h2 class="accordion-header" id="headingOne">
                <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                  ¿Cobran por cada actualización?
                </button>
              </h2>

              <div id="accordionOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body" style="text-align: justify"> No, no cobramos por cada actualización. En Mytems, las actualizaciones son parte de nuestro compromiso de mejorar constantemente nuestro software y brindar la mejor experiencia a nuestros usuarios. Las actualizaciones son gratuitas y se proporcionan de forma regular para mantener el software actualizado y seguro.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo">
                  ¿El sistema funciona en dispositivos m&oacute;viles?
                </button>
              </h2>
              <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">Lo puede utilizar en diferentes dispositivos tales como TABLETAS, SMARTPHONE, etc.</div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree">
                  ¿Cu&aacute;nto tiempo demora la instalaci&oacute;n?
                </button>
              </h2>
              <div id="accordionThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">Nos tomaremos un par de horas para la creación del subdominio, hosting y las credenciales.</div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="headingFour">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionFour" aria-expanded="false" aria-controls="accordionFour">
                  ¿Por qué debería elegir Mytems en lugar de otros?
                </button>
              </h2>
              <div id="accordionFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                <div class="accordion-body" style="text-align: justify;">La diferencia clave que nos distingue es nuestro compromiso con el soporte excepcional. En Mytems, no solo ofrecemos soluciones de alta calidad, sino que también respaldamos nuestro servicio con un equipo de soporte t&eacute;cnico dedicado y receptivo. Estamos aqu&iacute; para ayudarte en cada paso del camino, brindando asistencia experta cuando la necesitas. Esta atenci&oacute;n al cliente excepcional es lo que nos hace destacar y es una de las razones principales para elegirnos.</div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="headingFive">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionFive" aria-expanded="false" aria-controls="accordionFive">
                  ¿Puedo realizar cualquier cambio que desee al sistema?
                </button>
              </h2>
              <div id="accordionFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                <div class="accordion-body" style="text-align: justify;">Los cambios se realizarán previa evaluación de factibilidad y aprobación de parte de nuestro equipo de trabajo. Los planes mensuales y anuales cubren el costo de las modificaciones de menor relevancia.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scripts')
<script>
    
</script>
@endsection
