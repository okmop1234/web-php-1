<?php

namespace App\Service\Product;
/**
 * Created by PhpStorm.
 * User: nartra
 * Date: 28/9/19
 * Time: 12:24 PM
 */

use function foo\func;
use Illuminate\Database\Capsule\Manager as DB;
use PhpParser\Node\Expr\Closure;

class Product
{
    public function __construct(DB $db)
    {
    }

    public function getAll()
    {
        return DB::table('product')->get()->map([$this, 'mapReadData']);
    }

    public function get(int $id)
    {
        $row = DB::table('product')->where('id', '=', $id)->first();
        if (!$row) return null;
        return $this->mapReadData($row);
    }

    public function create(string $name, float $price, int $stock)
    {
        DB::table('product')->insert([
            'name' => $name,
            'price' => $price,
            'stock' => $stock,
            'create_at' => date(),
            'update_at' => date(),
        ]);
    }

//    public function update(int $id, string $name, float $price, int $stock)
//    {
//        DB::table('product')->where('id', '=', $id)->update([
//            'name' => $name,
//            'price' => $price,
//            'stock' => $stock,
//            'update_at' => date("Y-m-d H:i:s"),
////            'update_at'=> DB::raw("now()"),
//        ]);
//    }

    public function update(int $id, ?string $name, ?float $price, ?int $stock)
    {
        $values = [
            'string' => $name,
            'price' => $price,
            'stock' => $stock,
            'update_at'=> date("Y-m-d H:i:s"),
        ];

//        $values2 =[
//
//        ];
//
//        foreach ($values as $key => $val){
//            if(!is_null($val)) {
//                $values2[$key] = $val;
//            }
//        }
        $values2 = array_filter($values, function($val){
            return !is_null($val);
        });

        DB::table('product')->where('id', '=', $id)->update($values2);
    }

    public function delete(int $id){
        DB::table('product')->where('id','=', $id)->delete();
    }

    public function mapReadData($item)
    {
        $item->create_at = strtotime($item->create_at);
        $item->update_at = strtotime($item->update_at);
        return $item;
    }
}