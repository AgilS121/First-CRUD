<?php
namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    //

    public function index()
    {
        // memanggil model blog, kemudian menampilkan data terbaru dengan code latest,
        // paginate sebagai pembatas penampil jumlah perhalaman
        $blogs = Blog::latest()->paginate(10);
        // pemanggila sebuah view yang bernama index
        // kemudian di parsing ke dalam view dengan compact
        return view('blog.index', compact('blogs'));
    }

    // gunanya untuk menamplkan form yang akan diinputkan user
    public function create()
    {
        // mengembalikan nilai ke blog create
        return view('blog.create');
    }

    // proses pengisian ke database
    public function store(Request $request) 
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:png,jpg,jpeg',
            'title' => 'required',
            'content' => 'required'
        ]);

        // fungsi untuk upload image
        // membuat sebuah variabel baru yang berisi file image 
        $image = $request->file('image');
        // kemudian variabel itu dirubah lagi penamaannya, lalu disimpan ke store public/blog
        $image->storeAs('public/blogs', $image->hashName());

        // array sebagai wadah apa saja yang diisi
        $blog = Blog::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content
        ]);

        // kondisi dimana jika pengisian bernilai true maka akan redirect ataupun nilainya false akan redirect ke blog index
        if($blog) {
            return redirect()->route('blog.index')->with(['success']);
        } else {
            return redirect()->route('blog.index')->with(['error']);
        }
    }

    public function edit(Blog $blog)
    {
        return view('blog.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required'
        ]);

        $blog = Blog::findOrFail($blog->id);

        if($request->file('image') == "")
        {
            $blog->update([
                'title' => $request->title,
                'content' => $request->content
            ]);
        } else {
            // hapus gambar lama
            Storage::disk('local')->delete('public/blogs/'.$blog->image);

            // upload new image
            $image = $request->file('image');
            $image->storeAs('public/blogs', $image->hashName());

            $blog->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }

        if($blog) {
            return redirect()->route('blog.index')->with(['success']);
        } else {
            return redirect()->route('blog.index')->with(['error']);
        }

        
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        Storage::disk('local')->delete('public/blogs/'.$blog->image);
        $blog->delete();

        if($blog) {
            return redirect()->route('blog.index')->with(['success' => 'Data Sukses Dihapus']);
        } else {
            return redirect()->route('blog.index')->with(['error' => 'Data Gagal Dihapus']);
        }
    }
}
