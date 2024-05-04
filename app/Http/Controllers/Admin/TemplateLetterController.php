<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Select2\ResponsePaginate;
use Illuminate\Http\Request;
use App\Models\TemplateLetter as model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class TemplateLetterController extends Controller
{
    private $viewPath = 'admin.letter-template';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("$this->viewPath.index");
    }

    public function data()
    {
        $model = model::query()
            ->leftJoin('users as owner', 'owner.id', '=', 'template_letters.owned_id')
            ->selectRaw('
                template_letters.*,
                owner.name as owner_name
            ');

        return datatables($model)
            ->addIndexColumn()
            ->editColumn('id', function ($model) {
                return md5("--$model->id--");
            })
            ->addColumn('action', function ($model) {
                return "
                    <a href='".route('admin.template-undangan.show', md5("--$model->id--"))."' class='btn btn-sm btn-warning'><i class='ti ti-pencil'></i></a>
                    <a href='javascript:;' class='btn btn-sm btn-danger' onclick='handleDelete(\"" . md5("--$model->id--") . "\")'><i class='ti ti-trash'></i></a>
                    ";
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create()
    {
        return view("$this->viewPath.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'upload_zip' => 'required|file|mimes:zip|max:10240',
        ]);

        $validated['owned_id'] = auth()->id();

        DB::beginTransaction();
        try {
            $model = model::create($validated);

            $file = $request->file('upload_zip');
            $zip = new ZipArchive;
            $zip->open($file);
            $zip->extractTo(storage_path('app/public/template-undangan/' . $model->id));

            $model->uploadTemplateLetter()->create([
                'path_template' => 'template-undangan/' . $model->id
            ]);

            DB::commit();
            return redirect()->route('admin.template-undangan.show', md5("--$model->id--"))->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = model::query()
            ->whereRaw('MD5(CONCAT("--", id, "--")) = ?', $id)
            ->firstOrFail();

        return view("$this->viewPath.show", compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'legends' => 'nullable|array',
            'legends.*' => 'required|string',
            'upload_zip' => 'nullable|file|mimes:zip|max:10240',
            'legend.*' => 'required|string',
            'legend_name.*' => 'required|string',
            'legend_type.*' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $model = model::query()
                ->whereRaw('MD5(CONCAT("--", id, "--")) = ?', $id)
                ->first();

            if (!$model) {
                return back()->withErrors(['error' => 'Data tidak ditemukan']);
            }

            $model->fill($validated);
            $model->save();

            $model->legends()->delete();
            $model->legends()->createMany(
                collect($validated['legends'])->map(function ($legend) use ($request) {
                    return ['legend' => $request->legend[$legend], 'description' => $request->legend_name[$legend], 'type' => $request->legend_type[$legend]];
                })->toArray()
            );

            if ($request->hasFile('upload_zip')) {
                try {
                    Storage::deleteDirectory($model->uploadTemplateLetter->path_template);
                } catch (\Exception $e) {}

                $file = $request->file('upload_zip');
                $zip = new ZipArchive;
                $zip->open($file);
                $zip->extractTo(storage_path('app/public/template-undangan/' . $model->id));

                $model->uploadTemplateLetter()->update([
                    'path_template' => 'template-undangan/' . $model->id
                ]);
            }

            DB::commit();
            return redirect()->route('admin.template-undangan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        model::query()
            ->whereRaw('MD5(CONCAT("--", id, "--")) = ?', $id)
            ->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }


    public function uploadTemplateLetter(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|string',
            'title' => 'nullable|string',
            'upload_zip' => 'required|file|mimes:zip|max:10240'
        ]);

        $id = $request->input('id');

        $model = model::query()
            ->whereRaw('MD5(CONCAT("--", id, "--")) = ?', $id)
            ->first();

        if (!$model) {
            $model = new model;
            $model->title = $request->title;
            $model->owned_id = auth()->id();
            $model->save();
        } else {
            $model->title = $request->title;
            $model->save();
        }

        DB::beginTransaction();
        try {
            if ($model->uploadTemplateLetter) {
                try {
                    Storage::deleteDirectory($model->uploadTemplateLetter->path_template);
                } catch (\Exception $e) {}

                $file = $request->file('upload_zip');
                $zip = new ZipArchive;
                $zip->open($file);
                $zip->extractTo(storage_path('app/public/template-undangan/' . $model->id));

                $model->uploadTemplateLetter()->update([
                    'path_template' => 'template-undangan/' . $model->id
                ]);
            } else {
                $file = $request->file('upload_zip');
                $zip = new ZipArchive;
                $zip->open($file);
                $zip->extractTo(storage_path('app/public/template-undangan/' . $model->id));

                $model->uploadTemplateLetter()->create([
                    'path_template' => 'template-undangan/' . $model->id
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Data berhasil diupload', 'id' => md5("--$model->id--")]);
            //code...
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
            //throw $th;
        }
    }

    public function select2(Request $request)
    {
        $model = model::query()
            ->selectRaw('id, title as text')
            ->when($request->search, function ($query) use ($request) {
                $query->where('title', 'like', "%$request->search%");
            })
            ->paginate(10, ['id', 'text'], 'page', $request->page);


        return ResponsePaginate::collection($model);
    }

    public function updateBody(Request $request, $id)
    {
        $base65Decode = base64_decode($request->body);

        $model = model::query()
            ->whereRaw('MD5(CONCAT("--", id, "--")) = ?', $id)
            ->firstOrFail();


        $model->body = $base65Decode;
        $model->save();

        return response()->json(['message' => 'Data berhasil diubah']);
    }
}
