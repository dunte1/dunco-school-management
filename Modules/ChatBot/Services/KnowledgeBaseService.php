<?php

namespace Modules\ChatBot\Services;

use Illuminate\Support\Facades\Log;

class KnowledgeBaseService
{
    /**
     * Search knowledge base
     */
    public function search($query, $limit = 10)
    {
        try {
            // Mock knowledge base data
            $knowledgeBase = [
                [
                    'id' => 1,
                    'title' => 'School Admission Policy',
                    'content' => 'Students must submit application forms, academic transcripts, and recommendation letters.',
                    'category' => 'admission',
                    'tags' => ['admission', 'policy', 'requirements']
                ],
                [
                    'id' => 2,
                    'title' => 'Fee Payment Schedule',
                    'content' => 'Fees are due on the 15th of each month. Late payments incur a 5% penalty.',
                    'category' => 'finance',
                    'tags' => ['fees', 'payment', 'schedule']
                ],
                [
                    'id' => 3,
                    'title' => 'Attendance Policy',
                    'content' => 'Students must maintain 80% attendance to be eligible for examinations.',
                    'category' => 'academic',
                    'tags' => ['attendance', 'policy', 'examination']
                ],
                [
                    'id' => 4,
                    'title' => 'Library Rules',
                    'content' => 'Books can be borrowed for 2 weeks. Late returns incur fines.',
                    'category' => 'library',
                    'tags' => ['library', 'books', 'borrowing']
                ],
                [
                    'id' => 5,
                    'title' => 'Examination Guidelines',
                    'content' => 'Students must bring valid ID and writing materials. Electronic devices are prohibited.',
                    'category' => 'academic',
                    'tags' => ['examination', 'guidelines', 'rules']
                ]
            ];

            // Simple search implementation
            $results = [];
            $queryLower = strtolower($query);
            
            foreach ($knowledgeBase as $item) {
                $searchText = strtolower($item['title'] . ' ' . $item['content'] . ' ' . implode(' ', $item['tags']));
                
                if (strpos($searchText, $queryLower) !== false) {
                    $results[] = $item;
                }
                
                if (count($results) >= $limit) {
                    break;
                }
            }

            return $results;
        } catch (\Exception $e) {
            Log::error('Knowledge base search error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Search by category
     */
    public function searchByCategory($category, $limit = 10)
    {
        try {
            $allResults = $this->search('', 100); // Get all items
            $results = array_filter($allResults, function($item) use ($category) {
                return strtolower($item['category']) === strtolower($category);
            });
            
            return array_slice($results, 0, $limit);
        } catch (\Exception $e) {
            Log::error('Knowledge base category search error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Add knowledge base entry
     */
    public function addEntry($entry)
    {
        try {
            // In a real implementation, this would save to database
            Log::info('Knowledge base entry added', $entry);
            return true;
        } catch (\Exception $e) {
            Log::error('Knowledge base add entry error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update knowledge base entry
     */
    public function updateEntry($id, $entry)
    {
        try {
            // In a real implementation, this would update database
            Log::info("Knowledge base entry {$id} updated", $entry);
            return true;
        } catch (\Exception $e) {
            Log::error('Knowledge base update entry error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete knowledge base entry
     */
    public function deleteEntry($id)
    {
        try {
            // In a real implementation, this would delete from database
            Log::info("Knowledge base entry {$id} deleted");
            return true;
        } catch (\Exception $e) {
            Log::error('Knowledge base delete entry error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get knowledge base statistics
     */
    public function getStatistics()
    {
        try {
            $allResults = $this->search('', 100);
            $categories = array_unique(array_column($allResults, 'category'));
            
            return [
                'total_entries' => count($allResults),
                'categories' => $categories,
                'last_updated' => now()->toDateString()
            ];
        } catch (\Exception $e) {
            Log::error('Knowledge base statistics error: ' . $e->getMessage());
            return [
                'total_entries' => 0,
                'categories' => [],
                'last_updated' => null
            ];
        }
    }
}