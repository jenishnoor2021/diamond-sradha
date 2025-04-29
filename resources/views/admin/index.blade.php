@extends('layouts.admin')

@section('content')

<!--Start Dashboard Content-->

<div class="card mt-3">
  <div class="card-content">
    <div class="row row-group m-0">
      <div class="col-12 col-lg-6 col-xl-3 border-light">
        <div class="card-body">
          <a href="/admin/processed/Delivered">
            <p class="mb-0 text-warning small-font" style="font-size:20px;">Delivered Dimonds </p>
          </a>
          <div class="progress my-3" style="height:3px;">
            <div class="progress-bar" style="width:55%"></div>
          </div>
          <h5 class="text-white mb-0">{{ $deliverd_count }} <span class="float-right"></span></h5>
        </div>
      </div>
      <div class="col-12 col-lg-6 col-xl-3 border-light">
        <div class="card-body">
          <a href="/admin/processed/Completed">
            <p class="mb-0 text-warning medium-font" style="font-size:20px;">Completed Dimonds </p>
          </a>
          <div class="progress my-3" style="height:3px;">
            <div class="progress-bar" style="width:55%"></div>
          </div>
          <h5 class="text-white mb-0">{{ $completed_count }} <span class="float-right"></span></h5>
        </div>
      </div>

      <div class="col-12 col-lg-6 col-xl-3 border-light">
        <div class="card-body">
          <a href="/admin/processed/Processing">
            <p class="mb-0 text-warning small-font" style="font-size:20px;">Processing Dimonds </p>
          </a>
          <div class="progress my-3" style="height:3px;">
            <div class="progress-bar" style="width:55%"></div>
          </div>
          <h5 class="text-white mb-0">{{ $processing_count }} <span class="float-right"></span></h5>
        </div>
      </div>
      <div class="col-12 col-lg-6 col-xl-3 border-light">
        <div class="card-body">
          <a href="/admin/processed/Pending">
            <p class="mb-0 text-warning small-font" style="font-size:20px;">Pending Dimonds</p>
          </a>
          <div class="progress my-3" style="height:3px;">
            <div class="progress-bar" style="width:55%"></div>
          </div>
          <h5 class="text-white mb-0">{{ $pending_count }} <span class="float-right"></span></h5>
        </div>
      </div>
      <div class="col-12 col-lg-6 col-xl-3 border-light">
        <div class="card-body">
          <a href="/admin/processed/OutterProcessing">
            <p class="mb-0 text-warning small-font" style="font-size:20px;">Outter Dimonds</p>
          </a>
          <div class="progress my-3" style="height:3px;">
            <div class="progress-bar" style="width:55%"></div>
          </div>
          <h5 class="text-white mb-0">{{ $outercount }} <span class="float-right"></span></h5>
        </div>
      </div>
      <div class="col-12 col-lg-6 col-xl-3 border-light">
        <div class="card-body">
          <p class="mb-0 text-warning small-font" style="font-size:20px;">Total Dimonds</p>
          <div class="progress my-3" style="height:3px;">
            <div class="progress-bar" style="width:55%"></div>
          </div>
          <h5 class="text-white mb-0">{{ $total_count }} <span class="float-right"></span></h5>
        </div>
      </div>
    </div>

    <br>
    <div class="row row-group m-0">
      <div class="col-12 col-lg-6">
        <table class="table">
          <thead>
            <tr>
              <th colspan=2>Inner Type</th>
            </tr>
            <tr>
              <th>Name</th>
              <th>count</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($innerdesignation as $inner)
            <tr>
              <td>
                <a href="/admin/designation/{{ $inner }}" target="_blank"><u>{{ $inner }}</u></a>
              </td>
              <td>{{ $innerCounts[$inner] ?? 0 }}</td>
            </tr>
            @endforeach
            <tr>
              <td>
                Inside Company
              </td>
              <td>{{ $processingDiamonds }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-12 col-lg-6">
        <table class="table align-items-center">
          <thead>
            <tr>
              <th colspan=2>Outter Type</th>
            </tr>
            <tr>
              <th>Name</th>
              <th>count</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($outerdesignation as $outer)
            <tr>
              <td>
                {{ $outer }}
              </td>
              <td>{{ $outterCounts[$outer] ?? 0 }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <br>
    <center>
      <h3>Worker Avg</h3>
    </center>
    <div class="row m-0  mt-4 mb-4">
      <form method="GET" action="">
        <label for="month">Select Month:</label>
        <input type="month" id="month" name="month" value="{{ $selectedMonth }}">
        <button type="submit">Filter</button>
      </form>
    </div>
    <div class="row row-group m-0">
      <table class="table align-items-center">
        <thead>
          <tr>
            <th>Worker Name</th>
            <th>Issue weight</th>
            <th>Diamonds</th>
            <th>Avg.</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($workerData as $data)
          <tr>
            <td>{{ $data['name'] }}</td>
            <td>{{ number_format($data['issueWeight'], 2) }}</td>
            <td>{{ $data['diamondCount'] }}</td>
            <td>{{ number_format($data['avgWeight'], 2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
</div>

<!--End Dashboard Content-->

<!--start overlay-->
<div class="overlay toggle-menu"></div>
<!--end overlay-->

@endsection