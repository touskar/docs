<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="description" content="">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>PayTech - Documentation</title>
    <link href="http://fonts.googleapis.com/css?family=Raleway:700,300" rel="stylesheet"
          type="text/css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/prettify.css">
    <link rel="stylesheet" href="css/paytech.css">
</head>
<body data-post="http://www.egrappler.com/free-product-documentation-template/">
<nav>
    <div class="container">
        <h1>PAY <span style="color: #135571;background: white;border-radius: 10px;padding: 2px 5px">TECH</span></h1>
        <div id="menu">
            <ul class="toplinks" id="menu-top" >
                <li><a href="#introduction" class=" " style="margin: 0px 8px;padding: 0" data-xtr-key="menu_intro">Introduction</a></li>
                <li><a href="#payement" style="margin: 0px 8px;padding: 0" data-xtr-key="menu_payment">Paiement</a></li>
                <li><a href="#webpayment" style="margin: 0px 8px;padding: 0" data-xtr-key="menu_client">Coté Client</a></li>
                <li><a href="#serverpayment" style="margin: 0px 8px;padding: 0" data-xtr-key="menu_server">Coté Serveur</a></li>
                <li><a href="#mobilepayment" style="margin: 0px 8px;padding: 0" data-xtr-key="menu_mobile">Mobile</a></li>
                <li><a href="#ipn" style="margin: 0px 8px;padding: 0;margin-right:15px" data-xtr-key="menu_ipn">IPN</a></li>
            </ul>
            <select name="lang" id="lang" class="lang_select" style="display: none">
                <option value="fr" data-xtr-key="lang_fr">Frenshhh</option>
                <option value="en" data-xtr-key="lang_en">English</option>

            </select>
        </div>
        <a id="menu-toggle" href="#" class=" ">&#9776;</a> </div>

</nav>
<header class="myHeader">
    <div class="">
        <h4 class="docs-header" style="margin-top:19px;text-align: left;font-weight: bold;margin-left: 100px;"><span style="color: white;background: black;padding: 5px 10px;border-radius: 10px">Documentation</span> </h4>
    </div>
</header>

<section>
    <div class="container">
        <ul class="docs-nav" id="menu-left" style="font-size: 15px !important;top: 16px !important;line-height: 25px;">
            <li ><strong data-xtr-key="menu_intro">Introduction</strong></li>
            <li><a href="#generalite" class=" " data-xtr-key="menufl_generalty">Généralite</a></li>
            <li><a href="#depot" class=" " data-xtr-key="menufl_depos">Dépôt officiel - Plugins</a></li>
            <li><a href="#urlbase" class=" " data-xtr-key="menufl_urlbase">URL de base</a></li>
            <li><a href="#apikey" class=" " data-xtr-key="menufl_apikey">Clés API</a></li>
            <!--<li class="separator"></li>-->
            <li ><strong data-xtr-key="menu_payment">Paiement</strong></li>
            <li><a href="#paymentredirect" class=" " data-xtr-key="menu_payment_redirect">Paiement Avec Redirection</a></li>
            <li><a href="#paymentrequest" class=" " data-xtr-key="menu_payment_request">Demande de paiement </a></li>
            <li ><strong data-xtr-key="menu_payment_server">Paiement Coté Serveur</strong></li>
            <li><a href="#nodejsPayement" class=" " data-xtr-key="nodejs">NODE JS</a></li>
            <li><a href="#phpPayement" class=" " data-xtr-key="php">PHP</a></li>
            <!--<li><a href="#phpSDKPayement" class=" ">PHP SDK</a></li>-->
            <li ><strong data-xtr-key="menu_payment_client">Paiement Coté Client</strong></li>
            <li><a href="#payementweb" class=" " data-xtr-key="menu_payment_web">Web</a></li>
            <li ><strong data-xtr-key="menu_payment_mobile">Paiement mobile</strong></li>
            <li><a href="#payementAndoid" class=" " data-xtr-key="menu_payment_android">Android</a></li>
            <!--<li><a href="#payementIos" class=" ">IOS</a></li>-->
            <li ><strong data-xtr-key="menu_payment_ipn">IPN</strong></li>
            <li><a href="#ipnfonctionment" class=" " data-xtr-key="menu_payment_function">Fonctionnement</a></li>
            <li><a href="#ipnusecase" class=" " data-xtr-key="menu_payment_usercase">Utilisation</a></li>

        </ul>
        <div class="docs-content">
            <h2 id="introduction" data-xtr-key="menu_intro"> Introduction</h2>
            <p data-xtr-key="p1">Cette page vous aidera à démarrer avec PayTech. Vous serez opérationnel en un tour main!</p>
            <p data-xtr-key="p1">Vous pouvez telecharger la version PDF resumé avec des example en Java <a href="https://doc.paytech.sn/PayTech.pdf" target="_blank">ici</a></p>
            <h3 id="generalite" data-xtr-key="menufl_generalty"> Généralité</h3>
            <p data-xtr-key="p2"> Notre page de documentation vous donne toutes les informations dont vous pourriez avoir besoin pour utiliser nos API.</p>

            <p data-xtr-key="p3">
                Les SDK pour chaque plateforme sont ajoutés progressivement et mis à jour régulièrement. Ils sont repartis en deux catégories:
            </p>
            <ul>
                <li data-xtr-key="p4">
                    Les SDK coté client pour Navigateur web, Android et Ios et les SDK coté serveur PHP, RUBY,
                    PYTHON, NODEJS qui permettent d'interagir avec les serveurs PayTech.
                </li>
                <li data-xtr-key="p5">
                    CORS n'est pas activé dans les serveurs PayTech, et vos clés API doivent rester confidentielles,
                    d'où la nécessité de passer par un controller coté serveur pour faire une demande de token
                </li>
            </ul>
            <h3 id="depot" data-xtr-key="menufl_depos"> Dépôt officiel</h3>
            <p data-xtr-key="p6">
                Tous les fichiers sont disponibles dans les liens suivant:
            </p>
            <ul>
                <li data-xtr-key="p7-drupal">
                    Pour Drupal – Commerce : <a href="https://doc.paytech.sn/downloads/sdk/drupal/commerce_paytech.zip?v=5.0.0" target="_blank">https://doc.paytech.sn/downloads/sdk/drupal/commerce_paytech.zip</a>
                </li>
                <li data-xtr-key="p7">
                    Pour Wordpress – Woocommerce - PayTech 6.0.3 - Support update Woocommerce - 07 Avril 2025 12H02 : <a href="https://doc.paytech.sn/downloads/sdk/woocomerce/paytech_woocommerce.zip?v=6.0.3" target="_blank">https://doc.paytech.sn/downloads/sdk/woocomerce/paytech_woocommerce.zip?v=6.0.3</a>
                </li>
                <li data-xtr-key="p8">
                    Pour Prestashop : <a href="https://doc.paytech.sn/downloads/sdk/prestashop/paytech.zip?v=1" target="_blank">https://doc.paytech.sn/downloads/sdk/prestashop/paytech.zip?v=1</a>
                </li>
                <li data-xtr-key="p8">
                    PHP : <a href="https://doc.paytech.sn/downloads/sdk/paytech_php.zip" target="_blank">https://doc.paytech.sn/downloads/sdk/paytech_php.zip</a>
                </li>
                <li data-xtr-key="p8_1">
                    Boutique test : <a href="https://doc.paytech.sn/downloads/sdk/shop_test.zip" target="_blank">https://doc.paytech.sn/downloads/sdk/shop_test.zip</a>
                </li>
                <li data-xtr-key="p8_1">
                    Library & Projet test Android: <a href="https://doc.paytech.sn/downloads/sdk/paytech_android.zip" target="_blank">https://doc.paytech.sn/downloads/sdk/paytech_android.zip</a>
                </li>
                <li data-xtr-key="p8_1">
                    Library IOS: <a href="https://cocoapods.org/pods/Paytech" target="_blank">https://cocoapods.org/pods/Paytech</a>
                </li>
            </ul>
            <h3 id="urlbase" data-xtr-key="menufl_urlbase"> L'URL de base</h3>
            <p>
                <span data-xtr-key="url_base_is">L'URL de base de l'API est:</span>
            <pre class="prettyprint">

 URL_BASE :  "https://paytech.sn/api"
        </pre>
            </p>
            <h3 id="apikey" data-xtr-key="menufl_apikey"> Clés API</h3>
            <p data-xtr-key="p9">
                Une clé d'interface de programmation d'applications (clé API) est
                un code utilisé par les programmes informatiques qui utilisent nos API.
            </p>
            <p data-xtr-key="p10">
                Pour utiliser l'API de PayTech API, vous devez vous inscrire sur la plateforme. Une fois dans le Dashbord,
                cliquer sur le menu Paramètres, puis sur API pour récupérer vos clés.
                Vous avez aussi la possibilité de les régénérer.
            </p>
            <p data-xtr-key="p11">
                Toutes les requêtes envoyées par le site marchand vers notre API doivent comporter obligatoirement
                la clé API et la clé secrète dans les headers de la requête :
            </p>
            <pre class="prettyprint">
   SHELL
  # you can pass the correct header for each request in this way
  curl "api_endpoint_here"
  -H "API_KEY: 1afac858d4fa5ec74e3e3734c3829793eb6bd5f4602c84ac4a5069369812915e"
  -H "API_SECRET: 96bc36c11560f2151c4b43eee310cefabc2e9e9000f7e315c3ca3d279e3f98ac"
        </pre>
            <hr>
            <h2 id="payement" data-xtr-key="menu_payment"> Paiement</h2>

            <h3 id="paymentredirect" data-xtr-key="menu_payment_redirect"> Paiement Avec Redirection</h3>
            <p data-xtr-key="p12">
                Le Paiement avec Redirection vous permet de rediriger votre client vers notre plateforme afin qu'il puisse achever le processus de paiement.<br>
                Le traitement avec redirection est idéal pour les marchands qui désirent déléguer l’hébergement de la page de paiement
                à PayTech. Lors du processus de paiement, le client est redirigé vers une page de PayTech pour saisir les informations de paiement.<br>
                Les clients peuvent choisir de payer à partir d'une variété d'options de paiement disponibles sur notre plateforme.

            </p>
            <h3 id="paymentrequest" data-xtr-key="menu_payment_request"> Demande de paiement</h3>
            <p data-xtr-key="p13">
                La demande de paiement définit une expérience utilisateur cohérente entre les méthodes de paiement,
                les systèmes de paiement, les plateformes et les commerçants.
            </p>
            <p data-xtr-key="p14">
                Pour que le client puisse faire un paiement, le site marchand doit d'abord adresser une demande de paiement à notre API. Celui-ci va
                lui envoyer un token de 20 octets qui sera l'identifiant de la demande.
            </p>
            <pre class="prettyprint">

    POST "/payment/request-payment"
        </pre>
            <div>
                <table class="table">
                    <thead>
                    <tr>
                        <th data-xtr-key="parameter">Paramètres</th>
                        <th data-xtr-key="default">Défaut</th>
                        <th data-xtr-key="require">Obligatoire</th>
                        <th data-xtr-key="desc">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td >item_name</td>
                        <td >NULL</td>
                        <td data-xtr-key="yes">OUI</td>
                        <td data-xtr-key="item_name">Nom du produit</td>
                    </tr>
                    <tr>
                        <td>item_price</td>
                        <td>NULL</td>
                        <td data-xtr-key="yes">OUI</td>
                        <td data-xtr-key="command_price">Prix de la commande</td>
                    </tr>
                    <tr>
                        <td>ref_command</td>
                        <td>NULL</td>
                        <td data-xtr-key="yes">OUI</td>
                        <td data-xtr-key="ref_command">Une référence de commande générée par le site marchand doit être unique pour chaque demande de paiement.</td>
                    </tr>
                    <tr>
                        <td>command_name</td>
                        <td>""</td>
                        <td data-xtr-key="yes">OUI</td>
                        <td data-xtr-key="command_name">Description de la commande</td>
                    </tr>
                    <tr>
                        <td>currency</td>
                        <td>XOF</td>
                        <td data-xtr-key="no">NON</td>
                        <td data-xtr-key="currency">L'une de ses devises: ['XOF', 'EUR', 'USD', 'CAD','GBP','MAD']</td>
                    </tr>
                    <tr>
                        <td>env</td>
                        <td>prod</td>
                        <td data-xtr-key="no">NON</td>
                        <td data-xtr-key="env">Environnement ['test', 'prod']</td>
                    </tr>
                    <tr>
                        <td>ipn_url</td>
                        <td data-xtr-key="param_global">Paramètre global compte</td>
                        <td data-xtr-key="no">NON</td>
                        <td data-xtr-key="ipndesc">URL IPN (voir section IPN pour plus de détails). Si elle n'est pas fournie, on utilisera la valeur définie dans les paramètres globaux du site marchand. Seules les URL en HTTPS sont acceptées.</td>
                    </tr>
                    <tr>
                        <td>success_url</td>
                        <td data-xtr-key="param_global">Paramètre global compte</td>
                        <td data-xtr-key="no">NON</td>
                        <td data-xtr-key="descurlsuccess">URL vers laquelle le client est redirigé après le paiement. Si elle n'est pas fournie, on utilisera la valeur définie dans les paramètres globaux du site marchand.</td>
                    </tr>
                    <tr>
                        <td>cancel_url</td>
                        <td data-xtr-key="param_global">Paramètre global compte</td>
                        <td data-xtr-key="no">NON</td>
                        <td data-xtr-key="desccancelurl"> URL vers laquelle le client est redirigé quand il annule le paiement. Si elle n'est pas fournie, on utilisera la valeur définie dans les paramètres globaux du site marchand.</td>
                    </tr>
                    <tr>
                        <td>custom_field</td>
                        <td>NULL</td>
                        <td data-xtr-key="no">NON</td>
                        <td data-xtr-key="customerfieddesc">Donnée additionnelle qui sera envoyée au client vers l'URL IPN. Elle doit être une chaine de caractères encodés en JSON.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <h2 id="serverpayment" data-xtr-key="menu_payment_server">Paiement Coté Serveur</h2>
            <p data-xtr-key="p15">
                CORS n'est pas activé dans les serveurs PayTech, et vos clés API doivent rester confidentielles, d’où
                la nécessité de passer par un Controller coté serveur pour faire une demande de token.
            </p>
            <p data-xtr-key="p16">
                Votre application devrait disposer d'un Controller Web qui
                se chargera de faire la demande de token aux serveurs de PayTech.
            </p>
            <h3 id="nodejsPayement"> NODE JS</h3>
            <pre class="prettyprint">

    let paymentRequestUrl = "https://paytech.sn/api/payment/request-payment";
    let fetch = require('node-fetch');// http client
    let params = {
    item_name:"Iphone 7",
    item_price:"560000",
    currency:"XOF",
    ref_command:"HBZZYZVUZZZV",
    command_name:"Paiement Iphone 7 Gold via PayTech",
    env:"test",
    ipn_url:"https://domaine.com/ipn",
    success_url:"https://domaine.com/success",
    cancel_url:"https://domaine.com/cancel",
    custom_field:JSON.stringify({
       custom_fiel1:"value_1",
       custom_fiel2:"value_2",
    })
    };

    let headers = {
    Accept: "application/json",
    'Content-Type': "application/json",
    API_KEY:"1afac858d4fa5ec74e3e3734c3829793eb6bd5f4602c84ac4a5069369812915e",
    API_SECRET:"96bc36c11560f2151c4b43eee310cefabc2e9e9000f7e315c3ca3d279e3f98ac",
    };

    fetch(paymentRequestUrl, {
    method:'POST',
    body:JSON.stringify(params),
    headers: headers
    })
    .then(function (response) {
    return response.json()
    })
    .then(function (jsonResponse) {
    console.log(jsonResponse)
    /*
    {
        "success":1,
        "redirect_url":"https://paytech.sn/payment/checkout/98b1c97af00c8b2a92f2",
      token:"98b1c97af00c8b2a92f2"}

    */
    })

</pre>
            <h3 id="phpPayement">PHP</h3>
            <pre class="prettyprint">

    function post($url, $data = [], $header = [])
    {
        $strPostField = http_build_query($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $strPostField);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($header, [
            'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
            'Content-Length: ' . mb_strlen($strPostField)
        ]));

        return curl_exec($ch);
    }

    $postFields = array(
            "item_name"    =>$res['name'],
        "item_price"   => $total,
        "currency"     => "xof",
        "ref_command"  =>  $ref_commande,
        "command_name" =>  $commande,
        "env"          =>  $environnement,
        "success_url"  =>  $success_url,
        "ipn_url"		   =>  $ipn_url,
        "cancel_url"   =>  $success_url,
        "custom_field" =>   $customfield
    );

    $jsonResponse = post('https://paytech.sn/api/payment/request-payment', $postfields, [
        "API_KEY: ".$api_key,
        "API_SECRET: ".$api_secret
    ]);

    die($jsonResponse);

</pre>

            <h3 id="phpPayement">PHP SDK</h3>
            <pre class="prettyprint">

    require 'PayTech.php';

    $item = ....;//object
    const BASE_URL  = 'https://sample.domaine.com';
    $jsonResponse = (new PayTech($apiKey, $apiSecret))->setQuery([
            'item_name' => $item->name,
            'item_price' => $item->price,
            'command_name' => "Paiement {$item->name} Gold via PayTech",
        ])->setCustomeField([
            'item_id' => $id,
            'time_command' => time(),
            'ip_user' => $_SERVER['REMOTE_ADDR'],
            'lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE']
        ])
            ->setTestMode(true)
            ->setCurrency($item->currency)
            ->setRefCommand(uniqid())
            ->setNotificationUrl([
                'ipn_url' => BASE_URL.'/ipn.php', //only https
                'success_url' => BASE_URL.'/index.php?state=success&id='.$id,
                'cancel_url' =>   BASE_URL.'/index.php?state=cancel&id='.$id
            ])->send();

     die($jsonResponse);
            </pre>
            <p data-xtr-key="p17">
                Si tous les paramètres ont été correctement fournis, l'API PayTech renverra la réponse suivante :
            </p>
            <pre class="prettyprint" style="background: #e3edf2 !important;">
    {
    "success": 1,
    "token": "XJJDKS8S8SHNS2",
    "redirect_url": "https://paytech.sn/payment/checkout/XJJDKS8S8SHNS2",
    }
</pre>

            <br>
            <h2 id="webpayment" data-xtr-key="menu_payment_client">Paiment Coté Client</h2>
            <h3 id="payementweb" data-xtr-key="websdk">SDK Web</h3>
            <pre class="prettyprint" id="sdkwebContent">
</pre>
            <div>
                <table class="table">
                    <thead>
                    <tr>
                        <th data-xtr-key="present_mode_web_popop">Présentation Mode</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>PayTech.OPEN_IN_POPUP</td>
                    </tr>
                    <tr>
                        <td>PayTech.OPEN_IN_NEW_TAB</td>
                    </tr>
                    <tr>

                        <td>PayTech.OPEN_IN_SAME_TAB</td>
                    </tr>
                    <tr>
                        <td>PayTech.DO_NOTHING</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <br>
            <h2 id="mobilepayment" data-xtr-key="menu_payment_mobile">Paiment mobile</h2>
            <h3 id="payementAndoid" data-xtr-key="menu_payment_android">Android</h3>
            <p data-xtr-key="p19">
                Vous devez tout d'abord inclure la librairie PayTech disponible sous format .aar dans votre projet.<br>
                La librairie est disponible ici :
            </p>
            <pre class="prettyprint">
    LINK : "https://doc.paytech.sn/downloads/sdk/paytech_android/paytech-sdk/build/outputs/aar/paytech-sdk-release.aar"
</pre>
            <a href="https://doc.paytech.sn/downloads/sdk/paytech_android/paytech-sdk/build/outputs/aar/paytech-sdk-release.aar">https://doc.paytech.sn/downloads/sdk/paytech_android/paytech-sdk/build/outputs/aar/paytech-sdk-release.aar</a>
            <p data-xtr-key="p20">
                Ensuite vous devez importer ces deux classes dans votre activité.
            </p>
            <pre class="prettyprint">
    import sdk.paytech.sn.PCallback;
    import sdk.paytech.sn.PayTech;
</pre>
            <p data-xtr-key="p21">
                Ensuite, il reste plus qu’à invoquer la classe.
            </p>
            <pre class="prettyprint">
    button.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Log.i("click", "click on button");
                HashMap<String,Object> params = new HashMap<>();
                params.put("item_id", 2);

                new PayTech(MainActivity.this)
                        .setRequestTokenUrl("https://sample.paytech.sn/paiement.php")
                        .setParams(params)
                        .setPresentationMode(PayTech.FLOATING_VIEW ) // optional default to FULL_SCREEN
                        .setFloatTopMargin(25) // optional default to 25
                        .setLoadingDialogText("Chargement") //optional Chargement
                        .setCallback(new PCallback() {
                            @Override
                            public void onResult(Result result) {
                                if(result == Result.SUCCESS)
                                {
                                    Toast.makeText(MainActivity.this, "Paiement Effectuer", Toast.LENGTH_SHORT).show();
                                }
                                else if(result == Result.CANCEL)
                                {
                                    Toast.makeText(MainActivity.this, "Vous avez annulez le paiement", Toast.LENGTH_SHORT).show();
                                }
                                else if(result == Result.ERROR)
                                {
                                    Toast.makeText(MainActivity.this, "Erreur lors du paiement", Toast.LENGTH_SHORT).show();
                                }
                            }
                        })
                        .send();
            }
        });
</pre>
            <!--<h3 id="payementIos">IOS</h3>
    <pre class="prettyprint">
        import Paypress:
    </pre>-->
            <br>
            <h2 id="ipn" data-xtr-key="menu_payment_ipn">IPN</h2>
            <p data-xtr-key="p22">
                Cette section est relative à tout ce qui concerne les notifications de paiement.
            </p>
            <h3 id="ipnfonctionment" data-xtr-key="menu_payment_function">Fonctionnement</h3>
            <p data-xtr-key="p23">
                Lorsqu'un client finalise un paiement ou qu'un paiement est annulé,
                PayTech envoie une notification à votre serveur à l'URL IPN que vous avez indiquée.
            </p>
            <p data-xtr-key="p24">
                Cette notification comporte toutes les informations pertinentes de paiement de votre client que vous avez transmises lors de la demande de token
                de paiement ainsi que le type d'évènement.
            </p>
            <p data-xtr-key="p25">
                À la réception d’une notification, votre serveur devra vérifier
                si la requête provient bien de PayTech en comparant le hash en
                sha256 de sa clé API et clé secrète
                avec les deux clés que nous lui avons envoyées dans la requête.
            </p>
            <p data-xtr-key="p26">
                Voici la liste des paramètres de la notification :
            </p>
            <div>
                <table class="table">
                    <thead>
                    <tr>
                        <th data-xtr-key="parameter">Paramètres</th>
                        <th data-xtr-key="desc">Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>type_event</td>
                        <td data-xtr-key="type_even">Le type d'évènement qui a déclenché la requête vers l'URL IPN. L'une de ses valeurs possible. ['sale_complete', 'sale_canceled']</td>
                    </tr>
                    <tr>
                        <td>client_phone</td>
                        <td data-xtr-key="ipn_phone">Le numéro de téléphone du client</td>
                    </tr>
                    <tr>

                        <td>payment_method</td>
                        <td data-xtr-key="payment_method">La méthode de paiement ['Carte Bancaire', 'PayPal', 'Orange Money', 'Joni Joni', 'Wari','Poste Cash']</td>
                    </tr>
                    <tr>
                        <td>item_name</td>
                        <td data-xtr-key="item_name">Nom du produit</td>
                    </tr>
                    <tr>
                        <td>item_price</td>
                        <td data-xtr-key="command_price">Prix de la commande</td>
                    </tr>
                    <tr>
                        <td>ref_command</td>
                        <td data-xtr-key="ref_command2">La référence de commande générée par le site marchand.</td>
                    </tr>
                    <tr>
                        <td>command_name</td>
                        <td data-xtr-key="command_name">Description de la commande</td>
                    </tr>
                    <tr>
                        <td>currency</td>
                        <td data-xtr-key="currency">L'une de ses devises: ['XOF', 'EUR', 'USD', 'CAD','GBP','MAD']</td>
                    </tr>
                    <tr>
                        <td>env</td>
                        <td data-xtr-key="env">Environnement ['test', 'prod']</td>
                    </tr>
                    <tr>
                        <td>custom_field</td>
                        <td data-xtr-key="customerfieddesc2">Donnée additionnelle envoyée aux serveurs de PayTech lors de la demande de token</td>
                    </tr>
                    <tr>
                        <td>token</td>
                        <td data-xtr-key="token_paiment">Le token de paiement</td>
                    </tr>
                    <tr>
                        <td>api_key_sha256</td>
                        <td data-xtr-key="hash_api_key">La clé API du site marchand hachée avec l'algorithme sha256</td>
                    </tr>
                    <tr>
                        <td data-xtr-key="hash_secret_key">api_secret_sha256</td>
                        <td>La clé secrète du site marchand hachée avec l'algorithme sha256</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h3 id="ipnusecase" data-xtr-key="use_caseDesc_ipn">Utilisation de la Notification de Paiement</h3>
            <h4 >NODEJS/EXPRESSEJS</h4>
            <pre class="prettyprint">
    function SHA256Encrypt(password) {
        let crypto = require('crypto');
        let sha256 = crypto.createHash('sha256');
        sha256.update(password);
        return sha256.digest('hex');
    }

    app.post('/my-ipn', (req, res) => {
        let type_event = req.body.type_event;
        let custom_field = JSON.parse(req.body.custom_field);
        let ref_command = req.body.ref_command;
        let item_name = req.body.item_name;
        let item_price = req.body.item_price;
        let devise = req.body.devise;
        let command_name = req.body.command_name;
        let env = req.body.env;
        let token = req.body.token;
        let api_key_sha256 = req.body.api_key_sha256;
        let api_secret_sha256 = req.body.api_secret_sha256;

        let my_api_key = process.env['API_KEY'];
        let my_api_secret = process.env['API_SECRET'];

        if(SHA256Encrypt(my_api_secret) === api_secret_sha256 && SHA256Encrypt(my_api_key) === api_key_sha256)
        {
            //from PayTech
        }
        else{
            //not from PayTech
        }
    });
</pre>
            <h4 >PHP/LARAVEL</h4>
            <pre class="prettyprint">
    Route::post('/my-ipn', function () {
        $type_event = Input::get('type_event');
        $custom_field = json_decode(Input::get('custom_field'), true);
        $ref_command = Input::get('ref_command');
        $item_name = Input::get('item_name');
        $item_price = Input::get('item_price');
        $devise = Input::get('devise');
        $command_name = Input::get('command_name');
        $env = Input::get('env');
        $token = Input::get('token');
        $api_key_sha256 = Input::get('api_key_sha256');
        $api_secret_sha256 = Input::get('api_secret_sha256');

        $my_api_key = env('API_KEY');
        $my_api_secret = env('API_SECRET');

        if(hash('sha256', $my_api_secret) === $api_secret_sha256 && hash('sha256', $my_api_key) === $api_key_sha256)
        {
            //from PayTech
        }
        else{
            //not from PayTech
        }
    });
</pre>

            <h4 >PYTHON/DJANGO</h4>
            <pre class="prettyprint">
    import hashlib
    def ipn(request):
    if request.method == "POST":
    inputtxt = request.POST['getrow']
    api_key_sha256 = request.POST['api_key_sha256'];
    api_secret_sha256 = request.POST['api_secret_sha256']
    my_api_secret_sha256 = hashlib.sha256(b'here my api secret').hexdigest()
    my_api_key_sha256 = hashlib.sha256(b'here my api key').hexdigest()
    if my_api_key_sha256 == api_key_sha256 and my_api_secret_sha256 == api_secret_sha256:
      # from paytech

	return HttpResponse(...)
</pre>
            <h4 >PHP</h4>
            <pre class="prettyprint">
    function get($name)
    {
        return !empty($_POST[$name]) ? $_POST[$name] : '';
    }

    $type_event = get('type_event');
    $custom_field = json_decode(get('custom_field'), true);
    $ref_command = get('ref_command');
    $item_name = get('item_name');
    $item_price = get('item_price');
    $devise = get('devise');
    $command_name = get('command_name');
    $env = get('env');
    $token = get('token');
    $api_key_sha256 = get('api_key_sha256');
    $api_secret_sha256 = get('api_secret_sha256');
    $my_api_key = env('API_KEY');
    $my_api_secret = env('API_SECRET');
    if (hash('sha256', $my_api_secret) === $api_secret_sha256 && hash('sha256', $my_api_key) === $api_key_sha256) {
        //from PayTech}else{    //not from PayTech}
    }
</pre>

        </div>
    </div>
</section>
<!--<section class="vibrant centered">
  <div class="container">
    <h4> This documentation template is provided free by eGrappler.com. Opineo is a feedback
      collection widget and is available for free download <a href="http://www.greepit.com/Opineo/admin-form.html"> here</a></h4>
  </div>
</section>-->
<footer>
    <div class="container">
        <p> &copy; 2020 <a class="active" href="https://paytech.sn">Paytech.sn</a> </p>
    </div>
</footer>
<script src="js/jquery.min.js"></script>

<script type="text/javascript" src="js/prettify/prettify.js"></script>
<script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js?lang=css&skin=sunburst"></script>
<script src="js/layout.js"></script>
<script src="js/jquery.localscroll-1.2.7.js" type="text/javascript"></script>
<script src="js/jquery.scrollTo-1.4.3.1.js" type="text/javascript"></script>
<script src="js/plugin/jquery.xtr.min.js"></script>
<script src="_js/plugin/lang.js"></script>
<script src="js/myscripte.js?v=1"></script>

</body>
</html>
