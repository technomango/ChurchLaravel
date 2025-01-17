<?php

namespace Bishopm\Church\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Tags\HasTags;
use Parallax\FilamentComments\Models\Traits\HasFilamentComments;

class Book extends Model
{
    use HasTags;

    public $table = 'books';
    protected $guarded = ['id'];
    protected $casts = ['authors'=>'array'];
    
    public function getAllauthorsAttribute() {
        $authornames="";
        if ($this->authors){
            foreach ($this->authors as $arr){
                $authornames.=$arr['name'].", ";
            }
            return substr($authornames,0,-2);
        }
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class,'commentable');
    }
}
