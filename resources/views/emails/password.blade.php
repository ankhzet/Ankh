<html>
<body>
	<p>Click <a href="{{ $url = route('password.reset', $token) }}">here</a> to reset your password.</p>
	<p></p>
	<small>
		<p>
			Sombody has requested password reset for this e-mail.<br/>
			If it's not you, please, ignore this message.
		</p>
	</small>
</body>
</html>
