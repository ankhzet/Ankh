<?php

namespace Ankh\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Ankh\Http\Middleware\Api;
use Ankh\Http\Middleware\Subdomens;

class Handler extends ExceptionHandler
{
		/**
		 * A list of the exception types that should not be reported.
		 *
		 * @var array
		 */
		protected $dontReport = [
		\Symfony\Component\HttpKernel\Exception\HttpException::class,
		];

		/**
		 * Report or log an exception.
		 *
		 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
		 *
		 * @param  \Exception  $e
		 * @return void
		 */
		public function report(Exception $e)
		{
			return parent::report($e);
		}

		/**
		 * Render an exception into an HTTP response.
		 *
		 * @param  \Illuminate\Http\Request  $request
		 * @param  \Exception  $e
		 * @return \Illuminate\Http\Response
		 */
		public function render($request, Exception $e)
		{
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->view('errors.404');
            }

			$debug = config("app.debug");
			$debugbar = $debug && (config("debugbar.enabled") !== false) && !$request->ajax();

			if (app()->environment('local')) {
				if ($request->ajax())
					$handler = new \Whoops\Handler\JsonResponseHandler;
				else {
					$handler = new \Whoops\Handler\PrettyPageHandler;

					$handler->setEditor('sublime');
				}

				$whoops = new \Whoops\Run;
				$whoops->pushHandler($handler);

				$whoops->allowQuit(false);
				$whoops->writeToOutput(true);

				$status = 500;
				$headers = [];

				if ($e instanceof HttpExceptionInterface) {
					$status = $e->getStatusCode();
					$headers = $e->getHeaders();
				}

				$response = response($whoops->handleException($e), $status, $headers);
			} else
				$response = parent::render($request, $e);

			if ($debugbar) {
				$debugbar = app()->make('debugbar');
				$debugbar->boot();
				$debugbar->addException($e);

				$response = $debugbar->modifyResponse($request, $response);
			}

			if (Subdomens::is('api'))
				$response = Api::addCORSHeaders($response);

			return $response;
		}

	}
