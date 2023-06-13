<?php

namespace Campusdigital\CampusCMS\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Campusdigital\CampusCMS\Models\Program;
use Campusdigital\CampusCMS\Models\KategoriProgram;

class ProgramController extends Controller
{
    /**
     * Menampilkan data program
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check Access
        has_access(generate_method(__METHOD__), Auth::user()->role);

        // Data program
        $program = Program::join('users','program.author','=','users.id_user')->join('kategori_program','program.program_kategori','=','kategori_program.id_kp')->orderBy('program_at','desc')->get();
		
        // View
        return view('faturcms::admin.program.index', [
            'program' => $program,
        ]);
    }

    /**
     * Menampilkan form tambah program
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Check Access
        has_access(generate_method(__METHOD__), Auth::user()->role);

        // Kategori
        $kategori = KategoriProgram::all();

        // View
        return view('faturcms::admin.program.create', [
            'kategori' => $kategori,
        ]);
    }

    /**
     * Menambah program
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //get array of materi program
        $materi = $request->program_materi;
        $str_arr = explode (",", $materi); 
        $json_materi = json_encode($str_arr);

        // Validasi
        $validator = Validator::make($request->all(), [
            'judul_program' => 'required|max:255',
            'kategori' => 'required',
            'program_materi' => 'required',
            'program_manfaat' => 'required',
        ], array_validation_messages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput($request->only([
                'judul_program',
                'kategori',
                'program_materi',
                'program_manfaat',
            ]));
        }
        // Jika tidak ada error
        else{
            // Menambah data
            $program = new Program;
            $program->program_title = $request->judul_program;
            $program->program_permalink = slugify($request->judul_program, 'program', 'program_permalink', 'id_program', null);
            $program->program_gambar = name_image($request, 'program_gambar','assets/images/program');
            $program->program_kategori = $request->kategori;
            $program->konten = $request->konten;
            $program->author = Auth::user()->id_user;
            $program->program_at = date('Y-m-d H:i:s');

            $program->gambar_bnsp = name_image($request, 'gambar_bnsp', 'assets/images/bnsp');
            $program->program_materi = $json_materi;
            $program->program_manfaat = htmlentities(upload_quill_image($request->program_manfaat, 'assets/images/konten-program/')); 
            $program->price = $request->price;
            
            $program->save();
        }

        // Redirect
        return redirect()->route('admin.program.index')->with(['message' => 'Berhasil menambah data.']);
    }

    /**
     * Menampilkan form edit program
     *
     * int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check Access
        has_access(generate_method(__METHOD__), Auth::user()->role);

    	// Data program
    	$program = Program::findOrFail($id);

        $decode_array = json_decode($program->program_materi, true);
        $program->program_materi = implode(',',$decode_array);
    
        // Kategori
        $kategori = KategoriProgram::all();

        // View
        return view('faturcms::admin.program.edit', [
        	'program' => $program,
        	'kategori' => $kategori,
        ]);
    }

    /**
     * Mengupdate program
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //get array of materi program
        $materi = $request->program_materi;
        $str_arr = explode (",", $materi); 
        $json_materi = json_encode($str_arr);

        // Validasi
        $validator = Validator::make($request->all(), [
            'judul_program' => 'required|max:255',
            'kategori' => 'required',
            'program_materi' => 'required',
            'program_manfaat' => 'required',
        ], array_validation_messages());
        
        // Mengecek jika ada error
        if($validator->fails()){
            // Kembali ke halaman sebelumnya dan menampilkan pesan error
            return redirect()->back()->withErrors($validator->errors())->withInput($request->only([
                'judul_program',
                'kategori',
                'program_materi',
                'program_manfaat',
            ]));
        }
        // Jika tidak ada error
        else{
            // Mengupdate data
            $program = Program::find($request->id);
            
            $program->program_title = $request->judul_program;
            $program->program_permalink = slugify($request->judul_program, 'program', 'program_permalink', 'id_program', $request->id);
            $program->program_gambar = name_image($request, 'program_gambar','assets/images/program') != '' ? name_image($request, 'program_gambar','assets/images/program') : $program->program_gambar;
            $program->program_kategori = $request->kategori;
            $program->konten = $request->konten;

            $program->gambar_bnsp = name_image($request, 'gambar_bnsp', 'assets/images/bnsp');
            $program->program_materi = $json_materi;
            $program->program_manfaat = htmlentities(upload_quill_image($request->program_manfaat, 'assets/images/konten-program/')); 
            $program->price = $request->price;

            $program->save();
        }

        // Redirect
        return redirect()->route('admin.program.edit', ['id' => $request->id])->with(['message' => 'Berhasil mengupdate data.']);
    }

    /**
     * Menghapus program
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Check Access
        has_access(generate_method(__METHOD__), Auth::user()->role);
        
    	// Menghapus data
        $program = Program::find($request->id);
        $program->delete();

        // Redirect
        return redirect()->route('admin.program.index')->with(['message' => 'Berhasil menghapus data.']);
    }
      
    /**
     * Menampilkan file gambar
     *
     * @return \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function showImages(Request $request)
    {
        echo json_encode(generate_file(public_path('assets/images/program'), ['..png']));
    }
}
