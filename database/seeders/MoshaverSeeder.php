<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MoshaverAlt;

class MoshaverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = [
            [
                'moshaver_first_name' => 'Roozbeh',
                'moshaver_last_name' => 'Hajizadeh',
                'address' => 'Shiraz,Ghodosi',
                'description' => 'An experienced consultant in mathematics.',
                'more_description'=>'This is where your studing beging',
                'institute_name' => 'Whoknows',
                'degree'=>'Master',
                'subject' => 'Mathematics',
                'first_slot'=>'9:00',
                'best_students' => [
                    ['name' => 'Ali Rezaie', 'rate' => 18, 'exam' => 'Konkour Riazi'],
                    ['name' => 'Hamid Karami', 'rate' => 200, 'exam' => 'Konkour Riazi'],
                ],
                'contact' => [
                    ['name' => 'instagram', 'content' => 'roozbeh_haji'],
                    ['name' => 'linkedin', 'content' => 'roz_haj'],
                    ['name' => 'phone', 'content' => '09126547788'],
                ],
            ],
            [
                'moshaver_first_name' => 'Sara',
                'moshaver_last_name' => 'Ahmadi',
                'address' => 'Tehran,Tajrish',
                'description' => 'Expert in physics coaching.',
                'more_description'=>'This is where your studing beging',
                'institute_name' => 'Elite Coaching',
                'degree'=>'master',
                'subject' => 'Physics',
                'first_slot'=>'10:00',
                'best_students' => [
                    ['name' => 'Nazanin Jamali', 'rate' => 92, 'exam' => 'Konkour Riazi'],
                    ['name' => 'Dana sajadi', 'rate' => 88, 'exam' => 'Knkour Riazi'],
                ],
                'contact' => [
                    ['name' => 'instagram', 'content' => 'sara_physicscoach'],
                    ['name' => 'linkedin', 'content' => 'sara-ahmadi'],
                    ['name' => 'phone', 'content' => '09156328754'],
                ],
            ],
            [
                'moshaver_first_name' => 'Mahmoud',
                'moshaver_last_name' => 'Moghadasi',
                'address' => 'Bastak,Azadi',
                'description' => 'Expert in Ensani coaching.',
                'more_description'=>'This is where your studing beging',
                'institute_name' => 'Elite Test',
                'degree'=>'bachelor',
                'subject' => 'Ensani',
                'first_slot'=>'11:00',
                'best_students' => [
                    ['name' => 'Vahid Rousta', 'rate' => 92, 'exam' => 'Konkour Ensani'],
                    ['name' => 'Anna sajadi', 'rate' => 88, 'exam' => 'Konkour Ensani'],
                ],
                'contact' => [
                    ['name' => 'instagram', 'content' => 'mahmoud_ensani'],
                    ['name' => 'linkedin', 'content' => 'mahmoud_moghadas'],
                    ['name' => 'phone', 'content' => '09256326754'],
                ],
            ],
            [
                'moshaver_first_name' => 'Amin',
                'moshaver_last_name' => 'Ahmadi',
                'address' => 'Shiraz,Sattarkhan',
                'description' => 'Expert in math coaching.',
                'more_description'=>'This is where your studing beging',
                'institute_name' => 'Elite Coaching',
                'degree'=>'PhD',
                'subject' => 'math',
                'first_slot'=>'12:00',
                'best_students' => [
                    ['name' => 'Ava Jokar', 'rate' => 92, 'exam' => 'Konkour Riazi'],
                    ['name' => 'Sajad Daraie', 'rate' => 88, 'exam' => 'Konkour Riazi'],
                ],
                'contact' => [
                    ['name' => 'instagram', 'content' => 'Amin_Math'],
                    ['name' => 'linkedin', 'content' => 'Amin-ahmadi'],
                    ['name' => 'phone', 'content' => '09156328766'],
                ],
            ],
            [
                'moshaver_first_name' => 'Kamal',
                'moshaver_last_name' => 'Tabrizo',
                'address' => 'Tehran,Niavaran',
                'description' => 'Expert in biology coaching.',
                'more_description'=>'This is where your studing beging',
                'institute_name' => 'Elite Moshaveran',
                'degree'=>'PhD',
                'subject' => 'Biology',
                'first_slot'=>'13:00',
                'best_students' => [
                    ['name' => 'Kian Hajipour', 'rate' => 324, 'exam' => 'Konkour Tajrobi'],
                    ['name' => 'Dana sajadi', 'rate' => 741, 'exam' => 'Konkour Tajrobi'],
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
