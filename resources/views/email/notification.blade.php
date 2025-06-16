<!DOCTYPE html>
<html>
<head>
	<title>{{ $title }}</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
		body {
			font-family: Arial, sans-serif;
			font-size: 16px;
			line-height: 1.4;
			color: #333333;
			background-color: #dddddd!important;
			margin: 0;
			padding: 0;
		}

		.container {
			max-width: 600px;
			margin: 0 auto;
			padding: 20px;
			background-color: #ffffff;
			border: 1px solid #cccccc;
			box-shadow: 0 0 10px rgba(0,0,0,0.1);
			border-radius: 5px;
		}

		h1 {
			font-size: 24px;
			margin-bottom: 20px;
			text-align: center;
		}

		p {
			margin-bottom: 20px;
		}

		.button {
			display: inline-block;
			padding: 10px 20px;
			background-color: #28A745;
			color: #fff!important;
			border-radius: 5px;
			text-decoration: none;
		}
	</style>
</head>
<body>
	<div class="container">
		<h1>{{ $title }}</h1>

        <p>
            Dear {{ $user }},
        </p>

        <p>
            {!! $content !!}
        </p>

		@if($link)
		<p >
			if you can't click the link, copy the URL to visit the page <br />

			<a href="{{ $link }}">{{ $link }}</a>
		</p>
		@endif

		<p>
            If you have any questions or need further assistance,
            please contact our customer support team at info@koko.go.my.
        </p>

		<p>Thank you,<br>CRIMS V2.0</p>
	</div>
</body>
</html>
