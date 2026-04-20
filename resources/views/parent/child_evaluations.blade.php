@extends('layouts.user-navbar')

@section('title', 'Evaluations de l\'enfant')

@section('content')


    <header class="mb-6 rounded-2xl bg-gradient-to-r from-[#04371f] to-[#04371f]/80 p-6 text-white shadow-xl flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Evaluations de {{ $student->user->nom ?? '-' }} {{ $student->user->prenom ?? '' }}</h2>
            <p class="mt-2 text-sm text-slate-200">Halaqa: {{ $halaqa->nom_halaqa ?? '-' }}</p>
        </div>
        <a href="{{ route('parent.children.halaqas', $student->id) }}" class="rounded-lg bg-white/20 px-4 py-2 text-sm font-medium text-white hover:bg-white/30 backdrop-blur-sm transition">Retour</a>
    </header>

        <div class="mb-6 rounded-lg bg-white p-4 shadow">
            <form method="GET" action="{{ route('parent.children.evaluations.search', [$student->id, $halaqa->id]) }}" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div class="w-full sm:max-w-md">
                    <label for="du-sourate" class="mb-1 block text-sm font-medium text-slate-700">Du Sourate</label>
                    <select id="du-sourate" name="du_sourate" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                        <option value="">-- Choisir une sourate --</option>
                        <option value="الفاتحة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الفاتحة')>1 - الفاتحة</option>
                        <option value="البقرة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'البقرة')>2 - البقرة</option>
                        <option value="آل عمران" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'آل عمران')>3 - آل عمران</option>
                        <option value="النساء" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'النساء')>4 - النساء</option>
                        <option value="المائدة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'المائدة')>5 - المائدة</option>
                        <option value="الأنعام" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الأنعام')>6 - الأنعام</option>
                        <option value="الأعراف" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الأعراف')>7 - الأعراف</option>
                        <option value="الأنفال" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الأنفال')>8 - الأنفال</option>
                        <option value="التوبة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'التوبة')>9 - التوبة</option>
                        <option value="يونس" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'يونس')>10 - يونس</option>
                        <option value="هود" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'هود')>11 - هود</option>
                        <option value="يوسف" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'يوسف')>12 - يوسف</option>
                        <option value="الرعد" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الرعد')>13 - الرعد</option>
                        <option value="إبراهيم" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'إبراهيم')>14 - إبراهيم</option>
                        <option value="الحجر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الحجر')>15 - الحجر</option>
                        <option value="النحل" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'النحل')>16 - النحل</option>
                        <option value="الإسراء" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الإسراء')>17 - الإسراء</option>
                        <option value="الكهف" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الكهف')>18 - الكهف</option>
                        <option value="مريم" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'مريم')>19 - مريم</option>
                        <option value="طه" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'طه')>20 - طه</option>
                        <option value="الأنبياء" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الأنبياء')>21 - الأنبياء</option>
                        <option value="الحج" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الحج')>22 - الحج</option>
                        <option value="المؤمنون" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'المؤمنون')>23 - المؤمنون</option>
                        <option value="النور" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'النور')>24 - النور</option>
                        <option value="الفرقان" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الفرقان')>25 - الفرقان</option>
                        <option value="الشعراء" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الشعراء')>26 - الشعراء</option>
                        <option value="النمل" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'النمل')>27 - النمل</option>
                        <option value="القصص" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'القصص')>28 - القصص</option>
                        <option value="العنكبوت" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'العنكبوت')>29 - العنكبوت</option>
                        <option value="الروم" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الروم')>30 - الروم</option>
                        <option value="لقمان" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'لقمان')>31 - لقمان</option>
                        <option value="السجدة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'السجدة')>32 - السجدة</option>
                        <option value="الأحزاب" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الأحزاب')>33 - الأحزاب</option>
                        <option value="سبأ" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'سبأ')>34 - سبأ</option>
                        <option value="فاطر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'فاطر')>35 - فاطر</option>
                        <option value="يس" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'يس')>36 - يس</option>
                        <option value="الصافات" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الصافات')>37 - الصافات</option>
                        <option value="ص" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'ص')>38 - ص</option>
                        <option value="الزمر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الزمر')>39 - الزمر</option>
                        <option value="غافر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'غافر')>40 - غافر</option>
                        <option value="فصلت" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'فصلت')>41 - فصلت</option>
                        <option value="الشورى" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الشورى')>42 - الشورى</option>
                        <option value="الزخرف" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الزخرف')>43 - الزخرف</option>
                        <option value="الدخان" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الدخان')>44 - الدخان</option>
                        <option value="الجاثية" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الجاثية')>45 - الجاثية</option>
                        <option value="الأحقاف" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الأحقاف')>46 - الأحقاف</option>
                        <option value="محمد" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'محمد')>47 - محمد</option>
                        <option value="الفتح" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الفتح')>48 - الفتح</option>
                        <option value="الحجرات" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الحجرات')>49 - الحجرات</option>
                        <option value="ق" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'ق')>50 - ق</option>
                        <option value="الذاريات" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الذاريات')>51 - الذاريات</option>
                        <option value="الطور" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الطور')>52 - الطور</option>
                        <option value="النجم" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'النجم')>53 - النجم</option>
                        <option value="القمر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'القمر')>54 - القمر</option>
                        <option value="الرحمن" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الرحمن')>55 - الرحمن</option>
                        <option value="الواقعة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الواقعة')>56 - الواقعة</option>
                        <option value="الحديد" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الحديد')>57 - الحديد</option>
                        <option value="المجادلة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'المجادلة')>58 - المجادلة</option>
                        <option value="الحشر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الحشر')>59 - الحشر</option>
                        <option value="الممتحنة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الممتحنة')>60 - الممتحنة</option>
                        <option value="الصف" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الصف')>61 - الصف</option>
                        <option value="الجمعة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الجمعة')>62 - الجمعة</option>
                        <option value="المنافقون" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'المنافقون')>63 - المنافقون</option>
                        <option value="التغابن" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'التغابن')>64 - التغابن</option>
                        <option value="الطلاق" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الطلاق')>65 - الطلاق</option>
                        <option value="التحريم" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'التحريم')>66 - التحريم</option>
                        <option value="الملك" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الملك')>67 - الملك</option>
                        <option value="القلم" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'القلم')>68 - القلم</option>
                        <option value="الحاقة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الحاقة')>69 - الحاقة</option>
                        <option value="المعارج" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'المعارج')>70 - المعارج</option>
                        <option value="نوح" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'نوح')>71 - نوح</option>
                        <option value="الجن" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الجن')>72 - الجن</option>
                        <option value="المزمل" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'المزمل')>73 - المزمل</option>
                        <option value="المدثر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'المدثر')>74 - المدثر</option>
                        <option value="القيامة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'القيامة')>75 - القيامة</option>
                        <option value="الإنسان" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الإنسان')>76 - الإنسان</option>
                        <option value="المرسلات" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'المرسلات')>77 - المرسلات</option>
                        <option value="النبأ" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'النبأ')>78 - النبأ</option>
                        <option value="النازعات" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'النازعات')>79 - النازعات</option>
                        <option value="عبس" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'عبس')>80 - عبس</option>
                        <option value="التكوير" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'التكوير')>81 - التكوير</option>
                        <option value="الانفطار" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الانفطار')>82 - الانفطار</option>
                        <option value="المطففين" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'المطففين')>83 - المطففين</option>
                        <option value="الانشقاق" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الانشقاق')>84 - الانشقاق</option>
                        <option value="البروج" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'البروج')>85 - البروج</option>
                        <option value="الطارق" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الطارق')>86 - الطارق</option>
                        <option value="الأعلى" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الأعلى')>87 - الأعلى</option>
                        <option value="الغاشية" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الغاشية')>88 - الغاشية</option>
                        <option value="الفجر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الفجر')>89 - الفجر</option>
                        <option value="البلد" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'البلد')>90 - البلد</option>
                        <option value="الشمس" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الشمس')>91 - الشمس</option>
                        <option value="الليل" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الليل')>92 - الليل</option>
                        <option value="الضحى" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الضحى')>93 - الضحى</option>
                        <option value="الشرح" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الشرح')>94 - الشرح</option>
                        <option value="التين" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'التين')>95 - التين</option>
                        <option value="العلق" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'العلق')>96 - العلق</option>
                        <option value="القدر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'القدر')>97 - القدر</option>
                        <option value="البينة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'البينة')>98 - البينة</option>
                        <option value="الزلزلة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الزلزلة')>99 - الزلزلة</option>
                        <option value="العاديات" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'العاديات')>100 - العاديات</option>
                        <option value="القارعة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'القارعة')>101 - القارعة</option>
                        <option value="التكاثر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'التكاثر')>102 - التكاثر</option>
                        <option value="العصر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'العصر')>103 - العصر</option>
                        <option value="الهمزة" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الهمزة')>104 - الهمزة</option>
                        <option value="الفيل" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الفيل')>105 - الفيل</option>
                        <option value="قريش" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'قريش')>106 - قريش</option>
                        <option value="الماعون" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الماعون')>107 - الماعون</option>
                        <option value="الكوثر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الكوثر')>108 - الكوثر</option>
                        <option value="الكافرون" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الكافرون')>109 - الكافرون</option>
                        <option value="النصر" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'النصر')>110 - النصر</option>
                        <option value="المسد" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'المسد')>111 - المسد</option>
                        <option value="الإخلاص" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الإخلاص')>112 - الإخلاص</option>
                        <option value="الفلق" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الفلق')>113 - الفلق</option>
                        <option value="الناس" @selected(old('du_sourate', request('du_sourate', $du_sourate ?? '')) === 'الناس')>114 - الناس</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="date">Date</label>
                    <input id="date" name="date" type="date" value="{{ $date ?? request('date') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">Rechercher</button>
                    <a href="{{ route('parent.children.evaluations', [$student->id, $halaqa->id]) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Reset</a>
                </div>
            </form>
        </div>

        @forelse ($evaluationsByDay as $date => $dayEvaluations)
            <div class="mb-6 rounded-lg bg-white shadow overflow-hidden">
                <div class="border-b bg-gray-100 px-6 py-3 text-sm font-semibold text-gray-700">
                    {{ \Carbon\Carbon::parse($date)->locale('ar')->translatedFormat('l d F Y') }}
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="border-b text-left text-gray-600">
                            <tr>
                                <th class="px-6 py-3">Heure</th>
                                <th class="px-6 py-3">Cheikh</th>
                                <th class="px-6 py-3">Du sourate</th>
                                <th class="px-6 py-3">Au sourate</th>
                                <th class="px-6 py-3">Note</th>
                                <th class="px-6 py-3">Presence</th>
                                <th class="px-6 py-3">Remarque</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dayEvaluations as $evaluation)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $evaluation->created_at?->format('H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $evaluation->cheikh->nom ?? '-' }} {{ $evaluation->cheikh->prenom ?? '' }}</td>
                                    <td class="px-6 py-4">{{ $evaluation->du_sourate ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $evaluation->au_sourate ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $evaluation->note ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $evaluation->presence ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $evaluation->remarque ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-white p-6 text-center text-gray-500 shadow">Aucune evaluation pour cette halaqa.</div>
        @endforelse
@endsection
