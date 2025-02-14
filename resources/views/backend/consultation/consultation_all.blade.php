@extends('admin.admin_master')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">All Consultation</h4>



                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <a href="{{ route('consultation.add') }}"
                                class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right">
                                <i class="fas fa-plus-circle">
                                    Add Consultation
                                </i>
                            </a>

                            <br>
                            <br>
                            <br>

                            {{-- <h4 class="card-title"> All Supplier Data </h4> --}}


                            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Sex</th>
                                        <th>Consultation Fee</th>
                                        <th>Status</th>
                                        <th>Action</th>

                                </thead>


                                <tbody>

                                    @foreach ($consultation as $key => $item)
                                        <tr>
                                            <td> {{ $key + 1 }} </td>
                                            <td>{{ date('d-m-Y', strtotime($item->date)) }}</td>
                                            <td> {{ $item['customer']['name'] }} </td>
                                            <td> {{ $item['customer']['age'] }} </td>
                                            <td> {{ $item['customer']['sex'] }} </td>
                                            <td> {{ $item->consultation_fee }} </td>

                                            <td>
                                                @if ($item->status == '0')
                                                    <span class="btn btn-warning">Unseen</span>
                                                @elseif($item->status == '1')
                                                    <span class="btn btn-success">Seen</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href=" {{ route('prescription.add', $item->id) }} "
                                                    class="btn btn-info sm"--}} title="Add Prescription">
                                                    <i class="fas fa-plus"></i>
                                                </a>

                                                <a href=" {{ route('consultation.delete', $item->id) }} "
                                                    class="btn btn-danger sm" title="Delete Data" id="delete"> <i
                                                        class="fas fa-trash-alt"></i>
                                                </a>

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->



        </div> <!-- container-fluid -->
    </div>
@endsection
