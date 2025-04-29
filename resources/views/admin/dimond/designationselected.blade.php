@extends('layouts.admin')
@section('style')
<style>
  form {
    width: 300px;
    padding: 20px;
    border-radius: 5px;
  }

  input {
    width: 100%;
    padding: 2px;
    margin-bottom: 16px;
    box-sizing: border-box;
    background-color: transparent;
    color: white
  }
</style>
@endsection
@section('content')

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      @if (session('success'))
      <div class="alert text-white pl-3 pt-2 pb-2" style="background-color:green">
        {{ session('success') }}
      </div>
      @endif
      @if (session('error'))
      <div class="alert text-white pl-3 pt-2 pb-2" style="background-color:red">
        {{ session('error') }}
      </div>
      @endif
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Designation wise Diamond</h4>
      </div>

      <div class="">
        <table id="dailytable" class="table align-items-center table-flush table-borderless">
          <thead>
            <tr>
              <th>Action</th>
              <th>Dimond Name</th>
              <th>Barcode</th>
              <th>Date</th>
              <th>Current Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              @foreach($dailys as $index =>$dimond)
              <td>
                <a href="{{route('admin.dimond.show', $dimond->dimonds_barcode)}}"><i class="fa fa-eye" style="color:white;font-size:15px;background-color:rgba(255, 255, 255, 0.25);padding:8px;"></i></a>
              </td>
              <td>{{$dimond->dimonds->dimond_name}}</td>
              <td>{{$dimond->dimonds_barcode}}</td>
              <td>{{ \Carbon\Carbon::parse($dimond->updated_at)->format('d-m-Y') }}</td>
              <td>{{$dimond->dimonds->status}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div><!--End Row-->

@endsection