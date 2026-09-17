<?php

namespace App\Actions;

use App\Services\CaseRepositoryService;

class EvaluateAccusationAction
{
    public function __construct(
        protected CaseRepositoryService $caseRepository
    ) {}

    /**
     * Evaluate the player's final indictment submission.
     *
     * @param  array<int, string>  $evidenceChain
     * @return array<string, mixed>
     */
    public function execute(string $caseId, string $culpritId, string $motiveId, array $evidenceChain): array
    {
        $solution = $this->caseRepository->getSolution($caseId);
        $expectedCulprit = $solution['culprit_id'] ?? '';
        $expectedMotive = $solution['correct_motive'] ?? '';
        $expectedChain = $solution['key_evidence_chain'] ?? [];
        $narratives = $solution['narratives'] ?? [];

        // 1. Evaluate Culprit (40 points)
        $culpritCorrect = ($culpritId === $expectedCulprit);
        $culpritScore = $culpritCorrect ? 40 : 0;

        // 2. Evaluate Motive (20 points)
        $motiveCorrect = ($motiveId === $expectedMotive);
        $motiveScore = $motiveCorrect ? 20 : 0;

        // 3. Evaluate Key Evidence Chain (40 points proportional)
        $cleanChain = array_values(array_unique(array_filter($evidenceChain, fn ($val) => is_string($val) && trim($val) !== '')));
        $totalExpectedChain = count($expectedChain);
        $matchedEvidences = array_intersect($cleanChain, $expectedChain);
        $matchedCount = count($matchedEvidences);

        $evidenceScore = 0;
        if ($totalExpectedChain > 0) {
            $evidenceScore = (int) round(($matchedCount / $totalExpectedChain) * 40);
        }

        // Small penalty if excessive spamming of irrelevant evidences (more than required)
        $irrelevantEvidences = array_diff($cleanChain, $expectedChain);
        if (count($irrelevantEvidences) > 1) {
            $evidenceScore = max(0, $evidenceScore - (count($irrelevantEvidences) * 5));
        }

        // Total Score
        $totalScore = min(100, max(0, $culpritScore + $motiveScore + $evidenceScore));
        $isSuccess = ($totalScore >= 80);

        // Grade calculation
        $grade = match (true) {
            $totalScore >= 95 => 'S',
            $totalScore >= 80 => 'A',
            $totalScore >= 60 => 'B',
            $totalScore >= 40 => 'C',
            default => 'F',
        };

        // Determine narrative feedback
        if ($isSuccess) {
            $narrative = ($totalScore >= 95)
                ? ($narratives['verdict_perfect'] ?? 'Dakwaan sempurna diterima!')
                : ($narratives['verdict_good'] ?? 'Dakwaan diterima dengan cukup bukti.');
        } else {
            if (! $culpritCorrect) {
                $narrative = $narratives['verdict_failed_culprit'] ?? 'Tersangka yang Anda tuduh bukan pelaku sebenarnya.';
            } else {
                $narrative = $narratives['verdict_failed_evidence'] ?? 'Bukti yang diajukan tidak cukup untuk vonis bersalah.';
            }
        }

        return [
            'success' => $isSuccess,
            'total_score' => $totalScore,
            'grade' => $grade,
            'status' => $isSuccess ? 'INDICTMENT_ACCEPTED' : 'INDICTMENT_REJECTED',
            'breakdown' => [
                'culprit' => [
                    'submitted' => $culpritId,
                    'is_correct' => $culpritCorrect,
                    'score' => $culpritScore,
                    'max_score' => 40,
                ],
                'motive' => [
                    'submitted' => $motiveId,
                    'is_correct' => $motiveCorrect,
                    'score' => $motiveScore,
                    'max_score' => 20,
                ],
                'evidence_chain' => [
                    'submitted' => $cleanChain,
                    'matched' => array_values($matchedEvidences),
                    'matched_count' => $matchedCount,
                    'required_count' => $totalExpectedChain,
                    'score' => $evidenceScore,
                    'max_score' => 40,
                ],
            ],
            'narrative' => $narrative,
        ];
    }
}
