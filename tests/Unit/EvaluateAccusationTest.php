<?php

namespace Tests\Unit;

use App\Actions\EvaluateAccusationAction;
use App\Services\CaseRepositoryService;
use Tests\TestCase;

class EvaluateAccusationTest extends TestCase
{
    protected EvaluateAccusationAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new EvaluateAccusationAction(new CaseRepositoryService);
    }

    public function test_perfect_indictment_yields_s_rank(): void
    {
        // Culprit: Thorne (40 pts)
        // Motive: industrial_espionage_debt (20 pts)
        // Key Chain: EVD-01, EVD-03, EVD-05 (40 pts)
        $result = $this->action->execute(
            'case_001',
            'suspect_thorne',
            'industrial_espionage_debt',
            ['EVD-01', 'EVD-03', 'EVD-05']
        );

        $this->assertTrue($result['success']);
        $this->assertEquals(100, $result['total_score']);
        $this->assertEquals('S', $result['grade']);
        $this->assertEquals('INDICTMENT_ACCEPTED', $result['status']);
        $this->assertEquals(40, $result['breakdown']['culprit']['score']);
        $this->assertEquals(20, $result['breakdown']['motive']['score']);
        $this->assertEquals(40, $result['breakdown']['evidence_chain']['score']);
    }

    public function test_indictment_with_partial_evidence_above_threshold_passes(): void
    {
        // Culprit: Thorne (40) + Motive (20) + 2 out of 3 evidences (~27) = 87 => Grade A
        $result = $this->action->execute(
            'case_001',
            'suspect_thorne',
            'industrial_espionage_debt',
            ['EVD-01', 'EVD-05']
        );

        $this->assertTrue($result['success']);
        $this->assertGreaterThanOrEqual(80, $result['total_score']);
        $this->assertEquals('A', $result['grade']);
        $this->assertEquals('INDICTMENT_ACCEPTED', $result['status']);
    }

    public function test_wrong_culprit_fails_indictment(): void
    {
        // Culprit: Elena (wrong -> 0 pts)
        // Even with motive or some evidence, total is below 80
        $result = $this->action->execute(
            'case_001',
            'suspect_elena',
            'industrial_espionage_debt',
            ['EVD-01', 'EVD-03']
        );

        $this->assertFalse($result['success']);
        $this->assertLessThan(80, $result['total_score']);
        $this->assertEquals('INDICTMENT_REJECTED', $result['status']);
        $this->assertFalse($result['breakdown']['culprit']['is_correct']);
    }
}
