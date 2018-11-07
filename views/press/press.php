<?php 
$extra_vars = element('extravars',element('post',$view));
$post_md = element('post_md',element('post',$view));
$post_link = element('link',$view);
$popstate_url="";

$popstate="";
if($extra_vars['popstate']==='enable'){
    $popstate=1;
    if($extra_vars['view_type']==='random'){
        $popstate_url= $post_link[mt_rand(0,count($post_link)-1)]['pln_url'];
    } else {
        foreach($post_link as $value){

            if($value['pln_start'] <= date('H') && $value['pln_end'] >= date('H') ){

                $popstate=1;
                $popstate_url= $value['pln_url'];
                break;
            }            
        }
        
    }
}

?>

 <?php if ( $popstate === 1 && $popstate_url) { ?>

<script language = "javascript"> 
 var stateObj = { forward: "forward" };
    $(document).ready(function() {
    if (window.history && window.history.pushState) {

        <?php if($post_md==='ilyosisa'){ ?>
          if(window.history.length < 3)
            window.history.pushState(stateObj, null, 'namdonews.com');
          else 
            window.history.replaceState(stateObj, null, 'namdonews.com');
        <?php } else { ?>
          if('state' in window.history && window.history.state !== null && window.history.state.forward == 'forward') window.history.replaceState(stateObj, null, document.location.href);
          else window.history.pushState(stateObj, null, document.location.href);
        <?php } ?>
        var popped = ('state' in window.history && window.history.state !== null && window.history.state.forward == 'forward'), initialURL = location.href;

        $(window).bind('popstate', function (event) {

          // Ignore inital popstate that some browsers fire on page load
          var initialPop = !popped && location.href == initialURL
          popped = true
          if (initialPop) return;
          
          parent.top.location.replace("<?=$popstate_url?>");
          

        });
    }
});
</script>
<?php } ?>
<script>
  // (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  // (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  // m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  // })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  // ga('create', 'UA-88829342-6', 'auto');
  // ga('send', 'pageview');

</script>
</head>
<body>
</body>
</html>
