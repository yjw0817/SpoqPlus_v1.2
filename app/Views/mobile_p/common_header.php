<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SpoQ Plus 휘트니스</title>
  
  <!-- PWA 설정 -->
  <link rel="manifest" href="/manifest.json">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="SpoQ Plus">
  <link rel="apple-touch-icon" href="/images/icon-192.png">
  <meta name="theme-color" content="#007bff">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  
  <!-- jQuery -->
  <script src="/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="/dist/js/adminlte.min.js"></script>

  <!-- PWA 서비스 워커 등록 -->
  <script>
  if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
          navigator.serviceWorker.register('/sw.js').then(function(registration) {
              console.log('ServiceWorker registration successful');
          }, function(err) {
              console.log('ServiceWorker registration failed: ', err);
          });
      });
  }
  </script>
</head>