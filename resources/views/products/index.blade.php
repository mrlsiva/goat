<!DOCTYPE html>
<html lang="en">
	<head>
	  <meta charset="UTF-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <title>{{ config('app.name')}} | Product</title>
	  <!-- Bootstrap CSS -->
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	</head>
	<body class="bg-light">

	 	<div class="container mt-5">

	 		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
			    @csrf
			</form>
	 		<button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger btn-lg rounded-circle shadow" style=" position: fixed;bottom: 20px;right: 20px;z-index: 9999;width: 60px;height: 60px;">
    			<i class="fas fa-sign-out-alt"></i>
			</button>

		    <div class="d-flex justify-content-between align-items-center mb-4">
		      	<h2 class="fw-bold">Product</h2>
		      	<div>
		      		<a href="{{route('category.index')}}">
			    		<button class="btn btn-primary">Category</button>
			    	</a>
			    	<a href="{{route('product.create')}}">
			    		<button class="btn btn-primary">Add New Product</button>
			    	</a>
			    	@if(count($products) > 0)
				    	<a href="{{route('product.download_all')}}" target="_blank">
				    		<button class="btn btn-primary">Print all Product QR</button>
				    	</a>
				    @endif
		      	</div>
		    </div>

		    @if(session('success'))
		    <div class="alert alert-success">
		    	<strong>Congratulations! </strong>{{ session('success') }}<br>
		    </div>
		    @endif
		    
		    <div class="card shadow-sm">
			    <div class="card-body">
			        <table class="table table-bordered table-striped">
			        	<thead class="table-dark">
			        		<tr>
			        			<th>#</th>
			        			<th>ID</th>
			        			<th>Category</th>
			        			<th>Age</th>
			        			<th>Weight</th>
			        			<th>Gender</th>
			        			<th>Status</th>
			        			<th class="text-end">Action</th>
			        		</tr>
			        	</thead>
			        	<tbody>
			        		@foreach($products as $product)

			        		@php
			        			$product_detail = \App\Models\ProductDetail::where([['product_id', $product->id],['is_delete',0]])->latest('id')->first();
			        		@endphp
			        		
			        		<tr>
			        			<td>
			        				{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
			        			</td>
			        			<td>{{$product->unique_id}}</td>
			        			<td>{{$product_detail->category->name}}</td>
			        			<td>{{$product_detail->age}} {{$product_detail->age_type}}</td>
			        			<td>{{$product_detail->weight}}</td>
			        			<td>{{$product_detail->gender->name}}</td>
			        			<td>
			        				@if($product->status == 1)
			        					<span class="fw-bold badge bg-soft-success text-success">Active</span>
			        				@elseif($product->status == 2)
			        					<span class="badge bg-soft-danger text-danger">No More</span>
			        				@else
			        					<span class="badge bg-soft-primary text-primary">Sold Out</span>
			        				@endif
			        			</td>
			        			<td class="text-end">
			        				<a href="{{route('product.view', ['id' => $product->id])}}">
			        					<button class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> View</button>
			        				</a>
			        				<a href="{{route('product.download', ['id' => $product->id])}}" target="_blank">
			        					<button class="btn btn-sm btn-success"><i class="fa fa-download"></i> Print QR</button>
			        				</a>
			        				<a href="{{route('product.edit', ['id' => $product->id])}}">
			        					<button class="btn btn-sm btn-danger"><i class="fa fa-edit"></i> Update</button>
			        				</a>
			        			</td>
			        		</tr>
			        		@endforeach
			        	</tbody>
			        </table>
			    </div>
			    <div class="card-footer border-0">
					{!! $products->withQueryString()->links('pagination::bootstrap-5') !!}
				</div>
		    </div>
	  	</div>

	  	<!-- Bootstrap JS -->
	  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	</body>
</html>
