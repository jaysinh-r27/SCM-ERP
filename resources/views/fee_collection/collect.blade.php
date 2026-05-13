<x-app-layout>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Collect Fee Payment</h3>
                        </div>
                        <form action="{{ route('fee.collection.store.collect') }}" method="POST" id="CollectFeeForm">
                            @csrf
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Success!</h5>
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-ban"></i> Please fix the following errors:</h5>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label>Select Pending Fee <span class="text-danger">*</span></label>
                                    <select name="student_fee_id" id="student_fee_id"
                                        class="form-control select2 @error('student_fee_id') is-invalid @enderror">
                                        <option value="">Select Pending Fee</option>
                                        @foreach ($studentFees as $fee)
                                            <option value="{{ $fee->id }}"
                                                data-remaining="{{ $fee->amount - $fee->paid_amount }}">
                                                {{ $fee->student->name }} - {{ $fee->category->name }} (Remaining:
                                                {{ number_format($fee->amount - $fee->paid_amount, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('student_fee_id')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Amount to Pay <span class="text-danger">*</span></label>
                                    <input type="number" name="amount_paid" id="amount_paid"
                                        class="form-control @error('amount_paid') is-invalid @enderror" step="0.01">
                                    @error('amount_paid')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" id="payment_date"
                                        class="form-control @error('payment_date') is-invalid @enderror"
                                        value="{{ date('Y-m-d') }}">
                                    @error('payment_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="payment_method"
                                        class="form-control select2 @error('payment_method') is-invalid @enderror">
                                        <option value="Cash">Cash</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                    @error('payment_method')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="{{ route('fee.collection.dashboard') }}"
                                    class="btn btn-default mr-2">Cancel</a>
                                <button type="submit" class="btn btn-success">Process Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @section('script')
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    theme: 'bootstrap4'
                }).on('change', function() {
                    $(this).valid();
                });

                $('#student_fee_id').on('change', function() {
                    var remaining = $(this).find(':selected').data('remaining');
                    if (remaining) {
                        $('#amount_paid').val(remaining);
                        $('#amount_paid').attr('max', remaining);
                    } else {
                        $('#amount_paid').val('');
                        $('#amount_paid').removeAttr('max');
                    }
                    $('#amount_paid').valid();
                });

                $('#CollectFeeForm').validate({
                    ignore: ':hidden:not(.select2-hidden-accessible)',
                    rules: {
                        student_fee_id: {
                            required: true
                        },
                        amount_paid: {
                            required: true,
                            number: true,
                            min: 0.01
                        },
                        payment_date: {
                            required: true,
                            date: true
                        },
                        payment_method: {
                            required: true
                        }
                    },
                    messages: {
                        student_fee_id: {
                            required: "Please select a pending fee"
                        },
                        amount_paid: {
                            required: "Please enter the payment amount",
                            number: "Please enter a valid amount",
                            min: "Amount must be greater than zero",
                            max: "Amount cannot exceed the remaining balance"
                        },
                        payment_date: {
                            required: "Please select a payment date",
                            date: "Please enter a valid date"
                        },
                        payment_method: {
                            required: "Please select a payment method"
                        }
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        if (element.hasClass('select2-hidden-accessible')) {
                            error.insertAfter(element.next('.select2-container'));
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                        if ($(element).hasClass('select2-hidden-accessible')) {
                            $(element).next('.select2-container').find('.select2-selection').addClass(
                                'border-danger');
                        }
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                        if ($(element).hasClass('select2-hidden-accessible')) {
                            $(element).next('.select2-container').find('.select2-selection').removeClass(
                                'border-danger');
                        }
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
