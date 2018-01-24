//El elemento DOM con el código HTML de la máscara de cargando
var mask = $("#mask");
////El elemento dom que contiene los productos
var container = $("#data-container");
//Datos de la primera página cargados por ajax. Ver el evento popstate
var firstUrlData = null;
function attachPaginator(){
  //selector css de los enlaces de paginación
  $( ".pagination li a" ).click(function(event) {
    event.preventDefault();
    var href = $( this ).attr('href');
    //No hacerlo si no tiene href o si es la página actual.
    // <li class="active">
    //   <a href="???????>2</a>
    //  </li>
    if (("#" != href) && (!$( this ).parent().hasClass("active"))){
        //Mostrar la máscaramask.show();
        mask.show();
        //Añadir a la url el estado exclusive
        hrefExclusive = href + "&state=exclusive";
        //Crear el objeto ajax
        var jqxhr = $.get( hrefExclusive, function(data) {
          //Cuando devuelve los datos, hago un scroll animado para que la página se vea desde el principio
          //De otra forma, la página se quedaría con el scroll que tuviera en el momento de hacer la carga
          $('html, body').animate({scrollTop : 0},1000);
          //Este timeout sólo lo hago porque de otra forma lo hace
          //tan rápido que no se nota el efecto. De hecho lo podéis quitar
          setTimeout(function(){
            //Actualizar el html de container con los datos devueltos
            container.html( data );
            // El navegador asocia data con este href, de tal forma que al hacer history back
            // le pasa estos datos al evento popstate
            history.pushState(data, null, href);
            //Ocultar la máscara
            mask.hide();
            //Volver a poner los eventos en el paginador
            attachPaginator();
            //Volver a poner los eventos para la ventana modal del producto
            //Hacedlo sólo si también habéis puesto la lógica para la ventana modal
            //en la página de categoria.php
            attachModalInfo();
          }, 600);

        })
        .fail(function() {
          alert( "error" );
          mask.hide();
        });

    }
  });
}
attachPaginator();
