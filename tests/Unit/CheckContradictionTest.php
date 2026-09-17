<?php

namespace Tests\Unit;

use App\Actions\CheckContradictionAction;
use App\Services\CaseRepositoryService;
use Tests\TestCase;

class CheckContradictionTest extends TestCase
{
    protected CheckContradictionAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CheckContradictionAction(new CaseRepositoryService);
    }

    public function test_valid_contradiction_breaks_alibi_and_rewards_credibility(): void
    {
        // Dr. Thorne claims he never stepped near Sublevel 3 (STMT_THORNE_ALIBI_LOCATION)
        // Presenting his engraved cufflink (EVD-05) found in the air vent
        $result = $this->action->execute('case_001', 'STMT_THORNE_ALIBI_LOCATION', 'EVD-05');

        $this->assertTrue($result['valid']);
        $this->assertStringContainsString('OBJECTION', $result['message']);
        $this->assertEquals('CLUE_THORNE_LOCATION_CRACKED', $result['unlocked_clue_id']);
        $this->assertEquals('thorne_cracked_cufflink', $result['target_dialogue_node']);
        $this->assertGreaterThan(0, $result['credibility_delta']);
        $this->assertNotNull($result['board_fact']);
        $this->assertEquals('FACT-01', $result['board_fact']['id']);
    }

    public function test_invalid_contradiction_penalizes_credibility(): void
    {
        // Presenting irrelevant evidence (EVD-06 Elena's key to Thorne's alibi)
        $result = $this->action->execute('case_001', 'STMT_THORNE_ALIBI_LOCATION', 'EVD-06');

        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('OBJECTION GAGAL', $result['message']);
        $this->assertNull($result['unlocked_clue_id']);
        $this->assertLessThan(0, $result['credibility_delta']);
        $this->assertNull($result['board_fact']);
    }

    public function test_elena_cctv_contradiction(): void
    {
        // Elena claims CCTV was hacked from outside (STMT_ELENA_CCTV_DENIAL)
        // Presenting server logs wiped manually from console (EVD-02)
        $result = $this->action->execute('case_001', 'STMT_ELENA_CCTV_DENIAL', 'EVD-02');

        $this->assertTrue($result['valid']);
        $this->assertEquals('CLUE_ELENA_COMPLICITY_CRACKED', $result['unlocked_clue_id']);
        $this->assertEquals('elena_cracked_cctv', $result['target_dialogue_node']);
        $this->assertNotNull($result['board_fact']);
        $this->assertEquals('FACT-04', $result['board_fact']['id']);
    }

    public function test_croft_ledger_contradiction(): void
    {
        // Croft denies secret offshore transactions (STMT_CROFT_LEDGER_DENIAL)
        // Presenting offshore account ledger (EVD-04)
        $result = $this->action->execute('case_001', 'STMT_CROFT_LEDGER_DENIAL', 'EVD-04');

        $this->assertTrue($result['valid']);
        $this->assertEquals('CLUE_CROFT_FINANCE_CRACKED', $result['unlocked_clue_id']);
        $this->assertEquals('croft_cracked_ledger', $result['target_dialogue_node']);
        $this->assertNotNull($result['board_fact']);
        $this->assertEquals('FACT-06', $result['board_fact']['id']);
    }

    public function test_thorne_finance_contradiction(): void
    {
        // Thorne denies financial conspiratorial ties with Croft (STMT_THORNE_CROFT_DENIAL)
        // Presenting offshore account ledger (EVD-04)
        $result = $this->action->execute('case_001', 'STMT_THORNE_CROFT_DENIAL', 'EVD-04');

        $this->assertTrue($result['valid']);
        $this->assertEquals('CLUE_THORNE_FINANCE_CRACKED', $result['unlocked_clue_id']);
        $this->assertEquals('thorne_confession_details', $result['target_dialogue_node']);
        $this->assertNotNull($result['board_fact']);
        $this->assertEquals('FACT-07', $result['board_fact']['id']);
    }
}
