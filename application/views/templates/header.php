<html>
    <head>
        <title><?php echo $title ?> - CodeIgniter 2 Tutorial</title>
        <style>
            p {
                background-color: #fff;
                margin: 40px;
                font: 25px/20px normal Helvetica, Arial, sans-serif;
                color: #4F5155;
            }
        </style>
    </head>
    <!--ojo que title NO TIENE DOBLE TT: TITLE
    <!--LOS COMENTARIOS en html son con etiquetas bocas-->
    <body>
        <h1>Header: CodeIgniter 2 tutorial-</h1>
        <p >
            <?php
            echo 'esto es lo que hay en la Base de DAtos.<br>';
            $consulta = $this->db->get('news');
            foreach ($consulta->result() as $fila) {
                echo $fila->id.' --- ';
                echo $fila->title.' --- ';
                echo $fila->slug.' --- ';
                echo $fila->text."<br>";
            }
            ?>
        </p>
        <strong>&copy;2013</strong>
    </body> 
</html>