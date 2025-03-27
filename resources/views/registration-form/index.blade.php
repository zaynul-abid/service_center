<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Service Records</h2>
        <a href="{{ route('services.create') }}" class="btn btn-success">+ Create New Service</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>Booking ID</th>
                    <th>Customer Name</th>
                    <th>Vehicle Number</th>
                    <th>Vehicle Type</th>
                    <th> refference number</th>



        
                    <th> booking_date</th>
                    <th>booking_time</th>
                    <th>vehicle_company</th>
                    <th>vehicle_model</th>
                    <th>fuel type</th>
                    <th>fuel_level</th>
                    <th>km_driven</th>
                    <th>place</th>
                    <th>contact_number_1 </th>
                    <th>contact_number_2</th>
                    <th>service_details </th>
                    <th>customer_complaint</th>
                    <th>remarks</th>
                    
                     <th>cost </th>
                    <th>expected_delivery_date</th>
                    <th>expected_delivery_time</th>
                    <th>Photos</th>
                    <th>Actions</th>
                 


                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                    <tr>
                        <td>{{ $service->booking_id }}</td>
                        <td>{{ $service->customer_name }}</td>
                        <td>{{ $service->vehicle_number }}</td>
                        <td>{{ $service->vehicle_type }}</td>
                        <td>{{ $service->reference_number }}</td>


                        <td>{{ $service->booking_date }}</td>
                        <td>{{ $service->booking_time    }}</td>
                        <td>{{ $service->vehicle_company }}</td>
                        <td>{{ $service->vehicle_model  }}</td>
                        <td>{{ $service->fuel_type }}</td>
                        <td>{{ $service->fuel_level }}</td>
                        <td>{{ $service->km_driven }}</td>
                        <td>{{ $service->place }}</td>
                        <td>{{ $service->contact_number_1 }}</td>
                        <td>{{ $service->contact_number_2 }}</td>

                        <td>{{ $service->service_details }}</td>
                        <td>{{ $service->customer_complaint }}</td>
                        <td>{{ $service->remarks }}</td>
                        <td>{{ $service->cost }}</td>

                        <td>{{ $service->expected_delivery_date	 }}</td>
                        <td>{{ $service->expected_delivery_time }}</td>







                        <td>
                            @if (!empty($service->photos) && is_string($service->photos))
                                @foreach (json_decode($service->photos, true) as $index => $photo)
                                <img src="{{ asset('storage/' . $photo) }}" 
                                width="80" height="50" 
                                class="img-thumbnail" 
                                style="cursor: pointer;" 
                                data-bs-toggle="modal" 
                                data-bs-target="#imageModal" 
                                onclick="showImage('{{ asset('storage/' . $photo) }}')">
                           
                                @endforeach
                            @else
                                No Photos
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img id="fullImage" src="" class="img-fluid" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>

<script>
    function showImage(imageSrc) {
        document.getElementById("fullImage").src = imageSrc;
    }
</script>

</body>
</html>
