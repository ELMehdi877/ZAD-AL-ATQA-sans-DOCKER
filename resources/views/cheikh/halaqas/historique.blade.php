@extends('layouts.cheikh')

@section('title', 'Historique de la halaqa')

@section('content')
    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Historique de la halaqa</h2>
            <p class="mt-2 text-sm text-slate-200">Halaqa: {{ $halaqa->nom_halaqa }}</p>
        </div>
        <a href="{{ route('cheikh.halaqas.show', $halaqa->id) }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Retour</a>
    </header>

    <section class="mb-6 rounded-xl bg-white p-4 shadow">
        <form method="GET" action="{{ route('cheikh.halaqas.search', $halaqa->id) }}" class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
            <div class="w-full sm:max-w-xs">
                <label for="date" class="mb-1 block text-sm font-medium text-slate-700">Filtrer par date</label>
                <input
                    id="date"
                    type="date"
                    name="date"
                    value="{{ old('date', $selectedDate ?? '') }}"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >
                @error('date')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full sm:max-w-xs">
                <label for="nom" class="mb-1 block text-sm font-medium text-slate-700">Nom</label>
                <input
                    id="nom"
                    type="text"
                    name="nom"
                    value="{{ old('nom', $selectedNom ?? '') }}"
                    placeholder="Ex: Ahmed"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >
                @error('nom')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full sm:max-w-xs">
                <label for="prenom" class="mb-1 block text-sm font-medium text-slate-700">Prenom</label>
                <input
                    id="prenom"
                    type="text"
                    name="prenom"
                    value="{{ old('prenom', $selectedPrenom ?? '') }}"
                    placeholder="Ex: Mohamed"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2"
                >
                @error('prenom')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full sm:max-w-xs">
                <label class="mb-1 block text-sm font-medium text-slate-700">Presence</label>
                <select id="form-presence" name="presence" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="" @selected(old('presence', $selectedPresence ?? '') === '')>-- Tous --</option>
                    <option value="present" @selected(old('presence', $selectedPresence ?? '') === 'present')>present</option>
                    <option value="absent" @selected(old('presence', $selectedPresence ?? '') === 'absent')>absent</option>
                    <option value="retard" @selected(old('presence', $selectedPresence ?? '') === 'retard')>retard</option>
                </select>
            </div>

            <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
                Rechercher
            </button>

            <a href="{{ route('cheikh.halaqas.historique', $halaqa->id) }}" class="rounded-md border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">
                Tous les jours
            </a>
        </form>
    </section>

    @forelse ($evaluationsByDay as $date => $evaluations)
        <section class="mb-6 overflow-hidden rounded-xl bg-white shadow">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-lg font-semibold text-slate-900">
                    {{ \Carbon\Carbon::parse($date)->locale('ar')->translatedFormat('l d F Y') }}
                </h3>
                <p class="text-sm text-slate-500">{{ $evaluations->count() }} evaluation(s)</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Etudiant</th>
                            <th class="px-4 py-3 text-left">Du Sourate</th>
                            <th class="px-4 py-3 text-left">Au Sourate</th>
                            <th class="px-4 py-3 text-left">Hizb</th>
                            <th class="px-4 py-3 text-left">Du aya</th>
                            <th class="px-4 py-3 text-left">Au aya</th>
                            <th class="px-4 py-3 text-left">Presence</th>
                            <th class="px-4 py-3 text-left">Note</th>
                            <th class="px-4 py-3 text-left">Remarque</th>
                            <th class="px-4 py-3 text-left">Heure</th>
                            <th class="px-4 py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($evaluations as $evaluation)
                            <tr class="border-t border-slate-100">
                                <td class="px-4 py-3">
                                    {{ $evaluation->student->user->nom ?? '-' }} {{ $evaluation->student->user->prenom ?? '' }}
                                </td>
                                <td class="px-4 py-3">{{ $evaluation->du_sourate ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->au_sourate ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->hizb ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->du_aya ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->au_aya ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->presence ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->note ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->remarque ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $evaluation->created_at->format('H:i') }}</td>
                                <td class="px-4 py-3">
                                    <button
                                        type="button"
                                        class="open-edit-evaluation-modal rounded-md bg-slate-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-slate-700"
                                        data-evaluation-id="{{ $evaluation->id }}"
                                        data-student-name="{{ $evaluation->student->user->nom ?? '-' }} {{ $evaluation->student->user->prenom ?? '' }}"
                                        data-du-sourate="{{ $evaluation->du_sourate }}"
                                        data-au-sourate="{{ $evaluation->au_sourate }}"
                                        data-hizb="{{ $evaluation->hizb }}"
                                        data-du-aya="{{ $evaluation->du_aya }}"
                                        data-au-aya="{{ $evaluation->au_aya }}"
                                        data-presence="{{ $evaluation->presence }}"
                                        data-note="{{ $evaluation->note }}"
                                        data-remarque="{{ $evaluation->remarque }}"
                                    >
                                        Modifier
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @empty
        <section class="rounded-xl bg-white px-6 py-10 text-center text-slate-500 shadow">
            Aucune evaluation trouvee pour cette halaqa.
        </section>
    @endforelse

    <div id="edit-evaluation-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
        <div class="w-full max-w-2xl rounded-xl bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 id="edit-modal-title" class="text-lg font-bold">Modifier evaluation</h3>
                <button type="button" id="close-edit-evaluation-modal" class="rounded px-2 py-1 text-slate-600 hover:bg-slate-100">X</button>
            </div>

            <p class="mb-4 text-sm text-slate-600">Etudiant: <span id="edit-evaluation-student-name" class="font-semibold text-slate-900">-</span></p>

            <form
                id="edit-evaluation-form"
                method="POST"
                action=""
                data-update-url-template="{{ route('cheikh.evaluations.update', ['evaluation' => '__EVALUATION_ID__']) }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-2"
            >
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1 block text-sm font-medium">Du Sourate</label>
                    <select id="edit-form-du-sourate" name="du_sourate" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
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
                    <select id="edit-form-au-sourate" name="au_sourate" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
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
                    <select id="edit-form-hizb" name="hizb" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                        <option value="">-- Choisir hizb --</option>
                        @for ($i = 1; $i <= 60; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Presence</label>
                    <select id="edit-form-presence" name="presence" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                        <option value="present">present</option>
                        <option value="absent">absent</option>
                        <option value="retard">retard</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Du aya</label>
                    <select id="edit-form-du-aya" name="du_aya" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                        <option value="">-- Du aya --</option>
                        @for ($i = 1; $i <= 286; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Au aya</label>
                    <select id="edit-form-au-aya" name="au_aya" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                        <option value="">-- Au aya --</option>
                        @for ($i = 1; $i <= 286; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Note /20</label>
                    <input id="edit-form-note" name="note" type="number" min="0" max="20" step="0.01" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm" required>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium">Remarque</label>
                    <textarea id="edit-form-remarque" name="remarque" rows="3" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"></textarea>
                </div>

                <div class="md:col-span-2 flex justify-end gap-2">
                    <button type="button" id="cancel-edit-evaluation-modal" class="rounded-md border border-slate-300 px-4 py-2 text-sm hover:bg-slate-50">Annuler</button>
                    <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const editModal = document.getElementById('edit-evaluation-modal');
        const editForm = document.getElementById('edit-evaluation-form');
        const editStudentName = document.getElementById('edit-evaluation-student-name');
        const editTitle = document.getElementById('edit-modal-title');
        const closeEditBtn = document.getElementById('close-edit-evaluation-modal');
        const cancelEditBtn = document.getElementById('cancel-edit-evaluation-modal');

        const editDuSourate = document.getElementById('edit-form-du-sourate');
        const editAuSourate = document.getElementById('edit-form-au-sourate');
        const editHizb = document.getElementById('edit-form-hizb');
        const editPresence = document.getElementById('edit-form-presence');
        const editDuAya = document.getElementById('edit-form-du-aya');
        const editAuAya = document.getElementById('edit-form-au-aya');
        const editNote = document.getElementById('edit-form-note');
        const editRemarque = document.getElementById('edit-form-remarque');

        function openEditModal(button) {
            const evaluationId = button.dataset.evaluationId;

            editForm.action = editForm.dataset.updateUrlTemplate.replace('__EVALUATION_ID__', evaluationId);
            editStudentName.textContent = button.dataset.studentName || '-';
            editTitle.textContent = 'Modifier evaluation';

            editDuSourate.value = button.dataset.duSourate || '';
            editAuSourate.value = button.dataset.auSourate || '';
            editHizb.value = button.dataset.hizb || '';
            editDuAya.value = button.dataset.duAya || '';
            editAuAya.value = button.dataset.auAya || '';
            editPresence.value = button.dataset.presence || 'present';
            editNote.value = button.dataset.note || '';
            editRemarque.value = button.dataset.remarque || '';

            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
            editModal.classList.remove('flex');
        }

        document.querySelectorAll('.open-edit-evaluation-modal').forEach((button) => {
            button.addEventListener('click', () => openEditModal(button));
        });

        closeEditBtn?.addEventListener('click', closeEditModal);
        cancelEditBtn?.addEventListener('click', closeEditModal);

        editModal?.addEventListener('click', (event) => {
            if (event.target === editModal) {
                closeEditModal();
            }
        });
    </script>
@endsection