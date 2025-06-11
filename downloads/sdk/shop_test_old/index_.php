<?php
require_once 'conf.php';
?>

<!doctype html>
<html lang="en">

<!-- Mirrored from demo.angelostudio.net/1e-shop/1.3/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 24 Mar 2018 10:14:44 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <title>Boutique PayTech</title>
    <meta charset="utf-8">
    <meta name="author" content="PayTech">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/turquoise.css" class="colors">
	<link rel="shortcut icon" href="img/ico/32.png" sizes="32x32" type="image/png">
	<link rel="apple-touch-icon" href="img/ico/60.png" type="image/png">
	<link rel="apple-touch-icon" sizes="72x72" href="img/ico/72.png" type="image/png">
	<link rel="apple-touch-icon" sizes="120x120" href="img/ico/120.png" type="image/png">
	<link rel="apple-touch-icon" sizes="152x152" href="img/ico/152.png" type="image/png">
    <link rel="stylesheet" href="https://paytech.sn/cdn/paytech.min.css">

    <meta property="og:site_name" content="PayTech">
    <meta property="og:title" content="PayTech">
    <meta property="og:description"
          content="PayTech | Boutique Démo">
    <meta property="og:image" content="https://sample.paytech.sn/img/snapshoot.png">
    <meta property="og:url" content="https://sample.paytech.sn/">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <meta name="generator"
          content="PayTech | Boutique Démo"/>
    <script src="https://paytech.sn/cdn/paytech.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://www.googletagmanager.com/gtag/js?id=UA-30125205-11"></script>
    <script>
        if(location.hostname !== "localhost" || location.hostname !== "127.0.0.1"){
            $.getScript( "https://sample.paytech.sn/js/analytic.js", function( data, textStatus, jqxhr ) {
                console.log( "Load analytics 2 was performed." );
            });
        }
    </script>
    <style>
        .hidden{
            display: none !important;
        }
    </style>

    <script>
        function buy(btn) {
         //   $('.close-modal').click();
		loader('add');

            setTimeout(function () {
                var selector = pQuery(btn);

                (new PayTech({
                    item_id          :   1,
                })).withOption({
                    requestTokenUrl           :   '<?= BASE_URL ?>/paiement.php',
                    method              :   'POST',
                    headers             :   {
                        "Accept"          :    "text/html"
                    },
                    prensentationMode   :   PayTech.OPEN_IN_POPUP,
                    didPopupClosed: function (is_completed, success_url, cancel_url) {
                        window.location.href = is_completed === true ? success_url  : cancel_url;
                    },
                    willGetToken        :   function () {
                        console.log("Je me prepare a obtenir un token");
                        selector.prop('disabled', true);
                    },
                    didGetToken         : function (token, redirectUrl) {
                        console.log("Mon token est : " +  token  + ' et url est ' + redirectUrl );
                        selector.prop('disabled', false);
			loader('remove');
                    },
                    didReceiveError: function (error) {
                        alert('erreur inconnu', error.toString());
                        selector.prop('disabled', false);
			loader('remove');
                    },
                    didReceiveNonSuccessResponse: function (jsonResponse) {
                        console.log('non success response ',jsonResponse);
                        alert(jsonResponse.errors);
                        selector.prop('disabled', false);
			loader('remove');
                    }
                }).send();
            }, 500)

        }
    </script>
</head>

<body id="home">
	<a id="menu-link" href="#" class="hidden">
		<span class="menu-icon"></span>
	</a>

	<div class="overlay" id="overlay hidden">
        <nav class="overlay-menu">
          <ul>
            <li><a href="#home" class="smooth-scroll">Home</a></li>
            <li><a href="#start" class="smooth-scroll">About product</a></li>
            <li><a href="#showcase" class="smooth-scroll">Showcase</a></li>
            <li><a href="#requirements" class="smooth-scroll">Requirements</a></li>
            <li><a href="#features" class="smooth-scroll">Features</a></li>
            <li><a href="#contact" class="smooth-scroll">Contact</a></li>
            <li><a href="https://www.google.com/" class="smooth-scroll">External link</a></li>
          </ul>
        </nav>
      </div>

	<div id="wrap">
		<section id="hero" class="m-center text-center bg-shop full-height">
			<div class="center-box">

					<div class="hero-unit ">
						<div class="container ">
						<h1 class="title"><a href="https://paytech.sn/" target="_blank" style="color: #fff;"><b>PayTech </b></a> Boutique Test</h1>
						<h3><a href="https://paytech.sn/" target="_blank" style="color: #fff;"><b>PayTech </b></a> la meilleure plateforme de paiement en ligne Web & Mobile</h3>
						<p><br>
                            Si vous souhaitez vendre des produits, à partir de votre site,<br>
						<a href="https://paytech.sn/" target="_blank" style="color: #fff;"><b>PayTech</b></a> est la solution.<br>
						</p>
						<br>
						<a class="btn white" href="#" data-toggle="modal" onclick="buy(this)"><b>55 000 FCFA</b> Acheter</a>
						</div>
					</div>
					<div class="col-sm-12 img-hero"></div>

			</div>
		</section>
        <section id="features" class="features-1">
            <div class="container padding-top-bottom">
                <div class="row header">
                    <div class="col-md-12">
                        <h2>Partenaires</h2>
                        <p>Ils nous ont fait confiance</p>
                    </div>
                </div>
                <div class="container" >
                    <div class="row">
                        <div class="col-md-4 anima scale-in ">
                            <article class="text-center">
                                <a href="https://www.livrezmoi.sn/" target="_blank"><img src="https://lh3.googleusercontent.com/fyeKXmKdVA1h2qRkZukJoMbyJ1uid7UbaHZLPAaW_nlquDb53Gz1yolHbYBTFcXD1Z2g=s360" alt="#" class="zoom-img img-fluid center-block" style="height: 180px;object-fit: cover;"></a>
                                <h3>Livrez moi</h3>
                                <p>L’application de livraison rapide et à la demande</p>
                            </article>
                        </div>
                        <div class="col-md-4 anima scale-in d1">
                            <article class="text-center">
                                <a href="https://app.manueluniversitaire.com/" target="_blank"><img src="https://app.manueluniversitaire.com/previews/appx/images/others/logo.png" alt="#" class="zoom-img img-fluid center-block" style="height: 180px;object-fit: cover;"></a>
                                <h3>​Le Manuel Universitaire </h3>
                                <p> Le Manuel Universitaire vous propose en version numérique l’integralité des documents de votre faculté ainsi que des autres faculté:</p>
                            </article>
                        </div>
                        <div class="col-md-4 anima scale-in d2">
                            <article class="text-center">
                                <a href="https://www.sendeal.sn/" target="_blank"><img src="data:image/png;base64,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" alt="#" class="zoom-img img-fluid center-block" style="height: 180px;object-fit: cover;"></a>
                                <h3>6Point9</h3>
                                <p>Commandez et Payez vos articles en ligne. La livraison à Dakar se fera en 24H et en moins de 4 jours pour nos clients de l'extérieur via DHL</p>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="footer-1 text-center">
            <div class="container-fluid">
                <a href="#home" class="back-to-top smooth-scroll"><i class="fa fa-chevron-up"></i></a>
                <p> <i class="fa fa-heart color-text"></i> Par <a href="https://paytech.sn/" target="_blank">PayTech</a>.</p>
            	<ul class="social-links-2 ">
					<li><a href="https://www.linkedin.com/company/empiredigital.info" target="_blank"><i class="fa fa-linkedin"></i></a></li>
					<li><a href="https://twitter.com/pexpresse" target="_blank"><i class="fa fa-twitter"></i></a></li>
				</ul>
            </div>
        </div>

		<div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-hidden="true">
		    <div class="modal-dialog modal-lg">
		        <div class="modal-content">
		            <div class="modal-header">
		                <a class="close-modal" href="#" data-dismiss="modal">
		                    <span class="menu-icon"></span>
		                </a>
		                <img src="img/cup1.jpg" alt="" class="img-fluid">
		            </div>
		            <div class="modal-body">
		                <h3 class="text-center"><b>PaperCup - Mockup </b>(55 000 FCFA)</h3>
		            </div>
		            <div class="modal-footer">
		            	<div class="container">
			                <form id="buy" action="" class="myform" method="post" novalidate>
			                    <div class="form-group">
			                        <button onclick="buy()" type="button" class="btn btn-block">Confirmer</button>
			                    </div>
			                    <p class="text-center"><a href="#" data-dismiss="modal">Annuler</a>
			                    </p>
			                </form>
		            </div>
		            </div>
		        </div>
		    </div>
		</div>


		<section id="style-switcher" class="hidden" >
            <h2>Colors <a href="#"><i class="fa fa-tint"></i></a></h2>
            <ul>
                <li id="yellow"></li>
                <li id="purple"></li>
                <li id="turquoise"></li>
                <li id="blue"></li>
                <li id="red"></li>
                <li id="brown"></li>
            </ul>
        </section>

	</div>
    <!-- Core scrips -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
    <script type="text/javascript" src="js/core.js"></script>
    <script type="text/javascript" src="js/menu-overlay.js"></script>
    <script type="text/javascript" src="js/placeholders.min.js"></script>
    <!-- end core scripts -->
    <!-- sliders -->
    <script type="text/javascript" src="js/owl.carousel.min.js"></script>
   	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.waitforimages/2.4.0/jquery.waitforimages.min.js"></script>
   	<script type="text/javascript" src="js/sliders.js"></script>
	<script type="text/javascript" src="js/jquery.counterup.min.js"></script>
	<script type="text/javascript" src="js/numbers.js"></script>
	<script type="text/javascript" src="js/contact.js"></script>
	<script type="text/javascript" src="js/parallax.js"></script>
	<script type="text/javascript" src="js/ga.js"></script>
	<script>
	function loader(removeAdd){
	  var html=`<div id="loader" style='z-index:99999999;width:100%;height:100%;position:fixed;top:0;left:0;background:rgba(0,0,0,0.2);'>
	<div style='width:50px;height:50px;margin: 25% auto;'><img src='img/loader.gif' style='width:100%;height:100%'></div>
	  </div>`;
	  if(removeAdd === 'add')
	  {
	    jQuery("html").append(html);
	  }
	  else {
	    jQuery("#loader").remove()
	  }
	}
	</script>

</body>


</html>
