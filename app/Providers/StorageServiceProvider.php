<?php namespace Ankh\Providers;

use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider {

	public function register() {
	}

	public function boot() {
		$this->bootRepositories();
	}

	public function bootRepositories() {
		$this->app->bind('Ankh\Contracts\AuthorRepository', 'Ankh\Entity\AuthorRepositoryEloquent');
		$this->app->bind('Ankh\Contracts\GroupRepository', 'Ankh\Entity\GroupRepositoryEloquent');
		$this->app->bind('Ankh\Contracts\PageRepository', 'Ankh\Entity\PageRepositoryEloquent');
		$this->app->bind('Ankh\Contracts\UpdateRepository', 'Ankh\Entity\UpdateRepositoryEloquent');
	}

}

