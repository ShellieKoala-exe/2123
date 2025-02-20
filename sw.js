// Service Worker Script (sw.js)
const CACHE_NAME = 'login-app-v1';
const ASSETS = [
  '/',
  '/login.html',
  '/instructions.html',
  '/validatePassword.php',
  '/passwords.json',
  '/icon-192x192.png',
  '/icon-512x512.png'
];

// Install the service worker and cache assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(ASSETS))
  );
});

// Serve cached assets when offline
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => response || fetch(event.request))
  );
});
