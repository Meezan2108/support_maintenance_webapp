<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />


    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <script src="{{ mix('/js/app.js') }}" defer></script>


    <link href="{{ url('/assets/css/sb-admin/styles.css') }}" rel="stylesheet" />
    <link href="{{ url('/assets/vendor/fa/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ url('/assets/css/admin.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.2/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @inertiaHead
    @routes
  </head>
  <body>
    @inertia
    
  </body>

  <script src="{{ url('/assets/js/sb-admin/scripts.js') }}"></script>
</html>
