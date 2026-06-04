<!-- Sidebar — liendoan.dev -->
<aside class="adminDashboard_sidebar" id="adminDashboardSidebar">
    <div class="adminSidebar">
        <button class="adminSidebar_mobileClose" type="button" onclick="toggleAdminMobileMenu()" aria-label="Đóng menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>

        <div class="adminSidebar_header">
            <div class="adminSidebar_header_bg">
                <div class="adminSidebar_header_bg_circle adminSidebar_header_bg_circle--1"></div>
                <div class="adminSidebar_header_bg_circle adminSidebar_header_bg_circle--2"></div>
                <div class="adminSidebar_header_bg_circle adminSidebar_header_bg_circle--3"></div>
            </div>

            @php
                $user = auth()->user();
            @endphp

            <a href="{{ route('admin.lang-ui.index') }}" class="adminSidebar_header_link">
                <div class="adminSidebar_header_avatar">
                    <div class="adminSidebar_header_avatar_placeholder">
                        {{ strtoupper(mb_substr($user->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
                <div class="adminSidebar_header_info">
                    <div class="adminSidebar_header_info_name">{{ $user->name }}</div>
                    <div class="adminSidebar_header_info_email">{{ $user->email }}</div>
                </div>
            </a>
        </div>

        <nav class="adminSidebar_nav">
            @foreach (\App\Helpers\AdminMenuHelper::getMenuSections() as $sectionKey => $section)
                <div class="adminSidebar_nav_section">
                    <div class="adminSidebar_nav_section_title">{{ $section['title'] }}</div>
                    @foreach (\App\Helpers\AdminMenuHelper::getMenuItems($sectionKey) as $item)
                        <a href="{{ $item['url'] }}"
                           class="adminSidebar_nav_item {{ $item['active'] ? 'active' : '' }}"
                           @if (! empty($item['onclick'])) onclick="{{ $item['onclick'] }}" @endif>
                            <div class="adminSidebar_nav_item_icon">
                                @if (! empty($item['svg']))
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        {!! $item['svg'] !!}
                                    </svg>
                                @endif
                            </div>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>
    </div>
</aside>

<button type="button" class="adminDashboard_mobileMenuTrigger" id="adminMobileMenuTrigger" onclick="toggleAdminMobileMenu()" aria-label="Mở menu">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 6h16" class="adminDashboard_mobileMenuTrigger_line adminDashboard_mobileMenuTrigger_line--1"/>
        <path d="M4 12h16" class="adminDashboard_mobileMenuTrigger_line adminDashboard_mobileMenuTrigger_line--2"/>
        <path d="M4 18h16" class="adminDashboard_mobileMenuTrigger_line adminDashboard_mobileMenuTrigger_line--3"/>
    </svg>
</button>
