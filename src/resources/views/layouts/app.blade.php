<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atte</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}"/>
    @yield('css')
    <!--書体の追加-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
    margin: 0;
    padding: 0;

  font-family: "Noto Sans JP", sans-serif;
  font-optical-sizing: auto;
  font-weight: <weight>;
  font-style: normal;

}
        .header {
            width: 100%;
            height: 10vh;
            background-color: white;
            position: relative;
        }

        .header__inner {
    display: flex; /* Use flexbox for alignment */
    align-items: center; /* Center items vertically */
    justify-content: space-between; /* Space out items */
    width: 100%; /* Ensure full width of header */
    height: 100%; /* Ensure full height of header */
    padding: 0 20px; /* Add some padding */
}

        h1 {
            color: #000;
            position: absolute;
            left: 60px;
            margin:0;
            font-size: 30px;
        }

        main {
            height: 85vh;
            background-color:  rgb(245, 245, 245);
        }

        .footer {
            height: 5vh;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .footer__inner {
            text-align: center;
        }


        p {
            font-size: 10px;
            margin: 0;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <h1>Atte</h1>
            @yield('contact')
        </div>
        
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="footer__inner">
            <p>Atte,inc</p>
        </div>
    </footer>
</body>
</html>