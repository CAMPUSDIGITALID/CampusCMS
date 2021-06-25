@extends('faturcms::template.admin.main')

@section('title', 'Report')

@section('content')

<!-- Main -->
<main class="app-content">

    <!-- Breadcrumb -->
    @include('faturcms::template.admin._breadcrumb', ['breadcrumb' => [
        'title' => 'Report',
        'items' => [
            ['text' => 'Report', 'url' => '#'],
        ]
    ]])
    <!-- /Breadcrumb -->

    <!-- Row -->
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <!-- Tile -->
            <div class="tile">
                <!-- Tile Title -->
                <div class="tile-title-w-btn">
                    <div></div>
                    <div>
                        <form method="get" action="{{ route('admin.report.index') }}">
                            <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                              </div>
                              <input type="text" name="tanggal" class="form-control form-control-sm" value="{{ $tanggal }}" readonly>
                              <div class="input-group-append">
                                  <button type="submit" class="btn btn-sm btn-dark" data-toggle="tooltip" title="Filter"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Tile Title -->
                <!-- Tile Body -->
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover table-bordered" id="table-report">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th width="100">Hari Ini</th>
                                    <th width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td align="center" colspan="3"><em>Loading...</em></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /Tile Body -->
            </div>
            <!-- /Tile -->
        </div>
        <!-- /Column -->
    </div>
    <!-- /Row -->
</main>
<!-- /Main -->

@endsection

@section('js-extra')

@include('faturcms::template.admin._js-table')

<script src="{{ asset('templates/vali-admin/js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // Datepicker
        $("input[name=tanggal]").datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });

    $(function(){
        $.ajax({
            type: "get",
            url: "{{ route('api.report') }}",
            data: {tanggal: "{{ $tanggal }}"},
            success: function(response){
                var html = '';
                if(response.data.length > 0){
                    for(var i=0; i<response.data.length; i++){
                        html += response.data[i].parent == true ? '<tr class="parent">' : '<tr class="child">';
                        html += '<td>' + response.data[i].title + '</td>';
                        html += '<td align="right">' + thousand_format(response.data[i].today) + '</td>';
                        html += '<td align="right">' + thousand_format(response.data[i].total) + '</td>';
                        html += '</tr>';
                    }
                }
                else{
                    html += '<tr>';
                    html += '<td colspan="3" class="text-center"><em class="text-danger">Tidak ada data.</em></td>';
                    html += '</tr>';
                }
                $("#table-report").find("tbody").html(html);
            }
        })
    });
</script>

@endsection

@section('css-extra')

<style type="text/css">
    #table-report tr th {text-align: center;}
    #table-report tr.child td:first-child {text-indent: 1.5rem;}
</style>

@endsection