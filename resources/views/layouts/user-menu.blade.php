@if(with($user = Auth::user())->isAdmin())
<span class="ap-admin">
	<a href="#">@lang('admin.functions')</a>
	<div class="ap-drop">
		<ul>
			<li>&#187; <a href="/admin">@lang('admin.page')</a></li>
			<li>&#187; <a href="{{ route('logout') }}">@lang('common.logout')</a></li>
		</ul>
	</div>
</span>
@else
@endif
