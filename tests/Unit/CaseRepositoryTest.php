<?php

namespace Tests\Unit;

use App\Services\CaseRepositoryService;
use Tests\TestCase;

class CaseRepositoryTest extends TestCase
{
    protected CaseRepositoryService $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CaseRepositoryService;
    }

    public function test_can_read_case_info_for_case_001(): void
    {
        $caseInfo = $this->repository->getCaseInfo('case_001');

        $this->assertIsArray($caseInfo);
        $this->assertEquals('case_001', $caseInfo['id']);
        $this->assertEquals('The Phantom Prototype', $caseInfo['title']);
        $this->assertNotEmpty($caseInfo['victim']);
        $this->assertIsArray($caseInfo['objectives']);
    }

    public function test_can_read_suspects_for_case_001(): void
    {
        $suspects = $this->repository->getSuspects('case_001');

        $this->assertIsArray($suspects);
        $this->assertCount(3, $suspects);

        $thorne = $this->repository->getSuspect('case_001', 'suspect_thorne');
        $this->assertNotNull($thorne);
        $this->assertEquals('Dr. Aris Thorne', $thorne['name']);
    }

    public function test_can_read_evidences_for_case_001(): void
    {
        $evidences = $this->repository->getEvidences('case_001');

        $this->assertIsArray($evidences);
        $this->assertCount(6, $evidences);

        $evidence = $this->repository->getEvidence('case_001', 'EVD-05');
        $this->assertNotNull($evidence);
        $this->assertStringContainsString('Manset', $evidence['title']);
    }

    public function test_can_read_dialogues_and_solution(): void
    {
        $dialogues = $this->repository->getDialogues('case_001');
        $this->assertArrayHasKey('suspect_thorne', $dialogues);
        $this->assertArrayHasKey('suspect_elena', $dialogues);
        $this->assertArrayHasKey('suspect_croft', $dialogues);

        $solution = $this->repository->getSolution('case_001');
        $this->assertEquals('suspect_thorne', $solution['culprit_id']);
        $this->assertEquals('industrial_espionage_debt', $solution['correct_motive']);
        $this->assertContains('EVD-05', $solution['key_evidence_chain']);
    }

    public function test_can_get_initial_bundle(): void
    {
        $bundle = $this->repository->getInitialBundle('case_001');

        $this->assertArrayHasKey('case_info', $bundle);
        $this->assertArrayHasKey('suspects', $bundle);
        $this->assertArrayHasKey('evidences', $bundle);
        $this->assertArrayHasKey('dialogues', $bundle);
        $this->assertArrayHasKey('available_motives', $bundle);
    }
}
