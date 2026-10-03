<table class="w-full bg-white rounded-lg shadow text-sm">
  <thead class="bg-gray-50 text-left"><tr><th class="p-3">Order</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
  <tbody>@foreach($orders as $o)
    <tr class="border-t"><td class="p-3"><a class="text-blue-600" href="{{ route('admin.orders.show', $o) }}">{{ $o->order_no }}</a></td>
      <td>{{ $o->name }}<br><span class="text-gray-500">{{ $o->phone }}</span></td><td>৳{{ number_format($o->total) }}</td>
      <td>{{ $o->payment_method }} / {{ $o->payment_status }}</td><td class="capitalize">{{ $o->status }}</td><td>{{ $o->created_at->format('d M, h:i A') }}</td></tr>
  @endforeach</tbody></table>
