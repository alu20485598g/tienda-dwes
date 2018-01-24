<?php
  session_start();
  include("./include/funciones.php");
  $connect = connect_db();

  if (isset ($_GET['redirect'])===true)
    {$redirect=$_GET['redirect'];}
  else
    {$redirect='index.php';}

  $title = "Tienda DAW -> Cesta";
  include("./include/header.php");
  require './include/ElCaminas/Carrito.php';
  require './include/ElCaminas/Producto.php';
  require './include/ElCaminas/Productos.php';
  use ElCaminas\Carrito;


  $carrito = new Carrito();
  //Falta comprobar qué acción: add, delete, empty
  $action="view";
  if(isset($_GET["action"])){
    $action=$_GET["action"];
  }
  if ($action=="add") {
    $carrito-> addItem($_GET["id"], 1);
  }else if ($action=="delete") {
    $carrito-> deleteItem($_GET["id"]);
  }else if ($action=="empty"){
    $carrito-> empty();
  }

?>
<script>
  function eliminar(){
    if(confirm("¿Seguro que quieres eliminar este producto?")){
      return true;
    }else{
      return false;
    }
  }

  function eliminar2(){
    if(confirm("¿Seguro que quieres vaciar el carrito?")){
      return true;
    }else{
      return false;
    }
  }
</script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
    paypal.Button.render({

        env: 'sandbox', // sandbox | production

        // PayPal Client IDs - replace with your own
        // Create a PayPal app: https://developer.paypal.com/developer/applications/create
        client: {
            sandbox:    'AURtFahgo3cuV-8J35gOhzh0AhTk36fnkHRkuGs-ZBiDoRdzd4NGvRDFFvzkCqmoU3puoZ3FOyS2zkDX',
            production: '<insert production client id>'
        },

        // Show the buyer a 'Pay Now' button in the checkout flow
        commit: true,

        // payment() is called when the button is clicked
        payment: function(data, actions) {

            // Make a call to the REST api to create the payment
            return actions.payment.create({
                payment: {
                    transactions: [
                        {
                            amount: { total: <?php echo $carrito->getTotal()?>, currency: 'EUR' }
                        }
                    ]
                }
            });
        },

        // onAuthorize() is called when the buyer approves the payment
        onAuthorize: function(data, actions) {

            // Make a call to the REST api to execute the payment
            return actions.payment.execute().then(function() {
                window.alert('Pago Completado!');
                document.location.href = 'gracias.php';
            });
        }

    }, '#paypal-button-container');

</script>
<div class="row carro">
  <h2 class='subtitle' style='margin:0'>Carrito de la compra</h2>
  <?php echo $carrito->toHtml();?>
  <span><a href='./carro.php?action=empty' onclick='return eliminar2()' class='btn btn-danger' style='position:relative;'>Vaciar carrito</a></span>
  <span><a href="<?php echo $redirect ?>" onclick='' class='btn btn-primary' style='position:relative;'>Seguir comprando</a></span>
  <span><a href='#' id='paypal-button-container' style='position:relative; float:right;'></a></span>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Detalle del producto</h4>
      </div>
      <div class="modal-body">
        <iframe src='#' width="100%" height="600px" frameborder=0 style='padding:8px'></iframe>
      </div>
    </div>
  </div>
</div>
<?php
$bottomScripts = array();
$bottomScripts[] = "modalIframeProducto.js";
include("./include/footer.php");
?>
