<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Retro;
use App\Models\RetroColumn;
use App\Models\RetroData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\CardMoved;
use App\Events\CardAdded;

class RetroController extends Controller
{
    /**
     * Display list of retrospectives with filtering by role
     */
    public function index()
    {
        $user = Auth::user();
        $userRole = $user->school()->pivot->role ?? null;

        
        $query = Retro::with(['cohort', 'user']);

        
        if ($userRole === 'admin') {
            
        } elseif ($userRole === 'teacher') {
            
            $query->where('user_id', $user->id);
        } else {
            
            $userCohortIds = $user->cohorts()->pluck('cohorts.id')->toArray();
            $query->whereIn('cohort_id', $userCohortIds);
        }

        $retros = $query->get()->groupBy('cohort_id');
        
        
        if ($userRole === 'admin') {
            $cohorts = Cohort::all();
        } elseif ($userRole === 'teacher') {
            $cohorts = Cohort::whereIn('id', $retros->keys())->get();
        } else {
            $cohorts = $user->cohorts;
        }

        $isStudent = !in_array($userRole, ['admin', 'teacher']);
        return view('pages.retros.index', compact('retros', 'cohorts', 'isStudent'));
    }

    /**
     * Show form to create a new retro
     */
    public function create()
    {
        $cohorts = Cohort::all();
        return view('pages.retros.create', compact('cohorts'));  
    }

    /**
     * Store a new retro with its columns
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cohort_id' => 'required|exists:cohorts,id',
            'columns' => 'required|array|min:1',
            'columns.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            $retro = Retro::create([
                'name' => $request->input('name'),
                'cohort_id' => $request->input('cohort_id'),
                'user_id' => Auth::id(),
            ]);

            foreach ($request->input('columns') as $index => $colName) {
                $retro->columns()->create([
                    'name' => $colName,
                    'position' => $index,
                ]);
            }
        });

        return redirect()->route('retro.index')
            ->with('success', __('Rétrospective créée avec succès.'));
    }

    /**
     * Display a single retrospective Kanban board
     */
    public function show(Retro $retro)
    {
        $user = Auth::user();
        $userRole = $user->school()->pivot->role ?? null;
        
        
        if (!$userRole || ($userRole !== 'admin' && $userRole !== 'teacher')) {
            
            $userCohortIds = $user->cohorts()->pluck('cohorts.id')->toArray();
            if (!in_array($retro->cohort_id, $userCohortIds)) {
                abort(403, 'Vous n\'avez pas accès à cette rétrospective.');
            }
        } elseif ($userRole === 'teacher' && $retro->user_id !== $user->id) {
            
            abort(403, 'Vous n\'avez pas accès à cette rétrospective.');
        }
        
        $retro->load(['columns.data']);
        
        
        if ($retro->columns->isEmpty()) {
            $defaultColumns = [
                'Points positifs' => 0,
                'Points à améliorer' => 1,
                'Actions' => 2
            ];
            
            foreach ($defaultColumns as $name => $position) {
                $retro->columns()->create([
                    'name' => $name,
                    'position' => $position
                ]);
            }
            
            
            $retro->load(['columns.data']);
        }
        
        
        $isStudent = !in_array($userRole, ['admin', 'teacher']);
        
        return view('pages.retros.show', compact('retro', 'isStudent'));
    }

    /**
     * Remove the specified retrospective
     */
    public function destroy(Retro $retro)
    {
        $retro->delete();

        return redirect()->route('retro.index')
            ->with('success', __('Rétrospective supprimée avec succès.'));
    }

    /**
     * Add a new item to a column in the retrospective
     */
    public function addItem(Request $request, RetroColumn $column)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $user = Auth::user();
        $userRole = $user->school()->pivot->role ?? null;
        $retro = $column->retro;
        
        
        if (!$userRole) {
            abort(403, 'Vous n\'êtes pas autorisé à ajouter des retours.');
        } elseif ($userRole === 'teacher' && $retro->user_id !== $user->id) {
            
            abort(403, 'Vous ne pouvez pas ajouter de retours à cette rétrospective.');
        } elseif (!in_array($userRole, ['admin', 'teacher'])) {
            
            $userCohortIds = $user->cohorts()->pluck('cohorts.id')->toArray();
            if (!in_array($retro->cohort_id, $userCohortIds)) {
                abort(403, 'Vous n\'êtes pas autorisé à ajouter des retours à cette rétrospective.');
            }
        }

        
        $position = $column->data()->count();

        
        $item = $column->data()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'position' => $position,
        ]);

        
        event(new \App\Events\CardAdded(
            $item->id,
            $column->id,
            $item->name,
            $item->description,
            $position
        ));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description
            ]);
        }

        return redirect()->route('retro.show', $column->retro_id)
            ->with('success', __('Retour ajouté avec succès.'));
    }

    /**
     * Remove an item from the retrospective
     */
    public function removeItem(Request $request, RetroData $item)
    {
        $retroColumn = $item->column;
        $retro = $retroColumn->retro;
        $retroId = $retro->id;
        
        $user = Auth::user();
        $userRole = $user->school()->pivot->role ?? null;

        
        if (!$userRole) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer des retours.');
        } elseif ($userRole === 'teacher' && $retro->user_id !== $user->id) {
            
            abort(403, 'Vous ne pouvez pas supprimer de retours dans cette rétrospective.');
        } elseif (!in_array($userRole, ['admin', 'teacher'])) {
            
            $userCohortIds = $user->cohorts()->pluck('cohorts.id')->toArray();
            if (!in_array($retro->cohort_id, $userCohortIds)) {
                abort(403, 'Vous n\'êtes pas autorisé à supprimer des retours dans cette rétrospective.');
            }
        }

        $item->delete();

        
        event(new \App\Events\CardRemoved($item->id, $retroColumn->id));

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('retro.show', $retroId)
            ->with('success', __('Retour supprimé avec succès.'));
    }

    /**
     * Move an item to a different column and/or position
     */
    public function moveItem(Request $request, RetroData $item)
    {
        $data = $request->validate([
            'column_id' => 'required|exists:retros_columns,id',
            'position'  => 'required|integer|min:0',
        ]);

        $targetColumn = RetroColumn::findOrFail($data['column_id']);
        $retro = $item->column->retro;
        $originalColumn = $item->column;

        
        $userRole = Auth::user()->school()->pivot->role ?? null;
        if (!$userRole) {
            abort(403);
        }
        
        if ($userRole === 'teacher' && $retro->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($userRole, ['admin', 'teacher'])) {
            $userCohortIds = Auth::user()->cohorts()->pluck('cohorts.id')->toArray();
            if (!in_array($retro->cohort_id, $userCohortIds)) {
                abort(403);
            }
        }

        DB::transaction(function () use ($item, $targetColumn, $data, $originalColumn) {
            // Move item
            $item->update([
                'retros_column_id' => $targetColumn->id,
                'position' => $data['position'],
            ]);
            $targetColumn->data()->get()->each(function ($it, $index) {
                $it->update(['position' => $index]);
            });

            if ($originalColumn->id !== $targetColumn->id) {
                $originalColumn->data()->get()->each(function ($it, $index) {
                    $it->update(['position' => $index]);
                });
            }
        });

        event(new CardMoved($item->id, $originalColumn->id, $targetColumn->id, $data['position']));

        return response()->json(['success' => true]);
    }
}