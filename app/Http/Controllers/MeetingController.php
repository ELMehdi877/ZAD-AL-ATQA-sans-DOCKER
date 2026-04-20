<?php

namespace App\Http\Controllers;

use App\Models\Halaqa;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MeetingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'cheikh') {
            $meetings = Meeting::where('cheikh_id', $user->id)
                ->with('halaqa')
                ->latest()
                ->get();
        } elseif ($user->role === 'student') {
            $student = $user->student;

            if (! $student) {
                $meetings = collect();
            } else {
                $halaqaIds = $student->halaqas()->pluck('halaqas.id');

                $meetings = Meeting::whereIn('halaqa_id', $halaqaIds)
                    ->with('halaqa')
                    ->latest()
                    ->get();
            }
        } else {
            $meetings = collect();
        }

        return view('meetings.index', compact('meetings'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'cheikh') {
            abort(403, 'Seul un cheikh peut creer une reunion.');
        }

        $halaqas = Halaqa::where('cheikh_id', $user->id)->get();

        return view('meetings.create', compact('halaqas'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'cheikh') {
            abort(403, 'Seul un cheikh peut creer une reunion.');
        }

        $request->validate([
            'halaqa_id' => ['required', 'exists:halaqas,id'],
        ]);

        $halaqa = Halaqa::where('id', $request->halaqa_id)
            ->where('cheikh_id', $user->id)
            ->first();

        if (! $halaqa) {
            abort(403, 'Cette halaqa ne vous appartient pas.');
        }

        $roomName = Str::slug(($user->nom ?? $user->name ?? 'cheikh') . '-' . $halaqa->nom_halaqa . '-' . now()->format('YmdHis'));

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.daily.key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.daily.co/v1/rooms', [
            'name' => $roomName,
            'privacy' => 'private',
            'properties' => [
                'enable_chat' => true,
                'enable_screenshare' => true,
                'start_video_off' => false,
                'start_audio_off' => false,
                'exp' => now()->addHours(2)->timestamp,
            ],
        ]);

        if (! $response->successful()) {
            return back()->withErrors([
                'daily' => 'Erreur lors de la creation de la room Daily.'
            ])->withInput();
        }

        $data = $response->json();

        $meeting = Meeting::create([
            'meeting_name' => $data['name'],
            'url' => $data['url'],
            'cheikh_id' => $user->id,
            'halaqa_id' => $halaqa->id,
        ]);

        return redirect()->route('meetings.join', $meeting);
    }

    public function show(Meeting $meeting)
    {
        return $this->join($meeting);
    }

    public function join(Meeting $meeting)
    {
        $user = Auth::user();

        if (! $this->userCanJoin($meeting, $user)) {
            abort(403, 'Vous ne pouvez pas rejoindre cette reunion.');
        }

        $roomName = data_get($meeting, 'meeting_name');
        $isOwner = (int) data_get($meeting, 'cheikh_id') === (int) $user->id;

        $tokenResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.daily.key'),
            'Content-Type' => 'application/json',
        ])->post('https://api.daily.co/v1/meeting-tokens', [
            'properties' => [
                'room_name' => $roomName,
                'is_owner' => $isOwner,
                'exp' => now()->addMinutes(30)->timestamp,
                'user_name' => trim(($user->prenom ?? '') . ' ' . ($user->nom ?? '')) ?: ($user->email ?? 'Utilisateur'),
            ],
        ]);

        if (! $tokenResponse->successful()) {
            return redirect()->route('meetings.index')->with('error', 'Impossible de generer un token de reunion pour le moment.');
        }

        $token = data_get($tokenResponse->json(), 'token');

        if (! $token) {
            return redirect()->route('meetings.index')->with('error', 'Token Daily invalide.');
        }

        $students = collect();
        if ($isOwner) {
            $students = $meeting->halaqa->students()->with('user')->get();
        }

        return view('meetings.show', [
            'meeting' => $meeting,
            'token' => $token,
            'roomUrl' => data_get($meeting, 'url'),
            'isOwner' => $isOwner,
            'students' => $students,
        ]);
    }

    public function sendLink(Request $request, Meeting $meeting)
    {
        $user = Auth::user();
        if ($user->role !== 'cheikh' || (int) $meeting->cheikh_id !== (int) $user->id) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id'
        ]);

        $students = \App\Models\Student::whereIn('id', $request->student_ids)->with('user')->get();
        $messageText = "Rejoignez notre réunion en direct pour la halaqa {$meeting->halaqa->nom_halaqa} 👇";
        $linkText = route('meetings.join', $meeting);

        foreach ($students as $student) {
            if ($student->user) {
                // Creates conversation and sends text
                $user->sendMessageTo($student->user, $messageText);
                // Sends the isolated link so Wirechat recognizes it as a clickable URL
                $user->sendMessageTo($student->user, $linkText);
            }
        }

        return back()->with('success', 'Lien envoyé avec succès via la messagerie interne.');
    }

    private function userCanJoin(Meeting $meeting, $user): bool
    {
        if (! $user) {
            return false;
        }

        $cheikhId = (int) data_get($meeting, 'cheikh_id');
        if ($cheikhId === (int) $user->id) {
            return true;
        }

        if ($user->role !== 'student') {
            return false;
        }

        $student = $user->student;
        if (! $student) {
            return false;
        }

        return $student->halaqas()
            ->where('halaqas.id', $meeting->halaqa_id)
            ->wherePivot('statut', 'active')
            ->exists();
    }
}