@extends('template.front')
@section('title', 'Count Person')
@section('head')
    <link rel="stylesheet" href="{{ asset('style/css/progress-indication.css') }}">
@endsection
@section('content')
    @include('transaction.reservation.progressbar')
    <div class="container mt-3">
        <div class="row justify-content-md-center">
            <div class="col-md-8 mt-2">
                <div class="card shadow-sm border">
                    <div class="card-body p-3">
                        <div class="card">
                            <div class="card-body">
                                <form class="row g-3" method="GET" action="/chooseRoom">
                                    <div class="col-md-12">
                                        <label for="count_person" class="form-label">
                                            How many person?
                                        </label>
                                        <input type="text"
                                            class="form-control @error('count_person') is-invalid @enderror"
                                            id="
                                                count_person"
                                            name="count_person" value="{{ old('count_person') }}">
                                        @error('count_person')
                                            <div class="text-danger mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <label for="check_in" class="form-label">
                                            From
                                        </label>
                                        <input type="date" class="form-control @error('check_in') is-invalid @enderror"
                                            id="
                                                check_in" name="check_in"
                                            value="{{ old('check_in') }}">
                                        @error('check_in')
                                            <div class="text-danger mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <label for="check_out" class="form-label">
                                            Until
                                        </label>
                                        <input type="date" class="form-control @error('check_out') is-invalid @enderror"
                                            id="
                                                check_out" name="check_out"
                                            value="{{ old('check_out') }}">
                                        @error('check_out')
                                            <div class="text-danger mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn myBtn shadow-sm border float-end">Next</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
@endsection
