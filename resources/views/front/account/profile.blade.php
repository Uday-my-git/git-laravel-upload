@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
   <div class="container">
      <div class="light-font">
         <ol class="breadcrumb primary-color mb-0">
            <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
            <li class="breadcrumb-item">Settings</li>
         </ol>
      </div>
   </div>
</section>

<section class=" section-11 ">
   <div class="container  mt-5">
      <div class="row">
         <div class="col-md-12">
            @include('front.account.common.message')
         </div>
         <div class="col-md-3">
            @include('front.account.common.sidebar')
         </div>
         <div class="col-md-9">
            <div class="card">
               <div class="card-header">
                  <h2 class="h5 mb-0 pt-2 pb-2">Profile Information</h2>
               </div>
               <div class="card-body p-4">
                  <div class="row">
                     <form action="" name="profileForm" id="profile-form" method="POST">
                        <div class="mb-3">               
                           <label for="name">Name</label>
                           <input type="text" name="name" id="name" value="{{ $data->name ?? '' }}" placeholder="Enter Your Name" class="form-control">
                           <p></p>
                        </div>
                        <div class="mb-3">            
                           <label for="email">Email</label>
                           <input type="text" name="email" id="email" value="{{ $data->email ?? '' }}" placeholder="Enter Your Email" class="form-control">
                           <p></p>
                        </div>
                        <div class="mb-3">                                    
                           <label for="phone">Phone</label>
                           <input type="text" name="phone" id="phone" value="{{ $data->phone ?? '' }}" placeholder="Enter Your Phone" class="form-control">
                           <p></p>
                        </div>
                        <div class="d-flex">
                           <button type="submit" class="btn btn-dark">Update</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
            <div class="card mt-4">
               <div class="card-header">
                  <h2 class="h5 mb-0 pt-2 pb-2">Customer Address (Shipping Address)</h2>
               </div>
               <form action="" name="customerAddressForm" id="customer-address-form" method="POST">
                  <div class="card-body p-4">
                     <div class="row">
                        <div class="col-md-6 mb-3">               
                           <label for="name">Name</label>
                           <input type="text" name="first_name" id="first_name" value="{{ $address->first_name ?? '' }}" placeholder="Enter Your Name" class="form-control">
                           {{-- <p id="first_nameErr" class="text-danger"></p> --}}
                           <p></p>
                        </div>
                        <div class="col-md-6 mb-3">               
                           <label for="name">Last Name</label>
                           <input type="text" name="last_name" id="last_name" value="{{ $address->last_name ?? '' }}" placeholder="Enter Your Name" class="form-control">
                           {{-- <p id="last_nameErr" class="text-danger"></p> --}}
                           <p></p>
                        </div>
                        <div class="col-md-6 mb-3">            
                           <label for="email">Email</label>
                           <input type="text" name="email" id="email_id" value="{{ $address->email ?? '' }}" placeholder="Enter Your Email" class="form-control">
                           <p></p>
                           {{-- <p id="emailNameErr" class="text-danger"></p> --}}
                        </div>
                        <div class="col-md-6 mb-3">                                    
                           <label for="phone">Phone</label>
                           <input type="text" name="mobile" id="phone_id" value="{{ $address->mobile ?? '' }}" placeholder="Enter Your Phone" class="form-control">
                           {{-- <p id="mobile_nameErr" class="text-danger"></p> --}}
                           <p></p>
                        </div>
                        <div class="col-md-6 mb-3">                                    
                           <label for="country">Country</label>
                           <select name="country" class="form-control" id="country_id">
                              <option value="">Select Coutrie</option> 
                              
                              @if ($country->isNotEmpty())  
                                 @foreach ($country as $countries)
                                    <option value="{{ $countries->id }}" {{ (!empty($address) && $address->country_id == $countries->id) ? 'selected' : '' }}>
                                       {{ $countries->name }}   
                                    </option> 
                                 @endforeach    
                              @endif
                           </select>	
                           <p></p>
                        </div>
                        <div class="col-md-6 mb-3">                                    
                           <label for="state">State</label>
                           <input type="text" name="state" id="state" value="{{ $address->state ?? '' }}" placeholder="Enter Your State" class="form-control">
                           <p></p>
                        </div>
                        <div class="col-md-6 mb-3">                                    
                           <label for="city">City</label>
                           <input type="text" name="city" id="city" value="{{ $address->city ?? '' }}" placeholder="Enter Your City" class="form-control">
                           <p></p>
                        </div>
                        <div class="col-md-6 mb-3">                                    
                           <label for="apartment">apartment</label>
                           <input type="text" name="apartment" id="apartment" value="{{ $address->apartment ?? '' }}" placeholder="Enter Your Apartment" class="form-control">
                           <p></p>
                        </div>
                        <div class="mb-3">                                    
                           <label for="address">Address</label>
                           <textarea name="address" class="form-control" id="address" cols="10" rows="2">{{ $address->address ?? '' }}</textarea>
                           <p></p>
                        </div> 
                        <div class="mb-3">                                    
                           <label for="state">Zip</label>
                           <input type="text" name="zip" id="zip" value="{{ $address->zip ?? '' }}" placeholder="Enter Your Zip" class="form-control">
                           <p></p>
                        </div>
                        <div class="d-flex">
                           <button type="submit" class="btn btn-dark">Update Address</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</section>
@endsection
@section('custom-js')
<script>

$(function () {  
   $("#profile-form").submit(function (e) {
      e.preventDefault();

      $.ajax({
         type: "POST",
         url: "{{ route('account.updateProfile') }}",
         data: $(this).serializeArray(),
         dataType: "JSON",
         success: function (response) {
            if (response['status'] === true) {
               $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $('#email').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $('#phone').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");

               $("#profile-form")[0].reset();
               window.location.href = '{{ route("account.profile") }}'
            } else {
               var errors = response.errors;

               if (errors.name) {
                  $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.name);
               } else {
                  $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }

               if (errors.email) {
                  $('#email').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.email);
               } else {
                  $('#email').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               }

               if (errors.phone) {
                  $('#phone').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.phone);
               } else {
                  $('#phone').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               }
            }
         }
      });
   });


   // submit form data with j.s validation
   // document.getElementById('customer-address-form').addEventListener('submit', function (e) {  
   //    e.preventDefault();

   //    $.ajax({
   //       type: "POST",
   //       url: "{{ route('account.updateAddress') }}",
   //       data: $(this).serializeArray(),
   //       dataType: "JSON",
   //       success: function (response) {
   //          if (response['status'] === true) {
          
   //          } else {
   //             var errors = response.errors;

   //             // let first_name = document.getElementById('first_name').value.trim();
   //             let firstNameInput = document.getElementById('first_name');
   //             let lastNameInput  = document.getElementById('last_name');
   //             let emailNameInput = document.getElementById('email_id');
   //             let mobNameInput   = document.getElementById('phone_id');
               
   //             let firstNameErr = document.getElementById('first_nameErr');
   //             let lastNameErr  = document.getElementById('last_nameErr');
   //             let emailEameErr = document.getElementById('emailNameErr');
   //             let mobNameErr   = document.getElementById('mobile_nameErr');

   //             if (errors.first_name) {
   //                if (firstNameInput) firstNameInput.classList.add('is-invalid'); // Add Bootstrap error class

   //                if (firstNameErr) {
   //                   firstNameErr.classList.add('invalid-feedback');
   //                   firstNameErr.innerText = errors.first_name[0];              // Show Laravel error message
   //                }
   //             } else {
   //                if (firstNameInput) {
   //                   firstNameInput.classList.remove('is-invalid');
   //                   firstNameInput.classList.add('is-valid');                   // Add success class
   //                }
   //                if (firstNameErr) {
   //                   firstNameErr.innerText = '';                                // Clear error message
   //                   firstNameErr.classList.remove('invalid-feedback');
   //                }
   //             }

   //             if (errors.last_name) {
   //                if (lastNameInput) lastNameInput.classList.add('is-invalid'); 

   //                if (lastNameErr) {
   //                   lastNameErr.classList.add('invalid-feedback');
   //                   lastNameErr.innerText = errors.last_name[0];           
   //                }
   //             } else {
   //                if (lastNameInput) {
   //                   lastNameInput.classList.remove('is-invalid');
   //                   lastNameInput.classList.add('is-valid');                   
   //                }
   //                if (lastNameErr) {
   //                   lastNameErr.innerText = ''; // Clear error message
   //                   lastNameErr.classList.remove('invalid-feedback');
   //                }
   //             }

   //             if (errors.phone) {
   //                if (mobNameInput) mobNameInput.classList.add('is-invalid'); // Add Bootstrap error class

   //                if (mobNameErr) {
   //                   mobNameErr.classList.add('invalid-feedback');
   //                   mobNameErr.innerText = errors.phone[0];              // Show Laravel error message
   //                }
   //             } else {
   //                if (mobNameInput) {
   //                   mobNameInput.classList.remove('is-invalid');
   //                   mobNameInput.classList.add('is-valid');                   // Add success class
   //                }
   //                if (mobNameErr) {
   //                   mobNameErr.innerText = ''; // Clear error message
   //                   mobNameErr.classList.remove('invalid-feedback');
   //                }
   //             }

   //             if (errors.email) {
   //                if (emailNameInput) emailNameInput.classList.add('is-invalid'); // Add Bootstrap error class

   //                if (emailEameErr) {
   //                   emailEameErr.classList.add('invalid-feedback');
   //                   emailEameErr.innerText = errors.email[0];              // Show Laravel error message
   //                }
   //             } else {
   //                if (emailNameInput) {
   //                   emailNameInput.classList.remove('is-invalid');
   //                   emailNameInput.classList.add('is-valid');                   // Add success class
   //                }
   //                if (emailEameErr) {
   //                   emailEameErr.innerText = ''; // Clear error message
   //                   emailEameErr.classList.remove('invalid-feedback');
   //                }
   //             }
   //          }
   //       }
   //    });
   // });


   $("#customer-address-form").submit(function (e) {
      e.preventDefault();

      $.ajax({
         type: "POST",
         url: "{{ route('account.updateAddress') }}",
         data: $(this).serialize(),
         dataType: "JSON",
         success: function (response) {
            if (response['status'] === true) {
               $("#first_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $('#email_id').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $('#phone_id').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               $('#country_id').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               $('#state').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               $('#city').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

               $("#customer-address-form")[0].reset();
               window.location.href = '{{ route("account.profile") }}'
            } else {
               var errors = response.errors;

               // $.each(errors, function(key, value) {
               //    console.log(key);
                  
               //    $(`#${key}`).addClass("is-invalid").siblings("p").addClass("invalid-feedback").html(value[0]);
               // });

               if (errors.first_name) {
                  $("#first_name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.first_name);
               } else {
                  $("#first_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }

               if (errors.last_name) {
                  $("#last_name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.last_name);
               } else {
                  $("#last_name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
               }

               if (errors.email) {
                  $('#email_id').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.email);
               } else {
                  $('#email_id').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               }

               if (errors.mobile) {
                  $('#phone_id').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.mobile);
               } else {
                  $('#phone_id').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               }

               if (errors.country) {
                  $('#country_id').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.country);
               } else {
                  $('#country_id').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               }

               if (errors.state) {
                  $('#state').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.state);
               } else {
                  $('#state').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               }

               if (errors.city) {
                  $('#city').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.city);
               } else {
                  $('#city').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
               }
            }
         }
      });
   });
});


</script>
@endsection