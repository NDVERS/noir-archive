<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class SaveManagerService
{
    /**
     * Directory where save files reside.
     */
    protected string $savesBasePath;

    public function __construct(?string $savesBasePath = null)
    {
        $this->savesBasePath = $savesBasePath ?: storage_path('app/saves');

        if (! File::isDirectory($this->savesBasePath)) {
            File::makeDirectory($this->savesBasePath, 0755, true, true);
        }
    }

    /**
     * Get default initial game state for a new case playthrough.
     *
     * @return array<string, mixed>
     */
    public function getDefaultState(string $caseId): array
    {
        return [
            'case_id' => $caseId,
            'credibility_score' => 100,
            'discovered_evidences' => ['EVD-01', 'EVD-02', 'EVD-03', 'EVD-04', 'EVD-05', 'EVD-06'],
            'unlocked_clues' => [],
            'unlocked_dialogue_nodes' => [],
            'locked_suspects' => [],
            'interrogation_transcripts' => [],
            'board_connections' => [],
            'board_facts' => [],
            'board_nodes_positions' => [],
            'dialogue_progress' => [],
            'indictment_result' => null,
            'last_active_suspect' => 'suspect_thorne',
            'created_at' => now()->toISOString(),
            'updated_at' => now()->toISOString(),
        ];
    }

    /**
     * Save progress into a given slot (1, 2, 3, or custom string).
     *
     * @param  array<string, mixed>  $state
     * @return array<string, mixed>
     */
    public function save(string $slot, array $state): array
    {
        $slotName = preg_replace('/[^a-zA-Z0-9_-]/', '', $slot) ?: '1';
        $filePath = $this->getSlotPath($slotName);

        $mergedState = array_merge($this->getDefaultState($state['case_id'] ?? 'case_001'), $state);
        $mergedState['updated_at'] = now()->toISOString();
        $mergedState['slot'] = $slotName;

        File::put($filePath, json_encode($mergedState, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $mergedState;
    }

    /**
     * Load save state from slot.
     *
     * @return array<string, mixed>|null
     */
    public function load(string $slot): ?array
    {
        $slotName = preg_replace('/[^a-zA-Z0-9_-]/', '', $slot) ?: '1';
        $filePath = $this->getSlotPath($slotName);

        if (! File::exists($filePath)) {
            return null;
        }

        $content = File::get($filePath);
        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Delete save in a slot.
     */
    public function delete(string $slot): bool
    {
        $slotName = preg_replace('/[^a-zA-Z0-9_-]/', '', $slot) ?: '1';
        $filePath = $this->getSlotPath($slotName);

        if (File::exists($filePath)) {
            return File::delete($filePath);
        }

        return false;
    }

    /**
     * List all existing save files.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listSaves(): array
    {
        if (! File::isDirectory($this->savesBasePath)) {
            return [];
        }

        $files = File::files($this->savesBasePath);
        $saves = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'json') {
                $filename = $file->getFilenameWithoutExtension();
                $slotName = str_replace('save_', '', $filename);
                $content = @json_decode(File::get($file->getRealPath()), true);

                if (is_array($content)) {
                    $saves[] = [
                        'slot' => $slotName,
                        'case_id' => $content['case_id'] ?? 'unknown',
                        'credibility_score' => $content['credibility_score'] ?? 100,
                        'unlocked_clues_count' => count($content['unlocked_clues'] ?? []),
                        'connections_count' => count($content['board_connections'] ?? []),
                        'updated_at' => $content['updated_at'] ?? now()->toISOString(),
                    ];
                }
            }
        }

        return $saves;
    }

    /**
     * Get path for slot filename.
     */
    protected function getSlotPath(string $slot): string
    {
        return rtrim($this->savesBasePath, '/\\').DIRECTORY_SEPARATOR."save_{$slot}.json";
    }
}
