@extends('faturcms::template.admin.main')

@section('title', 'Edit Program')

@section('content')

<!-- Main -->
<main class="app-content">

    <!-- Breadcrumb -->
    @include('faturcms::template.admin._breadcrumb', ['breadcrumb' => [
        'title' => 'Edit Program',
        'items' => [
            ['text' => 'Program', 'url' => route('admin.program.index')],
            ['text' => 'Edit Program', 'url' => '#'],
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
                    @if(Session::get('message') != null)
                    <div class="alert alert-dismissible alert-success">
                        <button class="close" type="button" data-dismiss="alert">×</button>{{ Session::get('message') }}
                    </div>
                    @endif
                    <form id="form" method="post" action="{{ route('admin.program.update') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $program->id_program }}">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Judul Program <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="judul_program" class="form-control {{ $errors->has('judul_program') ? 'is-invalid' : '' }}" value="{{ $program->program_title }}">
                                @if($errors->has('judul_program'))
                                <div class="small text-danger mt-1">{{ ucfirst($errors->first('judul_program')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Kategori <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <select name="kategori" class="form-control {{ $errors->has('kategori') ? 'is-invalid' : '' }}" >
                                    <option value="" disabled selected>--Pilih--</option>
                                    @foreach($kategori as $data)
                                    <option value="{{ $data->id_kp }}" {{ $program->program_kategori === $data->id_kp ? 'selected' : '' }}>{{ $data->kategori }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('kategori'))
                                <div class="small text-danger mt-1">{{ ucfirst($errors->first('kategori')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Gambar Promo/Program</label>
                            <div class="col-md-10">
                                <input type="file" id="file" name="program_gambar" accept="image/*">
                                <br>
                                <img src="{{ image('assets/images/program/'.$program->program_gambar, 'program') }}" id="img-file" class="mt-2 img-thumbnail {{ $program->program_gambar != '' ? '' : 'd-none' }}" style="max-height: 150px">
                                <input type="hidden" name="gambar">
                                <input type="hidden" name="gambar_url">
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Deskripsi Pelatihan <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <textarea name="konten" class="form-control {{ $errors->has('konten') ? 'is-invalid' : '' }}">{{ $program->konten }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Materi Pelatihan <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" name="program_materi" class="form-control {{ $errors->has('program_materi') ? 'is-invalid' : '' }}" value="{{ $program->program_materi }}">
                                @if($errors->has('program_materi'))
                                <div class="small text-danger mt-1">{{ ucfirst($errors->first('program_materi')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Penjelasan Per poin materi</label>
                            <div class="col-md-10">
                                <textarea name="materi_desk" class="form-control {{ $errors->has('materi_desk') ? 'is-invalid' : '' }}">{{ $program->materi_desk }}</textarea>
                                @if($errors->has('materi_desk'))
                                <div class="small text-danger mt-1">{{ ucfirst($errors->first('materi_desk')) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Price</label>
                            <div class="col-md-10">
                                <input type="number" name="price" class="form-control" value="{{ $program->price }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Manfaat Pelatihan</label>
                            <div class="col-md-10">
                                <textarea name="program_manfaat" class="d-none"></textarea>
                                <div id="editor">{!! html_entity_decode($program->program_manfaat) !!}</div> 
                                @if($errors->has('program_manfaat'))
                                <div class="small text-danger mt-1">{{ ucfirst($errors->first('program_manfaat')) }}</div>
                                @endif
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

@include('faturcms::template.admin._modal-image', ['noCroppie' => true, 'croppieWidth' => 640, 'croppieHeight' => 360])

@endsection

@section('js-extra')

@include('faturcms::template.admin._js-image', ['imageType' => 'program', 'noCroppie' => true, 'croppieWidth' => 640, 'croppieHeight' => 360])

@include('faturcms::template.admin._js-editor')

<script type="text/javascript">
    // Quill
    // generate_quill("#editor");
    generate_quill("#editor");

    // Button Submit
    $(document).on("click", "button[type=submit]", function(e){
        var myEditor = document.querySelector('#editor');
        var html = myEditor.children[0].innerHTML;
        $("textarea[name=program_manfaat]").text(html);
        $("#form").submit();
    });
</script>

@endsection

@section('css-extra')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/croppie/croppie.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/quill/quill.snow.css') }}">

@endsection