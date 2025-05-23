@extends('layout.layout')
@section('content')


    <div class="page-wrapper">
        <!-- Page Content-->
        <div class="page-content-tab">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <div class="float-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Metrica</a></li>
                                    <li class="breadcrumb-item"><a href="#">Forms</a></li>
                                    <li class="breadcrumb-item active">Form Elements</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Find your vehicle</h4>
                        </div>
                        <!--end page-title-box-->
                    </div>
                    <!--end col-->
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Search by VIN or Frame Number</h4>
                                <p class="text-muted mb-0">VIN or frame number (VIN example: ZFA31200000503646 )</p>
                            </div><!--end card-header-->
                            <div class="card-body">
                                <form action="{{ route('vehicle.info') }}" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" class="form-control" id="" name="vin" aria-describedby="" placeholder="Vin number">
                                        <small id="emailHelp" class="form-text text-muted">Vehicle search by VIN, frame, registration number or part number</small>
                                    </div>
                                    <button type="submit" class="btn btn-de-primary">Submit</button>
                                </form>
                                <hr>
                                <form action="{{ route('vehicle.groups') }}" method="post">
                                    @csrf
{{--                                    <div class="mb-3">--}}
{{--                                        <input type="text" class="form-control" id="" name="vin" aria-describedby="" placeholder="Vin number">--}}
{{--                                        <small id="emailHelp" class="form-text text-muted">Vehicle search by VIN, frame, registration number or part number</small>--}}
{{--                                    </div>--}}
                                    <button type="submit" class="btn btn-de-primary">Get vehicle info</button>
                                </form>
                                <hr>
                                <form action="{{ route('vehicle.list.groups') }}" method="post">
                                    @csrf
                                    {{--                                    <div class="mb-3">--}}
                                    {{--                                        <input type="text" class="form-control" id="" name="vin" aria-describedby="" placeholder="Vin number">--}}
                                    {{--                                        <small id="emailHelp" class="form-text text-muted">Vehicle search by VIN, frame, registration number or part number</small>--}}
                                    {{--                                    </div>--}}
                                    <button type="submit" class="btn btn-de-primary">Get vehicle list info</button>
                                </form>
                                <hr>
                                <form action="{{ route('vehicle.group.parts') }}" method="post">
                                    @csrf
                                    {{--                                    <div class="mb-3">--}}
                                    {{--                                        <input type="text" class="form-control" id="" name="vin" aria-describedby="" placeholder="Vin number">--}}
                                    {{--                                        <small id="emailHelp" class="form-text text-muted">Vehicle search by VIN, frame, registration number or part number</small>--}}
                                    {{--                                    </div>--}}
                                    <button type="submit" class="btn btn-de-primary">Get vehicle groups</button>
                                </form>
                                <hr>
                                <form action="{{ route('vehicle.group.parts.all') }}" method="post">
                                    @csrf
                                    {{--                                    <div class="mb-3">--}}
                                    {{--                                        <input type="text" class="form-control" id="" name="vin" aria-describedby="" placeholder="Vin number">--}}
                                    {{--                                        <small id="emailHelp" class="form-text text-muted">Vehicle search by VIN, frame, registration number or part number</small>--}}
                                    {{--                                    </div>--}}
                                    <button type="submit" class="btn btn-de-primary">Get vehicle groups all</button>
                                </form>
                                <hr>
                                <form action="{{ route('vehicle.unit.parts') }}" method="post">
                                    @csrf
                                    {{--                                    <div class="mb-3">--}}
                                    {{--                                        <input type="text" class="form-control" id="" name="vin" aria-describedby="" placeholder="Vin number">--}}
                                    {{--                                        <small id="emailHelp" class="form-text text-muted">Vehicle search by VIN, frame, registration number or part number</small>--}}
                                    {{--                                    </div>--}}
                                    <button type="submit" class="btn btn-de-primary">Get unit parts</button>
                                </form>
                                <form action="{{ route('aftermarket.data') }}" method="post">
                                    @csrf
                                    {{--                                    <div class="mb-3">--}}
                                    {{--                                        <input type="text" class="form-control" id="" name="vin" aria-describedby="" placeholder="Vin number">--}}
                                    {{--                                        <small id="emailHelp" class="form-text text-muted">Vehicle search by VIN, frame, registration number or part number</small>--}}
                                    {{--                                    </div>--}}
                                    <button type="submit" class="btn btn-de-primary">Get aftermarket data</button>
                                </form>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div>

            </div>
        </div>
    </div>

@endsection
