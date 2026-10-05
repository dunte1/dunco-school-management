// Firebase configuration and initialization
import { initializeApp } from 'firebase/app';
import { getMessaging, getToken, onMessage } from 'firebase/messaging';

// Firebase configuration - replace with your actual config
const firebaseConfig = {
  apiKey: process.env.MIX_FIREBASE_API_KEY,
  authDomain: process.env.MIX_FIREBASE_AUTH_DOMAIN,
  projectId: process.env.MIX_FIREBASE_PROJECT_ID,
  storageBucket: process.env.MIX_FIREBASE_STORAGE_BUCKET,
  messagingSenderId: process.env.MIX_FIREBASE_MESSAGING_SENDER_ID,
  appId: process.env.MIX_FIREBASE_APP_ID,
  measurementId: process.env.MIX_FIREBASE_MEASUREMENT_ID
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

// Initialize Firebase Cloud Messaging
let messaging = null;
try {
  messaging = getMessaging(app);
} catch (error) {
  console.warn('Firebase Messaging not supported in this environment:', error);
}

// Function to request notification permission and get FCM token
export async function requestNotificationPermission() {
  if (!messaging) {
    console.warn('Firebase Messaging not initialized');
    return null;
  }

  try {
    // Request permission
    const permission = await Notification.requestPermission();
    
    if (permission === 'granted') {
      console.log('Notification permission granted.');
      
      // Get FCM token
      const token = await getToken(messaging, {
        vapidKey: process.env.MIX_FIREBASE_VAPID_KEY
      });
      
      return token;
    } else {
      console.log('Unable to get permission to notify.');
      return null;
    }
  } catch (error) {
    console.error('Error requesting notification permission:', error);
    return null;
  }
}

// Function to handle foreground messages
export function handleForegroundMessages() {
  if (!messaging) return;
  
  onMessage(messaging, (payload) => {
    console.log('Message received in foreground:', payload);
    
    // Customize notification handling based on your needs
    const notificationTitle = payload.notification?.title || 'New Notification';
    const notificationOptions = {
      body: payload.notification?.body || '',
      icon: payload.notification?.icon || '/icons/icon-192x192.png',
      data: payload.data || {}
    };
    
    // Show notification if browser supports it
    if (window.Notification && Notification.permission === 'granted') {
      new Notification(notificationTitle, notificationOptions);
    }
    
    // Dispatch custom event for app to handle
    window.dispatchEvent(new CustomEvent('firebase-message', {
      detail: { title: notificationTitle, options: notificationOptions, data: payload.data }
    }));
  });
}

// Function to register device with backend
export async function registerDeviceWithBackend(fcmToken) {
  if (!fcmToken) return;
  
  try {
    const response = await fetch('/api/mobile/v1/device/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        token: fcmToken,
        platform: 'web',
        device_id: 'web-browser-' + Date.now(),
        app_version: '1.0.0',
        firebase_token: fcmToken
      })
    });
    
    if (!response.ok) {
      throw new Error('Failed to register device with backend');
    }
    
    const data = await response.json();
    console.log('Device registered with backend:', data);
    return data;
  } catch (error) {
    console.error('Error registering device with backend:', error);
    return null;
  }
}

// Function to unregister device with backend
export async function unregisterDeviceWithBackend(fcmToken) {
  if (!fcmToken) return;
  
  try {
    const response = await fetch('/api/mobile/v1/device/unregister', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        token: fcmToken
      })
    });
    
    if (!response.ok) {
      throw new Error('Failed to unregister device with backend');
    }
    
    const data = await response.json();
    console.log('Device unregistered with backend:', data);
    return data;
  } catch (error) {
    console.error('Error unregistering device with backend:', error);
    return null;
  }
}

// Initialize Firebase messaging when the app loads
export function initializeFirebaseMessaging() {
  if (!messaging) return;
  
  // Handle foreground messages
  handleForegroundMessages();
  
  // Request notification permission and register device
  requestNotificationPermission().then(async (token) => {
    if (token) {
      console.log('FCM Token:', token);
      
      // Register device with backend
      await registerDeviceWithBackend(token);
    }
  });
}

export { app, messaging };