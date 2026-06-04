<?php

declare(strict_types=1);

/**
 * Ghi docs/translation/geo-names.json (bản 2026-05) — đồng bộ hoangsa.dev + ninedashline.dev.
 * Chạy: php scripts/publish-geo-names-json.php
 */

$root = dirname(__DIR__);
$htmlRoot = dirname($root);

$data = [
    '_meta' => [
        'version' => '2026-05',
        'description' => 'Tên địa danh Việt Nam / Hoàng Sa / Trường Sa / Đường Lưỡi Bò / Trung Quốc theo locale — dùng đồng bộ khi dịch lang_ui (hoangsa.dev · ninedashline.dev)',
        'keys' => [
            'vietnam' => 'Quốc gia (Việt Nam / Vietnam / …)',
            'hoang_sa' => 'Quần đảo Hoàng Sa (Paracel)',
            'truong_sa' => 'Quần đảo Trường Sa (Spratly)',
            'combined' => 'Cặp hai quần đảo (tiêu đề, meta, footer)',
            'nine_dash_line' => 'Đường Lưỡi Bò / Nine-Dash Line — tên người dùng địa phương thực tế gọi',
            'china' => 'Trung Quốc / China — tên người dùng địa phương thực tế gọi',
        ],
        'separator' => ' - ',
        'shared_projects' => ['hoangsa.dev', 'ninedashline.dev'],
    ],
    'vi' => ['vietnam' => 'Việt Nam', 'hoang_sa' => 'Hoàng Sa', 'truong_sa' => 'Trường Sa', 'combined' => 'Hoàng Sa - Trường Sa', 'nine_dash_line' => 'Đường lưỡi bò', 'china' => 'Trung Quốc'],
    'en' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paracel Islands', 'truong_sa' => 'Spratly Islands', 'combined' => 'Paracel Islands - Spratly Islands', 'nine_dash_line' => 'Nine-Dash Line', 'china' => 'China'],
    'zh-cn' => ['vietnam' => '越南', 'hoang_sa' => '西沙群岛', 'truong_sa' => '南沙群岛', 'combined' => '西沙群岛 - 南沙群岛', 'nine_dash_line' => '断续线', 'china' => '中国'],
    'zh-tw' => ['vietnam' => '越南', 'hoang_sa' => '西沙群島', 'truong_sa' => '南沙群島', 'combined' => '西沙群島 - 南沙群島', 'nine_dash_line' => '十一段線', 'china' => '中國'],
    'es' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Islas Paracel', 'truong_sa' => 'Islas Spratly', 'combined' => 'Islas Paracel - Islas Spratly', 'nine_dash_line' => 'Línea de nueve guiones', 'china' => 'China'],
    'fr' => ['vietnam' => 'Viêt Nam', 'hoang_sa' => 'Îles Paracels', 'truong_sa' => 'Îles Spratleys', 'combined' => 'Îles Paracels - Îles Spratleys', 'nine_dash_line' => 'Ligne en neuf traits', 'china' => 'Chine'],
    'de' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paracel-Inseln', 'truong_sa' => 'Spratly-Inseln', 'combined' => 'Paracel-Inseln - Spratly-Inseln', 'nine_dash_line' => 'Neun-Striche-Linie', 'china' => 'China'],
    'ru' => ['vietnam' => 'Вьетнам', 'hoang_sa' => 'Парасельские острова', 'truong_sa' => 'Острова Спратли', 'combined' => 'Парасельские острова - Острова Спратли', 'nine_dash_line' => 'Линия девяти пунктиров', 'china' => 'Китай'],
    'ja' => ['vietnam' => 'ベトナム', 'hoang_sa' => '西沙諸島', 'truong_sa' => '南沙諸島', 'combined' => '西沙諸島 - 南沙諸島', 'nine_dash_line' => '九段線', 'china' => '中国'],
    'ko' => ['vietnam' => '베트남', 'hoang_sa' => '파라셀 제도', 'truong_sa' => '스프래틀리 제도', 'combined' => '파라셀 제도 - 스프래틀리 제도', 'nine_dash_line' => '남중국해 9단선', 'china' => '중국'],
    'hi' => ['vietnam' => 'वियतनाम', 'hoang_sa' => 'पैरासेल द्वीपसमूह', 'truong_sa' => 'स्प्रैटली द्वीपसमूह', 'combined' => 'पैरासेल द्वीपसमूह - स्प्रैटली द्वीपसमूह', 'nine_dash_line' => 'नाइन-डैश लाइन', 'china' => 'चीन'],
    'bn' => ['vietnam' => 'ভিয়েতনাম', 'hoang_sa' => 'প্যারাসেল দ্বীপপুঞ্জ', 'truong_sa' => 'স্প্র্যাটলি দ্বীপপুঞ্জ', 'combined' => 'প্যারাসেল দ্বীপপুঞ্জ - স্প্র্যাটলি দ্বীপপুঞ্জ', 'nine_dash_line' => 'নাইন-ড্যাশ লাইন', 'china' => 'চীন'],
    'mr' => ['vietnam' => 'व्हिएतनाम', 'hoang_sa' => 'पॅरासेल द्वीपसमूह', 'truong_sa' => 'स्प्रॅटली द्वीपसमूह', 'combined' => 'पॅरासेल द्वीपसमूह - स्प्रॅटली द्वीपसमूह', 'nine_dash_line' => 'नाइन-डॅश रेषा', 'china' => 'चीन'],
    'ta' => ['vietnam' => 'வியட்நாம்', 'hoang_sa' => 'பராசல் தீவுகள்', 'truong_sa' => 'ஸ்ப்ராட்லி தீவுகள்', 'combined' => 'பராசல் தீவுகள் - ஸ்ப்ராட்லி தீவுகள்', 'nine_dash_line' => 'ஒன்பது-கோடு கோடு', 'china' => 'சீனா'],
    'te' => ['vietnam' => 'వియత్నాం', 'hoang_sa' => 'పారాసెల్ దీవులు', 'truong_sa' => 'స్ప్రాట్లీ దీవులు', 'combined' => 'పారాసెల్ దీవులు - స్ప్రాట్లీ దీవులు', 'nine_dash_line' => 'నైన్-డాష్ లైన్', 'china' => 'చైనా'],
    'ur' => ['vietnam' => 'ویتنام', 'hoang_sa' => 'پیراسل جزائر', 'truong_sa' => 'اسپراٹلی جزائر', 'combined' => 'پیراسل جزائر - اسپراٹلی جزائر', 'nine_dash_line' => 'نو خطوط والی لکیر', 'china' => 'چین'],
    'gu' => ['vietnam' => 'વિયેતનામ', 'hoang_sa' => 'પેરાસેલ ટાપુઓ', 'truong_sa' => 'સ્પ્રેટલી ટાપુઓ', 'combined' => 'પેરાસેલ ટાપુઓ - સ્પ્રેટલી ટાપુઓ', 'nine_dash_line' => 'નાઇન-ડૅશ લાઇન', 'china' => 'ચીન'],
    'jv' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Kapuloan Paracel', 'truong_sa' => 'Kapuloan Spratly', 'combined' => 'Kapuloan Paracel - Kapuloan Spratly', 'nine_dash_line' => 'Garis Sembilan Garis Putus', 'china' => 'Tiongkok'],
    'id' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Kepulauan Paracel', 'truong_sa' => 'Kepulauan Spratly', 'combined' => 'Kepulauan Paracel - Kepulauan Spratly', 'nine_dash_line' => 'Garis Sembilan Titik', 'china' => 'Tiongkok'],
    'ms' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Kepulauan Paracel', 'truong_sa' => 'Kepulauan Spratly', 'combined' => 'Kepulauan Paracel - Kepulauan Spratly', 'nine_dash_line' => 'Garisan Sembilan Titik', 'china' => 'China'],
    'th' => ['vietnam' => 'เวียดนาม', 'hoang_sa' => 'หมู่เกาะพาราเซล', 'truong_sa' => 'หมู่เกาะสแปรตลี', 'combined' => 'หมู่เกาะพาราเซล - หมู่เกาะสแปรตลี', 'nine_dash_line' => 'เส้นประเก้าเส้น', 'china' => 'จีน'],
    'ar' => ['vietnam' => 'فيتنام', 'hoang_sa' => 'جزر باراسيل', 'truong_sa' => 'جزر سبراتلي', 'combined' => 'جزر باراسيل - جزر سبراتلي', 'nine_dash_line' => 'خط الشرطات التسع', 'china' => 'الصين'],
    'fa' => ['vietnam' => 'ویتنام', 'hoang_sa' => 'جزایر پاراسل', 'truong_sa' => 'جزایر اسپراتلی', 'combined' => 'جزایر پاراسل - جزایر اسپراتلی', 'nine_dash_line' => 'خط نه‌خطی', 'china' => 'چین'],
    'tr' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paracel Adaları', 'truong_sa' => 'Spratly Adaları', 'combined' => 'Paracel Adaları - Spratly Adaları', 'nine_dash_line' => 'Dokuz Çizgi Hattı', 'china' => 'Çin'],
    'it' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Isole Paracel', 'truong_sa' => 'Isole Spratly', 'combined' => 'Isole Paracel - Isole Spratly', 'nine_dash_line' => 'Linea dei nove trattini', 'china' => 'Cina'],
    'pl' => ['vietnam' => 'Wietnam', 'hoang_sa' => 'Wyspy Paracelskie', 'truong_sa' => 'Wyspy Spratly', 'combined' => 'Wyspy Paracelskie - Wyspy Spratly', 'nine_dash_line' => 'Linia dziewięciu kresek', 'china' => 'Chiny'],
    'uk' => ['vietnam' => 'Вʼєтнам', 'hoang_sa' => 'Парасельські острови', 'truong_sa' => 'Острови Спратлі', 'combined' => 'Парасельські острови - Острови Спратлі', 'nine_dash_line' => 'Лінія дев\'яті пунктирів', 'china' => 'Китай'],
    'nl' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paraceleilanden', 'truong_sa' => 'Spratly-eilanden', 'combined' => 'Paraceleilanden - Spratly-eilanden', 'nine_dash_line' => 'Negenstreepjeslijn', 'china' => 'China'],
    'el' => ['vietnam' => 'Βιετνάμ', 'hoang_sa' => 'Νησιά Παρασέλ', 'truong_sa' => 'Νησιά Σπράτλι', 'combined' => 'Νησιά Παρασέλ - Νησιά Σπράτλι', 'nine_dash_line' => 'Γραμμή Εννέα Παύλων', 'china' => 'Κίνα'],
    'hu' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paracel-szigetek', 'truong_sa' => 'Spratly-szigetek', 'combined' => 'Paracel-szigetek - Spratly-szigetek', 'nine_dash_line' => 'Kilenc vonásos vonal', 'china' => 'Kína'],
    'cs' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paracelské ostrovy', 'truong_sa' => 'Spratlyho ostrovy', 'combined' => 'Paracelské ostrovy - Spratlyho ostrovy', 'nine_dash_line' => 'Čára devíti čárek', 'china' => 'Čína'],
    'ro' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Insulele Paracel', 'truong_sa' => 'Insulele Spratly', 'combined' => 'Insulele Paracel - Insulele Spratly', 'nine_dash_line' => 'Linia celor nouă liniuțe', 'china' => 'China'],
    'sk' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paracelské ostrovy', 'truong_sa' => 'Spratlyho ostrovy', 'combined' => 'Paracelské ostrovy - Spratlyho ostrovy', 'nine_dash_line' => 'Čiara deviatich čiarok', 'china' => 'Čína'],
    'ka' => ['vietnam' => 'ვიეტნამი', 'hoang_sa' => 'პარასელის კუნძულები', 'truong_sa' => 'სპრატლის კუნძულები', 'combined' => 'პარასელის კუნძულები - სპრატლის კუნძულები', 'nine_dash_line' => 'ცხრა ტირეს ხაზი', 'china' => 'ჩინეთი'],
    'he' => ['vietnam' => 'וייטנאם', 'hoang_sa' => 'איי פארסל', 'truong_sa' => 'איי ספרטלי', 'combined' => 'איי פארסל - איי ספרטלי', 'nine_dash_line' => 'קו תשע המקפים', 'china' => 'סין'],
    'uz' => ['vietnam' => 'Vyetnam', 'hoang_sa' => 'Parasel orollari', 'truong_sa' => 'Spratli orollari', 'combined' => 'Parasel orollari - Spratli orollari', 'nine_dash_line' => 'To\'qqiz chiziqli chegara', 'china' => 'Xitoy'],
    'pt' => ['vietnam' => 'Vietname', 'hoang_sa' => 'Ilhas Paracel', 'truong_sa' => 'Ilhas Spratly', 'combined' => 'Ilhas Paracel - Ilhas Spratly', 'nine_dash_line' => 'Linha dos Nove Traços', 'china' => 'China'],
    'fil' => ['vietnam' => 'Biyetnam', 'hoang_sa' => 'Kapuluang Paracel', 'truong_sa' => 'Kapuluang Spratly', 'combined' => 'Kapuluang Paracel - Kapuluang Spratly', 'nine_dash_line' => 'Linya ng Siyam na Guhit', 'china' => 'Tsina'],
    'sv' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paracelöarna', 'truong_sa' => 'Spratlyöarna', 'combined' => 'Paracelöarna - Spratlyöarna', 'nine_dash_line' => 'Niostrecksgränsen', 'china' => 'Kina'],
    'no' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paraceløyene', 'truong_sa' => 'Spratlyøyene', 'combined' => 'Paraceløyene - Spratlyøyene', 'nine_dash_line' => 'Ni-streklinjen', 'china' => 'Kina'],
    'fi' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paracelsaaret', 'truong_sa' => 'Spratlysaaret', 'combined' => 'Paracelsaaret - Spratlysaaret', 'nine_dash_line' => 'Yhdeksän viivan linja', 'china' => 'Kiina'],
    'da' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paraceløerne', 'truong_sa' => 'Spratlyøerne', 'combined' => 'Paraceløerne - Spratlyøerne', 'nine_dash_line' => 'Ni-streglinjen', 'china' => 'Kina'],
    'ml' => ['vietnam' => 'വിയറ്റ്നാം', 'hoang_sa' => 'പരാസെൽ ദ്വീപുകൾ', 'truong_sa' => 'സ്പ്രാറ്റ്ലി ദ്വീപുകൾ', 'combined' => 'പരാസെൽ ദ്വീപുകൾ - സ്പ്രാറ്റ്ലി ദ്വീപുകൾ', 'nine_dash_line' => 'ഒൻപത്-ഡാഷ് രേഖ', 'china' => 'ചൈന'],
    'bg' => ['vietnam' => 'Виетнам', 'hoang_sa' => 'Параселски острови', 'truong_sa' => 'Острови Спратли', 'combined' => 'Параселски острови - Острови Спратли', 'nine_dash_line' => 'Линията на деветте тирета', 'china' => 'Китай'],
    'ky' => ['vietnam' => 'Вьетнам', 'hoang_sa' => 'Парасел аралдары', 'truong_sa' => 'Спратли аралдары', 'combined' => 'Парасел аралдары - Спратли аралдары', 'nine_dash_line' => 'Тогуз сызык чеги', 'china' => 'Кытай'],
    'sr' => ['vietnam' => 'Вијетнам', 'hoang_sa' => 'Параселска острва', 'truong_sa' => 'Спратли острва', 'combined' => 'Параселска острва - Спратли острва', 'nine_dash_line' => 'Линија девет цртица', 'china' => 'Кина'],
    'lv' => ['vietnam' => 'Vjetnama', 'hoang_sa' => 'Paraselu salas', 'truong_sa' => 'Spratlija salas', 'combined' => 'Paraselu salas - Spratlija salas', 'nine_dash_line' => 'Deviņu svītru līnija', 'china' => 'Ķīna'],
    'lt' => ['vietnam' => 'Vietnamas', 'hoang_sa' => 'Paracelio salos', 'truong_sa' => 'Spratlio salos', 'combined' => 'Paracelio salos - Spratlio salos', 'nine_dash_line' => 'Devynių brūkšnių linija', 'china' => 'Kinija'],
    'sl' => ['vietnam' => 'Vietnam', 'hoang_sa' => 'Paracelski otoki', 'truong_sa' => 'Spratlyjevi otoki', 'combined' => 'Paracelski otoki - Spratlyjevi otoki', 'nine_dash_line' => 'Linija devetih črt', 'china' => 'Kitajska'],
    'mn' => ['vietnam' => 'Вьетнам', 'hoang_sa' => 'Параселийн арлууд', 'truong_sa' => 'Спратлийн арлууд', 'combined' => 'Параселийн арлууд - Спратлийн арлууд', 'nine_dash_line' => 'Есөн зурааст шугам', 'china' => 'Хятад'],
];

$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($json === false) {
    fwrite(STDERR, "json_encode failed\n");
    exit(1);
}

$targets = [
    $root . '/docs/translation/geo-names.json',
    $htmlRoot . '/hoangsa.dev/docs/translation/geo-names.json',
];

foreach ($targets as $path) {
    if (! is_dir(dirname($path))) {
        fwrite(STDERR, "Skip (no dir): {$path}\n");
        continue;
    }
    file_put_contents($path, $json . "\n");
    echo "Wrote {$path}\n";
}

echo "Locales: " . (count($data) - 1) . " (+ _meta)\n";
