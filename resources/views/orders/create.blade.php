<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Orders</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Axios & Pusher -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.5/axios.min.js"></script>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
</head>
<body class="container py-5">

    <h1 class="mb-4">Order Dashboard</h1>

    <h2 class="mt-5">Create Order</h2>
    <form id="orderForm" method="POST" class="row g-3 mb-3">
        @csrf
        <div class="col-md-4">
            <input type="text" class="form-control" id="orderName" name="ordername" placeholder="Order Name" required>
        </div>
        <div class="col-md-4">
            <input type="number" class="form-control" id="orderAmount" name="ordertamount" placeholder="Total Amount" required>
        </div>
        <input type="hidden" value="pending" name="status">
        <div class="col-md-4">
            <button type="submit" class="btn btn-success">Submit Order</button>
        </div>
    </form>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
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
                    <td>
                        <select class="form-select" onchange="updateStatus({{ $order->id }}, this.value)">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processed" {{ $order->status == 'processed' ? 'selected' : '' }}>Processed</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

 

    <!-- JavaScript -->
    <script>
        function updateStatus(orderId, newStatus) {
            axios.post(`/orders/${orderId}/update-status`, {
                status: newStatus
            }).then(response => {
                console.log("Status updated");
            }).catch(error => {
                console.error("Error updating status", error);
            });
        }

        // Form submission
        document.getElementById("orderForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const form = e.target;
            axios.post("{{ url('/order_store') }}", new FormData(form))
                .then(response => {
                    alert("Order Created Successfully!");
                    form.reset();
                })
                .catch(error => {
                    alert("Error creating order!");
                    console.error(error.response?.data);
                });
        });

        // Pusher real-time update
        Pusher.logToConsole = true;
        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true
        });

        var channel = pusher.subscribe('orders');

        channel.bind('order.created', function (data) {
            const order = data.order;
            const row = document.createElement("tr");
            row.id = `order-${order.id}`;
            row.innerHTML = `
                <td>${order.id}</td>
                <td>${order.name}</td>
                <td>${order.total_amount}</td>
                <td>
                    <select class="form-select" onchange="updateStatus(${order.id}, this.value)">
                        <option value="pending" ${order.status === 'pending' ? 'selected' : ''}>Pending</option>
                        <option value="processed" ${order.status === 'processed' ? 'selected' : ''}>Processed</option>
                        <option value="completed" ${order.status === 'completed' ? 'selected' : ''}>Completed</option>
                    </select>
                </td>
            `;
            document.getElementById("orders").appendChild(row);
        });

        
    </script>

</body>
</html>
