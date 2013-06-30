<?php
$link = current_url()."?download=true";
?>
<style>
    body{
        margin: 0;
        width:100%;
    }
    div{
        margin: 20px;
        background-color: #ffffee;
        border-radius: 3px;
        border: 1px solid #cccccc;
        width: 50%;
    }
    p{
        padding:20px;
        border:1px solid #cccccc;
        border-radius: 3px;
        margin: 20px;
        font-family: "Helvetica Neue";
        width: auto;
        background-color: white;
    }
    a, a:visited, a:focus{
        text-decoration: none;
        color: #0E3E7E;
    }
    a:hover{
        text-decoration: underline;
        cursor: pointer;
    }
    div.logo{
        color:#FFFFFF;
        background-color: transparent;
        font-size: 28px;
        border: none;
        font-weight:bold;
        padding:15px;
        font-family:lato,sans-serif;
        margin-top:0;
        margin-bottom: 0;

    }
    .logo span{
        color:#77C62E;}
   div.header{
        background-color:#29384B;
        width:100%;
        padding-bottom:10px;
        padding-top:10px;
        border-radius: 0;
       border: none;

       margin:0;
    }
</style>
<div class="header">
    <div class="logo">cham<span>soft</span></div>
</div><!--end header-->
<div>
<p>Thank You for downloading your Invoice</p>
<p><a id="download" href="<?=$link?>"><strong>=></strong> If your Invoice does not download in a few seconds click here</a></p>
    </div>
<script>
    setTimeout(function(){
        window.location.href = "<?=$link?>"
    },1000)
</script>
