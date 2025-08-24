<!DOCTYPE html>
<html lang="en">
	<head>
	  <meta charset="UTF-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <title>{{ config('app.name')}} | All Product</title>
	  <!-- Bootstrap CSS -->
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	<body class="bg-light">

	 	<div class="container mt-5">
		    
		    <div class="card shadow-sm">
			    <div class="card-body">
			        <table class="table table-bordered table-striped">
			        	<thead class="table-dark">
			        		<tr>
			        			<th>#</th>
			        			<th>Product ID</th>
			        			<th class="text-end">QR Code</th>
			        		</tr>
			        	</thead>
			        	<tbody>
			        		@foreach($products as $product)
			        		<tr>
			        			<td>
			        				{{ $loop->iteration }}
			        			</td>
			        			<td>
			        				{{$product->unique_id}}
			        			</td>
			        			<td class="text-end">
			        				{!! QrCode::size(100)->generate(url('/products/'.$product->id.'/view')) !!} 
			        			</td>
			        		</tr>
			        		@endforeach
			        	</tbody>
			        </table>
			    </div>
		    </div>
	  	</div>

	  	<!-- Bootstrap JS -->
	  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	  	<script>
		    window.onload = function() {
		        setTimeout(() => {
		            window.print();
		        }, 500); // half a second delay
		    }
		</script>

	</body>
</html>
