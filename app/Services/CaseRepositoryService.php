<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use InvalidArgumentException;
use RuntimeException;

class CaseRepositoryService
{
    /**
     * Base path where cases are stored.
     */
    protected string $casesBasePath;

    public function __construct(?string $casesBasePath = null)
    {
        $this->casesBasePath = $casesBasePath ?: storage_path('app/cases');
    }

    /**
     * Check if a case directory exists.
     */
    public function caseExists(string $caseId): bool
    {
        return File::isDirectory($this->getCasePath($caseId));
    }

    /**
     * Get all available cases in the repository.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAllCases(): array
    {
        if (! File::isDirectory($this->casesBasePath)) {
            return [];
        }

        $directories = File::directories($this->casesBasePath);
        $cases = [];

        foreach ($directories as $dir) {
            $caseId = basename($dir);
            try {
                $cases[] = $this->getCaseInfo($caseId);
            } catch (\Throwable $e) {
                // Skip corrupted case folders gracefully
                continue;
            }
        }

        return $cases;
    }

    /**
     * Get case metadata and overview.
     *
     * @return array<string, mixed>
     */
    public function getCaseInfo(string $caseId): array
    {
        return $this->readJsonFile($caseId, 'case_info.json');
    }

    /**
     * Get list of suspects for a case.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSuspects(string $caseId): array
    {
        return $this->readJsonFile($caseId, 'suspects.json');
    }

    /**
     * Get single suspect by ID.
     *
     * @return array<string, mixed>|null
     */
    public function getSuspect(string $caseId, string $suspectId): ?array
    {
        $suspects = $this->getSuspects($caseId);
        foreach ($suspects as $suspect) {
            if (($suspect['id'] ?? null) === $suspectId) {
                return $suspect;
            }
        }

        return null;
    }

    /**
     * Get list of evidences for a case.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getEvidences(string $caseId): array
    {
        return $this->readJsonFile($caseId, 'evidences.json');
    }

    /**
     * Get single evidence by ID.
     *
     * @return array<string, mixed>|null
     */
    public function getEvidence(string $caseId, string $evidenceId): ?array
    {
        $evidences = $this->getEvidences($caseId);
        foreach ($evidences as $evidence) {
            if (($evidence['id'] ?? null) === $evidenceId) {
                return $evidence;
            }
        }

        return null;
    }

    /**
     * Get dialogues for all suspects or a specific suspect.
     *
     * @return array<string, mixed>
     */
    public function getDialogues(string $caseId, ?string $suspectId = null): array
    {
        $dialogues = $this->readJsonFile($caseId, 'dialogues.json');

        if ($suspectId !== null) {
            return $dialogues[$suspectId] ?? [];
        }

        return $dialogues;
    }

    /**
     * Get solution data for contradiction and indictment checks.
     *
     * @return array<string, mixed>
     */
    public function getSolution(string $caseId): array
    {
        return $this->readJsonFile($caseId, 'solution.json');
    }

    /**
     * Build full initial bundle for the game client.
     *
     * @return array<string, mixed>
     */
    public function getInitialBundle(string $caseId): array
    {
        return [
            'case_info' => $this->getCaseInfo($caseId),
            'suspects' => $this->getSuspects($caseId),
            'evidences' => $this->getEvidences($caseId),
            'dialogues' => $this->getDialogues($caseId),
            'available_motives' => $this->getSolution($caseId)['available_motives'] ?? [],
        ];
    }

    /**
     * Resolve path to specific case folder.
     */
    public function getCasePath(string $caseId): string
    {
        return rtrim($this->casesBasePath, '/\\').DIRECTORY_SEPARATOR.$caseId;
    }

    /**
     * Safely read and decode a JSON file within a case directory.
     *
     * @return array<string, mixed>|array<int, mixed>
     */
    protected function readJsonFile(string $caseId, string $filename): array
    {
        $filePath = $this->getCasePath($caseId).DIRECTORY_SEPARATOR.$filename;

        if (! File::exists($filePath)) {
            throw new InvalidArgumentException("Case file [{$filename}] not found in case [{$caseId}].");
        }

        $content = File::get($filePath);
        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            throw new RuntimeException("Malformed JSON file [{$filename}] in case [{$caseId}]: ".json_last_error_msg());
        }

        return $decoded;
    }
}
