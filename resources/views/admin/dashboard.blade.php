@extends('admin.layouts.app')

@section('content')
<section class="content-header">					
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Dashboard</h1>
			</div>
			<div class="col-sm-6">	
			</div>
		</div>
	</div>
</section>
<section class="content">
	<!-- Default box -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-4 col-6">							
				<div class="small-box card">
					<div class="inner">
						<h3>{{ $orders }}</h3>
						<p>Total Orders</p>
					</div>
					<div class="icon">
						<i class="ion ion-bag"></i>
					</div>
					<a href="{{ route('orders.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-4 col-6">							
				<div class="small-box card">
					<div class="inner">
						<h3>{{ $products }}</h3>
						<p>Total Product</p>
					</div>
					<div class="icon">
						<i class="ion ion-stats-bars"></i>
					</div>
					<a href="{{ route('product.productListing') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>			
			<div class="col-lg-4 col-6">							
				<div class="small-box card">
					<div class="inner">
						<h3>{{ $user }}</h3>
						<p>Total Customers</p>
					</div>
					<div class="icon">
						<i class="ion ion-stats-bars"></i>
					</div>
					<a href="{{ route('user.index') }}" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
				</div>
			</div>			
			<div class="col-lg-4 col-6">							
				<div class="small-box card">
					<div class="inner">
						<h3>${{ number_format($totalRevenue, 2) }}</h3>
						<p>Total Sale</p>
					</div>
					<div class="icon">
						<i class="ion ion-person-add"></i>
					</div>
					<a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
				</div>
			</div>
			<div class="col-lg-4 col-6">							
				<div class="small-box card">
					<div class="inner">
						<h3>${{ number_format($revenueThisMonth, 2) }}</h3>
						<p>This Month Revenue</p>
					</div>
					<div class="icon">
						<i class="ion ion-person-add"></i>
					</div>
					<a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
				</div>
			</div>
			<div class="col-lg-4 col-6">							
				<div class="small-box card">
					<div class="inner">
						<h3>${{ number_format($revenueLastMonth, 2) }}</h3>
						<p>Last Month Revenue ({{ $lastMonName }})</p>
					</div>
					<div class="icon">
						<i class="ion ion-person-add"></i>
					</div>
					<a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
				</div>
			</div>
		</div>
	</div>					
	<!-- /.card -->
</section>
	@section('custom-js')
		<script>
			console.log('hello');
		</script>
	@endsection

@endsection