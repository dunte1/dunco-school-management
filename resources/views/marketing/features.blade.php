<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Features — {{ config('app.name') }}</title>
  @vite('resources/css/app.css')
  <style>
    .reveal{opacity:0;transform:translateY(12px);transition:opacity .5s ease,transform .5s ease}
    .reveal.show{opacity:1;transform:none}
    .hub{position:relative;width:320px;height:320px;margin:auto}
    .hub .center{position:absolute;inset:0;border-radius:50%;background:linear-gradient(135deg,#4F46E5,#9333EA);box-shadow:0 10px 30px rgba(79,70,229,.35)}
    .hub .orbit{position:absolute;inset:0;border-radius:50%;animation:spin 18s linear infinite}
    .hub .node{position:absolute;left:50%;top:-12px;transform:translateX(-50%);background:white;border-radius:9999px;padding:.35rem .6rem;box-shadow:0 6px 20px rgba(2,6,23,.12);font-size:.75rem}
    .hub .orbit .node:nth-child(2){top:50%;left:calc(100% - 12px);transform:translate(-50%,-50%)}
    .hub .orbit .node:nth-child(3){top:calc(100% - 12px)}
    .hub .orbit .node:nth-child(4){left:12px;top:50%;transform:translateY(-50%)}
    @keyframes spin{to{transform:rotate(360deg)}}
    .animated-gradient{background:linear-gradient(90deg,#4F46E5,#9333EA);background-size:200% 200%;animation:grad-move 8s ease-in-out infinite}
    @keyframes grad-move{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
    .feature-card{transition:transform .25s ease, box-shadow .25s ease}
    .feature-card:hover{transform:translateY(-4px);box-shadow:0 10px 24px rgba(2,6,23,.12)}
    .feature-card .more{opacity:0;transform:translateY(6px);transition:opacity .2s ease, transform .2s ease}
    .feature-card:hover .more{opacity:1;transform:none}
    table.compare td, table.compare th{border:1px solid #e5e7eb;padding:.6rem .8rem;text-align:center}
    table.compare{width:100%;border-collapse:collapse}
    @keyframes marquee {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    .animate-marquee {
      animation: marquee 30s linear infinite;
    }
  </style>
</head>
<body class="bg-white text-slate-800">
  @include('partials.header')

  <!-- Hero Section -->
  <section class="bg-slate-50" style="background: linear-gradient(rgba(248, 250, 252, 0.9), rgba(248, 250, 252, 0.9)), url('/images/4.jpg'); background-size: cover; background-position: center;">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16 grid md:grid-cols-2 gap-12 items-center">
      <div>
        <h1 class="text-3xl sm:text-4xl font-extrabold">All‑in‑one features to run your school with speed, clarity, and control.</h1>
        <p class="mt-3 text-slate-600">From admissions to finance, everything is beautifully integrated in one platform.</p>
        <div class="mt-6 flex flex-wrap gap-3">
          <a href="{{ url('/contact') }}" class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-3 text-white font-semibold shadow-sm hover:from-indigo-700 hover:to-purple-700">Book a Demo</a>
          <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg px-5 py-3 font-semibold text-slate-700 ring-1 ring-inset ring-slate-300 hover:bg-slate-50">Get Started Free</a>
        </div>
      </div>
      <div class="reveal">
        <div class="hub">
          <div class="center"></div>
          <div class="orbit">
            <div class="node">Admissions</div>
            <div class="node">Fees</div>
            <div class="node">Exams</div>
            <div class="node">Timetable</div>
          </div>
        </div>
        <p class="text-center mt-3 text-sm text-slate-500">Dashboard mockup with modules orbiting the hub</p>
      </div>
    </div>
  </section>

  <!-- Core Modules (with screenshots from public/images) -->
  @php($coreModules = [
    ['icon' => '🧑‍🎓', 'title' => 'Admissions & Students', 'points' => ['Enrollments, transfers, profiles','Centralized student database','Document uploads (birth cert, transcripts)']],
    ['icon' => '💳', 'title' => 'Fees & Invoicing', 'points' => ['Custom fee structures','Online payments (M‑Pesa/PayPal/Stripe)','Automated receipts & debt tracking']],
    ['icon' => '📝', 'title' => 'Examinations', 'points' => ['Online & offline exams','AI proctoring','Auto‑grading & instant results']],
    ['icon' => '🕒', 'title' => 'Timetable', 'points' => ['Drag‑and‑drop builder','Conflict‑free allocation','Teacher availability']],
    ['icon' => '📢', 'title' => 'Communication', 'points' => ['Bulk SMS, Email, Push','Parent‑Teacher messaging','Announcements']],
    ['icon' => '📚', 'title' => 'Library', 'points' => ['Digital catalog & borrowing','Fine tracking','E‑books integration']],
  ])
  <section class="py-14 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-center">Core Modules</h2>
      <p class="mt-2 text-slate-600 text-center">Cards animate on scroll. Hover to see more.</p>
      @php($imagePool = collect(glob(public_path('images/*.{jpg,jpeg,png,webp}'), GLOB_BRACE))->map(fn($p) => str_replace(public_path(), '', $p))->shuffle())
      <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($coreModules as $idx => $m)
          @php($img = $imagePool[$idx % max(1, count($imagePool))] ?? null)
          <div class="feature-card reveal rounded-xl border border-slate-200 bg-white overflow-hidden">
            @if($img)
              <img src="{{ $img }}" alt="{{ $m['title'] }} screenshot" class="w-full h-40 object-cover">
            @endif
            <div class="p-6">
              <div class="text-3xl">{{ $m['icon'] }}</div>
              <h3 class="mt-3 text-lg font-semibold">{{ $m['title'] }}</h3>
              <ul class="mt-2 space-y-1 text-sm text-slate-600">
                @foreach($m['points'] as $p)
                  <li>• {{ $p }}</li>
                @endforeach
              </ul>
              <a href="#" class="more mt-4 inline-flex items-center text-indigo-700 font-medium">Learn More →</a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Premium Differentiators -->
  @php($premium = [
    ['emoji' => '🪪', 'title' => 'Biometric/QR Attendance', 'desc' => 'Smart, fast attendance tracking.'],
    ['emoji' => '📱', 'title' => 'Parent & Student Mobile App', 'desc' => 'Real‑time records, fees and results.'],
    ['emoji' => '👥', 'title' => 'HR & Payroll', 'desc' => 'Staff profiles, leave and payslips.'],
    ['emoji' => '🏦', 'title' => 'Finance & Accounting', 'desc' => 'Ledgers, trial balances, bank reconciliation.'],
    ['emoji' => '📊', 'title' => 'Analytics Dashboard', 'desc' => 'Charts for performance, fees and attendance.'],
    ['emoji' => '🏫', 'title' => 'Multi‑School Support', 'desc' => 'Manage multiple campuses in one place.'],
  ])
  <section class="py-14 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-center">Premium Advanced Features</h2>
      <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($premium as $x)
          <div class="reveal rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-3xl">{{ $x['emoji'] }}</div>
            <h3 class="mt-3 text-lg font-semibold">{{ $x['title'] }}</h3>
            <p class="mt-1 text-sm text-slate-600">{{ $x['desc'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Integration Ecosystem -->
  <section class="py-12 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h3 class="text-center text-sm font-semibold text-slate-500">Integrates with your favorite tools</h3>
      <div class="mt-6 overflow-hidden">
        <div class="flex gap-10 whitespace-nowrap text-slate-600 items-center animate-marquee">
          <span class="font-semibold">M‑Pesa</span>
          <span class="font-semibold">PayPal</span>
          <span class="font-semibold">Stripe</span>
          <span class="font-semibold">Google Workspace</span>
          <span class="font-semibold">SMS Gateway</span>
          <span class="font-semibold">Mailgun</span>
          <span class="font-semibold">Firebase</span>
          <span class="font-semibold">AWS S3</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Technology Highlights -->
  <section class="py-14 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-10 items-center">
      <div class="reveal">
        <h2 class="text-2xl font-extrabold">Modern technology you can trust</h2>
        <ul class="mt-3 space-y-2 text-slate-700 text-sm">
          <li>• Cloud‑based & mobile responsive</li>
          <li>• Dark mode support</li>
          <li>• AI‑powered predictions (dropout risk, performance)</li>
          <li>• Scales to thousands of students</li>
        </ul>
      </div>
      <div class="reveal">
        <div class="grid md:grid-cols-2 gap-4">
          <div class="rounded-xl h-40 bg-white shadow-sm ring-1 ring-slate-200 grid place-content-center">📱 Phone mockup</div>
          <div class="rounded-xl h-40 bg-white shadow-sm ring-1 ring-slate-200 grid place-content-center md:col-span-1">🖥️ Desktop mockup</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Comparison Table (optional) -->
  <section class="py-14 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-extrabold text-center">Why choose {{ config('app.name') }}?</h2>
      <div class="overflow-x-auto mt-6">
        <table class="compare text-sm">
          <thead>
            <tr>
              <th class="text-left">Feature</th>
              <th>{{ config('app.name') }}</th>
              <th>Legacy Software</th>
              <th>Excel Sheets</th>
            </tr>
          </thead>
          <tbody>
            <tr><td class="text-left">All‑in‑one modules</td><td>✔</td><td>✖</td><td>✖</td></tr>
            <tr><td class="text-left">Online payments</td><td>✔</td><td>✖</td><td>✖</td></tr>
            <tr><td class="text-left">AI‑assisted workflows</td><td>✔</td><td>✖</td><td>✖</td></tr>
            <tr><td class="text-left">Multi‑school support</td><td>✔</td><td>✖</td><td>✖</td></tr>
            <tr><td class="text-left">Cloud backups</td><td>✔</td><td>✖</td><td>✖</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- Showcase with real screenshots from public/images (randomized) -->
  <section class="py-12 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-extrabold text-center">Feature highlights</h2>
      @php($shots = $imagePool->take(9))
      <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($shots as $s)
          <figure class="rounded-xl overflow-hidden ring-1 ring-slate-200 bg-white shadow-sm">
            <img src="{{ $s }}" alt="Feature screenshot" class="w-full h-48 object-cover" loading="lazy">
          </figure>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Final CTA -->
  <section class="py-12">
    <div class="mx-auto max-w-5xl rounded-2xl px-6 py-10 text-center text-white shadow-lg animated-gradient">
      <h3 class="text-2xl font-bold">One platform. Endless possibilities for your school.</h3>
      <div class="mt-5 flex items-center justify-center gap-3">
        <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg bg-white px-5 py-3 text-slate-900 font-semibold shadow-sm hover:bg-slate-100">Start Free</a>
        <a href="{{ url('/contact') }}" class="inline-flex items-center rounded-lg ring-1 ring-white/70 px-5 py-3 text-white font-semibold hover:bg-white/10">Book a Demo</a>
        <a href="{{ route('login') }}" class="inline-flex items-center rounded-lg ring-1 ring-white/70 px-5 py-3 text-white font-semibold hover:bg-white/10">Login</a>
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
  </script>
  @include('partials.footer')
</body>
</html>