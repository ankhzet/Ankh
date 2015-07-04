<?php

Admin::model(Ankh\Group::class)->title('Groups')->with('author')->filters(function ()
{
	ModelItem::filter('author_id')->as('author')->title('of author');
})->columns(function ()
{
	Column::string('author.fio', 'Author')->append(
    Column::filter('author')->value('author.id')
	);
	Column::string('title');
	Column::string('link');
	Column::yesNo('inline');
	Column::string('annotation');
	Column::date('created_at', 'Created At');
})->form(function ()
{
	FormItem::select('author_id', 'Author')->list(Ankh\Author::class);
	FormItem::text('title', 'Title');
	FormItem::text('link', 'Link');
	FormItem::checkbox('inline', 'Inline');
	FormItem::text('annotation', 'Annotation');
});
