<!DOCTYPE html>
<html lang="en">
	<head>
	  <meta charset="UTF-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <title>{{ config('app.name')}} | Product - {{$product->unique_id}}</title>
	  <!-- Bootstrap CSS -->
	  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	<body class="bg-light">

	 	<div class="container mt-5">
		    
		    <div class="card shadow-sm">
			    <div class="card-body d-flex justify-content-center">

			    	{!! $qrCode !!}

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
