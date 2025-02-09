{{-- alert red danger --}}

@if (Session::has('error'))
   <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fas fa-ban"></i> Error!</h4> {{ Session::get('error') }}
   </div>
@endif


{{-- alert success --}}
{{-- <div class="alert alert-success alert-dismissible">
   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
   <h5><i class="icon fas fa-check"></i> Alert!</h5>
</div> --}}

{{-- alert warning --}}

@if (Session::has('success'))
   <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fas fa-check"></i> Success!</h4> {{ Session::get('success') }}
   </div>
@endif

