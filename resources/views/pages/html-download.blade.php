<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset={{ $charset }}" />

	<title>{!! $title !!}</title>
	<style>
		body {
			background-color: #e9e9e9;
			font-family: Tahoma, Verdana, sans-serif;
			font-size: 1.05em;
			padding: 1% 5%;
		}
		footer ul {
			list-style: none;
			text-align: center;
			margin: 20px 0 0 0;
			padding: 0;

			font-family: Tahoma;
			font-size: 0.6em;
			font-weight: bold;

			color: #444;
		}
		p {
			margin: 0 0 18px 10px;
		}
		a {
			color: #246;
		}
		a:hover {
			color: #359;
		}

		.cnt-item {
			padding-bottom: 20px;
			outline-bottom: 1px dotted #eee;
			border-bottom: 1px dotted #fff;
		}
		.cnt-item .text.quote {
			border-left: 5px solid #ccc;
			padding-left: 5px;
			margin-top: 5px;
			color: #444;
		}

		.reader .pre,
		.reader .terminator {
			background-image: url('data:image/png;base64,{!! $img !!}');
			background-repeat: no-repeat;
			margin-top: 20px;
			padding: 0;
		}
		.text.reader .pre {
			background-position: center -16px;
			padding: 20px 0 40px 0;
			white-space: pre-wrap;
		}
		.text.reader .terminator {
			background-position: center 0;
			height: 16px;
		}

	</style>

</head>
<body>

	<div class="wrapper">
		<main id="content" role="main">
			<div>

				<div>
					<div class="cnt-item">
						<div>
							{!! $link !!}
						</div>
						<div class="text quote">
							{!! $annotation !!}
						</div>
					</div>
					<div class="text reader">
						<div class="pre">
							{!! $contents !!}
						</div>
						<div class="terminator"></div>
					</div>
				</div>

			</div>
		</main>
	</div>

	<footer>
		<ul>
			<li>{!! $downloaded !!}</li>
		</ul>
	</footer>

</body>
</html>
