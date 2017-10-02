<?php require( "auto.php" ); require("index.code.php") ?>
<!doctype html>
<html lang="en">
<head>
    <?php include("blocks/header.php") ?>
    <style>
        #mobile{
            display:none;
            text-align: center;
            color:black;
            font-size:22px;
            font-weight: bold;
        }
        #mobile img{
            width: 80vw;
        }
        .room{
            border:1px solid black;
            box-sizing: border-box;
            color:black;
            height: 50vh;
            text-transform: uppercase;
            background-position: center center;
            background-size: cover;
            text-align: center;
            font-weight: bold;
            position:relative;
            float:left;
            width: 50%;
        }
        .room:after{
            background-color:black;
            content:'';
            top:0;
            left:0;
            height: 100%;
            opacity: 0.7;
            position:absolute;
            width: 100%;
            z-index: 100;
        }
        .room div.text{
            display:none;
        }
        .room:hover div.text{
            display: block;
            width: auto;
            position: absolute;
            background-color: white;
            padding: 10px;
            top: 5px;
            left: 50%;
            transform: translateX(-50%);
            z-index:150;
        }
        .room.light:after{
            content:'';
            background: black;
            opacity: 0;
        }
        #room_1{
            background-image: url("img/room_1.jpg");
        }
        #room_2{
            background-image: url("img/room_2.jpg");
        }
        #room_3{
            background-image: url("img/room_3.jpg");
        }
        #room_4{
            background-image: url("img/room_4.jpg");
        }

        #control{
            background-color:white;
            border-bottom-right-radius:10px;
            cursor:pointer;
            top:0;
            left:0;
            padding:10px;
            position:fixed;
            opacity: 0.7;
            z-index: 250;
        }
        #control:hover{
            opacity: 1;
        }
        #qrcode{
            color: #949494;
            display:none;
            background-color: white;
            -webkit-border-radius:10px;
            -moz-border-radius:10px;
            border-radius:10px;
            top:50%;
            left:50%;
            transform: translateX( -50% ) translateY( -50% );
            padding:60px;
            position:absolute;
            z-index: 500;
        }
        #qrcode .divider{
            margin-top:40px;
            text-align: center;
            position: relative;
        }
        #qrcode .divider hr{
            margin-top: 5px;
            margin-bottom: 5px;
            border: 0;
            border-top: 1px solid #eee;
        }
        #qrcode .divider span{

            position: absolute;
            left: 50%;
            top: -10px;
            transform: translateX( -50% );
            background-color: white;
            padding-left: 10px;
            padding-right: 10px;
        }

        #qrcode .qrcode-form{
            margin-top:25px;
            text-align: center;
        }
        #qrcode .qrcode-form .field input{
            color:#969696;
            text-align: center;
            width: 100%;
        }
        #qrcode .qrcode-form .field input.error{
            border: 1px solid red;
        }
        #qrcode .qrcode-form .button{
            margin-top:10px;
        }
        #qrcode .qrcode-form .button button{
            background-color:#2b669a;
            border:none;
            -webkit-border-radius:10px;
            -moz-border-radius:10px;
            border-radius:10px;
            padding-top:4px;
            padding-bottom: 4px;
            color:white;
            width: 100%;
        }
        #qrcode .qrcode-form .button button:hover{
            background-color:#4080b9;
        }



    </style>
</head>
<body>
    <div class="" id="mobile">
        <p>Please use a desktop/laptop computer to access this page</p>
        <img src="img/no_mobile.png" />
    </div>

    <div class="" id="main">
        <div id="control">
            <img src="img/remote_control.png" />
        </div>

        <div id="qrcode">
            <a title="Close" href="<?php echo getControlUrl(); ?>"><img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=<?php echo urlencode( getControlUrl() ) ?>" /></a>
            <div class="divider">
                <hr />
                <span>OR</span>
            </div>
            <div class="qrcode-form">
                <form method="POST" action="" id="sendmethelinkform">
                    <input type="hidden" name="key" id="key" value="<?php echo @$_GET["key"] ?>" />
                    <input type="hidden" name="control_url" id="control_url" value="<?php echo getControlUrl(); ?>" />
                    <p>Send me the link to</p>
                    <div class="field">
                        <input title="Insert your email here" name="email" id="email" type="email" placeholder="example@domain.com" />
                    </div>
                    <div class="button">
                        <button title="Send me the link" type="submit"> <i class="fa fa-send-o" aria-hidden="true"></i> Send </button>
                    </div>
                </form>
            </div>
        </div>
            <div class="room" id="room_1">
                <div class="text">Living Room</div>
            </div>
            <div class="room" id="room_2">
                <div class="text">Kitchen</div>
            </div>
            <div class="room" id="room_3">
                <div class="text">Bedroom</div>
            </div>
            <div class="room" id="room_4">
                <div class="text">Dining Room</div>
            </div>
    </div>
    <?php include("blocks/footerjs.php") ?>
    <script type="text/javascript">

        var storing_interval = 3000;
        var reading_interval = 200;
        var mobile = false;

        var url_to_send_email = 'process/house.php?send_email=<?php echo $_GET["key"] ?>';

        function storeHouseKey(){
            logM( "Store House Call" );
            $.ajax({
                type: "POST",
                url: "process/house.php?store_key=<?php echo $_GET["key"] ?>",
                context: document.body
            }).done(function() {
                logM( "Call success" );
                setTimeout(storeHouseKey, storing_interval);
            });
        }
        function readInstructions(){
            logM( "Read Instructions" );
            $.ajax({
                url: "process/house.php?read_instructions=<?php echo $_GET["key"] ?>",
                context: document.body
            }).done(function( data ) {
                logM( "Read Instructions:" + data );
                eval( data );
                setTimeout(readInstructions , reading_interval);
            });
        }

        function turnLight( id ){
            $('#'+id).toggleClass('light');
        }

        function detectMobile(){
            if( isMobile() ) {
//                This means its a mobile device
                logM( "Its a mobile device" );
                mobile = true; // We indicate that this is a mobile device
                $("#main").hide();
                $("#mobile").show();
            }else{
                logM( "Its a desktop/laptop computer device" );
            }
        }

        function bindControlClick(){
            $( "#control" ).click( function( e ){
                $( "#qrcode" ).fadeIn();
                e.preventDefault();
            } );

            $( "#qrcode img" ).click(function( e ){

                $( this ).closest( "#qrcode" ).fadeOut();
                e.preventDefault();
            });
        }

        function bindFormSendLink(){
            $( "#sendmethelinkform" ).submit(function( e ){
                var _form_ = $( this );
                $( this ).find("input").removeClass( "error" );
                logM( "Sending the email" );
                $.ajax( {
                    type: "POST",
                    url: url_to_send_email,
                    data: $( this ).serialize(),
                    success: function( response ) {
                        logM( response );
                        if( parseInt( response ) == 1 ){
                            _form_.closest( "#qrcode" ).fadeOut();
                        }else{
                            _form_.find("input").addClass( "error" );
                        }
                    }
                } );
                e.preventDefault();
            });
        }

        $( document ).ready(function( start_event ){
            logM( "Document Ready" );
            detectMobile();
            if( !mobile ){
                storeHouseKey();
                readInstructions();
                bindControlClick();
                bindFormSendLink();
            }
        });
    </script>
</body>
</html>