@php
    $layout = in_array(auth()->user()?->role, ['admin', 'cheikh'], true) ? 'layouts.admin' : 'layouts.app';
@endphp

@extends($layout)

@section('content')
@php
    $meetingName = $meeting->meeting_name ?? $meeting->name ?? 'Séance en direct';
    $halaqaName = $meeting->halaqa->nom_halaqa ?? 'Halaqa';
@endphp

<div class="h-[calc(100dvh-4.5rem)] overflow-hidden bg-slate-50">
    <div class="grid h-full w-full grid-rows-[auto_1fr] gap-3">
        <header class=" rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#a48834] mb-1">SÉANCE EN DIRECT</p>
                <h2 class="text-2xl font-bold text-white leading-tight">{{ $meetingName }}</h2>
                <p class="mt-1 text-sm text-slate-200 opacity-90">{{ $halaqaName }} – Accès sécurisé par token temporaire</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition"
                    id="toggle-fullscreen"
                >
                    Plein écran
                </button>

                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition"
                    id="copy-secure-link"
                    data-url="{{ route('meetings.join', $meeting) }}"
                >
                    Copier le lien
                </button>

                <a href="{{ route('meetings.index') }}" class="inline-flex items-center justify-center rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">
                    Retour
                </a>
            </div>
        </header>

        @if($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-100 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                    </div>
                    <div class="ml-3">
                        <ul class="text-sm font-medium text-red-800 list-disc pl-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content (Video + sidebar) -->
        <div class="flex h-full min-h-0 gap-3 flex-col lg:flex-row">
            <!-- Video Room Container -->
            <div id="daily-room-wrapper" class="relative min-h-0 h-full w-full flex-1 overflow-hidden rounded-2xl border-2 border-slate-500 shadow-sm">
                <div id="daily-room" class="h-full w-full"></div>
            </div>

            @if($isOwner ?? false)
            <!-- Students Panel -->
            <div class="flex h-full w-full lg:w-80 flex-col overflow-hidden rounded-2xl border-2 border-slate-500 bg-white shadow-sm shrink-0">
                <div class="border-b border-slate-200 bg-slate-50 px-4 py-3 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-800">Étudiants</h3>
                        <p class="text-xs text-slate-500">Wirechat</p>
                    </div>
                    <button type="button" onclick="document.querySelectorAll('input[name=\'student_ids[]\']').forEach(cb => cb.checked = true)" class="text-xs text-sky-600 hover:text-sky-700 font-medium">Tout sélectionner</button>
                </div>
                
                <form action="{{ route('meetings.send_link', $meeting) }}" method="POST" class="flex flex-col min-h-0 h-full overflow-hidden bg-white">
                    @csrf
                    <div class="flex-1 overflow-y-auto p-3 space-y-2">
                        @forelse($students as $student)
                            <label class="flex items-center space-x-3 rounded-xl border border-slate-100 p-3 hover:bg-slate-50 cursor-pointer transition shadow-sm">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                <span class="text-sm font-medium text-slate-700 flex-1 truncate">
                                    {{ $student->user->nom ?? '' }} {{ $student->user->prenom ?? '' }}
                                </span>
                            </label>
                        @empty
                            <p class="text-sm text-slate-500 text-center py-4">Aucun étudiant n'est inscrit dans cette halaqa.</p>
                        @endforelse
                    </div>

                    @if($students->isNotEmpty())
                    <div class="border-t border-slate-200 bg-slate-50 p-3">
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700 shadow-sm">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            Inviter la sélection
                        </button>
                    </div>
                    @endif
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Config securisee stockee en JSON (jamais en URL) -->
<script type="application/json" id="daily-room-config">
{
    "token": "{{ $token ?? '' }}",
    "roomUrl": "{{ $roomUrl ?? '' }}"
}
</script>

<script src="https://unpkg.com/@daily-co/daily-js"></script>

<script>
(function () {
    let dailyToken = null;
    let roomUrl = null;

    try {
        const config = JSON.parse(document.getElementById('daily-room-config').textContent);
        dailyToken = config.token;
        roomUrl = config.roomUrl;
    } catch (error) {
        console.error('Configuration parse error:', error);
    }

    if (!dailyToken || !roomUrl) {
        const container = document.getElementById('daily-room');
        if (container) {
            container.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #d32f2f; font-size: 16px; padding: 2rem; text-align: center;"><p>Impossible de charger la réunion. Configuration manquante.</p></div>';
        }
        return;
    }

    // Créer l'iframe Daily
    const callFrame = window.DailyIframe.createFrame(
        document.getElementById('daily-room'),
        {
            showLeaveButton: true,
            iframeStyle: {
                position: 'static',
                width: '100%',
                height: '100%',
                border: 'none',
            },
        }
    );

    // Gestion des erreurs
    callFrame.on('error', function (error) {
        console.error('Daily error:', error);
        const container = document.getElementById('daily-room');
        if (container) {
            container.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #d32f2f; font-size: 16px; padding: 2rem; text-align: center;"><p>Erreur de connexion à la réunion.</p></div>';
        }
    });

    // Rejoindre la room avec le token
    callFrame.join({
        url: roomUrl,
        token: dailyToken,
    }).catch(function (error) {
        console.error('Join error:', error);
        const container = document.getElementById('daily-room');
        if (container) {
            container.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #d32f2f; font-size: 16px; padding: 2rem; text-align: center;"><p>Erreur lors de la connexion. Vérifiez vos droits d\'accès.</p></div>';
        }
    });
})();
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const copyButton = document.getElementById('copy-secure-link');
    const feedback = document.getElementById('copy-feedback');
    const fullscreenButton = document.getElementById('toggle-fullscreen');
    const videoWrapper = document.getElementById('daily-room-wrapper');

    function isFullscreenActive() {
        return document.fullscreenElement || document.webkitFullscreenElement;
    }

    function updateFullscreenButtonLabel() {
        if (!fullscreenButton) {
            return;
        }

        fullscreenButton.textContent = isFullscreenActive() ? 'Quitter plein ecran' : 'Plein ecran';
    }

    if (fullscreenButton && videoWrapper) {
        fullscreenButton.addEventListener('click', async function () {
            try {
                if (!isFullscreenActive()) {
                    if (videoWrapper.requestFullscreen) {
                        await videoWrapper.requestFullscreen();
                    } else if (videoWrapper.webkitRequestFullscreen) {
                        videoWrapper.webkitRequestFullscreen();
                    }
                } else if (document.exitFullscreen) {
                    await document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                }
            } catch (error) {
                console.error('Fullscreen error:', error);
            }

            updateFullscreenButtonLabel();
        });

        document.addEventListener('fullscreenchange', updateFullscreenButtonLabel);
        document.addEventListener('webkitfullscreenchange', updateFullscreenButtonLabel);
    }

    if (!copyButton) {
        return;
    }

    copyButton.addEventListener('click', async function () {
        const url = copyButton.getAttribute('data-url') || '';

        if (!url) {
            return;
        }

        try {
            await navigator.clipboard.writeText(url);

            if (feedback) {
                feedback.hidden = false;
            }

            const originalText = copyButton.textContent;
            copyButton.textContent = 'Copié ✓';

            window.setTimeout(function () {
                copyButton.textContent = originalText;
                if (feedback) {
                    feedback.hidden = true;
                }
            }, 2000);
        } catch (error) {
            const temporaryInput = document.createElement('input');
            temporaryInput.value = url;
            document.body.appendChild(temporaryInput);
            temporaryInput.select();
            document.execCommand('copy');
            document.body.removeChild(temporaryInput);

            if (feedback) {
                feedback.hidden = false;
            }

            const originalText = copyButton.textContent;
            copyButton.textContent = 'Copié ✓';
            window.setTimeout(function () {
                copyButton.textContent = originalText;
                if (feedback) {
                    feedback.hidden = true;
                }
            }, 2000);
        }
    });
});
</script>

@endsection