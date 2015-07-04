<?php

Admin::model(Ankh\Page::class)->title('Pages')->with('author', 'group')->filters(function ()
{
	ModelItem::filter('author_id')->as('author')->title('of author');
	ModelItem::filter('group_id')->as('group')->title('of group');
})->columns(function ()
{
	Column::string('author.fio', 'Author')->append(
		Column::url('author_id', 'Edit')->formatted(function ($id) {
			return "authors/{$id}/edit";
		}),
    Column::filter('author')->value('author.id')
	);
	Column::string('group.title', 'Group')->append(
		Column::url('group_id', 'Edit')->formatted(function ($id) {
			return "groups/{$id}/edit";
		}),
    Column::filter('group')->value('group.id')
	);
	Column::string('title');
	Column::string('link');
	Column::string('annotation');
	Column::size('size');
	Column::date('created_at', 'Created At');
})->form(function ()
{
	FormItem::select('author_id', 'Author')->list(Ankh\Author::class);
	FormItem::select('group_id', 'Group')->list(Ankh\Group::class)->relative();
	FormItem::text('title', 'Title');
	FormItem::text('link', 'Link');
	FormItem::text('annotation', 'Annotation');
});
