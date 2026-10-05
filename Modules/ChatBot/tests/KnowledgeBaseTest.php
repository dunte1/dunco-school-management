<?php

namespace Modules\ChatBot\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ChatBot\Services\KnowledgeBaseService;
use Modules\ChatBot\Models\ChatBotDocument;

class KnowledgeBaseTest extends TestCase
{
    use RefreshDatabase;

    protected $knowledgeBaseService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->knowledgeBaseService = app(KnowledgeBaseService::class);
    }

    /** @test */
    public function it_can_search_knowledge_base()
    {
        $query = "school policy";
        $results = $this->knowledgeBaseService->search($query, 10);
        
        $this->assertIsArray($results);
        $this->assertLessThanOrEqual(10, count($results));
    }

    /** @test */
    public function it_can_handle_empty_search_query()
    {
        $results = $this->knowledgeBaseService->search('', 10);
        
        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    /** @test */
    public function it_can_handle_special_characters_in_search()
    {
        $queries = [
            "What's the policy?",
            "How-to guide",
            "FAQ & Answers",
            "Rules & Regulations"
        ];

        foreach ($queries as $query) {
            $results = $this->knowledgeBaseService->search($query, 5);
            $this->assertIsArray($results);
        }
    }

    /** @test */
    public function it_can_handle_different_search_limits()
    {
        $query = "test";
        $limits = [1, 5, 10, 50];

        foreach ($limits as $limit) {
            $results = $this->knowledgeBaseService->search($query, $limit);
            $this->assertIsArray($results);
            $this->assertLessThanOrEqual($limit, count($results));
        }
    }

    /** @test */
    public function it_can_handle_knowledge_base_categories()
    {
        $categories = [
            'academic',
            'administrative',
            'financial',
            'technical',
            'general'
        ];

        foreach ($categories as $category) {
            $results = $this->knowledgeBaseService->searchByCategory($category, 10);
            $this->assertIsArray($results);
        }
    }

    /** @test */
    public function it_can_add_knowledge_base_entry()
    {
        $entry = [
            'title' => 'Test Knowledge Entry',
            'content' => 'This is a test knowledge base entry.',
            'category' => 'test',
            'tags' => ['test', 'example'],
            'priority' => 'normal'
        ];

        $result = $this->knowledgeBaseService->addEntry($entry);
        
        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_update_knowledge_base_entry()
    {
        // First add an entry
        $entry = [
            'title' => 'Test Entry',
            'content' => 'Original content',
            'category' => 'test'
        ];
        
        $this->knowledgeBaseService->addEntry($entry);
        
        // Update the entry
        $updatedEntry = [
            'title' => 'Updated Test Entry',
            'content' => 'Updated content',
            'category' => 'test'
        ];
        
        $result = $this->knowledgeBaseService->updateEntry(1, $updatedEntry);
        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_delete_knowledge_base_entry()
    {
        // First add an entry
        $entry = [
            'title' => 'To Be Deleted',
            'content' => 'This will be deleted',
            'category' => 'test'
        ];
        
        $this->knowledgeBaseService->addEntry($entry);
        
        // Delete the entry
        $result = $this->knowledgeBaseService->deleteEntry(1);
        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_get_knowledge_base_statistics()
    {
        $stats = $this->knowledgeBaseService->getStatistics();
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_entries', $stats);
        $this->assertArrayHasKey('categories', $stats);
        $this->assertArrayHasKey('last_updated', $stats);
    }

    /** @test */
    public function it_can_handle_fuzzy_search()
    {
        $query = "scool polcy"; // Intentional typos
        $results = $this->knowledgeBaseService->fuzzySearch($query, 10);
        
        $this->assertIsArray($results);
    }

    /** @test */
    public function it_can_handle_synonym_search()
    {
        $query = "fees payment"; // Should also find "tuition", "billing", etc.
        $results = $this->knowledgeBaseService->searchWithSynonyms($query, 10);
        
        $this->assertIsArray($results);
    }

    /** @test */
    public function it_can_export_knowledge_base()
    {
        $export = $this->knowledgeBaseService->export('json');
        
        $this->assertIsString($export);
        $this->assertJson($export);
    }

    /** @test */
    public function it_can_import_knowledge_base()
    {
        $importData = [
            [
                'title' => 'Imported Entry 1',
                'content' => 'This is imported content',
                'category' => 'imported'
            ],
            [
                'title' => 'Imported Entry 2',
                'content' => 'Another imported entry',
                'category' => 'imported'
            ]
        ];
        
        $result = $this->knowledgeBaseService->import($importData);
        $this->assertTrue($result);
    }
}
