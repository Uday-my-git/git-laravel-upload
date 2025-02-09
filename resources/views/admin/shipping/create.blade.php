@extends('admin.layouts.app')

@section('content')

<!-------- Content Header (Page header) -------->
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Shipping Charges</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('shipping.create') }}" class="btn btn-primary">Back</a>
         </div>
      </div>
   </div>
   <!-- /.container-fluid -->
</section>

<!-------------- Main content -------------->
<section class="content">
   <div class="container-fluid">
      @include('admin.message')
      <form action="" name="shippingForm" id="shipping-formId" method="POST">
         @csrf
         <div class="card">
            <div class="card-body">								
               <div class="row">
                  <div class="col-md-4">
                     <div class="mb-3">
                        <select name="country" class="form-control" id="country_id">
                           <option value="">Select Country</option>   
                           
                           @if ($countries->isNotEmpty())
                              @foreach ($countries as $country)
                                 <option value="{{ $country->id }}">{{ $country->name }}</option>   
                              @endforeach      
                             <option value="rest_of_world">Rest Of World</option>
                           @endif
                        </select>	
                        <p></p>
                     </div>
                  </div>
                  <div class="col-md-4"">
                     <input type="text" name="amount" class="form-control" id="amount" placeholder="amount enter in (dollar)$">
                     <p></p>
                  </div>	
                  <div class="col-md-4"">
                     <button type="submit" class="btn btn-primary">Create</button>
                  </div>		
               </div>
            </div>							
         </div>
         
      </form>   
   </div>
</section>
<section class="content">
   <!-- Default box -->
   <div class="container-fluid">
      <div class="card">
         <div class="card-header">
            <strong>Current Page:-  {{ $shipping->currentPage() }}</strong>
            <div class="card-tools">
               <div class="input-group input-group" style="width: 250px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                  
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                     <i class="fas fa-search"></i>
                    </button>
                  </div>
                 </div>
            </div>
         </div>
         <div class="card-body table-responsive p-0">								
            <table class="table table-hover text-nowrap table-sm">
               <thead>
                  <tr>
                     <th width="60">ID</th>
                     <th>Country </th>
                     <th>Amount</th>
                     <th width="100">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @if ($shipping->isNotEmpty())
                     @foreach ($shipping as $shippingItem)
                        <tr>
                           <td>{{ $shippingItem->id }}</td>
                           <td>{{ ($shippingItem->country_id == 'rest_of_world') ? 'Rest Of World' : $shippingItem->cName}}</td>
                           <td>${{ $shippingItem->amount }}</td>
                           <td>
                              <a href="{{ route('shipping.edit', $shippingItem->id) }}">
                                 <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                 </svg>
                              </a>
                              <a href="javascript:void(0)" class="text-danger w-4 h-4 mr-1" onclick="shippingDeleteFun({{ $shippingItem->id }})">
                                 <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                 </svg>
                              </a>
                           </td>
                        </tr>
                     @endforeach
                  @endif
               </tbody>
            </table>										
         </div>
         <div class="card-footer clearfix">
            {{ $shipping->links() }}
         </div>
      </div>
   </div>
   <!-- /.card -->
</section>
@endsection

@section('custom-js')
<script>
   
$(document).ready(function() {

   $("#shipping-formId").submit(function(e) {
      e.preventDefault();
      const data = $(this).serializeArray();
      
      $.ajax({
         type: "POST",
         url: "{{ route('shipping.store', $shippingItem->id) }}",
         data: data,
         dataType: "JSON",
         success: function (response) {
            if (response.status === true) {
               $("#country_id").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $("#amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");

               $("#shipping-formId")[0].reset();
               window.location.href='{{ route("shipping.create") }}';
            } else{
               const error = response.errors;

               if (error.country) {
                  $("#country_id").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.country);
               } else {
                  $("#country_id").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }

               if (error.amount) {
                  $("#amount").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error.amount);
               }else {
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

   function shippingDeleteFun(id)
   {
      if (confirm('You are sure to delete')) {
         var url = "{{ route('shipping.destroy', 'ID') }}";
         var newURL = url.replace('ID', id)

         if (id != '') {
            $.ajax({
               type: "DELETE",
               url: newURL,
               dataType: "JSON",
               success: function (response) {
                  if (response.status === true) {
                     window.location.href='{{ route("shipping.create") }}';
                  }
               }
            });
         }
        
      }
   }
   



</script>

@endsection