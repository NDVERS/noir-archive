<?php

namespace Tests\Unit;

use App\Services\SaveManagerService;
use Tests\TestCase;

class SaveManagerTest extends TestCase
{
    protected SaveManagerService $saveManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->saveManager = new SaveManagerService;
    }

    public function test_can_save_and_load_game_state(): void
    {
        $slot = 'test_slot_99';
        $state = [
            'case_id' => 'case_001',
            'credibility_score' => 85,
            'unlocked_clues' => ['CLUE_THORNE_LOCATION_CRACKED'],
            'locked_suspects' => ['suspect_croft', 'suspect_thorne'],
            'interrogation_transcripts' => [
                'suspect_croft' => [
                    [
                        'speaker' => 'Julian Croft',
                        'text' => 'Pemeriksaan selesai, Detektif.',
                        'type' => 'post_cracked',
                        'timestamp' => '14:20:00',
                    ],
                ],
            ],
            'board_connections' => [
                ['from_id' => 'node_thorne', 'to_id' => 'node_evd_05'],
            ],
        ];

        $saved = $this->saveManager->save($slot, $state);
        $this->assertEquals(85, $saved['credibility_score']);
        $this->assertEquals($slot, $saved['slot']);
        $this->assertContains('suspect_croft', $saved['locked_suspects']);
        $this->assertArrayHasKey('suspect_croft', $saved['interrogation_transcripts']);

        $loaded = $this->saveManager->load($slot);
        $this->assertNotNull($loaded);
        $this->assertEquals(85, $loaded['credibility_score']);
        $this->assertContains('CLUE_THORNE_LOCATION_CRACKED', $loaded['unlocked_clues']);
        $this->assertContains('suspect_croft', $loaded['locked_suspects']);
        $this->assertEquals('post_cracked', $loaded['interrogation_transcripts']['suspect_croft'][0]['type']);

        // Clean up test file
        $deleted = $this->saveManager->delete($slot);
        $this->assertTrue($deleted);
    }

    public function test_default_state_contains_locked_suspects_and_transcripts(): void
    {
        $default = $this->saveManager->getDefaultState('case_001');
        $this->assertIsArray($default['locked_suspects']);
        $this->assertEmpty($default['locked_suspects']);
        $this->assertIsArray($default['interrogation_transcripts']);
        $this->assertEmpty($default['interrogation_transcripts']);
    }
}
