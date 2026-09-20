<?php

namespace Modules\Document\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'documents';

    protected $fillable = [
        'title',
        'description',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'category',
        'tags',
        'visibility',
        'uploaded_by',
        'school_id',
        'is_active',
    ];

    protected $casts = [
        'tags' => 'array',
        'file_size' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who uploaded the document.
     */
    public function uploader()
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }

    /**
     * Get the school this document belongs to.
     */
    public function school()
    {
        return $this->belongsTo(\Modules\Core\Models\School::class, 'school_id');
    }

    /**
     * Get the document category.
     */
    public function categoryRel()
    {
        return $this->belongsTo(DocumentCategory::class, 'category');
    }

    /**
     * Scope: only active documents.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: filter by category.
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get human-readable file size.
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }

    /**
     * Get the file extension icon class.
     */
    public function getFileIconAttribute(): string
    {
        return match(strtolower($this->file_type)) {
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc', 'docx' => 'fas fa-file-word text-primary',
            'xls', 'xlsx' => 'fas fa-file-excel text-success',
            'ppt', 'pptx' => 'fas fa-file-powerpoint text-warning',
            'jpg', 'jpeg', 'png', 'gif' => 'fas fa-file-image text-info',
            'mp4', 'avi', 'mov' => 'fas fa-file-video text-secondary',
            default => 'fas fa-file text-muted',
        };
    }
}
