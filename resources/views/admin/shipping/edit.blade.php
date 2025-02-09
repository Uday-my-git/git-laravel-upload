@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Edit Shipping Charges</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('shipping.create') }}" class="btn btn-primary">Back</a>
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</section>

<section class="content">
   <div class="container-fluid">
      @include('admin.message')
      <form action="" name="shippingEditForm" id="shipping-edit-formId" method="POST">
         @csrf
         <div class="card">
            <div class="card-body">								
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <select name="country" class="form-control" id="country_id">
                           @if ($countries->isNotEmpty())
                              @foreach ($countries as $country)
                                 <option {{($shipping->country_id == $country->id) ? 'selected' : ''}} value="{{ $country->id }}">{{ $country->name }}</option>         
                              @endforeach
                              <option @selected($shipping->country_id == 'rest_of_world') value="rest_of_world">Rest Of World</option>
                           @endif
                        </select>	
                     </div>
                  </div>
                  <div class="col-md-4"">
                     <input type="text" name="amount" class="form-control" id="amount" placeholder="amount enter" value="{{ $shipping->amount }}">
                     <p></p>
                  </div>	
                  <div class="col-md-4"">
                     <button type="submit" class="btn btn-primary">Update</button>
                  </div>		
               </div>
            </div>							
         </div>
         
      </form>   
   </div>
</section>
@endsection

@section('custom-js')
<script>
$(document).ready(function() {

   $("#shipping-edit-formId").submit(function(e) {
      e.preventDefault();
      const data = $(this).serializeArray();

      $.ajax({
         type: "PUT",
         url: "{{ route('shipping.update', $shipping->id) }}",
         data: data,
         dataType: "JSON",
         success: function (response) {
            if (response.status === true) {
               $("#amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");

               $("#shipping-edit-formId")[0].reset();
               window.location.href='{{ route("shipping.create") }}';
            } else {
               if (response.errors.amount) {
                  $("#amount").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(response.errors.amount);
               } else {
                  $("#amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }
            }
         }, 
         error: function (jqXHR, exception) {  
            console.log("error occured!!");
         }
      });
   });


});   
</script>    


@endsection