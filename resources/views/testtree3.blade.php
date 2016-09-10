@extends('layouts.apptree')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Geneaology</div>
                Countdownline: {{$array['countdownline']}}
                <div class="panel-body">
                    You are logged in! <br>
                    {{$array['getdomain']}}
                </div>
            </div>
        </div>
    </div>
</div>



    <!-- jQuery -->
    <script src="/cleanblog/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/cleanblog/js/bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <!-- // <script src="/cleanblog/js/clean-blog.min.js"></script> -->

   
<div class="row">

    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">

<h2>Vista de Invitados, Voluntarios, Amigos y Participantes</h2>

{{$lazyusers}}

<div class="dtree">

    <p><h2><u><a style="font-size:1.3em" class="btn btn-primary" href="javascript: d.openAll();">Abrir</a></u> | <u><a style="font-size:1.3em" class="btn btn-primary" href="javascript: d.closeAll();">Cerrar</a></u></h2></p>

     <script type="text/javascript">

    <!--

        d = new dTree('d');
                d.add(0,-1,"<style=text-align:left>Inicio</style>");d.add(1,0, '<span style=border-radius:3px;padding:1px;background-color:#D3D3D3;>datos873@gmail.com</span>','#');d.add(2,1, '<span style=border-radius:3px;padding:1px;background-color:#D3D3D3;>datos874@gmail.com</span>','#');document.write(d);

        //-->
    </script>
            
</div>



<p>

            <!-- foreach($parentlessusers as $p)
            <form action="/asignparent" method="post">

     <input type="hidden" name="_token" value=" csrf_token() }}">

            ! $p->email !!}
            <input type="hidden" name="userid" value="$p->id}}">
            <input type="text" name="parentname" value="" placeholder="Nombre del Padre">
            <input type="submit" value="Asignar">
            </form> 
            endforeach
            -->
</p>

</div>
</div>

@endsection
