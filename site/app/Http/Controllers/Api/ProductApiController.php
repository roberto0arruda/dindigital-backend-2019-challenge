<?php

namespace App\Http\Controllers\Api;

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
            $product = $this->products::create($request->all());

            return responder()->success($product)->respond(201);
        } catch (\Exception $e) {
            if ( config('app.debug') ) {
                return response()->json(['msg' => $e->getMessage()]);
            }

            return responder()->error('error', 'Erro ao criar')->respond();
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
            return responder()->success($this->products::findOrFail($id))->respond();
        } catch (\Exception $e) {
            if ( config('app.debug') ) {
                return response()->json(['msg' => $e->getMessage()]);
            }

            return responder()->error('error', 'Produto não encontrado')->respond();
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
            $this->products::find($id)->update($request->all());

            return responder()->success($this->products::find($id))->respond(201);
        } catch (\Exception $e) {
            if ( config('app.debug') ) {
                return responder()->error('message', $e->getMessage())->respond();
            }

            return responder()->error('error', 'Erro ao atualizar')->respond();
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

            return responder()->success()->respond(200);
        } catch (\Exception $e) {
            if ( config('app.debug') ) {
                return response()->json(['msg' => $e->getMessage()]);
            }

            return responder()->error('error', 'Erro ao deletar')->respond();
        }
    }
}
