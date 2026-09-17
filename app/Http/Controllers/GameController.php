<?php

namespace App\Http\Controllers;

use App\Actions\CheckContradictionAction;
use App\Actions\EvaluateAccusationAction;
use App\Services\CaseRepositoryService;
use App\Services\SaveManagerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function __construct(
        protected CaseRepositoryService $caseRepository,
        protected SaveManagerService $saveManager
    ) {}

    /**
     * Start Menu / Title Screen: 1950s Retro Noir Game Portal.
     */
    public function welcome(?string $caseId = 'case_001'): View
    {
        $caseId = $caseId ?: 'case_001';
        $caseInfo = $this->caseRepository->getCaseInfo($caseId);
        $allCases = $this->caseRepository->getAllCases();
        $saves = $this->saveManager->listSaves();

        return view('welcome', [
            'caseId' => $caseId,
            'caseInfo' => $caseInfo,
            'allCases' => $allCases,
            'saves' => $saves,
        ]);
    }

    /**
     * Desk Hub: Main detective workspace.
     */
    public function desk(?string $caseId = 'case_001'): View
    {
        $caseId = $caseId ?: 'case_001';
        $bundle = $this->caseRepository->getInitialBundle($caseId);
        $allCases = $this->caseRepository->getAllCases();
        $saves = $this->saveManager->listSaves();

        return view('pages.desk', [
            'caseId' => $caseId,
            'case' => $bundle['case_info'],
            'caseInfo' => $bundle['case_info'],
            'suspects' => $bundle['suspects'],
            'evidences' => $bundle['evidences'],
            'availableMotives' => $bundle['available_motives'],
            'allCases' => $allCases,
            'saves' => $saves,
        ]);
    }

    /**
     * Investigation Corkboard: Dynamic SVG graph.
     */
    public function board(?string $caseId = 'case_001'): View
    {
        $caseId = $caseId ?: 'case_001';
        $bundle = $this->caseRepository->getInitialBundle($caseId);
        $saves = $this->saveManager->listSaves();

        return view('pages.board', [
            'caseId' => $caseId,
            'case' => $bundle['case_info'],
            'caseInfo' => $bundle['case_info'],
            'suspects' => $bundle['suspects'],
            'evidences' => $bundle['evidences'],
            'availableMotives' => $bundle['available_motives'],
            'saves' => $saves,
        ]);
    }

    /**
     * Interrogation Chamber: Branching dialogue & objection mechanics.
     */
    public function interrogation(?string $caseId = 'case_001', ?string $suspectId = null): View
    {
        $caseId = $caseId ?: 'case_001';
        $bundle = $this->caseRepository->getInitialBundle($caseId);
        $activeSuspectId = $suspectId ?: ($bundle['suspects'][0]['id'] ?? 'suspect_thorne');
        $saves = $this->saveManager->listSaves();

        return view('pages.interrogation', [
            'caseId' => $caseId,
            'activeSuspectId' => $activeSuspectId,
            'case' => $bundle['case_info'],
            'caseInfo' => $bundle['case_info'],
            'suspects' => $bundle['suspects'],
            'evidences' => $bundle['evidences'],
            'dialogues' => $bundle['dialogues'],
            'availableMotives' => $bundle['available_motives'],
            'saves' => $saves,
        ]);
    }

    /**
     * API: Fetch dialogue data for a suspect or all suspects.
     */
    public function apiGetDialogues(string $caseId, ?string $suspectId = null): JsonResponse
    {
        $dialogues = $this->caseRepository->getDialogues($caseId, $suspectId);

        return response()->json([
            'case_id' => $caseId,
            'suspect_id' => $suspectId,
            'dialogues' => $dialogues,
        ]);
    }

    /**
     * API: Present evidence against a statement (Objection).
     */
    public function apiPresentEvidence(Request $request, CheckContradictionAction $action): JsonResponse
    {
        $validated = $request->validate([
            'case_id' => ['required', 'string'],
            'statement_id' => ['required', 'string'],
            'evidence_id' => ['required', 'string'],
        ]);

        $result = $action->execute(
            $validated['case_id'],
            $validated['statement_id'],
            $validated['evidence_id']
        );

        return response()->json($result);
    }

    /**
     * API: Evaluate Final Indictment submission.
     */
    public function apiEvaluateAccusation(Request $request, EvaluateAccusationAction $action): JsonResponse
    {
        $validated = $request->validate([
            'case_id' => ['required', 'string'],
            'culprit_id' => ['required', 'string'],
            'motive_id' => ['required', 'string'],
            'evidence_chain' => ['required', 'array'],
            'evidence_chain.*' => ['string'],
        ]);

        $result = $action->execute(
            $validated['case_id'],
            $validated['culprit_id'],
            $validated['motive_id'],
            $validated['evidence_chain']
        );

        return response()->json($result);
    }

    /**
     * API: Save game progress to storage slot.
     */
    public function apiSaveGame(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'slot' => ['sometimes', 'string'],
            'state' => ['required', 'array'],
        ]);

        $slot = $validated['slot'] ?? '1';
        $saved = $this->saveManager->save($slot, $validated['state']);

        return response()->json([
            'success' => true,
            'message' => "Progress tersimpan di Slot [{$slot}].",
            'save' => $saved,
        ]);
    }

    /**
     * API: Load game progress from slot.
     */
    public function apiLoadGame(string $slot = '1'): JsonResponse
    {
        $loaded = $this->saveManager->load($slot);

        if (! $loaded) {
            return response()->json([
                'success' => false,
                'message' => "Slot penyimpanan [{$slot}] tidak ditemukan.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'save' => $loaded,
        ]);
    }

    /**
     * API: List all saves.
     */
    public function apiListSaves(): JsonResponse
    {
        return response()->json([
            'saves' => $this->saveManager->listSaves(),
        ]);
    }

    /**
     * API: Reset state to default.
     */
    public function apiResetGame(string $caseId = 'case_001'): JsonResponse
    {
        $default = $this->saveManager->getDefaultState($caseId);

        return response()->json([
            'success' => true,
            'default_state' => $default,
        ]);
    }
}
