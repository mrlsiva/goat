<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ config('app.name')}} | All Product</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		.qr-container {
			display: flex;
			flex-wrap: wrap;
			gap: 20px;
			/* spacing between QR codes */
			page-break-inside: avoid;
		}

		.qr-item {
			width: 120px;
			height: 120px;
			display: flex;
			align-items: center;
			justify-content: center;
			border: 1px solid #ccc;
			page-break-inside: avoid;
		}

		@media print {
			.qr-container {
				gap: 5px;
			}

			.qr-item {
				page-break-inside: avoid;
			}
		}
	</style>
</head>

<body class="bg-light">
	<div class="qr-container">
		@foreach($products as $product)

			@php
			    $detail = \App\Models\ProductDetail::where([['product_id', $product->id],['is_delete',0]])->latest('id')->first();
			@endphp

			<div class="qr-item">
				{!! QrCode::size(100)->generate(
				    "Product: {$product->unique_id}\n\n".
				    "Gender: {$detail->gender->name}\n\n".
				    "Category: {$detail->category->name}\n\n".
				    "Age: {$detail->age} {$detail->age_type}\n\n".
				    "Weight: {$detail->weight}\n\n".
				    "More: " . url('/products/'.$product->id.'/view')
				) !!}
			</div>
			
		@endforeach

		<!-- Add more QR codes as needed -->
	</div>
	<div class="container mt-5  d-none">

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