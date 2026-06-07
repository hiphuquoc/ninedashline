@php
  $navRoot = rtrim($navRootUrl ?? '', '/');
  $navHref = static fn (string $hash): string => ($navRoot !== '' ? $navRoot : '') . $hash;
  $navRailSteps = $navRailSteps ?? \App\Support\LandingPageData::navRailStepsResolved();
@endphp
<nav id="mainNav" aria-label="{{ te('nav_rail_aria') }}">
  <a href="{{ $navHref('#hero') }}" class="nav-logo brand-mark" id="navLogo" data-cursor aria-label="{{ ($brandMark ?? \App\Support\BrandMark::data())['aria'] }}">
    @include('landing.partials.brand-mark', ['brandMark' => $brandMark ?? null, 'logoFetchPriority' => 'high'])
  </a>

  <div class="nav-rail" id="navRail" role="navigation" aria-label="{{ te('nav_rail_aria') }}">
    <div class="nav-rail-steps" style="--nav-step-count:{{ count($navRailSteps) }};">
      <span class="nav-rail-track" aria-hidden="true"></span>
      <span class="nav-rail-progress" id="navRailProgress" aria-hidden="true"></span>
      @foreach ($navRailSteps as $step)
      <a href="{{ $navHref('#' . $step['section']) }}" class="nav-step" data-section="{{ $step['section'] }}" data-cursor>
        <span class="nav-step-dot" aria-hidden="true"></span>
        <span class="nav-step-label" data-nav-ui="{{ $step['label_key'] }}">{{ $step['label'] }}</span>
      </a>
      @endforeach
    </div>
  </div>

  @include('landing.partials.nav-end')
</nav>
