@extends('layouts.cheikh')

@section('title', 'Detail Halaqa')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold">Halaqa: {{ $halaqa->nom_halaqa }}</h2>
            <p class="mt-2 text-sm text-slate-200">{{ \Carbon\Carbon::now()->locale('ar')->translatedFormat('l d F Y') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 mt-4 sm:mt-0">
            <button
                type="button"
                id="open-students-modal"
                class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition"
            >
                Voir les etudiants
            </button>

            <a href="{{ route('cheikh.halaqas.historique', $halaqa->id) }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">
                Historique halaqa
            </a>

            <a href="{{ route('cheikh.halaqas') }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">
                Retour
            </a>
        </div>
    </header>

    <section class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <article class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs uppercase tracking-wide text-slate-500">Moyenne de la halaqa</p>
            <p class="mt-2 text-2xl font-bold text-slate-900">
                {{ is_null($moyenneHalaqa) ? '-' : number_format($moyenneHalaqa, 2) }}
            </p>
        </article>

        <article class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs uppercase tracking-wide text-slate-500">Presents aujourd'hui</p>
            <p class="mt-2 text-2xl font-bold text-emerald-600">{{ $presentAujourdhuiCount }}</p>
        </article>

        <article class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs uppercase tracking-wide text-slate-500">Retards aujourd'hui</p>
            <p class="mt-2 text-2xl font-bold text-amber-500">{{ $retardAujourdhuiCount }}</p>
        </article>

        <article class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs uppercase tracking-wide text-slate-500">Absents aujourd'hui</p>
            <p class="mt-2 text-2xl font-bold text-rose-600">{{ $absentAujourdhuiCount }}</p>
        </article>

        <article class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs uppercase tracking-wide text-slate-500">Meilleure note aujourd'hui</p>
            <p class="mt-2 text-2xl font-bold text-amber-600">
                {{ is_null($meilleureNoteAujourdhui) ? '-' : number_format($meilleureNoteAujourdhui, 2) }}
            </p>
        </article>
    </section>

    <section class="overflow-hidden rounded-xl bg-white shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Etudiant</th>
                        <th class="px-4 py-3 text-left">Nombre hifz</th>
                        <th class="px-4 py-3 text-left">Du Sourate</th>
                        <th class="px-4 py-3 text-left">Au Sourate</th>
                        <th class="px-4 py-3 text-left">Hizb</th>
                        <th class="px-4 py-3 text-left">Du aya</th>
                        <th class="px-4 py-3 text-left">Au aya</th>
                        <th class="px-4 py-3 text-left">Presence</th>
                        <th class="px-4 py-3 text-left">Note</th>
                        <th class="px-4 py-3 text-left">Remarque</th>
                           <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        @forelse ($student->evaluations as $evaluation)
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3">
                                    {{ $student->user->nom ?? '-' }} {{ $student->user->prenom ?? '' }}
                                </td>
                                <td class="px-4 py-3">{{ $student->nombre_hifz ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->du_sourate ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->au_sourate ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->hizb ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->du_aya ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->au_aya ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->presence ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->note ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->remarque ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-3 py-3 align-top">
                                    <div class="flex max-w-[220px] flex-wrap items-center gap-1.5">
                                        <button
                                            type="button"
                                            class="open-evaluation-modal whitespace-nowrap rounded-md bg-slate-900 px-2.5 py-1 text-[11px] font-medium leading-4 text-white hover:bg-slate-700"
                                            data-student-id="{{ $student->id }}"
                                            data-student-name="{{ trim(($student->user->nom ?? '-') . ' ' . ($student->user->prenom ?? '')) }}"
                                            data-evaluation-id="{{ $evaluation->id }}"
                                            data-du-sourate="{{ $evaluation->du_sourate }}"
                                            data-au-sourate="{{ $evaluation->au_sourate }}"
                                            data-hizb="{{ $evaluation->hizb }}"
                                            data-du-aya="{{ $evaluation->du_aya }}"
                                            data-au-aya="{{ $evaluation->au_aya }}"
                                            data-presence="{{ $evaluation->presence }}"
                                            data-note="{{ $evaluation->note }}"
                                            data-remarque="{{ $evaluation->remarque }}"
                                            data-created-at="{{ $evaluation->created_at->format('d/m/Y H:i') }}"
                                        >
                                            Modifier
                                        </button>

                                        <a
                                            href="{{ route('cheikh.students.historique', [$halaqa->id, $student->id]) }}"
                                            class="whitespace-nowrap rounded-md border border-slate-300 px-2.5 py-1 text-[11px] font-medium leading-4 text-slate-700 hover:bg-slate-50"
                                        >
                                            Historique
                                        </a>

                                        <form method="POST" action="{{ route('cheikh.evaluations.delete', $evaluation->id) }}" onsubmit="return showCustomConfirm(event, 'Supprimer cette evaluation ?', 'delete');">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="whitespace-nowrap rounded-md border border-red-300 px-2.5 py-1 text-[11px] font-medium leading-4 text-red-700 hover:bg-red-50"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3">
                                    {{ $student->user->nom ?? '-' }} {{ $student->user->prenom ?? '' }}
                                </td>
                                <td class="px-4 py-3">{{ $student->nombre_hifz ?? '-' }}</td>
                                <td colspan="10" class="px-4 py-3 text-center text-slate-500">Aucune évaluation</td>
                            </tr>

                            <tr class="border-t border-slate-100">
                                <td colspan="12" class="px-4 py-3">
                                    <button
                                        type="button"
                                        class="open-evaluation-modal whitespace-nowrap rounded-md border border-emerald-300 bg-emerald-50 px-2.5 py-1 text-[11px] font-medium leading-4 text-emerald-700 hover:bg-emerald-100"
                                        data-student-id="{{ $student->id }}"
                                        data-student-name="{{ trim(($student->user->nom ?? '-') . ' ' . ($student->user->prenom ?? '')) }}"
                                    >
                                        + Ajouter évaluation
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    @empty
                        <tr>
                            <td colspan="12" class="px-4 py-6 text-center text-slate-500">Aucun etudiant dans cette halaqa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div id="students-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-3xl rounded-xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Etudiants de la halaqa</h3>
                    <p class="text-sm text-slate-500">Nom, prenom et nombre de hifz</p>
                </div>

                <button type="button" id="close-students-modal" class="rounded-md px-3 py-1 text-slate-600 hover:bg-slate-100">X</button>
            </div>

            <div class="max-h-[70vh] overflow-x-auto overflow-y-auto p-6">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-slate-700">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Nom</th>
                            <th class="px-4 py-3">Prenom</th>
                            <th class="px-4 py-3">Nombre hifz</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3">{{ $student->id }}</td>
                                <td class="px-4 py-3">{{ $student->user->nom ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $student->user->prenom ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $student->nombre_hifz ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <button
                                        type="button"
                                        class="open-hifz-modal rounded-md border border-slate-300 px-3 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50"
                                        data-student-id="{{ $student->id }}"
                                        data-student-name="{{ trim(($student->user->nom ?? '-') . ' ' . ($student->user->prenom ?? '')) }}"
                                        data-halaqa-id="{{ $halaqa->id }}"
                                        data-hifz-value="{{ $student->nombre_hifz ?? 0 }}"
                                        data-action-url="{{ route('cheikh.students.hifz.update', [$halaqa->id, $student->id]) }}"
                                    >
                                        Modifier hifz
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-slate-500">Aucun etudiant dans cette halaqa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="hifz-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Modifier le nombre de hifz</h3>
                    <p id="hifz-student-name" class="text-sm text-slate-500">-</p>
                </div>

                <button type="button" id="close-hifz-modal" class="rounded-md px-3 py-1 text-slate-600 hover:bg-slate-100">X</button>
            </div>

            <form id="hifz-form" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="space-y-4 p-6">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Nombre hifz</label>
                        <input id="hifz-value" name="nombre_hifz" type="number" min="0" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" id="cancel-hifz-modal" class="rounded-md border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Annuler</button>
                        <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="evaluation-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
        <div class="w-full max-w-2xl rounded-xl bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 id="modal-title" class="text-lg font-bold">Nouvelle evaluation</h3>
                <button type="button" id="close-evaluation-modal" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-100">X</button>
            </div>

            <p class="mb-4 text-sm text-slate-600">Etudiant: <span id="evaluation-student-name" class="font-semibold text-slate-900">-</span></p>

               <p id="evaluation-date-info" class="mb-4 text-xs text-slate-500 hidden">Créée le: <span id="evaluation-date-text">-</span></p>

            <form
                id="evaluation-form"
                method="POST"
                action="{{ route('cheikh.evaluations.store') }}"
                data-store-url="{{ route('cheikh.evaluations.store') }}"
                data-update-url-template="{{ route('cheikh.evaluations.update', ['evaluation' => '__EVALUATION_ID__']) }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-2"
            >
                @csrf
                <input type="hidden" id="evaluation-method" name="_method" value="POST">
                <input type="hidden" name="halaqa_id" value="{{ $halaqa->id }}">
                <input type="hidden" id="evaluation-student-id" name="student_id" value="">

                <div>
                    <label class="mb-1 block text-sm font-medium">Du Sourate</label>
                    <select id="form-du-sourate" name="du_sourate" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" >
                        <option value="">-- Choisir une sourate --</option>
                        <option value="الفاتحة">1 - الفاتحة</option>
                        <option value="البقرة">2 - البقرة</option>
                        <option value="آل عمران">3 - آل عمران</option>
                        <option value="النساء">4 - النساء</option>
                        <option value="المائدة">5 - المائدة</option>
                        <option value="الأنعام">6 - الأنعام</option>
                        <option value="الأعراف">7 - الأعراف</option>
                        <option value="الأنفال">8 - الأنفال</option>
                        <option value="التوبة">9 - التوبة</option>
                        <option value="يونس">10 - يونس</option>
                        <option value="هود">11 - هود</option>
                        <option value="يوسف">12 - يوسف</option>
                        <option value="الرعد">13 - الرعد</option>
                        <option value="إبراهيم">14 - إبراهيم</option>
                        <option value="الحجر">15 - الحجر</option>
                        <option value="النحل">16 - النحل</option>
                        <option value="الإسراء">17 - الإسراء</option>
                        <option value="الكهف">18 - الكهف</option>
                        <option value="مريم">19 - مريم</option>
                        <option value="طه">20 - طه</option>
                        <option value="الأنبياء">21 - الأنبياء</option>
                        <option value="الحج">22 - الحج</option>
                        <option value="المؤمنون">23 - المؤمنون</option>
                        <option value="النور">24 - النور</option>
                        <option value="الفرقان">25 - الفرقان</option>
                        <option value="الشعراء">26 - الشعراء</option>
                        <option value="النمل">27 - النمل</option>
                        <option value="القصص">28 - القصص</option>
                        <option value="العنكبوت">29 - العنكبوت</option>
                        <option value="الروم">30 - الروم</option>
                        <option value="لقمان">31 - لقمان</option>
                        <option value="السجدة">32 - السجدة</option>
                        <option value="الأحزاب">33 - الأحزاب</option>
                        <option value="سبأ">34 - سبأ</option>
                        <option value="فاطر">35 - فاطر</option>
                        <option value="يس">36 - يس</option>
                        <option value="الصافات">37 - الصافات</option>
                        <option value="ص">38 - ص</option>
                        <option value="الزمر">39 - الزمر</option>
                        <option value="غافر">40 - غافر</option>
                        <option value="فصلت">41 - فصلت</option>
                        <option value="الشورى">42 - الشورى</option>
                        <option value="الزخرف">43 - الزخرف</option>
                        <option value="الدخان">44 - الدخان</option>
                        <option value="الجاثية">45 - الجاثية</option>
                        <option value="الأحقاف">46 - الأحقاف</option>
                        <option value="محمد">47 - محمد</option>
                        <option value="الفتح">48 - الفتح</option>
                        <option value="الحجرات">49 - الحجرات</option>
                        <option value="ق">50 - ق</option>
                        <option value="الذاريات">51 - الذاريات</option>
                        <option value="الطور">52 - الطور</option>
                        <option value="النجم">53 - النجم</option>
                        <option value="القمر">54 - القمر</option>
                        <option value="الرحمن">55 - الرحمن</option>
                        <option value="الواقعة">56 - الواقعة</option>
                        <option value="الحديد">57 - الحديد</option>
                        <option value="المجادلة">58 - المجادلة</option>
                        <option value="الحشر">59 - الحشر</option>
                        <option value="الممتحنة">60 - الممتحنة</option>
                        <option value="الصف">61 - الصف</option>
                        <option value="الجمعة">62 - الجمعة</option>
                        <option value="المنافقون">63 - المنافقون</option>
                        <option value="التغابن">64 - التغابن</option>
                        <option value="الطلاق">65 - الطلاق</option>
                        <option value="التحريم">66 - التحريم</option>
                        <option value="الملك">67 - الملك</option>
                        <option value="القلم">68 - القلم</option>
                        <option value="الحاقة">69 - الحاقة</option>
                        <option value="المعارج">70 - المعارج</option>
                        <option value="نوح">71 - نوح</option>
                        <option value="الجن">72 - الجن</option>
                        <option value="المزمل">73 - المزمل</option>
                        <option value="المدثر">74 - المدثر</option>
                        <option value="القيامة">75 - القيامة</option>
                        <option value="الإنسان">76 - الإنسان</option>
                        <option value="المرسلات">77 - المرسلات</option>
                        <option value="النبأ">78 - النبأ</option>
                        <option value="النازعات">79 - النازعات</option>
                        <option value="عبس">80 - عبس</option>
                        <option value="التكوير">81 - التكوير</option>
                        <option value="الانفطار">82 - الانفطار</option>
                        <option value="المطففين">83 - المطففين</option>
                        <option value="الانشقاق">84 - الانشقاق</option>
                        <option value="البروج">85 - البروج</option>
                        <option value="الطارق">86 - الطارق</option>
                        <option value="الأعلى">87 - الأعلى</option>
                        <option value="الغاشية">88 - الغاشية</option>
                        <option value="الفجر">89 - الفجر</option>
                        <option value="البلد">90 - البلد</option>
                        <option value="الشمس">91 - الشمس</option>
                        <option value="الليل">92 - الليل</option>
                        <option value="الضحى">93 - الضحى</option>
                        <option value="الشرح">94 - الشرح</option>
                        <option value="التين">95 - التين</option>
                        <option value="العلق">96 - العلق</option>
                        <option value="القدر">97 - القدر</option>
                        <option value="البينة">98 - البينة</option>
                        <option value="الزلزلة">99 - الزلزلة</option>
                        <option value="العاديات">100 - العاديات</option>
                        <option value="القارعة">101 - القارعة</option>
                        <option value="التكاثر">102 - التكاثر</option>
                        <option value="العصر">103 - العصر</option>
                        <option value="الهمزة">104 - الهمزة</option>
                        <option value="الفيل">105 - الفيل</option>
                        <option value="قريش">106 - قريش</option>
                        <option value="الماعون">107 - الماعون</option>
                        <option value="الكوثر">108 - الكوثر</option>
                        <option value="الكافرون">109 - الكافرون</option>
                        <option value="النصر">110 - النصر</option>
                        <option value="المسد">111 - المسد</option>
                        <option value="الإخلاص">112 - الإخلاص</option>
                        <option value="الفلق">113 - الفلق</option>
                        <option value="الناس">114 - الناس</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Au Sourate</label>
                    <select id="form-au-sourate" name="au_sourate" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" >
                        <option value="">-- Choisir une sourate --</option>
                        <option value="الفاتحة">1 - الفاتحة</option>
                        <option value="البقرة">2 - البقرة</option>
                        <option value="آل عمران">3 - آل عمران</option>
                        <option value="النساء">4 - النساء</option>
                        <option value="المائدة">5 - المائدة</option>
                        <option value="الأنعام">6 - الأنعام</option>
                        <option value="الأعراف">7 - الأعراف</option>
                        <option value="الأنفال">8 - الأنفال</option>
                        <option value="التوبة">9 - التوبة</option>
                        <option value="يونس">10 - يونس</option>
                        <option value="هود">11 - هود</option>
                        <option value="يوسف">12 - يوسف</option>
                        <option value="الرعد">13 - الرعد</option>
                        <option value="إبراهيم">14 - إبراهيم</option>
                        <option value="الحجر">15 - الحجر</option>
                        <option value="النحل">16 - النحل</option>
                        <option value="الإسراء">17 - الإسراء</option>
                        <option value="الكهف">18 - الكهف</option>
                        <option value="مريم">19 - مريم</option>
                        <option value="طه">20 - طه</option>
                        <option value="الأنبياء">21 - الأنبياء</option>
                        <option value="الحج">22 - الحج</option>
                        <option value="المؤمنون">23 - المؤمنون</option>
                        <option value="النور">24 - النور</option>
                        <option value="الفرقان">25 - الفرقان</option>
                        <option value="الشعراء">26 - الشعراء</option>
                        <option value="النمل">27 - النمل</option>
                        <option value="القصص">28 - القصص</option>
                        <option value="العنكبوت">29 - العنكبوت</option>
                        <option value="الروم">30 - الروم</option>
                        <option value="لقمان">31 - لقمان</option>
                        <option value="السجدة">32 - السجدة</option>
                        <option value="الأحزاب">33 - الأحزاب</option>
                        <option value="سبأ">34 - سبأ</option>
                        <option value="فاطر">35 - فاطر</option>
                        <option value="يس">36 - يس</option>
                        <option value="الصافات">37 - الصافات</option>
                        <option value="ص">38 - ص</option>
                        <option value="الزمر">39 - الزمر</option>
                        <option value="غافر">40 - غافر</option>
                        <option value="فصلت">41 - فصلت</option>
                        <option value="الشورى">42 - الشورى</option>
                        <option value="الزخرف">43 - الزخرف</option>
                        <option value="الدخان">44 - الدخان</option>
                        <option value="الجاثية">45 - الجاثية</option>
                        <option value="الأحقاف">46 - الأحقاف</option>
                        <option value="محمد">47 - محمد</option>
                        <option value="الفتح">48 - الفتح</option>
                        <option value="الحجرات">49 - الحجرات</option>
                        <option value="ق">50 - ق</option>
                        <option value="الذاريات">51 - الذاريات</option>
                        <option value="الطور">52 - الطور</option>
                        <option value="النجم">53 - النجم</option>
                        <option value="القمر">54 - القمر</option>
                        <option value="الرحمن">55 - الرحمن</option>
                        <option value="الواقعة">56 - الواقعة</option>
                        <option value="الحديد">57 - الحديد</option>
                        <option value="المجادلة">58 - المجادلة</option>
                        <option value="الحشر">59 - الحشر</option>
                        <option value="الممتحنة">60 - الممتحنة</option>
                        <option value="الصف">61 - الصف</option>
                        <option value="الجمعة">62 - الجمعة</option>
                        <option value="المنافقون">63 - المنافقون</option>
                        <option value="التغابن">64 - التغابن</option>
                        <option value="الطلاق">65 - الطلاق</option>
                        <option value="التحريم">66 - التحريم</option>
                        <option value="الملك">67 - الملك</option>
                        <option value="القلم">68 - القلم</option>
                        <option value="الحاقة">69 - الحاقة</option>
                        <option value="المعارج">70 - المعارج</option>
                        <option value="نوح">71 - نوح</option>
                        <option value="الجن">72 - الجن</option>
                        <option value="المزمل">73 - المزمل</option>
                        <option value="المدثر">74 - المدثر</option>
                        <option value="القيامة">75 - القيامة</option>
                        <option value="الإنسان">76 - الإنسان</option>
                        <option value="المرسلات">77 - المرسلات</option>
                        <option value="النبأ">78 - النبأ</option>
                        <option value="النازعات">79 - النازعات</option>
                        <option value="عبس">80 - عبس</option>
                        <option value="التكوير">81 - التكوير</option>
                        <option value="الانفطار">82 - الانفطار</option>
                        <option value="المطففين">83 - المطففين</option>
                        <option value="الانشقاق">84 - الانشقاق</option>
                        <option value="البروج">85 - البروج</option>
                        <option value="الطارق">86 - الطارق</option>
                        <option value="الأعلى">87 - الأعلى</option>
                        <option value="الغاشية">88 - الغاشية</option>
                        <option value="الفجر">89 - الفجر</option>
                        <option value="البلد">90 - البلد</option>
                        <option value="الشمس">91 - الشمس</option>
                        <option value="الليل">92 - الليل</option>
                        <option value="الضحى">93 - الضحى</option>
                        <option value="الشرح">94 - الشرح</option>
                        <option value="التين">95 - التين</option>
                        <option value="العلق">96 - العلق</option>
                        <option value="القدر">97 - القدر</option>
                        <option value="البينة">98 - البينة</option>
                        <option value="الزلزلة">99 - الزلزلة</option>
                        <option value="العاديات">100 - العاديات</option>
                        <option value="القارعة">101 - القارعة</option>
                        <option value="التكاثر">102 - التكاثر</option>
                        <option value="العصر">103 - العصر</option>
                        <option value="الهمزة">104 - الهمزة</option>
                        <option value="الفيل">105 - الفيل</option>
                        <option value="قريش">106 - قريش</option>
                        <option value="الماعون">107 - الماعون</option>
                        <option value="الكوثر">108 - الكوثر</option>
                        <option value="الكافرون">109 - الكافرون</option>
                        <option value="النصر">110 - النصر</option>
                        <option value="المسد">111 - المسد</option>
                        <option value="الإخلاص">112 - الإخلاص</option>
                        <option value="الفلق">113 - الفلق</option>
                        <option value="الناس">114 - الناس</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Hizb</label>
                    <select id="form-hizb" name="hizb" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" >
                        <option value="">-- Choisir hizb --</option>
                        @for ($i = 1; $i <= 60; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Presence</label>
                    <select id="form-presence" name="presence" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                        <option value="present">present</option>
                        <option value="absent">absent</option>
                        <option value="retard">retard</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Du aya</label>
                    <select id="form-du-aya" name="du_aya" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" >
                        <option value="">-- Du aya --</option>
                        @for ($i = 1; $i <= 286; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Au aya</label>
                    <select id="form-au-aya" name="au_aya" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" >
                        <option value="">-- Au aya --</option>
                        @for ($i = 1; $i <= 286; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Note /20</label>
                    <input id="form-note" name="note" type="number" min="0" max="20" step="0.01" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" >
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium">Remarque</label>
                    <textarea id="form-remarque" name="remarque" rows="3" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm resize-none"></textarea>
                </div>

                <div class="md:col-span-2 flex justify-end gap-2">
                    <button type="button" id="cancel-evaluation-modal" class="rounded-md border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Annuler</button>
                    <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const studentsModal = document.getElementById('students-modal');
        const openStudentsModalBtn = document.getElementById('open-students-modal');
        const closeStudentsModalBtn = document.getElementById('close-students-modal');
        const hifzModal = document.getElementById('hifz-modal');
        const hifzForm = document.getElementById('hifz-form');
        const hifzValue = document.getElementById('hifz-value');
        const hifzStudentName = document.getElementById('hifz-student-name');
        const openHifzButtons = document.querySelectorAll('.open-hifz-modal');
        const closeHifzModalBtn = document.getElementById('close-hifz-modal');
        const cancelHifzModalBtn = document.getElementById('cancel-hifz-modal');

        function openStudentsModal() {
            studentsModal.classList.remove('hidden');
            studentsModal.classList.add('flex');
        }

        function closeStudentsModal() {
            studentsModal.classList.add('hidden');
            studentsModal.classList.remove('flex');
        }

        openStudentsModalBtn?.addEventListener('click', openStudentsModal);
        closeStudentsModalBtn?.addEventListener('click', closeStudentsModal);

        studentsModal?.addEventListener('click', (event) => {
            if (event.target === studentsModal) {
                closeStudentsModal();
            }
        });

        function openHifzModal(button) {
            hifzForm.action = button.dataset.actionUrl;
            hifzStudentName.textContent = button.dataset.studentName || '-';
            hifzValue.value = button.dataset.hifzValue || 0;
            hifzModal.classList.remove('hidden');
            hifzModal.classList.add('flex');
        }

        function closeHifzModal() {
            hifzModal.classList.add('hidden');
            hifzModal.classList.remove('flex');
        }

        openHifzButtons.forEach((button) => {
            button.addEventListener('click', () => openHifzModal(button));
        });

        closeHifzModalBtn?.addEventListener('click', closeHifzModal);
        cancelHifzModalBtn?.addEventListener('click', closeHifzModal);

        hifzModal?.addEventListener('click', (event) => {
            if (event.target === hifzModal) {
                closeHifzModal();
            }
        });

        const modal = document.getElementById('evaluation-modal');
        const modalTitle = document.getElementById('modal-title');
        const evaluationForm = document.getElementById('evaluation-form');
        const evaluationMethod = document.getElementById('evaluation-method');
        const studentIdInput = document.getElementById('evaluation-student-id');
        const studentNameText = document.getElementById('evaluation-student-name');
        
        // Form fields
        const formDuSourate = document.getElementById('form-du-sourate');
        const formAuSourate = document.getElementById('form-au-sourate');
        const formHizb = document.getElementById('form-hizb');
        const formDuAya = document.getElementById('form-du-aya');
        const formAuAya = document.getElementById('form-au-aya');
        const formPresence = document.getElementById('form-presence');
        const formNote = document.getElementById('form-note');
        const formRemarque = document.getElementById('form-remarque');
        
        const openButtons = document.querySelectorAll('.open-evaluation-modal');
        const dateInfo = document.getElementById('evaluation-date-info');
        const dateText = document.getElementById('evaluation-date-text');
        const closeBtn = document.getElementById('close-evaluation-modal');
        const cancelBtn = document.getElementById('cancel-evaluation-modal');

        function resetForm() {
            formDuSourate.value = '';
            formAuSourate.value = '';
            formHizb.value = '';
            formDuAya.value = '';
            formAuAya.value = '';
            formPresence.value = 'present';
            formNote.value = '';
            formRemarque.value = '';
        }

        function openModal(button) {
            const studentId = button.dataset.studentId;
            const studentName = button.dataset.studentName;
            const evaluationId = button.dataset.evaluationId;

            studentIdInput.value = studentId;
            studentNameText.textContent = studentName;

            // Verifier si on est en mode edition ou creation
            if (evaluationId) {
                // Mode modification
                modalTitle.textContent = 'Modifier evaluation';
                evaluationForm.action = evaluationForm.dataset.updateUrlTemplate.replace('__EVALUATION_ID__', evaluationId);
                evaluationMethod.value = 'PUT';
                formDuSourate.value = button.dataset.duSourate || '';
                formAuSourate.value = button.dataset.auSourate || '';
                formHizb.value = button.dataset.hizb || '';
                formDuAya.value = button.dataset.duAya || '';
                formAuAya.value = button.dataset.auAya || '';
                formPresence.value = button.dataset.presence || 'present';
                formNote.value = button.dataset.note || '';
                formRemarque.value = button.dataset.remarque || '';
                       dateText.textContent = button.dataset.createdAt || '-';
                       dateInfo.classList.remove('hidden');
            } else {
                // Mode creation
                modalTitle.textContent = 'Nouvelle evaluation';
                evaluationForm.action = evaluationForm.dataset.storeUrl;
                evaluationMethod.value = 'POST';
                       dateInfo.classList.add('hidden');
                resetForm();
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        openButtons.forEach((button) => {
            button.addEventListener('click', () => {
                openModal(button);
            });
        });

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>
@endsection
