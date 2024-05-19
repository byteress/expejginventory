<?php

namespace ProductManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use SupplierManagement\Models\Supplier;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured');
        $this->addMediaCollection('gallery');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
