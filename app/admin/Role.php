<?php

Admin::model('Role')->title('Roles')->filters(function ()
{

})->columns(function ()
{
	Column::string('title', 'Title');
	Column::string('label', 'Label');
})->form(function ()
{
	FormItem::text('title', 'Title');
	FormItem::text('label', 'Label');
});
