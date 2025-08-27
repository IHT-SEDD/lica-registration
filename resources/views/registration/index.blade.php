<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
 <base href="{{ url('/') }}">
 <title>LICA - Formulir</title>
 <meta charset="utf-8" />
 <meta name="description"
  content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
 <meta name="keywords"
  content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
 <meta name="viewport" content="width=device-width, initial-scale=1" />
 <meta property="og:locale" content="en_US" />
 <meta property="og:type" content="article" />
 <meta property="og:title"
  content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
 <meta property="og:url" content="https://keenthemes.com/metronic" />
 <meta property="og:site_name" content="Keenthemes | Metronic" />
 <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
 <link rel="shortcut icon" href="{{asset('metronic_assets/media/logos/favicon-lica2.png')}}" />
 <!--begin::Fonts-->
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" />
 <!--end::Fonts-->
 <!--begin::Global Stylesheets Bundle(used by all pages)-->
 <link href="{{asset('metronic_assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
 <link href="{{asset('metronic_assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
 <!--end::Global Stylesheets Bundle-->


</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="bg-body">
 <!--begin::Main-->
 <!--begin::Root-->
 <div class="d-flex flex-column flex-root">
  <!--begin::Authentication - Sign-in -->
  <div
   class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
   style="background-image: url('{{url('metronic_assets/media/illustrations/sketchy-1/14.png')}}')">
   <!--begin::Content-->
   <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <!--begin::Wrapper-->
    <div class="d-flex flex-center p-10; w-lg-250px">
     <table width="100%">
      <tr>
       <td style="width:100%;">
        <h1 class="text-dark mb-3">PENDAFTARAN ONLINE</h1>
       </td>
      </tr>
     </table>
    </div>
    <div class="d-flex flex-center p-10; w-lg-250px">
     <table width="100%">
      <tr>
       <td style="width:100%;">
        <h3 class="text-dark mb-3">LABKESDA KAB.BANDUNG</h3>
       </td>
      </tr>
     </table>
    </div>
    <br>
    <div class="w-xl-900px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
     @if (Session::get('error'))
     <!--begin::Alert-->
     <div class="alert alert-danger">
      <!--begin::Wrapper-->
      <div class="d-flex flex-column">
       <!--begin::Title-->
       <h4 class="mb-1 text-danger">Credential Is Invalid</h4>
       <!--end::Title-->
       <!--begin::Content-->
       <span>{{ Session::get('error') }}</span>
       <!--end::Content-->
      </div>
      <!--end::Wrapper-->
     </div>
     <!--end::Alert-->
     @endif
     <!--begin::Form-->
     {{-- <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form"
      data-kt-redirect-url="../../demo1/dist/index.html" action="#"> --}}
      {!! Form::open(['route' => 'login', 'method' => 'POST', 'class' => 'login-form
      form-validate-jquery', 'id' => 'form-create']) !!}
      <!--begin::Heading-->
      <div class="flex-column current" data-kt-stepper-element="content">
       <div class="mb-4 row">
        <div class="col-md-1 my-auto">
         <label class="form-label fs-5">Cari</label>
        </div>

        <div class="col-md-10">
         <div class="input-group input-group-solid flex-nowrap">
          <span class="input-group-text"><i class="bi bi-person-circle fs-4"></i></span>
          <div class="overflow-hidden flex-grow-1">
           {{ Form::text('search_nik', null, ['class' => 'form-control form-control-solid rounded-0 border-start
           select-two select-patient', 'placeholder' => 'NIK']) }}
          </div>
         </div>
        </div>

        <div class="col-md-1">
         <button type="button" class="btn btn-primary" id="btn-search">
          <span class="indicator-label">
           <i class="bi bi-search"></i>
          </span>
         </button>
        </div>

       </div>
       <div class="separator mb-4"></div>
       <div class="row form-step-1">
        <div class="col-md-6 patient-form">

         <div class="fv-row row mb-4">
          <div class="col-md-3"><label class="form-label fs-7">NIK</label></div>
          <div class="col-md-9">
           {{ Form::text('nik', null, ['class' => 'form-control form-control-solid form-control-sm req-input']) }}
          </div>
         </div>

         <div class="fv-row row mb-4">
          <div class="col-md-3"><label class="form-label fs-7">No. BPJS</label></div>
          <div class="col-md-9">
           {{ Form::text('no_bpjs', null, ['class' => 'form-control form-control-solid form-control-sm req-input']) }}
          </div>
         </div>

         <div class="fv-row row mb-4">
          <div class="col-md-3"><label class="form-label fs-7">Nama Pasien</label></div>
          <div class="col-md-9">
           {{ Form::text('name', null, ['class' => 'form-control form-control-solid form-control-sm req-input']) }}
          </div>
         </div>

         <div class="fv-row row mb-4">
          <div class="col-md-3"><label class="form-label fs-7">Email</label></div>
          <div class="col-md-9">
           {{ Form::text('email', null, ['class' => 'form-control form-control-solid form-control-sm', 'disabled',
           'readonly']) }}
          </div>
         </div>

         <div class="fv-row row mb-4">
          <div class="col-md-3"><label class="form-label fs-7">No. HP</label></div>
          <div class="col-md-9">
           {{ Form::text('phone', null, ['class' => 'form-control form-control-solid form-control-sm', 'disabled',
           'readonly']) }}
          </div>
         </div>
         <!-- End Input -->
        </div>

        <div class="col-md-6 patient-right-form">

         <div class="fv-row row mb-4">
          <div class="col-md-3"><label class="form-label fs-7">Tanggal Lahir</label></div>
          <div class="col-md-9">
           {{ Form::text('birthdate', null, ['class' => 'form-control form-control-solid form-control-sm birthdate
           req-input', 'disabled', 'readonly']) }}
          </div>
         </div>

         <div class="fv-row row mb-4">
          <div class="col-md-3"><label class="form-label fs-7">Jenis Kelamin</label></div>
          <div class="col-md-9">
           <div class="row">
            <div class="col-4">
             <div class="form-check form-check-custom form-check-solid me-10">
              {{ Form::radio('gender', 'M', null, ['class' => 'form-check-input h-15px w-15px', 'id' => 'radio-male',
              'disabled', 'readonly']) }}
              <label class="form-check-label mr-1" for="radio-male">
               Laki-laki
              </label>
             </div>
            </div>
            <div class="col-4">
             <div class="form-check form-check-custom form-check-solid me-10">
              {{ Form::radio('gender', 'F', null, ['class' => 'form-check-input h-15px w-15px', 'id' => 'radio-female',
              'disabled', 'readonly']) }}
              <label class="form-check-label" for="radio-female">
               Perempuan
              </label>
             </div>
            </div>
           </div>
          </div>
         </div>

         <div class="fv-row row mb-4">
          <div class="col-md-3"><label class="form-label fs-7">Alamat</label></div>
          <div class="col-md-9">
           {{ Form::textarea('address', null, ['class' => 'form-control form-control-solid form-control-sm', 'disabled',
           'readonly', 'data-kt-autosize' => 'true', 'rows' => 3]) }}
          </div>
         </div>

         <!-- End Input -->
        </div>
       </div>
       <button type="submit" class="btn btn-info" id="continue-btn" disabled>
        Submit
       </button>
      </div>
      {!! Form::close() !!}
      <!--end::Form-->
    </div>
    <!--end::Wrapper-->
   </div>
   <!--end::Content-->
  </div>
  <!--end::Authentication - Sign-in-->
 </div>
 <!--end::Root-->
 <!--end::Main-->
 <!--begin::Javascript-->
 <script>
  var hostUrl = "assets/";
 </script>
 <!--begin::Global Javascript Bundle(used by all pages)-->
 <script src="{{asset('metronic_assets/plugins/global/plugins.bundle.js')}}"></script>
 <script src="{{asset('metronic_assets/js/scripts.bundle.js')}}"></script>
 <!--end::Global Javascript Bundle-->
 <!--begin::Page Custom Javascript(used by this page)-->
 {{-- <script src="{{asset('metronic_assets/js/custom/authentication/sign-in/general.js')}}"></script> --}}
 <!--end::Page Custom Javascript-->
 <!--end::Javascript-->

 <script src="{{asset('limitless_assets/js/plugins/forms/validation/validate.min.js')}}"></script>
 <script src="{{asset('js/registration/index.js')}}"></script>
</body>
<!--end::Body-->

</html>