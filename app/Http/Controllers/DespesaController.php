<?php

namespace App\Http\Controllers;

use App\Mail\NovaDespesa;
use App\Models\Despesa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;


class DespesaController extends Controller
{
    private $validaDespesa = [
        'usuario_id' => 'required|integer',
        'descricao' => 'required|string|max:191',
        'data_despesa'=>'date|before:tomorrow',
        'valor' => 'required'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function index(Request $request)
    {
        //
        $validation = Validator::make($request->all(), [
            'usuario_id' => 'integer'
        ]);

        if ($validation->fails())
        {
            return json_encode(['status'=>-100, 'msg'=>$validation->errors()]);
        }
        if ($request->input('usuario_id') != null) {
            $despesas = Despesa::get()->where('usuario_id', $request->input('usuario_id'));
        } else {
            $despesas = Despesa::all();
        }

        return json_encode(['status'=>200, 'record'=>$despesas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validation = Validator::make($request->all(), $this->validaDespesa);

        if ($validation->fails())
        {
            return json_encode(['status'=>-100, 'msg'=>$validation->errors()]);
        }
        try {

            $despesa = new Despesa();
            $despesa->usuario_id = $request->input('usuario_id');
            $despesa->descricao = $request->input('descricao');
            $despesa->data_despesa = $request->input('data_despesa');
            $despesa->valor = $request->input('valor');
            $despesa->Save();

            $user = User::find($request->input('usuario_id'));
            Mail::to($user)->send(new NovaDespesa($user));

            return json_encode(['status'=>200, 'record'=>$despesa]);



        } catch (Exception $e) {

            return json_encode(['status'=>400, 'msg'=>$e->getMessage()]);
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
        $despesa = Despesa::find($id);
        if (isset($despesa)) {
            return json_encode(['status'=>200, 'record'=>$despesa]);
        }
        return json_encode(['status'=>400, 'msg'=>'Despesa não encontrada']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
        $validation = Validator::make($request->all(), $this->validaDespesa);

        if ($validation->fails())
        {
            return json_encode(['status'=>-100, 'msg'=>$validation->errors()]);
        }
        try {

            $despesa = Despesa::find($id);

            if (isset($despesa)) {

                $despesa->usuario_id = $request->input('usuario_id');
                $despesa->descricao = $request->input('descricao');
                $despesa->data_despesa = $request->input('data_despesa');
                $despesa->valor = $request->input('valor');
                $despesa->Save();
                return json_encode(['status' => 200, 'record' => $despesa]);
            }

        } catch (Exception $e) {

            return json_encode(['status'=>400, 'msg'=>$e->getMessage()]);
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
        //
        $despesa = Despesa::find($id);
        if (isset($despesa)) {
            $despesa->delete();
            return json_encode(['status' => 200, 'record' => $despesa]);
        }
        return json_encode(['status'=>400, 'msg'=>'Despesa não encontrada']);
    }
}
