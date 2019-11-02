<?php
/**
 * Created by PhpStorm.
 * User: nartra
 * Date: 28/9/19
 * Time: 1:48 PM
 */

namespace App\Application\Controllers\Product;

use App\Application\Controllers\BaseController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Service\Product\Product as ProductService;

class Product extends BaseController
{

    private $productService;

    /**
     * Product constructor.
     * @param $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    public function index(Request $request, Response $response, $args): Response
    {
        // TODO: Implement index() method.
    }

    public function listAll(Request $request, Response $response, $args): Response
    {
        $products = $this->productService->getAll()->map([$this, 'mapTimeIso']);

//        $response->getBody()->write($products->toJson());
//        return $response->withHeader('Content-Type', 'application/json');
        return $this->json($response, $products);
    }

    public function get(Request $request, Response $response, $args): Response
    {
        $id = $args['id'];
        $product = $this->productService->get($id);

        if (!$product) return $this->response404($response);

        $product = $this->mapTimeIso($product);

//        $response->getBody()->write(json_encode($product));
//        return $response->withHeader('Content-Type', 'application/json');
        return $this->json($response, $product);
    }

    public function mapTimeIso($item)
    {
        $item->create_at = date("c", $item->create_at);
        $item->update_at = date("c", $item->update_at);
        return $item;
    }

    public function create(Request $request, Response $response): Response
    {
        $data = $this->getJsonBody($request);
        $this->productService->create($data['name'], $data['price'], $data['stock']);
        return $this->json($response, $data);

    }

    public function update(Request $request, Response $response, $args){
        $id = $args['id'];
        $data = $this->getJsonBody($request);
        $name = $data['name'] ?? null;
        $price = $data['price'] ?? null;
        $stock = $data['stock'] ?? null;
        $this->productService->update($id,$name,$price,$stock);

        $product = $this->productService->get($id);

        if(!$product){
            return $this->response404($response);
        }

        return $this->json($response, $product);
    }

    public function delete(Request $request, Response $response, $args){
        $id = $args['id'];
        $product = $this->productService->get($id);

        if(!$product){
            return $this->response404($response);
        }

        $this->productService->delete($id);
        return $this->json($response, $product);

    }
}