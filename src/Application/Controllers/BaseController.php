<?php
/**
 * Created by PhpStorm.
 * User: nartra
 * Date: 28/9/19
 * Time: 1:43 PM
 */

namespace App\Application\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class BaseController
{
    public abstract function index(Request $request, Response $response, $args): Response;

    protected function response404(Response $response, string $msg = "not found")
    {
        $response->getBody()->write(json_encode([
            'status' => 404,
            'msg' => $msg
        ]));
        return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    }

    protected function json(Response $response, $data)
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    protected function getJsonBody(Request $request): array
    {
        return json_decode($request->getBody(), true);
    }

    protected function html(Response $response, $data)
    {
        $response->getBody()->write(strval($data));
        return $response;
    }
}