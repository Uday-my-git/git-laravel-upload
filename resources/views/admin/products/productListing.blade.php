@extends('admin.layouts.app')

@section('content')

<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Products</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('product.create') }}" class="btn btn-primary btn-sm">New Product</a>
         </div>
      </div>
   </div>
</section>

<section class="content">
   <div class="container-fluid">
      @include('admin.message')
      
      <div class="card">
         <div class="card-header">
            <button type="button" class="btn btn-default btn-sm" onclick="window.location.href='{{ route('product.productListing') }}'">Reset</button>

            <div class="card-tools">
               <form action="" name="search" method="GET">
                  <div class="input-group input-group" style="width: 250px;">
                     <input type="text" name="search" class="form-control float-right" value="{{ Request::get('search') }}" placeholder="Search">
   
                     <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                           <i class="fas fa-search"></i>
                        </button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
         <div class="card-body table-responsive p-0">								
            <table class="table table-hover table-sm text-nowrap">
               <thead>
                  <tr>
                     <th width="60">ID</th>
                     <th width="80"></th>
                     <th>Title</th>
                     <th>Slug</th>
                     <th>Featured</th>
                     <th>Price</th>
                     <th>Qty</th>
                     <th>Category</th>
                     <th width="100">Status</th>
                     <th width="100">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @if ($products->isNotEmpty())
                     @forelse ($products as $productList)
                        @php
                           $productImage = $productList->productImage->first();
                        @endphp
                        <tr>
                           <td>{{ $productList->id }}</td>
                           <td>
                              @if (!empty($productImage->image))
                                 <img src="{{ asset('uploads/product/small/'.$productImage->image) }}" class="img-thumbnail" width="50" >
                              @else 
                                 <img src="{{ asset('uploads/dummy-img.jpg') }}" class="img-thumbnail" width="50" >
                              @endif
                           </td>
                           <td><a href="javascript:void(0)">{{ $productList->title }}</td></a>
                           <td>{{ $productList->slug }}</td>
                           <td style="color: rgb(137, 137, 226)">{{ $productList->is_featured }}</td>
                           <td>{{ $productList->price }}</td>
                           <td>{{ $productList->qty }}</td>
                           <td>{{ $productList->categoryName }}</td>
                           <td>
                              @if ($productList->status == 1)
                                 <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                 </svg>    
                              @else
                                 <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                 </svg>
                              @endif
                           
                           </td>
                           <td>
                              <a href="{{ route('product.edit', $productList->id) }}">
                                 <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                 </svg>
                              </a>
                              <a href="javascript:void(0)" class="text-danger w-4 h-4 mr-1">
                                 <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" onclick="deleteProductFun({{ $productList->id }})"></path>
                                 </svg>
                              </a>
                           </td>
                        </tr>
                     @empty
                        <tr><td>No Data Found</td></tr>
                     @endforelse
                  @endif
               </tbody>
            </table>										
         </div>
         <div class="card-footer clearfix">
            {{ $products->links() }}
         </div>
      </div>
   </div>
</section>

@endsection

@section('custom-js')
    
<script>

   function deleteProductFun(id)
   {
      const url = "{{ route('product.delete', 'ID') }}";
      const newUrl = url.replace("ID", id);

      if (id != null) {
         $.ajax({
            type: "DELETE",
            url: newUrl,
            dataType: "JSON",
            success: function (response) {
               if (response.status === true) {
                  window.location.href="{{ route('product.productListing') }}";
               } else {
                  window.location.href="{{ route('product.productListing') }}";
               }
            }
         });
      }
   }

</script>

@endsection