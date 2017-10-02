<?php require( "auto.php" );?>
<!doctype html>
<html lang="en">
<head>
	<?php include("blocks/header.php") ?>
	<style>

        #mobile{
            display:none;
            text-align: center;
            color:white;
            font-size:22px;
            font-weight: bold;
        }

        #mobile img{
            width: 30vw;
        }

        body{
            background-color:black;
        }
		.remote_control{
            position:relative;
            width: 100%;
        }
        .remote_control:after{
            content:'';
            clear:both;
            display: table;
            width: 100%;
        }
        .status{
            height: 50px;
            position:absolute;
            bottom: 0;
            left:0;
            padding:5px;
            width: 100%;

            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -moz-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -moz-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;


        }

        .status .light{
            -webkit-border-radius:100%;
            -moz-border-radius:100%;
            border-radius:100%;
            background-color: green;
            height: 25px;
            width: 25px;
        }
        .status.disconnected .light{
            -webkit-border-radius:100%;
            -moz-border-radius:100%;
            border-radius:100%;
            background-color: red;
            height: 25px;
            width: 25px;
        }
        .status .text{
            color:lightgray;
            margin-left: 10px;
            margin-right: 10px;
        }

        .remote_control .buttons .button_container{
            float:left;
            height: 25vw;
            width: 25vw;
            padding:1vw;
        }
        .remote_control .buttons .button{
            background-color:#2b669a;
            color:white;
            -webkit-border-radius:10px;
            -moz-border-radius:10px;
            border-radius:10px;
            border: 1px solid white;
            cursor: pointer;
            text-align: center;
            height: 100%;
            width: 100%;

            padding-top: 3vw;

        }
        .remote_control .buttons .button:hover{
            background-color:#2e6da4;
        }
        .remote_control .buttons .button img{
            width: 50%;
        }

        @media only screen and (max-width : 400px){
            .remote_control .buttons .button_container{
                height: 50vw;
                width: 50vw;
            }
        }

	</style>
</head>
<body>

<div class="content" id="mobile">
    <p>Please use a mobile device to access the house controls</p>
    <img src="img/mobile.png" />
</div>

<div id="main">

    <div class="status">
        <div class="light"></div>
        <div class="text">
        </div>
    </div>
    <div class="remote_control">
                <div class="buttons">
                    <div class="button_container">
                        <div class="button" instr="turnLight('room_1');">
                           <div>
                               <img src="img/light.png" /><br />
                               Living Room
                           </div>
                        </div>
                    </div>
                    <div class="button_container">
                        <div class="button" instr="turnLight('room_2');">
                            <div>
                                <img src="img/light.png" /><br />
                                Kitchen
                            </div>
                        </div>
                    </div>
                    <div class="button_container">
                        <div class="button" instr="turnLight('room_3');">
                            <div>
                                <img src="img/light.png" /><br />
                                Bedroom
                            </div>
                        </div>
                    </div>
                    <div class="button_container">
                        <div class="button" instr="turnLight('room_4');">
                            <div>
                                <img src="img/light.png" /><br />
                                Dining Room
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</div>
<?php include("blocks/footerjs.php") ?>
<script type="text/javascript">


    var house_connection_interval = 3000;
    var connected_text = "Connected"
    var disconnected_text = "No Connected";
    var mobile = false;

    function bindButton(){
        $(".button").click(function( e ){
            var ins = $( this ).attr( "instr" );
            $.ajax({
                type: "POST",
                url: 'process/house.php?key=<?php echo @$_GET["key"] ?>',
                data: 'add_instruction='+ins,
                success: function(){
                    logM( "Added Instructions" );
                },
            });
            e.preventDefault();
        });
    }

    function checkHouseConnection(){
        logM( "Check House" );
        $.ajax({
            url: "process/house.php?check_house=<?php echo $_GET["key"] ?>",
            context: document.body
        }).done(function( data ) {
            logM( data );
            if( data == 1 ){
                logM( "Connected" );
                $(".status").removeClass("disconnected");
                $(".status .text").html( connected_text );
            }else{
                logM( "No Connected" );
                $(".status").addClass("disconnected");
                $(".status .text").html( disconnected_text );
            }
        });
        setTimeout(checkHouseConnection,house_connection_interval)
    }

    function detectMobile(){
        if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
//                This means its a mobile device
            logM( "Its a mobile device" );
            mobile = true; // We indicate that this is a mobile device

        }else{
            logM( "Its a desktop/laptop computer device" );
            mobile = false;
            $("#main").hide();
            $("#mobile").show();
        }
    }

    $( document ).ready(function( start_event ){

        logM( "Document Ready" );
//        detectMobile();

        bindButton();
        checkHouseConnection();


    });
</script>
</body>
</html>