@extends('template.front')
@section('title', 'Choose Day Reservation')
@section('head')
    <link rel="stylesheet" href="{{ asset('style/css/progress-indication.css') }}">
@endsection
@section('content')
    @include('transaction.reservation.progressbar')
    <div class="container mt-3">
        <div class="row justify-content-md-center">
            <div class="col-md-8 mt-2">
                <div class="card shadow-sm border">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="row mb-3">
                                    <label for="room_number" class="col-sm-2 col-form-label">Room</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="room_number" name="room_number"
                                            placeholder="col-form-label" value="{{ $room->number }} " readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="room_type" class="col-sm-2 col-form-label">Type</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="room_type" name="room_type"
                                            placeholder="col-form-label" value="{{ $room->type->name }} " readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="room_capacity" class="col-sm-2 col-form-label">Capacity</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="room_capacity" name="room_capacity"
                                            placeholder="col-form-label" value="{{ $room->capacity }} " readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="room_price" class="col-sm-2 col-form-label">Price / Day</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="room_price" name="room_price"
                                            placeholder="col-form-label" value="{{ Helper::convertToRupiah($room->price) }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-sm-12 mt-2">
                                <form method="POST" action="{{ route('payDownPayment', ['room' => $room->id]) }}">
                                    @csrf
                                    <input type="number" class="form-control" id="customer_id" name="customer_id">
                                    <div class="row mb-3">
                                        <label for="check_in" class="col-sm-2 col-form-label">Check In</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="check_in" name="check_in"
                                                placeholder="col-form-label" value="{{ $stayFrom }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="check_out" class="col-sm-2 col-form-label">Check Out</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="check_out" name="check_out"
                                                placeholder="col-form-label" value="{{ $stayUntil }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="how_long" class="col-sm-2 col-form-label">Total Day</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="how_long" name="how_long"
                                                placeholder="col-form-label"
                                                value="{{ $dayDifference }} {{ Helper::plural('Day', $dayDifference) }} "
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="total_price" class="col-sm-2 col-form-label">Total Price</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="total_price" name="total_price"
                                                placeholder="col-form-label"
                                                value="{{ Helper::convertToRupiah(Helper::getTotalPayment($dayDifference, $room->price)) }} "
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="minimum_dp" class="col-sm-2 col-form-label">Minimum DP</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="minimum_dp" name="minimum_dp"
                                                placeholder="col-form-label"
                                                value="{{ Helper::convertToRupiah($downPayment) }} " readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="downPayment" class="col-sm-2 col-form-label">Payment</label>
                                        <div class="col-sm-10">
                                            <input type="text"
                                                class="form-control @error('downPayment') is-invalid @enderror"
                                                id="downPayment" name="downPayment" placeholder="Input payment here"
                                                value="{{ old('downPayment') }}">
                                            @error('downPayment')
                                                <div class="text-danger mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-10" id="showPaymentType"></div>
                                    </div>
                                    <button type="submit" class="btn btn-primary float-end">Pay DownPayment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-2">
                <div class="card shadow-sm">
                    <div class="card-body p-3">
                        <h3>Already Customer?</h3>
                        Confirm by email
                        <div class="row">
                            <form method="POST" action="#">
                                <div class="col-md-9">
                                    <input type="email" class="form-control" id="return_email" name="return_email">
                                </div>
                                <div class="col-md-3">
                                    <button id="getcustomerinfo" type="submit" class="btn btn-primary float-end">Get
                                        Info</button>
                                </div>
                            </form>
                        </div>
                        <div class="row p-3">
                            <h4>or fill the form below</h4>
                        </div>
                        <form class="row g-3" method="POST" action="{{ route('customer.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="birthdate" class="form-label">Date of birth</label>
                                <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                                    id="birthdate" name="birthdate" value="{{ old('birthdate') }}">
                                @error('birthdate')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender"
                                    name="gender" aria-label="Default select example">
                                    {{-- <option selected hidden>Select</option> --}}
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                @error('gender')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="job" class="form-label">Job</label>
                                <input type="text" class="form-control @error('job') is-invalid @enderror"
                                    id="job" name="job" value="{{ old('job') }}">
                                @error('job')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-mg-12">
                                <label for="avatar" class="form-label">Profile Picture</label>
                                <input class="form-control" type="file" name="avatar" id="avatar">
                                @error('avatar')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn myBtn shadow-sm border float-end">Save</button>
                            </div>
                    </div>
                    </form>


                    <!--- MUST EDIT ----->
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer')

@endsection

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $('#downPayment').keyup(function() {
        $('#showPaymentType').text('Rp. ' + parseFloat($(this).val(), 10).toFixed(2).replace(
                /(\d)(?=(\d{3})+\.)/g, "$1.")
            .toString());
    });

    $(document).ready(function() {
        $('#getcustomerinfo').on('click', function(e) {
            e.preventDefault();
            $.ajax({

                url: "/getcustomerdata",
                type: "POST",
                data: {
                    email: document.getElementById('return_email').value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
                    if (response[0] == null) {
                        alert(
                            "You aren't registered in our system. Please complete your first booking."
                        );
                    } else {
                        document.getElementById('customer_id').value = response[0]["id"];
                        document.getElementById('name').value = response[0]["name"];
                        document.getElementById('email').value = response[0]["email"];
                        document.getElementById('gender').value = response[0]["gender"];
                        document.getElementById('job').value = response[0]["job"];
                        document.getElementById('address').value = response[0]["address"];
                        document.getElementById('birthday').value = response[0]["birthday"];

                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {

                    console.log(xhr.status);
                    console.log(thrownError);
                }
            });

        });
    });
</script>
