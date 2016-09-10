<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Clean Blog</title>

    <!-- Bootstrap Core CSS -->
    <link href="/cleanblog/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/cleanblog/css/clean-blog.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    

    <link rel="StyleSheet" href="/dtree/js/dtree.css" type="text/css" />
    <script type="text/javascript" src="/dtree/js/dtree.js"></script>


</head>

<body>




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

        {!! $dtreegoodview !!}

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


</body>

</html>