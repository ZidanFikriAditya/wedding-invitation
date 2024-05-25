<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\LetterInvitationImport;
use App\Models\LetterInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ShareLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.invitation.index');
    }

    public function data()
    {
        $model = LetterInvitation::query()
            ->where('program_id', Auth::user()->program?->id);

        return datatables($model)
            ->addIndexColumn()
            ->editColumn('id', function ($model) {
                return md5("--$model->id--");
            })
            ->addColumn('action', function ($model) {

                return "
                    <a href='javascript:;' data-bs-toggle='modal' data-bs-target='#modal-edit-invitation' data-id='". cryptId($model->id) ."' class='btn btn-sm btn-warning'><i class='ti ti-pencil'></i></a>
                    <a href='javascript:;' class='btn btn-sm btn-danger' onclick='handleDelete(\"" . cryptId($model->id) . "\")'><i class='ti ti-trash'></i></a>
                    <a target='blank' href='". route('admin.undangan.sent-status', ['id' => cryptId($model->id), 'status' => 'sending']) ."' class='btn btn-sm btn-primary'><i class='ti ti-share'></i></a>
                ";
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function show($id)
    {
        $id = decryptId($id);

        $model = LetterInvitation::query()->findOrFail($id, ['receiver_name', 'receiver_number']);

        $view = view('admin.invitation.show', compact('model'))->render();

        return $this->responseData($view, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|array',
            'name.*' => 'required|string',
            'number' => 'required|array',
            'number.*' => 'required|string',
        ]);

        try {
            $program = Auth::user()->program;

            if (!$program) {
                throw new \Exception('Harap isi data program terlebih dahulu');
            }

            foreach ($request->name as $key => $name) {
                $receiver_number = str_replace([' ', '-', '+'], ['', '', ''], $request->number[$key]);

                $checkHas = LetterInvitation::query()
                    ->where('program_id', $program->id)
                    ->whereIn('receiver_number', [$receiver_number])
                    ->first();

                if ($checkHas) {
                    $checkHas->receiver_name = $name;
                    $checkHas->receiver_number = $receiver_number;
                    $checkHas->save();
                } else {
                    $model = new LetterInvitation();
                    $model->receiver_name = $name;
                    $model->receiver_number = $receiver_number;
                    $model->program_id = $program->id;
                    $model->save();
                }

            }
    
            return $this->responseMessage('Berhasil menambahkan data', 201);
        } catch (\Throwable $th) {
            return $this->responseMessage($th->getMessage(), 500);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'number' => 'required',
        ]);

        $id = decryptId($id);
        
        $receiver_number = str_replace([' ', '-', '+'], ['', '', ''], $request->number);

        $model = LetterInvitation::findOrFail($id);
        $model->receiver_name = $request->name;
        $model->receiver_number = $receiver_number;
        $model->save();

        return $this->responseMessage('Berhasil mengubah data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = decryptId($id);
        $model = LetterInvitation::where('id', $id)->firstOrFail();
        $model->delete();

        return $this->responseMessage('Berhasil menghapus data');
    }

    public function importExcel(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');

        if (!auth()->user()->program) {
            return $this->responseMessage('Harap isi data program terlebih dahulu', 500);
        }

        Excel::import(new LetterInvitationImport(auth()->user()->program->id), $file);

        return $this->responseMessage('Berhasil mengimport data');
    }

    public function sentStatus (Request $request, $id)
    {
        $id = decryptId($id);

        $model = LetterInvitation::findOrFail($id);
        $model->status = $request->status;
        $model->sent_at = now();
        $model->save();

        $urlEncode = 'Hai ' . $model->receiver_name . ', Tolong Klik link ini untuk melihat undangan '  . urlencode(route('preview', ['id' => $model->program_id, 'undangan' => $model->receiver_number]));
        $phoneNumber = substr($model->receiver_number, 0, 1) == '0' ? '62' . substr($model->receiver_number, 1) : (substr($model->receiver_number, 0, 2) == '62' ? $model->receiver_number : '62' . $model->receiver_number);

        return redirect("https://api.whatsapp.com/send?phone=". $phoneNumber ."&text=".$urlEncode."");
    }
}
