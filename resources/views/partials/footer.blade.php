<!-- CTA Above Footer -->
<section class="py-8 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-4 items-center">
    <div>
      <div class="text-lg font-semibold">Ready to simplify your school operations? 🚀</div>
      <p class="text-white/90">Start today with {{ config('app.name') }}.</p>
    </div>
    <div class="flex md:justify-end gap-3">
      <a href="{{ url('/contact') }}" class="inline-flex items-center rounded-lg bg-white px-5 py-3 text-slate-900 font-semibold shadow-sm hover:bg-slate-100">Request Demo</a>
      <a href="{{ route('register') }}" class="inline-flex items-center rounded-lg ring-1 ring-white/70 px-5 py-3 text-white font-semibold hover:bg-white/10">Get Started</a>
    </div>
  </div>
</section>

<!-- Main Footer -->
<footer class="bg-[#0D1B2A] text-white py-12">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
      <!-- Column 1: Branding -->
      <div>
        <div class="text-xl font-extrabold">{{ config('app.name') }}</div>
        <p class="mt-2 text-sm text-slate-300">Run multi‑school operations with speed, clarity, and security.</p>
        <p class="mt-3 text-sm text-slate-400">A unified platform for admissions, finance, exams, library, communication and more.</p>
        <div class="mt-4 flex gap-3 text-slate-300">
          <a href="#" aria-label="LinkedIn" class="hover:text-white">in</a>
          <a href="#" aria-label="Facebook" class="hover:text-white">f</a>
          <a href="#" aria-label="Instagram" class="hover:text-white">◎</a>
          <a href="#" aria-label="Twitter" class="hover:text-white">𝕏</a>
        </div>
      </div>

      <!-- Column 2: Quick Links -->
      <div>
        <div class="font-semibold">Quick Links</div>
        <ul class="mt-3 space-y-2 text-sm text-slate-300">
          <li><a href="/" class="hover:underline">Home</a></li>
          <li><a href="{{ route('features') }}" class="hover:underline">Features</a></li>
          <li><a href="{{ route('about') }}" class="hover:underline">About Us</a></li>
          <li><a href="{{ route('contact') }}" class="hover:underline">Contact</a></li>
          <li><a href="#" class="hover:underline">Demo / Pricing</a></li>
          <li><a href="#" class="hover:underline">Blog / Resources</a></li>
        </ul>
      </div>

      <!-- Column 3: Core Modules -->
      <div>
        <div class="font-semibold">Core Modules</div>
        <ul class="mt-3 space-y-2 text-sm text-slate-300">
          <li>🎓 Admissions & Enrollment</li>
          <li>💰 Finance & Fees</li>
          <li>📚 Library Management</li>
          <li>📝 Exams & Results</li>
          <li>👩‍🏫 HR & Staff</li>
          <li>🔔 Notifications & Communication</li>
          <li>📊 Reports & Analytics</li>
        </ul>
      </div>

      <!-- Column 4: Support & Legal -->
      <div>
        <div class="font-semibold">Support & Legal</div>
        <ul class="mt-3 space-y-2 text-sm text-slate-300">
          <li><a href="#" class="hover:underline">Help Center / FAQs</a></li>
          <li><a href="#" class="hover:underline">Documentation</a></li>
          <li><a href="{{ route('privacy') }}" class="hover:underline">Privacy Policy</a></li>
          <li><a href="#" class="hover:underline">Terms & Conditions</a></li>
          <li><a href="#" class="hover:underline">Refund Policy</a></li>
          <li><a href="#" class="hover:underline">Service Level Agreement</a></li>
        </ul>
      </div>
    </div>

    <hr class="my-8 border-white/10">
    <div class="text-sm text-slate-300 grid sm:grid-cols-2 gap-2">
      <div>
        📍 Nairobi, Kenya · 📧 info@duncoweb.co.ke · 📞 +254 746 979 588
      </div>
      <div class="sm:text-right">
        © {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved. <span class="text-slate-400">Designed & Developed by Dunco Web Solutions</span>
      </div>
    </div>
  </div>
</footer>


