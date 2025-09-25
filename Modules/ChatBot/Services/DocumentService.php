<?php

namespace Modules\ChatBot\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Modules\ChatBot\Models\Document;
use Modules\ChatBot\Models\Conversation;

class DocumentService
{
    protected $allowedMimeTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/plain',
        'text/html',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/bmp',
        'image/webp'
    ];

    protected $maxFileSize = 10485760; // 10MB

    /**
     * Get allowed file types
     */
    public function getAllowedTypes()
    {
        return [
            'pdf' => 'PDF Documents',
            'doc' => 'Word Documents',
            'docx' => 'Word Documents',
            'txt' => 'Text Files',
            'jpg' => 'Images',
            'jpeg' => 'Images',
            'png' => 'Images',
            'gif' => 'Images',
            'bmp' => 'Images',
            'webp' => 'Images',
            'ppt' => 'PowerPoint',
            'pptx' => 'PowerPoint'
        ];
    }

    /**
     * Upload and process a document
     */
    public function uploadDocument(UploadedFile $file, $userId, $conversationId = null, $description = null)
    {
        try {
            // Validate file
            $this->validateFile($file);

            // Generate unique filename
            $filename = $this->generateFilename($file);
            $filePath = 'chatbot/documents/' . $filename;

            // Store file
            $file->storeAs('chatbot/documents', $filename, 'local');

            // Create document record
            $document = Document::create([
                'user_id' => $userId,
                'conversation_id' => $conversationId,
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'description' => $description,
                'metadata' => [
                    'uploaded_at' => now()->toISOString(),
                    'file_hash' => hash_file('sha256', $file->getRealPath()),
                ]
            ]);

            // Process document content
            $this->processDocument($document);

            return $document;
        } catch (\Exception $e) {
            Log::error('Document upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Process document content
     */
    protected function processDocument(Document $document)
    {
        try {
            $content = '';

            if ($document->isPdf()) {
                $content = $this->extractPdfText($document);
            } elseif ($document->isWord()) {
                $content = $this->extractWordText($document);
            } elseif ($document->isText()) {
                $content = $this->extractTextContent($document);
            } elseif ($document->isImage()) {
                $content = $this->extractImageText($document);
            }

            // Update document with extracted content
            $document->update([
                'content_text' => $content,
                'is_processed' => true,
                'metadata' => array_merge($document->metadata ?? [], [
                    'processed_at' => now()->toISOString(),
                    'content_length' => strlen($content),
                    'word_count' => str_word_count($content)
                ])
            ]);

        } catch (\Exception $e) {
            Log::error('Document processing failed: ' . $e->getMessage());
            $document->update([
                'is_processed' => false,
                'metadata' => array_merge($document->metadata ?? [], [
                    'processing_error' => $e->getMessage()
                ])
            ]);
        }
    }

    /**
     * Extract text from PDF
     */
    protected function extractPdfText(Document $document)
    {
        // For now, return a placeholder. In production, you'd use a PDF parsing library
        return "PDF content extraction requires additional libraries like Smalot\PdfParser. " .
               "Document: {$document->original_filename} has been uploaded successfully. " .
               "Please install PDF parsing library for full text extraction.";
    }

    /**
     * Extract text from Word documents
     */
    protected function extractWordText(Document $document)
    {
        // For now, return a placeholder. In production, you'd use a Word parsing library
        return "Word document content extraction requires additional libraries like PhpOffice\PhpWord. " .
               "Document: {$document->original_filename} has been uploaded successfully. " .
               "Please install Word parsing library for full text extraction.";
    }

    /**
     * Extract text from text files
     */
    protected function extractTextContent(Document $document)
    {
        $filePath = Storage::path($document->file_path);
        return file_get_contents($filePath);
    }

    /**
     * Extract text from images using OCR
     */
    protected function extractImageText(Document $document)
    {
        // For now, return a placeholder. In production, you'd use OCR libraries
        return "Image OCR requires additional libraries like Tesseract. " .
               "Image: {$document->original_filename} has been uploaded successfully. " .
               "Please install OCR library for text extraction from images.";
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile(UploadedFile $file)
    {
        if (!$file->isValid()) {
            throw new \Exception('Invalid file upload');
        }

        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            throw new \Exception('File type not allowed. Allowed types: ' . implode(', ', array_keys($this->getAllowedTypes())));
        }

        if ($file->getSize() > $this->maxFileSize) {
            throw new \Exception('File size too large. Maximum size: ' . $this->formatBytes($this->maxFileSize));
        }
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y_m_d_H_i_s');
        $random = bin2hex(random_bytes(8));
        
        return "doc_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get documents for a user
     */
    public function getUserDocuments($userId, $limit = 20)
    {
        return Document::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get documents for a conversation
     */
    public function getConversationDocuments($conversationId)
    {
        return Document::where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Delete a document
     */
    public function deleteDocument($documentId, $userId)
    {
        $document = Document::where('id', $documentId)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Delete file from storage
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        // Delete document record
        $document->delete();

        return true;
    }

    /**
     * Search documents by content
     */
    public function searchDocuments($userId, $query, $limit = 10)
    {
        return Document::where('user_id', $userId)
            ->where('is_processed', true)
            ->where('content_text', 'like', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
