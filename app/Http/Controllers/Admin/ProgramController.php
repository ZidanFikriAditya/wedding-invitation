<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\TemplateLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.program.index');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'type_medsos.*' => 'required|string',
            'social_media.*' => 'required|string',
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            if ($request->template_letter_id) {
                $templateLetter = TemplateLetter::whereCrypt('id', $request->template_letter_id)->firstOrFail();

                $validate = [];
                foreach ($templateLetter->legends as $legend) {
                    $validate['legend_' . $legend->legend] = 'required';
                }

                $this->validate($request, $validate);
            } else {
                $templateLetter = null;
            }
            
            if ($user->program) {
                $program = Program::query()
                    ->where('owned_id', $user->id)
                    ->first();
    
                $medsos = [];
                foreach ($request->type_medsos as $key => $type) {
                    $medsos[$type] = $request->social_media[$key];
                }
    
                $program->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'social_media' => $medsos,
                    'template_letter_id' => $templateLetter?->id,
                ]);
            } else {
                $medsos = [];
                foreach ($request->type_medsos as $key => $type) {
                    $medsos[$type] = $request->social_media[$key];
                }
    
                $program = Program::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'phone_number' => $request->phone_number,
                    'address' => $request->address,
                    'social_media' => $medsos,
                    'owned_id' => $user->id,
                    'template_letter_id' => $templateLetter?->id,
                ]);
            }

            $htmlReplace = $templateLetter->body;
            $legendsValue['legends'] = [];
            foreach ($templateLetter?->legends ?? [] as $legend) {
                $legendValue = $request->input('legend_' . $legend->legend);
                $legendsValue['legends'][$legend->legend] = $legendValue;
                $htmlReplace = str_replace($legend->legend, $legendValue, $templateLetter->body);
            }
            $program->others = $legendsValue;
            $program->current_template_letter = $htmlReplace;
            $program->save();

            DB::commit();
            return redirect()->back()->with('success', 'Berhasil mengubah acara');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal membuat acara baru');
        }
    }

    public function templateLetterUtils(Request $request, $undangan)
    {
        $templateLetter = TemplateLetter::whereCrypt('id', $undangan)
            ->with(['legends'])
            ->first();

        if (!$templateLetter) return $this->responseMessage('Template letter not found', 404);

        $data['title'] = $templateLetter->title;
        $data['body'] = $templateLetter->body;
        $data['legends'] = [];
        foreach ($templateLetter->legends as $legend) {
            $data['legends'][] = [
                'legend' => $legend->legend,
                'type' => $legend->type,
                'description' => $legend->description,
            ];
        }

        return $this->responseData($data);
    }

    public function previewHtml(Request $request, $undangan) 
    {
        $model = TemplateLetter::whereCrypt('id', $undangan)->firstOrFail();

        foreach ($model->legends as $legend) {
            $legendValue = $request->input($legend->legend);
            $model->body = str_replace($legend->legend, $legendValue ?? $legend->legend, $model->body);
        }

        return view('admin.preview', compact('model'));
    }

    public function saveImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'legend' => 'required|string',
        ]);

        $program = Program::where('owned_id', Auth::id())->firstOrFail();
        
        if (isset($program->others['legends'])) {
            $legendsValue = $program->others['legends'];

            try {
                Storage::disk('public')->delete($legendsValue[$request->legend]);
            } catch (\Throwable $th) {}

            $image = $request->file('image');
            $imageName = $request->legend . '_' . time() . '.' . $image->extension();
            $path = 'images_legend/' . $imageName;
            Storage::disk('public')->put($path, file_get_contents($image));

            $legendsValue[$request->legend] = $path;
        } else {
            $image = $request->file('image');
            $imageName = $request->legend . '_' . time() . '.' . $image->extension();
            $path = 'images_legend/' . $imageName;
            Storage::disk('public')->put($path, file_get_contents($image));
            $legendsValue = [
                $request->legend => $path
            ];
        }

        $program->others = ['legends' => $legendsValue];
        $program->save();

        return $this->responseData([
            'path' => $path,
            'legend' => $request->legend,
        ]);
    }
}
