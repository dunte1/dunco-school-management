<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact — {{ config('app.name') }}</title>
  @vite('resources/css/app.css')
  <style>
    .reveal{opacity:0;transform:translateY(12px);transition:opacity .5s ease,transform .5s ease}
    .reveal.show{opacity:1;transform:none}
    .animated-gradient{background:linear-gradient(90deg,#4F46E5,#9333EA);background-size:200% 200%;animation:grad-move 8s ease-in-out infinite}
    @keyframes grad-move{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
    .shape{position:absolute;border-radius:9999px;opacity:.25;filter:blur(2px)}
    .faq summary{cursor:pointer}
    .faq details{border:1px solid #e5e7eb;border-radius:.75rem;padding:.8rem 1rem;background:#fff}
    .faq details[open]{box-shadow:0 6px 20px rgba(2,6,23,.06)}
    .icon-bounce{display:inline-block;animation:bounce 2s infinite}
    @keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-3px)}}
  </style>
</head>
<body class="text-slate-800 bg-white">
  @include('partials.header')

  <!-- Hero -->
  <section class="relative overflow-hidden bg-slate-50">
    <div class="absolute -top-10 -left-10 shape w-40 h-40 bg-indigo-300"></div>
    <div class="absolute bottom-0 -right-8 shape w-56 h-56 bg-purple-300"></div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14 text-center">
      <h1 class="text-3xl sm:text-4xl font-extrabold reveal">We’d love to hear from you.</h1>
      <p class="mt-3 text-slate-600 reveal">Whether you’re a school administrator, teacher, or parent — our team is here to help.</p>
      <div class="mt-4 text-2xl text-indigo-600">
        <span class="icon-bounce">💬</span> <span class="icon-bounce" style="animation-delay:.2s">📧</span> <span class="icon-bounce" style="animation-delay:.4s">🎧</span>
      </div>
    </div>
  </section>

  <!-- Contact Form + Direct Info -->
  <section class="py-12 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-8">
      <form id="contactForm" method="POST" action="{{ route('contact.submit') }}" class="reveal rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @if(session('contact_ok'))
          <p class="mb-3 text-sm text-emerald-600">Thanks! Our team will respond within 24 hours.</p>
        @endif
        @csrf
        <label class="block text-sm font-medium">Name</label>
        <input name="name" type="text" class="mt-1 w-full rounded-lg border-slate-300" value="{{ old('name') }}" required>
        <label class="block text-sm font-medium mt-4">Email</label>
        <input name="email" type="email" class="mt-1 w-full rounded-lg border-slate-300" value="{{ old('email') }}" required>
        <label class="block text-sm font-medium mt-4">Subject</label>
        <select name="subject" class="mt-1 w-full rounded-lg border-slate-300" required>
          <option value="Sales">Sales</option>
          <option value="Support">Support</option>
          <option value="Demo">Demo</option>
          <option value="Partnerships">Partnerships</option>
        </select>
        <label class="block text-sm font-medium mt-4">Message</label>
        <textarea name="message" rows="5" class="mt-1 w-full rounded-lg border-slate-300" required>{{ old('message') }}</textarea>
        <button class="mt-5 inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-3 text-white font-semibold shadow-sm hover:from-indigo-700 hover:to-purple-700 hover:scale-[1.02] transition-transform">Send Message</button>
        @error('name')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        @error('email')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        @error('subject')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
        @error('message')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror
      </form>

      <div class="reveal rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h2 class="font-semibold">Contact Information</h2>
        <ul class="mt-3 space-y-2 text-sm text-slate-700">
          <li>📧 info@duncowebsolutions.co.ke</li>
          <li>📞 +254 746 979 588</li>
          <li>⏰ Mon–Fri, 9 AM – 6 PM (EAT)</li>
          <li>📍 Mutomo, Kitui County, Kenya</li>
        </ul>
        <div class="mt-4 h-64 w-full rounded-lg overflow-hidden ring-1 ring-slate-200">
          <iframe title="map" class="h-full w-full" src="https://maps.google.com/maps?q=Mutomo%20Kitui%20County%20Kenya&t=&z=13&ie=UTF8&iwloc=&output=embed" loading="lazy"></iframe>
        </div>
        <div class="mt-4 text-slate-600 text-sm">Follow us:
          <div class="mt-2 flex gap-3 text-xl">
            <a href="#" aria-label="LinkedIn" class="icon-bounce">in</a>
            <a href="#" aria-label="Facebook" class="icon-bounce">f</a>
            <a href="#" aria-label="YouTube" class="icon-bounce">▶</a>
            <a href="#" aria-label="Twitter" class="icon-bounce">𝕏</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Preview -->
  <section class="py-12 bg-slate-50">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-extrabold text-center">Quick Help</h2>
      <div class="mt-6 grid gap-4 faq">
        <details class="reveal"><summary class="font-medium">How do I request a demo?</summary><div class="mt-2 text-sm text-slate-600">Use the contact form and select "Demo" as the subject, or click Book a Demo.</div></details>
        <details class="reveal"><summary class="font-medium">Do you support multiple schools/campuses?</summary><div class="mt-2 text-sm text-slate-600">Yes. {{ config('app.name') }} supports multi-school with isolated data per campus.</div></details>
        <details class="reveal"><summary class="font-medium">What payment methods do you accept?</summary><div class="mt-2 text-sm text-slate-600">M‑Pesa, PayPal, Stripe and bank integrations are available.</div></details>
      </div>
      <div class="mt-6 text-center">
        <a href="#" class="inline-flex items-center rounded-lg px-5 py-3 font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-300 hover:bg-indigo-50">Visit Full Help Center</a>
      </div>
    </div>
  </section>

  <!-- Final CTA -->
  <section class="py-12">
    <div class="mx-auto max-w-5xl rounded-2xl px-6 py-10 text-center text-white shadow-lg animated-gradient">
      <h3 class="text-2xl font-bold">Have questions? Let’s make education seamless together.</h3>
      <div class="mt-5 flex items-center justify-center gap-3">
        <a href="{{ url('/contact') }}" class="inline-flex items-center rounded-lg bg-white px-5 py-3 text-slate-900 font-semibold shadow-sm hover:bg-slate-100">Book a Demo</a>
        <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg ring-1 ring-white/70 px-5 py-3 text-white font-semibold hover:bg-white/10">Create Account</a>
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
    // no client override; server handles flash success
  </script>
  @include('partials.footer')
</body>
</html>