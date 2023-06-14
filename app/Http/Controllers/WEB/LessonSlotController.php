<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\LessonSlot;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LessonSlotController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $lessonSlots = LessonSlot::with('campus')
            ->where('created_by', $user->id)
            ->where('status', '!=', 'deleted')
            ->orderBy('campus_id')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $days = array(
            "1" => "Pazartesi",
            "2" => "Salı",
            "3" => "Çarşamba",
            "4" => "Perşembe",
            "5" => "Cuma",
            "6" => "Cumartesi",
            "7" => "Pazar"
        );

        $campuses = Campus::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($campuses->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle kampüs oluşturmalısınız');
        }

        return view('/pages/lesson-slot.list', ['lessonSlots' => $lessonSlots, 'days'=>$days, 'campuses'=>$campuses]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $lessonSlot = LessonSlot::findOrFail($id);
            $lessonSlot->status = 'deleted';
            $lessonSlot->save();
            return redirect()->route('lesson-slot.list')->with('success', 'Silme işlemi başarıyla gerçekleşti');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Veri bulunamadı');
        }
    }

    public function filter(Request $request)
    {
        $user = Auth::user();

        $campus_id = $request->input('campus_id');
        $query = LessonSlot::query();

        if (!empty($campus_id)) {
            $query->whereIn('campus_id', $campus_id);
        }

        $query->where('status', '!=', 'deleted');
        $query->where('created_by', $user->id);
        $query->orderByDesc('updated_at');
        $query->orderByDesc('created_at');

        $lessonSlots = $query->get();

        $days = array(
            "1" => "Pazartesi",
            "2" => "Salı",
            "3" => "Çarşamba",
            "4" => "Perşembe",
            "5" => "Cuma",
            "6" => "Cumartesi",
            "7" => "Pazar"
        );

        $campuses = Campus::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($campuses->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle kampüs oluşturmalısınız');
        }
        return view('/pages/lesson-slot.list', ['lessonSlots' => $lessonSlots,'campus_id'=>$campus_id, 'campuses'=>$campuses, 'days'=>$days, 'filter'=>true]);
    }

    public function add()
    {
        $user = Auth::user();

        $campuses = Campus::where('created_by', $user->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        if ($campuses->isEmpty()) {
            return redirect()->back()->with('error', 'Öncelikle kampüs oluşturmalısınız');
        }


        return view('/pages/lesson-slot.add', ['campuses'=>$campuses]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request)
    {
        $user = Auth::user();

        try {
            $validatedData = $request->validate([
                'campus_id' => 'required',
                'day' => 'required',
                'start_time' => 'required',
                'end_time' => 'required'
            ]);

            $campusId = $validatedData['campus_id'];
            $day = $validatedData['day'];
            $startTime = $validatedData['start_time'];
            $endTime = $validatedData['end_time'];

            $existingSlot = LessonSlot::where('campus_id', $campusId)
                ->where('day', $day)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '>=', $startTime)
                            ->where('start_time', '<', $endTime);
                    })->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('end_time', '>', $startTime)
                            ->where('end_time', '<=', $endTime);
                    })->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
                })
                ->first();

            if ($existingSlot) {
                return redirect()->back()->with('error', 'Bu gün için, bu saat dilimi ile çakışan slot mevcut');
            }

            $lessonSlot = new LessonSlot;
            $lessonSlot->campus_id  = $validatedData['campus_id'];
            $lessonSlot->day        = $validatedData['day'];
            $lessonSlot->start_time = $validatedData['start_time'];
            $lessonSlot->end_time   = $validatedData['end_time'];
            $lessonSlot->created_by = $user->id;
            $lessonSlot->save();


            return redirect()->route('lesson-slot.list')->with('success', 'Ekleme işlemi başarılı');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', 'Kaydetme sırasında hata oluştu');
        }
        catch (ValidationException $exception) {
            return redirect()->back()->with('error', 'Zorunlu alanları doldurunuz');
        }catch (\Exception $exception){
            return redirect()->back()->with('error', __( ($exception->getMessage() ? $exception->getMessage() : "Hatalı istek")));
        }
    }
}
