  <meta name="robots" content="noindex,nofollow">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
  <title>Quản trị — {{ config('admin.company_name') }}</title>
  @include('partials.favicon')
  <style>
    @font-face {
      font-family: 'SVN-Gilroy Bold';
      font-style: normal;
      font-weight: 700;
      src: url("{{ asset('fonts/svn-gilroy_semibold.ttf') }}");
    }
    @font-face {
      font-family: 'SVN-Gilroy';
      font-style: normal;
      font-weight: 500;
      src: url("{{ asset('fonts/svn-gilroy_medium.ttf') }}");
    }
  </style>
  @vite('resources/sources/style.scss')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
