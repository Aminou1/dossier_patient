// JavaScript Document
/*<![CDATA[*/
window.addEventListener("load", function (event) {

    $("#formulaire_list").on("submit", function (e) {
        var result = true;
        $(".itech-formater-nombre").each(function (i, node) {
            var real_nombre = getNumberOfString($(node).val());
            if (isNaN(real_nombre)) {
                launch_toast("Veuillez saisir un nombre valide!");
                result = false;
                return 0;
            }
        });
       // e.preventDefault();
        //var montant_affiche = $("#id_montant_afficher").val();       
        //confirmEnvoie('Etes-vous sûrs de vouloir faire ce transfert, montant : ', montant_affiche);
        //console.log(aa);
    });
    function calculTotal(valeur_coupure) {
        var total = 0;
        $(".itech-formater-nombre-hidden").each(function (i, node) {
            var real_nombre = $(node).val();
            var valeur_coupure = getMontantCoupure($(node).attr('id'), '#id_valeur_coupure-');
            //console.log(valeur_coupure);
            if (!isNaN(real_nombre)) {
                total += parseInt(real_nombre * valeur_coupure);
            }
        });
        return total;
    }
    function launch_toast(message) {
        var x = $("#toast");
        x.find("#desc").html(message);
        x.addClass("show");
        setTimeout(function () {
            x.removeClass("show");
        }, 3000);
    }

    function formaterNombre(nombre) {
        return nombre.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ");
    }

    function getNumberOfString(nombre) {
        var nombre = nombre.replace(/ /g, '');
        return parseInt(nombre, 10);
    }
    var montant_affiche = $("#id_montant_afficher");
    var montant = $("#id_montant");

    $(".itech-formater-nombre").on("keyup", function (e) {
        var input = $(this);
        var real_nombre = getNumberOfString(input.val());
        if (!isNaN(real_nombre)) {
            var id_coupure = getIdCoupure(input.data("id"));
            var montant_ligne = $("#id_montant-coupure-" + id_coupure);
            var valeur_coupure = getMontantCoupureParIdCoupure(id_coupure);
            //console.log(montant_ligne);
            $("#" + input.data("id")).val(real_nombre);
            input.val(formaterNombre(real_nombre));
            //console.log(input.data("id"));
            montant_ligne.val(formaterNombre(real_nombre * valeur_coupure));
            var total = calculTotal();
            montant_affiche.val(formaterNombre(total));
            montant.val(total);
        }
        $("#" + input.data("id")).val(real_nombre);
    });


    function getIdCoupure(input_id) {
        var tmp_tbl = input_id.split('-');
        var id_coupure = tmp_tbl[tmp_tbl.length - 1];
        return id_coupure;
    }
    function getMontantCoupureParIdCoupure(id_coupure) {
        var valeur_coupure = $("#id_valeur_coupure-" + id_coupure).val();
        return valeur_coupure;
    }
    function getMontantCoupure(input_id) {
        var id_coupure = getIdCoupure(input_id);
        return getMontantCoupureParIdCoupure(id_coupure);
    }
    function confirmEnvoie(titre, montant) {
        $question = titre + montant + ' ?';
        var result = confirm($question);
        if (result === true) {
            $('#formulaire_list').submit();
        }
return result;
    }
//	$(".itech-formater-nombre").each(function(i,node) {
//            $(node).trigger("keyup");
//	});

}, false);
/*]]>*/