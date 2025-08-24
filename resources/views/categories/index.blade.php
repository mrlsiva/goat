<!DOCTYPE html>
<html lang="en">
	<head>
	  <meta charset="UTF-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <title>{{ config('app.name')}} | Category</title>
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
			    <h2 class="fw-bold">Category</h2>
			    <div>
			    	<a href="{{route('product.index')}}">
			    		<button class="btn btn-primary">Product</button>
			    	</a>
			    	<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategory">Add New Category</button>
			    </div>
		    </div>

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

		    <div class="card shadow-sm">
		      	<div class="card-body">
			        <table class="table table-bordered table-striped">
			          	<thead class="table-dark">
				            <tr>
				              <th>#</th>
				              <th>Name</th>
				              <th class="text-end">Action</th>
				            </tr>
			          	</thead>
			          	<tbody>
				          	@foreach($categories as $category)
					            <tr>
					              	<td>
										{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
									</td>
					              	<td>{{$category->name}}</td>
					              	<td class="text-end">
					              		<button class="btn btn-sm btn-danger updateBtn" data-id="{{$category->id}}"  data-name="{{$category->name}}" data-bs-toggle="modal" data-bs-target="#updateCategory">
								            <i class="fa fa-edit"></i> Update
								        </button>
					              	</td>
					            </tr>
				            @endforeach
			          	</tbody>
			        </table>
		      	</div>
		      	<div class="card-footer border-0">
					{!! $categories->withQueryString()->links('pagination::bootstrap-5') !!}
				</div>
		    </div>
	  	</div>

		<!-- Modal -->
		<div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addCategoryLabel">Category</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form method="post" action="{{route('category.store')}}" enctype="multipart/form-data">
	        			@csrf
						<div class="modal-body">
							<div class="mb-3">
							 	<label for="name" class="form-label">Name</label>
							 	<input type="text" class="form-control" id="name" name="name" placeholder="Category Name" required="">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade" id="updateCategory" tabindex="-1" aria-labelledby="updateCategoryLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="updateCategoryLabel">Category</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form method="post" action="{{route('category.update')}}" enctype="multipart/form-data">
	        			@csrf
						<div class="modal-body">
							<div class="mb-3">
								<input type="hidden" name="category_id" id="category_id">
							 	<label for="name" class="form-label">Name</label>
							 	<input type="text" class="form-control" id="category" name="category" placeholder="Category Name" required="">
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>

	  	<!-- Bootstrap JS -->
	  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	  	<script>
		
		document.querySelectorAll('.updateBtn').forEach(btn => {
		  btn.addEventListener('click', function () {
		    let id = this.getAttribute('data-id');
		    let name = this.getAttribute('data-name');
		    document.getElementById('category_id').value = id;
		    document.getElementById('category').value = name;
		  });
		});
		</script>


	</body>
</html>
