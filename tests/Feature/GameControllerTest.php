<?php

namespace Tests\Feature;

use Tests\TestCase;

class GameControllerTest extends TestCase
{
    public function test_can_load_desk_page(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_can_load_board_page(): void
    {
        $response = $this->get('/board/case_001');
        $response->assertStatus(200);
    }

    public function test_can_load_interrogation_page_for_all_suspects(): void
    {
        $suspects = ['suspect_thorne', 'suspect_elena', 'suspect_croft'];
        foreach ($suspects as $suspectId) {
            $response = $this->get("/interrogation/case_001/{$suspectId}");
            $response->assertStatus(200);
        }
    }

    public function test_api_present_evidence_valid_objection_thorne(): void
    {
        $response = $this->postJson('/api/game/present-evidence', [
            'case_id' => 'case_001',
            'statement_id' => 'STMT_THORNE_ALIBI_LOCATION',
            'evidence_id' => 'EVD-05',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'unlocked_clue_id' => 'CLUE_THORNE_LOCATION_CRACKED',
                'target_dialogue_node' => 'thorne_cracked_cufflink',
                'board_fact' => [
                    'id' => 'FACT-01',
                    'type' => 'fact',
                ],
            ]);
    }

    public function test_api_present_evidence_valid_objection_elena(): void
    {
        $response = $this->postJson('/api/game/present-evidence', [
            'case_id' => 'case_001',
            'statement_id' => 'STMT_ELENA_CCTV_DENIAL',
            'evidence_id' => 'EVD-02',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'unlocked_clue_id' => 'CLUE_ELENA_COMPLICITY_CRACKED',
                'target_dialogue_node' => 'elena_cracked_cctv',
                'board_fact' => [
                    'id' => 'FACT-04',
                    'type' => 'fact',
                ],
            ]);
    }

    public function test_api_present_evidence_valid_objection_croft(): void
    {
        $response = $this->postJson('/api/game/present-evidence', [
            'case_id' => 'case_001',
            'statement_id' => 'STMT_CROFT_LEDGER_DENIAL',
            'evidence_id' => 'EVD-04',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'unlocked_clue_id' => 'CLUE_CROFT_FINANCE_CRACKED',
                'target_dialogue_node' => 'croft_cracked_ledger',
                'board_fact' => [
                    'id' => 'FACT-06',
                    'type' => 'fact',
                ],
            ]);
    }

    public function test_api_present_evidence_invalid_objection(): void
    {
        $response = $this->postJson('/api/game/present-evidence', [
            'case_id' => 'case_001',
            'statement_id' => 'STMT_CROFT_LEDGER_DENIAL',
            'evidence_id' => 'EVD-01',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => false,
                'unlocked_clue_id' => null,
            ]);
    }

    public function test_api_accuse_indictment(): void
    {
        $response = $this->postJson('/api/game/accuse', [
            'case_id' => 'case_001',
            'culprit_id' => 'suspect_thorne',
            'motive_id' => 'industrial_espionage_debt',
            'evidence_chain' => ['EVD-01', 'EVD-03', 'EVD-05'],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'total_score' => 100,
                'grade' => 'S',
            ]);
    }

    public function test_api_save_and_load_full_sync_state(): void
    {
        $slot = 'sync_test_slot';
        $fullState = [
            'case_id' => 'case_001',
            'credibility_score' => 85,
            'unlocked_clues' => [
                'CLUE_THORNE_LOCATION_CRACKED',
                'CLUE_ELENA_COMPLICITY_CRACKED',
                'CLUE_CROFT_FINANCE_CRACKED',
            ],
            'board_facts' => [
                [
                    'id' => 'FACT-01',
                    'title' => 'Alibi Dr. Thorne Runtuh',
                    'text' => 'Dr. Thorne terbukti menyusup ke Sublevel 3 melalui ventilasi udara.',
                    'type' => 'fact',
                    'color' => '#DC2626',
                ],
                [
                    'id' => 'FACT-04',
                    'title' => 'Keterlibatan Pengawas Elena',
                    'text' => 'Elena mengakui mematikan CCTV karena diperas oleh Julian Croft.',
                    'type' => 'fact',
                    'color' => '#7C3AED',
                ],
                [
                    'id' => 'FACT-06',
                    'title' => 'Aliran Dana Konspirasi',
                    'text' => 'Julian Croft terbukti menyuap Thorne untuk mengekstraksi chip Ouroboros.',
                    'type' => 'fact',
                    'color' => '#2563EB',
                ],
            ],
            'board_connections' => [
                ['from' => 'node_EVD-05', 'to' => 'node_FACT-01'],
                ['from' => 'node_EVD-04', 'to' => 'node_FACT-06'],
            ],
            'board_nodes_positions' => [
                'node_FACT-01' => ['x' => 450, 'y' => 300],
                'node_FACT-04' => ['x' => 600, 'y' => 200],
                'node_FACT-06' => ['x' => 500, 'y' => 450],
            ],
            'locked_suspects' => [
                'suspect_thorne',
                'suspect_croft',
            ],
            'interrogation_transcripts' => [
                'suspect_thorne' => [
                    [
                        'speaker' => 'Dr. Aris Thorne',
                        'text' => 'Saya tidak akan mengucapkan sepatah kata pun lagi.',
                        'type' => 'post_cracked',
                        'timestamp' => '14:30:10',
                    ],
                ],
                'suspect_croft' => [
                    [
                        'speaker' => 'Julian Croft',
                        'text' => 'Pemeriksaan selesai, Detektif.',
                        'type' => 'post_cracked',
                        'timestamp' => '14:32:45',
                    ],
                ],
            ],
            'dialogue_progress' => [
                'suspect_thorne' => 'thorne_post_cracked',
                'suspect_elena' => 'elena_post_cracked',
                'suspect_croft' => 'croft_post_cracked',
            ],
        ];

        $saveResponse = $this->postJson('/api/game/save', [
            'slot' => $slot,
            'state' => $fullState,
        ]);

        $saveResponse->assertStatus(200)
            ->assertJson(['success' => true]);

        $loadResponse = $this->getJson("/api/game/load/{$slot}");
        $loadResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'save' => [
                    'case_id' => 'case_001',
                    'credibility_score' => 85,
                    'unlocked_clues' => [
                        'CLUE_THORNE_LOCATION_CRACKED',
                        'CLUE_ELENA_COMPLICITY_CRACKED',
                        'CLUE_CROFT_FINANCE_CRACKED',
                    ],
                    'locked_suspects' => [
                        'suspect_thorne',
                        'suspect_croft',
                    ],
                    'dialogue_progress' => [
                        'suspect_thorne' => 'thorne_post_cracked',
                        'suspect_elena' => 'elena_post_cracked',
                        'suspect_croft' => 'croft_post_cracked',
                    ],
                ],
            ]);

        $loadedData = $loadResponse->json('save');
        $this->assertCount(3, $loadedData['board_facts']);
        $this->assertEquals('FACT-01', $loadedData['board_facts'][0]['id']);
        $this->assertEquals('FACT-04', $loadedData['board_facts'][1]['id']);
        $this->assertEquals('FACT-06', $loadedData['board_facts'][2]['id']);
        $this->assertCount(2, $loadedData['board_connections']);
        $this->assertArrayHasKey('node_FACT-01', $loadedData['board_nodes_positions']);
        $this->assertContains('suspect_thorne', $loadedData['locked_suspects']);
        $this->assertArrayHasKey('suspect_croft', $loadedData['interrogation_transcripts']);
        $this->assertEquals('post_cracked', $loadedData['interrogation_transcripts']['suspect_croft'][0]['type']);
    }
}
