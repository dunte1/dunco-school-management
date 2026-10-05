<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Privacy Policy — {{ config('app.name') }}</title>
  @vite('resources/css/app.css')
  <style>
    .toc a{color:#4f46e5}
    .section{scroll-margin-top:80px}
    .reveal{opacity:0;transform:translateY(8px);transition:opacity .4s ease,transform .4s ease}
    .reveal.show{opacity:1;transform:none}
  </style>
</head>
<body class="bg-white text-slate-900">
  @include('partials.header')

  <main class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-extrabold mb-2">Privacy Policy</h1>
    <p class="text-slate-600 mb-6">Last updated: {{ now()->toDateString() }}</p>

    <nav class="toc mb-8 text-sm">
      <div class="font-semibold mb-2">On this page</div>
      <ul class="list-disc pl-5 grid sm:grid-cols-2 gap-y-1">
        <li><a href="#intro">1. Introduction</a></li>
        <li><a href="#collect">2. Information We Collect</a></li>
        <li><a href="#use">3. How We Use Your Information</a></li>
        <li><a href="#share">4. How We Share Your Information</a></li>
        <li><a href="#security">5. Data Security</a></li>
        <li><a href="#retention">6. Data Retention</a></li>
        <li><a href="#rights">7. User Rights</a></li>
        <li><a href="#cookies">8. Cookies & Tracking</a></li>
        <li><a href="#children">9. Children’s Privacy</a></li>
        <li><a href="#transfers">10. International Data Transfers</a></li>
        <li><a href="#changes">11. Changes to This Policy</a></li>
        <li><a href="#contact">12. Contact Us</a></li>
      </ul>
    </nav>

    <section id="intro" class="section reveal mb-6">
      <h2 class="text-xl font-bold mb-2">1. Introduction</h2>
      <p>At {{ config('app.name') }} ("we," "our," or "us"), your privacy is very important to us. This Privacy Policy explains how we collect, use, and protect your personal and institutional data when you use our services.</p>
    </section>

    <section id="collect" class="section reveal mb-6">
      <h2 class="text-xl font-bold mb-2">2. Information We Collect</h2>
      <ul class="list-disc pl-5 space-y-1">
        <li><strong>School Data</strong>: Institution name, registration details.</li>
        <li><strong>User Data</strong>: Names, emails, phone numbers, roles (admin, teacher, parent, student).</li>
        <li><strong>Student Data</strong>: Enrollment records, grades, fees, attendance, exam results.</li>
        <li><strong>Financial Data</strong>: Payments, invoices, transaction records.</li>
        <li><strong>Technical Data</strong>: Device type, IP address, browser, cookies.</li>
      </ul>
    </section>

    <section id="use" class="section reveal mb-6">
      <h2 class="text-xl font-bold mb-2">3. How We Use Your Information</h2>
      <ul class="list-disc pl-5 space-y-1">
        <li>To provide and improve system features (admissions, fees, exams, library, etc.).</li>
        <li>To process payments and generate invoices.</li>
        <li>To send system notifications (e.g., fee reminders, exam updates).</li>
        <li>To ensure security and prevent fraud.</li>
        <li>To comply with legal obligations.</li>
      </ul>
    </section>

    <section id="share" class="section reveal mb-6">
      <h2 class="text-xl font-bold mb-2">4. How We Share Your Information</h2>
      <p>We do not sell or rent data. We may share data only with:</p>
      <ul class="list-disc pl-5 space-y-1">
        <li>Authorized school administrators (controlled by your school).</li>
        <li>Third-party service providers (e.g., SMS gateways, payment processors).</li>
        <li>Legal authorities if required by law.</li>
      </ul>
    </section>

    <section id="security" class="section reveal mb-6">
      <h2 class="text-xl font-bold mb-2">5. Data Security</h2>
      <ul class="list-disc pl-5 space-y-1">
        <li>End-to-end encryption for data transfer.</li>
        <li>Secure cloud hosting with backups.</li>
        <li>Role-based access controls.</li>
        <li>Regular security audits.</li>
      </ul>
    </section>

    <section id="retention" class="section reveal mb-6">
      <h2 class="text-xl font-bold mb-2">6. Data Retention</h2>
      <p>We retain school and student data only as long as necessary to provide services. Schools can request export or deletion of data at any time.</p>
    </section>

    <section id="rights" class="section reveal mb-6">
      <h2 class="text-xl font-bold mb-2">7. User Rights</h2>
      <p>Depending on your jurisdiction (e.g., GDPR, Kenya Data Protection Act), you may have rights to access, correct, delete, restrict processing, object to processing, or request data portability. Schools should contact <a class="text-indigo-600" href="mailto:info@duncoweb.co.ke">info@duncoweb.co.ke</a>.</p>
    </section>

    <section id="cookies" class="section reveal mb-6">
      <h2 class="text-xl font-bold mb-2">8. Cookies & Tracking</h2>
      <p>We use cookies to enhance user experience (login sessions, theme preferences). You may disable cookies in your browser, but some features may stop working.</p>
    </section>

    <section id="children" class="section reveal mb-6">
      <h2 class="text-xl font-bold mb-2">9. Children’s Privacy</h2>
      <p>The platform may store student data provided by schools. We do not directly collect personal information from minors without school/parental consent.</p>
    </section>

    <section id="transfers" class="section reveal mb-6">
      <h2 class="text-xl font-bold mb-2">10. International Data Transfers</h2>
      <p>Data may be stored in cloud servers outside your country. We ensure safeguards and compliance with local data protection laws.</p>
    </section>

    <section id="changes" class="section reveal mb-6">
      <h2 class="text-xl font-bold mb-2">11. Changes to This Policy</h2>
      <p>We may update this Privacy Policy from time to time. Any changes will be communicated via email or system notifications.</p>
    </section>

    <section id="contact" class="section reveal mb-10">
      <h2 class="text-xl font-bold mb-2">12. Contact Us</h2>
      <p>Email: <a class="text-indigo-600" href="mailto:info@duncoweb.co.ke">info@duncoweb.co.ke</a><br/>Phone: +254 746 979 588</p>
    </section>

    <p class="text-sm text-slate-500">By using {{ config('app.name') }}, you agree to this Privacy Policy.</p>
  </main>

  <script>
    (function(){
      var items = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
      if(!items.length || !('IntersectionObserver' in window)) return items.forEach(function(i){ i.classList.add('show'); });
      var io = new IntersectionObserver(function(entries){
        entries.forEach(function(e){ if(e.isIntersecting){ e.target.classList.add('show'); io.unobserve(e.target); } });
      }, { threshold: 0.1 });
      items.forEach(function(i){ io.observe(i); });
    })();
  </script>
</body>
</html>


