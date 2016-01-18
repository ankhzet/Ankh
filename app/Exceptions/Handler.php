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

			if (config('app.debug')) { 
				if ($request->ajax())
					$handler = new \Whoops\Handler\JsonResponseHandler;
				else {
					$handler = new \Whoops\Handler\PrettyPageHandler;

					$handler->setEditor('sublime');
				}

				$whoops = new \Whoops\Run;
				$whoops->pushHandler($handler);

					$whoops->allowQuit(false);
					$whoops->writeToOutput(false);

				$status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;
				$headers = $e instanceof HttpExceptionInterface ? $e->getHeaders() : [];

				$response = response($whoops->handleException($e), $status, $headers);

			} else
				$response = parent::render($request, $e);

			if (Subdomens::is('api'))
				$response = Api::addCORSHeaders($response);

			return $response;
		}

	}
