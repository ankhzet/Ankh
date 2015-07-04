<?php

Admin::model('Ankh\Author')->title('Authors')->with()->filters(function ()
{

})->columns(function ()
{
	Column::string('fio', 'Fio');
	Column::string('link', 'Link');
})->form(function ()
{
	FormItem::text('fio', 'Fio');
	FormItem::text('link', 'Link');
	FormItem::timestamp('created_at', 'Created At');
	FormItem::timestamp('deleted_at', 'Deleted At');
});
