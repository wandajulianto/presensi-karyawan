@extends('layouts.presensi')

@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Riwayat Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <!-- Month Selection -->
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <select name="month" id="month" class="form-control">
                            @php
                                $selectedMonth = request('month') ?? date('m');
                            @endphp
                            @foreach ($months as $value => $label)
                                <option value="{{ $value }}" {{ $value == $selectedMonth ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Year Selection -->
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <select name="year" id="year" class="form-control">
                            @php
                                $startYear = 2025;
                                $thisYear = date('Y');
                                $selectedYear = request('year') ?? $thisYear;
                            @endphp
                            @for ($year = $startYear; $year <= $thisYear; $year++)
                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- Search Button -->
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" id="getData">
                            <ion-icon name="search-outline"></ion-icon>
                            Cari
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search Results Container -->
            <div class="row">
                <div class="col-12">
                    <div id="result"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myScript')
    <script>
        $(function() {
            /**
             * Handle search button click event
             */
            $("#getData").click(function() {
                const month = $("#month").val();
                const year = $("#year").val();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('presensi.history.search') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        month: month,
                        year: year
                    },
                    success: function(response) {
                        $("#result").html(response);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat mengambil data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            /**
             * Trigger search on page load if month/year parameters exist
             */
            @if(request()->has('month') || request()->has('year'))
                $(document).ready(function() {
                    $("#getData").trigger('click');
                });
            @endif
        });
    </script>
@endpush