<!DOCTYPE html>
<html lang="en">
	<head>
	  <meta charset="UTF-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <title>{{ config('app.name')}} | Product</title>
	  <!-- Bootstrap CSS -->
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body class="bg-light">

	 	<div class="container mt-5">
		    <div class="d-flex justify-content-between align-items-center mb-4">
		      	<h2 class="fw-bold">Product Detail</h2>
		      	<div>
			      	<a href="{{route('product.download_excel', ['id' => $product->id])}}">
				        <button class="btn btn-primary">Download Excel</button>
				    </a>
			      	@auth
			      	<a href="{{route('product.edit', ['id' => $product->id])}}" class="btn bg-primary text-white btn-sm">Edit</a>
			      	@endauth
		      	</div>
		    </div>

		    <div class="card">
			    <div class="card-body">
			        <h4>Product: @if($product->unique_number == null) {{ $product->unique_id }} @else {{ $product->unique_number }} @endif</h4>
			        <p>
			        	Status: 
			        	@if($product->status == 1)
						    <span class="fw-bold badge bg-success">Active</span>
						@elseif($product->status == 2)
						    <span class="badge bg-danger">No More</span>
						@else
						    <span class="badge bg-primary">Sold Out</span>
						@endif
			        </p>

			        @php
			        	$detail = \App\Models\ProductDetail::where([['product_id', $product->id],['is_delete',0]])->latest('id')->first();
			        @endphp

			        <p>
			        	Category: 						    
			        	<span class="fw-bold">{{$detail->category->name}}</span>
			        </p>

			        <p>
			        	Gender: 						    
			        	<span class="fw-bold">{{$detail->gender->name}}</span>
			        </p>

			        <p>
			        	Purchased Amount (In ₹): 						    
			        	<span class="fw-bold">{{$detail->purchased_amount}}</span>
			        </p>

			        <p>
			        	Sold Amount (In ₹): 						    
			        	<span class="fw-bold">@if($detail->sold_amount != null) {{$detail->sold_amount}} @else - @endif</span>
			        </p>

			        <h5>Product Details</h5>
			        @if($product->details && count($product->details) > 0)
			            <table class="table table-bordered">
			                <thead>
			                    <tr>
			                        <th>Image</th>
			                        <th>Age</th>
			                        <th>Weight(In Kgs)</th>
			                        <th>Updated On</th>
			                    </tr>
			                </thead>
			                <tbody>
			                    @foreach($product->details as $detail)
			                        <tr>
			                            <td>
			                                @if($detail->image != null)
			                                	<img src="{{ asset('storage/' . $detail->image) }}" class="logo-dark me-1" alt="Product" height="50">
			                                @else
			                                	<img src="{{ asset('no-image-icon.jpg') }}" class="logo-dark me-1" alt="Product" height="50">
			                                @endif
			                            </td>
			                            <td>{{ $detail->age }} {{ $detail->age_type }}</td>
			                            <td>{{ $detail->weight }}</td>
			                            <td>{{ \Carbon\Carbon::parse($detail->created_at)->format('d M Y') }}</td>
			                        </tr>
			                    @endforeach
			                </tbody>
			            </table>
			        @else
			            <p>No details available.</p>
			        @endif
			    </div>
			</div>



	  	</div>

	  	<!-- Bootstrap JS -->
	  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	</body>
</html>
