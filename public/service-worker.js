self.addEventListener('install', event => {
  event.waitUntil(
    caches.open('timetable-cache-v1').then(cache => {
      return cache.addAll([
        '/',
        '/manifest.json',
        '/favicon.ico',
        // Add more static assets as needed
      ]);
    })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
}); 