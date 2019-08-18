<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;

class ProductApiController extends Controller
{
    protected $products;

    public function __construct(Product $product)
    {
        $this->products = $product;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return responder()->success($this->products->all())->only('id', 'nome', 'preco', 'peso')->respond();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->products::create($request->all());

            return responder()->success('message', 'Criado com sucesso!')->respond(201);
        } catch (\Exception $e) {
            if ( config('app.debug') ) {
                return response()->json(['msg' => $e->getMessage()]);
            }

            return responder()->error('message', 'Erro ao criar')->respond();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return responder()->success($this->products::find($id))->respond();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $atualizado = $this->products::find($id)->update($request->all());

            return responder()->success($atualizado)->respond(201);
        } catch (\Exception $e) {
            if ( config('app.debug') ) {
                return responder()->error('message', $e->getMessage())->respond();
            }

            return responder()->error('message', 'Erro ao atualizar')->respond();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->products::destroy($id);

            return responder()->success('message', 'Deletado com sucesso')->respond(200);
        } catch (\Exception $e) {
            if ( config('app.debug') ) {
                return response()->json(['msg' => $e->getMessage()]);
            }

            return responder()->error('message', 'Erro ao deletar')->respond();
        }
    }
}
