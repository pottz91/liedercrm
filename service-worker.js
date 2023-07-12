// Install event
self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open('my-cache').then(function (cache) {
            return cache.addAll([
                // List of files to be cached
                '/',
                '/styles.css',
                '/script.js',
                '/image.jpg'
            ]);
        })
    );
});

// Fetch event
self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request).then(function (response) {
            if (response) {
                return response;
            }
            return fetch(event.request);
        })
    );
});
