<?php

namespace Modules\ChatBot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $table = 'chatbot_documents';

    protected $fillable = [
        'user_id',
        'conversation_id',
        'filename',
        'original_filename',
        'file_path',
        'file_size',
        'file_type',
        'mime_type',
        'description',
        'content_text',
        'metadata',
        'is_processed',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_processed' => 'boolean',
    ];

    /**
     * Get the user who uploaded this document
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the conversation this document belongs to
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get messages related to this document
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'document_id');
    }

    /**
     * Scope to get processed documents
     */
    public function scopeProcessed($query)
    {
        return $query->where('is_processed', true);
    }

    /**
     * Scope to get documents by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get file size in human readable format
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if document is an image
     */
    public function isImage()
    {
        return in_array($this->mime_type, [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/bmp',
            'image/webp'
        ]);
    }

    /**
     * Check if document is a PDF
     */
    public function isPdf()
    {
        return $this->mime_type === 'application/pdf';
    }

    /**
     * Check if document is a text file
     */
    public function isText()
    {
        return in_array($this->mime_type, [
            'text/plain',
            'text/html',
            'text/css',
            'text/javascript'
        ]);
    }

    /**
     * Check if document is a Word document
     */
    public function isWord()
    {
        return in_array($this->mime_type, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ]);
    }

    /**
     * Check if document is a PowerPoint
     */
    public function isPowerPoint()
    {
        return in_array($this->mime_type, [
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ]);
    }

    /**
     * Get document type for display
     */
    public function getDocumentTypeAttribute()
    {
        if ($this->isImage()) return 'Image';
        if ($this->isPdf()) return 'PDF';
        if ($this->isText()) return 'Text';
        if ($this->isWord()) return 'Word Document';
        if ($this->isPowerPoint()) return 'PowerPoint';
        return 'Document';
    }
}
