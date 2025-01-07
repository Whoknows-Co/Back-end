<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MoshaverProfile extends Model
{
    protected $fillable=[
        "moshaver_id",
        "specialty",
        "city",
        "address",
        "about",
        "services",
        "social_media",
        "display_phone",
        "is_complete"
    ];
    protected $casts=[
        "social_media"=>"array",
        "is_complete"=>"boolean"
    ];
    public function moshaver(){
        return $this->belongsTo(Moshaver::class);
    }
    public static function boot()
    {
        parent::boot();

        static::saving(function ($profile) {
            $requiredFields = [
                'specialty', 'city', 'address', 'about', 'services', 'social_media','display_phone'
            ];
            $data = $profile->exists ? $profile->getOriginal() : [];
            $data = array_merge($data, $profile->getAttributes());
            $isComplete = true;
            foreach ($requiredFields as $field) {
                if (empty($profile->{$field})) {
                    $isComplete = false;
                    break;
                }
            }

            $profile->is_complete = $isComplete;
        });
    }
}
