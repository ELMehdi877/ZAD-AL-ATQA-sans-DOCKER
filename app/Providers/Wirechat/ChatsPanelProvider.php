<?php

namespace App\Providers\Wirechat;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Wirechat\Wirechat\Panel;
use Wirechat\Wirechat\PanelProvider;

class ChatsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
             ->id('chats')
             ->path('chats')
             ->messagesQueue('default')
             ->eventsQueue('default')
             ->createChatAction()
             ->parseMessageUrls()
             ->chatsSearch(true)
             ->searchUsersUsing(function ($needle) {
                 $authUser = Auth::user();

                 $query = User::query()
                     ->where('statut', 'active');

                 if ($authUser instanceof User) {
                     $query->where('id', '!=', $authUser->id);

                     if (in_array($authUser->role, ['student', 'parent'], true)) {
                         $allowedCheikhIds = $this->allowedChatRecipientIdsFor($authUser);

                         $query->where(function ($recipientQuery) use ($allowedCheikhIds) {
                             $recipientQuery->where('role', 'admin');

                             if (! empty($allowedCheikhIds)) {
                                 $recipientQuery->orWhereIn('id', $allowedCheikhIds);
                             }
                         });
                     }
                 }

                 if (filled($needle)) {
                     $query->where(function ($q) use ($needle) {
                         $q->where('nom', 'like', "%{$needle}%")
                             ->orWhere('prenom', 'like', "%{$needle}%")
                             ->orWhere('email', 'like', "%{$needle}%");
                     });
                 }

                 return $query->limit(30)->get();
             })
             ->middleware(['web','auth'])
             ->default();
    }

    /**
     * Get chat recipients allowed for student/parent users.
     *
     * @return array<int>
     */
    private function allowedChatRecipientIdsFor(User $user): array
    {
        $halaqaIds = collect();

        if ($user->role === 'student' && $user->student) {
            $halaqaIds = $user->student()
                ->with(['halaqas' => function ($query) {
                    $query->wherePivot('statut', 'active');
                }])
                ->first()
                ?->halaqas
                ?->pluck('id') ?? collect();
        }

        if ($user->role === 'parent') {
            $halaqaIds = Student::where('parent_id', $user->id)
                ->with(['halaqas' => function ($query) {
                    $query->wherePivot('statut', 'active');
                }])
                ->get()
                ->flatMap(function (Student $student) {
                    return $student->halaqas->pluck('id');
                });
        }

        $halaqaIds = $halaqaIds->filter()->unique()->values();

        if ($halaqaIds->isEmpty()) {
            return [];
        }

        return User::query()
            ->where('role', 'cheikh')
            ->whereHas('halaqas', function ($query) use ($halaqaIds) {
                $query->whereIn('halaqas.id', $halaqaIds);
            })
            ->pluck('id')
            ->all();
    }
}
