<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About — {{ config('app.name') }}</title>
  @vite('resources/css/app.css')
  <style>
    .reveal{opacity:0;transform:translateY(12px);transition:opacity .5s ease,transform .5s ease}
    .reveal.show{opacity:1;transform:none}
    .animated-gradient{background:linear-gradient(90deg,#4F46E5,#9333EA);background-size:200% 200%;animation:grad-move 8s ease-in-out infinite}
    @keyframes grad-move{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
    .shape{position:absolute;border-radius:9999px;opacity:.25;filter:blur(2px)}
    .counter{font-variant-numeric:tabular-nums}
  </style>
  <script>
    // smooth scroll for hero CTA
    document.addEventListener('click', function(e){
      var a = e.target.closest('a[href^="#"]');
      if(!a) return;
      var id = a.getAttribute('href');
      var el = document.querySelector(id);
      if(el){ e.preventDefault(); el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
  </script>
  
</head>
<body class="bg-white text-slate-800">
  @include('partials.header')

  <!-- Hero (Intro Banner) -->
  <section class="relative overflow-hidden bg-slate-50" style="background: linear-gradient(rgba(248, 250, 252, 0.8), rgba(248, 250, 252, 0.8)), url('/images/5.jpg'); background-size: cover; background-position: center;">
    <div class="absolute -top-10 -left-10 shape w-40 h-40 bg-indigo-300"></div>
    <div class="absolute bottom-0 -right-8 shape w-56 h-56 bg-purple-300"></div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14 grid md:grid-cols-2 gap-10 items-center">
      <div class="reveal">
        <h1 class="text-3xl sm:text-4xl font-extrabold">Empowering schools with seamless technology.</h1>
        <p class="mt-3 text-slate-600">{{ config('app.name') }} is built to simplify school operations — admissions, fees, exams, communication, and beyond.</p>
        <div class="mt-6">
          <a href="#our-story" class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-3 text-white font-semibold shadow-sm hover:from-indigo-700 hover:to-purple-700">Learn More</a>
        </div>
      </div>
      <div class="reveal">
        @php($imgs = collect(glob(public_path('images/*.{jpg,jpeg,png,webp}'), GLOB_BRACE))->map(fn($p) => str_replace(public_path(), '', $p))->shuffle()->take(3))
        <div class="grid sm:grid-cols-3 gap-3">
          @foreach($imgs as $img)
            <img src="{{ $img }}" alt="Team / classroom" class="rounded-xl ring-1 ring-slate-200 object-cover w-full h-40" loading="lazy">
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <!-- Our Mission -->
  <section class="py-14 bg-white">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 text-center">
      <div class="reveal">
        <h2 class="text-2xl sm:text-3xl font-extrabold">Our Mission</h2>
        <p class="mt-3 text-slate-700">We believe education should be powered by <span class="underline decoration-indigo-400 decoration-4">clarity</span>, not complexity. Our mission is to give schools powerful, simple tools so administrators, teachers, students, and parents can focus on what really matters — <span class="underline decoration-purple-400 decoration-4">learning</span>.</p>
      </div>
    </div>
  </section>

  <!-- Our Story (Timeline) -->
  <section id="our-story" class="py-14 bg-slate-50">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-center">Our Story</h2>
      <div class="mt-8 relative">
        <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-px bg-slate-200"></div>
        @php($events = [
          ['year' => '2019', 'title' => 'The Idea', 'desc' => 'Helping schools digitize records.'],
          ['year' => '2020', 'title' => 'Prototype', 'desc' => 'Launched admissions and fees.'],
          ['year' => '2022', 'title' => 'Expansion', 'desc' => 'Added exams, library and timetable.'],
          ['year' => '2025', 'title' => 'Scale', 'desc' => 'Multi‑school with premium finance & HR.'],
        ])
        <div class="space-y-8">
          @foreach($events as $i => $e)
            <div class="reveal grid md:grid-cols-2 gap-6 items-start">
              <div class="md:text-right md:pr-8 {{ $i % 2 ? 'md:order-2' : '' }}">
                <div class="inline-flex items-center gap-3">
                  <span class="text-sm font-semibold text-indigo-700">{{ $e['year'] }}</span>
                  <span class="text-lg font-semibold">{{ $e['title'] }}</span>
                </div>
                <p class="text-slate-600 mt-1">{{ $e['desc'] }}</p>
              </div>
              <div class="md:pl-8 {{ $i % 2 ? 'md:order-1' : '' }}">
                <div class="rounded-xl h-28 bg-white ring-1 ring-slate-200 grid place-content-center">📌</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  <!-- Meet the Team -->
  <section class="py-14 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-center">Meet the Team</h2>
      @php($avatars = $imgs->concat(collect(glob(public_path('images/*.{jpg,jpeg,png,webp}'), GLOB_BRACE))->map(fn($p) => str_replace(public_path(), '', $p)))->shuffle()->take(6))
      <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($avatars as $k => $a)
          <div class="rounded-xl overflow-hidden border border-slate-200 group">
            <img src="{{ $a }}" alt="Team member" class="w-full h-44 object-cover">
            <div class="p-4">
              <div class="font-semibold">{{ $k === 0 ? 'Duncan Maingi' : 'Team Member '.($k+1) }}</div>
              <div class="text-sm text-slate-600">{{ $k === 0 ? 'Founder & Developer' : 'Product / Support' }}</div>
            </div>
            <a href="#" class="hidden group-hover:flex absolute inset-0 items-center justify-center bg-black/30 text-white text-sm">View LinkedIn</a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Impact in Numbers -->
  <section class="py-14 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-center">Our Impact</h2>
      <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
        @php($nums = [ ['50','Schools'], ['15000','Students'], ['5000','Exams'], ['99.9','Uptime %'] ])
        @foreach($nums as $n)
          <div class="rounded-xl bg-white p-4 shadow-sm reveal">
            <div class="text-3xl font-extrabold"><span class="counter" data-target="{{ $n[0] }}">0</span>{{ $n[1]==='Uptime %' ? '%' : '+' }}</div>
            <div class="text-xs text-slate-500">{{ $n[1] }} managed</div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Why Choose Us -->
  <section class="py-14 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-center">Why Choose Us</h2>
      @php($pillars = [
        ['emoji' => '🔒', 'title' => 'Secure & Reliable', 'desc' => 'Encrypted with daily backups.'],
        ['emoji' => '🚀', 'title' => 'Fast & Scalable', 'desc' => 'Handles thousands of records.'],
        ['emoji' => '🎓', 'title' => 'Education‑First', 'desc' => 'Designed with schools, for schools.'],
        ['emoji' => '💡', 'title' => 'Future‑Proof', 'desc' => 'AI‑ready, mobile‑first, always evolving.'],
      ])
      <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($pillars as $p)
          <div class="reveal rounded-xl bg-slate-50 p-6 ring-1 ring-slate-200">
            <div class="text-3xl">{{ $p['emoji'] }}</div>
            <div class="mt-2 font-semibold">{{ $p['title'] }}</div>
            <p class="text-sm text-slate-600">{{ $p['desc'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Partnerships / Logos (optional placeholders) -->
  <section class="py-12 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h3 class="text-center text-sm font-semibold text-slate-500">Trusted by leading schools</h3>
      @php($logos = collect(glob(public_path('images/*.{jpg,jpeg,png,webp,svg}'), GLOB_BRACE))->map(fn($p) => str_replace(public_path(), '', $p))->shuffle()->take(8))
      <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4 items-center">
        @foreach($logos as $l)
          <img src="{{ $l }}" alt="Partner logo" class="h-12 w-full object-contain opacity-80 hover:opacity-100 transition" loading="lazy">
        @endforeach
      </div>
    </div>
  </section>

  <!-- Final CTA -->
  <section class="py-12">
    <div class="mx-auto max-w-5xl rounded-2xl px-6 py-10 text-center text-white shadow-lg animated-gradient">
      <h3 class="text-2xl font-bold">We’re on a mission to transform education. Join us today.</h3>
      <div class="mt-5 flex items-center justify-center gap-3">
        <a href="{{ url('/contact') }}" class="inline-flex items-center rounded-lg bg-white px-5 py-3 text-slate-900 font-semibold shadow-sm hover:bg-slate-100">Book a Demo</a>
        <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg ring-1 ring-white/70 px-5 py-3 text-white font-semibold hover:bg-white/10">Get Started Free</a>
      </div>
    </div>
  </section>

  <script>
    // reveal on scroll
    (function(){
      var items = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
      if(!items.length || !('IntersectionObserver' in window)) return items.forEach(function(i){ i.classList.add('show'); });
      var io = new IntersectionObserver(function(entries){
        entries.forEach(function(e){ if(e.isIntersecting){ e.target.classList.add('show'); io.unobserve(e.target); } });
      }, { threshold: 0.15 });
      items.forEach(function(i){ io.observe(i); });
    })();
    // counters
    (function(){
      var counters = Array.prototype.slice.call(document.querySelectorAll('.counter'));
      if(!counters.length) return;
      function animate(el, target){
        var start = 0; var dur = 1200; var t0 = performance.now();
        function tick(t){ var p = Math.min(1,(t-t0)/dur); el.textContent = Math.floor(p*parseFloat(target)).toString(); if(p<1) requestAnimationFrame(tick); }
        requestAnimationFrame(tick);
      }
      var fired = false;
      function startIfVisible(){
        if(fired) return; var first = document.querySelector('.counter'); if(!first) return;
        var r = first.getBoundingClientRect(); if(r.top < innerHeight){
          document.querySelectorAll('.counter').forEach(function(c){ animate(c, c.getAttribute('data-target')); }); fired = true;
        }
      }
      addEventListener('scroll', startIfVisible, { passive: true }); addEventListener('load', startIfVisible); startIfVisible();
    })();
  </script>
  @include('partials.footer')
</body>
</html>