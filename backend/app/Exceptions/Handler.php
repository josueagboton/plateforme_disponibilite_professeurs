<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Rendre une réponse HTTP appropriée pour l'exception donnée.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            // Si c'est une erreur 405 Method Not Allowed, renvoyer une réponse JSON personnalisée
            return response()->json([
                'error' => true,
                'message' => 'La méthode HTTP utilisée n\'est pas autorisée pour cette ressource.',
                'allowed_methods' => $this->getAllowedMethods($request)
            ], 405);
        }

        return parent::render($request, $exception);
    }

    /**
     * Retourne les méthodes HTTP autorisées pour la route donnée.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function getAllowedMethods(Request $request)
    {
        // Cette fonction retourne les méthodes HTTP autorisées pour la route actuelle
        $route = $request->route();
        if ($route) {
            return $route->methods();
        }
        return [];
    }
}
