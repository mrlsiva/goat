<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ config('app.name')}} | Create Product</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

		<div class="card shadow-sm">

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

			<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
		    	<h4 class="mb-0">Create Product</h4>
		    	<a href="{{route('product.index')}}" class="btn btn-light btn-sm">⬅ Back</a>
		    </div>
			<div class="card-body">
				<form method="post" action="{{route('product.store')}}" enctype="multipart/form-data">
	        		@csrf

					<!-- Category Dropdown -->
					<div class="mb-3">
						<label for="category" class="form-label">Category</label>
						<select class="form-select" id="category" name="category" required>
							<option value="">-- Select Category --</option>
							@foreach($categories as $category)
								<option value="{{$category->id}}">{{$category->name}}</option>
							@endforeach
						</select>
					</div>

					<!-- Gender Dropdown -->
					<div class="mb-3">
						<label for="gender" class="form-label">Gender</label>
						<select class="form-select" id="gender" name="gender" required>
							<option value="">-- Select Gender --</option>
							@foreach($genders as $gender)
								<option value="{{$gender->id}}">{{$gender->name}}</option>
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
			          <input type="number" class="form-control" id="age" name="age" placeholder="Enter age" required min="1">
			        </div>

			        <!-- Weight -->
			        <div class="mb-3">
			          <label for="weight" class="form-label">Weight (In Kgs)</label>
			          <input type="number" class="form-control" id="weight" name="weight" placeholder="Enter Weight" required min="1">
			        </div>

					<!-- Image Upload -->
					<div class="mb-3">
						<label for="image" class="form-label">Product Image</label>
						<input type="file" class="form-control" id="image" name="image" accept="image/*" required>
					</div>

					<div class="d-flex justify-content-center">
					<!-- Submit Button -->
					<button type="submit" class="btn btn-success">Create Product</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
