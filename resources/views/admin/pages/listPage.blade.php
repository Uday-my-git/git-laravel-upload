@extends('admin.layouts.app')
@section('content')
<section class="content-header">					
   <div class="container-fluid my-2">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1>Pages Details</h1>
         </div>
         <div class="col-sm-6 text-right">
            <a href="{{ route('pages.createPage') }}" class="btn btn-primary btn-sm">Add Pages</a>
         </div>
      </div>
   </div>
</section>

<!-- Main content -->
<section class="content">
   <div class="container-fluid">
      @include('admin.message')
      <div class="card">
         <form action="" name="search" method="GET">
            <div class="card-header">
               <div class="card-title">
                  <button type="button" class="btn btn-default btn-sm" onclick="window.location.href='{{ route('pages.listPage') }}'">Reset Now</button>
               </div>
               <form action="" name="search" method="GET">
                  <div class="card-tools">
                     <div class="input-group input-group" style="width: 250px;">
                        <input type="text" name="search" class="form-control float-right" placeholder="Search" value="{{ Request::get('search') }}">
         
                        <div class="input-group-append">
                           <button type="submit" class="btn btn-default">
                              <i class="fas fa-search"></i>
                           </button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </form>
         <div class="card-body table-responsive p-0">								
            <table class="table table-hover table-sm text-nowrap">
               <thead>
                  <tr>
                     <th width="60">ID</th>
                     <th>Name</th>
                     <th>Slug</th>
                     <th>Created At</th>
                     <th>Updated At</th>
                     <th width="100">Action</th>
                  </tr>
               </thead>
               <tbody>
                  @if ($pages->isNotEmpty())
                     @foreach ($pages as $page)
                        <tr>  
                           <td>{{ $page->id }}</td>   
                           <td>{{ $page->name }}</td>   
                           <td>{{ $page->slug }}</td>   
                           <td>{{ $page->created_at }}</td>   
                           <td>{{ $page->updated_at }}</td> 
                           <td>
                              <a href="{{ route('pages.edit', $page->id) }}">
                                 <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                 </svg>
                              </a>
                              <a href="javascript:void(0)" class="text-danger w-4 h-4 mr-1" onclick="delePageFun({{ $page->id }})">
                                 <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                 </svg>
                              </a>
                           </td> 
                        </tr>   
                     @endforeach
                  @else
                        <tr><td>No Pages Found</td></tr>
                  @endif
               </tbody>
            </table>										
         </div>
         <div class="card-footer clearfix">
            {{ $pages->links(); }}
         </div>
      </div>
   </div>
</section>
@endsection

@section('custom-js')
<script>

   function delePageFun(id) 
   {
      if (confirm('Are You Sure To Delete Pages ?')) {
         let url = '{{ route("pages.delete", "ID") }}';
         let newURL = url.replace("ID", id);
         
         $.ajax({
         type: "DELETE",
         url: newURL,
         data: "id="+id,
         success: function (response) {
            if (response.status === true) {
               window.location.href='{{ route("pages.listPage") }}';
            }
         }
      });
      }
   }


</script>
@endsection