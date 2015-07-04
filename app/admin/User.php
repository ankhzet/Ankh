<?php

Admin::model('User')->title('Users')->with('roles')->filters(function ()
{
})->columns(function ()
{
	Column::string('name', 'Name');
	Column::string('email', 'E-mail');
	Column::string('email', 'E-mail');
	Column::date('created_at', 'Created');
	Column::lists('roles.title', 'Roles');
})->denyDeleting(function ($instance) {
	return $instance->isAdmin() && Role::find(Role::ADMIN)->users->count() <= 1;
})->form(function ()
{
	FormItem::text('name', 'Name');
	FormItem::text('email', 'Email');
	FormItem::multiselect('roles', 'Roles')->list(Role::class)->value('roles.id');
	FormItem::timestamp('created_at', 'Created At');
	FormItem::timestamp('deleted_at', 'Deleted At');
});
