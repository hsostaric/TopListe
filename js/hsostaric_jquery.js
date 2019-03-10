

$(document).ready(function () {
    $('#korime2').blur(function () {
        var korime2 = $("#korime2").val();
        $.ajax({
            url: "provjeraAjax.php",
            type: 'post',
            data: {korime2: korime2},

            success: function (odgovor) {
                if (odgovor > 0) {
                   
                    $("#korime2").addClass("zadaca3");
                    greska1=true;
                     event.preventDefault();
                    
                } else {
                    
                     $("#korime2").removeClass("zadaca3");
                    greska1=false;
                     event.preventDefault();
                }
            }
        });

    });

    $('#email').blur(function (event) {
        var email = $('#email').val();

        var regic = new RegExp(/^(?=^.{10,30}$)(?=^[A-Za-z0-9])[A-Za-z0-9\.]+@[A-Za-z0-9]+\.[A-Za-z]{2,}$/);
        var uvjetRegica = regic.test(email);
        if (uvjetRegica === false) {


            alert("Pogresna vrijednost e-maila");
            $("#email").addClass("zadaca3");
            greska2=true;
            event.preventDefault();
        } else {

            alert("OK");
            $("#email").removeClass("zadaca3");
            greska2=false;
             event.preventDefault();
        }


    }
    );

    $('#lozinka1').blur(function (event) {
        var lozinka = $('#lozinka1').val();

        var regic = new RegExp(/^[a-zA-Z1-9]\w{4,23}/);
        var uvjetRegica2 = regic.test(lozinka);
        if (uvjetRegica2 === false) {
          
            $('#lozinka1').addClass("zadaca3");
            greska3=true;
             event.preventDefault();
           
        } else {
            $('#lozinka1').removeClass("zadaca3");
            greska3=false;
            event.preventDefault();
        }


    }
    );
    
    $('#lozinka2').blur(function (event) {
        var lozinka2 = $('#lozinka2').val();
        var lozinka1= $('#lozinka1').val();

        
        if (lozinka1!==lozinka2) {
          
            $('#lozinka1').addClass("zadaca3");
             $('#lozinka2').addClass("zadaca3");
             event.preventDefault();
           
        } else {
            $('#lozinka1').removeClass("zadaca3");
            $('#lozinka2').removeClass("zadaca3");
            event.preventDefault();
        }


    }
    );
    
     $('#ime').blur(function (event) {
        var ime = $('#ime').val();

        
        if (ime === "") {
          
            $('#ime').addClass("zadaca3");
            greska3=true;
             event.preventDefault();
           
        } else {
            $('#ime').removeClass("zadaca3");
            greska3=false;
            event.preventDefault();
        }


    }
    );
    
    $('#prezime').blur(function (event) {
        var prezime = $('#prezime').val();

        
        if (prezime === "") {
          
            $('#prezime').addClass("zadaca3");
            greska3=true;
             event.preventDefault();
           
        } else {
            $('#prezime').removeClass("zadaca3");
            greska3=false;
            event.preventDefault();
        }


    }
    );

   


 $('#table_id').DataTable({
     ordering:false
 });
});