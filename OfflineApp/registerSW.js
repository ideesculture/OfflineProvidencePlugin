if('serviceWorker' in navigator) {window.addEventListener('load', () => {navigator.serviceWorker.register('/offline/sw.js', { scope: '/offline/' })})}