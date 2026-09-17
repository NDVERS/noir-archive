<?php

namespace App\Actions;

use App\Services\CaseRepositoryService;

class CheckContradictionAction
{
    public function __construct(
        protected CaseRepositoryService $caseRepository
    ) {}

    /**
     * Evaluate if the presented evidence contradicts the suspect's statement.
     *
     * @return array{
     *     valid: bool,
     *     message: string,
     *     unlocked_clue_id: ?string,
     *     target_dialogue_node: ?string,
     *     credibility_delta: int,
     *     board_fact: ?array<string, mixed>
     * }
     */
    public function execute(string $caseId, string $statementId, string $evidenceId): array
    {
        $solution = $this->caseRepository->getSolution($caseId);
        $contradictions = $solution['contradictions'] ?? [];

        foreach ($contradictions as $item) {
            if ($item['statement_id'] === $statementId && $item['evidence_id'] === $evidenceId) {
                return [
                    'valid' => true,
                    'message' => $item['success_message'] ?? 'OBJECTION! Kontradiksi alibi berhasil dibongkar!',
                    'unlocked_clue_id' => $item['unlocked_clue_id'] ?? null,
                    'target_dialogue_node' => $item['target_dialogue_node'] ?? null,
                    'credibility_delta' => (int) ($item['credibility_gain'] ?? 15),
                    'board_fact' => $item['board_fact'] ?? null,
                ];
            }
        }

        // Penalty for irrelevant evidence presentation
        return [
            'valid' => false,
            'message' => 'OBJECTION GAGAL! Saksi tersenyum sinis: "Bukti yang Anda sodorkan sama sekali tidak membantah ucapan saya, Detektif."',
            'unlocked_clue_id' => null,
            'target_dialogue_node' => null,
            'credibility_delta' => -15,
            'board_fact' => null,
        ];
    }
}
