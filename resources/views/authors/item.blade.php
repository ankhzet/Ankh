				<div class="cnt-item @trashed($author)">
					<div class="title">
						<span class="head">
							<a href="{{ route('authors.show', $author) }}">{{$author->fio}}</a>
							@admin()
								@i-menu(admin icons)
									@m-item(!edit, route('authors.edit', $author) )
									@m-delete(!trash, route('authors.destroy', $author) )
								@endmenu
							@endadmin
							@samlib($author)
						</span>
					</div>
					<div class="text">
						<div class="author-title">{{ $author->title }}</div>
					</div>
				</div>
