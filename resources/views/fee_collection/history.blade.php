<x-app-layout>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Fee History for {{ $student->name }} ( Phone : {{ $student->phone }} )
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Fee Category</th>
                                        <th>Total Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Remaining</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Payments & Receipts</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fees as $fee)
                                        <tr>
                                            <td>{{ $fee->category->name }}</td>
                                            <td>{{ number_format($fee->amount, 2) }}</td>
                                            <td>{{ number_format($fee->paid_amount, 2) }}</td>
                                            <td>{{ number_format($fee->amount - $fee->paid_amount, 2) }}</td>
                                            <td>{{ $fee->due_date ? \Carbon\Carbon::parse($fee->due_date)->format('Y-m-d') : 'N/A' }}
                                            </td>
                                            <td>
                                                @if ($fee->status == 'paid')
                                                    <span class="badge badge-success">Paid</span>
                                                @elseif($fee->status == 'partial')
                                                    <span class="badge badge-warning">Partial</span>
                                                @else
                                                    <span class="badge badge-danger">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($fee->payments->count() > 0)
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($fee->payments as $payment)
                                                            <li>
                                                                {{ number_format($payment->amount_paid, 2) }} on
                                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}
                                                                <a href="{{ route('fee.collection.receipt', $payment->receipt_number) }}"
                                                                    class="btn btn-xs btn-info float-right"
                                                                    target="_blank"><i class="fas fa-print"></i>
                                                                    Receipt</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <em>No payments</em>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No fee records found for this
                                                student.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-app-layout>
