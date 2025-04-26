<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Orders</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Axios + Pusher -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.5/axios.min.js"></script>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
</head>
<body class="container py-5">
    <h1 class="mb-4">Order Dashboard</h1>

    <div class="table-responsive">
        <table class="table table-bordered" id="orders-table">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="orders">
                @foreach ($orders as $order)
                    <tr id="order-{{ $order->id }}">
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->total_amount }}</td>
                        <td><span class="badge bg-secondary">{{ $order->status }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        // Pusher Setup
        Pusher.logToConsole = true;
        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true
        });

        var channel = pusher.subscribe('orders');

        // New Order Created
        channel.bind('order.created', function(data) {
            const order = data.order;

            const newRow = document.createElement("tr");
            newRow.id = `order-${order.id}`;
            newRow.innerHTML = `
                <td>${order.id}</td>
                <td>${order.name}</td>
                <td>${order.total_amount}</td>
                <td><span class="badge bg-secondary">${order.status}</span></td>
            `;
            document.getElementById("orders").appendChild(newRow);
        });

        // Order Status Updated
        channel.bind('order.status.updated', function(data) {
            const order = data.order;
            const row = document.getElementById(`order-${order.id}`);
            if (row) {
                row.innerHTML = `
                    <td>${order.id}</td>
                    <td>${order.name}</td>
                    <td>${order.total_amount}</td>
                    <td><span class="badge bg-secondary">${order.status}</span></td>
                `;
            }
        });
    </script>

</body>
</html>
