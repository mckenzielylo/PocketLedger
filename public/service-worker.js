const CACHE_NAME = 'pocketledger-v1';
const urlsToCache = [
    '/',
    '/dashboard',
    '/reports',
    '/css/app.css',
    '/js/app.js',
    '/manifest.webmanifest'
];

// Install event - cache app shell
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

// Fetch event - serve from cache when offline
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Return cached version or fetch from network
                if (response) {
                    return response;
                }
                
                // Clone the request because it's a stream
                const fetchRequest = event.request.clone();
                
                return fetch(fetchRequest).then(response => {
                    // Check if we received a valid response
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }
                    
                    // Clone the response because it's a stream
                    const responseToCache = response.clone();
                    
                    caches.open(CACHE_NAME)
                        .then(cache => {
                            // Cache API responses for offline use
                            if (event.request.url.includes('/api/')) {
                                cache.put(event.request, responseToCache);
                            }
                        });
                    
                    return response;
                });
            })
            .catch(() => {
                // Return offline page for navigation requests
                if (event.request.mode === 'navigate') {
                    return caches.match('/dashboard');
                }
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Background sync for offline data
self.addEventListener('sync', event => {
    if (event.tag === 'background-sync') {
        event.waitUntil(doBackgroundSync());
    }
});

// Handle push notifications
self.addEventListener('push', event => {
    const options = {
        body: event.data ? event.data.text() : 'New notification from PocketLedger',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-72x72.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'View',
                icon: '/icons/icon-72x72.png'
            },
            {
                action: 'close',
                title: 'Close',
                icon: '/icons/icon-72x72.png'
            }
        ]
    };
    
    event.waitUntil(
        self.registration.showNotification('PocketLedger', options)
    );
});

// Handle notification clicks
self.addEventListener('notificationclick', event => {
    event.notification.close();
    
    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/dashboard')
        );
    }
});

// Background sync function
async function doBackgroundSync() {
    try {
        // Sync offline transactions
        const offlineTransactions = await getOfflineTransactions();
        
        for (const transaction of offlineTransactions) {
            try {
                await syncTransaction(transaction);
                await removeOfflineTransaction(transaction.id);
            } catch (error) {
                console.error('Failed to sync transaction:', error);
            }
        }
        
        // Update cache with fresh data
        await updateCache();
        
    } catch (error) {
        console.error('Background sync failed:', error);
    }
}

// Get offline transactions from IndexedDB
async function getOfflineTransactions() {
    // This would typically use IndexedDB to get offline data
    // For now, return empty array
    return [];
}

// Sync a single transaction
async function syncTransaction(transaction) {
    const response = await fetch('/api/transactions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify(transaction)
    });
    
    if (!response.ok) {
        throw new Error('Failed to sync transaction');
    }
    
    return response.json();
}

// Remove offline transaction after successful sync
async function removeOfflineTransaction(id) {
    // This would typically remove from IndexedDB
    console.log('Removed offline transaction:', id);
}

// Update cache with fresh data
async function updateCache() {
    try {
        const cache = await caches.open(CACHE_NAME);
        
        // Update dashboard cache
        const dashboardResponse = await fetch('/dashboard');
        if (dashboardResponse.ok) {
            await cache.put('/dashboard', dashboardResponse);
        }
        
        // Update reports cache
        const reportsResponse = await fetch('/reports');
        if (reportsResponse.ok) {
            await cache.put('/reports', reportsResponse);
        }
        
    } catch (error) {
        console.error('Failed to update cache:', error);
    }
}

// Get CSRF token from meta tag
function getCsrfToken() {
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    return metaTag ? metaTag.getAttribute('content') : '';
}
