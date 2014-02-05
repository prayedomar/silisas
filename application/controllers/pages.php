<?php

class Pages extends CI_Controller {

//significa que Pges puede utilizar los metodos y variables
    //de CI_Controller
    public function view($page = 'home') {
        //SI no se especifica un metodo en la URL ejecuta el metodo index. SI no existe error 404
        if (!file_exists('application/views/pages/' . $page . '.php')) {
            // Whoops, we don't have a page for that!
            //En php concateno con . punto
            //show_404();
        }

        echo 'parametro: ' . $page;
        $data['title'] = ucfirst($page); // Capitalize the first letter
        $this->load->view('templates/header', $data);
       // $this->load->view('pages/' . $page, $data); //home
      //  $this->load->view('pages/about', $data);
      //  $this->load->view('footer', $data);
   
      //  $this->load->library('email');
/*Enviar correo
$this->email->from('you@yourdomain', 'Tu Principe');
$this->email->to('prayedomar@hotmail.com,k-pollita@hotmail.com');
$this->email->subject('Karen te amo');
$this->email->message('Gracias por Hacerme la Vida mas Linda');
$this->email->send();

/*echo 'insertamos un dato:';
$data1 = array(
               'id' => 7,
               'title' => 'rev 7' ,
               'slug' => 'slug 7',
               'text' => 'text 7'
            );

$this->db->insert('news', $data1); 
echo 'ya lo insertamos.';*/
    
$this->output->enable_profiler(TRUE);   //ESta linea solo se puede colocar 
                   //Dentro de un metodo de la clase. Nunca Afuera.
    }
    public function index(){
        echo 'Pages - Index';
    }

}
?>