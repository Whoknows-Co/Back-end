<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MoshaverAlt;

class PersianMoshaverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = [
            [
                'moshaver_first_name' => 'روزبه',
                'moshaver_last_name' => 'حاجی زاده',
                'address' => 'شیراز،قدوسی',
                'description' => 'مشاوره ماهر در رشته ریاضی',
                'more_description'=>'اینجا جایست که پیشرفت شما شروع میشود',
                'institute_name' => 'مشاورین',
                'degree'=>'کارشناسی ارشد',
                'subject' => 'ریاضی',
                'first_slot'=>'9:00',
                'best_students' => [
                    ['name' => 'علی رضایی', 'rate' => 18, 'exam' => 'کنکور ریاضی'],
                    ['name' => 'حمید کرمی', 'rate' => 200, 'exam' => 'کنکور ریاضی'],
                ],
                'contact' => [
                    ['name' => 'instagram', 'content' => 'roozbeh_haji'],
                    ['name' => 'linkedin', 'content' => 'roz_haj'],
                    ['name' => 'phone', 'content' => '09126547788'],
                ],
            ],
            [
                'moshaver_first_name' => 'سارا',
                'moshaver_last_name' => 'احمدی',
                'address' => 'تهران،تجریش',
                'description' => 'متخصص در حوزه فیزیک',
                'more_description'=>'جایی که مطالعه شما ساختارمند میشود',
                'institute_name' => 'مربی برتر',
                'degree'=>'کارشناسی ارشد',
                'subject' => 'فیزیک',
                'first_slot'=>'10:00',
                'best_students' => [
                    ['name' => 'نازنین جمالی', 'rate' => 92, 'exam' => 'کنکور ریاضی'],
                    ['name' => 'دانا جمالی', 'rate' => 88, 'exam' => 'کنکور ریاضی'],
                ],
                'contact' => [
                    ['name' => 'instagram', 'content' => 'sara_physicscoach'],
                    ['name' => 'linkedin', 'content' => 'sara-ahmadi'],
                    ['name' => 'phone', 'content' => '09156328754'],
                ],
            ],
            [
                'moshaver_first_name' => 'محمود',
                'moshaver_last_name' => 'مقدسی',
                'address' => 'بستک،آزادی',
                'description' => 'متخصص در مشاوره انسانی',
                'more_description'=>'جایی که رویاهای شما تحقق پیدا میکند',
                'institute_name' => 'تست برتر',
                'degree'=>'کارشناسی',
                'subject' => 'انسانی',
                'first_slot'=>'11:00',
                'best_students' => [
                    ['name' => 'وحید روستا', 'rate' => 92, 'exam' => 'کنکور انسانی'],
                    ['name' => 'آنا سجادی', 'rate' => 88, 'exam' => 'کنکور انسانی'],
                ],
                'contact' => [
                    ['name' => 'instagram', 'content' => 'mahmoud_ensani'],
                    ['name' => 'linkedin', 'content' => 'mahmoud_moghadas'],
                    ['name' => 'phone', 'content' => '09256326754'],
                ],
            ],
            [
                'moshaver_first_name' => 'امین',
                'moshaver_last_name' => 'کیایی',
                'address' => 'شیراز،ستارخان',
                'description' => 'متخصص در تجربی',
                'more_description'=>'جایی که زمانت به خوبی سپری میشه',
                'institute_name' => 'مشاور یک',
                'degree'=>'دکترا',
                'subject' => 'ریاضی',
                'first_slot'=>'12:00',
                'best_students' => [
                    ['name' => 'آوا جوکار', 'rate' => 92, 'exam' => 'کنکور ریاضی'],
                    ['name' => 'سجاد دارایی', 'rate' => 88, 'exam' => 'کنکور ریاضی'],
                ],
                'contact' => [
                    ['name' => 'instagram', 'content' => 'Amin_Math'],
                    ['name' => 'linkedin', 'content' => 'Amin-ahmadi'],
                    ['name' => 'phone', 'content' => '09156328766'],
                ],
            ],
            [
                'moshaver_first_name' => 'کمال',
                'moshaver_last_name' => 'تبریزی',
                'address' => 'تهران،نیاوران',
                'description' => 'متخصص در زیست شناسی',
                'more_description'=>'جایی که زمان مدیریت میشه',
                'institute_name' => 'حافط ساعت',
                'degree'=>'دکترا',
                'subject' => 'تجربی',
                'first_slot'=>'13:00',
                'best_students' => [
                    ['name' => 'کیان حاجی پور', 'rate' => 324, 'exam' => 'کنکور تجربی'],
                    ['name' => 'رحیم امیدی', 'rate' => 741, 'exam' => 'کنکور تجربی'],
                ],
                'contact' => [
                    ['name' => 'instagram', 'content' => 'Kamal_zist'],
                    ['name' => 'linkedin', 'content' => 'kamal-tabriz'],
                    ['name' => 'phone', 'content' => '09156321154'],
                ],
            ],
        ];
        foreach ($records as $record) {
            MoshaverAlt::create([
                'moshaver_first_name' => $record['moshaver_first_name'],
                'moshaver_last_name' => $record['moshaver_last_name'],
                'address' => $record['address'],
                'description' => $record['description'],
                'more_description'=>$record['more_description'],
                'degree'=>$record['degree'],
                'institute_name' => $record['institute_name'],
                'subject' => $record['subject'],
                'first_slot'=>$record['first_slot'],
                'best_students' => json_encode($record['best_students']),
                'contact' => json_encode($record['contact']),
            ]);
        }
    }
}
