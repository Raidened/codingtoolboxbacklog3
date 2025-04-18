<?php

namespace App\Services;

use App\Models\Cohort;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\Http;

class AIHomogeneousGroupService
{
    /**
     * Appelle l'API Gemini pour générer des groupes homogènes
     * @param Cohort $cohort
     * @param int $maxStudentsPerGroup
     * @param bool $useHistory
     * @return array|null
     */
    public function generateGroups(Cohort $cohort, int $maxStudentsPerGroup, bool $useHistory = false): ?array
    {
        // Récupérer les étudiants et leurs notes
        $students = $cohort->users()->with('grades')->get();
        if ($students->isEmpty()) return null;
        
        logger()->info('Generating groups for cohort', [
            'cohort_id' => $cohort->id,
            'cohort_name' => $cohort->name,
            'students_count' => $students->count(),
            'max_per_group' => $maxStudentsPerGroup
        ]);

        $studentData = $students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'skill' => optional($student->grades->first())->score ?? rand(8, 16), // fallback aléatoire pour test
            ];
        })->toArray();

        // Historique des groupes
        $history = [];
        if ($useHistory) {
            foreach ($students as $student) {
                $history[$student->id] = $student->groups->pluck('id')->toArray();
            }
        }

        $studentsCount = $students->count(); 
        // Appel à l'API Gemini v1beta2
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-goog-api-key' => config('services.ai.key')
        ])->post(config('services.ai.url'), [
            'contents' => [
                'role' => 'user',
                'parts' => [
                    [
                        'text' => "OBJECTIF: Créer des groupes d'étudiants équilibrés où chaque groupe aura une moyenne de compétences similaire.

INSTRUCTIONS:
1. Répartis TOUS les étudiants de la liste en groupes contenant au maximum $maxStudentsPerGroup membres.
2. Aucun étudiant ne doit être exclu ou laissé de côté. ASSURE-TOI que le nombre total d'étudiants répartis dans les groupes correspond exactement au nombre total d'étudiants fournis.
3. Les groupes doivent être aussi homogènes que possible en termes de moyenne des compétences.
4. Privilégie des groupes complets autant que possible, en minimisant les groupes incomplets.
5. IMPORTANT : Si le nombre total d'étudiants ($studentsCount) n'est pas un multiple exact de $maxStudentsPerGroup, INCLUS TOUS les étudiants. Forme des groupes de $maxStudentsPerGroup et répartis les étudiants restants soit dans un groupe plus petit, soit en les ajoutant à des groupes existants (créant des groupes de taille $maxStudentsPerGroup + 1), tout en maintenant l'homogénéité. NE LAISSE AUCUN ÉTUDIANT SANS GROUPE.
6. Si applicable, prends en compte l'historique des groupes pour éviter de regrouper des étudiants ayant déjà travaillé ensemble.
7. VÉRIFICATION FINALE : Avant de retourner le JSON, vérifie que le total des étudiants dans tous les groupes générés correspond à $studentsCount.
8. AVANT DE RETOURNER, assure-toi que :
    - La sortie est un JSON valide.
    - Chaque ID étudiant fourni apparaît exactement une fois.
    - Si la validation échoue, retourne exactement : {\"error\": \"validation_failed\", \"missing_ids\": [...liste des IDs manquants...]}


FORMAT DE RÉPONSE:
Retourne un objet JSON au format précis suivant:
{\"groups\": [{\"members\": [{\"id\": 1, \"skill\": 12}]}]}

DONNÉES ÉTUDIANTS (id, nom, note):\n" .
implode("\n", array_map(function($s) { return $s['id']." | ".$s['name']." | note: ".$s['skill']; }, $studentData)) .
"\n\nDONNÉES JSON:\n" .
json_encode([
    'students' => $studentData,
    'max_students_per_group' => $maxStudentsPerGroup,
    'history' => $history,
], JSON_PRETTY_PRINT)
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.2,
                'topK' => 40,
                'topP' => 0.8,
                'maxOutputTokens' => 1024,
                'responseMimeType' => 'application/json'
            ]
        ]);
        logger()->info('AI response status '.$response->status(), ['body' => $response->body()]);

        if ($response->successful()) {
            // Extraction de la réponse et parsing...
            if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
                $jsonText = $response['candidates'][0]['content']['parts'][0]['text'];
                
                // Tenter de parser le JSON retourné
                try {
                    $jsonData = json_decode($jsonText, true);
                    logger()->info('AI parsed response', ['data' => $jsonData]);
                    
                    if (isset($jsonData['groups'])) {
                        return $jsonData['groups'];
                    }
                } catch (\Exception $e) {
                    logger()->error('Failed to parse AI response', ['error' => $e->getMessage()]);
                }
            }
        }
        
        // Aucune réponse exploitable de l'IA
        return null;
    }
}
