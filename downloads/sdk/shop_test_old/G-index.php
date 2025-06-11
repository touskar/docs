<?php
    require_once 'conf.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Boutique</title>
    <link rel="stylesheet" href="https://cdn.payexpresse.com/v1/payexpresse.min.css">
    <script src="https://cdn.payexpresse.com/v1/payexpresse.min.js"></script>

    <style>
        .buy{
            display: block;
            height: 60px;
            width: calc(100% + 10px);
            text-decoration: none;
            color: #fff;
            background-color: #26a69a;
            text-align: center;
            letter-spacing: .5px;
            -webkit-transition: .2s ease-out;
            transition: .2s ease-out;
            cursor: pointer;
            border: 0;
        }



        .wrapper{
            display: inline-block;
            width: 190px;
            margin: 22px;
        }

        .previous-success{
            border: 2px dashed green;
        }

        .previous-cancel{
            border: 2px dashed red;
        }

    </style>
</head>
<body>
    <div>

    </div>

    <?php
        $id = null;
        $class = '';
        if(!empty($_GET['state']) && !empty($_GET['id']))
        {
            $id = intval($_GET['id']);
            $state = $_GET['state'];
            $class = 'previous-'.$state;
        }

    ?>

    <?php
        $items = json_decode(file_get_contents('article.json'), true)['articles'];

        foreach ($items as $item){
            $item = (object)$item;
            echo '<div class="wrapper">';
            echo sprintf('<img class="%s" src="https://api.adorable.io/avatars/200/%s">', $item->id === $id ? $class : '',$item->name);
            echo sprintf('<button class="buy" onclick="buy(this)" data-item-id="%s" >%s <br>Stock: %s<br>%s %s<br>Acheter</button>', $item->id, $item->name, $item->stock,number_format($item->price, 1,',','.'), $item->currency);
            echo '</div >';
        }
    ?>


<script>


    function buy(btn) {
        var selector = pQuery(btn);

        (new PayExpresse({
            item_id          :   selector.attr('data-item-id'),
        })).withOption({
            requestTokenUrl           :   '<?= BASE_URL ?>/paiement.php',
            method              :   'POST',
            headers             :   {
              "Accept"          :    "text/html"
            },
            prensentationMode   :   PayExpresse.OPEN_IN_POPUP,
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
            },
            didReceiveError: function (error) {
                alert('erreur inconnu', error.toString());
                selector.prop('disabled', false);
            },
            didReceiveNonSuccessResponse: function (jsonResponse) {
                console.log('non success response ',jsonResponse);
                alert(jsonResponse.errors);
                selector.prop('disabled', false);
            }
        }).send({
            pageBackgroundRadianStart:'#0178bc',
            pageBackgroundRadianEnd:'#00bdda',
            pageTextPrimaryColor:'#333',
            paymentFormBackground:'#fff',
            navControlNextBackgroundRadianStart:'#608d93',
            navControlNextBackgroundRadianEnd:'#28314e',
            navControlCancelBackgroundRadianStar:'#28314e',
            navControlCancelBackgroundRadianEnd:'#608d93',
            navControlTextColor:'#fff',
            paymentListItemTextColor:'#555',
            paymentListItemSelectedBackground:'#eee',
            commingIconBackgroundRadianStart:'#0178bc',
            commingIconBackgroundRadianEnd:'#00bdda',
            commingIconTextColor:'#fff',
            formInputBackgroundColor:'#eff1f2',
            formInputBorderTopColor:'#e3e7eb',
            formInputBorderLeftColor:'#7c7c7c',
            totalIconBackgroundRadianStart:'#0178bc',
            totalIconBackgroundRadianEnd:'#00bdda',
            formLabelTextColor:'#292b2c',
            alertDialogTextColor:'#333',
            alertDialogConfirmButtonBackgroundColor:'#0178bc',
            alertDialogConfirmButtonTextColor:'#fff'
        });
    }
</script>
</body>
</html>


