@php($mapsAria = $ancientMapsLangPrefix ?? 'witnesses')
<div class="ancient-maps-timeline" role="list" aria-label="{{ t($mapsAria . '_timeline_aria') }}">
  @foreach ($ancientChineseMaps as $mi => $map)
    <article
      class="ancient-map-entry reveal{{ $mi > 0 ? ' reveal-delay-' . min($mi, 2) : '' }}"
      role="listitem"
      id="ancient-map-{{ $map['id'] }}"
    >
      <div class="ancient-map-entry__rail" aria-hidden="true">
        <span class="ancient-map-entry__dot"></span>
        @if (! $loop->last)
          <span class="ancient-map-entry__line"></span>
        @endif
      </div>
      <div class="ancient-map-entry__year" aria-label="{{ t($mapsAria . '_year_aria') }}">{{ $map['year_label'] }}</div>
      <div class="ancient-map-entry__content">
        @if ($map['image'])
          <figure class="ancient-map-entry__figure">
            <button
              type="button"
              class="ancient-map-entry__zoom"
              data-ancient-map-lightbox
              data-src="{{ $map['image'] }}"
              data-alt="{{ $map['image_alt'] }}"
              data-era="{{ e($map['year_label']) }}"
              data-title="{{ e($map['title']) }}"
              aria-label="{{ t('lightbox_aria') }}: {{ $map['title'] }}"
            >
              <img
                src="{{ $map['image'] }}"
                alt=""
                loading="lazy"
                decoding="async"
                width="1200"
                height="800"
              />
              <span class="ancient-map-entry__zoom-hint" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle cx="10" cy="10" r="6.5" stroke="currentColor" stroke-width="1.6"/>
                  <path d="M14.5 14.5L20 20" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                  <path d="M10 7.5V12.5M7.5 10H12.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
              </span>
            </button>
          </figure>
        @endif
        <h4 class="ancient-map-entry__title">{{ $map['title'] }}</h4>
        <div class="ancient-map-entry__text prose-body">{!! $map['body'] !!}</div>
      </div>
    </article>
  @endforeach
</div>
