@php
  $carouselId = isset($id) ? (string) $id : 'carousel-'.uniqid();
  $imgs = [];
  for ($i = 1; $i <= 15; $i++) {
    $path = public_path('images/'.$i.'.jpg');
    if (file_exists($path)) { $imgs[] = asset('images/'.$i.'.jpg'); }
  }
@endphp

<section class="py-8">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div id="{{ $carouselId }}" class="relative w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="relative h-64 sm:h-80 md:h-96">
        @if(count($imgs))
          @foreach($imgs as $k => $src)
            <img data-idx="{{ $k }}" src="{{ $src }}" alt="Slide {{ $k+1 }}" class="c-slide absolute inset-0 h-full w-full object-cover {{ $k === 0 ? '' : 'hidden' }}" loading="lazy">
          @endforeach
        @else
          <div class="absolute inset-0 grid place-content-center text-slate-500">
            <div>No images found in public/images (1.jpg - 15.jpg)</div>
          </div>
        @endif
      </div>
      @if(count($imgs))
        <div class="pointer-events-auto absolute inset-x-0 bottom-3 flex justify-center gap-2">
          @foreach($imgs as $k => $_)
            <button type="button" class="c-dot size-2 rounded-full bg-white/80 ring-1 ring-black/10" data-idx="{{ $k }}" aria-label="Go to slide {{ $k+1 }}"></button>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</section>

@if(count($imgs))
  <script>
    (function(){
      var root = document.getElementById(@json($carouselId));
      if(!root) return;
      var slides = Array.prototype.slice.call(root.querySelectorAll('.c-slide'));
      if(!slides.length) return;
      var dots = Array.prototype.slice.call(root.querySelectorAll('.c-dot'));
      var idx = 0; var n = slides.length; var timer;
      function show(i){
        idx = (i + n) % n;
        slides.forEach(function(s, k){ s.classList.toggle('hidden', k !== idx); });
        if(dots.length) dots.forEach(function(d, k){ d.style.opacity = (k === idx ? '1' : '.5'); });
      }
      function start(){ timer = setInterval(function(){ show(idx+1); }, 5000); }
      function stop(){ if(timer) clearInterval(timer); }
      if(dots.length) dots.forEach(function(d){ d.addEventListener('click', function(){ stop(); show(parseInt(this.dataset.idx||'0',10)); start(); }); });
      show(0); start();
    })();
  </script>
@endif




