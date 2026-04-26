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
        if ($user->role === 'student' && $user->student) {
            return $user->student->halaqas()
                ->wherePivot('statut', 'active')
                ->pluck('cheikh_id')
                ->unique()
                ->toArray();
        }

        if ($user->role === 'parent') {
            return \App\Models\Halaqa::whereHas('students', function ($query) use ($user) {
                $query->where('parent_id', $user->id)
                      ->where('memberships.statut', 'active');
            })
            ->pluck('cheikh_id')
            ->unique()
            ->toArray();
        }

        return [];
    }
}
