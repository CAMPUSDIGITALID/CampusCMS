@extends('faturcms::template.admin.main')

@section('title', 'Edit Gallery')

@section('content')

<!-- Main -->
<main class="app-content">

    <!-- Breadcrumb -->
    @include('faturcms::template.admin._breadcrumb', ['breadcrumb' => [
        'title' => 'Edit Gallery',
        'items' => [
            ['text' => 'Gallery', 'url' => route('admin.gallery.index')],
            ['text' => 'Edit Gallery', 'url' => '#'],
        ]
    ]])
    <!-- /Breadcrumb -->

    <!-- Row -->
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <!-- Tile -->
            <div class="tile">
                <!-- Tile Body -->
                <div class="tile-body">
                    <form id="form" method="post" action="{{ route('admin.gallery.update') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Judul Pelatihan <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="judul_gambar" class="form-control {{ $errors->has('judul_gambar') ? 'is-invalid' : '' }}" value="{{ $data->judul_gambar }}">
                                @if($errors->has('judul_gambar'))
                                <div class="small text-danger mt-1">{{ ucfirst($errors->first('judul_gambar')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Gambar</label>
                            <div class="col-md-10">
                                <input type="file" id="file" class="d-none" accept="image/*">
                                <a class="btn btn-sm btn-secondary btn-image" href="#"><i class="fa fa-image mr-2"></i>Pilih Gambar...</a>
                                <br>
                                <img src="{{ image('assets/images/dokumentasi/'.$data->gambar, 'gallery') }}" id="img-file" class="mt-2 img-thumbnail {{ $data->gambar != '' ? '' : 'd-none' }}" style="max-height: 150px">
                                <input type="hidden" name="gambar">
                                <input type="hidden" name="gambar_url">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"></label>
                            <div class="col-md-10">
                                <button type="submit" class="btn btn-theme-1"><i class="fa fa-save mr-2"></i>Simpan</button>
                            </div>
                        </div>
                    </form>
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

@include('faturcms::template.admin._modal-image', ['croppieWidth' => 800, 'croppieHeight' => 800])

@endsection

@section('js-extra')

@include('faturcms::template.admin._js-image', ['imageType' => 'gallery', 'croppieWidth' => 800, 'croppieHeight' => 800])

@endsection

@section('css-extra')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/croppie/croppie.css') }}">

@endsection