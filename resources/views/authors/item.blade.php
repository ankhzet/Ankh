				<div class="cnt-item">
					<div class="title">
						<span class="head">
							<a href="{{ route('authors.show', $author) }}">{{$author->fio}}</a>
							@i-menu()
								@m-item('pages.pages.list', route('authors.pages.index', $author) )
								@m-item('pages.authors.details', route('authors.show', $author) )
							@endmenu

							@i-menu()
								@m-item('pages.authors.trace-updates', route('authors.trace-updates', $author) )
							@endmenu

							@admin()
								@i-menu(admin)
									@m-item('common.edit', route('authors.edit', $author) )
									@m-delete('common.delete', route('authors.destroy', $author) )
								@endmenu
							@endadmin
						</span>
					</div>
					<div class="text">
						<span style="float: left; padding-left: 5px;">
							@samlib($author)
						</span>
					</div>
					<br /><br />
				</div>
