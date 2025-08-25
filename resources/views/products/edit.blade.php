<!DOCTYPE html>
<html lang="en">
	<head>
	  <meta charset="UTF-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <title>{{ config('app.name')}} | Edit Product</title>
	  <!-- Bootstrap CSS -->
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
		      	<h2 class="fw-bold">Product Detail</h2>
		    </div>

		    <div class="card">
			    <div class="card-body">
			    	<div class="d-flex justify-content-between align-items-center mb-4">
				      	<h4>Product: {{ $product->unique_id }}</h4>
				      	<a href="{{route('product.index')}}" class="btn bg-primary text-white btn-sm">Back</a>
				    </div>
			        
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

			        <h5>Product Details</h5>
			        @if($product->details && count($product->details) > 0)
			            <table class="table table-bordered">
			                <thead>
			                    <tr>
			                        <th>Image</th>
			                        <th>Gender</th>
			                        <th>Category</th>
			                        <th>Age</th>
			                        <th>Weight(In Kgs)</th>
			                        <th>Updated On</th>
			                    </tr>
			                </thead>
			                <tbody>
			                    @foreach($product->details as $detail)
			                        <tr>
			                            <td>
			                                <img src="{{ asset('storage/' . $detail->image) }}" class="logo-dark me-1" alt="Product" height="50">
			                            </td>
			                            <td>{{ $detail->gender->name }}</td>
			                            <td>{{ $detail->category->name }}</td>
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

			<div class="card mt-3">
			    <div class="card-body">

			    	@if ($errors->any())
		    <div class="alert alert-danger">
		    	<strong>Whoops!</strong> There were some problems with your input.<br><br>
		    	<ul>
		    		@foreach ($errors->all() as $error)
		    		<li>{{ $error }}</li>
		    		@endforeach
		    	</ul>
		    </div>
		    @endif

			    	@if(session('success'))
				    <div class="alert alert-success">
				    	<strong>Congratulations! </strong>{{ session('success') }}<br>
				    </div>
				    @endif

			        <h4>Update</h4>
			        <form method="post" action="{{route('product.update')}}" enctype="multipart/form-data">
		        		@csrf

		        		<input type="hidden" name="id" id="id" value="{{$product->id}}">
		        		<input type="hidden" name="category" id="category" value="{{$detail->category_id}}">
		        		<input type="hidden" name="gender" id="gender" value="{{$detail->gender_id}}">

		        		<!-- Image Upload -->
						<div class="mb-3">
							<label for="image" class="form-label">Product Image</label>
							<input type="file" class="form-control" id="image" name="image" accept="image/*">
						</div>

						<!-- Category Dropdown -->
						<div class="mb-3">
							<label for="category" class="form-label">Category</label>
							<select class="form-select" id="category" name="category" required disabled="">
								<option value="">-- Select Category --</option>
								@foreach($categories as $category)
									<option value="{{$category->id}}" {{$detail->category_id == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
								@endforeach
							</select>
						</div>

						<!-- Gender Dropdown -->
						<div class="mb-3">
							<label for="gender" class="form-label">Gender</label>
							<select class="form-select" id="gender" name="gender" required disabled="">
								<option value="">-- Select Gender --</option>
								@foreach($genders as $gender)
									<option value="{{$gender->id}}" {{$detail->gender_id == $gender->id ? 'selected' : '' }}>{{$gender->name}}</option>
								@endforeach
							</select>
						</div>

						<!-- Age Type Dropdown -->
						<div class="mb-3">
							<label for="age_type" class="form-label">Age Type</label>
							<select class="form-select" id="age_type" name="age_type" required>
								<option value="">-- Select Age Type --</option>
								<option value="Days">Days</option>
								<option value="Month">Month</option>
								<option value="Year">Year</option>
							</select>
						</div>

						<!-- Age -->
				        <div class="mb-3">
				          <label for="age" class="form-label">Age</label>
				          <input type="text" class="form-control" id="age" name="age" placeholder="Enter age" required >
				        </div>

				        <!-- Weight -->
				        <div class="mb-3">
				          <label for="weight" class="form-label">Weight (In Kgs)</label>
				          <input type="text" class="form-control" id="weight" name="weight" placeholder="Enter Weight" required >
				        </div>

						<!-- Status Dropdown -->
						<div class="mb-3">
							<label for="status" class="form-label">Status</label>
							<select class="form-select" id="status" name="status" required>
								<option value="">-- Status --</option>
								<option value="1" {{$product->status == 1 ? 'selected' : '' }}>Active</option>
								<option value="2" {{$product->status == 2 ? 'selected' : '' }}>No More</option>
								<option value="3" {{$product->status == 3 ? 'selected' : '' }}>Sold Out</option>
							</select>
						</div>

						<div class="d-flex justify-content-center">
						<!-- Submit Button -->
						<button type="submit" class="btn btn-success">Update Product</button>
						</div>
					</form>
			    </div>
			</div>



	  	</div>

	  	<!-- Bootstrap JS -->
	  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	</body>
</html>
