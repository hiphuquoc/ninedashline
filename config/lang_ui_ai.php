<?php

/**
 * Prompt dịch admin lang_ui — bản gốc tiếng Việt (vi), áp dụng cho MỘT trường (key) trong section.
 * Token: [locale], [target_language], [section], [key], [source]
 * Quy chuẩn: docs/translation/guide.md · geo-names.json
 */
return [
    'field_prompt_base_vi' => <<<'BASE'
Bạn là biên dịch chuyên môn cho ninedashline.dev — trang giáo dục về tranh chấp Đường Lưỡi Bò (Nine-Dash Line) trên Biển Đông.

Quy tắc bắt buộc (mọi trường):
- Dịch từ bản tiếng Việt (master vi); không lược bỏ luận điểm, không thêm ý không có trong bản gốc.
- Giữ nguyên toàn bộ thẻ HTML, class CSS (accent, red-accent), xuống dòng, placeholder (:year, :count, :paracel, :spratly, :hoangsa, :truongsa, :ninedashline).
- Liên kết hệ sinh thái (paracelislands.net, spratlyislands.net, hoangsaisland.net, truongsaisland.net, ninedashline.net): CHỈ sửa thuộc tính href — URL phải có /[locale] đúng mã ngôn ngữ đích [locale] (vd. đích ja → https://paracelislands.net/ja). Không đổi text hiển thị trong <a>. Placeholder :paracel, :spratly, :hoangsa, :truongsa, :ninedashline: giữ nguyên; nếu href là URL đầy đủ thì đổi segment locale cho khớp [locale].
- Khi trả về JSON (prompt copy ngoài): dịch phần chữ hiển thị nhưng GIỮ NGUYÊN cấu trúc HTML; mọi dấu `"` bên trong value phải escape thành `\"`.
- Giọng: trang trọng, giáo dục, phân tích khách quan — không kích động thù hận dân tộc.
- Tên địa danh: tra docs/translation/geo-names.json theo locale [locale] (vietnam, hoang_sa, truong_sa, combined, nine_dash_line, china).
- Trong bản gốc vi: tên chính **Đường Lưỡi Bò**; chỉ dùng **Đường Chín Đoạn** trong section what khi liệt kê tên gọi thay thế.
- Chỉ trả về MỘT chuỗi dịch — không giải thích, không markdown, không bọc ngoặc kép thừa.
BASE,

    'field_prompt_suffix' => <<<'SUF'

---
Ngữ cảnh section: [section]
Key đang dịch: [key]
Ngôn ngữ đích: [target_language] (mã locale: [locale])

Nội dung tiếng Việt (dịch sang ngôn ngữ đích, chỉ output bản dịch):
[source]
SUF,

    'scopes' => [
        'landing' => [
            'nav' => [
                'label' => 'Menu & điều hướng',
                'field_prompt_vi' => 'Nhãn menu, modal 50 ngôn ngữ, nút âm thanh/chia sẻ: ngắn, vừa khung UI.',
            ],
            'meta' => [
                'label' => 'SEO & Meta',
                'field_prompt_vi' => 'Title và meta description: chính xác, độ dài phù hợp snippet tìm kiếm.',
            ],
            'hero' => [
                'label' => 'Hero (mở đầu)',
                'field_prompt_vi' => 'Tiêu đề hero, CTA, fact line. Giữ xuống dòng/HTML nếu có.',
            ],
            'common' => [
                'label' => 'Loader & UI chung',
                'field_prompt_vi' => 'Loader, toast chia sẻ/sao chép link: ngắn gọn.',
            ],
            'what' => [
                'label' => '01 · Định nghĩa',
                'field_prompt_vi' => 'Định nghĩa Đường Lưỡi Bò: liệt kê tên gọi (có thể nhắc Đường Chín Đoạn như tên thay thế); 4 ý, thống kê, chú thích bản đồ; Nine-Dash Line / South China Sea chuẩn.',
            ],
            'origin' => [
                'label' => '02 · Nguồn gốc',
                'field_prompt_vi' => 'Timeline lịch sử 1936–2016: năm, tag, tiêu đề, đoạn HTML; văn phong lịch sử.',
            ],
            'dispute' => [
                'label' => '03 · Tranh cãi',
                'field_prompt_vi' => 'Lý do tranh cãi, trụ cột, so sánh EEZ 200 hải lý, UNCLOS: thuật ngữ pháp lý biển chính xác; Đường Lưỡi Bò dùng nine_dash_line, quốc gia dùng china từ geo-names.json.',
            ],
            'opposition' => [
                'label' => '04 · Phản đối',
                'field_prompt_vi' => 'Tiếng nói phản đối từ các nước: trích dẫn, nhãn quốc gia; khách quan.',
            ],
            'witnesses' => [
                'label' => '05 · Khung section nhân chứng',
                'field_prompt_vi' => 'Nhãn section, lead, quote, lightbox; không dịch timeline chi tiết (ancient_maps).',
            ],
            'ancient_maps' => [
                'label' => '05b · Timeline bản đồ cổ',
                'field_prompt_vi' => 'Timeline bản đồ cổ: ancient_map_*_title, *_year (niên đại ngắn), *_body (HTML). Giữ thẻ HTML.',
            ],
            'verdict' => [
                'label' => '06 · PCA 2016',
                'field_prompt_vi' => 'Phán quyết PCA 2016: tóm tắt pháp lý, kết luận tòa; chính xác thuật ngữ.',
            ],
            'sovereignty' => [
                'label' => '07 · Hoàng Sa – Trường Sa',
                'field_prompt_vi' => 'Chủ quyền Hoàng Sa, Trường Sa của Việt Nam; tên quần đảo theo locale. Nếu có link paracelislands.net trong href: dùng /[locale] đích.',
            ],
            'footer' => [
                'label' => 'Footer',
                'field_prompt_vi' => 'Footer: sứ mệnh, trích dẫn, footer_copy có <a href>; giữ :year; href site hệ sinh thái phải /[locale] đích; escape JSON nếu xuất ngoài.',
            ],
        ],
        'contribute' => [
            'page' => [
                'label' => 'Trang & SEO',
                'field_prompt_vi' => 'H1 và meta trang Chung sức: tin cậy, mời gọi đồng hành phi lợi nhuận.',
            ],
            'nav' => [
                'label' => 'Menu Chung sức',
                'field_prompt_vi' => 'Nhãn bước điều hướng trang Chung sức — ngắn.',
            ],
            'letter' => [
                'label' => 'Tâm thư',
                'field_prompt_vi' => 'Tâm thư mở: chân thành, lịch sử; đoạn dài giữ mạch văn; HTML title nếu có.',
            ],
            'paths' => [
                'label' => '4 bước đồng hành',
                'field_prompt_vi' => '4 lối đóng góp + intro: tiêu đề, mô tả, CTA.',
            ],
            'payments' => [
                'label' => 'Thanh toán & PayPal',
                'field_prompt_vi' => 'UI thanh toán, PayPal, disclaimer: rõ ràng, chuẩn UX.',
            ],
            'donor' => [
                'label' => 'Ghi nhận nhà tài trợ',
                'field_prompt_vi' => 'Form/modal ghi nhận: ấm áp, tôn trọng.',
            ],
        ],
    ],
];
