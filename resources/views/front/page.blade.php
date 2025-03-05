@extends('front.layouts.app')
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
   <div class="container">
      <div class="light-font">
         <ol class="breadcrumb primary-color mb-0">
            <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
            <li class="breadcrumb-item">{{ $page->name }}</li>
         </ol>
      </div>
   </div>
</section>

@if ($page->slug == 'contact-us')
   @if ($message = Session::get('success'))
      <div class="alert alert-success">
         <p>{{ $message }}</p>
      </div>
   @endif
   <section class=" section-10">
      <div class="container">
         <div class="section-title mt-5 ">
            <h2>{{ $page->name }}</h2>
         </div>   
      </div>
   </section>
   <section>
      <div class="container">          
         <div class="row">
            <div class="col-md-6 mt-3 pe-lg-5">
               {!! $page->content  !!}              
            </div>

           
            <div class="col-md-6">
               @if ($errors->any())
                  <div class="alert alert-danger">
                     <ul>
                        @foreach ($errors->all() as $error)
                           <li>{{ $error }}</li>
                        @endforeach
                     </ul>
                  </div>
               @endif
               <form action="{{ route('front.sendContactUsEmail') }}" name="contact-form" class="shake" role="form" id="contact-form" method="post">
                  @csrf
                  <div class="mb-3">
                     <label class="mb-2" for="name">Name</label>
                     <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" data-error="Please enter your name">
                     <p class="help-block with-errors"></p>
                  </div>
                  
                  <div class="mb-3">
                     <label class="mb-2" for="email">Email</label>
                     <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" data-error="Please enter your Email">
                     <p class="help-block with-errors"></p>
                  </div>
                  
                  <div class="mb-3">
                     <label class="mb-2">Subject</label>
                     <input type="text" name="subject" class="form-control" id="msg_subject" value="{{ old('subject') }}" data-error="Please enter your message subject">
                     <p class="help-block with-errors"></p>
                  </div>
                  
                  <div class="mb-3">
                     <label for="message" class="mb-2">Message</label>
                     <textarea name="message" class="form-control" rows="3" id="message" data-error="Write your message"></textarea>
                     <p class="help-block with-errors"></p>
                  </div>
               
                  <div class="form-submit">
                     <button type="submit" class="btn btn-dark" id="form-submit"><i class="material-icons mdi mdi-message-outline"></i> Send Message</button>
                     <div id="msgSubmit" class="h3 text-center hidden"></div>
                     <p class="clearfix"></p>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
@else
   <section class=" section-10">
      <div class="container">
         <h1 class="my-3"></h1>
         <p>{!! $page->content !!}</p>

      </div>
   </section>  
@endif
@endsection

@section('custom-js')
<script>

// $(function () {
//    $("#contact-form").submit(function (e) {
//       e.preventDefault();

//       $.ajax({
//          type: "POST",
//          url: "{{ route('front.sendContactUsEmail') }}",
//          data: $(this).serialize(),
//          dataType: "JSON",
//          success: function (response) {
//             if (response['status'] === true) {

//             } else {
               
//             }
//          }
//       });
//    });
// });


</script>
@endsection