<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LessonSlot;

class LessonSlotSeeder extends Seeder
{
    public function run()
    {
        $campusId = 1;
        $createdBy = 1;
        $startTimes = ['08:30', '09:20', '10:10', '11:00', '11:50','13:30', '14:20', '15:10'];
        $endTimes = ['09:10', '10:00', '10:50', '11:40', '12:30','14:10', '15:00', '15:50'];

        for ($day = 1; $day <= 5; $day++) {
            for ($course = 1; $course <= count($startTimes); $course++) {
                $lessonSlot = new LessonSlot;
                $lessonSlot->campus_id = $campusId;
                $lessonSlot->day = $day;
                $lessonSlot->start_time = $startTimes[$course - 1];
                $lessonSlot->end_time = $endTimes[$course - 1];
                $lessonSlot->created_by = $createdBy;
                $lessonSlot->save();
            }
        }
    }
}
